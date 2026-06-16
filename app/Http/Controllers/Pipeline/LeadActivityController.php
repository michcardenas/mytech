<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadActivityController extends Controller
{
    /** Registra una actividad en la bitácora y, opcionalmente, fija la próxima acción. */
    public function store(Request $request, Lead $lead)
    {
        $this->authorize('update', $lead);

        $data = $request->validate([
            'tipo'                => 'required|string|in:' . implode(',', array_keys(LeadActivity::TIPOS)),
            'descripcion'         => 'required|string|max:2000',
            'proxima_accion_at'   => 'nullable|date',
            'proxima_accion_nota' => 'nullable|string|max:255',
        ]);

        LeadActivity::create([
            'lead_id'     => $lead->id,
            'user_id'     => Auth::id(),
            'tipo'        => $data['tipo'],
            'descripcion' => $data['descripcion'],
        ]);

        // Fijar la próxima acción de una vez (motor del seguimiento)
        if ($request->filled('proxima_accion_at')) {
            $lead->update([
                'proxima_accion_at'   => $data['proxima_accion_at'],
                'proxima_accion_nota' => $data['proxima_accion_nota'] ?? null,
            ]);
        } else {
            $lead->touch();
        }

        return back()->with('success', 'Actividad registrada.');
    }
}
