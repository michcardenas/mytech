<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Meeting;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulingController extends Controller
{
    public function __construct(private GoogleCalendarService $google) {}

    /** Anfitrión: primer admin con calendario conectado. */
    private function host(): ?User
    {
        return User::role('admin')->whereNotNull('google_calendar_refresh_token')->orderBy('id')->first();
    }

    /** Disponibilidad del admin (JSON) para el modal de agendamiento. */
    public function availability()
    {
        $host = $this->host();

        if (! $host) {
            return response()->json([
                'connected' => false,
                'message'   => 'El administrador aún no ha conectado su calendario.',
                'days'      => [],
            ]);
        }

        try {
            $days = $this->google->availableSlots($host);
        } catch (\Throwable $e) {
            return response()->json([
                'connected' => true,
                'message'   => 'No se pudo leer la disponibilidad en este momento.',
                'days'      => [],
            ], 200);
        }

        return response()->json([
            'connected' => true,
            'host'      => $host->name,
            'days'      => $days,
        ]);
    }

    /** Reserva una reunión de cierre en el calendario del admin. */
    public function book(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $data = $request->validate([
            'scheduled_at' => 'required|date',
        ]);

        $host = $this->host();
        if (! $host) {
            return back()->with('error', 'El administrador no tiene el calendario conectado.');
        }

        $start = Carbon::parse($data['scheduled_at'], config('app.timezone'));
        $end   = $start->copy()->addMinutes(30);

        $meeting = Meeting::create([
            'lead_id'      => $lead->id,
            'user_id'      => Auth::id(),
            'host_user_id' => $host->id,
            'titulo'       => 'Cierre: ' . $lead->nombre,
            'tipo'         => 'cierre',
            'scheduled_at' => $start,
            'estado'       => 'agendada',
        ]);

        $aviso = null;
        try {
            $event = $this->google->createEvent($host, [
                'summary'     => 'Cierre: ' . $lead->nombre . ($lead->empresa ? ' (' . $lead->empresa . ')' : ''),
                'description' => "Reunión de cierre.\nLead: {$lead->nombre}\nComercial: " . Auth::user()->name
                    . ($lead->fuente_url ? "\nOrigen: {$lead->fuente_url}" : ''),
                'start'       => $start,
                'end'         => $end,
                'attendees'   => array_filter([Auth::user()->email, $lead->email]),
            ]);
            $meeting->update([
                'google_event_id' => $event['id'],
                'meet_link'       => $event['meet'],
            ]);
        } catch (\Throwable $e) {
            $aviso = 'La reunión se guardó, pero no se pudo crear el evento en Google Calendar: ' . $e->getMessage();
        }

        if ($lead->estado === Lead::ESTADO_ABIERTO) {
            $lead->update(['etapa' => 'cierre']);
        }

        LeadActivity::create([
            'lead_id' => $lead->id, 'user_id' => Auth::id(), 'tipo' => 'reunion',
            'descripcion' => 'Reunión de cierre agendada con ' . $host->name . ' para ' . $start->format('d/m/Y H:i')
                . ($meeting->meet_link ? ' (Meet generado)' : ''),
        ]);

        return back()->with($aviso ? 'error' : 'success', $aviso ?: 'Reunión de cierre agendada. Se creó el evento con Google Meet y te llegará la invitación.');
    }
}
