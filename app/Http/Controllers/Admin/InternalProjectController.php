<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PlantillaBolsaHorasExport;
use App\Http\Controllers\Controller;
use App\Imports\BolsaMovimientosImport;
use App\Models\BolsaMovimiento;
use App\Models\Client;
use App\Models\Developer;
use App\Models\DeveloperPayment;
use App\Models\GestionPayment;
use App\Models\InternalProject;
use App\Models\ProjectExpense;
use App\Models\ProjectFile;
use App\Models\ProjectPayment;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InternalProjectController extends Controller
{
    /**
     * Vista principal: agrupada por desarrollador.
     * La lista clásica con filtros avanzados vive en {@see self::todos()}.
     */
    public function index(Request $request)
    {
        return $this->porDesarrollador($request);
    }

    public function todos(Request $request)
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));

        $query = InternalProject::withCount('payments', 'files', 'developerPayments', 'gestionPayments')
            ->withSum('payments', 'monto')
            ->withSum('developerPayments as developer_payments_sum_monto', 'monto')
            ->withSum('gestionPayments as gestion_payments_sum_monto', 'monto');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fuente')) {
            $query->where('fuente', $request->fuente);
        }

        if ($request->filled('moneda')) {
            $query->where('moneda', $request->moneda);
        }

        if ($request->filled('desarrollador')) {
            $query->where('desarrollador_nombre', $request->desarrollador);
        }

        if ($request->filled('recurrente')) {
            $query->where('es_recurrente', $request->recurrente === 'si' ? 1 : 0);
        }

        if ($request->filled('periodo')) {
            $now = now();
            match ($request->periodo) {
                'mes_actual' => $query->where(function ($q) use ($now) {
                    $q->whereBetween('fecha_inicio', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                        ->orWhereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
                        ->orWhereHas('payments', fn ($p) => $p->whereBetween('fecha', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]));
                }),
                'mes_anterior' => $query->where(function ($q) use ($now) {
                    $ini = $now->copy()->subMonth()->startOfMonth();
                    $fin = $now->copy()->subMonth()->endOfMonth();
                    $q->whereBetween('fecha_inicio', [$ini, $fin])
                        ->orWhereBetween('created_at', [$ini, $fin])
                        ->orWhereHas('payments', fn ($p) => $p->whereBetween('fecha', [$ini, $fin]));
                }),
                'este_anio' => $query->whereYear('created_at', $now->year),
                default => null,
            };
        }

        if ($request->filled('cobro')) {
            match ($request->cobro) {
                'pendiente' => $query->whereRaw('precio > (SELECT COALESCE(SUM(monto),0) FROM project_payments WHERE project_payments.internal_project_id = internal_projects.id)'),
                'pagado_total' => $query->whereRaw('precio <= (SELECT COALESCE(SUM(monto),0) FROM project_payments WHERE project_payments.internal_project_id = internal_projects.id)'),
                'sin_cobros' => $query->whereDoesntHave('payments'),
                default => null,
            };
        }

        if ($request->filled('pago_dev')) {
            match ($request->pago_dev) {
                'pendiente' => $query->whereNotNull('desarrollador_pago')
                    ->where('desarrollador_pago', '>', 0)
                    ->whereRaw('desarrollador_pago > (SELECT COALESCE(SUM(monto),0) FROM developer_payments WHERE developer_payments.internal_project_id = internal_projects.id)'),
                'al_dia' => $query->whereNotNull('desarrollador_pago')
                    ->whereRaw('desarrollador_pago <= (SELECT COALESCE(SUM(monto),0) FROM developer_payments WHERE developer_payments.internal_project_id = internal_projects.id)'),
                'sin_dev_asignado' => $query->whereNull('desarrollador_nombre'),
                default => null,
            };
        }

        if ($request->filled('gestion')) {
            match ($request->gestion) {
                'con_gestion' => $query->whereNotNull('vendedor_id'),
                'sin_gestion' => $query->whereNull('vendedor_id'),
                'pendiente_gestion' => $query->whereNotNull('vendedor_id')
                    ->where(function ($q) {
                        $q->where(function ($sub) {
                            // Monto fijo → saldo > 0
                            $sub->where('comision_tipo', 'monto')
                                ->whereColumn('comision_valor', '>', DB::raw('(SELECT COALESCE(SUM(monto),0) FROM gestion_payments WHERE gestion_payments.internal_project_id = internal_projects.id)'));
                        })->orWhere(function ($sub) {
                            // Porcentaje → precio × % > abonado
                            $sub->where('comision_tipo', 'porcentaje')
                                ->whereRaw('(precio * comision_valor / 100) > (SELECT COALESCE(SUM(monto),0) FROM gestion_payments WHERE gestion_payments.internal_project_id = internal_projects.id)');
                        });
                    }),
                default => null,
            };
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('cliente_nombre', 'like', "%{$search}%")
                    ->orWhere('desarrollador_nombre', 'like', "%{$search}%");
            });
        }

        match ($request->get('orden', 'prioridad')) {
            'mayor_saldo_cliente' => $query->orderByRaw('(precio - COALESCE((SELECT SUM(monto) FROM project_payments WHERE project_payments.internal_project_id = internal_projects.id),0)) DESC'),
            'mayor_deuda_dev' => $query->orderByRaw('(COALESCE(desarrollador_pago,0) - COALESCE((SELECT SUM(monto) FROM developer_payments WHERE developer_payments.internal_project_id = internal_projects.id),0)) DESC'),
            'mayor_precio' => $query->orderBy('precio', 'desc'),
            'fecha_entrega' => $query->orderByRaw('fecha_entrega IS NULL, fecha_entrega ASC'),
            'reciente' => $query->orderBy('created_at', 'desc'),
            // PRIORIDAD (default): en_progreso/cotizado primero (por fecha_entrega ASC), pausados, completados al final
            default => $query
                ->orderByRaw("FIELD(estado, 'en_progreso', 'cotizado', 'pausado', 'completado', 'cancelado')")
                ->orderByRaw('fecha_entrega IS NULL, fecha_entrega ASC')
                ->orderBy('created_at', 'desc'),
        };

        $perPage = (int) $request->get('per_page', 15);
        if (! in_array($perPage, [10, 15, 25, 50])) {
            $perPage = 15;
        }

        $projects = $query->paginate($perPage)->withQueryString();

        // Si hay filtro por desarrollador, todas las stats financieras se recalculan sólo para ese dev
        $devFiltro = $request->filled('desarrollador') ? $request->desarrollador : null;

        // Stats operacionales (con o sin filtro de dev)
        $baseProjectsQuery = fn () => InternalProject::query()
            ->when($devFiltro, fn ($q) => $q->where('desarrollador_nombre', $devFiltro));

        $totalCount = $baseProjectsQuery()->count();
        $activosCount = $baseProjectsQuery()->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count();
        $completadosCount = $baseProjectsQuery()->where('estado', 'completado')->count();
        $sinDevCount = $devFiltro
            ? 0  // Si filtra por dev no tiene sentido contar "sin desarrollador"
            : InternalProject::whereNull('desarrollador_nombre')->whereIn('estado', ['en_progreso', 'cotizado'])->count();

        // Stats financieros: proyectos activos del dev filtrado (o todos)
        $activos = $baseProjectsQuery()
            ->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])
            ->withSum('payments', 'monto')
            ->withSum('developerPayments as developer_payments_sum_monto', 'monto')
            ->get();

        // "Por cobrar": todos los proyectos no cancelados (activos + completados + recurrentes vencidos).
        $hoy = now()->endOfDay();
        $paraCobrar = $baseProjectsQuery()
            ->where('estado', '!=', 'cancelado')
            ->withSum('payments', 'monto')
            ->get();

        $porCobrarCop = 0;
        $proyectosConDeuda = 0;
        foreach ($paraCobrar as $p) {
            $saldo = 0.0;
            if ($p->es_recurrente) {
                if ($p->fecha_facturacion && $p->fecha_facturacion->lte($hoy)) {
                    $saldo = (float) $p->precio;
                }
            } else {
                $saldo = max((float) $p->precio - (float) ($p->payments_sum_monto ?? 0), 0);
            }

            if ($saldo > 0) {
                $porCobrarCop += $p->moneda === 'USD' ? $saldo * $usdCop : $saldo;
                $proyectosConDeuda++;
            }
        }

        $porPagarDevCop = 0;
        $devsConSaldo = 0;
        foreach ($activos as $p) {
            $saldoDev = (float) ($p->desarrollador_pago ?? 0) - (float) ($p->developer_payments_sum_monto ?? 0);
            if ($saldoDev > 0) {
                $porPagarDevCop += $p->desarrollador_moneda === 'USD' ? $saldoDev * $usdCop : $saldoDev;
                $devsConSaldo++;
            }
        }

        // Utilidad del mes: flujo de caja neto. Si filtra por dev, sólo cuenta proyectos de ese dev.
        $ini = now()->startOfMonth();
        $fin = now()->endOfMonth();

        // IDs de proyectos del dev filtrado (para scopear los pagos del mes)
        $projectIdsScope = $devFiltro
            ? InternalProject::where('desarrollador_nombre', $devFiltro)->pluck('id')->all()
            : null;

        $ingresosMes = ProjectPayment::with('project:id,moneda')
            ->whereBetween('fecha', [$ini, $fin])
            ->when($projectIdsScope, fn ($q) => $q->whereIn('internal_project_id', $projectIdsScope))
            ->get()
            ->sum(function ($p) use ($usdCop) {
                if ($p->monto_recibido_cop) {
                    return (float) $p->monto_recibido_cop;
                }
                $moneda = optional($p->project)->moneda ?? 'COP';

                return $moneda === 'USD' ? (float) $p->monto * $usdCop : (float) $p->monto;
            });

        $pagosDevMes = DeveloperPayment::whereBetween('fecha', [$ini, $fin])
            ->when($projectIdsScope, fn ($q) => $q->whereIn('internal_project_id', $projectIdsScope))
            ->get()
            ->sum(fn ($p) => $p->moneda === 'USD' ? (float) $p->monto * $usdCop : (float) $p->monto);

        $pagosGestionMes = GestionPayment::whereBetween('fecha', [$ini, $fin])
            ->when($projectIdsScope, fn ($q) => $q->whereIn('internal_project_id', $projectIdsScope))
            ->get()
            ->sum(fn ($p) => ($p->moneda ?? 'COP') === 'USD' ? (float) $p->monto * $usdCop : (float) $p->monto);

        $gastosMes = ProjectExpense::whereBetween('fecha', [$ini, $fin])
            ->when($projectIdsScope, fn ($q) => $q->whereIn('internal_project_id', $projectIdsScope))
            ->get()
            ->sum(fn ($e) => ($e->moneda ?? 'COP') === 'USD' ? (float) $e->monto * $usdCop : (float) $e->monto);

        // Utilidad neta = ingresos − pagos a desarrolladores − pagos a gestión − otros gastos
        $utilidadMes = $ingresosMes - $pagosDevMes - $pagosGestionMes - $gastosMes;

        $proyectosMesCount = $baseProjectsQuery()
            ->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])
            ->where(function ($q) use ($ini, $fin) {
                $q->whereBetween('fecha_inicio', [$ini, $fin])
                    ->orWhereBetween('created_at', [$ini, $fin])
                    ->orWhereHas('payments', fn ($p) => $p->whereBetween('fecha', [$ini, $fin]));
            })
            ->count();

        $stats = [
            'total' => $totalCount,
            'activos' => $activosCount,
            'completados' => $completadosCount,
            'sin_desarrollador' => $sinDevCount,
            'por_cobrar_cop' => $porCobrarCop,
            'proyectos_con_deuda' => $proyectosConDeuda,
            'por_pagar_dev_cop' => $porPagarDevCop,
            'devs_con_saldo' => $devsConSaldo,
            'utilidad_mes' => $utilidadMes,
            'ingresos_mes' => $ingresosMes,
            'pagos_dev_mes' => $pagosDevMes,
            'pagos_gestion_mes' => $pagosGestionMes,
            'gastos_mes' => $gastosMes,
            'proyectos_mes' => $proyectosMesCount,
            'dev_filtro' => $devFiltro,
        ];

        $desarrolladores = InternalProject::whereNotNull('desarrollador_nombre')
            ->where('desarrollador_nombre', '!=', '')
            ->distinct()
            ->orderBy('desarrollador_nombre')
            ->pluck('desarrollador_nombre');

        return view('admin.internal-projects.todos', compact('projects', 'stats', 'desarrolladores', 'usdCop'));
    }

    public function stats(Request $request)
    {
        $data = $this->buildStatsData($request);

        return view('admin.internal-projects.stats', $data);
    }

    public function detalle(Request $request)
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $toCop = fn ($monto, $moneda) => ($moneda === 'USD' ? (float) $monto * $usdCop : (float) $monto);

        $query = InternalProject::withSum('payments as payments_sum', 'monto')
            ->withSum('payments as payments_sum_cop', 'monto_recibido_cop')
            ->withSum('developerPayments as developer_payments_sum', 'monto')
            ->withSum('gestionPayments as gestion_payments_sum', 'monto')
            ->withSum('expenses as expenses_sum', 'monto')
            ->withCount('payments', 'developerPayments', 'gestionPayments', 'expenses')
            ->with('vendedor:id,nombre');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('cliente_nombre', 'like', "%{$search}%")
                    ->orWhere('desarrollador_nombre', 'like', "%{$search}%");
            });
        }

        if ($request->filled('desarrollador')) {
            $query->where('desarrollador_nombre', $request->desarrollador);
        }

        if ($request->filled('fuente')) {
            $query->where('fuente', $request->fuente);
        }

        if ($request->filled('vendedor')) {
            if ($request->vendedor === 'sin') {
                $query->whereNull('vendedor_id');
            } else {
                $query->where('vendedor_id', $request->vendedor);
            }
        }

        match ($request->get('orden', 'reciente')) {
            'mayor_precio' => $query->orderBy('precio', 'desc'),
            'mayor_saldo_cliente' => $query->orderByRaw('(precio - COALESCE((SELECT SUM(monto) FROM project_payments WHERE project_payments.internal_project_id = internal_projects.id),0)) DESC'),
            'mayor_saldo_dev' => $query->orderByRaw('(COALESCE(desarrollador_pago,0) - COALESCE((SELECT SUM(monto) FROM developer_payments WHERE developer_payments.internal_project_id = internal_projects.id),0)) DESC'),
            'fecha_entrega' => $query->orderByRaw('fecha_entrega IS NULL, fecha_entrega ASC'),
            'nombre' => $query->orderBy('nombre', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = (int) $request->get('per_page', 30);
        if (! in_array($perPage, [15, 30, 50, 100])) {
            $perPage = 30;
        }
        $projects = $query->paginate($perPage)->withQueryString();

        $desarrolladores = InternalProject::whereNotNull('desarrollador_nombre')
            ->where('desarrollador_nombre', '!=', '')
            ->distinct()
            ->orderBy('desarrollador_nombre')
            ->pluck('desarrollador_nombre');

        $vendedores = Vendedor::orderBy('nombre')->get(['id', 'nombre']);

        // === Totales de empresa (lifetime, todo el histórico) ===
        $allProjects = InternalProject::with(['payments', 'developerPayments', 'expenses'])->get();

        $totalIngresos = 0;
        $totalPagadoDevs = 0;
        $totalGastos = 0;
        $totalContratado = 0;
        $porCobrar = 0;
        $porPagarDev = 0;
        $proyectoTop = null;
        $clienteIngresos = [];

        foreach ($allProjects as $p) {
            $ingresos = $p->payments->sum(function ($pay) use ($p, $toCop) {
                if ($pay->monto_recibido_cop) {
                    return (float) $pay->monto_recibido_cop;
                }

                return $toCop($pay->monto, $p->moneda);
            });
            $pagadoDev = $p->developerPayments->sum(fn ($d) => $toCop($d->monto, $d->moneda ?? 'COP'));
            $gastos = $p->expenses->sum(fn ($e) => $toCop($e->monto, $e->moneda ?? 'COP'));
            $contratado = $toCop($p->precio, $p->moneda);
            $utilidadProj = $ingresos - $pagadoDev - $gastos;

            $totalIngresos += $ingresos;
            $totalPagadoDevs += $pagadoDev;
            $totalGastos += $gastos;
            $totalContratado += $contratado;

            if (! in_array($p->estado, ['cancelado'])) {
                $saldoCli = $contratado - $ingresos;
                if ($saldoCli > 0) {
                    $porCobrar += $saldoCli;
                }

                $saldoDev = $toCop($p->desarrollador_pago ?? 0, $p->desarrollador_moneda ?? 'COP') - $pagadoDev;
                if ($saldoDev > 0) {
                    $porPagarDev += $saldoDev;
                }
            }

            if ($proyectoTop === null || $utilidadProj > $proyectoTop['utilidad']) {
                $proyectoTop = [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'utilidad' => $utilidadProj,
                ];
            }

            $clienteIngresos[$p->cliente_nombre] = ($clienteIngresos[$p->cliente_nombre] ?? 0) + $ingresos;
        }

        arsort($clienteIngresos);
        $clienteTop = $clienteIngresos ? [
            'nombre' => array_key_first($clienteIngresos),
            'ingresos' => reset($clienteIngresos),
        ] : null;

        $devsActivos = InternalProject::whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])
            ->whereNotNull('desarrollador_nombre')
            ->where('desarrollador_nombre', '!=', '')
            ->distinct()
            ->count('desarrollador_nombre');

        $companyTotals = [
            'total_ingresos' => $totalIngresos,
            'total_pagado_devs' => $totalPagadoDevs,
            'total_gastos' => $totalGastos,
            'utilidad_total' => $totalIngresos - $totalPagadoDevs - $totalGastos,
            'margen' => $totalIngresos > 0 ? round((($totalIngresos - $totalPagadoDevs - $totalGastos) / $totalIngresos) * 100, 1) : 0,
            'total_contratado' => $totalContratado,
            'por_cobrar' => $porCobrar,
            'por_pagar_dev' => $porPagarDev,
            'proyectos_total' => $allProjects->count(),
            'proyectos_activos' => $allProjects->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count(),
            'proyectos_completados' => $allProjects->where('estado', 'completado')->count(),
            'proyectos_pausados' => $allProjects->where('estado', 'pausado')->count(),
            'proyectos_cancelados' => $allProjects->where('estado', 'cancelado')->count(),
            'devs_activos' => $devsActivos,
            'proyecto_top' => $proyectoTop,
            'cliente_top' => $clienteTop,
        ];

        $filters = [
            'estado' => $request->get('estado', ''),
            'buscar' => $request->get('buscar', ''),
            'desarrollador' => $request->get('desarrollador', ''),
            'fuente' => $request->get('fuente', ''),
            'vendedor' => $request->get('vendedor', ''),
            'orden' => $request->get('orden', 'reciente'),
            'per_page' => $perPage,
        ];

        // === Totales de la página actual ===
        // Para columnas del cliente (precio, cobrado, saldo cli.) separamos por moneda nativa.
        // Para dev/gestión/gastos y la utilidad total se normaliza a COP.
        $pageTotals = [
            'precio_cop_native' => 0,
            'precio_usd_native' => 0,
            'cobrado_cop_native' => 0,
            'cobrado_usd_native' => 0,
            'saldo_cliente_cop_native' => 0,
            'saldo_cliente_usd_native' => 0,
            'pago_dev_cop' => 0,
            'abonado_dev_cop' => 0,
            'saldo_dev_cop' => 0,
            'comision_cop' => 0,
            'abonado_gestion_cop' => 0,
            'saldo_gestion_cop' => 0,
            'gastos_cop' => 0,
            'utilidad_cop' => 0,
        ];
        foreach ($projects as $p) {
            $moneda = $p->moneda;
            $devMoneda = $p->desarrollador_moneda ?? 'COP';
            $cobrado = (float) ($p->payments_sum ?? 0);
            $saldoCli = max((float) $p->precio - $cobrado, 0);
            $pagoDev = (float) ($p->desarrollador_pago ?? 0);
            $abonadoDev = (float) ($p->developer_payments_sum ?? 0);
            $saldoDev = max($pagoDev - $abonadoDev, 0);
            $gastos = (float) ($p->expenses_sum ?? 0);

            // Ingreso real en COP (usa monto_recibido_cop si está, sino convierte)
            $netoCopReal = (float) ($p->payments_sum_cop ?? 0);
            $ingresoCopBase = $moneda === 'USD'
                ? ($netoCopReal > 0 ? $netoCopReal : $cobrado * $usdCop)
                : $cobrado;
            $pagoDevCopBase = $devMoneda === 'USD' ? $pagoDev * $usdCop : $pagoDev;

            // Comisión de gestión — siempre en COP, sobre el precio total del proyecto
            $comision = 0;
            if ($p->comision_tipo && $p->comision_valor) {
                if ($p->comision_tipo === 'monto') {
                    $comision = (float) $p->comision_valor;
                } else {
                    $precioCop = $moneda === 'USD' ? (float) $p->precio * $usdCop : (float) $p->precio;
                    $comision = $precioCop * ((float) $p->comision_valor / 100);
                }
            }
            $abonadoGestion = (float) ($p->gestion_payments_sum ?? 0);
            $saldoGestion = max($comision - $abonadoGestion, 0);

            // Columnas cliente: acumular por moneda nativa del proyecto
            if ($moneda === 'USD') {
                $pageTotals['precio_usd_native'] += (float) $p->precio;
                $pageTotals['cobrado_usd_native'] += $cobrado;
                $pageTotals['saldo_cliente_usd_native'] += $saldoCli;
            } else {
                $pageTotals['precio_cop_native'] += (float) $p->precio;
                $pageTotals['cobrado_cop_native'] += $cobrado;
                $pageTotals['saldo_cliente_cop_native'] += $saldoCli;
            }

            // Dev en COP, gestión ya está en COP (no convertir), gastos en COP
            $pageTotals['pago_dev_cop'] += $toCop($pagoDev, $devMoneda);
            $pageTotals['abonado_dev_cop'] += $toCop($abonadoDev, $devMoneda);
            $pageTotals['saldo_dev_cop'] += $toCop($saldoDev, $devMoneda);
            $pageTotals['comision_cop'] += $comision;
            $pageTotals['abonado_gestion_cop'] += $abonadoGestion;
            $pageTotals['saldo_gestion_cop'] += $saldoGestion;
            $pageTotals['gastos_cop'] += $gastos;

            // Utilidad de caja: ingreso_real − abonado_dev − abonado_gestion − gastos (todo COP)
            $pageTotals['utilidad_cop'] += $ingresoCopBase
                - $toCop($abonadoDev, $devMoneda)
                - $abonadoGestion
                - $gastos;
        }

        return view('admin.internal-projects.detalle', compact('projects', 'companyTotals', 'pageTotals', 'filters', 'desarrolladores', 'vendedores', 'usdCop'));
    }

    /**
     * Vista rápida agrupada por desarrollador: valor del proyecto, pago al dev y fecha de entrega.
     * Completados quedan colapsados por dev; proyectos sin dev asignado se muestran arriba destacados.
     */
    public function porDesarrollador(Request $request)
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));

        $query = InternalProject::query()
            ->withSum('payments', 'monto')
            ->withSum('developerPayments as developer_payments_sum_monto', 'monto');

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('cliente_nombre', 'like', "%{$search}%")
                    ->orWhere('desarrollador_nombre', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderByRaw('fecha_entrega IS NULL, fecha_entrega ASC')
            ->orderBy('created_at', 'desc')
            ->get();

        $activos = ['en_progreso', 'cotizado', 'pausado'];
        $sinDevKey = '__sin_dev__';

        $grupos = $projects->groupBy(fn ($p) => $p->desarrollador_nombre ?: $sinDevKey)
            ->map(function ($items, $key) use ($activos, $usdCop, $sinDevKey) {
                $activosItems = $items->filter(fn ($p) => in_array($p->estado, $activos))->values();
                $completadosItems = $items->filter(fn ($p) => in_array($p->estado, ['completado', 'cancelado']))->values();

                $porPagarDevCop = 0;
                $porCobrarCop = 0;
                $proximaEntrega = null;
                $hoy = now()->endOfDay();

                // "Por pagar al dev" y "próxima entrega": solo proyectos activos.
                foreach ($activosItems as $p) {
                    $saldoDev = max((float) ($p->desarrollador_pago ?? 0) - (float) ($p->developer_payments_sum_monto ?? 0), 0);
                    if ($saldoDev > 0) {
                        $porPagarDevCop += ($p->desarrollador_moneda ?? 'COP') === 'USD' ? $saldoDev * $usdCop : $saldoDev;
                    }

                    if ($p->fecha_entrega && (! $proximaEntrega || $p->fecha_entrega < $proximaEntrega)) {
                        $proximaEntrega = $p->fecha_entrega;
                    }
                }

                // "Por cobrar" (saldo pendiente): sobre TODOS los proyectos del dev (menos cancelados).
                //  - Recurrente: si ya llegó su fecha de facturación, el valor del ciclo entra como pendiente.
                //  - No recurrente (activo o completado): precio − cobrado. Un completado sin ningún
                //    cobro suma su precio completo.
                foreach ($items as $p) {
                    if ($p->estado === 'cancelado') {
                        continue;
                    }

                    $saldoCliente = 0.0;
                    if ($p->es_recurrente) {
                        if ($p->fecha_facturacion && $p->fecha_facturacion->lte($hoy)) {
                            $saldoCliente = (float) $p->precio;
                        }
                    } else {
                        $saldoCliente = max((float) $p->precio - (float) ($p->payments_sum_monto ?? 0), 0);
                    }

                    if ($saldoCliente > 0) {
                        $porCobrarCop += $p->moneda === 'USD' ? $saldoCliente * $usdCop : $saldoCliente;
                    }
                }

                return [
                    'nombre' => $key === $sinDevKey ? null : $key,
                    'activos' => $activosItems,
                    'completados' => $completadosItems,
                    'por_pagar_dev_cop' => $porPagarDevCop,
                    'por_cobrar_cop' => $porCobrarCop,
                    'proxima_entrega' => $proximaEntrega,
                    'is_sin_dev' => $key === $sinDevKey,
                ];
            })
            ->sortBy(function ($grupo) {
                if ($grupo['is_sin_dev']) {
                    return '000_'.uniqid();
                }

                return $grupo['activos']->isEmpty() ? '999_'.$grupo['nombre'] : '100_'.strtolower($grupo['nombre']);
            })
            ->values();

        $resumen = [
            'devs_activos' => $grupos->filter(fn ($g) => ! $g['is_sin_dev'] && $g['activos']->isNotEmpty())->count(),
            'proyectos_activos' => $projects->whereIn('estado', $activos)->count(),
            'sin_dev' => $projects->whereIn('estado', $activos)->whereNull('desarrollador_nombre')->count(),
            'por_pagar_dev_cop' => $grupos->sum('por_pagar_dev_cop'),
            'por_cobrar_cop' => $grupos->sum('por_cobrar_cop'),
        ];

        return view('admin.internal-projects.index', compact('grupos', 'resumen', 'usdCop'));
    }

    public function statsExport(Request $request): StreamedResponse
    {
        $data = $this->buildStatsData($request, includeAllMovimientos: true);
        $movimientos = $data['movimientos'];
        $start = $data['rango']['start'];
        $end = $data['rango']['end'];

        $filename = 'reporte_'.$start->format('Ymd').'_'.$end->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($movimientos) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Fecha', 'Tipo', 'Proyecto', 'Cliente', 'Concepto', 'Monto', 'Moneda', 'Monto COP']);
            foreach ($movimientos as $m) {
                fputcsv($out, [
                    $m['fecha']->format('Y-m-d'),
                    $m['tipo'],
                    $m['proyecto'],
                    $m['cliente'],
                    $m['concepto'],
                    $m['monto'],
                    $m['moneda'],
                    round($m['monto_cop']),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function buildStatsData(Request $request, bool $includeAllMovimientos = false): array
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $toCop = fn ($monto, $moneda) => ($moneda === 'USD' ? (float) $monto * $usdCop : (float) $monto);

        // === Resolver rango ===
        $preset = $request->get('preset', 'mes_actual');
        $now = now();
        [$start, $end] = match ($preset) {
            'hoy' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'mes_anterior' => [$now->copy()->subMonth()->startOfMonth(), $now->copy()->subMonth()->endOfMonth()],
            'este_anio' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            'personalizado' => [
                Carbon::parse($request->get('desde', $now->copy()->startOfMonth()->toDateString()))->startOfDay(),
                Carbon::parse($request->get('hasta', $now->copy()->endOfMonth()->toDateString()))->endOfDay(),
            ],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
        if ($end->lt($start)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        // === Filtro opcional por desarrollador ===
        $desarrolladorFilter = trim((string) $request->get('desarrollador', '')) ?: null;
        $applyDevScope = fn ($query) => $desarrolladorFilter
            ? $query->whereHas('project', fn ($q) => $q->where('desarrollador_nombre', $desarrolladorFilter))
            : $query;

        // === Movimientos del rango (unificados) ===
        $pagos = $applyDevScope(
            ProjectPayment::with('project:id,nombre,cliente_nombre,moneda,desarrollador_nombre')
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'desc')
        )->get()->map(function ($p) use ($toCop) {
            $moneda = optional($p->project)->moneda ?? 'COP';
            $cop = $p->monto_recibido_cop ? (float) $p->monto_recibido_cop : $toCop($p->monto, $moneda);

            return [
                'fecha' => $p->fecha,
                'tipo' => 'Ingreso',
                'proyecto_id' => optional($p->project)->id,
                'proyecto' => optional($p->project)->nombre ?? '—',
                'cliente' => optional($p->project)->cliente_nombre ?? '—',
                'metodo' => $p->metodo,
                'referencia' => $p->referencia,
                'nota' => $p->nota,
                'concepto' => trim(($p->metodo ?? '').($p->referencia ? ' · '.$p->referencia : '')) ?: ($p->nota ?? ''),
                'monto' => (float) $p->monto,
                'moneda' => $moneda,
                'monto_cop' => $cop,
            ];
        });

        $pagosDev = $applyDevScope(
            DeveloperPayment::with('project:id,nombre,cliente_nombre,desarrollador_nombre')
                ->whereBetween('fecha', [$start, $end])
                ->orderBy('fecha', 'desc')
        )->get()->map(function ($p) use ($toCop) {
            $moneda = $p->moneda ?? 'COP';

            return [
                'fecha' => $p->fecha,
                'tipo' => 'Pago dev',
                'proyecto_id' => optional($p->project)->id,
                'proyecto' => optional($p->project)->nombre ?? '—',
                'cliente' => optional($p->project)->desarrollador_nombre ?? '—',
                'desarrollador' => optional($p->project)->desarrollador_nombre ?? '—',
                'metodo' => $p->metodo,
                'referencia' => $p->referencia,
                'nota' => $p->nota,
                'concepto' => trim(($p->metodo ?? '').($p->referencia ? ' · '.$p->referencia : '')) ?: ($p->nota ?? ''),
                'monto' => (float) $p->monto,
                'moneda' => $moneda,
                'monto_cop' => $toCop($p->monto, $moneda),
            ];
        });

        $gastos = $applyDevScope(
            ProjectExpense::with('project:id,nombre,cliente_nombre,desarrollador_nombre')
                ->whereBetween('fecha', [$start, $end])
        )->get()->map(function ($e) use ($toCop) {
            $moneda = $e->moneda ?? 'COP';

            return [
                'fecha' => $e->fecha,
                'tipo' => 'Gasto',
                'proyecto' => optional($e->project)->nombre ?? '—',
                'cliente' => optional($e->project)->cliente_nombre ?? '—',
                'concepto' => $e->concepto ?? $e->categoria ?? '',
                'monto' => (float) $e->monto,
                'moneda' => $moneda,
                'monto_cop' => $toCop($e->monto, $moneda),
            ];
        });

        $movimientos = $pagos->concat($pagosDev)->concat($gastos)
            ->sortByDesc(fn ($m) => $m['fecha']->timestamp)
            ->values();

        // === KPIs ===
        $ingresos = $pagos->sum('monto_cop');
        $egresosDev = $pagosDev->sum('monto_cop');
        $egresosOtros = $gastos->sum('monto_cop');
        $utilidad = $ingresos - $egresosDev - $egresosOtros;

        $kpis = [
            'ingresos' => $ingresos,
            'pagos_dev' => $egresosDev,
            'gastos' => $egresosOtros,
            'utilidad' => $utilidad,
            'margen' => $ingresos > 0 ? round(($utilidad / $ingresos) * 100, 1) : 0,
            'cuenta_ingresos' => $pagos->count(),
            'cuenta_pagos_dev' => $pagosDev->count(),
            'cuenta_gastos' => $gastos->count(),
        ];

        // === Serie temporal (agrupación según span) ===
        $dias = $start->diffInDays($end) + 1;
        $granularity = $dias <= 31 ? 'day' : ($dias <= 186 ? 'week' : 'month');

        $buckets = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            if ($granularity === 'day') {
                $key = $cursor->format('Y-m-d');
                $label = $cursor->locale('es')->isoFormat('DD MMM');
                $bEnd = $cursor->copy()->endOfDay();
                $cursor = $cursor->copy()->addDay();
            } elseif ($granularity === 'week') {
                $key = $cursor->format('o-W');
                $label = $cursor->locale('es')->isoFormat('DD MMM');
                $bEnd = $cursor->copy()->endOfWeek();
                $cursor = $cursor->copy()->addWeek()->startOfWeek();
            } else {
                $key = $cursor->format('Y-m');
                $label = $cursor->locale('es')->isoFormat('MMM YY');
                $bEnd = $cursor->copy()->endOfMonth();
                $cursor = $cursor->copy()->addMonth()->startOfMonth();
            }
            $buckets[$key] = ['label' => $label, 'end' => $bEnd, 'ing' => 0, 'egr' => 0];
        }

        $assign = function ($fecha, &$buckets) use ($granularity) {
            $key = match ($granularity) {
                'day' => $fecha->format('Y-m-d'),
                'week' => $fecha->format('o-W'),
                'month' => $fecha->format('Y-m'),
            };

            return isset($buckets[$key]) ? $key : null;
        };

        foreach ($pagos as $p) {
            if ($k = $assign($p['fecha'], $buckets)) {
                $buckets[$k]['ing'] += $p['monto_cop'];
            }
        }
        foreach ($pagosDev as $p) {
            if ($k = $assign($p['fecha'], $buckets)) {
                $buckets[$k]['egr'] += $p['monto_cop'];
            }
        }
        foreach ($gastos as $g) {
            if ($k = $assign($g['fecha'], $buckets)) {
                $buckets[$k]['egr'] += $g['monto_cop'];
            }
        }

        $serieLabels = array_values(array_map(fn ($b) => $b['label'], $buckets));
        $serieIngresos = array_values(array_map(fn ($b) => round($b['ing']), $buckets));
        $serieEgresos = array_values(array_map(fn ($b) => round($b['egr']), $buckets));

        // === Próximos a vencer (30 días + vencidos) ===
        $hoy = now()->startOfDay();
        $limite = $hoy->copy()->addDays(30);
        $proximosQuery = InternalProject::whereNotIn('estado', ['completado', 'cancelado'])
            ->where('es_recurrente', false)
            ->whereNotNull('fecha_entrega')
            ->where('fecha_entrega', '<=', $limite)
            ->withSum('payments as pagado_total', 'monto');
        if ($desarrolladorFilter) {
            $proximosQuery->where('desarrollador_nombre', $desarrolladorFilter);
        }
        $proximos = $proximosQuery
            ->orderBy('fecha_entrega', 'asc')
            ->limit(15)
            ->get()
            ->map(function ($p) use ($toCop, $hoy) {
                $saldo = max((float) $p->precio - (float) ($p->pagado_total ?? 0), 0);
                $diasRest = $hoy->diffInDays($p->fecha_entrega, false);

                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'cliente' => $p->cliente_nombre,
                    'estado_label' => $p->estado_label,
                    'estado_color' => $p->estado_color,
                    'fecha_entrega' => $p->fecha_entrega,
                    'dias_restantes' => (int) $diasRest,
                    'vencido' => $diasRest < 0,
                    'saldo_cop' => $toCop($saldo, $p->moneda),
                    'moneda' => $p->moneda,
                ];
            });

        $movimientosVista = $includeAllMovimientos ? $movimientos : $movimientos->take(50);

        // === Lista de desarrolladores para el selector ===
        $desarrolladores = InternalProject::whereNotNull('desarrollador_nombre')
            ->where('desarrollador_nombre', '!=', '')
            ->distinct()
            ->orderBy('desarrollador_nombre')
            ->pluck('desarrollador_nombre');

        // === Resumen vitalicio del desarrollador seleccionado ===
        $devSummary = null;
        if ($desarrolladorFilter) {
            $devProjects = InternalProject::where('desarrollador_nombre', $desarrolladorFilter)
                ->withSum('developerPayments as dev_pagado', 'monto')
                ->orderBy('fecha_inicio', 'asc')
                ->get();

            $asignado = $devProjects->sum(fn ($p) => $toCop($p->desarrollador_pago ?? 0, $p->desarrollador_moneda ?? 'COP'));
            $pagado = $devProjects->sum(fn ($p) => $toCop($p->dev_pagado ?? 0, $p->desarrollador_moneda ?? 'COP'));
            $pendiente = max($asignado - $pagado, 0);

            $primerProyecto = $devProjects->pluck('fecha_inicio')->filter()->min()
                ?? $devProjects->pluck('created_at')->filter()->min();
            $ultimoPago = DeveloperPayment::whereHas('project', fn ($q) => $q->where('desarrollador_nombre', $desarrolladorFilter))
                ->max('fecha');

            $devSummary = [
                'nombre' => $desarrolladorFilter,
                'proyectos_total' => $devProjects->count(),
                'proyectos_activos' => $devProjects->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count(),
                'asignado_cop' => $asignado,
                'pagado_cop' => $pagado,
                'pendiente_cop' => $pendiente,
                'porcentaje_pagado' => $asignado > 0 ? round(($pagado / $asignado) * 100, 1) : 0,
                'desde' => $primerProyecto ? Carbon::parse($primerProyecto) : null,
                'ultimo_pago' => $ultimoPago ? Carbon::parse($ultimoPago) : null,
                'proyectos' => $devProjects->map(function ($p) use ($toCop) {
                    $devMoneda = $p->desarrollador_moneda ?? 'COP';
                    $asignado = (float) ($p->desarrollador_pago ?? 0);
                    $pagado = (float) ($p->dev_pagado ?? 0);

                    return [
                        'id' => $p->id,
                        'nombre' => $p->nombre,
                        'cliente' => $p->cliente_nombre,
                        'estado_label' => $p->estado_label,
                        'estado_color' => $p->estado_color,
                        'asignado' => $asignado,
                        'pagado' => $pagado,
                        'pendiente' => max($asignado - $pagado, 0),
                        'moneda' => $devMoneda,
                        'asignado_cop' => $toCop($asignado, $devMoneda),
                        'pagado_cop' => $toCop($pagado, $devMoneda),
                        'pendiente_cop' => $toCop(max($asignado - $pagado, 0), $devMoneda),
                        'fecha_inicio' => $p->fecha_inicio,
                    ];
                })->values(),
            ];
        }

        return [
            'usdCop' => $usdCop,
            'rango' => [
                'preset' => $preset,
                'start' => $start,
                'end' => $end,
                'desde' => $start->toDateString(),
                'hasta' => $end->toDateString(),
                'dias' => $dias,
                'granularity' => $granularity,
            ],
            'kpis' => $kpis,
            'serieLabels' => $serieLabels,
            'serieIngresos' => $serieIngresos,
            'serieEgresos' => $serieEgresos,
            'movimientos' => $movimientosVista,
            'movimientosTotal' => $movimientos->count(),
            'proximos' => $proximos,
            'pagosClientes' => $pagos,
            'pagosDevs' => $pagosDev,
            'desarrolladores' => $desarrolladores,
            'desarrolladorFilter' => $desarrolladorFilter,
            'devSummary' => $devSummary,
        ];
    }

    public function create()
    {
        return view('admin.internal-projects.form', [
            'project' => new InternalProject,
            'isEdit' => false,
            'clients' => Client::orderBy('nombre')->get(),
            'developers' => Developer::orderBy('nombre')->get(),
            'vendedores' => $this->vendedoresParaSelect(),
        ]);
    }

    /**
     * Devuelve los vendedores disponibles para el select del form, incluyendo
     * automáticamente a los usuarios con rol "comercial" (los sincroniza como
     * Vendedor si aún no existen, por email o nombre).
     */
    private function vendedoresParaSelect()
    {
        \App\Models\User::role('comercial')->get()->each(function (\App\Models\User $u) {
            $criteria = $u->email
                ? ['email' => $u->email]
                : ['nombre' => $u->name];

            Vendedor::firstOrCreate($criteria, [
                'nombre' => $u->name,
                'email' => $u->email,
            ]);
        });

        return Vendedor::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'cliente_nombre' => 'required_without:client_id|nullable|string|max:255',
            'cliente_contacto' => 'nullable|string|max:255',
            'cliente_email' => 'nullable|email|max:255',
            'fuente' => 'required|in:directo,workana',
            'fuente_url' => 'nullable|url|max:500',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD,EUR',
            'estado' => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_entrega' => 'nullable|date|required_without_all:es_recurrente,es_bolsa_horas',
            'fecha_facturacion' => 'nullable|date',
            'notas_facturacion' => 'nullable|string|max:500',
            'es_recurrente' => 'nullable|boolean',
            'es_bolsa_horas' => 'nullable|boolean',
            'horas_totales' => 'nullable|numeric|min:0|required_if:es_bolsa_horas,1',
            'valor_hora' => 'nullable|numeric|min:0',
            'puntos' => 'nullable|array',
            'puntos.*.texto' => 'nullable|string|max:500',
            'puntos.*.horas' => 'nullable|numeric|min:0',
            'puntos.*.estado' => 'nullable|in:pendiente,en_progreso,hecho',
            'descripcion' => 'nullable|string',
            'notas' => 'nullable|string',
            'developer_id' => 'nullable|exists:developers,id',
            'desarrollador_nombre' => 'nullable|string|max:255',
            'desarrollador_email' => 'nullable|email|max:255',
            'desarrollador_pago' => 'nullable|numeric|min:0',
            'desarrollador_moneda' => 'required|in:COP,USD,EUR',
            'vendedor_id' => 'nullable|exists:vendedores,id',
            'comision_tipo' => 'nullable|in:porcentaje,monto',
            'comision_valor' => 'nullable|numeric|min:0',
        ]);

        if (empty($validated['vendedor_id'])) {
            $validated['comision_tipo'] = null;
            $validated['comision_valor'] = null;
        }

        if (! empty($validated['client_id'])) {
            $client = Client::find($validated['client_id']);
            if ($client) {
                $validated['cliente_nombre'] = $client->nombre;
                if (empty($validated['cliente_contacto']) && $client->telefono) {
                    $validated['cliente_contacto'] = $client->telefono;
                }
            }
        }

        if (! empty($validated['developer_id'])) {
            $dev = Developer::find($validated['developer_id']);
            if ($dev) {
                $validated['desarrollador_nombre'] = $dev->nombre;
                if (empty($validated['desarrollador_email']) && $dev->email) {
                    $validated['desarrollador_email'] = $dev->email;
                }
            }
        }

        $validated['es_recurrente'] = $request->boolean('es_recurrente');
        if ($validated['es_recurrente']) {
            $validated['fecha_entrega'] = null;
        }

        $validated = $this->aplicarDatosBolsa($validated, $request);

        $project = InternalProject::create($validated);

        $this->guardarDocumentosAdjuntos($request, $project);

        return redirect()->route('admin.internal-projects.show', $project)
            ->with('success', 'Proyecto creado exitosamente.');
    }

    /**
     * Guarda los archivos de propuesta/contrato adjuntados desde el form
     * de crear/editar como ProjectFile del proyecto.
     */
    private function guardarDocumentosAdjuntos(Request $request, InternalProject $project): void
    {
        if (! $request->hasFile('documentos')) {
            return;
        }

        $request->validate([
            'documentos.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,webp,zip|max:20480',
        ]);

        foreach ($request->file('documentos') as $file) {
            $path = $file->store('internal-projects/'.$project->id, 'public');
            $project->files()->create([
                'nombre' => 'Propuesta/Contrato — '.$file->getClientOriginalName(),
                'archivo' => $path,
                'tipo' => $file->getMimeType(),
                'tamano' => $file->getSize(),
            ]);
        }
    }

    /**
     * Normaliza los campos de "bolsa de horas prepagada" antes de guardar el proyecto.
     * Si no es bolsa, limpia los campos; si lo es, arma el arreglo de puntos acordados.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function aplicarDatosBolsa(array $validated, Request $request): array
    {
        unset($validated['puntos']);

        $esBolsa = $request->boolean('es_bolsa_horas');
        $validated['es_bolsa_horas'] = $esBolsa;

        if (! $esBolsa) {
            $validated['horas_totales'] = null;
            $validated['valor_hora'] = null;
            $validated['puntos_acuerdo'] = null;

            return $validated;
        }

        $puntos = collect($request->input('puntos', []))
            ->map(fn ($p) => [
                'texto' => trim((string) ($p['texto'] ?? '')),
                'horas' => ($p['horas'] ?? '') === '' ? null : (float) $p['horas'],
                'estado' => in_array($p['estado'] ?? '', ['pendiente', 'en_progreso', 'hecho'], true) ? $p['estado'] : 'pendiente',
            ])
            ->filter(fn ($p) => $p['texto'] !== '')
            ->values()
            ->all();

        $validated['puntos_acuerdo'] = $puntos ?: null;

        // El precio de una bolsa = horas × valor por hora (si se definió el valor por hora).
        if (! empty($validated['horas_totales']) && ! empty($validated['valor_hora'])) {
            $validated['precio'] = round((float) $validated['horas_totales'] * (float) $validated['valor_hora'], 2);
        }

        return $validated;
    }

    public function storeMovimiento(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tema' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad' => 'required|in:horas,minutos',
        ]);

        $internal_project->bolsaMovimientos()->create([
            'fecha' => $validated['fecha'],
            'tema' => $validated['tema'],
            'descripcion' => $validated['descripcion'],
            'horas' => $this->cantidadAHoras($validated['cantidad'], $validated['unidad']),
        ]);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Horas registradas en la bolsa.');
    }

    public function updateMovimiento(Request $request, InternalProject $internal_project, BolsaMovimiento $movimiento)
    {
        abort_unless($movimiento->internal_project_id === $internal_project->id, 404);

        $validated = $request->validate([
            'fecha' => 'required|date',
            'tema' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'cantidad' => 'required|numeric|min:0.01',
            'unidad' => 'required|in:horas,minutos',
        ]);

        $movimiento->update([
            'fecha' => $validated['fecha'],
            'tema' => $validated['tema'],
            'descripcion' => $validated['descripcion'],
            'horas' => $this->cantidadAHoras($validated['cantidad'], $validated['unidad']),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'movimiento' => [
                    'id' => $movimiento->id,
                    'fecha' => $movimiento->fecha->format('Y-m-d'),
                    'fecha_fmt' => $movimiento->fecha->format('d/m/Y'),
                    'tema' => $movimiento->tema,
                    'descripcion' => $movimiento->descripcion,
                    'horas' => (float) $movimiento->horas,
                ],
                'totales' => $this->totalesBolsa($internal_project),
            ]);
        }

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Registro de horas actualizado.');
    }

    public function destroyMovimiento(Request $request, InternalProject $internal_project, BolsaMovimiento $movimiento)
    {
        abort_unless($movimiento->internal_project_id === $internal_project->id, 404);

        $movimiento->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'totales' => $this->totalesBolsa($internal_project),
            ]);
        }

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Registro de horas eliminado.');
    }

    /**
     * Elimina varios movimientos de la bolsa a la vez (borrado masivo).
     */
    public function bulkDestroyMovimientos(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'ids' => 'nullable|array',
            'ids.*' => 'integer',
            'todos' => 'nullable|boolean',
        ]);

        $query = $internal_project->bolsaMovimientos();

        if ($request->boolean('todos')) {
            $eliminados = $query->count();
            $query->delete();
        } else {
            $ids = $validated['ids'] ?? [];
            $eliminados = empty($ids) ? 0 : $query->whereIn('id', $ids)->delete();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'eliminados' => $eliminados,
                'totales' => $this->totalesBolsa($internal_project),
            ]);
        }

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', "Se eliminaron {$eliminados} registro(s) de horas.");
    }

    /**
     * Totales de la bolsa recalculados desde la BD (para respuestas AJAX).
     *
     * @return array{cons: float, tot: float, rest: float, pct: int}
     */
    private function totalesBolsa(InternalProject $internal_project): array
    {
        $cons = round((float) $internal_project->bolsaMovimientos()->sum('horas'), 2);
        $tot = (float) $internal_project->horas_totales;
        $rest = round($tot - $cons, 2);
        $pct = $tot > 0 ? (int) min(round($cons / $tot * 100), 100) : 0;

        return ['cons' => $cons, 'tot' => $tot, 'rest' => $rest, 'pct' => $pct];
    }

    /**
     * Convierte una cantidad en horas o minutos a horas decimales (unidad canónica de la bolsa).
     */
    private function cantidadAHoras(float $cantidad, string $unidad): float
    {
        return $unidad === 'minutos' ? round($cantidad / 60, 2) : round($cantidad, 2);
    }

    /** Descarga la plantilla .xlsx para registrar horas de la bolsa. */
    public function plantillaMovimientos(InternalProject $internal_project): BinaryFileResponse
    {
        return Excel::download(new PlantillaBolsaHorasExport, 'plantilla-horas-bolsa.xlsx');
    }

    /**
     * Importa registros de horas desde un archivo Excel/CSV.
     * Columnas esperadas: Fecha, Tema, Descripcion, Cantidad, Unidad (horas|minutos).
     */
    public function importMovimientos(Request $request, InternalProject $internal_project)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ], [
            'archivo.required' => 'Selecciona el archivo Excel a importar.',
            'archivo.mimes' => 'El archivo debe ser Excel (.xlsx, .xls) o CSV.',
            'archivo.max' => 'El archivo no puede superar los 10 MB.',
        ]);

        $import = new BolsaMovimientosImport;
        Excel::import($import, $request->file('archivo'));

        $filas = $import->filas;

        if ($filas->isEmpty()) {
            return back()->with('error', 'El archivo está vacío.');
        }

        $idx = $this->mapearColumnasMovimiento($filas->first() ?? collect());

        // Respaldo posicional si el archivo no trae encabezados reconocibles.
        if (! isset($idx['fecha']) && ! isset($idx['descripcion'])) {
            $idx = ['fecha' => 0, 'tema' => 1, 'descripcion' => 2, 'cantidad' => 3, 'unidad' => 4];
        }

        $valor = fn ($fila, $campo) => isset($idx[$campo]) ? trim((string) ($fila[$idx[$campo]] ?? '')) : '';

        $ok = 0;
        $errores = [];
        $nuevos = [];
        $now = now();

        // Se omite la primera fila (encabezados de la plantilla).
        foreach ($filas->slice(1) as $i => $fila) {
            $temaTxt = $valor($fila, 'tema');
            $descTxt = $valor($fila, 'descripcion');
            $cantTxt = $valor($fila, 'cantidad');
            $fechaTxt = $valor($fila, 'fecha');

            // Ignora filas completamente vacías sin marcarlas como error.
            if ($fechaTxt === '' && $temaTxt === '' && $descTxt === '' && $cantTxt === '') {
                continue;
            }

            $filaNum = $i + 1;
            $fechaCruda = isset($idx['fecha']) ? ($fila[$idx['fecha']] ?? null) : null;
            $fecha = $this->parsearFechaExcel($fechaCruda);
            $cantidad = (float) str_replace(',', '.', $cantTxt);
            $unidad = $this->normalizarUnidad($valor($fila, 'unidad'));

            $problemas = [];
            if (! $fecha) {
                $problemas[] = 'fecha inválida';
            }
            if ($temaTxt === '') {
                $problemas[] = 'falta el tema';
            }
            if ($descTxt === '') {
                $problemas[] = 'falta la descripción';
            }
            if ($cantidad <= 0) {
                $problemas[] = 'cantidad inválida';
            }
            if (! $unidad) {
                $problemas[] = 'unidad inválida (usa "horas" o "minutos")';
            }

            if (! empty($problemas)) {
                $errores[] = ['fila' => $filaNum, 'motivo' => implode(', ', $problemas)];

                continue;
            }

            $nuevos[] = [
                'internal_project_id' => $internal_project->id,
                'fecha' => $fecha,
                'tema' => $temaTxt,
                'descripcion' => $descTxt,
                'horas' => $this->cantidadAHoras($cantidad, $unidad),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $ok++;
        }

        if (! empty($nuevos)) {
            BolsaMovimiento::insert($nuevos);
        }

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('import_bolsa', ['ok' => $ok, 'errores' => $errores]);
    }

    /**
     * Detecta el índice de cada columna por el NOMBRE del encabezado, no por su posición.
     *
     * @return array<string, int>
     */
    private function mapearColumnasMovimiento(Collection $encabezado): array
    {
        $normalizar = fn ($s) => strtolower(strtr(trim((string) $s), [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u', 'Ñ' => 'n',
        ]));

        $idx = [];
        foreach ($encabezado as $i => $titulo) {
            $h = $normalizar($titulo);
            if ($h === '') {
                continue;
            }

            $asignar = function (string $campo) use (&$idx, $i): void {
                if (! isset($idx[$campo])) {
                    $idx[$campo] = $i;
                }
            };

            if (str_contains($h, 'fecha')) {
                $asignar('fecha');
            } elseif (str_contains($h, 'unidad') || str_contains($h, 'medida')) {
                $asignar('unidad');
            } elseif (str_contains($h, 'cantidad') || str_contains($h, 'tiempo') || str_contains($h, 'duracion') || str_contains($h, 'hora') || str_contains($h, 'minuto')) {
                $asignar('cantidad');
            } elseif (str_contains($h, 'tema') || str_contains($h, 'titulo') || str_contains($h, 'asunto')) {
                $asignar('tema');
            } elseif (str_contains($h, 'descrip') || str_contains($h, 'detalle') || str_contains($h, 'activid') || str_contains($h, 'nota') || str_contains($h, 'hizo')) {
                $asignar('descripcion');
            }
        }

        return $idx;
    }

    /** Normaliza el texto de unidad a "horas" o "minutos"; null si no es reconocible. */
    private function normalizarUnidad(string $valor): ?string
    {
        $v = strtolower(trim($valor));

        if ($v === '') {
            return 'horas';
        }
        if (str_starts_with($v, 'h')) {
            return 'horas';
        }
        if (str_starts_with($v, 'm')) {
            return 'minutos';
        }

        return null;
    }

    /** Convierte una celda de fecha (serial de Excel o texto) a formato Y-m-d; null si no es válida. */
    private function parsearFechaExcel($valor): ?string
    {
        if ($valor === null || trim((string) $valor) === '') {
            return null;
        }

        if (is_numeric($valor) && $valor > 59 && $valor < 60000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $valor)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        $texto = trim((string) $valor);
        foreach (['d/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/y', 'Y/m/d'] as $formato) {
            $fecha = \DateTime::createFromFormat($formato, $texto);
            if ($fecha !== false) {
                return $fecha->format('Y-m-d');
            }
        }

        try {
            return Carbon::parse($texto)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function show(InternalProject $internal_project)
    {
        $internal_project->load([
            'payments' => fn ($q) => $q->orderBy('fecha', 'desc'),
            'developerPayments' => fn ($q) => $q->orderBy('fecha', 'desc'),
            'gestionPayments' => fn ($q) => $q->orderBy('fecha', 'desc'),
            'expenses' => fn ($q) => $q->orderBy('fecha', 'desc'),
            'bolsaMovimientos' => fn ($q) => $q->orderBy('fecha', 'desc'),
            'files',
            'vendedor',
        ]);

        return view('admin.internal-projects.show', [
            'project' => $internal_project,
        ]);
    }

    public function edit(InternalProject $internal_project)
    {
        return view('admin.internal-projects.form', [
            'project' => $internal_project,
            'isEdit' => true,
            'clients' => Client::orderBy('nombre')->get(),
            'developers' => Developer::orderBy('nombre')->get(),
            'vendedores' => $this->vendedoresParaSelect(),
        ]);
    }

    public function update(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'cliente_nombre' => 'required_without:client_id|nullable|string|max:255',
            'cliente_contacto' => 'nullable|string|max:255',
            'cliente_email' => 'nullable|email|max:255',
            'fuente' => 'required|in:directo,workana',
            'fuente_url' => 'nullable|url|max:500',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD,EUR',
            'estado' => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_entrega' => 'nullable|date|required_without_all:es_recurrente,es_bolsa_horas',
            'fecha_facturacion' => 'nullable|date',
            'notas_facturacion' => 'nullable|string|max:500',
            'es_recurrente' => 'nullable|boolean',
            'es_bolsa_horas' => 'nullable|boolean',
            'horas_totales' => 'nullable|numeric|min:0|required_if:es_bolsa_horas,1',
            'valor_hora' => 'nullable|numeric|min:0',
            'puntos' => 'nullable|array',
            'puntos.*.texto' => 'nullable|string|max:500',
            'puntos.*.horas' => 'nullable|numeric|min:0',
            'puntos.*.estado' => 'nullable|in:pendiente,en_progreso,hecho',
            'descripcion' => 'nullable|string',
            'notas' => 'nullable|string',
            'developer_id' => 'nullable|exists:developers,id',
            'desarrollador_nombre' => 'nullable|string|max:255',
            'desarrollador_email' => 'nullable|email|max:255',
            'desarrollador_pago' => 'nullable|numeric|min:0',
            'desarrollador_moneda' => 'required|in:COP,USD,EUR',
            'vendedor_id' => 'nullable|exists:vendedores,id',
            'comision_tipo' => 'nullable|in:porcentaje,monto',
            'comision_valor' => 'nullable|numeric|min:0',
        ]);

        if (empty($validated['vendedor_id'])) {
            $validated['comision_tipo'] = null;
            $validated['comision_valor'] = null;
        }

        if (! empty($validated['client_id'])) {
            $client = Client::find($validated['client_id']);
            if ($client) {
                $validated['cliente_nombre'] = $client->nombre;
                if (empty($validated['cliente_contacto']) && $client->telefono) {
                    $validated['cliente_contacto'] = $client->telefono;
                }
            }
        }

        if (! empty($validated['developer_id'])) {
            $dev = Developer::find($validated['developer_id']);
            if ($dev) {
                $validated['desarrollador_nombre'] = $dev->nombre;
                if (empty($validated['desarrollador_email']) && $dev->email) {
                    $validated['desarrollador_email'] = $dev->email;
                }
            }
        }

        $validated['es_recurrente'] = $request->boolean('es_recurrente');
        if ($validated['es_recurrente']) {
            $validated['fecha_entrega'] = null;
        }

        $validated = $this->aplicarDatosBolsa($validated, $request);

        $internal_project->update($validated);

        $this->guardarDocumentosAdjuntos($request, $internal_project);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function destroy(InternalProject $internal_project)
    {
        // Delete associated files from storage
        foreach ($internal_project->files as $file) {
            Storage::disk('public')->delete($file->archivo);
        }

        $internal_project->delete();

        return redirect()->route('admin.internal-projects.index')
            ->with('success', 'Proyecto eliminado.');
    }

    // --- Payments ---

    public function storePayment(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'monto_recibido_cop' => 'nullable|numeric|min:0',
            'fecha' => 'required|date',
            'metodo' => 'nullable|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
        ]);

        $internal_project->payments()->create($validated);
        $autoCompletado = $this->autoCompletarSiPagado($internal_project);

        $mensaje = $autoCompletado
            ? 'Pago registrado. El proyecto quedó pagado en su totalidad y se marcó como completado.'
            : 'Pago registrado exitosamente.';

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', $mensaje);
    }

    /**
     * Marca el proyecto como completado si el total pagado alcanza o supera el precio.
     * No aplica a recurrentes (no "terminan") ni a proyectos ya cerrados (completado/cancelado).
     */
    private function autoCompletarSiPagado(InternalProject $project): bool
    {
        if ($project->es_recurrente) {
            return false;
        }
        if (in_array($project->estado, ['completado', 'cancelado'], true)) {
            return false;
        }
        if ((float) $project->precio <= 0) {
            return false;
        }

        $totalPagado = (float) $project->payments()->sum('monto');
        if ($totalPagado + 0.005 < (float) $project->precio) {
            return false;
        }

        $project->update(['estado' => 'completado']);

        return true;
    }

    public function destroyPayment(InternalProject $internal_project, ProjectPayment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago eliminado.');
    }

    /**
     * Vista imprimible del recibo/factura de un pago del cliente,
     * con membrete MY Tech Solutions. El admin usa Ctrl+P → "Guardar como PDF".
     */
    public function receiptPayment(InternalProject $internal_project, ProjectPayment $payment)
    {
        abort_unless($payment->internal_project_id === $internal_project->id, 404);

        $internal_project->load('client');
        $totalPagado = (float) $internal_project->payments()->sum('monto');
        $saldo = max((float) $internal_project->precio - $totalPagado, 0);

        return view('admin.internal-projects.receipt', [
            'project' => $internal_project,
            'payment' => $payment,
            'totalPagado' => $totalPagado,
            'saldo' => $saldo,
            'backUrl' => route('admin.internal-projects.show', $internal_project),
        ]);
    }

    /**
     * Cuenta de cobro (solicitud de pago) para el proyecto.
     * Para recurrentes: cobra el valor mensual del periodo indicado (?mes=YYYY-MM).
     * Para one-shot: cobra el saldo pendiente del cliente.
     */
    public function cuentaCobro(Request $request, InternalProject $internal_project)
    {
        $esRecurrente = (bool) $internal_project->es_recurrente;
        $internal_project->load('client');

        if ($esRecurrente) {
            try {
                $periodo = $request->filled('mes')
                    ? \Carbon\Carbon::createFromFormat('Y-m', $request->get('mes'))->startOfMonth()
                    : now()->startOfMonth();
            } catch (\Exception) {
                $periodo = now()->startOfMonth();
            }
        } else {
            $periodo = now()->startOfMonth();
        }

        // Monto a cobrar según lo que elija el admin al imprimir:
        //  - saldo (default one-shot): precio − cobrado
        //  - total: precio completo (default recurrente)
        //  - porcentaje: precio × %
        //  - valor: valor exacto escrito
        $totalPagado = (float) $internal_project->payments()->sum('monto');
        $saldo = max((float) $internal_project->precio - $totalPagado, 0);
        $tipo = $request->get('tipo', $esRecurrente ? 'total' : 'saldo');
        $valorParam = (float) $request->get('valor', 0);

        $monto = match ($tipo) {
            'total' => (float) $internal_project->precio,
            'porcentaje' => round((float) $internal_project->precio * (max($valorParam, 0) / 100), 2),
            'valor' => max($valorParam, 0),
            default => $saldo,
        };

        if ($monto <= 0) {
            return redirect()->route('admin.internal-projects.show', $internal_project)
                ->with('error', 'El monto a cobrar debe ser mayor a cero.');
        }

        return view('admin.internal-projects.cuenta-cobro', [
            'project' => $internal_project,
            'monto' => $monto,
            'periodo' => $periodo,
            'esRecurrente' => $esRecurrente,
            'tipoCobro' => $tipo,
            'pctCobro' => $tipo === 'porcentaje' ? $valorParam : null,
        ]);
    }

    /**
     * Publica una cuenta de cobro al portal del cliente (la persiste y la marca visible).
     * El monto se calcula igual que en cuentaCobro() según tipo/valor.
     */
    public function publicarCuentaCobro(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:saldo,total,porcentaje,valor',
            'valor' => 'nullable|numeric|min:0',
            'mes' => 'nullable|date_format:Y-m',
            'visible_cliente' => 'nullable|boolean',
        ]);

        $esRecurrente = (bool) $internal_project->es_recurrente;
        $totalPagado = (float) $internal_project->payments()->sum('monto');
        $saldo = max((float) $internal_project->precio - $totalPagado, 0);
        $valorParam = (float) ($validated['valor'] ?? 0);

        $monto = match ($validated['tipo']) {
            'total' => (float) $internal_project->precio,
            'porcentaje' => round((float) $internal_project->precio * (max($valorParam, 0) / 100), 2),
            'valor' => max($valorParam, 0),
            default => $saldo,
        };

        if ($monto <= 0) {
            return back()->with('error', 'El monto a cobrar debe ser mayor a cero.');
        }

        $periodo = null;
        if ($esRecurrente) {
            try {
                $periodo = ! empty($validated['mes'])
                    ? \Carbon\Carbon::createFromFormat('Y-m', $validated['mes'])->startOfMonth()
                    : now()->startOfMonth();
            } catch (\Exception) {
                $periodo = now()->startOfMonth();
            }
        }

        $numeroDoc = $esRecurrente && $periodo
            ? 'CC-'.$periodo->format('Ym').'-'.str_pad((string) $internal_project->id, 4, '0', STR_PAD_LEFT)
            : 'CC-'.now()->format('Ymd').'-'.str_pad((string) $internal_project->id, 4, '0', STR_PAD_LEFT);

        \App\Models\CuentaCobro::create([
            'internal_project_id' => $internal_project->id,
            'numero_doc' => $numeroDoc,
            'tipo' => $validated['tipo'],
            'valor_param' => in_array($validated['tipo'], ['porcentaje', 'valor'], true) ? $valorParam : null,
            'monto' => $monto,
            'moneda' => $internal_project->moneda,
            'periodo' => $periodo,
            'visible_cliente' => $request->boolean('visible_cliente'),
        ]);

        $msg = $request->boolean('visible_cliente')
            ? 'Cuenta de cobro publicada y visible para el cliente en su portal.'
            : 'Cuenta de cobro guardada (no visible para el cliente).';

        return back()->with('success', $msg);
    }

    /**
     * Ciclo de liquidación "20 a 20": del día 20 del mes anterior al día 19 (inclusive)
     * del mes de corte. El pago se efectúa entre el 20 y el 25 del mes de corte.
     * ?mes=YYYY-MM es el MES DE CORTE. Default: si hoy es ≥20, el corte de este mes
     * (ciclo recién cerrado); si no, el del mes pasado.
     *
     * @return array{0: Carbon, 1: Carbon, 2: Carbon} [cicloInicio, cicloFin, mesCorte]
     */
    private function cicloLiquidacion(Request $request): array
    {
        try {
            $mesCorte = $request->filled('mes')
                ? Carbon::createFromFormat('Y-m', $request->get('mes'))->startOfMonth()
                : (now()->day >= 20 ? now()->startOfMonth() : now()->subMonth()->startOfMonth());
        } catch (\Exception) {
            $mesCorte = now()->day >= 20 ? now()->startOfMonth() : now()->subMonth()->startOfMonth();
        }

        $cicloInicio = $mesCorte->copy()->subMonth()->day(20)->startOfDay();
        $cicloFin = $mesCorte->copy()->day(19)->endOfDay();

        return [$cicloInicio, $cicloFin, $mesCorte];
    }

    /**
     * Liquidación de comerciales: sueldo básico + comisiones del ciclo 20-a-20.
     * Comisión atribuida por fecha de cierre del proyecto (fecha_inicio,
     * fallback created_at), igual que el panel "Mis resultados".
     */
    public function liquidacionComerciales(Request $request)
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $eurUsd = (float) config('services.eur_usd', env('EUR_USD_RATE', 1.17));
        $toCop = function (float $monto, string $moneda) use ($usdCop, $eurUsd): float {
            return match ($moneda) {
                'USD' => $monto * $usdCop,
                'EUR' => $monto * $eurUsd * $usdCop,
                default => $monto,
            };
        };

        [$cicloInicio, $cicloFin, $mesCorte] = $this->cicloLiquidacion($request);

        $vendedores = $this->vendedoresParaSelect();

        $proyectosMes = InternalProject::whereNotNull('vendedor_id')
            ->with('gestionPayments')
            ->get()
            ->filter(function ($p) use ($cicloInicio, $cicloFin) {
                $cierre = $p->fecha_inicio ?? $p->created_at;

                return $cierre && $cierre->between($cicloInicio, $cicloFin);
            });

        $pagosLiquidacion = \App\Models\LiquidacionPago::whereDate('periodo', $cicloInicio->toDateString())
            ->orderBy('fecha_pago')
            ->get()
            ->groupBy('vendedor_id');

        $liquidaciones = $vendedores->map(function ($v) use ($proyectosMes, $toCop, $pagosLiquidacion) {
            $proyectos = $proyectosMes->where('vendedor_id', $v->id)->values();

            // Comisión escalonada: el tramo alcanzado aplica retroactivo a todo el ciclo.
            $pctEscalon = $v->porcentajePorCierres($proyectos->count());

            $detalle = $proyectos->map(function ($p) use ($toCop, $pctEscalon) {
                $comisionBase = (float) $p->comision_calculada;
                $comision = $comisionBase;
                $pctAplicado = null;

                // El escalón es un incentivo: solo aplica si MEJORA lo pactado en el proyecto.
                if ($pctEscalon !== null && $p->comision_tipo === 'porcentaje') {
                    $comisionEscalon = round((float) $p->precio * ($pctEscalon / 100), 2);
                    if ($comisionEscalon > $comisionBase) {
                        $comision = $comisionEscalon;
                        $pctAplicado = $pctEscalon;
                    }
                }

                $abonado = (float) $p->gestionPayments->sum('monto');

                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'cliente' => $p->cliente_nombre,
                    'cierre' => ($p->fecha_inicio ?? $p->created_at)->format('d/m/Y'),
                    'precio' => (float) $p->precio,
                    'moneda' => $p->moneda,
                    'comision_tipo' => $p->comision_tipo,
                    'comision_valor' => (float) $p->comision_valor,
                    'comision' => $comision,
                    'comision_base' => $comisionBase,
                    'pct_aplicado' => $pctAplicado,
                    'comision_cop' => $toCop($comision, $p->moneda),
                    'abonado' => $abonado,
                ];
            });

            $comisionesCop = $detalle->sum('comision_cop');
            $abonadoCop = $detalle->sum('abonado');
            $sueldoCop = $toCop((float) ($v->sueldo_basico ?? 0), $v->sueldo_moneda ?? 'COP');
            $pagos = $pagosLiquidacion->get($v->id, collect());
            $pagadoCop = (float) $pagos->sum('monto');
            $totalCop = $sueldoCop + $comisionesCop;

            // Cuánto ganaría con un cierre más (para mostrarle la zanahoria del siguiente tramo)
            $siguienteTramo = null;
            if ($v->escalonada_activa) {
                $tramos = collect($v->escalones ?: Vendedor::ESCALONES_DEFAULT)->sortBy('desde');
                $prox = $tramos->first(fn ($t) => (int) ($t['desde'] ?? 0) > $proyectos->count());
                if ($prox) {
                    $siguienteTramo = [
                        'faltan' => (int) $prox['desde'] - $proyectos->count(),
                        'pct' => (float) $prox['pct'],
                    ];
                }
            }

            return [
                'vendedor' => $v,
                'proyectos' => $detalle,
                'sueldo_cop' => $sueldoCop,
                'comisiones_cop' => $comisionesCop,
                'abonado_cop' => $abonadoCop,
                'total_cop' => $totalCop,
                'pendiente_cop' => max($sueldoCop + $comisionesCop - $abonadoCop, 0),
                'pct_escalon' => $pctEscalon,
                'siguiente_tramo' => $siguienteTramo,
                'pagos' => $pagos,
                'pagado_cop' => $pagadoCop,
                'saldo_liquidacion' => max($totalCop - $pagadoCop, 0),
                'estado_pago' => $totalCop <= 0 ? 'na' : ($pagadoCop + 1 >= $totalCop ? 'pagado' : ($pagadoCop > 0 ? 'parcial' : 'pendiente')),
            ];
        })->sortByDesc('total_cop')->values();

        return view('admin.internal-projects.liquidacion', [
            'liquidaciones' => $liquidaciones,
            'cicloInicio' => $cicloInicio,
            'cicloFin' => $cicloFin,
            'mesCorte' => $mesCorte,
            'usdCop' => $usdCop,
        ]);
    }

    /**
     * Documento imprimible de liquidación individual del vendedor:
     * honorarios fijos + detalle de comisiones del ciclo 20-a-20 + total.
     */
    public function liquidacionVendedorDocumento(Request $request, Vendedor $vendedor)
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $eurUsd = (float) config('services.eur_usd', env('EUR_USD_RATE', 1.17));
        $toCop = function (float $monto, string $moneda) use ($usdCop, $eurUsd): float {
            return match ($moneda) {
                'USD' => $monto * $usdCop,
                'EUR' => $monto * $eurUsd * $usdCop,
                default => $monto,
            };
        };

        [$cicloInicio, $cicloFin, $mesCorte] = $this->cicloLiquidacion($request);

        $proyectos = InternalProject::where('vendedor_id', $vendedor->id)
            ->get()
            ->filter(function ($p) use ($cicloInicio, $cicloFin) {
                $cierre = $p->fecha_inicio ?? $p->created_at;

                return $cierre && $cierre->between($cicloInicio, $cicloFin);
            })
            ->values();

        $pctEscalon = $vendedor->porcentajePorCierres($proyectos->count());

        $proyectos = $proyectos->map(function ($p) use ($toCop, $pctEscalon) {
            $comision = (float) $p->comision_calculada;
            $pctAplicado = null;

            // El escalón es un incentivo: solo aplica si MEJORA lo pactado en el proyecto.
            if ($pctEscalon !== null && $p->comision_tipo === 'porcentaje') {
                $comisionEscalon = round((float) $p->precio * ($pctEscalon / 100), 2);
                if ($comisionEscalon > $comision) {
                    $comision = $comisionEscalon;
                    $pctAplicado = $pctEscalon;
                }
            }

            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'cliente' => $p->cliente_nombre,
                'cierre' => ($p->fecha_inicio ?? $p->created_at)->format('d/m/Y'),
                'precio' => (float) $p->precio,
                'moneda' => $p->moneda,
                'comision_tipo' => $p->comision_tipo,
                'comision_valor' => (float) $p->comision_valor,
                'comision' => $comision,
                'pct_aplicado' => $pctAplicado,
                'comision_cop' => $toCop($comision, $p->moneda),
            ];
        });

        $sueldoCop = $toCop((float) ($vendedor->sueldo_basico ?? 0), $vendedor->sueldo_moneda ?? 'COP');
        $comisionesCop = $proyectos->sum('comision_cop');

        return view('admin.internal-projects.liquidacion-documento', [
            'vendedor' => $vendedor,
            'proyectos' => $proyectos,
            'cicloInicio' => $cicloInicio,
            'cicloFin' => $cicloFin,
            'mesCorte' => $mesCorte,
            'sueldoCop' => $sueldoCop,
            'comisionesCop' => $comisionesCop,
            'pctEscalon' => $pctEscalon,
            'totalCop' => $sueldoCop + $comisionesCop,
        ]);
    }

    /** Actualiza el sueldo básico de un vendedor desde la pantalla de liquidación. */
    public function actualizarSueldoVendedor(Request $request, Vendedor $vendedor)
    {
        $validated = $request->validate([
            'sueldo_basico' => 'nullable|numeric|min:0',
            'sueldo_moneda' => 'required|in:COP,USD,EUR',
        ]);

        $vendedor->update($validated);

        return back()->with('success', 'Sueldo de '.$vendedor->nombre.' actualizado.');
    }

    /** Configura la comisión escalonada (tramos por cantidad de cierres) de un vendedor. */
    public function actualizarEscalonesVendedor(Request $request, Vendedor $vendedor)
    {
        $validated = $request->validate([
            'escalonada_activa' => 'nullable|boolean',
            'tramos' => 'nullable|array|max:5',
            'tramos.*.desde' => 'required_with:tramos|integer|min:1|max:99',
            'tramos.*.pct' => 'required_with:tramos|numeric|min:0|max:100',
        ]);

        $tramos = collect($validated['tramos'] ?? [])
            ->filter(fn ($t) => isset($t['desde'], $t['pct']))
            ->map(fn ($t) => ['desde' => (int) $t['desde'], 'pct' => (float) $t['pct']])
            ->sortBy('desde')
            ->values()
            ->all();

        $vendedor->update([
            'escalonada_activa' => $request->boolean('escalonada_activa'),
            'escalones' => $tramos ?: Vendedor::ESCALONES_DEFAULT,
        ]);

        return back()->with('success', 'Comisión escalonada actualizada para '.$vendedor->nombre.'.');
    }

    /** Registra el pago de la liquidación de un mes al vendedor, con comprobante adjunto opcional. */
    public function storeLiquidacionPago(Request $request, Vendedor $vendedor)
    {
        $validated = $request->validate([
            'periodo' => 'required|date',
            'fecha_pago' => 'required|date',
            'monto' => 'required|numeric|min:0.01',
            'metodo' => 'nullable|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:8192',
        ]);

        $rutaComprobante = null;
        if ($request->hasFile('comprobante')) {
            $rutaComprobante = $request->file('comprobante')->store('liquidaciones', 'public');
        }

        \App\Models\LiquidacionPago::create([
            'vendedor_id' => $vendedor->id,
            'periodo' => Carbon::parse($validated['periodo'])->startOfDay(),
            'fecha_pago' => $validated['fecha_pago'],
            'monto' => $validated['monto'],
            'metodo' => $validated['metodo'] ?? null,
            'referencia' => $validated['referencia'] ?? null,
            'nota' => $validated['nota'] ?? null,
            'comprobante' => $rutaComprobante,
        ]);

        return back()->with('success', 'Pago de liquidación registrado para '.$vendedor->nombre.'.');
    }

    /** Elimina un pago de liquidación (y su comprobante en disco). */
    public function destroyLiquidacionPago(\App\Models\LiquidacionPago $pago)
    {
        if ($pago->comprobante) {
            Storage::disk('public')->delete($pago->comprobante);
        }
        $pago->delete();

        return back()->with('success', 'Pago de liquidación eliminado.');
    }

    // --- Developer Payments ---

    public function storeDeveloperPayment(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD,EUR',
            'fecha' => 'required|date',
            'metodo' => 'nullable|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
        ]);

        $internal_project->developerPayments()->create($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago al desarrollador registrado.');
    }

    public function destroyDeveloperPayment(InternalProject $internal_project, DeveloperPayment $developerPayment)
    {
        $developerPayment->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago al desarrollador eliminado.');
    }

    // --- Pagos de Gestión (vendedor) ---

    public function storeGestionPayment(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD,EUR',
            'fecha' => 'required|date',
            'metodo' => 'nullable|string|max:100',
            'referencia' => 'nullable|string|max:255',
            'nota' => 'nullable|string|max:500',
        ]);

        $internal_project->gestionPayments()->create($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago de gestión registrado.');
    }

    public function destroyGestionPayment(InternalProject $internal_project, \App\Models\GestionPayment $gestionPayment)
    {
        $gestionPayment->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago de gestión eliminado.');
    }

    // --- Otros Gastos ---

    public function storeExpense(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD,EUR',
            'fecha' => 'required|date',
            'categoria' => 'nullable|string|max:100',
        ]);

        $internal_project->expenses()->create($validated);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Gasto registrado.');
    }

    public function destroyExpense(InternalProject $internal_project, ProjectExpense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Gasto eliminado.');
    }

    // --- Files ---

    public function storeFile(Request $request, InternalProject $internal_project)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'archivo' => 'required|file|max:20480', // 20MB max
        ]);

        $file = $request->file('archivo');
        $path = $file->store('internal-projects/'.$internal_project->id, 'public');

        $internal_project->files()->create([
            'nombre' => $request->nombre,
            'archivo' => $path,
            'tipo' => $file->getMimeType(),
            'tamano' => $file->getSize(),
        ]);

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Archivo subido exitosamente.');
    }

    public function destroyFile(InternalProject $internal_project, ProjectFile $file)
    {
        Storage::disk('public')->delete($file->archivo);
        $file->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Archivo eliminado.');
    }
}
