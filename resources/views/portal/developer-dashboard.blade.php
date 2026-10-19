<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel · {{ $developer->nombre }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #f7f7fb;
            --surface: #fdfdff;
            --ink: #23222e;
            --ink-soft: #6a6878;
            --ink-faint: #9b99a8;
            --line: #ececf2;
            --line-strong: #e0dfe9;
            --accent: #6d3fd4;
            --accent-ink: #5a30b8;
            --accent-soft: #f1ecfc;
            --danger: #d1314f;
            --danger-soft: #fcebee;
            --warn: #b9770f;
            --warn-soft: #fbf2e0;
            --ok: #18996a;
            --ok-soft: #e7f5ef;
            --radius: 16px;
            --shadow: 0 1px 2px rgba(25, 22, 48, 0.05);
            --ease: cubic-bezier(0.22, 1, 0.36, 1);
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, Roboto, sans-serif;
            background: var(--bg); color: var(--ink);
            min-height: 100vh; line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        .wrap { max-width: 920px; margin: 0 auto; padding: 1.75rem 1.25rem 4rem; }
        a { color: inherit; }

        /* ===== HEADER ===== */
        .head {
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem; margin-bottom: 1.5rem;
        }
        .head-info { display: flex; align-items: center; gap: 0.8rem; min-width: 0; }
        .head-avatar {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--accent-soft); color: var(--accent-ink);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; font-weight: 600; flex-shrink: 0;
        }
        .head-name { font-size: 1.05rem; font-weight: 600; letter-spacing: -0.01em; }
        .head-sub { font-size: 0.8rem; color: var(--ink-faint); margin-top: 0.1rem; }
        .btn-ghost {
            background: transparent; border: 1px solid var(--line-strong); color: var(--ink-soft);
            padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 500; font-size: 0.82rem;
            cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem;
            transition: all 0.18s var(--ease); font-family: inherit;
        }
        .btn-ghost:hover { border-color: var(--ink-faint); color: var(--ink); }

        /* ===== SEGMENTED TABS ===== */
        .tabs {
            display: flex; gap: 0.25rem; background: #eeeef4; padding: 0.3rem;
            border-radius: 13px; margin-bottom: 1.75rem; max-width: 420px;
        }
        .tab-btn {
            flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.6rem 0.9rem; border: none; background: transparent;
            color: var(--ink-soft); font-weight: 600; font-size: 0.88rem;
            border-radius: 10px; cursor: pointer; transition: all 0.18s var(--ease);
            font-family: inherit;
        }
        .tab-btn:hover { color: var(--ink); }
        .tab-btn .tab-count {
            font-size: 0.72rem; background: #dcdce6; color: var(--ink-soft);
            padding: 0.05rem 0.45rem; border-radius: 999px; font-weight: 600;
            font-variant-numeric: tabular-nums;
        }
        .tab-btn.active {
            background: var(--surface); color: var(--ink);
            box-shadow: 0 1px 3px rgba(25, 22, 48, 0.08);
        }
        .tab-btn.active .tab-count { background: var(--accent-soft); color: var(--accent-ink); }
        .tab-btn.alert:not(.active) .tab-count { background: var(--danger-soft); color: var(--danger); }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ===== SECTION ===== */
        .section { margin-bottom: 2.25rem; }
        .section-head {
            display: flex; justify-content: space-between; align-items: baseline;
            margin-bottom: 0.9rem; gap: 0.75rem;
        }
        .section-title { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--ink-faint); }
        .section-link { font-size: 0.8rem; font-weight: 600; color: var(--accent); text-decoration: none; }
        .section-link:hover { color: var(--accent-ink); }

        /* ===== MI DÍA (3 stats) ===== */
        .day-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; margin-bottom: 1rem; }
        .day-stat {
            background: var(--surface); border: 1px solid var(--line); border-radius: 14px;
            padding: 1.15rem 1.1rem; box-shadow: var(--shadow);
        }
        .day-stat-num { font-size: 2rem; font-weight: 700; line-height: 1; letter-spacing: -0.03em; font-variant-numeric: tabular-nums; }
        .day-stat.has-late .day-stat-num { color: var(--danger); }
        .day-stat.has-today .day-stat-num { color: var(--warn); }
        .day-stat-label { font-size: 0.78rem; color: var(--ink-soft); font-weight: 500; margin-top: 0.5rem; }
        .day-stat-label i { margin-right: 0.35rem; color: var(--ink-faint); font-size: 0.78rem; }

        /* ===== STATUS STRIP ===== */
        .status-strip {
            display: flex; align-items: stretch; background: var(--surface);
            border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow);
            overflow: hidden;
        }
        .status-seg { flex: 1; padding: 0.85rem 0.6rem; text-align: center; border-right: 1px solid var(--line); }
        .status-seg:last-child { border-right: none; }
        .status-seg-num { font-size: 1.15rem; font-weight: 700; font-variant-numeric: tabular-nums; }
        .status-seg-num.muted { color: var(--ink-faint); }
        .status-seg-label { font-size: 0.68rem; color: var(--ink-faint); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; margin-top: 0.2rem; }

        /* ===== FOCO LIST ===== */
        .panel { background: var(--surface); border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; }
        .foco-item { display: flex; align-items: center; gap: 0.8rem; padding: 0.85rem 1.1rem; border-top: 1px solid var(--line); text-decoration: none; transition: background 0.15s; }
        .foco-item:first-child { border-top: none; }
        .foco-item:hover { background: #fafafd; }
        .prio-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .prio-dot.alta { background: var(--danger); } .prio-dot.media { background: var(--accent); } .prio-dot.baja { background: var(--ink-faint); }
        .foco-body { flex: 1; min-width: 0; }
        .foco-title { font-size: 0.9rem; font-weight: 500; color: var(--ink); line-height: 1.3; }
        .foco-meta { font-size: 0.76rem; color: var(--ink-faint); margin-top: 0.1rem; }
        .due-badge { font-size: 0.72rem; font-weight: 600; padding: 0.22rem 0.6rem; border-radius: 8px; white-space: nowrap; }
        .due-badge.late { background: var(--danger-soft); color: var(--danger); }
        .due-badge.today { background: var(--warn-soft); color: var(--warn); }

        /* ===== BOARDS ===== */
        .boards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 0.85rem; }
        .board {
            display: block; background: var(--surface); border: 1px solid var(--line);
            border-radius: 14px; padding: 1.1rem 1.2rem; box-shadow: var(--shadow);
            text-decoration: none; transition: border-color 0.18s var(--ease), transform 0.18s var(--ease);
        }
        .board:hover { border-color: var(--line-strong); transform: translateY(-2px); }
        .board-name { font-size: 0.95rem; font-weight: 600; color: var(--ink); line-height: 1.3; }
        .board-cli { font-size: 0.76rem; color: var(--ink-faint); margin-top: 0.15rem; }
        .board-stat { display: flex; align-items: baseline; gap: 0.4rem; margin-top: 0.9rem; }
        .board-stat-num { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; font-variant-numeric: tabular-nums; }
        .board-stat-txt { font-size: 0.78rem; color: var(--ink-soft); }
        .board-late { font-size: 0.74rem; font-weight: 600; color: var(--danger); margin-top: 0.3rem; }
        .board-late i { font-size: 0.7rem; }
        .board-bar { height: 4px; background: #edecf3; border-radius: 2px; overflow: hidden; margin-top: 0.9rem; }
        .board-bar > span { display: block; height: 100%; background: var(--accent); border-radius: 2px; }
        .board-foot { display: flex; justify-content: space-between; font-size: 0.74rem; color: var(--ink-faint); margin-top: 0.55rem; font-variant-numeric: tabular-nums; }

        /* ===== PAGOS: resumen del mes ===== */
        .pay-summary {
            background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius);
            box-shadow: var(--shadow); padding: 1.5rem 1.6rem; margin-bottom: 1.5rem;
            display: flex; justify-content: space-between; align-items: flex-end; gap: 1rem; flex-wrap: wrap;
        }
        .pay-summary-tag { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--ink-faint); margin-bottom: 0.5rem; }
        .pay-summary-tag .live { width: 7px; height: 7px; border-radius: 50%; background: var(--ok); }
        .pay-summary-month { font-size: 0.9rem; color: var(--ink-soft); text-transform: capitalize; margin-bottom: 0.25rem; }
        .pay-summary-amount { font-size: 2.6rem; font-weight: 700; line-height: 1; letter-spacing: -0.03em; color: var(--ink); font-variant-numeric: tabular-nums; }
        .pay-summary-meta { font-size: 0.8rem; color: var(--ink-soft); text-align: right; }
        .pay-summary-meta div { margin-bottom: 0.25rem; }
        .pay-summary-meta i { color: var(--ink-faint); margin-right: 0.3rem; width: 14px; text-align: center; }

        /* ===== MONTH SELECTOR ===== */
        .months { display: flex; gap: 0.4rem; overflow-x: auto; padding-bottom: 0.4rem; margin-bottom: 1.75rem; -webkit-overflow-scrolling: touch; }
        .month-chip {
            padding: 0.5rem 0.8rem; border-radius: 10px; background: var(--surface);
            border: 1px solid var(--line); color: var(--ink-soft); text-decoration: none;
            font-size: 0.76rem; font-weight: 600; text-transform: capitalize; white-space: nowrap;
            display: flex; flex-direction: column; align-items: center; gap: 0.1rem; min-width: 72px;
            transition: all 0.15s var(--ease);
        }
        .month-chip:hover { border-color: var(--line-strong); color: var(--ink); }
        .month-chip.active { background: var(--accent); border-color: var(--accent); color: #fff; }
        .month-chip-amt { font-size: 0.66rem; font-weight: 500; opacity: 0.7; font-variant-numeric: tabular-nums; }

        /* ===== PAY KPIs ===== */
        .pay-kpis { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 0.85rem; margin-bottom: 2.25rem; }
        .pay-kpi { background: var(--surface); border: 1px solid var(--line); border-radius: 14px; padding: 1.05rem 1.2rem; box-shadow: var(--shadow); }
        .pay-kpi-label { font-size: 0.72rem; color: var(--ink-faint); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.4rem; }
        .pay-kpi-label i { margin-right: 0.35rem; }
        .pay-kpi-value { font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em; font-variant-numeric: tabular-nums; }
        .pay-kpi-value.danger { color: var(--danger); }
        .pay-kpi-value.ok { color: var(--ok); }
        .pay-kpi-sub { font-size: 0.74rem; color: var(--ink-faint); margin-top: 0.2rem; }

        /* ===== PROJECT CARDS (pagos) ===== */
        .proj-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 0.85rem; }
        .proj {
            background: var(--surface); border: 1px solid var(--line); border-radius: 14px;
            padding: 1.15rem 1.25rem; box-shadow: var(--shadow); transition: border-color 0.18s;
        }
        .proj:hover { border-color: var(--line-strong); }
        .proj.pendiente { background: #fffbfb; border-color: #f4dfe3; }
        .proj-pill { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; padding: 0.2rem 0.55rem; border-radius: 999px; margin-bottom: 0.65rem; }
        .proj-pill.ok { background: var(--ok-soft); color: var(--ok); }
        .proj-pill.pend { background: var(--danger-soft); color: var(--danger); }
        .proj-name { font-size: 0.95rem; font-weight: 600; color: var(--ink); line-height: 1.3; }
        .proj-cli { font-size: 0.76rem; color: var(--ink-faint); margin: 0.2rem 0 0.8rem; display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
        .est { font-size: 0.64rem; font-weight: 600; padding: 0.1rem 0.45rem; border-radius: 6px; }
        .tag-mensual { font-size: 0.64rem; font-weight: 600; color: var(--accent-ink); background: var(--accent-soft); padding: 0.1rem 0.45rem; border-radius: 6px; margin-left: 0.3rem; }
        .proj-amounts { display: flex; gap: 1.1rem; padding: 0.7rem 0; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); flex-wrap: wrap; }
        .amt { flex: 1; min-width: 78px; }
        .amt-label { font-size: 0.66rem; color: var(--ink-faint); font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 0.15rem; }
        .amt-val { font-size: 0.95rem; font-weight: 700; font-variant-numeric: tabular-nums; }
        .amt-val.ok { color: var(--ok); } .amt-val.danger { color: var(--danger); } .amt-val.accent { color: var(--accent-ink); } .amt-val.warn { color: var(--warn); }
        .proj-bar { height: 5px; background: #edecf3; border-radius: 3px; overflow: hidden; margin-top: 0.75rem; }
        .proj-bar > span { display: block; height: 100%; background: var(--accent); border-radius: 3px; }
        .proj-bar > span.full { background: var(--ok); }
        .proj-foot { display: flex; justify-content: space-between; font-size: 0.74rem; color: var(--ink-faint); margin-top: 0.5rem; font-variant-numeric: tabular-nums; }
        .proj-link { display: inline-flex; align-items: center; gap: 0.4rem; margin-top: 0.85rem; font-size: 0.78rem; font-weight: 600; color: var(--accent); text-decoration: none; }
        .proj-link:hover { color: var(--accent-ink); }

        /* ===== HISTORY BARS ===== */
        .bars { display: grid; grid-template-columns: repeat(12, 1fr); gap: 0.5rem; align-items: end; height: 128px; }
        .bar-col { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; height: 100%; text-decoration: none; }
        .bar { flex: 1; width: 100%; max-width: 30px; background: #e4e2ee; border-radius: 4px 4px 0 0; min-height: 4px; transition: background 0.18s; }
        .bar-col:hover .bar { background: var(--accent); }
        .bar-col.sel .bar { background: var(--accent); }
        .bar-col.cur .bar { background: var(--ok); }
        .bar-lbl { font-size: 0.66rem; color: var(--ink-faint); font-weight: 600; text-transform: capitalize; }
        .bar-col.sel .bar-lbl { color: var(--accent-ink); }

        /* ===== PAYMENT HISTORY ===== */
        .pay-month { margin-bottom: 1.25rem; }
        .pay-month:last-child { margin-bottom: 0; }
        .pay-month-head { display: flex; justify-content: space-between; align-items: baseline; padding: 0 0.2rem 0.5rem; font-size: 0.85rem; font-weight: 600; text-transform: capitalize; border-bottom: 1px solid var(--line); margin-bottom: 0.3rem; }
        .pay-month-total { color: var(--ok); font-variant-numeric: tabular-nums; }
        .pay-row { display: grid; grid-template-columns: 84px 1fr auto; gap: 0.85rem; padding: 0.65rem 0.2rem; align-items: center; border-bottom: 1px solid var(--line); }
        .pay-row:last-child { border-bottom: none; }
        .pay-date { font-size: 0.78rem; color: var(--ink-soft); white-space: nowrap; font-variant-numeric: tabular-nums; }
        .pay-proj { font-size: 0.87rem; font-weight: 600; line-height: 1.25; }
        .pay-meta { font-size: 0.74rem; color: var(--ink-faint); margin-top: 0.1rem; }
        .pay-amt { font-size: 0.92rem; font-weight: 700; color: var(--ok); white-space: nowrap; font-variant-numeric: tabular-nums; }

        /* ===== EMPTY ===== */
        .empty { text-align: center; padding: 2.5rem 1rem; color: var(--ink-faint); }
        .empty i { display: block; font-size: 1.8rem; color: var(--line-strong); margin-bottom: 0.6rem; }
        .empty p { font-size: 0.9rem; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 680px) {
            .wrap { padding: 1.25rem 1rem 3rem; }
            .day-row { gap: 0.5rem; }
            .day-stat { padding: 0.9rem 0.75rem; }
            .day-stat-num { font-size: 1.7rem; }
            .pay-summary { padding: 1.25rem 1.2rem; }
            .pay-summary-amount { font-size: 2.1rem; }
            .pay-summary-meta { text-align: left; }
            .bars { gap: 0.25rem; height: 100px; }
            .bar-lbl { font-size: 0.58rem; }
            .pay-row { grid-template-columns: 64px 1fr auto; gap: 0.5rem; }
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

    {{-- ===== HEADER ===== --}}
    <div class="head">
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
            <button type="submit" class="btn-ghost"><i class="fas fa-arrow-right-from-bracket"></i> Salir</button>
        </form>
    </div>

    {{-- ===== TABS ===== --}}
    <div class="tabs">
        <button type="button" class="tab-btn {{ ($tareaStats['vencidas'] + $tareaStats['hoy']) > 0 ? 'alert' : '' }}" data-tab="tareas">
            Mis tareas
            @if($tareaStats['pendientes'] > 0)<span class="tab-count">{{ $tareaStats['pendientes'] }}</span>@endif
        </button>
        <button type="button" class="tab-btn" data-tab="pagos">Pagos</button>
    </div>

    {{-- ================== TAB: MIS TAREAS ================== --}}
    <div id="tab-tareas" class="tab-pane">

        {{-- Mi día --}}
        <div class="day-row">
            <div class="day-stat {{ $tareaStats['vencidas'] > 0 ? 'has-late' : '' }}">
                <div class="day-stat-num">{{ $tareaStats['vencidas'] }}</div>
                <div class="day-stat-label"><i class="fas fa-circle-exclamation"></i> Vencidas</div>
            </div>
            <div class="day-stat {{ $tareaStats['hoy'] > 0 ? 'has-today' : '' }}">
                <div class="day-stat-num">{{ $tareaStats['hoy'] }}</div>
                <div class="day-stat-label"><i class="fas fa-bolt"></i> Para hoy</div>
            </div>
            <div class="day-stat">
                <div class="day-stat-num">{{ $tareaStats['semana'] }}</div>
                <div class="day-stat-label"><i class="fas fa-calendar-week"></i> Esta semana</div>
            </div>
        </div>

        {{-- Estado de las tareas --}}
        <div class="status-strip section">
            <div class="status-seg">
                <div class="status-seg-num {{ $tareaStats['por_hacer'] ? '' : 'muted' }}">{{ $tareaStats['por_hacer'] }}</div>
                <div class="status-seg-label">Por hacer</div>
            </div>
            <div class="status-seg">
                <div class="status-seg-num {{ $tareaStats['en_progreso'] ? '' : 'muted' }}">{{ $tareaStats['en_progreso'] }}</div>
                <div class="status-seg-label">En progreso</div>
            </div>
            <div class="status-seg">
                <div class="status-seg-num {{ $tareaStats['en_revision'] ? '' : 'muted' }}">{{ $tareaStats['en_revision'] }}</div>
                <div class="status-seg-label">En revisión</div>
            </div>
            <div class="status-seg">
                <div class="status-seg-num {{ $tareaStats['hechas'] ? '' : 'muted' }}">{{ $tareaStats['hechas'] }}</div>
                <div class="status-seg-label">Hechas</div>
            </div>
        </div>

        {{-- Foco: vencidas + hoy --}}
        <div class="section">
            <div class="section-head">
                <span class="section-title">Para hoy y vencidas</span>
                <a href="{{ route('portal.developer.board-global') }}" class="section-link">Ver todas</a>
            </div>
            @if($tareasFoco->count() > 0)
            <div class="panel">
                @foreach($tareasFoco as $t)
                    @php
                        $venc = $t->fecha_limite->lt(now()->startOfDay());
                        $dias = (int) now()->startOfDay()->diffInDays($t->fecha_limite->startOfDay(), false);
                    @endphp
                    <a href="{{ route('portal.developer.board', $t->internal_project_id) }}" class="foco-item">
                        <span class="prio-dot {{ $t->prioridad }}"></span>
                        <div class="foco-body">
                            <div class="foco-title">{{ $t->titulo }}</div>
                            <div class="foco-meta">{{ optional($t->project)->nombre }}</div>
                        </div>
                        <span class="due-badge {{ $venc ? 'late' : 'today' }}">{{ $venc ? 'Vencido ' . abs($dias) . 'd' : 'Hoy' }}</span>
                    </a>
                @endforeach
            </div>
            @else
            <div class="panel"><div class="empty"><i class="fas fa-mug-hot"></i><p>Sin tareas vencidas ni para hoy. Vas al día.</p></div></div>
            @endif
        </div>

        {{-- Tableros --}}
        <div class="section">
            <div class="section-head">
                <span class="section-title">Tus tableros</span>
                <a href="{{ route('portal.developer.board-global') }}" class="section-link">Todas mis tareas</a>
            </div>
            @if($boardsInfo->count() > 0)
            <div class="boards-grid">
                @foreach($boardsInfo as $b)
                    <a href="{{ route('portal.developer.board', $b->id) }}" class="board">
                        <div class="board-name">{{ $b->nombre }}</div>
                        <div class="board-cli">{{ $b->cliente_nombre }}</div>
                        <div class="board-stat">
                            <span class="board-stat-num">{{ $b->pendientes }}</span>
                            <span class="board-stat-txt">{{ $b->pendientes == 1 ? 'pendiente' : 'pendientes' }}</span>
                        </div>
                        @if($b->vencidas > 0)
                            <div class="board-late"><i class="fas fa-circle-exclamation"></i> {{ $b->vencidas }} {{ $b->vencidas == 1 ? 'vencida' : 'vencidas' }}</div>
                        @endif
                        @if($b->total > 0)
                            <div class="board-bar"><span style="width: {{ $b->pct }}%;"></span></div>
                            <div class="board-foot">
                                <span>@if($b->proxima){{ \Carbon\Carbon::parse($b->proxima)->format('d/m') }}@else sin fecha @endif</span>
                                <span>{{ $b->pct }}%</span>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
            @else
            <div class="panel"><div class="empty"><i class="fas fa-table-columns"></i><p>Aún no tienes tableros asignados.</p></div></div>
            @endif
        </div>
    </div>

    {{-- ================== TAB: PAGOS ================== --}}
    <div id="tab-pagos" class="tab-pane">

        {{-- Resumen del mes --}}
        <div class="pay-summary">
            <div>
                <div class="pay-summary-tag">
                    @if($isCurrentMonth)<span class="live"></span> Mes en curso @else <i class="fas fa-clock-rotate-left"></i> Historial @endif
                </div>
                <div class="pay-summary-month">{{ $kpis['mes_label'] }}</div>
                <div class="pay-summary-amount">{{ $fmtCop($kpis['mes_total_cop']) }}</div>
            </div>
            <div class="pay-summary-meta">
                <div><i class="fas fa-paper-plane"></i> {{ $kpis['mes_pagos_count'] }} {{ $kpis['mes_pagos_count'] == 1 ? 'pago' : 'pagos' }}</div>
                <div><i class="fas fa-folder"></i> {{ $kpis['mes_proyectos_count'] }} {{ $kpis['mes_proyectos_count'] == 1 ? 'proyecto' : 'proyectos' }}</div>
                @if($kpis['ultimo_pago'])<div><i class="fas fa-calendar-check"></i> Último: {{ $kpis['ultimo_pago']->locale('es')->isoFormat('DD MMM') }}</div>@endif
            </div>
        </div>

        {{-- Selector de meses --}}
        <div class="months">
            @foreach($historico as $mes)
                <a href="{{ route('portal.developer.dashboard', ['mes' => $mes['key'], 'tab' => 'pagos']) }}"
                   class="month-chip {{ $mes['is_selected'] ? 'active' : '' }}" title="{{ $mes['label_full'] }}">
                    {{ $mes['label_short'] }}
                    <span class="month-chip-amt">{{ $mes['total'] > 0 ? '$' . number_format($mes['total'] / 1000, 0) . 'k' : '·' }}</span>
                </a>
            @endforeach
        </div>

        {{-- KPIs de pagos --}}
        <div class="pay-kpis">
            <div class="pay-kpi">
                <div class="pay-kpi-label"><i class="fas fa-coins"></i> Total ganado</div>
                <div class="pay-kpi-value ok">{{ $fmtCop($kpis['lifetime_cop']) }}</div>
                <div class="pay-kpi-sub">desde el inicio</div>
            </div>
            <div class="pay-kpi">
                <div class="pay-kpi-label"><i class="fas fa-hourglass-half"></i> Por cobrar</div>
                <div class="pay-kpi-value danger">{{ $fmtCop($kpis['pendiente_total_cop']) }}</div>
                <div class="pay-kpi-sub">{{ $kpis['pendientes_count'] }} {{ $kpis['pendientes_count'] == 1 ? 'proyecto' : 'proyectos' }}</div>
            </div>
            <div class="pay-kpi">
                <div class="pay-kpi-label"><i class="fas fa-folder"></i> Proyectos</div>
                <div class="pay-kpi-value">{{ $kpis['proyectos_total'] }}</div>
                <div class="pay-kpi-sub">{{ $kpis['proyectos_activos'] }} activos · {{ $kpis['proyectos_completados'] }} completados</div>
            </div>
            <div class="pay-kpi">
                <div class="pay-kpi-label"><i class="fas fa-repeat"></i> Recurrentes</div>
                <div class="pay-kpi-value">{{ $kpis['recurrentes_count'] }}</div>
                <div class="pay-kpi-sub">cada mes</div>
            </div>
        </div>

        {{-- Recurrentes --}}
        @if($resumenRecurrentes->count() > 0)
        <div class="section">
            <div class="section-head">
                <span class="section-title">Proyectos recurrentes · {{ $kpis['mes_label_short'] }}</span>
                <span class="section-link" style="color:var(--ink-faint);">{{ $resumenRecurrentes->count() }} mensual{{ $resumenRecurrentes->count() == 1 ? '' : 'es' }}</span>
            </div>
            <div class="proj-grid">
                @foreach($resumenRecurrentes as $r)
                    <div class="proj">
                        <div class="proj-name">{{ $r['nombre'] }} <span class="tag-mensual">Mensual</span></div>
                        <div class="proj-cli">{{ $r['cliente'] }}
                            <span class="est" style="background: {{ $r['estado_color'] }}15; color: {{ $r['estado_color'] }};">{{ $r['estado_label'] }}</span>
                        </div>
                        <div class="proj-amounts">
                            <div class="amt"><div class="amt-label">Pago / mes</div><div class="amt-val accent">{{ $fmtMoneda($r['asignado_mensual'], $r['moneda']) }}</div></div>
                            <div class="amt"><div class="amt-label">Cobrado</div><div class="amt-val ok">{{ $fmtMoneda($r['cobrado_mes'], $r['moneda']) }}</div></div>
                            @if($r['pendiente_mes'] > 0)
                            <div class="amt"><div class="amt-label">Pendiente</div><div class="amt-val warn">{{ $fmtMoneda($r['pendiente_mes'], $r['moneda']) }}</div></div>
                            @endif
                        </div>
                        @if($r['asignado_mensual'] > 0)
                            <div class="proj-bar"><span class="{{ $r['pct'] >= 100 ? 'full' : '' }}" style="width: {{ $r['pct'] }}%;"></span></div>
                        @endif
                        <div class="proj-foot">
                            <span>{{ $r['pagos_mes_count'] }} {{ $r['pagos_mes_count'] == 1 ? 'pago' : 'pagos' }} este mes</span>
                            <span>{{ $r['pct'] }}%</span>
                        </div>
                        <a href="{{ route('portal.developer.board', $r['id']) }}" class="proj-link">Ver tablero <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i></a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- No recurrentes --}}
        @if($resumenOneShot->count() > 0)
        @php
            $countCobrados = $resumenOneShot->where('tiene_pago_mes', true)->count();
            $countPendientes = $resumenOneShot->where('status', 'pendiente')->count();
        @endphp
        <div class="section">
            <div class="section-head">
                <span class="section-title">Proyectos</span>
                <span class="section-link" style="color:var(--ink-faint);">
                    @if($countCobrados > 0)<span>{{ $countCobrados }} cobrados</span>@endif
                    @if($countPendientes > 0)<span style="color:var(--danger);">{{ $countPendientes }} por cobrar</span>@endif
                </span>
            </div>
            <div class="proj-grid">
                @foreach($resumenOneShot as $o)
                    @php $isPendiente = $o['status'] === 'pendiente'; $isCompleto = $o['status'] === 'completo'; @endphp
                    <div class="proj {{ $isPendiente ? 'pendiente' : '' }}">
                        @if($o['status'] === 'cobrado_mes')
                            <span class="proj-pill ok"><i class="fas fa-check"></i> Cobrado este mes</span>
                        @elseif($isCompleto)
                            <span class="proj-pill ok"><i class="fas fa-check-double"></i> Completado</span>
                        @else
                            <span class="proj-pill pend"><i class="fas fa-hourglass-half"></i> Por cobrar</span>
                        @endif
                        <div class="proj-name">{{ $o['nombre'] }}</div>
                        <div class="proj-cli">{{ $o['cliente'] }}
                            <span class="est" style="background: {{ $o['estado_color'] }}15; color: {{ $o['estado_color'] }};">{{ $o['estado_label'] }}</span>
                        </div>
                        <div class="proj-amounts">
                            <div class="amt"><div class="amt-label">Pago total</div><div class="amt-val accent">{{ $fmtMoneda($o['asignado'], $o['moneda']) }}</div></div>
                            @if($o['tiene_pago_mes'])
                            <div class="amt"><div class="amt-label">Cobrado</div><div class="amt-val ok">{{ $fmtMoneda($o['cobrado_mes'], $o['moneda']) }}</div></div>
                            @endif
                            @if($o['pendiente'] > 0)
                            <div class="amt"><div class="amt-label">{{ $isPendiente ? 'Te deben' : 'Falta' }}</div><div class="amt-val danger">{{ $fmtMoneda($o['pendiente'], $o['moneda']) }}</div></div>
                            @endif
                        </div>
                        @if($o['asignado'] > 0)
                            <div class="proj-bar"><span class="{{ $o['pct'] >= 100 ? 'full' : '' }}" style="width: {{ $o['pct'] }}%;"></span></div>
                        @endif
                        <div class="proj-foot">
                            <span>{{ $o['pagos_total_count'] }} {{ $o['pagos_total_count'] == 1 ? 'pago' : 'pagos' }}</span>
                            <span>{{ $o['pct'] }}% pagado</span>
                        </div>
                        <a href="{{ route('portal.developer.board', $o['id']) }}" class="proj-link">Ver tablero <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i></a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($resumenRecurrentes->count() === 0 && $resumenOneShot->count() === 0)
        <div class="section"><div class="panel"><div class="empty"><i class="fas fa-inbox"></i><p>No hay pagos en {{ $kpis['mes_label'] }}.</p></div></div></div>
        @endif

        {{-- Histórico 12 meses --}}
        <div class="section">
            <div class="section-head">
                <span class="section-title">Histórico mensual</span>
                <span class="section-link" style="color:var(--ink-faint);">últimos 12 meses</span>
            </div>
            <div class="panel" style="padding: 1.25rem 1.3rem;">
                <div class="bars">
                    @foreach($historico as $mes)
                        <a href="{{ route('portal.developer.dashboard', ['mes' => $mes['key'], 'tab' => 'pagos']) }}"
                           class="bar-col {{ $mes['is_current'] ? 'cur' : '' }} {{ $mes['is_selected'] ? 'sel' : '' }}"
                           title="{{ $mes['label_full'] }}: {{ $fmtCop($mes['total']) }}">
                            <div class="bar" style="height: {{ max(($mes['total'] / $maxHistorico) * 100, 3) }}%;"></div>
                            <span class="bar-lbl">{{ $mes['label_short'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Historial de pagos --}}
        @if($paymentsAgrupados->count() > 0)
        <div class="section">
            <div class="section-head"><span class="section-title">Historial de pagos</span></div>
            <div class="panel" style="padding: 1.1rem 1.3rem;">
                @foreach($paymentsAgrupados as $grupo)
                    <div class="pay-month">
                        <div class="pay-month-head">
                            <span>{{ $grupo['label'] }}</span>
                            <span class="pay-month-total">{{ $fmtCop($grupo['total_cop']) }}</span>
                        </div>
                        @foreach($grupo['pagos'] as $pay)
                            <div class="pay-row">
                                <div class="pay-date">{{ $pay->fecha->format('d/m/Y') }}</div>
                                <div>
                                    <div class="pay-proj">{{ optional($pay->project)->nombre ?? '·' }}
                                        @if($pay->project && $pay->project->es_recurrente)<span class="tag-mensual">Mensual</span>@endif
                                    </div>
                                    <div class="pay-meta">{{ optional($pay->project)->cliente_nombre }}@if($pay->metodo) · {{ $pay->metodo }}@endif</div>
                                </div>
                                <div class="pay-amt">+{{ $fmtMoneda($pay->monto, $pay->moneda ?? 'COP') }}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- /#tab-pagos --}}
</div>

<script>
(function () {
    const btns = document.querySelectorAll('.tab-btn');
    const panes = { tareas: document.getElementById('tab-tareas'), pagos: document.getElementById('tab-pagos') };
    function activate(tab) {
        if (! panes[tab]) { tab = 'tareas'; }
        btns.forEach(b => b.classList.toggle('active', b.dataset.tab === tab));
        Object.keys(panes).forEach(k => panes[k] && panes[k].classList.toggle('active', k === tab));
        try { localStorage.setItem('devDashTab', tab); } catch (e) {}
    }
    btns.forEach(b => b.addEventListener('click', () => activate(b.dataset.tab)));
    const urlTab = new URLSearchParams(location.search).get('tab');
    let initial = urlTab || 'tareas';
    if (! urlTab) { try { initial = localStorage.getItem('devDashTab') || 'tareas'; } catch (e) {} }
    activate(initial);
})();
</script>
</body>
</html>
