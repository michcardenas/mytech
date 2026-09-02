<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis proyectos · {{ $nombreVisible }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --blue: #2563EB;
            --grad: linear-gradient(135deg, #60A5FA 0%, #2563EB 100%);
            --shadow: 0 4px 15px rgba(0,0,0,0.06);
            --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #F6F7F9; color: #0F172A; min-height: 100vh; }
        .wrap { max-width: 1080px; margin: 0 auto; padding: 1.75rem 1.25rem 3rem; }

        .head { background: var(--grad); color: white; border-radius: 18px; padding: 1.5rem 1.75rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .head-info { display: flex; align-items: center; gap: 1rem; min-width: 0; }
        .head-avatar { width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,0.22); border: 2px solid rgba(255,255,255,0.4); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800; flex-shrink: 0; }
        .head h1 { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.15rem; letter-spacing:-.02em; }
        .head .meta { font-size: 0.82rem; opacity: 0.9; }
        .btn-logout { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.28); color: white; padding: 0.5rem 0.95rem; border-radius: 10px; font-weight: 600; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.85rem; margin-bottom: 1.25rem; }
        .kpi { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 0.9rem 1.1rem; position: relative; overflow: hidden; }
        .kpi::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: #CBD5E1; }
        .kpi.blue::before { background: var(--blue); }
        .kpi.green::before { background: #16A34A; }
        .kpi.amber::before { background: #F59E0B; }
        .kpi-label { font-size: 0.7rem; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; }
        .kpi-value { font-size: 1.35rem; font-weight: 800; color: #0F172A; margin-top: 0.15rem; letter-spacing:-.02em; }

        .panel { background: white; border: 1px solid #E5E7EB; border-radius: 14px; padding: 1.15rem 1.4rem; margin-bottom: 1.25rem; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem; }
        .panel-head h3 { font-size: 1rem; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 0.5rem; }
        .panel-head .muted { font-size: 0.78rem; color: #94A3B8; }

        table { width: 100%; border-collapse: collapse; font-size: 0.87rem; font-variant-numeric: tabular-nums; }
        th { text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.03em; color: #94A3B8; font-weight: 700; padding: 0.55rem 0.75rem; border-bottom: 2px solid #F1F5F9; white-space: nowrap; }
        td { padding: 0.7rem 0.75rem; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #FAFBFC; }
        .row-name { font-weight: 700; color: #0F172A; }
        .estado { display: inline-flex; align-items: center; gap: .3rem; padding: .2rem .55rem; border-radius: 999px; font-size: .7rem; font-weight: 700; }
        .estado .dot { width: 6px; height: 6px; border-radius: 999px; background: currentColor; }
        .estado.cotizado { background: #FEF3C7; color: #B45309; }
        .estado.en_progreso { background: #DBEAFE; color: #1D4ED8; }
        .estado.pausado { background: #F1F5F9; color: #475569; }
        .estado.completado { background: #DCFCE7; color: #166534; }
        .estado.cancelado { background: #FEE2E2; color: #B91C1C; }
        .pct-bar { display: flex; align-items: center; gap: .5rem; min-width: 130px; }
        .pct-bar .bar { flex: 1; height: 6px; border-radius: 999px; background: #F1F5F9; overflow: hidden; }
        .pct-bar .bar > span { display: block; height: 100%; border-radius: 999px; transition: width .3s; }
        .pct-bar .txt { font-size: .75rem; font-weight: 700; color: #0F172A; min-width: 34px; text-align: right; }

        .empty { text-align: center; padding: 2.5rem 1rem; color: #94A3B8; }
        .empty i { font-size: 2rem; opacity: .4; margin-bottom: .5rem; display: block; }

        .fact-info { background: #EFF6FF; border: 1px dashed #93C5FD; color: #1E40AF; border-radius: 12px; padding: 0.85rem 1rem; font-size: 0.85rem; display: flex; gap: 0.6rem; align-items: flex-start; }
        .fact-info i { font-size: 1rem; margin-top: 0.15rem; }

        /* Botón recibo */
        .btn-recibo {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .4rem .8rem; border-radius: 8px;
            background: #2563EB; color: #fff; text-decoration: none;
            font-size: .75rem; font-weight: 700;
            transition: all .15s;
        }
        .btn-recibo:hover { background: #1D4ED8; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(37,99,235,.35); color: #fff; }
        .btn-recibo i { font-size: .7rem; }

        .metodo-pill {
            display: inline-block;
            padding: .18rem .55rem;
            border-radius: 999px;
            background: #F1F5F9;
            color: #475569;
            font-size: .72rem;
            font-weight: 600;
        }

        /* ===== Bolsa de horas (premium) ===== */
        .bolsa-card { position: relative; overflow: hidden; border: 1px solid #E9EEF5; opacity: 0; transform: translateY(10px); animation: bolsaIn .6s var(--ease-out) forwards; }
        .bolsa-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #60A5FA, #2563EB); }
        @keyframes bolsaIn { to { opacity: 1; transform: translateY(0); } }
        @media (hover: hover) and (pointer: fine) {
            .bolsa-card { transition: transform .28s var(--ease-out), box-shadow .28s ease; }
            .bolsa-card:hover { transform: translateY(-3px); box-shadow: 0 14px 34px rgba(37,99,235,.12); }
        }

        .bolsa-top { display: flex; align-items: center; gap: 1.75rem; margin: .5rem 0 .25rem; flex-wrap: wrap; }
        .bolsa-gauge { position: relative; width: 176px; height: 176px; flex-shrink: 0; margin: 0 auto; }
        .bolsa-gauge svg { width: 100%; height: 100%; transform: rotate(-90deg); overflow: visible; }
        .g-track { fill: none; stroke: #EEF2F7; stroke-width: 12; }
        .g-value { fill: none; stroke-width: 12; stroke-linecap: round; transition: stroke-dashoffset 1.1s var(--ease-out); filter: drop-shadow(0 3px 6px rgba(37,99,235,.18)); }
        .g-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; }
        .g-num { font-size: 2.7rem; font-weight: 800; color: #0F172A; letter-spacing: -.03em; line-height: 1; font-variant-numeric: tabular-nums; }
        .g-num .g-unit { font-size: 1.05rem; font-weight: 700; color: #64748B; margin-left: 2px; }
        .g-lbl { font-size: .68rem; text-transform: uppercase; letter-spacing: .07em; color: #94A3B8; font-weight: 800; margin-top: .4rem; }
        .g-sub { font-size: .74rem; color: #64748B; font-weight: 600; margin-top: .15rem; }
        .g-sub.excedida { color: #DC2626; font-weight: 700; }

        .bolsa-meta { flex: 1; min-width: 220px; display: flex; flex-direction: column; gap: .55rem; }
        .bm-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .65rem .85rem; background: #F8FAFC; border: 1px solid #EEF2F7; border-radius: 12px; }
        .bm-left { display: flex; align-items: center; gap: .7rem; min-width: 0; }
        .bm-ico { width: 34px; height: 34px; border-radius: 9px; display: inline-flex; align-items: center; justify-content: center; font-size: .82rem; flex-shrink: 0; }
        .bm-lbl { font-size: .82rem; color: #475569; font-weight: 600; }
        .bm-num { font-size: 1.15rem; font-weight: 800; color: #0F172A; font-variant-numeric: tabular-nums; white-space: nowrap; }
        .bm-num small { font-size: .72rem; color: #94A3B8; font-weight: 600; }

        .bolsa-linebar { height: 9px; border-radius: 999px; background: #EEF2F7; overflow: hidden; margin-top: .35rem; }
        .bolsa-linebar > span { display: block; height: 100%; width: 0; border-radius: 999px; background: linear-gradient(90deg, #60A5FA, #2563EB); transition: width 1.1s var(--ease-out); }
        .bolsa-linebar.warn > span { background: linear-gradient(90deg, #FBBF24, #F59E0B); }
        .bolsa-linebar.danger > span { background: linear-gradient(90deg, #F87171, #DC2626); }
        .bolsa-linecap { display: flex; justify-content: space-between; flex-wrap: wrap; gap: .3rem; font-size: .77rem; color: #64748B; margin-top: .45rem; }
        .bolsa-linecap strong { color: #0F172A; }

        .bolsa-sub { font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; color: #94A3B8; font-weight: 800; margin: 1.35rem 0 .55rem; display: flex; align-items: center; gap: .4rem; }
        .bolsa-puntos { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: .4rem; }
        .bolsa-puntos li { display: flex; align-items: center; gap: .65rem; padding: .6rem .8rem; background: #F8FAFC; border: 1px solid #EEF2F7; border-radius: 11px; font-size: .87rem; opacity: 0; transform: translateY(6px); animation: puntoIn .45s var(--ease-out) forwards; }
        @keyframes puntoIn { to { opacity: 1; transform: translateY(0); } }
        @media (hover: hover) and (pointer: fine) {
            .bolsa-puntos li { transition: background .2s ease, border-color .2s ease; }
            .bolsa-puntos li:hover { background: #F1F6FF; border-color: #DBEAFE; }
        }
        .bp-check { flex-shrink: 0; font-size: .95rem; }
        @keyframes bpSpin { to { transform: rotate(360deg); } }
        .bp-spin { display: inline-block; animation: bpSpin 2.4s linear infinite; transform-origin: center; }
        .bp-text { flex: 1; min-width: 0; color: #0F172A; font-weight: 600; }
        .bp-hrs { font-size: .74rem; color: #64748B; font-weight: 700; white-space: nowrap; }
        .bp-badge { padding: .18rem .6rem; border-radius: 999px; font-size: .68rem; font-weight: 800; white-space: nowrap; }
        .bolsa-empty { text-align: center; color: #94A3B8; font-size: .85rem; padding: 1.1rem; background: #F8FAFC; border-radius: 10px; }

        /* Detalle de horas usadas — lista minimalista */
        .uso-list { display: flex; flex-direction: column; }
        .uso-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.25rem; padding: .8rem .1rem; border-bottom: 1px solid #F1F5F9; }
        .uso-row:last-of-type { border-bottom: none; }
        .uso-main { min-width: 0; }
        .uso-meta { display: flex; align-items: center; gap: .55rem; margin-bottom: .22rem; flex-wrap: wrap; }
        .uso-fecha { font-size: .74rem; color: #94A3B8; font-weight: 600; font-variant-numeric: tabular-nums; }
        .uso-tema { font-size: .67rem; font-weight: 700; color: #2563EB; background: #EEF4FF; padding: .11rem .55rem; border-radius: 999px; letter-spacing: .01em; }
        .uso-desc { font-size: .9rem; color: #334155; line-height: 1.45; }
        .uso-horas { font-size: .95rem; font-weight: 800; color: #0F172A; white-space: nowrap; font-variant-numeric: tabular-nums; }
        .uso-horas span { font-size: .7rem; font-weight: 600; color: #94A3B8; margin-left: .12rem; }
        .uso-total { display: flex; align-items: center; justify-content: space-between; margin-top: .55rem; padding-top: .7rem; border-top: 2px solid #F1F5F9; }
        .uso-total span { font-size: .72rem; text-transform: uppercase; letter-spacing: .04em; color: #94A3B8; font-weight: 800; }
        .uso-total strong { font-size: 1rem; color: #0F172A; font-weight: 800; font-variant-numeric: tabular-nums; }

        @media (prefers-reduced-motion: reduce) {
            .bolsa-card, .bolsa-puntos li { animation: none; opacity: 1; transform: none; }
            .bp-spin { animation: none; }
            .g-value, .bolsa-linebar > span { transition: none; }
        }

        @media (max-width: 560px) {
            .bolsa-gauge { width: 150px; height: 150px; }
            .g-num { font-size: 2.3rem; }
        }

        /* Banners de servicios — carrusel premium (los publica el admin) */
        .clibn-carousel { position: relative; margin-bottom: 1.25rem; }
        .clibn-slide { display: none; }
        .clibn-slide.is-active { display: block; animation: clibnFade .45s var(--ease-out, ease); }
        @keyframes clibnFade { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }
        .clibn {
            position: relative; overflow: hidden;
            background: #fff; border: 1px solid #E9EEF5; border-radius: 16px;
            padding: 1.4rem 1.6rem 1.4rem 1.75rem;
            display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
            box-shadow: 0 6px 22px rgba(15,23,42,.05);
        }
        .clibn::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background: var(--accent, #2563EB); }
        .clibn-img { width: 118px; height: 104px; border-radius: 12px; object-fit: cover; flex-shrink: 0; background: var(--soft, #EFF6FF); }
        .clibn-body { flex: 1; min-width: 240px; }
        .clibn-body h2 { font-size: 1.12rem; font-weight: 800; color: #0F172A; margin: 0 0 .35rem; letter-spacing: -.02em; line-height: 1.28; }
        .clibn-body p { font-size: .9rem; color: #64748B; margin: 0; line-height: 1.55; }
        .clibn-cta { display: inline-flex; align-items: center; gap: .5rem; margin-top: 1rem; padding: .6rem 1.3rem; background: var(--accent, #2563EB); color: #fff; border-radius: 10px; text-decoration: none; font-size: .86rem; font-weight: 700; transition: transform .15s ease, filter .15s ease; }
        .clibn-cta:hover { filter: brightness(1.07); transform: translateY(-1px); }
        .clibn-dots { display: flex; justify-content: center; gap: .4rem; margin-top: .85rem; }
        .clibn-dot { width: 8px; height: 8px; border-radius: 999px; border: none; background: #CBD5E1; cursor: pointer; padding: 0; transition: all .25s ease; }
        .clibn-dot.is-active { background: #2563EB; width: 22px; }
        .clibn-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 34px; height: 34px; border-radius: 50%; border: 1px solid #E5E7EB; background: rgba(255,255,255,.92); color: #334155; cursor: pointer; display: none; align-items: center; justify-content: center; z-index: 3; box-shadow: 0 2px 10px rgba(15,23,42,.1); }
        .clibn-arrow.prev { left: 10px; } .clibn-arrow.next { right: 10px; }
        .clibn-arrow:hover { background: #fff; color: #0F172A; }
        .clibn-carousel:hover .clibn-arrow { display: flex; }
        @media (max-width: 640px) {
            .clibn { padding: 1.2rem 1.3rem; }
            .clibn-img { width: 100%; height: 150px; }
            .clibn-arrow { display: none !important; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="head">
        <div class="head-info">
            <div class="head-avatar">{{ strtoupper(mb_substr($nombreVisible, 0, 1)) }}</div>
            <div>
                <h1>{{ $nombreVisible }}</h1>
                <div class="meta">{{ $proyectos->count() }} {{ $proyectos->count() === 1 ? 'proyecto' : 'proyectos' }} con nosotros</div>
            </div>
        </div>
        <form method="POST" action="{{ route('portal.cliente.logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Salir</button>
        </form>
    </div>

    @if(isset($banners) && $banners->isNotEmpty())
        <div class="clibn-carousel" id="clibnCarousel">
            @if($banners->count() > 1)
                <button class="clibn-arrow prev" type="button" aria-label="Anterior" onclick="clibnGo(-1)"><i class="fas fa-chevron-left"></i></button>
                <button class="clibn-arrow next" type="button" aria-label="Siguiente" onclick="clibnGo(1)"><i class="fas fa-chevron-right"></i></button>
            @endif

            @foreach($banners as $banner)
                <div class="clibn-slide {{ $loop->first ? 'is-active' : '' }}">
                    <div class="clibn" style="--accent: {{ $banner->accent }}; --soft: {{ $banner->soft }};">
                        @if($banner->imagen)
                            <img class="clibn-img" src="{{ asset('storage/'.$banner->imagen) }}" alt="">
                        @endif
                        <div class="clibn-body">
                            <h2>{{ $banner->titulo }}</h2>
                            @if($banner->mensaje)<p>{{ $banner->mensaje }}</p>@endif
                            @if($banner->cta_texto && $banner->cta_url)
                                <a href="{{ $banner->cta_url }}" target="_blank" rel="noopener" class="clibn-cta">{{ $banner->cta_texto }} <i class="fas fa-arrow-right"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($banners->count() > 1)
                <div class="clibn-dots">
                    @foreach($banners as $banner)
                        <button class="clibn-dot {{ $loop->first ? 'is-active' : '' }}" type="button" onclick="clibnJump({{ $loop->index }})" aria-label="Ir al banner {{ $loop->iteration }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @php
        $totalContratado = $proyectos->sum('precio');
        $totalPagado = $proyectos->sum(fn ($p) => (float) ($p->pagado ?? 0));
        $totalSaldo = max($totalContratado - $totalPagado, 0);
    @endphp

    <div class="kpi-grid">
        <div class="kpi blue">
            <div class="kpi-label">Total contratado</div>
            <div class="kpi-value">${{ number_format((float) $totalContratado, 0, ',', '.') }}</div>
        </div>
        <div class="kpi green">
            <div class="kpi-label">Total pagado</div>
            <div class="kpi-value">${{ number_format((float) $totalPagado, 0, ',', '.') }}</div>
        </div>
        <div class="kpi amber">
            <div class="kpi-label">Saldo pendiente</div>
            <div class="kpi-value">${{ number_format((float) $totalSaldo, 0, ',', '.') }}</div>
        </div>
    </div>

    @php
        $bolsas = $proyectos->where('es_bolsa_horas', true);
        $fmtH = fn ($h) => rtrim(rtrim(number_format((float) $h, 2, ',', '.'), '0'), ',');
        $estadoBolsa = [
            'pendiente' => ['Pendiente', '#64748B', '#F1F5F9', 'fa-circle'],
            'en_progreso' => ['En progreso', '#1D4ED8', '#DBEAFE', 'fa-circle-notch'],
            'hecho' => ['Hecho', '#166534', '#DCFCE7', 'fa-circle-check'],
        ];
    @endphp
    @foreach($bolsas as $b)
        @php
            $bTot = (float) $b->horas_totales;
            $bCons = $b->horas_consumidas;
            $bRest = $b->horas_restantes;
            $bPct = $b->porcentaje_horas;   // % consumido (0..100)
            $bRem = max(100 - $bPct, 0);     // % disponible
            $sim = $b->moneda === 'USD' ? 'US$' : ($b->moneda === 'EUR' ? '€' : '$');
            // Color según horas DISPONIBLES: sano (azul) -> ojo (ámbar) -> casi agotada (rojo)
            if ($bRem <= 20) { $g1 = '#F87171'; $g2 = '#DC2626'; }
            elseif ($bRem <= 40) { $g1 = '#FBBF24'; $g2 = '#F59E0B'; }
            else { $g1 = '#38BDF8'; $g2 = '#2563EB'; }
            $circ = 2 * M_PI * 52;
            $gid = 'bolsaGrad'.$loop->index;
        @endphp
        <div class="panel bolsa-card" style="animation-delay: {{ $loop->index * 90 }}ms;">
            <div class="panel-head">
                <h3><i class="fas fa-hourglass-half" style="color:#2563EB;"></i> {{ $b->nombre }}</h3>
                <span class="muted">Bolsa de horas prepagada</span>
            </div>

            <div class="bolsa-top">
                <div class="bolsa-gauge">
                    <svg viewBox="0 0 120 120" role="img" aria-label="{{ $bRem }}% de horas disponibles">
                        <defs>
                            <linearGradient id="{{ $gid }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="{{ $g1 }}"/>
                                <stop offset="100%" stop-color="{{ $g2 }}"/>
                            </linearGradient>
                        </defs>
                        <circle class="g-track" cx="60" cy="60" r="52"/>
                        <circle class="g-value" cx="60" cy="60" r="52"
                                stroke="url(#{{ $gid }})"
                                stroke-dasharray="{{ round($circ, 2) }}"
                                stroke-dashoffset="{{ round($circ * (min($bPct, 100) / 100), 2) }}"
                                data-offset="{{ round($circ * (min($bPct, 100) / 100), 2) }}"
                                data-empty="{{ round($circ, 2) }}"/>
                    </svg>
                    <div class="g-center">
                        <div class="g-num"><span class="js-count" data-to="{{ max($bRest, 0) }}">{{ $fmtH(max($bRest, 0)) }}</span><span class="g-unit">h</span></div>
                        <div class="g-lbl">Disponibles</div>
                        @if($bRest < 0)
                            <div class="g-sub excedida">Excedida {{ $fmtH(abs($bRest)) }} h</div>
                        @else
                            <div class="g-sub">de {{ $fmtH($bTot) }} h</div>
                        @endif
                    </div>
                </div>

                <div class="bolsa-meta">
                    <div class="bm-row">
                        <div class="bm-left">
                            <span class="bm-ico" style="background:#EFF6FF; color:#2563EB;"><i class="fas fa-clock"></i></span>
                            <span class="bm-lbl">Horas contratadas</span>
                        </div>
                        <span class="bm-num"><span class="js-count" data-to="{{ $bTot }}">{{ $fmtH($bTot) }}</span> h</span>
                    </div>
                    <div class="bm-row">
                        <div class="bm-left">
                            <span class="bm-ico" style="background:#FEF2F2; color:#DC2626;"><i class="fas fa-bolt"></i></span>
                            <span class="bm-lbl">Horas consumidas</span>
                        </div>
                        <span class="bm-num"><span class="js-count" data-to="{{ $bCons }}">{{ $fmtH($bCons) }}</span> h</span>
                    </div>
                    @if($b->valor_hora)
                        <div class="bm-row">
                            <div class="bm-left">
                                <span class="bm-ico" style="background:#F0FDF4; color:#16A34A;"><i class="fas fa-tag"></i></span>
                                <span class="bm-lbl">Valor de la bolsa</span>
                            </div>
                            <span class="bm-num">{{ $sim }}{{ number_format((float) $b->valor_hora * $bTot, 0, ',', '.') }} <small>{{ $b->moneda }}</small></span>
                        </div>
                    @endif

                    <div class="bolsa-linebar"><span style="width: {{ min($bPct, 100) }}%;" data-width="{{ min($bPct, 100) }}"></span></div>
                    <div class="bolsa-linecap">
                        <span><strong>{{ $bPct }}%</strong> consumido</span>
                        <span>Quedan <strong>{{ $fmtH(max($bRest, 0)) }}</strong> de {{ $fmtH($bTot) }} h</span>
                    </div>
                </div>
            </div>

            @if(!empty($b->puntos_acuerdo))
                <div class="bolsa-sub"><i class="fas fa-list-check" style="color:#2563EB;"></i> Puntos acordados</div>
                <ul class="bolsa-puntos">
                    @foreach($b->puntos_acuerdo as $pt)
                        @php $estado = $pt['estado'] ?? 'pendiente'; $em = $estadoBolsa[$estado] ?? $estadoBolsa['pendiente']; @endphp
                        <li style="animation-delay: {{ $loop->index * 60 }}ms;">
                            <span class="bp-check" style="color:{{ $em[1] }};"><i class="fas {{ $em[3] }}{{ $estado === 'en_progreso' ? ' bp-spin' : '' }}"></i></span>
                            <span class="bp-text">{{ $pt['texto'] ?? '' }}</span>
                            @if(!empty($pt['horas']))<span class="bp-hrs">{{ $fmtH($pt['horas']) }} h</span>@endif
                            <span class="bp-badge" style="color:{{ $em[1] }}; background:{{ $em[2] }};">{{ $em[0] }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="bolsa-sub"><i class="fas fa-clock-rotate-left" style="color:#2563EB;"></i> Detalle de horas usadas</div>
            @if($b->bolsaMovimientos->isNotEmpty())
                <div class="uso-list">
                    @foreach($b->bolsaMovimientos as $mov)
                        <div class="uso-row">
                            <div class="uso-main">
                                <div class="uso-meta">
                                    <span class="uso-fecha">{{ $mov->fecha->format('d/m/Y') }}</span>
                                    @if($mov->tema)<span class="uso-tema">{{ $mov->tema }}</span>@endif
                                </div>
                                <div class="uso-desc">{{ $mov->descripcion }}</div>
                            </div>
                            <div class="uso-horas">{{ $fmtH($mov->horas) }}<span>h</span></div>
                        </div>
                    @endforeach
                    <div class="uso-total"><span>Total consumido</span><strong>{{ $fmtH($bCons) }} h</strong></div>
                </div>
            @else
                <div class="bolsa-empty">Aún no hemos consumido horas de esta bolsa.</div>
            @endif
        </div>
    @endforeach

    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-briefcase" style="color:#2563EB;"></i> Mis proyectos</h3>
            <span class="muted">{{ $proyectos->count() }} en total</span>
        </div>
        @if($proyectos->isEmpty())
            <div class="empty">
                <i class="fas fa-folder-open"></i>
                <div>Aún no tienes proyectos registrados.</div>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Pagado</th>
                        <th>Entrega</th>
                        <th>Próxima factura</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($proyectos as $p)
                        @php
                            $pagado = (float) ($p->pagado ?? 0);
                            $precio = (float) $p->precio;
                            $pct = $precio > 0 ? min(100, round(($pagado / $precio) * 100)) : 0;
                            $pctColor = $pct >= 100 ? '#16A34A' : ($pct >= 50 ? '#2563EB' : ($pct > 0 ? '#F59E0B' : '#CBD5E1'));
                            $simbolo = $p->moneda === 'USD' ? 'US$' : ($p->moneda === 'EUR' ? '€' : '$');
                        @endphp
                        <tr>
                            <td class="row-name">{{ $p->nombre }}</td>
                            <td><span class="estado {{ $p->estado }}"><span class="dot"></span>{{ $p->estado_label }}</span></td>
                            <td>{{ $simbolo }}{{ number_format($precio, 0, ',', '.') }}</td>
                            <td>
                                <div class="pct-bar">
                                    <div class="bar"><span style="width:{{ $pct }}%; background:{{ $pctColor }};"></span></div>
                                    <span class="txt">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td>
                                @if($p->es_recurrente)
                                    <span style="color:#7C3AED; font-weight:600;">Recurrente</span>
                                @elseif($p->fecha_entrega)
                                    {{ $p->fecha_entrega->format('d/m/Y') }}
                                @else
                                    <span style="color:#94A3B8;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($p->fecha_facturacion)
                                    {{ $p->fecha_facturacion->format('d/m/Y') }}
                                @else
                                    <span style="color:#94A3B8;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @if(isset($cuentasCobro) && $cuentasCobro->isNotEmpty())
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-file-invoice" style="color:#B45309;"></i> Cuentas de cobro por pagar</h3>
                <span class="muted">{{ $cuentasCobro->count() }} pendiente{{ $cuentasCobro->count() === 1 ? '' : 's' }}</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Proyecto</th>
                        <th style="text-align:right;">Valor</th>
                        <th style="text-align:right;">Cuenta de cobro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuentasCobro as $cc)
                        @php $sc = $cc->moneda === 'USD' ? 'US$' : ($cc->moneda === 'EUR' ? '€' : '$'); @endphp
                        <tr>
                            <td class="row-name">{{ $cc->numero_doc }}</td>
                            <td>{{ $cc->project->nombre ?? '—' }}</td>
                            <td style="text-align:right; font-weight:800; color:#B45309;">{{ $sc }}{{ number_format((float) $cc->monto, 0, ',', '.') }} <span style="color:#94A3B8; font-size:.72rem; font-weight:600;">{{ $cc->moneda }}</span></td>
                            <td style="text-align:right;">
                                <a href="{{ route('portal.cliente.cuenta-cobro', $cc) }}" target="_blank" class="btn-recibo" style="background:#B45309;">
                                    <i class="fas fa-file-download"></i> Ver / descargar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-file-invoice-dollar" style="color:#2563EB;"></i> Mis recibos de pago</h3>
            <span class="muted">{{ $pagos->count() }} {{ $pagos->count() === 1 ? 'recibo emitido' : 'recibos emitidos' }}</span>
        </div>

        @if($pagos->isEmpty())
            <div class="empty">
                <i class="fas fa-receipt"></i>
                <div>Aún no tienes pagos registrados.</div>
                <p style="margin-top:.3rem; font-size:.85rem;">Cuando registremos un pago tuyo, aquí aparecerá el recibo con el membrete oficial.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Proyecto</th>
                        <th>Método</th>
                        <th>Referencia</th>
                        <th style="text-align:right;">Monto</th>
                        <th style="text-align:right;">Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                        @php $simbolo = $pago->project->moneda === 'USD' ? 'US$' : ($pago->project->moneda === 'EUR' ? '€' : '$'); @endphp
                        <tr>
                            <td class="row-name">{{ $pago->fecha->format('d/m/Y') }}</td>
                            <td>{{ $pago->project->nombre }}</td>
                            <td>
                                @if($pago->metodo)
                                    <span class="metodo-pill">{{ $pago->metodo }}</span>
                                @else
                                    <span style="color:#94A3B8;">—</span>
                                @endif
                            </td>
                            <td style="color:#64748B; font-size:.82rem;">{{ $pago->referencia ?: '—' }}</td>
                            <td style="text-align:right; font-weight:700; color:#0F172A;">
                                {{ $simbolo }}{{ number_format((float) $pago->monto, 0, ',', '.') }}
                                <span style="color:#94A3B8; font-size:.72rem; font-weight:600;">{{ $pago->project->moneda }}</span>
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('portal.cliente.receipt', $pago) }}" target="_blank" class="btn-recibo" title="Descargar recibo con membrete">
                                    <i class="fas fa-file-download"></i> Descargar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="fact-info" style="margin-top:1rem;">
                <i class="fas fa-info-circle"></i>
                <div>
                    Cada recibo se genera con el membrete oficial de <strong>MYTECH SOLUTIONS S.A.S · NIT 901.923.467-5</strong>. Al abrirlo puedes imprimirlo o guardarlo como PDF con <kbd style="background:#DBEAFE; padding:.1rem .35rem; border-radius:4px; font-family:monospace;">Ctrl+P</kbd>.
                </div>
            </div>
        @endif
    </div>
</div>
<script>
    (function () {
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const cards = document.querySelectorAll('.bolsa-card');
        if (!cards.length) { return; }

        function fmtH(n) {
            return (Math.round(n * 100) / 100).toFixed(2).replace(/\.?0+$/, '').replace('.', ',');
        }

        // Estado final (el HTML ya lo trae por defecto; esto lo re-asegura sin depender de la animación).
        function setFinal(card) {
            card.querySelectorAll('.js-count').forEach(el => { el.textContent = fmtH(parseFloat(el.dataset.to || '0')); });
            const ring = card.querySelector('.g-value');
            if (ring) { ring.style.strokeDashoffset = ring.dataset.offset || '0'; }
            const bar = card.querySelector('.bolsa-linebar > span');
            if (bar) { bar.style.width = (bar.dataset.width || '0') + '%'; }
        }

        if (reduce) { cards.forEach(setFinal); return; }

        // Reset al estado inicial de forma síncrona (antes del primer paint → sin parpadeo).
        cards.forEach(card => {
            card.querySelectorAll('.js-count').forEach(el => { el.textContent = '0'; });
            const ring = card.querySelector('.g-value');
            if (ring) { ring.style.transition = 'none'; ring.style.strokeDashoffset = ring.dataset.empty; }
            const bar = card.querySelector('.bolsa-linebar > span');
            if (bar) { bar.style.transition = 'none'; bar.style.width = '0%'; }
        });
        void document.body.offsetWidth; // confirma el reset antes de reactivar transiciones
        cards.forEach(card => {
            const ring = card.querySelector('.g-value');
            if (ring) { ring.style.transition = ''; }
            const bar = card.querySelector('.bolsa-linebar > span');
            if (bar) { bar.style.transition = ''; }
        });

        function countUp(el, to) {
            const start = performance.now(), dur = 1000;
            function tick(now) {
                const t = Math.min((now - start) / dur, 1);
                el.textContent = fmtH(to * (1 - Math.pow(1 - t, 3))); // easeOutCubic
                if (t < 1) { requestAnimationFrame(tick); } else { el.textContent = fmtH(to); }
            }
            requestAnimationFrame(tick);
        }

        function reveal(card) {
            const ring = card.querySelector('.g-value');
            if (ring) { ring.style.strokeDashoffset = ring.dataset.offset || '0'; }
            const bar = card.querySelector('.bolsa-linebar > span');
            if (bar) { bar.style.width = (bar.dataset.width || '0') + '%'; }
            card.querySelectorAll('.js-count').forEach(el => countUp(el, parseFloat(el.dataset.to || '0')));
        }

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries, obs) => {
                entries.forEach(e => { if (e.isIntersecting) { reveal(e.target); obs.unobserve(e.target); } });
            }, { threshold: 0.2 });
            cards.forEach(c => io.observe(c));
        } else {
            cards.forEach(reveal);
        }

        // Red de seguridad: si rAF nunca corre (p. ej. pestaña pintada en segundo plano), fija el estado final.
        setTimeout(() => cards.forEach(setFinal), 1600);
    })();
</script>

<script>
    /* Carrusel de banners de servicios: autoavance + dots + flechas, pausa al pasar el mouse. */
    (function () {
        var car = document.getElementById('clibnCarousel');
        if (!car) { return; }
        var slides = car.querySelectorAll('.clibn-slide');
        var dots = car.querySelectorAll('.clibn-dot');
        if (slides.length < 2) { return; }

        var idx = 0, timer = null, DELAY = 6000;
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function show(n) {
            idx = (n + slides.length) % slides.length;
            slides.forEach(function (s, i) { s.classList.toggle('is-active', i === idx); });
            dots.forEach(function (d, i) { d.classList.toggle('is-active', i === idx); });
        }
        function start() { if (!reduce) { timer = setInterval(function () { show(idx + 1); }, DELAY); } }
        function stop() { if (timer) { clearInterval(timer); timer = null; } }
        function restart() { stop(); start(); }

        window.clibnGo = function (dir) { show(idx + dir); restart(); };
        window.clibnJump = function (n) { show(n); restart(); };

        car.addEventListener('mouseenter', stop);
        car.addEventListener('mouseleave', start);
        start();
    })();
</script>
</body>
</html>
