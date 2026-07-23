@extends('layouts.app_admin')

@section('content')
<style>
    .mr-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .mr-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }

    /* Banners motivacionales */
    .mr-banner {
        border-radius:16px; padding:1.35rem 1.6rem; margin-bottom:1rem;
        color:#fff; display:flex; align-items:center; gap:1.35rem; flex-wrap:wrap;
        box-shadow:0 6px 20px rgba(15,23,42,.12);
    }
    .mr-banner img { height:84px; width:auto; border-radius:12px; object-fit:cover; flex-shrink:0; }
    .mr-banner-txt { flex:1; min-width:220px; }
    .mr-banner-txt h2 { font-size:1.3rem; font-weight:800; margin:0 0 .3rem; letter-spacing:-.02em; line-height:1.2; }
    .mr-banner-txt p { font-size:.92rem; opacity:.94; margin:0; line-height:1.55; }
    .mr-banner-cta {
        display:inline-flex; align-items:center; gap:.45rem; margin-top:.8rem;
        padding:.55rem 1.15rem; background:rgba(255,255,255,.2);
        border:1px solid rgba(255,255,255,.4); border-radius:10px;
        color:#fff; text-decoration:none; font-size:.87rem; font-weight:700;
        transition:all .15s;
    }
    .mr-banner-cta:hover { background:rgba(255,255,255,.32); color:#fff; transform:translateY(-1px); }
    @media (max-width:640px) { .mr-banner { padding:1.1rem 1.2rem; } .mr-banner img { height:60px; } }
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

    /* Mis liquidaciones */
    .mr-liq-row { display:flex; align-items:center; gap:1rem; padding:.6rem .85rem; background:#F8FAFC; border:1px solid #E5E7EB; border-radius:10px; margin-bottom:.45rem; font-size:.85rem; flex-wrap:wrap; }
    .mr-liq-row .ciclo { font-weight:700; color:#0F172A; }
    .mr-liq-row .monto { font-weight:800; color:#16A34A; font-variant-numeric:tabular-nums; }
    .mr-liq-row .meta { color:#64748B; font-size:.78rem; }
    .mr-liq-btn { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .75rem; border-radius:8px; font-size:.75rem; font-weight:700; text-decoration:none; transition:all .12s; }
    .mr-liq-btn.doc { background:#2563EB; color:#fff; }
    .mr-liq-btn.doc:hover { background:#1D4ED8; color:#fff; }
    .mr-liq-btn.comp { background:#fff; color:#334155; border:1px solid #E2E8F0; }
    .mr-liq-btn.comp:hover { border-color:#2563EB; color:#2563EB; }
</style>

<div class="mr-wrap">
    @if(isset($banners) && $banners->isNotEmpty())
        @foreach($banners as $banner)
            <div class="mr-banner" style="background: {{ $banner->gradiente }};">
                @if($banner->imagen)
                    <img src="{{ asset('storage/'.$banner->imagen) }}" alt="">
                @endif
                <div class="mr-banner-txt">
                    <h2>{{ $banner->titulo }}</h2>
                    @if($banner->mensaje)<p>{{ $banner->mensaje }}</p>@endif
                    @if($banner->cta_texto && $banner->cta_url)
                        <a href="{{ $banner->cta_url }}" class="mr-banner-cta">{{ $banner->cta_texto }} <i class="fas fa-arrow-right"></i></a>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

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

    @if(isset($liquidaciones) && $liquidaciones->isNotEmpty())
        <div class="mr-card" style="margin-top:1.4rem;">
            <h3><i class="fas fa-file-invoice-dollar" style="color:#2563EB;"></i> Mis liquidaciones pagadas</h3>
            @foreach($liquidaciones as $liq)
                <div class="mr-liq-row">
                    <span class="ciclo"><i class="far fa-calendar-alt" style="color:#94A3B8;"></i> Ciclo {{ $liq->periodo->format('d/m/Y') }} — {{ $liq->ciclo_fin->format('d/m/Y') }}</span>
                    <span class="monto">${{ number_format((float) $liq->monto, 0, ',', '.') }}</span>
                    <span class="meta">Pagado el {{ $liq->fecha_pago->format('d/m/Y') }}{{ $liq->metodo ? ' · '.$liq->metodo : '' }}</span>
                    <span style="margin-left:auto; display:flex; gap:.4rem; flex-wrap:wrap;">
                        @if($liq->comprobante)
                            <a href="{{ asset('storage/'.$liq->comprobante) }}" target="_blank" class="mr-liq-btn comp">
                                <i class="fas fa-paperclip"></i> Comprobante
                            </a>
                        @endif
                        <a href="{{ route('admin.internal-projects.liquidacion.documento', ['vendedor' => $liq->vendedor_id, 'mes' => $liq->mes_corte]) }}" target="_blank" class="mr-liq-btn doc">
                            <i class="fas fa-file-pdf"></i> Ver liquidación
                        </a>
                    </span>
                </div>
            @endforeach
            <p style="font-size:.75rem; color:#94A3B8; margin-top:.5rem;">El documento de liquidación detalla tu sueldo básico, las comisiones del ciclo y el total pagado.</p>
        </div>
    @endif
</div>
@endsection
