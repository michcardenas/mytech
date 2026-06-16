<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PipelineController extends Controller
{
    /** Tablero Kanban del pipeline. */
    public function index(Request $request)
    {
        $user    = Auth::user();
        $isAdmin = $user->hasRole('admin');

        $comerciales    = $isAdmin ? User::role('comercial')->orderBy('name')->get() : collect();
        $filtroComercial = $isAdmin ? $request->integer('comercial') : null;

        $query = Lead::query()->enTablero()->with('user');

        if (! $isAdmin) {
            $query->where('user_id', $user->id);
        } elseif ($filtroComercial) {
            $query->where('user_id', $filtroComercial);
        }

        $leadsPorEtapa = $query->orderBy('orden')->orderByDesc('updated_at')->get()->groupBy('etapa');

        // Métricas rápidas del encabezado
        $abiertos      = Lead::query()->abierto()->visibleTo($user)
            ->when($filtroComercial, fn ($q) => $q->where('user_id', $filtroComercial));
        $totalAbiertos = (clone $abiertos)->count();
        $valorPipeline = (clone $abiertos)->sum('valor_estimado');
        $vencidos      = (clone $abiertos)->whereNotNull('proxima_accion_at')
            ->where('proxima_accion_at', '<', now())->count();
        $reunionesProximas = Meeting::query()->visibleTo($user)->proximas()->count();

        $perdidosCount = Lead::query()->where('estado', Lead::ESTADO_PERDIDO)->visibleTo($user)
            ->when($filtroComercial, fn ($q) => $q->where('user_id', $filtroComercial))->count();

        return view('pipeline.kanban', [
            'etapas'            => Lead::ETAPAS,
            'leadsPorEtapa'     => $leadsPorEtapa,
            'fuentes'           => Lead::FUENTES,
            'isAdmin'           => $isAdmin,
            'comerciales'       => $comerciales,
            'filtroComercial'   => $filtroComercial,
            'totalAbiertos'     => $totalAbiertos,
            'valorPipeline'     => $valorPipeline,
            'vencidos'          => $vencidos,
            'reunionesProximas' => $reunionesProximas,
            'perdidosCount'     => $perdidosCount,
            'pageTitle'         => 'Pipeline',
        ]);
    }

    /** Mover una tarjeta de etapa (drag & drop). */
    public function updateStage(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $data = $request->validate([
            'etapa' => 'required|string|in:' . implode(',', array_keys(Lead::ETAPAS)),
            'ids'   => 'sometimes|array',
            'ids.*' => 'integer',
        ]);

        $etapaAnterior = $lead->etapa;
        $lead->etapa = $data['etapa'];

        if ($data['etapa'] === 'ganado') {
            $lead->estado = Lead::ESTADO_GANADO;
            $lead->won_at = $lead->won_at ?? now();
        } elseif ($lead->estado === Lead::ESTADO_GANADO) {
            // Se sacó de "ganado": vuelve a abierto
            $lead->estado = Lead::ESTADO_ABIERTO;
            $lead->won_at = null;
        }
        $lead->save();

        // Reordenar la columna destino (solo leads visibles para el usuario)
        if (! empty($data['ids'])) {
            foreach ($data['ids'] as $orden => $id) {
                Lead::query()->visibleTo(Auth::user())->where('id', $id)->update(['orden' => $orden]);
            }
        }

        if ($etapaAnterior !== $lead->etapa) {
            LeadActivity::create([
                'lead_id'     => $lead->id,
                'user_id'     => Auth::id(),
                'tipo'        => 'etapa',
                'descripcion' => 'Movido a ' . $lead->etapa_label,
            ]);
        }

        return response()->json(['ok' => true, 'etapa' => $lead->etapa, 'estado' => $lead->estado]);
    }
}
