<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $data = $this->validateProposal($request);
        $data['lead_id'] = $lead->id;
        $data['user_id'] = Auth::id();

        $proposal = Proposal::create($data);

        LeadActivity::create([
            'lead_id' => $lead->id, 'user_id' => Auth::id(), 'tipo' => 'propuesta',
            'descripcion' => 'Propuesta ' . $proposal->estado_label . ' por ' . $proposal->monto_formateado,
        ]);

        // Avanzar la etapa si aún está atrás
        if (in_array($lead->etapa, ['prospecto', 'contactado'], true) && $lead->estado === Lead::ESTADO_ABIERTO) {
            $lead->update(['etapa' => 'propuesta']);
        }

        return back()->with('success', 'Propuesta registrada.');
    }

    public function update(Request $request, Proposal $proposal)
    {
        $this->authorize('update', $proposal);

        $proposal->update($this->validateProposal($request));

        return back()->with('success', 'Propuesta actualizada.');
    }

    public function destroy(Proposal $proposal)
    {
        $this->authorize('delete', $proposal);
        $proposal->delete();

        return back()->with('success', 'Propuesta eliminada.');
    }

    private function validateProposal(Request $request): array
    {
        return $request->validate([
            'titulo'     => 'nullable|string|max:255',
            'monto'      => 'nullable|numeric|min:0',
            'moneda'     => 'required|in:COP,USD',
            'estado'     => 'required|string|in:' . implode(',', array_keys(Proposal::ESTADOS)),
            'enviada_at' => 'nullable|date',
            'url'        => 'nullable|url|max:500',
            'notas'      => 'nullable|string|max:2000',
        ]);
    }
}
