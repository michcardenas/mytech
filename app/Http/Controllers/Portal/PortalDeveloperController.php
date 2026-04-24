<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\DeveloperPayment;
use App\Models\InternalProject;
use Illuminate\Http\Request;

class PortalDeveloperController extends Controller
{
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
        $match = $developers->first(fn ($d) => $this->normalizePhone($d->telefono) === $normalized);

        if (!$match) {
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
        if (!$devId) return redirect()->route('portal.developer.login.show');

        $developer = Developer::find($devId);
        if (!$developer) {
            $request->session()->forget('portal_developer_id');
            return redirect()->route('portal.developer.login.show');
        }

        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $toCop = fn ($monto, $moneda) => $moneda === 'USD' ? (float) $monto * $usdCop : (float) $monto;

        // Proyectos donde el dev está asignado: por developer_id O por nombre legacy
        $projects = InternalProject::where(function ($q) use ($developer) {
                $q->where('developer_id', $developer->id)
                  ->orWhere('desarrollador_nombre', $developer->nombre);
            })
            ->withSum('developerPayments as developer_payments_sum', 'monto')
            ->withCount('developerPayments')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $proyectosResumen = $projects->map(function ($p) use ($toCop) {
            $devMoneda = $p->desarrollador_moneda ?? 'COP';
            $asignado = (float) ($p->desarrollador_pago ?? 0);
            $pagado = (float) ($p->developer_payments_sum ?? 0);
            $saldo = max($asignado - $pagado, 0);
            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'cliente' => $p->cliente_nombre,
                'estado' => $p->estado,
                'estado_label' => $p->estado_label,
                'estado_color' => $p->estado_color,
                'fecha_inicio' => $p->fecha_inicio,
                'fecha_entrega' => $p->fecha_entrega,
                'es_recurrente' => $p->es_recurrente,
                'asignado' => $asignado,
                'pagado' => $pagado,
                'saldo' => $saldo,
                'moneda' => $devMoneda,
                'asignado_cop' => $toCop($asignado, $devMoneda),
                'pagado_cop' => $toCop($pagado, $devMoneda),
                'saldo_cop' => $toCop($saldo, $devMoneda),
                'pct' => $asignado > 0 ? round(($pagado / $asignado) * 100, 1) : 0,
            ];
        });

        $totalAsignadoCop = $proyectosResumen->sum('asignado_cop');
        $totalPagadoCop = $proyectosResumen->sum('pagado_cop');
        $totalSaldoCop = $proyectosResumen->sum('saldo_cop');

        $primerProyecto = $projects->pluck('fecha_inicio')->filter()->min()
            ?? $projects->pluck('created_at')->filter()->min();

        $proyectosActivos = $projects->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count();
        $proyectosCompletados = $projects->where('estado', 'completado')->count();

        // Pagos recibidos (cronológico desc)
        $projectIds = $projects->pluck('id');
        $payments = DeveloperPayment::with('project:id,nombre,cliente_nombre')
            ->whereIn('internal_project_id', $projectIds)
            ->orderBy('fecha', 'desc')
            ->get();

        $ultimoPago = $payments->first()?->fecha;

        $kpis = [
            'asignado_cop' => $totalAsignadoCop,
            'pagado_cop' => $totalPagadoCop,
            'saldo_cop' => $totalSaldoCop,
            'porcentaje' => $totalAsignadoCop > 0 ? round(($totalPagadoCop / $totalAsignadoCop) * 100, 1) : 0,
            'proyectos_total' => $projects->count(),
            'proyectos_activos' => $proyectosActivos,
            'proyectos_completados' => $proyectosCompletados,
            'desde' => $primerProyecto ? \Carbon\Carbon::parse($primerProyecto) : null,
            'ultimo_pago' => $ultimoPago,
        ];

        return view('portal.developer-dashboard', compact('developer', 'kpis', 'proyectosResumen', 'payments'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('portal_developer_id');
        return redirect()->route('portal.developer.login.show')->with('success', 'Sesión cerrada.');
    }
}
