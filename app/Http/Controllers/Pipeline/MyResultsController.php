<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyResultsController extends Controller
{
    /** Resultados de la comercial: cierres y comisiones por mes (solo lectura). */
    public function index(Request $request)
    {
        $user = Auth::user();
        $usdCop = (float) config('services.usd_cop', env('USD_COP_RATE', 4000));
        $toCop = fn ($p) => $p->moneda === 'USD' ? (float) $p->precio * $usdCop : (float) $p->precio;

        $inicioMesActual = now()->startOfMonth();
        $finMesActual = now()->endOfMonth();
        $inicioMesAnterior = now()->subMonth()->startOfMonth();
        $finMesAnterior = now()->subMonth()->endOfMonth();

        $todosProyectos = InternalProject::where('comercial_user_id', $user->id)
            ->with(['gestionPayments', 'payments'])
            ->orderByDesc('created_at')->get();

        $mesActual = $todosProyectos->filter(fn ($p) => $p->created_at->between($inicioMesActual, $finMesActual));
        $mesAnterior = $todosProyectos->filter(fn ($p) => $p->created_at->between($inicioMesAnterior, $finMesAnterior));

        $cierresMesActual = [
            'count' => $mesActual->count(),
            'valor_cop' => $mesActual->sum($toCop),
        ];
        $cierresMesAnterior = [
            'count' => $mesAnterior->count(),
            'valor_cop' => $mesAnterior->sum($toCop),
        ];

        $comisionMesActual = $mesActual->sum(fn ($p) => $p->comision_calculada);
        $comisionMesAnterior = $mesAnterior->sum(fn ($p) => $p->comision_calculada);

        $mesFiltro = in_array($request->get('mes'), ['actual', 'anterior'], true) ? $request->get('mes') : null;
        $proyectos = match ($mesFiltro) {
            'actual' => $mesActual->values(),
            'anterior' => $mesAnterior->values(),
            default => $todosProyectos,
        };

        $ganados = Lead::where('user_id', $user->id)->where('estado', Lead::ESTADO_GANADO)->count();
        $abiertos = Lead::where('user_id', $user->id)->abierto()->count();
        $pipeline = Lead::where('user_id', $user->id)->abierto()->sum('valor_estimado');

        return view('pipeline.my-results', [
            'proyectos' => $proyectos,
            'cierresMesActual' => $cierresMesActual,
            'cierresMesAnterior' => $cierresMesAnterior,
            'comisionMesActual' => $comisionMesActual,
            'comisionMesAnterior' => $comisionMesAnterior,
            'mesFiltro' => $mesFiltro,
            'ganados' => $ganados,
            'abiertos' => $abiertos,
            'pipeline' => $pipeline,
            'pageTitle' => 'Mis resultados',
        ]);
    }
}
