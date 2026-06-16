<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\Lead;
use App\Models\Meeting;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;

class CommercialDashboardController extends Controller
{
    /** Dashboard comercial (solo admin): métricas y desempeño. */
    public function index(Request $request)
    {
        $comerciales     = User::role('comercial')->orderBy('name')->get();
        $filtroComercial = $request->integer('comercial') ?: null;

        $leadFilter = fn ($q) => $filtroComercial ? $q->where('user_id', $filtroComercial) : $q;

        // Conteo por etapa (tablero)
        $porEtapa = [];
        foreach (Lead::ETAPAS as $key => $meta) {
            $porEtapa[$key] = $leadFilter(Lead::query()->where('etapa', $key)->enTablero())->count();
        }

        $totalLeads   = $leadFilter(Lead::query())->count();
        $ganados      = $leadFilter(Lead::query()->where('estado', Lead::ESTADO_GANADO))->count();
        $perdidos     = $leadFilter(Lead::query()->where('estado', Lead::ESTADO_PERDIDO))->count();
        $abiertos     = $leadFilter(Lead::query()->abierto())->count();
        $cerrados     = $ganados + $perdidos;
        $winRate      = $cerrados > 0 ? round($ganados / $cerrados * 100) : 0;
        $valorPipeline = $leadFilter(Lead::query()->abierto())->sum('valor_estimado');
        $vencidos      = $leadFilter(Lead::query()->abierto()->whereNotNull('proxima_accion_at')
            ->where('proxima_accion_at', '<', now()))->count();

        $propuestasEnviadas = $filtroComercial
            ? Proposal::where('user_id', $filtroComercial)->count()
            : Proposal::count();
        $reunionesAgendadas = $filtroComercial
            ? Meeting::where('user_id', $filtroComercial)->where('estado', 'agendada')->count()
            : Meeting::where('estado', 'agendada')->count();

        // Valor cerrado (proyectos convertidos) y comisiones
        $proyectos = InternalProject::query()
            ->whereNotNull('comercial_user_id')
            ->when($filtroComercial, fn ($q) => $q->where('comercial_user_id', $filtroComercial))
            ->with('gestionPayments')->get();

        $valorCerrado     = $proyectos->sum('precio');
        $comisionTotal    = $proyectos->sum(fn ($p) => $p->comision_calculada);
        $comisionPagada   = $proyectos->sum(fn ($p) => $p->total_pagado_gestion);
        $comisionPendiente = max($comisionTotal - $comisionPagada, 0);

        // Desempeño por comercial (tabla)
        $ranking = $comerciales->map(function ($c) {
            $proyectos = InternalProject::where('comercial_user_id', $c->id)->with('gestionPayments')->get();
            return [
                'comercial'  => $c,
                'leads'      => Lead::where('user_id', $c->id)->count(),
                'ganados'    => Lead::where('user_id', $c->id)->where('estado', Lead::ESTADO_GANADO)->count(),
                'pipeline'   => Lead::where('user_id', $c->id)->abierto()->sum('valor_estimado'),
                'cerrado'    => $proyectos->sum('precio'),
                'comision'   => $proyectos->sum(fn ($p) => $p->comision_calculada),
            ];
        });

        return view('pipeline.dashboard', [
            'etapas'             => Lead::ETAPAS,
            'porEtapa'           => $porEtapa,
            'comerciales'        => $comerciales,
            'filtroComercial'    => $filtroComercial,
            'totalLeads'         => $totalLeads,
            'ganados'            => $ganados,
            'perdidos'           => $perdidos,
            'abiertos'           => $abiertos,
            'winRate'            => $winRate,
            'valorPipeline'      => $valorPipeline,
            'vencidos'           => $vencidos,
            'propuestasEnviadas' => $propuestasEnviadas,
            'reunionesAgendadas' => $reunionesAgendadas,
            'valorCerrado'       => $valorCerrado,
            'comisionTotal'      => $comisionTotal,
            'comisionPagada'     => $comisionPagada,
            'comisionPendiente'  => $comisionPendiente,
            'ranking'            => $ranking,
            'pageTitle'          => 'Dashboard comercial',
        ]);
    }
}
