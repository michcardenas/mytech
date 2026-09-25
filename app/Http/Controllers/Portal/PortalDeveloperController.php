<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Concerns\BoardFiles;
use App\Http\Controllers\Concerns\BoardTasks;
use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\DeveloperPayment;
use App\Models\InternalProject;
use App\Models\ProjectFile;
use App\Models\ProjectSubtask;
use App\Models\ProjectTask;
use App\Models\ProjectTaskFile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PortalDeveloperController extends Controller
{
    use BoardFiles;
    use BoardTasks;
    use PortalAuth;

    public function showLogin(Request $request)
    {
        if ($request->session()->get('portal_developer_id')) {
            return redirect()->route('portal.developer.dashboard');
        }

        return view('portal.login', [
            'role' => 'developer',
            'titulo' => 'Portal de desarrolladores',
            'subtitulo' => 'Consulta tus proyectos, pagos y métricas',
            'icon' => 'fa-laptop-code',
            'color' => 'purple',
            'route_login' => route('portal.developer.login'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate(['telefono' => 'required|string|max:30']);

        if ($this->tooManyAttempts($request, 'developer')) {
            return back()->withErrors(['telefono' => 'Demasiados intentos. Intenta nuevamente en 10 minutos.']);
        }

        $normalized = $this->normalizePhone($request->telefono);
        if (strlen($normalized) < 7) {
            $this->recordAttempt($request, 'developer');

            return back()->withErrors(['telefono' => 'Número inválido.'])->withInput();
        }

        // Buscar dev cuyo telefono normalizado coincida
        $developers = Developer::whereNotNull('telefono')->get();
        $match = $developers->first(fn ($d) => $this->phonesMatch($d->telefono, $normalized));

        if (! $match) {
            $this->recordAttempt($request, 'developer');

            return back()->withErrors(['telefono' => 'No encontramos un desarrollador con ese número.'])->withInput();
        }

        $this->clearAttempts($request, 'developer');
        $request->session()->put('portal_developer_id', $match->id);

        return redirect()->route('portal.developer.dashboard');
    }

    public function dashboard(Request $request)
    {
        $devId = $request->session()->get('portal_developer_id');
        if (! $devId) {
            return redirect()->route('portal.developer.login.show');
        }

        $developer = Developer::find($devId);
        if (! $developer) {
            $request->session()->forget('portal_developer_id');

            return redirect()->route('portal.developer.login.show');
        }

        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $toCop = fn ($monto, $moneda) => $moneda === 'USD' ? (float) $monto * $usdCop : (float) $monto;

        // === Mes seleccionado (default: mes actual) ===
        $selectedMonth = $request->get('mes', now()->format('Y-m'));
        try {
            $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Exception $e) {
            $monthStart = now()->startOfMonth();
            $selectedMonth = now()->format('Y-m');
        }
        $monthEnd = $monthStart->copy()->endOfMonth();
        $isCurrentMonth = $monthStart->isSameMonth(now());

        // === Proyectos del dev (por FK o nombre legacy) ===
        $projects = InternalProject::where(function ($q) use ($developer) {
            $q->where('developer_id', $developer->id)
                ->orWhere('desarrollador_nombre', $developer->nombre);
        })
            ->withSum('developerPayments as total_pagado_dev', 'monto')
            ->withCount('developerPayments')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $projectIds = $projects->pluck('id');

        // === Todos los pagos al dev (todo el histórico) ===
        $allPayments = DeveloperPayment::with('project:id,nombre,cliente_nombre,es_recurrente,desarrollador_pago,desarrollador_moneda')
            ->whereIn('internal_project_id', $projectIds)
            ->orderBy('fecha', 'desc')
            ->get();

        // === Pagos del mes seleccionado ===
        $monthPayments = $allPayments->filter(fn ($p) => $p->fecha
            && $p->fecha->between($monthStart, $monthEnd))->values();
        $totalMesCop = $monthPayments->sum(fn ($p) => $toCop($p->monto, $p->moneda ?? 'COP'));

        // === Recurrentes en el mes seleccionado ===
        $resumenRecurrentes = $projects->where('es_recurrente', true)
            ->where('estado', '!=', 'cancelado')
            ->map(function ($p) use ($monthPayments, $toCop) {
                $devMoneda = $p->desarrollador_moneda ?? 'COP';
                $asignadoMensual = (float) ($p->desarrollador_pago ?? 0);
                $pagosMes = $monthPayments->where('internal_project_id', $p->id);
                $cobradoMes = $pagosMes->sum('monto');

                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'cliente' => $p->cliente_nombre,
                    'estado_label' => $p->estado_label,
                    'estado_color' => $p->estado_color,
                    'asignado_mensual' => $asignadoMensual,
                    'cobrado_mes' => $cobradoMes,
                    'pendiente_mes' => max($asignadoMensual - $cobradoMes, 0),
                    'moneda' => $devMoneda,
                    'pagos_mes_count' => $pagosMes->count(),
                    'cobrado_mes_cop' => $toCop($cobradoMes, $devMoneda),
                    'pct' => $asignadoMensual > 0
                        ? min(round(($cobradoMes / $asignadoMensual) * 100), 100)
                        : ($cobradoMes > 0 ? 100 : 0),
                ];
            })->values();

        // === Proyectos no-recurrentes:
        //  (a) con pago en este mes (cobrados este mes), O
        //  (b) con saldo pendiente y no cancelados (siguen apareciendo aunque no haya pago este mes)
        $idsOneShotConPagoMes = $monthPayments->filter(fn ($p) => $p->project && ! $p->project->es_recurrente)
            ->pluck('internal_project_id')->unique();

        $resumenOneShot = $projects->where('es_recurrente', false)
            ->filter(function ($p) use ($idsOneShotConPagoMes) {
                $asignado = (float) ($p->desarrollador_pago ?? 0);
                $pagado = (float) ($p->total_pagado_dev ?? 0);
                $tienePendiente = $asignado > $pagado && $p->estado !== 'cancelado';

                return $idsOneShotConPagoMes->contains($p->id) || $tienePendiente;
            })
            ->map(function ($p) use ($allPayments, $monthPayments, $toCop, $idsOneShotConPagoMes) {
                $devMoneda = $p->desarrollador_moneda ?? 'COP';
                $asignado = (float) ($p->desarrollador_pago ?? 0);
                $totalPagado = (float) ($p->total_pagado_dev ?? 0);
                $pagosMes = $monthPayments->where('internal_project_id', $p->id);
                $cobradoMes = $pagosMes->sum('monto');
                $totalPagosProyecto = $allPayments->where('internal_project_id', $p->id)->count();
                $pendiente = max($asignado - $totalPagado, 0);
                $tienePagoMes = $idsOneShotConPagoMes->contains($p->id);

                // Status: cobrado_mes / pendiente / completo
                if ($tienePagoMes && $pendiente <= 0) {
                    $status = 'completo';
                } elseif ($tienePagoMes) {
                    $status = 'cobrado_mes';
                } else {
                    $status = 'pendiente';
                }

                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'cliente' => $p->cliente_nombre,
                    'estado_label' => $p->estado_label,
                    'estado_color' => $p->estado_color,
                    'asignado' => $asignado,
                    'pagado_total' => $totalPagado,
                    'cobrado_mes' => $cobradoMes,
                    'pendiente' => $pendiente,
                    'moneda' => $devMoneda,
                    'pagos_mes_count' => $pagosMes->count(),
                    'pagos_total_count' => $totalPagosProyecto,
                    'cobrado_mes_cop' => $toCop($cobradoMes, $devMoneda),
                    'pendiente_cop' => $toCop($pendiente, $devMoneda),
                    'pct' => $asignado > 0 ? min(round(($totalPagado / $asignado) * 100), 100) : 0,
                    'tiene_pago_mes' => $tienePagoMes,
                    'status' => $status, // 'completo' | 'cobrado_mes' | 'pendiente'
                ];
            })
            // Orden: primero los cobrados este mes, después los pendientes
            ->sortBy(fn ($r) => $r['tiene_pago_mes'] ? 0 : 1)
            ->values();

        // Total pendiente lifetime (no-recurrentes activos)
        $totalPendienteCop = $resumenOneShot->where('status', '!=', 'completo')->sum('pendiente_cop');

        // === Histórico de últimos 12 meses (para selector + barras) ===
        $historico = collect(range(11, 0))->map(function ($i) use ($allPayments, $toCop, $selectedMonth) {
            $m = now()->subMonths($i);
            $start = $m->copy()->startOfMonth();
            $end = $m->copy()->endOfMonth();
            $pagos = $allPayments->filter(fn ($p) => $p->fecha && $p->fecha->between($start, $end));

            return [
                'key' => $m->format('Y-m'),
                'label_short' => $m->locale('es')->isoFormat('MMM'),
                'label_full' => $m->locale('es')->isoFormat('MMMM YYYY'),
                'total' => $pagos->sum(fn ($p) => $toCop($p->monto, $p->moneda ?? 'COP')),
                'count' => $pagos->count(),
                'is_current' => $m->isSameMonth(now()),
                'is_selected' => $m->format('Y-m') === $selectedMonth,
            ];
        })->values();

        $maxHistorico = $historico->max('total') ?: 1;

        // === Historial completo de pagos agrupado por mes (para tabla) ===
        $paymentsAgrupados = $allPayments->groupBy(fn ($p) => $p->fecha?->format('Y-m'))
            ->map(function ($pagos, $key) use ($toCop) {
                $first = $pagos->first()?->fecha ?? \Carbon\Carbon::now();

                return [
                    'key' => $key,
                    'label' => $first->locale('es')->isoFormat('MMMM YYYY'),
                    'total_cop' => $pagos->sum(fn ($p) => $toCop($p->monto, $p->moneda ?? 'COP')),
                    'pagos' => $pagos->values(),
                ];
            })->sortKeysDesc()->values();

        // === KPIs ===
        $totalLifetime = $allPayments->sum(fn ($p) => $toCop($p->monto, $p->moneda ?? 'COP'));
        $proyectosActivos = $projects->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count();
        $proyectosCompletados = $projects->where('estado', 'completado')->count();
        $primerProyecto = $projects->pluck('fecha_inicio')->filter()->min()
            ?? $projects->pluck('created_at')->filter()->min();

        $kpis = [
            'mes_label' => $monthStart->locale('es')->isoFormat('MMMM YYYY'),
            'mes_label_short' => $monthStart->locale('es')->isoFormat('MMM YYYY'),
            'mes_total_cop' => $totalMesCop,
            'mes_pagos_count' => $monthPayments->count(),
            'mes_proyectos_count' => $monthPayments->pluck('internal_project_id')->unique()->count(),
            'is_current_month' => $isCurrentMonth,
            'lifetime_cop' => $totalLifetime,
            'pendiente_total_cop' => $totalPendienteCop,
            'proyectos_total' => $projects->count(),
            'proyectos_activos' => $proyectosActivos,
            'proyectos_completados' => $proyectosCompletados,
            'recurrentes_count' => $projects->where('es_recurrente', true)->where('estado', '!=', 'cancelado')->count(),
            'pendientes_count' => $resumenOneShot->where('status', 'pendiente')->count(),
            'desde' => $primerProyecto ? \Carbon\Carbon::parse($primerProyecto) : null,
            'ultimo_pago' => $allPayments->first()?->fecha,
        ];

        // Tableros: TODOS los proyectos donde el dev esté asignado (principal, por nombre o en el equipo).
        $boards = InternalProject::where('developer_id', $developer->id)
            ->orWhere('desarrollador_nombre', $developer->nombre)
            ->orWhereHas('equipo', function ($q) use ($developer) {
                $q->where('developers.id', $developer->id);
            })
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'cliente_nombre']);

        return view('portal.developer-dashboard', compact(
            'developer', 'kpis', 'selectedMonth', 'isCurrentMonth',
            'resumenRecurrentes', 'resumenOneShot', 'monthPayments',
            'historico', 'maxHistorico', 'paymentsAgrupados', 'boards'
        ));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('portal_developer_id');

        return redirect()->route('portal.developer.login.show')->with('success', 'Sesión cerrada.');
    }

    /* ===================== Tablero de tareas (Kanban) ===================== */

    /** Developer autenticado por la sesión del portal, o null. */
    private function currentDev(Request $request): ?Developer
    {
        $id = $request->session()->get('portal_developer_id');

        return $id ? Developer::find($id) : null;
    }

    /** ¿El dev pertenece al proyecto? (equipo pivote o vínculo legacy por FK/nombre). */
    private function devPuedeVer(Developer $dev, InternalProject $project): bool
    {
        if ($project->equipo()->where('developers.id', $dev->id)->exists()) {
            return true;
        }

        return $project->developer_id === $dev->id
            || $project->desarrollador_nombre === $dev->nombre;
    }

    public function board(Request $request, InternalProject $internal_project)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }

        $project = $internal_project;

        // Asegura que el dev principal esté en el equipo para un tablero consistente.
        if ($project->developer_id && ! $project->equipo()->where('developers.id', $project->developer_id)->exists()) {
            $project->equipo()->attach($project->developer_id);
        }

        abort_unless($this->devPuedeVer($dev, $project), 403, 'No perteneces a este proyecto.');

        $project->load(['equipo', 'files', 'tasks.developer', 'tasks.subtasks.developer', 'tasks.files']);

        return view('portal.developer-board', [
            'project' => $project,
            'developer' => $dev,
            'columnas' => ProjectTask::COLUMNAS,
            'prioridades' => ProjectTask::PRIORIDADES,
            'tareasPorColumna' => $project->tasks->groupBy('columna'),
            'equipo' => $project->equipo,
            'documentos' => $project->files,
            'esAdmin' => false,
            'puedeEditar' => true,
        ]);
    }

    public function moveTask(Request $request, ProjectTask $task)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return response()->json(['ok' => false], 401);
        }
        abort_unless($this->devPuedeVer($dev, $task->project), 403);

        $data = $request->validate([
            'columna' => ['required', Rule::in(array_keys(ProjectTask::COLUMNAS))],
            'ids' => 'sometimes|array',
            'ids.*' => 'integer',
        ]);

        $task->update(['columna' => $data['columna']]);

        if (! empty($data['ids'])) {
            foreach ($data['ids'] as $orden => $id) {
                ProjectTask::where('internal_project_id', $task->internal_project_id)
                    ->where('id', $id)
                    ->update(['orden' => $orden]);
            }
        }

        return response()->json(['ok' => true, 'columna' => $task->columna]);
    }

    public function toggleSubtask(Request $request, ProjectSubtask $subtask)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return response()->json(['ok' => false], 401);
        }
        abort_unless($this->devPuedeVer($dev, $subtask->task->project), 403);

        $subtask->update(['hecha' => ! $subtask->hecha]);
        $task = $subtask->task->load('subtasks');

        return response()->json(['ok' => true, 'hecha' => $subtask->hecha, 'progreso' => $task->progreso]);
    }

    /* ===================== Tareas / subtareas (dev) ===================== */

    public function storeTask(Request $request, InternalProject $internal_project)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $internal_project), 403);

        $this->guardarTarea($request, $internal_project);

        return back()->with('success', 'Tarea creada.');
    }

    public function updateTask(Request $request, ProjectTask $task)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $task->project), 403);

        $this->actualizarTarea($request, $task);

        return back()->with('success', 'Tarea actualizada.');
    }

    public function storeSubtask(Request $request, ProjectTask $task)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $task->project), 403);

        $this->guardarSubtarea($request, $task);

        return back()->with('success', 'Subtarea agregada.');
    }

    public function updateSubtask(Request $request, ProjectSubtask $subtask)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $subtask->task->project), 403);

        $this->actualizarSubtarea($request, $subtask);

        return back()->with('success', 'Subtarea actualizada.');
    }

    public function destroySubtask(Request $request, ProjectSubtask $subtask)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $subtask->task->project), 403);

        $subtask->delete();

        return back()->with('success', 'Subtarea eliminada.');
    }

    /* ===================== Documentos (dev) ===================== */

    public function storeProjectFile(Request $request, InternalProject $internal_project)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $internal_project), 403);

        $this->guardarProjectFile($request, $internal_project);

        return back()->with('success', 'Documento del proyecto subido.');
    }

    public function destroyProjectFile(Request $request, ProjectFile $file)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $file->project), 403);

        $this->borrarProjectFile($file);

        return back()->with('success', 'Documento eliminado.');
    }

    public function storeTaskFile(Request $request, ProjectTask $task)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $task->project), 403);

        $this->guardarTaskFile($request, $task, $dev->nombre);

        return back()->with('success', 'Archivo adjuntado a la tarea.');
    }

    public function destroyTaskFile(Request $request, ProjectTaskFile $taskFile)
    {
        $dev = $this->currentDev($request);
        if (! $dev) {
            return redirect()->route('portal.developer.login.show');
        }
        abort_unless($this->devPuedeVer($dev, $taskFile->task->project), 403);

        $this->borrarTaskFile($taskFile);

        return back()->with('success', 'Archivo eliminado.');
    }
}
