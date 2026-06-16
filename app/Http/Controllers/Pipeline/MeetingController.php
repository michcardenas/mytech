<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Meeting;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function __construct(private GoogleCalendarService $google) {}

    /** Borra el evento de Google asociado (si lo hay) en el calendario del anfitrión. */
    private function removeGoogleEvent(Meeting $meeting): void
    {
        if (! $meeting->google_event_id || ! $meeting->host_user_id) {
            return;
        }
        $host = User::find($meeting->host_user_id);
        if ($host && $host->hasGoogleCalendar()) {
            try {
                $this->google->deleteEvent($host, $meeting->google_event_id);
            } catch (\Throwable $e) {
                // silencioso: no bloquear la cancelación por un fallo de Google
            }
        }
    }

    /** Agenda / lista de reuniones (scoped). */
    public function index(Request $request)
    {
        $user = Auth::user();

        $proximas = Meeting::query()->visibleTo($user)
            ->with('lead')
            ->where('estado', 'agendada')
            ->where('scheduled_at', '>=', now()->startOfDay())
            ->orderBy('scheduled_at')->get();

        $pasadas = Meeting::query()->visibleTo($user)
            ->with('lead')
            ->where(fn ($q) => $q->where('estado', '!=', 'agendada')->orWhere('scheduled_at', '<', now()->startOfDay()))
            ->orderByDesc('scheduled_at')->limit(50)->get();

        return view('pipeline.meetings.index', [
            'proximas'       => $proximas,
            'pasadas'        => $pasadas,
            'estadosReunion' => Meeting::ESTADOS,
            'pageTitle'      => 'Reuniones',
        ]);
    }

    public function store(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $data = $request->validate([
            'titulo'       => 'nullable|string|max:255',
            'tipo'         => 'required|string|in:' . implode(',', array_keys(Meeting::TIPOS)),
            'scheduled_at' => 'required|date',
            'notas'        => 'nullable|string|max:2000',
        ]);
        $data['lead_id'] = $lead->id;
        $data['user_id'] = Auth::id();
        $data['estado']  = 'agendada';

        $meeting = Meeting::create($data);

        LeadActivity::create([
            'lead_id' => $lead->id, 'user_id' => Auth::id(), 'tipo' => 'reunion',
            'descripcion' => 'Reunión de ' . $meeting->tipo_label . ' agendada para ' . $meeting->scheduled_at->format('d/m/Y H:i'),
        ]);

        // Si es reunión de cierre, mover etapa a "cierre"
        if ($meeting->tipo === 'cierre' && $lead->estado === Lead::ESTADO_ABIERTO) {
            $lead->update(['etapa' => 'cierre']);
        } elseif (in_array($lead->etapa, ['prospecto', 'contactado', 'propuesta'], true) && $lead->estado === Lead::ESTADO_ABIERTO) {
            $lead->update(['etapa' => 'reunion']);
        }

        return back()->with('success', 'Reunión agendada.');
    }

    public function update(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $data = $request->validate([
            'titulo'       => 'nullable|string|max:255',
            'tipo'         => 'required|string|in:' . implode(',', array_keys(Meeting::TIPOS)),
            'scheduled_at' => 'required|date',
            'estado'       => 'required|string|in:' . implode(',', array_keys(Meeting::ESTADOS)),
            'resultado'    => 'nullable|string|max:2000',
            'notas'        => 'nullable|string|max:2000',
        ]);

        $meeting->update($data);

        // Si se cancela, quitar el evento del calendario del admin
        if (in_array($data['estado'], ['cancelada', 'no_show'], true)) {
            $this->removeGoogleEvent($meeting);
        }

        return back()->with('success', 'Reunión actualizada.');
    }

    public function destroy(Meeting $meeting)
    {
        $this->authorize('delete', $meeting);
        $this->removeGoogleEvent($meeting);
        $meeting->delete();

        return back()->with('success', 'Reunión eliminada.');
    }
}
