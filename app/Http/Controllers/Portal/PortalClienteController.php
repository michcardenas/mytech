<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClienteBanner;
use App\Models\InternalProject;
use App\Models\ProjectPayment;
use Illuminate\Http\Request;

class PortalClienteController extends Controller
{
    use PortalAuth;

    public function showLogin(Request $request)
    {
        if ($request->session()->get('portal_cliente_id') || $request->session()->get('portal_cliente_phone')) {
            return redirect()->route('portal.cliente.dashboard');
        }

        return view('portal.login', [
            'role' => 'cliente',
            'titulo' => 'Portal de clientes',
            'subtitulo' => 'Consulta el estado de tus proyectos y descarga tus facturas',
            'icon' => 'fa-user',
            'color' => 'blue',
            'route_login' => route('portal.cliente.login'),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate(['telefono' => 'required|string|max:30']);

        if ($this->tooManyAttempts($request, 'cliente')) {
            return back()->withErrors(['telefono' => 'Demasiados intentos. Intenta nuevamente en 10 minutos.']);
        }

        $normalized = $this->normalizePhone($request->telefono);
        if (strlen($normalized) < 7) {
            $this->recordAttempt($request, 'cliente');

            return back()->withErrors(['telefono' => 'Número inválido.'])->withInput();
        }

        $client = Client::whereNotNull('telefono')->get()
            ->first(fn ($c) => $this->phonesMatch($c->telefono, $normalized));

        $matchProyecto = null;
        if (! $client) {
            $matchProyecto = InternalProject::whereNotNull('cliente_contacto')->get()
                ->first(fn ($p) => $this->phonesMatch($p->cliente_contacto, $normalized));
        }

        if (! $client && ! $matchProyecto) {
            $this->recordAttempt($request, 'cliente');

            return back()->withErrors(['telefono' => 'No encontramos un cliente con ese número.'])->withInput();
        }

        $this->clearAttempts($request, 'cliente');
        if ($client) {
            $request->session()->put('portal_cliente_id', $client->id);
            $request->session()->forget('portal_cliente_phone');
        } else {
            $request->session()->put('portal_cliente_phone', $normalized);
            $request->session()->forget('portal_cliente_id');
        }

        return redirect()->route('portal.cliente.dashboard');
    }

    public function dashboard(Request $request)
    {
        $clientId = $request->session()->get('portal_cliente_id');
        $phone = $request->session()->get('portal_cliente_phone');

        if (! $clientId && ! $phone) {
            return redirect()->route('portal.cliente.login.show');
        }

        $client = $clientId ? Client::find($clientId) : null;
        $nombreVisible = $client?->nombre ?: 'Cliente';

        $proyectos = InternalProject::query()
            ->when($client, fn ($q) => $q->where('client_id', $client->id))
            ->when(! $client && $phone, function ($q) use ($phone) {
                $q->whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(cliente_contacto, " ", ""), "-", ""), "+", ""), "(", "") LIKE ?', ['%'.$phone.'%']);
            })
            ->with([
                'payments' => fn ($q) => $q->orderByDesc('fecha'),
                'bolsaMovimientos' => fn ($q) => $q->orderByDesc('fecha'),
            ])
            ->withSum('payments as pagado', 'monto')
            ->orderByDesc('created_at')
            ->get();

        if ($client === null && $proyectos->isNotEmpty()) {
            $nombreVisible = $proyectos->first()->cliente_nombre ?: 'Cliente';
        }

        // Todos los pagos del cliente, aplanados y ordenados por fecha desc.
        $pagos = $proyectos->flatMap(fn ($p) => $p->payments->map(function ($pay) use ($p) {
            $pay->setRelation('project', $p);

            return $pay;
        }))->sortByDesc('fecha')->values();

        // Cuentas de cobro que el admin marcó visibles para el cliente.
        $cuentasCobro = \App\Models\CuentaCobro::whereIn('internal_project_id', $proyectos->pluck('id'))
            ->where('visible_cliente', true)
            ->with('project:id,nombre,moneda')
            ->orderByDesc('created_at')
            ->get();

        // Banners de oferta de servicios que el admin publicó para este cliente.
        $banners = ClienteBanner::vigentesPara($client?->id)->get();

        return view('portal.cliente-dashboard', [
            'client' => $client,
            'nombreVisible' => $nombreVisible,
            'proyectos' => $proyectos,
            'pagos' => $pagos,
            'cuentasCobro' => $cuentasCobro,
            'banners' => $banners,
        ]);
    }

    /**
     * Recibo de un pago para el cliente logueado.
     * Reutiliza la vista del admin (mismo membrete MYTECH SOLUTIONS S.A.S).
     */
    public function receipt(Request $request, ProjectPayment $payment)
    {
        $clientId = $request->session()->get('portal_cliente_id');
        $phone = $request->session()->get('portal_cliente_phone');
        if (! $clientId && ! $phone) {
            return redirect()->route('portal.cliente.login.show');
        }

        $project = $payment->project;
        abort_unless($project, 404);

        // Validar que el pago pertenezca a un proyecto del cliente logueado.
        $esDelCliente = ($clientId && $project->client_id === (int) $clientId)
            || ($phone && $this->phonesMatch($project->cliente_contacto ?? '', $phone));

        abort_unless($esDelCliente, 403);

        $project->load('client');
        $totalPagado = (float) $project->payments()->sum('monto');
        $saldo = max((float) $project->precio - $totalPagado, 0);

        return view('admin.internal-projects.receipt', [
            'project' => $project,
            'payment' => $payment,
            'totalPagado' => $totalPagado,
            'saldo' => $saldo,
            'backUrl' => route('portal.cliente.dashboard'),
        ]);
    }

    /** Cuenta de cobro publicada, visible para el cliente logueado. */
    public function cuentaCobro(Request $request, \App\Models\CuentaCobro $cuenta)
    {
        $clientId = $request->session()->get('portal_cliente_id');
        $phone = $request->session()->get('portal_cliente_phone');
        if (! $clientId && ! $phone) {
            return redirect()->route('portal.cliente.login.show');
        }

        abort_unless($cuenta->visible_cliente, 404);

        $project = $cuenta->project;
        abort_unless($project, 404);

        $esDelCliente = ($clientId && $project->client_id === (int) $clientId)
            || ($phone && $this->phonesMatch($project->cliente_contacto ?? '', $phone));
        abort_unless($esDelCliente, 403);

        $project->load('client');

        return view('admin.internal-projects.cuenta-cobro', [
            'project' => $project,
            'monto' => (float) $cuenta->monto,
            'periodo' => $cuenta->periodo ?? now()->startOfMonth(),
            'esRecurrente' => (bool) $project->es_recurrente,
            'tipoCobro' => $cuenta->tipo,
            'pctCobro' => $cuenta->tipo === 'porcentaje' ? (float) $cuenta->valor_param : null,
            'backUrl' => route('portal.cliente.dashboard'),
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['portal_cliente_id', 'portal_cliente_phone']);

        return redirect()->route('portal.cliente.login.show')->with('success', 'Sesión cerrada.');
    }
}
