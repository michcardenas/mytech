<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\InternalProject;
use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversionController extends Controller
{
    /** Convierte un lead ganado en un Proyecto interno (solo admin). */
    public function convert(Request $request, Lead $lead)
    {
        // Solo admin (la policy 'convert' devuelve false; before() deja pasar al admin)
        $this->authorize('convert', $lead);

        if ($lead->internal_project_id) {
            return back()->with('error', 'Este lead ya fue convertido en proyecto.');
        }

        $data = $request->validate([
            'nombre'         => 'required|string|max:255',
            'precio'         => 'required|numeric|min:0',
            'moneda'         => 'required|in:COP,USD',
            'comision_tipo'  => 'required|in:porcentaje,monto',
            'comision_valor' => 'required|numeric|min:0',
            'fecha_inicio'   => 'nullable|date',
            'estado'         => 'required|in:cotizado,en_progreso,pausado,completado,cancelado',
        ]);

        $project = DB::transaction(function () use ($lead, $data) {
            $project = InternalProject::create([
                'nombre'            => $data['nombre'],
                'cliente_nombre'    => $lead->empresa ?: $lead->nombre,
                'cliente_contacto'  => $lead->telefono,
                'cliente_email'     => $lead->email,
                'fuente'            => $lead->fuente === 'workana' ? 'workana' : 'directo',
                'fuente_url'        => $lead->fuente_url,
                'precio'            => $data['precio'],
                'moneda'            => $data['moneda'],
                'comision_tipo'     => $data['comision_tipo'],
                'comision_valor'    => $data['comision_valor'],
                'comercial_user_id' => $lead->user_id,
                'lead_id'           => $lead->id,
                'estado'            => $data['estado'],
                'fecha_inicio'      => $data['fecha_inicio'] ?? null,
                'descripcion'       => trim(($lead->descripcion ? $lead->descripcion . "\n\n" : '') . 'Origen: ' . $lead->fuente_label . ($lead->fuente_url ? ' — ' . $lead->fuente_url : '')),
            ]);

            $lead->update([
                'internal_project_id' => $project->id,
                'etapa'               => 'ganado',
                'estado'              => Lead::ESTADO_GANADO,
                'won_at'              => $lead->won_at ?? now(),
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id, 'user_id' => Auth::id(), 'tipo' => 'sistema',
                'descripcion' => 'Convertido en Proyecto interno #' . $project->id,
            ]);

            return $project;
        });

        return redirect()->route('admin.internal-projects.show', $project)
            ->with('success', 'Lead convertido en proyecto correctamente.');
    }

    /** Datos por defecto para el formulario de conversión. */
    public static function defaults(Lead $lead): array
    {
        $setting = CommissionSetting::actual();

        return [
            'nombre'         => $lead->empresa ? $lead->empresa . ' — ' . $lead->nombre : $lead->nombre,
            'precio'         => $lead->valor_estimado,
            'moneda'         => $lead->moneda,
            'comision_tipo'  => $setting->tipo,
            'comision_valor' => $setting->valor,
        ];
    }
}
