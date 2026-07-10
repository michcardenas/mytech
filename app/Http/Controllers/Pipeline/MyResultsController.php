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
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $toCop = fn ($p) => $p->moneda === 'USD' ? (float) $p->precio * $usdCop : (float) $p->precio;

        $proyectos = InternalProject::where('comercial_user_id', $user->id)
            ->with('gestionPayments')->orderByDesc('created_at')->get();

        $valorCerrado = $proyectos->sum('precio');
        $comisionTotal = $proyectos->sum(fn ($p) => $p->comision_calculada);
        $comisionPagada = $proyectos->sum(fn ($p) => $p->total_pagado_gestion);
        $comisionPendiente = max($comisionTotal - $comisionPagada, 0);

        $inicioMesActual = now()->startOfMonth();
        $finMesActual = now()->endOfMonth();
        $inicioMesAnterior = now()->subMonth()->startOfMonth();
        $finMesAnterior = now()->subMonth()->endOfMonth();

        $mesActual = $proyectos->filter(fn ($p) => $p->created_at->between($inicioMesActual, $finMesActual));
        $mesAnterior = $proyectos->filter(fn ($p) => $p->created_at->between($inicioMesAnterior, $finMesAnterior));

        $cierresMesActual = [
            'count' => $mesActual->count(),
            'valor_cop' => $mesActual->sum($toCop),
        ];
        $cierresMesAnterior = [
            'count' => $mesAnterior->count(),
            'valor_cop' => $mesAnterior->sum($toCop),
        ];

        $ganados = Lead::where('user_id', $user->id)->where('estado', Lead::ESTADO_GANADO)->count();
        $abiertos = Lead::where('user_id', $user->id)->abierto()->count();
        $pipeline = Lead::where('user_id', $user->id)->abierto()->sum('valor_estimado');

        return view('pipeline.my-results', [
            'proyectos' => $proyectos,
            'valorCerrado' => $valorCerrado,
            'comisionTotal' => $comisionTotal,
            'comisionPagada' => $comisionPagada,
            'comisionPendiente' => $comisionPendiente,
            'cierresMesActual' => $cierresMesActual,
            'cierresMesAnterior' => $cierresMesAnterior,
            'ganados' => $ganados,
            'abiertos' => $abiertos,
            'pipeline' => $pipeline,
            'pageTitle' => 'Mis resultados',
        ]);
    }
}
