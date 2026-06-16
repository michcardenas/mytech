<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\InternalProject;
use App\Models\User;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /** Config de comisiones + listado de comisiones por proyecto (solo admin). */
    public function index(Request $request)
    {
        $setting         = CommissionSetting::actual();
        $comerciales     = User::role('comercial')->orderBy('name')->get();
        $filtroComercial = $request->integer('comercial') ?: null;

        $proyectos = InternalProject::query()
            ->whereNotNull('comercial_user_id')
            ->when($filtroComercial, fn ($q) => $q->where('comercial_user_id', $filtroComercial))
            ->with(['comercial', 'gestionPayments'])
            ->orderByDesc('created_at')->get();

        $totalComision  = $proyectos->sum(fn ($p) => $p->comision_calculada);
        $totalPagado    = $proyectos->sum(fn ($p) => $p->total_pagado_gestion);
        $totalPendiente = max($totalComision - $totalPagado, 0);

        return view('pipeline.commissions', [
            'setting'        => $setting,
            'proyectos'      => $proyectos,
            'comerciales'    => $comerciales,
            'filtroComercial' => $filtroComercial,
            'totalComision'  => $totalComision,
            'totalPagado'    => $totalPagado,
            'totalPendiente' => $totalPendiente,
            'pageTitle'      => 'Comisiones',
        ]);
    }

    /** Guardar la tasa de comisión por defecto. */
    public function update(Request $request)
    {
        $data = $request->validate([
            'tipo'   => 'required|in:porcentaje,monto',
            'valor'  => 'required|numeric|min:0',
            'moneda' => 'required|in:COP,USD',
            'notas'  => 'nullable|string|max:500',
        ]);

        $setting = CommissionSetting::actual();
        $setting->update($data + ['activo' => true]);

        return back()->with('success', 'Configuración de comisión actualizada.');
    }
}
