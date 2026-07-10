@extends('layouts.app_admin')

@section('content')
<style>
    .mr-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .mr-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .mr-sub { color:#64748B; font-size:.9rem; margin:0 0 1.4rem; }
    .mr-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:.9rem; margin-bottom:1.4rem; }
    .mr-stat { border-radius:14px; padding:1.15rem 1.25rem; color:#fff; }
    .mr-stat .n { font-size:1.6rem; font-weight:800; line-height:1; }
    .mr-stat .l { font-size:.76rem; text-transform:uppercase; letter-spacing:.03em; opacity:.9; font-weight:700; margin-top:.35rem; }
    .mr-stat.light { background:#fff; color:#0F172A; border:1px solid #E5E7EB; }
    .mr-stat.light .l { color:#94A3B8; }
    /* Cierres por mes: número grande + valor abajo, mismo card */
    .mr-stat.month { display:flex; align-items:baseline; gap:.7rem; flex-wrap:wrap; }
    .mr-stat.month .head { display:flex; align-items:baseline; gap:.55rem; }
    .mr-stat.month .n { font-size:1.9rem; font-weight:800; line-height:1; letter-spacing:-.02em; }
    .mr-stat.month .n small { font-size:.7rem; font-weight:700; opacity:.85; margin-left:.15rem; text-transform:uppercase; letter-spacing:.05em; }
    .mr-stat.month .val { font-size:1rem; font-weight:700; opacity:.95; font-variant-numeric:tabular-nums; }
    .mr-stat.month .l { width:100%; font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; opacity:.85; font-weight:700; margin-top:.15rem; }
    .mr-stat.month.zero { opacity:.7; }
    .mr-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; }
    .mr-card h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0 0 1rem; }
    .mr-table { width:100%; font-size:.86rem; }
    .mr-table th { font-size:.72rem; text-transform:uppercase; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.5rem .6rem; text-align:left; }
    .mr-table td { padding:.6rem .6rem; border-bottom:1px solid #F1F5F9; }
</style>

<div class="mr-wrap">
    <h1 class="mr-title">Mis resultados</h1>
    <p class="mr-sub">Tu desempeño: lo que has cerrado y tu comisión generada.</p>

    <div class="mr-grid">
        <div class="mr-stat" style="background:linear-gradient(135deg,#0F172A,#1E293B)"><div class="n">${{ number_format((float)$valorCerrado,0,',','.') }}</div><div class="l">Total cerrado por ti</div></div>
        <div class="mr-stat" style="background:linear-gradient(135deg,#2563EB,#1D4ED8)"><div class="n">${{ number_format((float)$comisionTotal,0,',','.') }}</div><div class="l">Comisión generada</div></div>
        <div class="mr-stat" style="background:linear-gradient(135deg,#16A34A,#15803D)"><div class="n">${{ number_format((float)$comisionPagada,0,',','.') }}</div><div class="l">Comisión pagada</div></div>
        <div class="mr-stat" style="background:linear-gradient(135deg,#F59E0B,#D97706)"><div class="n">${{ number_format((float)$comisionPendiente,0,',','.') }}</div><div class="l">Por cobrar</div></div>
    </div>

    {{-- Cierres del mes actual y del mes anterior --}}
    <div class="mr-grid">
        <div class="mr-stat month {{ $cierresMesActual['count'] === 0 ? 'zero' : '' }}"
             style="background:linear-gradient(135deg,#0EA5E9,#0284C7); color:#fff;">
            <div class="head">
                <span class="n">{{ $cierresMesActual['count'] }}<small>{{ $cierresMesActual['count'] === 1 ? ' cierre' : ' cierres' }}</small></span>
                <span class="val">${{ number_format((float) $cierresMesActual['valor_cop'], 0, ',', '.') }}</span>
            </div>
            <div class="l"><i class="far fa-calendar-check"></i> Cerrados este mes · {{ now()->translatedFormat('F') }}</div>
        </div>
        <div class="mr-stat month {{ $cierresMesAnterior['count'] === 0 ? 'zero' : '' }}"
             style="background:linear-gradient(135deg,#64748B,#475569); color:#fff;">
            <div class="head">
                <span class="n">{{ $cierresMesAnterior['count'] }}<small>{{ $cierresMesAnterior['count'] === 1 ? ' cierre' : ' cierres' }}</small></span>
                <span class="val">${{ number_format((float) $cierresMesAnterior['valor_cop'], 0, ',', '.') }}</span>
            </div>
            <div class="l"><i class="far fa-calendar"></i> Cerrados mes pasado · {{ now()->subMonth()->translatedFormat('F') }}</div>
        </div>
    </div>

    <div class="mr-grid">
        <div class="mr-stat light"><div class="n">{{ $ganados }}</div><div class="l">Leads ganados</div></div>
        <div class="mr-stat light"><div class="n">{{ $abiertos }}</div><div class="l">Leads abiertos</div></div>
        <div class="mr-stat light"><div class="n">${{ number_format((float)$pipeline,0,',','.') }}</div><div class="l">En pipeline</div></div>
    </div>

    <div class="mr-card">
        <h3>Mis proyectos cerrados</h3>
        <div class="table-responsive">
            <table class="mr-table">
                <thead><tr><th>Proyecto</th><th>Cerrado</th><th>Precio</th><th>Mi comisión</th><th>Estado pago</th></tr></thead>
                <tbody>
                    @forelse($proyectos as $p)
                        @php $com=(float)$p->comision_calculada; $pag=(float)$p->total_pagado_gestion; $pend=max($com-$pag,0); @endphp
                        <tr>
                            <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($p->nombre,32) }}</td>
                            <td>{{ $p->created_at->format('d/m/Y') }}</td>
                            <td>{{ $p->moneda==='USD'?'US$':'$' }}{{ number_format((float)$p->precio,0,',','.') }}</td>
                            <td class="fw-semibold">${{ number_format($com,0,',','.') }}</td>
                            <td>
                                @if($pend <= 0)
                                    <span class="badge bg-success">Pagada</span>
                                @elseif($pag > 0)
                                    <span class="badge bg-warning text-dark">Parcial · falta ${{ number_format($pend,0,',','.') }}</span>
                                @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Aún no tienes proyectos cerrados. ¡A cerrar leads! 💪</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
