<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard · {{ $developer->nombre }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --purple: #7c3aed;
            --grad: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
            --grad-dark: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%);
            --shadow: 0 4px 15px rgba(0,0,0,0.06);
            --shadow-lg: 0 20px 50px -10px rgba(124, 58, 237, 0.25);
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; }
        .wrap { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; }

        /* === HEADER === */
        .head-bar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem 1.25rem; background: white; border-radius: 14px;
            box-shadow: var(--shadow); margin-bottom: 1rem; gap: 1rem; flex-wrap: wrap;
        }
        .head-info { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
        .head-avatar { width: 44px; height: 44px; border-radius: 50%; background: var(--grad); color: white; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; flex-shrink: 0; }
        .head-name { font-size: 1rem; font-weight: 800; color: #0f172a; line-height: 1.1; }
        .head-sub { font-size: 0.78rem; color: #94a3b8; margin-top: 0.15rem; }
        .btn-logout { background: rgba(124,58,237,0.08); border: none; color: var(--purple); padding: 0.5rem 0.95rem; border-radius: 10px; font-weight: 700; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: all 0.2s; }
        .btn-logout:hover { background: var(--purple); color: white; }

        /* === HERO MES === */
        .month-hero {
            position: relative; overflow: hidden;
            background: var(--grad-dark); color: white;
            border-radius: 20px; padding: 2rem 2rem 1.75rem;
            margin-bottom: 1.25rem; box-shadow: var(--shadow-lg);
        }
        .month-hero::before {
            content: ''; position: absolute; top: -50%; right: -20%;
            width: 60%; height: 200%;
            background: radial-gradient(circle, rgba(167, 139, 250, 0.35) 0%, transparent 70%);
            pointer-events: none;
        }
        .month-hero-tag {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.35rem 0.85rem; border-radius: 999px;
            background: rgba(255,255,255,0.14); backdrop-filter: blur(8px);
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
            margin-bottom: 0.85rem; border: 1px solid rgba(255,255,255,0.18);
        }
        .month-hero-tag .dot { width: 7px; height: 7px; border-radius: 50%; background: #34d399; box-shadow: 0 0 0 3px rgba(52,211,153,0.25); animation: dotPulse 2s ease-in-out infinite; }
        @keyframes dotPulse { 0%,100% { box-shadow: 0 0 0 3px rgba(52,211,153,0.25); } 50% { box-shadow: 0 0 0 6px rgba(52,211,153,0); } }
        .month-hero-label { font-size: 0.95rem; opacity: 0.85; font-weight: 500; text-transform: capitalize; margin-bottom: 0.4rem; }
        .month-hero-amount {
            font-size: 3.2rem; font-weight: 800; line-height: 1; letter-spacing: -0.03em;
            text-shadow: 0 4px 20px rgba(0,0,0,0.18);
        }
        .month-hero-meta {
            margin-top: 1.1rem; display: flex; gap: 1.25rem; flex-wrap: wrap;
            font-size: 0.85rem; opacity: 0.9;
        }
        .month-hero-meta i { margin-right: 0.35rem; opacity: 0.75; }

        /* === SELECTOR DE MESES (chips horizontales) === */
        .month-selector {
            background: white; border-radius: 14px; padding: 0.85rem 1rem;
            box-shadow: var(--shadow); margin-bottom: 1.25rem; overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .month-selector-track { display: flex; gap: 0.45rem; min-width: max-content; }
        .month-chip {
            padding: 0.55rem 0.85rem; border-radius: 10px;
            background: #f1f5f9; color: #475569;
            text-decoration: none; font-size: 0.78rem; font-weight: 700;
            text-transform: capitalize; white-space: nowrap;
            display: inline-flex; flex-direction: column; align-items: center;
            gap: 0.1rem; min-width: 78px; border: 1px solid transparent;
            transition: all 0.2s;
        }
        .month-chip:hover { background: #e2e8f0; color: #0f172a; text-decoration: none; }
        .month-chip.active { background: var(--purple); color: white; border-color: var(--purple); box-shadow: 0 4px 14px rgba(124,58,237,0.3); }
        .month-chip.current::after { content: '·'; position: absolute; }
        .month-chip-amount { font-size: 0.68rem; font-weight: 600; opacity: 0.75; margin-top: 0.1rem; }

        /* === KPIs SECUNDARIOS === */
        .kpi-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.85rem; margin-bottom: 1.25rem; }
        .kpi-mini { background: white; border-radius: 14px; padding: 1rem 1.1rem; box-shadow: var(--shadow); border-left: 3px solid var(--purple); }
        .kpi-mini.green { border-left-color: #059669; }
        .kpi-mini.amber { border-left-color: #f59e0b; }
        .kpi-mini.red { border-left-color: #dc2626; }
        .kpi-mini.red .kpi-mini-value { color: #dc2626; }
        .kpi-mini-label { font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.35rem; }
        .kpi-mini-value { font-size: 1.25rem; font-weight: 800; color: #0f172a; line-height: 1.1; }
        .kpi-mini-sub { font-size: 0.72rem; color: #94a3b8; margin-top: 0.2rem; }

        /* === SECCIONES (cards) === */
        .section { background: white; border-radius: 16px; padding: 1.4rem 1.5rem; box-shadow: var(--shadow); margin-bottom: 1.25rem; }
        .section-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem; }
        .section-head h3 { font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; color: #0f172a; letter-spacing: -0.01em; }
        .section-head h3 .icon-circle { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 8px; background: rgba(124,58,237,0.1); color: var(--purple); font-size: 0.85rem; }
        .section-head h3 .icon-circle.green { background: rgba(5,150,105,0.1); color: #059669; }
        .section-head .muted { font-size: 0.78rem; color: #94a3b8; font-weight: 500; }
        .section-head .badge { background: rgba(124,58,237,0.1); color: var(--purple); padding: 0.2rem 0.55rem; border-radius: 8px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }

        /* === GRID DE PROYECTOS === */
        .proj-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 0.85rem; }
        .proj-card { background: #fafbfc; border: 1px solid #f1f3f5; border-radius: 12px; padding: 1rem 1.1rem; transition: all 0.25s; position: relative; }
        .proj-card:hover { border-color: rgba(124,58,237,0.2); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(124,58,237,0.08); }
        .proj-card.is-pendiente { border-left: 3px solid #dc2626; background: #fff8f8; }
        .proj-card.is-pendiente:hover { border-color: rgba(220,38,38,0.4); border-left-color: #dc2626; box-shadow: 0 6px 18px rgba(220,38,38,0.1); }
        .proj-card.is-completo { border-left: 3px solid #059669; }

        .proj-status { margin-bottom: 0.5rem; }
        .status-pill { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.22rem 0.6rem; border-radius: 999px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
        .status-pill i { font-size: 0.65rem; }
        .status-cobrado { background: rgba(5,150,105,0.12); color: #059669; }
        .status-pendiente { background: rgba(220,38,38,0.12); color: #dc2626; }
        .status-completo { background: rgba(5,150,105,0.12); color: #059669; }
        .proj-name { font-size: 0.95rem; font-weight: 700; color: #0f172a; margin-bottom: 0.2rem; line-height: 1.25; }
        .proj-cliente { font-size: 0.78rem; color: #94a3b8; margin-bottom: 0.65rem; }
        .proj-amounts { display: flex; gap: 1rem; padding: 0.65rem 0; border-top: 1px dashed #e2e8f0; border-bottom: 1px dashed #e2e8f0; flex-wrap: wrap; }
        .proj-amount { flex: 1; min-width: 80px; }
        .proj-amount-label { font-size: 0.65rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.3px; margin-bottom: 0.15rem; }
        .proj-amount-value { font-size: 0.95rem; font-weight: 800; color: #0f172a; }
        .proj-amount-value.green { color: #059669; }
        .proj-amount-value.purple { color: var(--purple); }
        .proj-amount-value.amber { color: #d97706; }
        .proj-amount-value.red { color: #dc2626; }
        .proj-bar { height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 0.65rem; }
        .proj-bar-fill { height: 100%; background: var(--grad); border-radius: 3px; transition: width 0.6s ease; }
        .proj-foot { font-size: 0.72rem; color: #94a3b8; margin-top: 0.5rem; display: flex; justify-content: space-between; }
        .est-pill { padding: 0.12rem 0.5rem; border-radius: 6px; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; display: inline-block; margin-left: 0.4rem; }
        .recurrente-pill { background: rgba(124,58,237,0.12); color: var(--purple); padding: 0.12rem 0.5rem; border-radius: 6px; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; display: inline-block; margin-left: 0.35rem; }

        /* === HISTÓRICO BARS === */
        .history-bars { display: grid; grid-template-columns: repeat(12, 1fr); gap: 0.4rem; align-items: end; height: 140px; padding: 0.75rem 0; }
        .history-bar-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; height: 100%; cursor: pointer; text-decoration: none; }
        .history-bar-wrap:hover .history-bar { background: var(--purple); }
        .history-bar-amount { font-size: 0.62rem; font-weight: 700; color: #0f172a; opacity: 0; transition: opacity 0.2s; min-height: 14px; }
        .history-bar-wrap:hover .history-bar-amount { opacity: 1; }
        .history-bar-wrap.has-data .history-bar-amount { opacity: 0.65; }
        .history-bar { flex: 1; width: 100%; background: linear-gradient(180deg, #c4b5fd, #a78bfa); border-radius: 6px 6px 2px 2px; transition: all 0.3s ease; min-height: 4px; }
        .history-bar-wrap.is-current .history-bar { background: linear-gradient(180deg, #34d399, #059669); }
        .history-bar-wrap.is-selected .history-bar { background: linear-gradient(180deg, #1e1b4b, #4c1d95); transform: scaleY(1.02); }
        .history-bar-label { font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: capitalize; }
        .history-bar-wrap.is-selected .history-bar-label { color: var(--purple); }

        /* === HISTORIAL DE PAGOS AGRUPADO === */
        .month-group { margin-bottom: 1.25rem; }
        .month-group:last-child { margin-bottom: 0; }
        .month-group-head {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.65rem 0.85rem; background: #f8fafc; border-radius: 10px;
            margin-bottom: 0.5rem; font-size: 0.85rem; font-weight: 700;
            color: #0f172a; text-transform: capitalize;
        }
        .month-group-total { color: #059669; font-weight: 800; }
        .pay-row {
            display: grid; grid-template-columns: 90px 1fr auto; gap: 0.85rem;
            padding: 0.7rem 0.85rem; border-bottom: 1px solid #f1f3f5; align-items: center;
        }
        .pay-row:last-child { border-bottom: none; }
        .pay-date { font-size: 0.78rem; color: #475569; font-weight: 600; white-space: nowrap; }
        .pay-info { min-width: 0; }
        .pay-proj { font-size: 0.88rem; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .pay-meta { font-size: 0.72rem; color: #94a3b8; margin-top: 0.15rem; }
        .pay-amount { font-size: 0.95rem; font-weight: 800; color: #059669; white-space: nowrap; text-align: right; }

        /* === EMPTY === */
        .empty { text-align: center; padding: 2.5rem 1rem; color: #94a3b8; }
        .empty i { display: block; font-size: 2.2rem; color: #cbd5e1; margin-bottom: 0.6rem; }
        .empty p { font-size: 0.9rem; }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .wrap { padding: 1rem; }
            .month-hero { padding: 1.5rem 1.4rem; }
            .month-hero-amount { font-size: 2.4rem; }
            .month-hero-meta { gap: 0.75rem; font-size: 0.78rem; }
            .head-bar { padding: 0.85rem 1rem; }
            .head-name { font-size: 0.92rem; }
            .section { padding: 1.1rem 1.1rem; }
            .pay-row { grid-template-columns: 70px 1fr auto; gap: 0.5rem; padding: 0.6rem 0.5rem; }
            .pay-date { font-size: 0.72rem; }
            .pay-proj { font-size: 0.82rem; }
            .pay-amount { font-size: 0.85rem; }
            .history-bars { height: 110px; gap: 0.25rem; }
            .history-bar-label { font-size: 0.55rem; }
            .history-bar-amount { display: none; }
        }
    </style>
</head>
<body>
@php
    $fmtCop = fn ($v) => '$' . number_format((float) $v, 0, ',', '.');
    $fmtMoneda = fn ($v, $m) => ($m === 'USD' ? 'US$' : '$') . number_format((float) $v, 0, ',', '.') . ($m === 'USD' ? ' USD' : '');
    $initials = collect(explode(' ', trim($developer->nombre)))->map(fn ($n) => mb_substr($n, 0, 1))->take(2)->implode('');
    $initials = $initials ? mb_strtoupper($initials) : '·';
@endphp
<div class="wrap">

    {{-- ===== HEADER MINI ===== --}}
    <div class="head-bar">
        <div class="head-info">
            <div class="head-avatar">{{ $initials }}</div>
            <div>
                <div class="head-name">{{ $developer->nombre }}</div>
                <div class="head-sub">
                    @if($kpis['desde'])Desde {{ $kpis['desde']->locale('es')->isoFormat('MMM YYYY') }} · @endif
                    {{ $kpis['proyectos_total'] }} proyectos
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('portal.developer.logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Salir</button>
        </form>
    </div>

    {{-- ===== HERO DEL MES ===== --}}
    <div class="month-hero">
        <span class="month-hero-tag">
            @if($isCurrentMonth)<span class="dot"></span> Mes en curso @else <i class="fas fa-history"></i> Historial @endif
        </span>
        <div class="month-hero-label">{{ $kpis['mes_label'] }}</div>
        <div class="month-hero-amount">{{ $fmtCop($kpis['mes_total_cop']) }}</div>
        <div class="month-hero-meta">
            <span><i class="fas fa-paper-plane"></i> {{ $kpis['mes_pagos_count'] }} {{ $kpis['mes_pagos_count'] == 1 ? 'pago recibido' : 'pagos recibidos' }}</span>
            <span><i class="fas fa-briefcase"></i> {{ $kpis['mes_proyectos_count'] }} {{ $kpis['mes_proyectos_count'] == 1 ? 'proyecto' : 'proyectos' }}</span>
            @if($kpis['ultimo_pago'])
                <span><i class="fas fa-clock"></i> Último pago: {{ $kpis['ultimo_pago']->locale('es')->isoFormat('DD MMM') }}</span>
            @endif
        </div>
    </div>

    {{-- ===== SELECTOR DE MESES ===== --}}
    <div class="month-selector">
        <div class="month-selector-track">
            @foreach($historico as $mes)
                <a href="{{ route('portal.developer.dashboard', ['mes' => $mes['key']]) }}"
                   class="month-chip {{ $mes['is_selected'] ? 'active' : '' }} {{ $mes['is_current'] ? 'current' : '' }}"
                   title="{{ $mes['label_full'] }}">
                    {{ $mes['label_short'] }}
                    <span class="month-chip-amount">{{ $mes['total'] > 0 ? '$' . number_format($mes['total'] / 1000, 0) . 'k' : '—' }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ===== KPIs SECUNDARIOS (lifetime) ===== --}}
    <div class="kpi-row">
        <div class="kpi-mini green">
            <div class="kpi-mini-label"><i class="fas fa-coins"></i> Total ganado</div>
            <div class="kpi-mini-value">{{ $fmtCop($kpis['lifetime_cop']) }}</div>
            <div class="kpi-mini-sub">desde el inicio</div>
        </div>
        <div class="kpi-mini red">
            <div class="kpi-mini-label"><i class="fas fa-hourglass-half"></i> Por cobrar</div>
            <div class="kpi-mini-value">{{ $fmtCop($kpis['pendiente_total_cop']) }}</div>
            <div class="kpi-mini-sub">{{ $kpis['pendientes_count'] }} {{ $kpis['pendientes_count'] == 1 ? 'proyecto pendiente' : 'proyectos pendientes' }}</div>
        </div>
        <div class="kpi-mini">
            <div class="kpi-mini-label"><i class="fas fa-briefcase"></i> Proyectos</div>
            <div class="kpi-mini-value">{{ $kpis['proyectos_total'] }}</div>
            <div class="kpi-mini-sub">{{ $kpis['proyectos_activos'] }} activos · {{ $kpis['proyectos_completados'] }} completados</div>
        </div>
        <div class="kpi-mini amber">
            <div class="kpi-mini-label"><i class="fas fa-sync-alt"></i> Recurrentes</div>
            <div class="kpi-mini-value">{{ $kpis['recurrentes_count'] }}</div>
            <div class="kpi-mini-sub">se renuevan cada mes</div>
        </div>
    </div>

    {{-- ===== PROYECTOS RECURRENTES (en el mes seleccionado) ===== --}}
    @if($resumenRecurrentes->count() > 0)
    <div class="section">
        <div class="section-head">
            <h3>
                <span class="icon-circle"><i class="fas fa-sync-alt"></i></span>
                Proyectos recurrentes
                <span class="badge">{{ $kpis['mes_label_short'] }}</span>
            </h3>
            <span class="muted">Pago mensual fijo · {{ $resumenRecurrentes->count() }}</span>
        </div>
        <div class="proj-grid">
            @foreach($resumenRecurrentes as $r)
                <div class="proj-card">
                    <div class="proj-name">
                        {{ $r['nombre'] }}
                        <span class="recurrente-pill"><i class="fas fa-sync-alt"></i> Mensual</span>
                    </div>
                    <div class="proj-cliente"><i class="fas fa-user" style="color:#cbd5e1; font-size:0.7rem;"></i> {{ $r['cliente'] }}
                        <span class="est-pill" style="background: {{ $r['estado_color'] }}15; color: {{ $r['estado_color'] }};">{{ $r['estado_label'] }}</span>
                    </div>
                    <div class="proj-amounts">
                        <div class="proj-amount">
                            <div class="proj-amount-label">Pago / mes</div>
                            <div class="proj-amount-value purple">{{ $fmtMoneda($r['asignado_mensual'], $r['moneda']) }}</div>
                        </div>
                        <div class="proj-amount">
                            <div class="proj-amount-label">Cobrado este mes</div>
                            <div class="proj-amount-value green">{{ $fmtMoneda($r['cobrado_mes'], $r['moneda']) }}</div>
                        </div>
                        @if($r['pendiente_mes'] > 0)
                        <div class="proj-amount">
                            <div class="proj-amount-label">Pendiente</div>
                            <div class="proj-amount-value amber">{{ $fmtMoneda($r['pendiente_mes'], $r['moneda']) }}</div>
                        </div>
                        @endif
                    </div>
                    @if($r['asignado_mensual'] > 0)
                        <div class="proj-bar"><div class="proj-bar-fill" style="width: {{ $r['pct'] }}%; @if($r['pct'] >= 100) background: linear-gradient(135deg, #34d399, #059669); @endif"></div></div>
                    @endif
                    <div class="proj-foot">
                        <span>{{ $r['pagos_mes_count'] }} {{ $r['pagos_mes_count'] == 1 ? 'pago' : 'pagos' }} este mes</span>
                        <span>{{ $r['pct'] }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== PROYECTOS NO-RECURRENTES (con pago en mes O con saldo pendiente) ===== --}}
    @if($resumenOneShot->count() > 0)
    @php
        $countCobrados = $resumenOneShot->where('tiene_pago_mes', true)->count();
        $countPendientes = $resumenOneShot->where('status', 'pendiente')->count();
    @endphp
    <div class="section">
        <div class="section-head">
            <h3>
                <span class="icon-circle green"><i class="fas fa-folder-open"></i></span>
                Proyectos
            </h3>
            <span class="muted">
                @if($countCobrados > 0){{ $countCobrados }} cobrados este mes@endif
                @if($countCobrados > 0 && $countPendientes > 0) · @endif
                @if($countPendientes > 0)<strong style="color:#dc2626;">{{ $countPendientes }} pendientes de pago</strong>@endif
            </span>
        </div>
        <div class="proj-grid">
            @foreach($resumenOneShot as $o)
                @php
                    $isPendiente = $o['status'] === 'pendiente';
                    $isCompleto = $o['status'] === 'completo';
                @endphp
                <div class="proj-card {{ $isPendiente ? 'is-pendiente' : '' }} {{ $isCompleto ? 'is-completo' : '' }}">
                    <div class="proj-status">
                        @if($o['status'] === 'cobrado_mes')
                            <span class="status-pill status-cobrado"><i class="fas fa-check-circle"></i> Cobrado este mes</span>
                        @elseif($isCompleto)
                            <span class="status-pill status-completo"><i class="fas fa-check-double"></i> Completado</span>
                        @else
                            <span class="status-pill status-pendiente"><i class="fas fa-hourglass-half"></i> Por cobrar</span>
                        @endif
                    </div>
                    <div class="proj-name">{{ $o['nombre'] }}</div>
                    <div class="proj-cliente"><i class="fas fa-user" style="color:#cbd5e1; font-size:0.7rem;"></i> {{ $o['cliente'] }}
                        <span class="est-pill" style="background: {{ $o['estado_color'] }}15; color: {{ $o['estado_color'] }};">{{ $o['estado_label'] }}</span>
                    </div>
                    <div class="proj-amounts">
                        <div class="proj-amount">
                            <div class="proj-amount-label">Pago total</div>
                            <div class="proj-amount-value purple">{{ $fmtMoneda($o['asignado'], $o['moneda']) }}</div>
                        </div>
                        @if($o['tiene_pago_mes'])
                        <div class="proj-amount">
                            <div class="proj-amount-label">Cobrado este mes</div>
                            <div class="proj-amount-value green">{{ $fmtMoneda($o['cobrado_mes'], $o['moneda']) }}</div>
                        </div>
                        @endif
                        @if($o['pendiente'] > 0)
                        <div class="proj-amount">
                            <div class="proj-amount-label">{{ $isPendiente ? 'Te deben' : 'Falta cobrar' }}</div>
                            <div class="proj-amount-value red">{{ $fmtMoneda($o['pendiente'], $o['moneda']) }}</div>
                        </div>
                        @endif
                    </div>
                    @if($o['asignado'] > 0)
                        <div class="proj-bar"><div class="proj-bar-fill" style="width: {{ $o['pct'] }}%; @if($o['pct'] >= 100) background: linear-gradient(135deg, #34d399, #059669); @endif"></div></div>
                    @endif
                    <div class="proj-foot">
                        <span>{{ $o['pagos_total_count'] }} {{ $o['pagos_total_count'] == 1 ? 'pago' : 'pagos' }} en total</span>
                        <span>{{ $o['pct'] }}% pagado</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== EMPTY STATE DEL MES ===== --}}
    @if($resumenRecurrentes->count() === 0 && $resumenOneShot->count() === 0)
    <div class="section">
        <div class="empty">
            <i class="fas fa-inbox"></i>
            <p>No hay pagos registrados en {{ $kpis['mes_label'] }}.</p>
        </div>
    </div>
    @endif

    {{-- ===== HISTÓRICO DE 12 MESES (gráfico de barras) ===== --}}
    <div class="section">
        <div class="section-head">
            <h3>
                <span class="icon-circle"><i class="fas fa-chart-bar"></i></span>
                Histórico mensual
            </h3>
            <span class="muted">Últimos 12 meses · clic para ver detalle</span>
        </div>
        <div class="history-bars">
            @foreach($historico as $mes)
                <a href="{{ route('portal.developer.dashboard', ['mes' => $mes['key']]) }}"
                   class="history-bar-wrap {{ $mes['is_current'] ? 'is-current' : '' }} {{ $mes['is_selected'] ? 'is-selected' : '' }} {{ $mes['total'] > 0 ? 'has-data' : '' }}"
                   title="{{ $mes['label_full'] }}: {{ $fmtCop($mes['total']) }} ({{ $mes['count'] }} pagos)">
                    <span class="history-bar-amount">{{ $mes['total'] > 0 ? '$' . number_format($mes['total'] / 1000, 0) . 'k' : '' }}</span>
                    <div class="history-bar" style="height: {{ max(($mes['total'] / $maxHistorico) * 100, 3) }}%;"></div>
                    <span class="history-bar-label">{{ $mes['label_short'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- ===== HISTORIAL COMPLETO DE PAGOS (agrupado por mes) ===== --}}
    @if($paymentsAgrupados->count() > 0)
    <div class="section">
        <div class="section-head">
            <h3>
                <span class="icon-circle green"><i class="fas fa-list-alt"></i></span>
                Historial completo de pagos
            </h3>
            <span class="muted">Todos los pagos recibidos</span>
        </div>
        @foreach($paymentsAgrupados as $grupo)
            <div class="month-group">
                <div class="month-group-head">
                    <span style="text-transform:capitalize;">{{ $grupo['label'] }}</span>
                    <span class="month-group-total">{{ $fmtCop($grupo['total_cop']) }}</span>
                </div>
                @foreach($grupo['pagos'] as $pay)
                    <div class="pay-row">
                        <div class="pay-date">{{ $pay->fecha->format('d/m/Y') }}</div>
                        <div class="pay-info">
                            <div class="pay-proj">{{ optional($pay->project)->nombre ?? '—' }}
                                @if($pay->project && $pay->project->es_recurrente)
                                    <span class="recurrente-pill" style="font-size:0.6rem;"><i class="fas fa-sync-alt"></i> Mensual</span>
                                @endif
                            </div>
                            <div class="pay-meta">
                                {{ optional($pay->project)->cliente_nombre }}
                                @if($pay->metodo) · {{ $pay->metodo }}@endif
                                @if($pay->referencia) · {{ $pay->referencia }}@endif
                            </div>
                        </div>
                        <div class="pay-amount">+{{ $fmtMoneda($pay->monto, $pay->moneda ?? 'COP') }}</div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    @endif
</div>
</body>
</html>
