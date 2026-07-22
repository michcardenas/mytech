<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\GestionPayment;
use App\Models\InternalProject;
use App\Models\Vendedor;
use Illuminate\Http\Request;

class PortalVendedorController extends Controller
{
    use PortalAuth;

    public function showLogin(Request $request)
    {
        if ($request->session()->get('portal_vendedor_id')) {
            return redirect()->route('portal.vendedor.dashboard');
        }

        return view('portal.login', [
            'role' => 'vendedor',
            'titulo' => 'Portal de gestores',
            'subtitulo' => 'Consulta tus comisiones, pagos y proyectos gestionados',
            'icon' => 'fa-handshake',
            'color' => 'green',
            'route_login' => route('portal.vendedor.login'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate(['telefono' => 'required|string|max:30']);

        if ($this->tooManyAttempts($request, 'vendedor')) {
            return back()->withErrors(['telefono' => 'Demasiados intentos. Intenta nuevamente en 10 minutos.']);
        }

        $normalized = $this->normalizePhone($request->telefono);
        if (strlen($normalized) < 7) {
            $this->recordAttempt($request, 'vendedor');

            return back()->withErrors(['telefono' => 'Número inválido.'])->withInput();
        }

        $vendedores = Vendedor::whereNotNull('telefono')->get();
        $match = $vendedores->first(fn ($v) => $this->phonesMatch($v->telefono, $normalized));

        if (! $match) {
            $this->recordAttempt($request, 'vendedor');

            return back()->withErrors(['telefono' => 'No encontramos un gestor con ese número.'])->withInput();
        }

        $this->clearAttempts($request, 'vendedor');
        $request->session()->put('portal_vendedor_id', $match->id);

        return redirect()->route('portal.vendedor.dashboard');
    }

    public function dashboard(Request $request)
    {
        $vendId = $request->session()->get('portal_vendedor_id');
        if (! $vendId) {
            return redirect()->route('portal.vendedor.login.show');
        }

        $vendedor = Vendedor::find($vendId);
        if (! $vendedor) {
            $request->session()->forget('portal_vendedor_id');

            return redirect()->route('portal.vendedor.login.show');
        }

        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));

        $projects = InternalProject::where('vendedor_id', $vendedor->id)
            ->withSum('payments as payments_sum', 'monto')
            ->withSum('payments as payments_sum_cop', 'monto_recibido_cop')
            ->withSum('gestionPayments as gestion_payments_sum', 'monto')
            ->withCount('gestionPayments')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        $proyectosResumen = $projects->map(function ($p) use ($usdCop) {
            $cobrado = (float) ($p->payments_sum ?? 0);
            $netoCop = (float) ($p->payments_sum_cop ?? 0);
            $ingresoCopBase = $p->moneda === 'USD'
                ? ($netoCop > 0 ? $netoCop : $cobrado * $usdCop)
                : $cobrado;
            $devMoneda = $p->desarrollador_moneda ?? 'COP';
            $pagoDev = (float) ($p->desarrollador_pago ?? 0);
            $pagoDevCop = $devMoneda === 'USD' ? $pagoDev * $usdCop : $pagoDev;

            $comision = 0;
            if ($p->comision_tipo && $p->comision_valor) {
                if ($p->comision_tipo === 'monto') {
                    $comision = (float) $p->comision_valor;
                } else {
                    $precioCop = $p->moneda === 'USD' ? (float) $p->precio * $usdCop : (float) $p->precio;
                    $comision = $precioCop * ((float) $p->comision_valor / 100);
                }
            }
            $abonado = (float) ($p->gestion_payments_sum ?? 0);
            $saldo = max($comision - $abonado, 0);

            return [
                'id' => $p->id,
                'nombre' => $p->nombre,
                'cliente' => $p->cliente_nombre,
                'estado' => $p->estado,
                'estado_label' => $p->estado_label,
                'estado_color' => $p->estado_color,
                'fecha_inicio' => $p->fecha_inicio,
                'fecha_entrega' => $p->fecha_entrega,
                'comision_tipo' => $p->comision_tipo,
                'comision_valor' => (float) $p->comision_valor,
                'comision' => $comision,
                'abonado' => $abonado,
                'saldo' => $saldo,
                'pct' => $comision > 0 ? round(($abonado / $comision) * 100, 1) : 0,
            ];
        });

        $totalComision = $proyectosResumen->sum('comision');
        $totalAbonado = $proyectosResumen->sum('abonado');
        $totalSaldo = $proyectosResumen->sum('saldo');

        $proyectosActivos = $projects->whereIn('estado', ['en_progreso', 'cotizado', 'pausado'])->count();
        $proyectosCompletados = $projects->where('estado', 'completado')->count();

        $primerProyecto = $projects->pluck('fecha_inicio')->filter()->min()
            ?? $projects->pluck('created_at')->filter()->min();

        $payments = GestionPayment::with('project:id,nombre,cliente_nombre')
            ->whereIn('internal_project_id', $projects->pluck('id'))
            ->orderBy('fecha', 'desc')
            ->get();

        $ultimoPago = $payments->first()?->fecha;

        $kpis = [
            'comision_cop' => $totalComision,
            'abonado_cop' => $totalAbonado,
            'saldo_cop' => $totalSaldo,
            'porcentaje' => $totalComision > 0 ? round(($totalAbonado / $totalComision) * 100, 1) : 0,
            'proyectos_total' => $projects->count(),
            'proyectos_activos' => $proyectosActivos,
            'proyectos_completados' => $proyectosCompletados,
            'desde' => $primerProyecto ? \Carbon\Carbon::parse($primerProyecto) : null,
            'ultimo_pago' => $ultimoPago,
        ];

        return view('portal.vendedor-dashboard', compact('vendedor', 'kpis', 'proyectosResumen', 'payments'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('portal_vendedor_id');

        return redirect()->route('portal.vendedor.login.show')->with('success', 'Sesión cerrada.');
    }
}
