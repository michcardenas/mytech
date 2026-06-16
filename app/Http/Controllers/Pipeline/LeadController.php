<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /** Crear un nuevo lead (captura rápida). */
    public function store(Request $request)
    {
        $this->authorize('create', Lead::class);

        $data = $this->validateLead($request);

        $user = Auth::user();
        // El comercial siempre es dueño de lo suyo; el admin puede asignar.
        if ($user->hasRole('admin') && $request->filled('user_id')) {
            $data['user_id'] = (int) $request->input('user_id');
        } else {
            $data['user_id'] = $user->id;
        }

        $data['etapa']  = $data['etapa'] ?? 'prospecto';
        $data['estado'] = Lead::ESTADO_ABIERTO;

        $lead = Lead::create($data);

        LeadActivity::create([
            'lead_id'     => $lead->id,
            'user_id'     => $user->id,
            'tipo'        => 'sistema',
            'descripcion' => 'Lead creado desde ' . $lead->fuente_label,
        ]);

        return redirect()->route('pipeline.leads.show', $lead)
            ->with('success', 'Lead creado correctamente.');
    }

    /** Detalle del lead con bitácora, propuestas y reuniones. */
    public function show(Lead $lead)
    {
        $this->authorize('view', $lead);

        $lead->load(['activities.user', 'proposals.user', 'meetings.user', 'user', 'internalProject']);

        return view('pipeline.leads.show', [
            'lead'             => $lead,
            'etapas'           => Lead::ETAPAS,
            'fuentes'          => Lead::FUENTES,
            'tiposActividad'   => \App\Models\LeadActivity::TIPOS,
            'estadosPropuesta' => \App\Models\Proposal::ESTADOS,
            'tiposReunion'     => \App\Models\Meeting::TIPOS,
            'estadosReunion'   => \App\Models\Meeting::ESTADOS,
            'isAdmin'          => Auth::user()->hasRole('admin'),
            'pageTitle'        => $lead->nombre,
        ]);
    }

    /** Actualizar datos del lead. */
    public function update(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $data = $this->validateLead($request);
        // No permitimos reasignar dueño desde aquí (salvo admin explícito)
        if (Auth::user()->hasRole('admin') && $request->filled('user_id')) {
            $data['user_id'] = (int) $request->input('user_id');
        }

        $lead->update($data);

        return back()->with('success', 'Lead actualizado.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete', $lead);
        $lead->delete();

        return redirect()->route('pipeline.index')->with('success', 'Lead eliminado.');
    }

    /** Marcar como GANADO (solo admin — es quien hace el cierre). */
    public function marcarGanado(Lead $lead)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403, 'Solo el administrador puede marcar un lead como ganado.');
        $this->authorize('update', $lead);

        $lead->update([
            'etapa'  => 'ganado',
            'estado' => Lead::ESTADO_GANADO,
            'won_at' => now(),
            'lost_at' => null,
            'motivo_perdido' => null,
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id, 'user_id' => Auth::id(),
            'tipo' => 'sistema', 'descripcion' => 'Lead marcado como GANADO 🎉',
        ]);

        return back()->with('success', '¡Lead marcado como ganado! Ya puede convertirse en proyecto.');
    }

    /** Marcar como PERDIDO con motivo. */
    public function marcarPerdido(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $request->validate(['motivo_perdido' => 'nullable|string|max:255']);

        $lead->update([
            'estado'         => Lead::ESTADO_PERDIDO,
            'lost_at'        => now(),
            'won_at'         => null,
            'motivo_perdido' => $request->input('motivo_perdido'),
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id, 'user_id' => Auth::id(),
            'tipo' => 'sistema',
            'descripcion' => 'Lead marcado como PERDIDO' . ($request->filled('motivo_perdido') ? ': ' . $request->input('motivo_perdido') : ''),
        ]);

        return back()->with('success', 'Lead marcado como perdido.');
    }

    /** Vista de pendientes: vencidos / hoy / esta semana. */
    public function pendientes()
    {
        $user = Auth::user();

        $base = Lead::query()->abierto()->visibleTo($user)
            ->whereNotNull('proxima_accion_at')->with('user');

        $vencidos = (clone $base)->where('proxima_accion_at', '<', now()->startOfDay())
            ->orderBy('proxima_accion_at')->get();
        $hoy = (clone $base)->whereBetween('proxima_accion_at', [now()->startOfDay(), now()->endOfDay()])
            ->orderBy('proxima_accion_at')->get();
        $semana = (clone $base)->whereBetween('proxima_accion_at', [now()->endOfDay(), now()->endOfWeek()->endOfDay()])
            ->orderBy('proxima_accion_at')->get();
        $sinFecha = Lead::query()->abierto()->visibleTo($user)
            ->whereNull('proxima_accion_at')->with('user')->orderByDesc('updated_at')->get();

        return view('pipeline.pendientes', compact('vencidos', 'hoy', 'semana', 'sinFecha') + [
            'pageTitle' => 'Pendientes',
        ]);
    }

    /** Listado de leads perdidos (admin: todos / filtrable; comercial: los suyos). */
    public function perdidos(Request $request)
    {
        $user    = Auth::user();
        $isAdmin = $user->hasRole('admin');

        $comerciales = $isAdmin ? User::role('comercial')->orderBy('name')->get() : collect();
        $filtro      = $isAdmin ? $request->integer('comercial') : null;

        $leads = Lead::query()
            ->where('estado', Lead::ESTADO_PERDIDO)
            ->when(! $isAdmin, fn ($q) => $q->where('user_id', $user->id))
            ->when($filtro, fn ($q) => $q->where('user_id', $filtro))
            ->with('user')
            ->orderByDesc('lost_at')->get();

        $valorPerdido = $leads->sum('valor_estimado');

        return view('pipeline.perdidos', [
            'leads'        => $leads,
            'comerciales'  => $comerciales,
            'filtro'       => $filtro,
            'isAdmin'      => $isAdmin,
            'valorPerdido' => $valorPerdido,
            'pageTitle'    => 'Perdidos',
        ]);
    }

    /** Reglas de validación compartidas. */
    private function validateLead(Request $request): array
    {
        return $request->validate([
            'nombre'              => 'required|string|max:255',
            'empresa'             => 'nullable|string|max:255',
            'fuente'              => 'required|string|in:' . implode(',', array_keys(Lead::FUENTES)),
            'fuente_url'          => 'nullable|url|max:500',
            'descripcion'         => 'nullable|string|max:2000',
            'email'               => 'nullable|email|max:255',
            'telefono'            => 'nullable|string|max:50',
            'valor_estimado'      => 'nullable|numeric|min:0',
            'moneda'              => 'required|in:COP,USD',
            'etapa'               => 'nullable|string|in:' . implode(',', array_keys(Lead::ETAPAS)),
            'proxima_accion_at'   => 'nullable|date',
            'proxima_accion_nota' => 'nullable|string|max:255',
        ]);
    }
}
