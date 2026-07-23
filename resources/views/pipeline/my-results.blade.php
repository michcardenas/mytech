@extends('layouts.app_admin')

@section('content')
<style>
    .mr-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .mr-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .mr-sub { color:#64748B; font-size:.9rem; margin:0 0 1.4rem; }
    .mr-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:.9rem; margin-bottom:1.4rem; }
    .mr-stat { border-radius:14px; padding:1.15rem 1.25rem; color:#fff; }
    .mr-stat .n { font-size:1.6rem; font-weight:800; line-height:1; }
    .mr-stat .l { font-size:.76rem; text-transform:uppercase; letter-spacing:.03em; opacity:.9; font-weight:700; margin-top:.35rem; }
    .mr-stat.light { background:#fff; color:#0F172A; border:1px solid #E5E7EB; }
    .mr-stat.light .l { color:#94A3B8; }
    /* Cierres / comisión por mes: número grande + valor abajo, mismo card */
    .mr-stat.month { display:flex; align-items:baseline; gap:.7rem; flex-wrap:wrap; }
    .mr-stat.month .head { display:flex; align-items:baseline; gap:.55rem; }
    .mr-stat.month .n { font-size:1.9rem; font-weight:800; line-height:1; letter-spacing:-.02em; }
    .mr-stat.month .n small { font-size:.7rem; font-weight:700; opacity:.85; margin-left:.15rem; text-transform:uppercase; letter-spacing:.05em; }
    .mr-stat.month .val { font-size:1rem; font-weight:700; opacity:.95; font-variant-numeric:tabular-nums; }
    .mr-stat.month .l { width:100%; font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; opacity:.85; font-weight:700; margin-top:.15rem; }
    .mr-stat.month.zero { opacity:.7; }
    .mr-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; }
    .mr-card-head { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1rem; }
    .mr-card-head h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0; }
    .mr-filter { display:inline-flex; gap:.3rem; padding:.25rem; background:#F1F5F9; border-radius:999px; }
    .mr-filter a { padding:.35rem .85rem; border-radius:999px; font-size:.78rem; font-weight:700; color:#64748B; text-decoration:none; transition:all .12s; }
    .mr-filter a.active { background:#0F172A; color:#fff; }
    .mr-filter a:not(.active):hover { color:#0F172A; }
    .mr-table { width:100%; font-size:.86rem; font-variant-numeric:tabular-nums; }
    .mr-table th { font-size:.72rem; text-transform:uppercase; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.5rem .6rem; text-align:left; }
    .mr-table td { padding:.6rem .6rem; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
    /* pill % comisión */
    .mr-com-pct { display:inline-flex; align-items:center; padding:.2rem .6rem; border-radius:999px; font-size:.78rem; font-weight:700; font-variant-numeric:tabular-nums; }
    .mr-com-pct.pct { background:#DBEAFE; color:#1D4ED8; }
    .mr-com-pct.fijo { background:#EDE9FE; color:#6D28D9; font-size:.7rem; text-transform:uppercase; letter-spacing:.03em; }
</style>

<div class="mr-wrap">
    <h1 class="mr-title">Mis resultados</h1>
    <p class="mr-sub">Cierres y comisión generada por mes.</p>

    {{-- Badges: cierres y comisión por mes --}}
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
        <div class="mr-stat month {{ $comisionMesActual == 0 ? 'zero' : '' }}"
             style="background:linear-gradient(135deg,#16A34A,#15803D); color:#fff;">
            <div class="head">
                <span class="n">${{ number_format((float) $comisionMesActual, 0, ',', '.') }}</span>
            </div>
            <div class="l"><i class="fas fa-hand-holding-usd"></i> Comisión {{ now()->translatedFormat('F') }}</div>
        </div>
        <div class="mr-stat month {{ $comisionMesAnterior == 0 ? 'zero' : '' }}"
             style="background:linear-gradient(135deg,#7C3AED,#5B21B6); color:#fff;">
            <div class="head">
                <span class="n">${{ number_format((float) $comisionMesAnterior, 0, ',', '.') }}</span>
            </div>
            <div class="l"><i class="fas fa-hand-holding-usd"></i> Comisión {{ now()->subMonth()->translatedFormat('F') }}</div>
        </div>
    </div>

    {{-- KPIs de leads --}}
    <div class="mr-grid">
        <div class="mr-stat light"><div class="n">{{ $ganados }}</div><div class="l">Leads ganados</div></div>
        <div class="mr-stat light"><div class="n">{{ $abiertos }}</div><div class="l">Leads abiertos</div></div>
        <div class="mr-stat light"><div class="n">${{ number_format((float)$pipeline,0,',','.') }}</div><div class="l">En pipeline</div></div>
    </div>

    <div class="mr-card">
        <div class="mr-card-head">
            <h3>Mis proyectos cerrados</h3>
            <div class="mr-filter">
                <a href="{{ route('pipeline.my-results') }}" class="{{ $mesFiltro === null ? 'active' : '' }}">Todos</a>
                <a href="{{ route('pipeline.my-results', ['mes' => 'actual']) }}" class="{{ $mesFiltro === 'actual' ? 'active' : '' }}">Mes en curso</a>
                <a href="{{ route('pipeline.my-results', ['mes' => 'anterior']) }}" class="{{ $mesFiltro === 'anterior' ? 'active' : '' }}">Mes anterior</a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="mr-table">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Cerrado</th>
                        <th>Precio</th>
                        <th>% comisión</th>
                        <th>Mi comisión</th>
                        <th>Estado pago comisión</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proyectos as $p)
                        @php
                            $com = (float) $p->comision_calculada;
                            $pag = (float) $p->total_pagado_gestion;
                            $pend = max($com - $pag, 0);
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ \Illuminate\Support\Str::limit($p->nombre,32) }}</td>
                            <td>{{ ($p->fecha_inicio ?? $p->created_at)->format('d/m/Y') }}</td>
                            <td>{{ $p->moneda==='USD'?'US$':'$' }}{{ number_format((float)$p->precio,0,',','.') }}</td>
                            <td>
                                @if($p->comision_tipo === 'porcentaje' && $p->comision_valor > 0)
                                    <span class="mr-com-pct pct">{{ rtrim(rtrim(number_format((float) $p->comision_valor, 2, ',', '.'), '0'), ',') }}%</span>
                                @elseif($p->comision_tipo === 'monto' && $p->comision_valor > 0)
                                    <span class="mr-com-pct fijo">Monto fijo</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold">${{ number_format($com,0,',','.') }}</td>
                            <td>
                                @if($com <= 0)
                                    <span class="badge bg-light text-muted">Sin comisión</span>
                                @elseif(($p->estado_liquidacion ?? null) === 'pagada' || $pend <= 0)
                                    <span class="badge bg-success">Pagada</span>
                                @elseif(($p->estado_liquidacion ?? null) === 'parcial')
                                    <span class="badge bg-warning text-dark">Parcial (liquidación)</span>
                                @elseif($pag > 0)
                                    <span class="badge bg-warning text-dark">Parcial · falta ${{ number_format($pend,0,',','.') }}</span>
                                @else
                                    <span class="badge bg-secondary">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">
                            @if($mesFiltro)
                                No tienes proyectos cerrados en {{ $mesFiltro === 'actual' ? now()->translatedFormat('F') : now()->subMonth()->translatedFormat('F') }}.
                            @else
                                Aún no tienes proyectos cerrados. ¡A cerrar leads! 💪
                            @endif
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
