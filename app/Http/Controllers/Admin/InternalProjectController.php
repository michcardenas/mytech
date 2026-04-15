<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\ProjectPayment;
use App\Models\DeveloperPayment;
use App\Models\ProjectExpense;
use App\Models\ProjectFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InternalProjectController extends Controller
{
    public function index(Request $request)
    {
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));

        $query = InternalProject::withCount('payments', 'files', 'developerPayments')
            ->withSum('payments', 'monto')
            ->withSum('developerPayments as developer_payments_sum_monto', 'monto');

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
                      ->orWhereHas('payments', fn($p) => $p->whereBetween('fecha', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]));
                }),
                'mes_anterior' => $query->where(function ($q) use ($now) {
                    $ini = $now->copy()->subMonth()->startOfMonth();
                    $fin = $now->copy()->subMonth()->endOfMonth();
                    $q->whereBetween('fecha_inicio', [$ini, $fin])
                      ->orWhereBetween('created_at', [$ini, $fin])
                      ->orWhereHas('payments', fn($p) => $p->whereBetween('fecha', [$ini, $fin]));
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

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('cliente_nombre', 'like', "%{$search}%")
                  ->orWhere('desarrollador_nombre', 'like', "%{$search}%");
            });
        }

        match ($request->get('orden', 'reciente')) {
            'mayor_saldo_cliente' => $query->orderByRaw('(precio - COALESCE((SELECT SUM(monto) FROM project_payments WHERE project_payments.internal_project_id = internal_projects.id),0)) DESC'),
            'mayor_deuda_dev' => $query->orderByRaw('(COALESCE(desarrollador_pago,0) - COALESCE((SELECT SUM(monto) FROM developer_payments WHERE developer_payments.internal_project_id = internal_projects.id),0)) DESC'),
            'mayor_precio' => $query->orderBy('precio', 'desc'),
            'fecha_entrega' => $query->orderByRaw('fecha_entrega IS NULL, fecha_entrega ASC'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = (int) $request->get('per_page', 15);
        if (!in_array($perPage, [10, 15, 25, 50])) {
            $perPage = 15;
        }

        $projects = $query->paginate($perPage)->withQueryString();

        // Stats operacionales
        $totalCount = InternalProject::count();
        $activosCount = InternalProject::whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count();
        $completadosCount = InternalProject::where('estado', 'completado')->count();
        $sinDevCount = InternalProject::whereNull('desarrollador_nombre')
            ->whereIn('estado', ['en_progreso', 'cotizado'])
            ->count();

        // Stats financieros: cargamos los proyectos activos con sums y calculamos en PHP
        $activos = InternalProject::whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])
            ->withSum('payments', 'monto')
            ->withSum('developerPayments as developer_payments_sum_monto', 'monto')
            ->get();

        $porCobrarCop = 0;
        $proyectosConDeuda = 0;
        foreach ($activos as $p) {
            $saldo = (float) $p->precio - (float) ($p->payments_sum_monto ?? 0);
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

        // Utilidad del mes: flujo de caja neto del mes actual
        $ini = now()->startOfMonth();
        $fin = now()->endOfMonth();

        $ingresosMes = ProjectPayment::with('project:id,moneda')
            ->whereBetween('fecha', [$ini, $fin])
            ->get()
            ->sum(function ($p) use ($usdCop) {
                if ($p->monto_recibido_cop) return (float) $p->monto_recibido_cop;
                $moneda = optional($p->project)->moneda ?? 'COP';
                return $moneda === 'USD' ? (float) $p->monto * $usdCop : (float) $p->monto;
            });

        $pagosDevMes = DeveloperPayment::whereBetween('fecha', [$ini, $fin])
            ->get()
            ->sum(fn($p) => $p->moneda === 'USD' ? (float) $p->monto * $usdCop : (float) $p->monto);

        $gastosMes = ProjectExpense::whereBetween('fecha', [$ini, $fin])
            ->get()
            ->sum(fn($e) => ($e->moneda ?? 'COP') === 'USD' ? (float) $e->monto * $usdCop : (float) $e->monto);

        $utilidadMes = $ingresosMes - $pagosDevMes - $gastosMes;

        $proyectosMesCount = InternalProject::whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])
            ->where(function ($q) use ($ini, $fin) {
                $q->whereBetween('fecha_inicio', [$ini, $fin])
                  ->orWhereBetween('created_at', [$ini, $fin])
                  ->orWhereHas('payments', fn($p) => $p->whereBetween('fecha', [$ini, $fin]));
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
            'proyectos_mes' => $proyectosMesCount,
        ];

        $desarrolladores = InternalProject::whereNotNull('desarrollador_nombre')
            ->where('desarrollador_nombre', '!=', '')
            ->distinct()
            ->orderBy('desarrollador_nombre')
            ->pluck('desarrollador_nombre');

        return view('admin.internal-projects.index', compact('projects', 'stats', 'desarrolladores', 'usdCop'));
    }

    public function stats(Request $request)
    {
        $data = $this->buildStatsData($request);

        return view('admin.internal-projects.stats', $data);
    }

    public function statsExport(Request $request): StreamedResponse
    {
        $data = $this->buildStatsData($request, includeAllMovimientos: true);
        $movimientos = $data['movimientos'];
        $start = $data['rango']['start'];
        $end = $data['rango']['end'];

        $filename = 'reporte_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($movimientos) {
            $out = fopen('php://output', 'w');
            fputs($out, "\xEF\xBB\xBF");
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
        )->get()->map(function ($p) use ($toCop) {
                $moneda = optional($p->project)->moneda ?? 'COP';
                $cop = $p->monto_recibido_cop ? (float) $p->monto_recibido_cop : $toCop($p->monto, $moneda);
                return [
                    'fecha' => $p->fecha,
                    'tipo' => 'Ingreso',
                    'proyecto' => optional($p->project)->nombre ?? '—',
                    'cliente' => optional($p->project)->cliente_nombre ?? '—',
                    'concepto' => trim(($p->metodo ?? '') . ($p->referencia ? ' · ' . $p->referencia : '')) ?: ($p->nota ?? ''),
                    'monto' => (float) $p->monto,
                    'moneda' => $moneda,
                    'monto_cop' => $cop,
                ];
            });

        $pagosDev = $applyDevScope(
            DeveloperPayment::with('project:id,nombre,cliente_nombre,desarrollador_nombre')
                ->whereBetween('fecha', [$start, $end])
        )->get()->map(function ($p) use ($toCop) {
                $moneda = $p->moneda ?? 'COP';
                return [
                    'fecha' => $p->fecha,
                    'tipo' => 'Pago dev',
                    'proyecto' => optional($p->project)->nombre ?? '—',
                    'cliente' => optional($p->project)->desarrollador_nombre ?? '—',
                    'concepto' => trim(($p->metodo ?? '') . ($p->referencia ? ' · ' . $p->referencia : '')) ?: ($p->nota ?? ''),
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
            if ($k = $assign($p['fecha'], $buckets)) $buckets[$k]['ing'] += $p['monto_cop'];
        }
        foreach ($pagosDev as $p) {
            if ($k = $assign($p['fecha'], $buckets)) $buckets[$k]['egr'] += $p['monto_cop'];
        }
        foreach ($gastos as $g) {
            if ($k = $assign($g['fecha'], $buckets)) $buckets[$k]['egr'] += $g['monto_cop'];
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
        ];
    }

    public function create()
    {
        return view('admin.internal-projects.form', [
            'project' => new InternalProject(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_contacto' => 'nullable|string|max:255',
            'cliente_email' => 'nullable|email|max:255',
            'fuente' => 'required|in:directo,workana',
            'fuente_url' => 'nullable|url|max:500',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD',
            'estado' => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_entrega' => 'nullable|date|required_without:es_recurrente',
            'es_recurrente' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
            'notas' => 'nullable|string',
            'desarrollador_nombre' => 'nullable|string|max:255',
            'desarrollador_email' => 'nullable|email|max:255',
            'desarrollador_pago' => 'nullable|numeric|min:0',
            'desarrollador_moneda' => 'required|in:COP,USD',
        ]);

        $validated['es_recurrente'] = $request->boolean('es_recurrente');
        if ($validated['es_recurrente']) {
            $validated['fecha_entrega'] = null;
        }

        $project = InternalProject::create($validated);

        return redirect()->route('admin.internal-projects.show', $project)
            ->with('success', 'Proyecto creado exitosamente.');
    }

    public function show(InternalProject $internal_project)
    {
        $internal_project->load([
            'payments' => fn($q) => $q->orderBy('fecha', 'desc'),
            'developerPayments' => fn($q) => $q->orderBy('fecha', 'desc'),
            'expenses' => fn($q) => $q->orderBy('fecha', 'desc'),
            'files',
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
        ]);
    }

    public function update(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_contacto' => 'nullable|string|max:255',
            'cliente_email' => 'nullable|email|max:255',
            'fuente' => 'required|in:directo,workana',
            'fuente_url' => 'nullable|url|max:500',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD',
            'estado' => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'nullable|date',
            'fecha_entrega' => 'nullable|date|required_without:es_recurrente',
            'es_recurrente' => 'nullable|boolean',
            'descripcion' => 'nullable|string',
            'notas' => 'nullable|string',
            'desarrollador_nombre' => 'nullable|string|max:255',
            'desarrollador_email' => 'nullable|email|max:255',
            'desarrollador_pago' => 'nullable|numeric|min:0',
            'desarrollador_moneda' => 'required|in:COP,USD',
        ]);

        $validated['es_recurrente'] = $request->boolean('es_recurrente');
        if ($validated['es_recurrente']) {
            $validated['fecha_entrega'] = null;
        }

        $internal_project->update($validated);

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

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function destroyPayment(InternalProject $internal_project, ProjectPayment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.internal-projects.show', $internal_project)
            ->with('success', 'Pago eliminado.');
    }

    // --- Developer Payments ---

    public function storeDeveloperPayment(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD',
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

    // --- Otros Gastos ---

    public function storeExpense(Request $request, InternalProject $internal_project)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:COP,USD',
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
        $path = $file->store('internal-projects/' . $internal_project->id, 'public');

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
