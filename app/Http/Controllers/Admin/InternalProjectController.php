<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\ProjectPayment;
use App\Models\DeveloperPayment;
use App\Models\ProjectExpense;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
