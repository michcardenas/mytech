@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --primary-dark: #0056b3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --danger: #dc3545;
        --warning: #f7a831;
        --success: #28a745;
        --purple: #7c3aed;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --gradient-danger: linear-gradient(135deg, #ff6b6b 0%, #dc3545 100%);
        --gradient-purple: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
        --gradient-success: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        --gradient-warning: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stats-container { background: var(--light-gray); max-width: 1280px; margin: 0 auto; padding: 2rem; min-height: 80vh; }

    .stats-header {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        margin-bottom: 1.5rem; padding: 1.75rem 2rem; background: var(--gradient-blue);
        border-radius: 16px; color: white;
    }
    .stats-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem 0; color: white; display: flex; align-items: center; gap: 0.75rem; }
    .stats-header p { margin: 0; opacity: 0.85; font-size: 0.88rem; }

    .btn-ghost { background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 2px solid rgba(255,255,255,0.4); color: white; padding: 0.6rem 1.1rem; border-radius: 12px; font-weight: 600; text-decoration: none; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
    .btn-ghost:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; transform: translateY(-2px); }

    /* Range picker */
    .range-wrap { background: var(--white); padding: 1rem 1.25rem; border-radius: 14px; box-shadow: var(--shadow-soft); margin-bottom: 1.25rem; }
    .range-presets { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
    .range-chip { padding: 0.45rem 0.95rem; border-radius: 20px; border: 1.5px solid #e9ecef; background: var(--white); color: #666; font-weight: 600; font-size: 0.8rem; text-decoration: none; transition: var(--transition); cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; }
    .range-chip:hover { border-color: var(--primary-blue); color: var(--primary-blue); text-decoration: none; }
    .range-chip.active { background: var(--gradient-blue); color: white; border-color: transparent; }
    .range-chip.active:hover { color: white; }

    .range-custom { display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 0.6rem; align-items: end; }
    .range-custom label { font-size: 0.72rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; display: block; margin-bottom: 0.3rem; }
    .range-custom input { padding: 0.55rem 0.8rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 0.85rem; background: var(--white); color: var(--dark-text); width: 100%; transition: var(--transition); }
    .range-custom input:focus { border-color: var(--primary-blue); outline: none; }
    .btn-apply { padding: 0.55rem 1.1rem; border-radius: 10px; border: none; background: var(--gradient-blue); color: white; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; height: 40px; }
    .btn-apply:hover { transform: translateY(-1px); }
    .btn-csv { padding: 0.55rem 1.1rem; border-radius: 10px; border: 1px solid #10b981; background: var(--white); color: #059669; font-weight: 600; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; height: 40px; transition: var(--transition); }
    .btn-csv:hover { background: #d1fae5; color: #047857; text-decoration: none; }

    .range-info { margin-top: 0.6rem; font-size: 0.78rem; color: #888; }
    .range-info strong { color: var(--dark-text); }

    /* KPIs */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .kpi-card { border-radius: 14px; padding: 1.25rem 1.5rem; color: white; box-shadow: var(--shadow-soft); position: relative; overflow: hidden; }
    .kpi-card.ing { background: var(--gradient-success); }
    .kpi-card.dev { background: var(--gradient-purple); }
    .kpi-card.gas { background: var(--gradient-warning); }
    .kpi-card.uti { background: var(--gradient-blue); }
    .kpi-card.uti.negative { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
    .kpi-label { font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; opacity: 0.9; display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.4rem; }
    .kpi-value { font-size: 1.7rem; font-weight: 800; line-height: 1.1; }
    .kpi-meta { font-size: 0.78rem; opacity: 0.9; margin-top: 0.25rem; }
    .kpi-icon { position: absolute; right: 1rem; top: 1rem; font-size: 2.4rem; opacity: 0.18; }

    /* Panels */
    .panel { background: var(--white); border-radius: 14px; padding: 1.25rem 1.5rem; box-shadow: var(--shadow-soft); margin-bottom: 1.25rem; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem; }
    .panel-head h3 { font-size: 1rem; font-weight: 700; margin: 0; color: var(--dark-text); display: flex; align-items: center; gap: 0.5rem; }
    .panel-head .muted { font-size: 0.78rem; color: #888; }

    /* Chart */
    .chart-wrap { position: relative; height: 320px; }

    /* Movimientos table */
    .mov-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    .mov-table th { text-align: left; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.3px; color: #888; font-weight: 700; padding: 0.6rem 0.75rem; border-bottom: 2px solid #f1f3f5; }
    .mov-table td { padding: 0.7rem 0.75rem; border-bottom: 1px solid #f1f3f5; color: var(--dark-text); vertical-align: middle; }
    .mov-table tr:last-child td { border-bottom: none; }
    .mov-table tr:hover td { background: #fafbfc; }
    .mov-tipo { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.18rem 0.55rem; border-radius: 6px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
    .mov-tipo.ing { background: rgba(16,185,129,0.12); color: #059669; }
    .mov-tipo.dev { background: rgba(124,58,237,0.12); color: #6b21a8; }
    .mov-tipo.gas { background: rgba(245,158,11,0.15); color: #b45309; }
    .mov-monto { font-weight: 700; text-align: right; white-space: nowrap; }
    .mov-monto.ing { color: #059669; }
    .mov-monto.egr { color: var(--danger); }
    .mov-proyecto { font-weight: 600; }
    .mov-cliente { font-size: 0.78rem; color: #888; }

    /* Próximos a vencer */
    .prox-list { display: grid; gap: 0.6rem; }
    .prox-item { display: grid; grid-template-columns: 1fr auto; gap: 0.75rem; align-items: center; padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid #f1f3f5; background: var(--white); transition: var(--transition); text-decoration: none; color: var(--dark-text); }
    .prox-item:hover { border-color: rgba(0,123,255,0.25); box-shadow: var(--shadow-soft); transform: translateX(2px); text-decoration: none; color: var(--dark-text); }
    .prox-item.vencido { border-left: 4px solid var(--danger); }
    .prox-item.urgente { border-left: 4px solid var(--warning); }
    .prox-item.normal { border-left: 4px solid var(--primary-blue); }
    .prox-title { font-weight: 700; font-size: 0.92rem; margin: 0 0 0.2rem 0; }
    .prox-cliente { font-size: 0.78rem; color: #888; display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
    .prox-right { text-align: right; }
    .prox-dias { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 0.2rem; }
    .prox-dias.vencido { color: var(--danger); }
    .prox-dias.urgente { color: #b76f00; }
    .prox-dias.normal { color: var(--primary-blue); }
    .prox-fecha { font-size: 0.82rem; color: var(--dark-text); font-weight: 600; }
    .prox-saldo { font-size: 0.72rem; color: #888; margin-top: 0.15rem; }

    .empty-mini { text-align: center; padding: 2.5rem 1rem; color: #aaa; }
    .empty-mini i { font-size: 2.25rem; color: #ddd; margin-bottom: 0.5rem; display: block; }

    @media (max-width: 768px) {
        .stats-container { padding: 1rem; }
        .stats-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .range-custom { grid-template-columns: 1fr 1fr; }
        .kpi-grid { grid-template-columns: 1fr 1fr; }
        .mov-table th:nth-child(4), .mov-table td:nth-child(4) { display: none; }
    }
</style>

@php
    $fmtCop = fn ($v) => '$' . number_format((float) $v, 0, ',', '.');
    $fmtMoneda = fn ($v, $m) => ($m === 'USD' ? 'US$' : '$') . number_format((float) $v, 0, ',', '.') . ($m === 'USD' ? ' USD' : '');
    $preset = $rango['preset'];
    $presets = [
        'hoy' => 'Hoy',
        'mes_actual' => 'Este mes',
        'mes_anterior' => 'Mes anterior',
        'este_anio' => 'Este año',
    ];
    $granLabel = ['day' => 'día', 'week' => 'semana', 'month' => 'mes'][$rango['granularity']];
    $exportQuery = array_filter([
        'preset' => $preset,
        'desde' => $preset === 'personalizado' ? $rango['desde'] : null,
        'hasta' => $preset === 'personalizado' ? $rango['hasta'] : null,
    ]);
@endphp

<div class="stats-container">
    <div class="stats-header">
        <div>
            <h1><i class="fas fa-chart-line"></i> Reporte financiero</h1>
            <p>Ingresos, egresos y utilidad del rango seleccionado</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.internal-projects.index') }}" class="btn-ghost">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    {{-- Range picker --}}
    <form method="GET" class="range-wrap">
        <div class="range-presets">
            @foreach($presets as $key => $label)
                <a href="{{ route('admin.internal-projects.stats', ['preset' => $key]) }}"
                   class="range-chip {{ $preset === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
            <span class="range-chip {{ $preset === 'personalizado' ? 'active' : '' }}"
                  onclick="document.getElementById('range-custom').style.display='grid';document.getElementById('preset-input').value='personalizado';">
                <i class="fas fa-calendar-alt"></i> Personalizado
            </span>
        </div>

        <input type="hidden" name="preset" id="preset-input" value="personalizado">

        <div class="range-custom" id="range-custom" style="display: {{ $preset === 'personalizado' ? 'grid' : 'none' }};">
            <div>
                <label for="desde">Desde</label>
                <input type="date" name="desde" id="desde" value="{{ $rango['desde'] }}">
            </div>
            <div>
                <label for="hasta">Hasta</label>
                <input type="date" name="hasta" id="hasta" value="{{ $rango['hasta'] }}">
            </div>
            <button type="submit" class="btn-apply"><i class="fas fa-check"></i> Aplicar</button>
            <a href="{{ route('admin.internal-projects.stats.export', $exportQuery) }}" class="btn-csv">
                <i class="fas fa-file-csv"></i> CSV
            </a>
        </div>

        <div class="range-info">
            <strong>{{ $rango['start']->locale('es')->isoFormat('DD MMM YYYY') }}</strong>
            a <strong>{{ $rango['end']->locale('es')->isoFormat('DD MMM YYYY') }}</strong>
            · {{ $rango['dias'] }} días · agrupado por {{ $granLabel }}
            @if($preset !== 'personalizado')
                · <a href="{{ route('admin.internal-projects.stats.export', $exportQuery) }}" style="color:#059669; font-weight:600; text-decoration:none;"><i class="fas fa-file-csv"></i> Exportar CSV</a>
            @endif
        </div>
    </form>

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi-card ing">
            <i class="fas fa-arrow-down kpi-icon"></i>
            <div class="kpi-label"><i class="fas fa-hand-holding-usd"></i> Ingresos</div>
            <div class="kpi-value">{{ $fmtCop($kpis['ingresos']) }}</div>
            <div class="kpi-meta">{{ $kpis['cuenta_ingresos'] }} pagos recibidos</div>
        </div>
        <div class="kpi-card dev">
            <i class="fas fa-laptop-code kpi-icon"></i>
            <div class="kpi-label"><i class="fas fa-paper-plane"></i> Pagos a devs</div>
            <div class="kpi-value">{{ $fmtCop($kpis['pagos_dev']) }}</div>
            <div class="kpi-meta">{{ $kpis['cuenta_pagos_dev'] }} transferencias</div>
        </div>
        <div class="kpi-card gas">
            <i class="fas fa-receipt kpi-icon"></i>
            <div class="kpi-label"><i class="fas fa-shopping-cart"></i> Otros gastos</div>
            <div class="kpi-value">{{ $fmtCop($kpis['gastos']) }}</div>
            <div class="kpi-meta">{{ $kpis['cuenta_gastos'] }} gastos registrados</div>
        </div>
        <div class="kpi-card uti {{ $kpis['utilidad'] < 0 ? 'negative' : '' }}">
            <i class="fas fa-coins kpi-icon"></i>
            <div class="kpi-label"><i class="fas fa-chart-line"></i> Utilidad</div>
            <div class="kpi-value">{{ $fmtCop($kpis['utilidad']) }}</div>
            <div class="kpi-meta">Margen: {{ $kpis['margen'] }}%</div>
        </div>
    </div>

    {{-- Gráfica ingresos vs egresos --}}
    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-chart-bar"></i> Ingresos vs egresos</h3>
            <span class="muted">Agrupado por {{ $granLabel }}</span>
        </div>
        <div class="chart-wrap">
            <canvas id="chartFlujo"></canvas>
        </div>
    </div>

    {{-- Próximos a vencer --}}
    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-hourglass-half"></i> Próximos a vencer</h3>
            <span class="muted">Entrega en ≤ 30 días o vencidos</span>
        </div>
        @if($proximos->count() > 0)
            <div class="prox-list">
                @foreach($proximos as $p)
                    @php
                        $class = $p['vencido'] ? 'vencido' : ($p['dias_restantes'] <= 7 ? 'urgente' : 'normal');
                        $diasTxt = $p['vencido']
                            ? 'Vencido hace ' . abs($p['dias_restantes']) . 'd'
                            : ($p['dias_restantes'] === 0 ? 'Hoy' : 'En ' . $p['dias_restantes'] . 'd');
                    @endphp
                    <a href="{{ route('admin.internal-projects.show', $p['id']) }}" class="prox-item {{ $class }}">
                        <div>
                            <p class="prox-title">{{ $p['nombre'] }}</p>
                            <p class="prox-cliente">
                                <i class="fas fa-user"></i> {{ $p['cliente'] }}
                                <span style="padding:0.1rem 0.45rem; border-radius:6px; background: {{ $p['estado_color'] }}15; color: {{ $p['estado_color'] }}; font-size:0.68rem; font-weight:700; text-transform:uppercase;">{{ $p['estado_label'] }}</span>
                            </p>
                        </div>
                        <div class="prox-right">
                            <div class="prox-dias {{ $class }}">{{ $diasTxt }}</div>
                            <div class="prox-fecha">{{ $p['fecha_entrega']->format('d/m/Y') }}</div>
                            @if($p['saldo_cop'] > 0)
                                <div class="prox-saldo">Saldo: {{ $fmtCop($p['saldo_cop']) }}</div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-mini">
                <i class="fas fa-calendar-check"></i>
                <p>Ningún proyecto con entrega en los próximos 30 días.</p>
            </div>
        @endif
    </div>

    {{-- Movimientos del rango --}}
    <div class="panel">
        <div class="panel-head">
            <h3><i class="fas fa-list-alt"></i> Movimientos del rango</h3>
            <span class="muted">
                {{ $movimientosTotal }} movimientos
                @if($movimientosTotal > $movimientos->count())
                    · mostrando {{ $movimientos->count() }} (usa CSV para ver todo)
                @endif
            </span>
        </div>
        @if($movimientos->count() > 0)
            <div style="overflow-x:auto;">
                <table class="mov-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Proyecto / Cliente</th>
                            <th>Concepto</th>
                            <th style="text-align:right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $m)
                            @php
                                $tipoCls = $m['tipo'] === 'Ingreso' ? 'ing' : ($m['tipo'] === 'Pago dev' ? 'dev' : 'gas');
                                $montoCls = $m['tipo'] === 'Ingreso' ? 'ing' : 'egr';
                                $signo = $m['tipo'] === 'Ingreso' ? '+' : '-';
                            @endphp
                            <tr>
                                <td style="white-space:nowrap;">{{ $m['fecha']->format('d/m/Y') }}</td>
                                <td><span class="mov-tipo {{ $tipoCls }}">{{ $m['tipo'] }}</span></td>
                                <td>
                                    <div class="mov-proyecto">{{ $m['proyecto'] }}</div>
                                    <div class="mov-cliente">{{ $m['cliente'] }}</div>
                                </td>
                                <td style="color:#888; font-size:0.8rem;">{{ $m['concepto'] ?: '—' }}</td>
                                <td class="mov-monto {{ $montoCls }}">
                                    {{ $signo }}{{ $fmtMoneda($m['monto'], $m['moneda']) }}
                                    @if($m['moneda'] === 'USD')
                                        <div style="font-size:0.7rem; color:#aaa; font-weight:500;">≈ {{ $fmtCop($m['monto_cop']) }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-mini">
                <i class="fas fa-inbox"></i>
                <p>No hay movimientos en este rango.</p>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const fmtCop = v => '$' + Number(v).toLocaleString('es-CO', { maximumFractionDigits: 0 });

    new Chart(document.getElementById('chartFlujo'), {
        type: 'bar',
        data: {
            labels: @json($serieLabels),
            datasets: [
                { label: 'Ingresos', data: @json($serieIngresos), backgroundColor: 'rgba(16,185,129,0.75)', borderRadius: 6 },
                { label: 'Egresos',  data: @json($serieEgresos),  backgroundColor: 'rgba(220,53,69,0.75)',  borderRadius: 6 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12, weight: 600 } } },
                tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + fmtCop(ctx.parsed.y) } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => fmtCop(v), font: { size: 11 } }, grid: { color: '#f1f3f5' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } }
            }
        }
    });
</script>
@endsection
