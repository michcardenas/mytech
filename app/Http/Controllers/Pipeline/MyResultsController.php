<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class MyResultsController extends Controller
{
    /** Resultados de la comercial: total cerrado + su comisión (solo lectura). */
    public function index()
    {
        $user = Auth::user();

        $proyectos = InternalProject::where('comercial_user_id', $user->id)
            ->with('gestionPayments')->orderByDesc('created_at')->get();

        $valorCerrado     = $proyectos->sum('precio');
        $comisionTotal    = $proyectos->sum(fn ($p) => $p->comision_calculada);
        $comisionPagada   = $proyectos->sum(fn ($p) => $p->total_pagado_gestion);
        $comisionPendiente = max($comisionTotal - $comisionPagada, 0);

        $ganados  = Lead::where('user_id', $user->id)->where('estado', Lead::ESTADO_GANADO)->count();
        $abiertos = Lead::where('user_id', $user->id)->abierto()->count();
        $pipeline = Lead::where('user_id', $user->id)->abierto()->sum('valor_estimado');

        return view('pipeline.my-results', [
            'proyectos'         => $proyectos,
            'valorCerrado'      => $valorCerrado,
            'comisionTotal'     => $comisionTotal,
            'comisionPagada'    => $comisionPagada,
            'comisionPendiente' => $comisionPendiente,
            'ganados'           => $ganados,
            'abiertos'          => $abiertos,
            'pipeline'          => $pipeline,
            'pageTitle'         => 'Mis resultados',
        ]);
    }
}
