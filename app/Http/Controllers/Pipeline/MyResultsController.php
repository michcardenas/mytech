<?php

namespace App\Http\Controllers\Pipeline;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\Lead;
use App\Models\LiquidacionPago;
use App\Models\Vendedor;
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

        // Cuenta como "mío" cualquier proyecto donde soy comercial_user_id (venido del pipeline)
        // o el vendedor de gestión coincide con mi email (asignado desde el form del proyecto).
        $vendedorIds = $user->email
            ? Vendedor::where('email', $user->email)->pluck('id')->all()
            : [];

        $todosProyectos = InternalProject::query()
            ->where(function ($q) use ($user, $vendedorIds) {
                $q->where('comercial_user_id', $user->id);
                if (! empty($vendedorIds)) {
                    $q->orWhereIn('vendedor_id', $vendedorIds);
                }
            })
            ->with(['gestionPayments', 'payments'])
            ->orderByRaw('COALESCE(fecha_inicio, created_at) DESC')->get();

        // Fecha de "cierre" = fecha_inicio del proyecto; fallback a created_at si es null.
        $fechaCierre = fn ($p) => $p->fecha_inicio ?? $p->created_at;

        // === Estado de pago vía liquidación (ciclo 20 a 20) ===
        // Cuando el admin registra el pago de la liquidación del ciclo, todas las
        // comisiones de proyectos cerrados dentro de ese ciclo cuentan como pagadas.
        $eurUsd = (float) config('services.eur_usd', env('EUR_USD_RATE', 1.17));
        $comisionCop = function ($p) use ($usdCop, $eurUsd): float {
            $c = (float) $p->comision_calculada;

            return match ($p->moneda) {
                'USD' => $c * $usdCop,
                'EUR' => $c * $eurUsd * $usdCop,
                default => $c,
            };
        };
        $cicloStart = function (\Carbon\Carbon $fecha): string {
            return ($fecha->day >= 20 ? $fecha->copy()->day(20) : $fecha->copy()->subMonthNoOverflow()->day(20))->toDateString();
        };

        if (! empty($vendedorIds)) {
            $pagosLiq = LiquidacionPago::whereIn('vendedor_id', $vendedorIds)->get()
                ->groupBy(fn ($pg) => $pg->periodo->toDateString());

            $sueldoCop = 0.0;
            $vend = Vendedor::find($vendedorIds[0]);
            if ($vend && $vend->sueldo_basico) {
                $sueldoCop = match ($vend->sueldo_moneda ?? 'COP') {
                    'USD' => (float) $vend->sueldo_basico * $usdCop,
                    'EUR' => (float) $vend->sueldo_basico * $eurUsd * $usdCop,
                    default => (float) $vend->sueldo_basico,
                };
            }

            $porCiclo = $todosProyectos->groupBy(fn ($p) => $cicloStart($fechaCierre($p)));
            foreach ($porCiclo as $inicio => $proyectosCiclo) {
                $totalCiclo = $sueldoCop + $proyectosCiclo->sum($comisionCop);
                $pagadoCiclo = (float) ($pagosLiq->get($inicio)?->sum('monto') ?? 0);
                $estado = $pagadoCiclo + 1 >= $totalCiclo && $pagadoCiclo > 0
                    ? 'pagada'
                    : ($pagadoCiclo > 0 ? 'parcial' : null);
                foreach ($proyectosCiclo as $p) {
                    $p->estado_liquidacion = $estado;
                }
            }
        }

        $mesActual = $todosProyectos->filter(fn ($p) => $fechaCierre($p)->between($inicioMesActual, $finMesActual));
        $mesAnterior = $todosProyectos->filter(fn ($p) => $fechaCierre($p)->between($inicioMesAnterior, $finMesAnterior));

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

        // Liquidaciones pagadas al comercial (con comprobante y link al documento)
        $liquidaciones = empty($vendedorIds)
            ? collect()
            : LiquidacionPago::whereIn('vendedor_id', $vendedorIds)
                ->orderByDesc('periodo')
                ->orderByDesc('fecha_pago')
                ->get()
                ->map(function ($pg) {
                    $pg->ciclo_fin = $pg->periodo->copy()->addMonth()->day(19);
                    $pg->mes_corte = $pg->periodo->copy()->addMonth()->format('Y-m');

                    return $pg;
                });

        return view('pipeline.my-results', [
            'proyectos' => $proyectos,
            'liquidaciones' => $liquidaciones,
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
