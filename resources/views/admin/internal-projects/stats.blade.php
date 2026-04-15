@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --success: #10b981;
        --danger: #dc3545;
        --warning: #f7a831;
        --purple: #7c3aed;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sp-container {
        background: var(--light-gray);
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    .sp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.75rem 2rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .sp-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem 0; color: white; display: flex; align-items: center; gap: 0.75rem; }
    .sp-header p { margin: 0; opacity: 0.85; font-size: 0.88rem; }

    .btn-back {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        padding: 0.65rem 1.3rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.88rem;
    }
    .btn-back:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; transform: translateY(-2px); }

    /* KPIs */
    .sp-kpis {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .sp-kpi {
        background: var(--white);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-soft);
        border-left: 4px solid var(--primary-blue);
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    .sp-kpi.ingresos { border-left-color: var(--success); }
    .sp-kpi.gastos-dev { border-left-color: var(--purple); }
    .sp-kpi.gastos-otros { border-left-color: var(--warning); }
    .sp-kpi.utilidad { border-left-color: var(--primary-blue); }
    .sp-kpi.utilidad.neg { border-left-color: var(--danger); }
    .sp-kpi-label { font-size: 0.72rem; text-transform: uppercase; color: #888; font-weight: 700; letter-spacing: 0.5px; }
    .sp-kpi-value { font-size: 1.5rem; font-weight: 800; color: var(--dark-text); line-height: 1.1; }
    .sp-kpi-meta { font-size: 0.78rem; color: #888; }

    /* Secciones y cards */
    .sp-section { margin-bottom: 1.75rem; }
    .sp-section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0 0 0.85rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .sp-section-title i { color: var(--primary-blue); }

    .sp-card {
        background: var(--white);
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(0,0,0,0.04);
    }

    .sp-grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; }
    .sp-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
    .sp-grid-eq { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .chart-wrap { position: relative; height: 320px; }
    .chart-wrap.small { height: 260px; }

    /* Tablas */
    .sp-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.85rem;
    }
    .sp-table th {
        text-align: left;
        padding: 0.65rem 0.85rem;
        background: #f8f9fa;
        color: #666;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e9ecef;
    }
    .sp-table td {
        padding: 0.75rem 0.85rem;
        border-bottom: 1px solid #f1f1f1;
        color: var(--dark-text);
    }
    .sp-table tr:last-child td { border-bottom: none; }
    .sp-table tr:hover td { background: #fafbfc; }
    .sp-table .num { text-align: right; font-variant-numeric: tabular-nums; font-weight: 600; }
    .sp-table .num.pos { color: var(--success); }
    .sp-table .num.neg { color: var(--danger); }
    .sp-table .num.purple { color: var(--purple); }

    .mini-badge {
        display: inline-block;
        padding: 0.15rem 0.55rem;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .dev-link { color: var(--primary-blue); text-decoration: none; font-weight: 600; }
    .dev-link:hover { text-decoration: underline; }

    .empty-row td { text-align: center; color: #aaa; padding: 2rem; font-style: italic; }

    /* Aging */
    .aging-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.85rem; }
    .aging-item {
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .aging-item.a1 { background: rgba(40,167,69,0.08); border-left: 3px solid var(--success); }
    .aging-item.a2 { background: rgba(247,168,49,0.1); border-left: 3px solid var(--warning); }
    .aging-item.a3 { background: rgba(255,107,107,0.1); border-left: 3px solid #ff6b6b; }
    .aging-item.a4 { background: rgba(220,53,69,0.1); border-left: 3px solid var(--danger); }
    .aging-label { font-size: 0.72rem; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.3px; }
    .aging-value { font-size: 1.25rem; font-weight: 800; color: var(--dark-text); margin: 0.3rem 0; }
    .aging-count { font-size: 0.75rem; color: #888; }

    @media (max-width: 900px) {
        .sp-grid-2, .sp-grid-3, .sp-grid-eq { grid-template-columns: 1fr; }
        .aging-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .sp-container { padding: 1rem; }
        .sp-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .sp-kpis { grid-template-columns: repeat(2, 1fr); }
    }
</style>

@php
    $fmtCop = fn ($v) => '$' . number_format((float) $v, 0, ',', '.');
@endphp

<div class="sp-container">
    <div class="sp-header">
        <div>
            <h1><i class="fas fa-chart-pie"></i> Estadísticas y Análisis</h1>
            <p>Dashboard financiero · Ingresos, utilidad, desarrolladores y clientes · Tasa USD→COP: ${{ number_format($usdCop, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('admin.internal-projects.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a proyectos
        </a>
    </div>

    {{-- KPIs globales --}}
    <div class="sp-kpis">
        <div class="sp-kpi ingresos">
            <div class="sp-kpi-label"><i class="fas fa-arrow-down"></i> Ingresos totales</div>
            <div class="sp-kpi-value">{{ $fmtCop($kpis['total_ingresos']) }}</div>
            <div class="sp-kpi-meta">Histórico completo (COP)</div>
        </div>
        <div class="sp-kpi gastos-dev">
            <div class="sp-kpi-label"><i class="fas fa-laptop-code"></i> Pagado a devs</div>
            <div class="sp-kpi-value">{{ $fmtCop($kpis['total_pagos_dev']) }}</div>
            <div class="sp-kpi-meta">Total histórico</div>
        </div>
        <div class="sp-kpi gastos-otros">
            <div class="sp-kpi-label"><i class="fas fa-receipt"></i> Gastos del proyecto</div>
            <div class="sp-kpi-value">{{ $fmtCop($kpis['total_gastos']) }}</div>
            <div class="sp-kpi-meta">Herramientas, licencias, etc.</div>
        </div>
        <div class="sp-kpi utilidad {{ $kpis['utilidad_total'] < 0 ? 'neg' : '' }}">
            <div class="sp-kpi-label"><i class="fas fa-coins"></i> Utilidad neta</div>
            <div class="sp-kpi-value">{{ $fmtCop($kpis['utilidad_total']) }}</div>
            <div class="sp-kpi-meta">Margen {{ $kpis['margen_total'] }}%</div>
        </div>
        <div class="sp-kpi">
            <div class="sp-kpi-label"><i class="fas fa-folder-open"></i> Proyectos totales</div>
            <div class="sp-kpi-value">{{ $kpis['proyectos_totales'] }}</div>
            <div class="sp-kpi-meta">Ticket promedio: {{ $fmtCop($kpis['ticket_promedio']) }}</div>
        </div>
    </div>

    {{-- Flujo de caja (12 meses) --}}
    <div class="sp-section">
        <h2 class="sp-section-title"><i class="fas fa-chart-line"></i> Flujo de caja — últimos 12 meses</h2>
        <div class="sp-grid-2">
            <div class="sp-card">
                <div class="chart-wrap"><canvas id="chartFlujo"></canvas></div>
            </div>
            <div class="sp-card">
                <div class="chart-wrap small"><canvas id="chartUtilidad"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Distribuciones --}}
    <div class="sp-section">
        <h2 class="sp-section-title"><i class="fas fa-chart-pie"></i> Distribución de proyectos</h2>
        <div class="sp-grid-eq">
            <div class="sp-card">
                <h3 style="font-size:0.9rem;font-weight:700;color:#555;margin:0 0 0.75rem;">Por estado</h3>
                <div class="chart-wrap small"><canvas id="chartEstado"></canvas></div>
            </div>
            <div class="sp-card">
                <h3 style="font-size:0.9rem;font-weight:700;color:#555;margin:0 0 0.75rem;">Por fuente</h3>
                <div class="chart-wrap small"><canvas id="chartFuente"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Aging cuentas por cobrar --}}
    <div class="sp-section">
        <h2 class="sp-section-title"><i class="fas fa-hourglass-half"></i> Antigüedad de cuentas por cobrar</h2>
        <div class="sp-card">
            <div class="aging-grid">
                <div class="aging-item a1">
                    <div class="aging-label">0 – 30 días</div>
                    <div class="aging-value">{{ $fmtCop($aging['0-30']) }}</div>
                    <div class="aging-count">{{ $agingCount['0-30'] }} proyectos</div>
                </div>
                <div class="aging-item a2">
                    <div class="aging-label">31 – 60 días</div>
                    <div class="aging-value">{{ $fmtCop($aging['31-60']) }}</div>
                    <div class="aging-count">{{ $agingCount['31-60'] }} proyectos</div>
                </div>
                <div class="aging-item a3">
                    <div class="aging-label">61 – 90 días</div>
                    <div class="aging-value">{{ $fmtCop($aging['61-90']) }}</div>
                    <div class="aging-count">{{ $agingCount['61-90'] }} proyectos</div>
                </div>
                <div class="aging-item a4">
                    <div class="aging-label">90+ días</div>
                    <div class="aging-value">{{ $fmtCop($aging['90+']) }}</div>
                    <div class="aging-count">{{ $agingCount['90+'] }} proyectos</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top desarrolladores --}}
    <div class="sp-section">
        <h2 class="sp-section-title"><i class="fas fa-users"></i> Top desarrolladores</h2>
        <div class="sp-card">
            <table class="sp-table">
                <thead>
                    <tr>
                        <th>Desarrollador</th>
                        <th class="num">Proyectos</th>
                        <th class="num">Asignado</th>
                        <th class="num">Pagado</th>
                        <th class="num">Pendiente</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDevs as $dev)
                        <tr>
                            <td><strong>{{ $dev['nombre'] }}</strong></td>
                            <td class="num">{{ $dev['proyectos'] }}</td>
                            <td class="num">{{ $fmtCop($dev['asignado_cop']) }}</td>
                            <td class="num pos">{{ $fmtCop($dev['pagado_cop']) }}</td>
                            <td class="num {{ $dev['pendiente_cop'] > 0 ? 'purple' : '' }}">{{ $fmtCop($dev['pendiente_cop']) }}</td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="5">Sin desarrolladores asignados todavía</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top clientes --}}
    <div class="sp-section">
        <h2 class="sp-section-title"><i class="fas fa-user-tie"></i> Top clientes</h2>
        <div class="sp-card">
            <table class="sp-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th class="num">Proyectos</th>
                        <th class="num">Contratado</th>
                        <th class="num">Pagado</th>
                        <th class="num">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topClientes as $cli)
                        <tr>
                            <td><strong>{{ $cli['nombre'] }}</strong></td>
                            <td class="num">{{ $cli['proyectos'] }}</td>
                            <td class="num">{{ $fmtCop($cli['contratado_cop']) }}</td>
                            <td class="num pos">{{ $fmtCop($cli['ingresos_cop']) }}</td>
                            <td class="num {{ $cli['saldo_cop'] > 0 ? 'neg' : '' }}">{{ $fmtCop($cli['saldo_cop']) }}</td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="5">Sin datos de clientes</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top proyectos más rentables --}}
    <div class="sp-section">
        <h2 class="sp-section-title"><i class="fas fa-trophy"></i> Proyectos más rentables</h2>
        <div class="sp-card">
            <table class="sp-table">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th class="num">Ingresos</th>
                        <th class="num">Utilidad</th>
                        <th class="num">Margen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topRentables as $p)
                        <tr>
                            <td><a href="{{ route('admin.internal-projects.show', $p['id']) }}" class="dev-link">{{ $p['nombre'] }}</a></td>
                            <td>{{ $p['cliente'] }}</td>
                            <td>
                                <span class="mini-badge" style="background: {{ $p['estado_color'] }}15; color: {{ $p['estado_color'] }};">
                                    {{ $p['estado_label'] }}
                                </span>
                            </td>
                            <td class="num">{{ $fmtCop($p['ingresos_cop']) }}</td>
                            <td class="num {{ $p['utilidad_cop'] >= 0 ? 'pos' : 'neg' }}">{{ $fmtCop($p['utilidad_cop']) }}</td>
                            <td class="num {{ $p['margen'] >= 0 ? 'pos' : 'neg' }}">{{ $p['margen'] }}%</td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="6">Sin proyectos para mostrar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels = @json($serieLabels);
    const ingresos = @json($serieIngresos);
    const gastosDev = @json($serieGastosDev);
    const gastosOtros = @json($serieGastosOtros);
    const utilidad = @json($serieUtilidad);

    const fmt = (v) => '$' + new Intl.NumberFormat('es-CO').format(v);

    // Flujo: ingresos vs gastos
    new Chart(document.getElementById('chartFlujo'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                { label: 'Ingresos', data: ingresos, backgroundColor: 'rgba(16,185,129,0.75)', borderRadius: 6 },
                { label: 'Pagos a devs', data: gastosDev, backgroundColor: 'rgba(124,58,237,0.75)', borderRadius: 6 },
                { label: 'Otros gastos', data: gastosOtros, backgroundColor: 'rgba(247,168,49,0.75)', borderRadius: 6 },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } },
                tooltip: { callbacks: { label: (ctx) => ctx.dataset.label + ': ' + fmt(ctx.raw) } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { ticks: { callback: (v) => fmt(v), font: { size: 10 } }, grid: { color: '#f1f1f1' } }
            }
        }
    });

    // Utilidad mensual
    new Chart(document.getElementById('chartUtilidad'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Utilidad',
                data: utilidad,
                borderColor: '#007BFF',
                backgroundColor: 'rgba(0,123,255,0.12)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#007BFF',
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (ctx) => 'Utilidad: ' + fmt(ctx.raw) } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { ticks: { callback: (v) => fmt(v), font: { size: 10 } }, grid: { color: '#f1f1f1' } }
            }
        }
    });

    // Distribución por estado
    const estadoColors = {
        cotizado: '#f7a831',
        en_progreso: '#007BFF',
        pausado: '#6c757d',
        completado: '#28a745',
        cancelado: '#dc3545'
    };
    const estadoLabels = {
        cotizado: 'Cotizado',
        en_progreso: 'En Progreso',
        pausado: 'Pausado',
        completado: 'Completado',
        cancelado: 'Cancelado'
    };
    const estadoData = @json($porEstado);
    const estadoKeys = Object.keys(estadoData);

    new Chart(document.getElementById('chartEstado'), {
        type: 'doughnut',
        data: {
            labels: estadoKeys.map(k => estadoLabels[k] || k),
            datasets: [{
                data: estadoKeys.map(k => estadoData[k]),
                backgroundColor: estadoKeys.map(k => estadoColors[k] || '#ccc'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { usePointStyle: true, padding: 10, font: { size: 11 } } } }
        }
    });

    // Distribución por fuente
    const fuenteData = @json($porFuente);
    const fuenteLabels = { directo: 'Directo', workana: 'Workana' };
    const fuenteKeys = Object.keys(fuenteData);

    new Chart(document.getElementById('chartFuente'), {
        type: 'doughnut',
        data: {
            labels: fuenteKeys.map(k => fuenteLabels[k] || k),
            datasets: [{
                data: fuenteKeys.map(k => fuenteData[k]),
                backgroundColor: ['#0056b3', '#f7a831', '#28a745', '#6c757d'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { usePointStyle: true, padding: 10, font: { size: 11 } } } }
        }
    });
})();
</script>
@endsection
