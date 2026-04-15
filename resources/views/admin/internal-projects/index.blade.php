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
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ip-container {
        background: var(--light-gray);
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    .ip-header {
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

    .ip-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ip-header p { margin: 0; opacity: 0.85; font-size: 0.88rem; }

    .btn-new {
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

    .btn-new:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; transform: translateY(-2px); }

    /* Stats operacionales */
    .ip-stats-op {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .ip-stat {
        background: var(--white);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: var(--shadow-soft);
        text-align: center;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .ip-stat-num { font-size: 1.5rem; font-weight: 800; color: var(--dark-text); }
    .ip-stat-label { font-size: 0.72rem; color: #888; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px; margin: 0; }
    .ip-stat.alert .ip-stat-num { color: var(--danger); }

    /* Stats financieros */
    .ip-stats-fin {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .ip-fin-card {
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        color: white;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .ip-fin-card.cobrar { background: var(--gradient-danger); }
    .ip-fin-card.pagar { background: var(--gradient-purple); }
    .ip-fin-card.utilidad { background: var(--gradient-success); }
    .ip-fin-card.utilidad.negative { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }

    .ip-fin-label { font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; opacity: 0.9; display: flex; align-items: center; gap: 0.4rem; }
    .ip-fin-value { font-size: 1.6rem; font-weight: 800; line-height: 1.1; }
    .ip-fin-meta { font-size: 0.78rem; opacity: 0.9; }
    .ip-fin-icon { position: absolute; right: 1rem; top: 1rem; font-size: 2.4rem; opacity: 0.18; }

    /* Filtros */
    .ip-filters-wrap {
        background: var(--white);
        padding: 1rem 1.25rem;
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
        margin-bottom: 1rem;
    }

    .ip-filters {
        display: grid;
        grid-template-columns: 2fr repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.6rem;
        align-items: center;
    }

    .ip-filters-row2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.6rem;
        margin-top: 0.6rem;
    }

    .ip-filters select, .ip-filters input,
    .ip-filters-row2 select, .ip-filters-row2 input {
        padding: 0.55rem 0.8rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 0.82rem;
        background: var(--white);
        color: var(--dark-text);
        transition: var(--transition);
        width: 100%;
    }

    .ip-filters select:focus, .ip-filters input:focus,
    .ip-filters-row2 select:focus { border-color: var(--primary-blue); outline: none; }

    .btn-filter {
        padding: 0.55rem 1rem;
        border-radius: 10px;
        border: none;
        background: var(--gradient-blue);
        color: white;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        justify-content: center;
    }

    .btn-filter:hover { transform: translateY(-1px); }

    .btn-clear {
        padding: 0.55rem 1rem;
        border-radius: 10px;
        border: 1px solid #ddd;
        background: var(--white);
        color: #666;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-clear:hover { background: #f1f1f1; color: #333; text-decoration: none; }

    /* Chips de filtros activos */
    .ip-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.8rem;
        padding-top: 0.8rem;
        border-top: 1px dashed #e9ecef;
    }
    .ip-chip {
        background: #e7f1ff;
        color: #0056b3;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border: 1px solid rgba(0,123,255,0.15);
        transition: var(--transition);
    }
    .ip-chip:hover { background: #d0e3ff; color: #003d82; text-decoration: none; }
    .ip-chip i { font-size: 0.65rem; }

    /* Alert */
    .alert-success {
        background: var(--white);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.2);
        border-left: 4px solid #28a745;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        box-shadow: var(--shadow-soft);
    }

    /* Project Cards */
    .ip-list { display: grid; gap: 0.85rem; }

    .ip-card {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        display: grid;
        grid-template-columns: 1fr 200px;
        gap: 1.25rem;
        align-items: center;
        text-decoration: none;
        color: var(--dark-text);
    }

    .ip-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(0,123,255,0.12);
        text-decoration: none;
        color: var(--dark-text);
    }

    .ip-card-body { min-width: 0; }

    .ip-card-top {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.35rem;
        flex-wrap: wrap;
    }

    .ip-card-name { font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--dark-text); }

    .estado-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 8px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .fuente-badge {
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(0,0,0,0.06);
        color: #666;
    }

    .flag-badge {
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-size: 0.66rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .flag-badge.cobrar { background: rgba(220,53,69,0.12); color: #c92a2a; }
    .flag-badge.pagar-dev { background: rgba(124,58,237,0.12); color: #6b21a8; }
    .flag-badge.sin-dev { background: rgba(247,168,49,0.15); color: #b76f00; }
    .flag-badge.recurrente { background: rgba(0,123,255,0.12); color: #0056b3; }

    .ip-card-client {
        font-size: 0.85rem;
        color: #777;
        margin: 0.2rem 0 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
    }

    .ip-card-client i { font-size: 0.75rem; color: #aaa; }

    .ip-card-meta {
        display: flex;
        gap: 1.1rem;
        flex-wrap: wrap;
    }

    .ip-meta-item {
        font-size: 0.78rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .ip-meta-item i { font-size: 0.72rem; color: #aaa; }
    .ip-meta-item strong { color: var(--dark-text); }

    /* Finance panel (right side) */
    .ip-finance {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 180px;
    }

    .ip-price {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--dark-text);
        text-align: right;
        line-height: 1.1;
    }
    .ip-price small { font-size: 0.7rem; color: #999; font-weight: 600; }

    .progress-row { display: flex; flex-direction: column; gap: 0.2rem; }
    .progress-label {
        font-size: 0.7rem;
        color: #777;
        display: flex;
        justify-content: space-between;
        font-weight: 600;
    }
    .progress-label .label-name { color: #999; }
    .progress-label .label-pct { color: var(--dark-text); }
    .ip-progress-bar {
        width: 100%;
        height: 6px;
        background: #eee;
        border-radius: 3px;
        overflow: hidden;
    }
    .ip-progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    .saldo-line {
        font-size: 0.72rem;
        text-align: right;
        color: #888;
        display: flex;
        justify-content: space-between;
        gap: 0.4rem;
    }
    .saldo-line strong { font-weight: 700; }
    .saldo-line.alert strong { color: var(--danger); }
    .saldo-line.pagar-dev strong { color: var(--purple); }

    /* Empty */
    .ip-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
    }

    .ip-empty i { font-size: 3rem; color: #ddd; margin-bottom: 0.75rem; display: block; }
    .ip-empty h3 { color: #888; font-weight: 600; margin-bottom: 0.5rem; }
    .ip-empty p { color: #aaa; }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--white);
        padding: 0.85rem 1.25rem;
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .pagination-info { font-size: 0.82rem; color: #666; font-weight: 500; }
    .per-page-select {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #666;
    }
    .per-page-select select {
        padding: 0.35rem 0.6rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.8rem;
        background: var(--white);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .ip-container { padding: 1rem; }
        .ip-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .ip-card { grid-template-columns: 1fr; }
        .ip-finance { min-width: auto; }
        .ip-price { text-align: left; }
        .saldo-line { justify-content: flex-start; }
        .ip-stats-op { grid-template-columns: repeat(2, 1fr); }
        .ip-stats-fin { grid-template-columns: 1fr; }
        .ip-filters { grid-template-columns: 1fr; }
        .pagination-wrapper { flex-direction: column; align-items: stretch; }
    }
</style>

@php
    $fmt = function ($val, $moneda = 'COP') {
        $prefix = $moneda === 'USD' ? 'US$' : '$';
        return $prefix . number_format((float) $val, 0, ',', '.') . ($moneda === 'USD' ? ' USD' : '');
    };
    $fmtCop = fn ($v) => '$' . number_format((float) $v, 0, ',', '.');

    $activeFilters = [];
    if (request('estado')) $activeFilters['estado'] = 'Estado: ' . ucfirst(str_replace('_', ' ', request('estado')));
    if (request('fuente')) $activeFilters['fuente'] = 'Fuente: ' . ucfirst(request('fuente'));
    if (request('moneda')) $activeFilters['moneda'] = 'Moneda: ' . request('moneda');
    if (request('periodo')) {
        $periodoLabels = ['mes_actual' => 'Este mes', 'mes_anterior' => 'Mes anterior', 'este_anio' => 'Este año'];
        $activeFilters['periodo'] = 'Período: ' . ($periodoLabels[request('periodo')] ?? request('periodo'));
    }
    if (request('desarrollador')) $activeFilters['desarrollador'] = 'Dev: ' . request('desarrollador');
    if (request('cobro')) {
        $cobroLabels = ['pendiente' => 'Me deben', 'pagado_total' => 'Pagado total', 'sin_cobros' => 'Sin cobros'];
        $activeFilters['cobro'] = 'Cobro: ' . ($cobroLabels[request('cobro')] ?? request('cobro'));
    }
    if (request('pago_dev')) {
        $pagoDevLabels = ['pendiente' => 'Debo al dev', 'al_dia' => 'Dev al día', 'sin_dev_asignado' => 'Sin desarrollador'];
        $activeFilters['pago_dev'] = 'Pago dev: ' . ($pagoDevLabels[request('pago_dev')] ?? request('pago_dev'));
    }
    if (request('recurrente')) $activeFilters['recurrente'] = 'Recurrente: ' . (request('recurrente') === 'si' ? 'Sí' : 'No');
    if (request('buscar')) $activeFilters['buscar'] = 'Búsqueda: "' . request('buscar') . '"';
    if (request('orden') && request('orden') !== 'reciente') {
        $ordenLabels = [
            'mayor_saldo_cliente' => 'Mayor saldo cliente',
            'mayor_deuda_dev' => 'Mayor deuda dev',
            'mayor_precio' => 'Mayor precio',
            'fecha_entrega' => 'Próxima entrega',
        ];
        $activeFilters['orden'] = 'Orden: ' . ($ordenLabels[request('orden')] ?? request('orden'));
    }
@endphp

<div class="ip-container">
    <div class="ip-header">
        <div>
            <h1><i class="fas fa-briefcase"></i> Mis Proyectos</h1>
            <p>Gestion interna de proyectos, clientes, pagos y desarrolladores</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.internal-projects.stats') }}" class="btn-new" style="background: rgba(255,255,255,0.15);">
                <i class="fas fa-chart-pie"></i> Estadísticas
            </a>
            <a href="{{ route('admin.internal-projects.create') }}" class="btn-new">
                <i class="fas fa-plus-circle"></i> Nuevo Proyecto
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Stats operacionales --}}
    <div class="ip-stats-op">
        <div class="ip-stat">
            <div class="ip-stat-num">{{ $stats['total'] }}</div>
            <p class="ip-stat-label">Total</p>
        </div>
        <div class="ip-stat">
            <div class="ip-stat-num" style="color: #007BFF;">{{ $stats['activos'] }}</div>
            <p class="ip-stat-label">Activos</p>
        </div>
        <div class="ip-stat">
            <div class="ip-stat-num" style="color: #28a745;">{{ $stats['completados'] }}</div>
            <p class="ip-stat-label">Completados</p>
        </div>
        <div class="ip-stat {{ $stats['sin_desarrollador'] > 0 ? 'alert' : '' }}">
            <div class="ip-stat-num">{{ $stats['sin_desarrollador'] }}</div>
            <p class="ip-stat-label">Sin Dev</p>
        </div>
    </div>

    {{-- Stats financieros --}}
    <div class="ip-stats-fin">
        <div class="ip-fin-card cobrar">
            <i class="fas fa-hand-holding-usd ip-fin-icon"></i>
            <div class="ip-fin-label"><i class="fas fa-arrow-down"></i> Por cobrar (clientes)</div>
            <div class="ip-fin-value">{{ $fmtCop($stats['por_cobrar_cop']) }}</div>
            <div class="ip-fin-meta">{{ $stats['proyectos_con_deuda'] }} proyectos con saldo pendiente</div>
        </div>
        <div class="ip-fin-card pagar">
            <i class="fas fa-laptop-code ip-fin-icon"></i>
            <div class="ip-fin-label"><i class="fas fa-arrow-up"></i> Por pagar a desarrolladores</div>
            <div class="ip-fin-value">{{ $fmtCop($stats['por_pagar_dev_cop']) }}</div>
            <div class="ip-fin-meta">{{ $stats['devs_con_saldo'] }} desarrolladores con saldo</div>
        </div>
        <div class="ip-fin-card utilidad {{ $stats['utilidad_mes'] < 0 ? 'negative' : '' }}">
            <i class="fas fa-chart-line ip-fin-icon"></i>
            <div class="ip-fin-label"><i class="fas fa-coins"></i> Utilidad del mes</div>
            <div class="ip-fin-value">{{ $fmtCop($stats['utilidad_mes']) }}</div>
            <div class="ip-fin-meta">Ingresos: {{ $fmtCop($stats['ingresos_mes']) }} · {{ $stats['proyectos_mes'] }} proyectos activos</div>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" class="ip-filters-wrap">
        <div class="ip-filters">
            <input type="text" name="buscar" placeholder="Buscar proyecto, cliente o desarrollador..." value="{{ request('buscar') }}" aria-label="Buscar">
            <select name="estado" aria-label="Filtrar por estado">
                <option value="">Todos los estados</option>
                <option value="cotizado" {{ request('estado') == 'cotizado' ? 'selected' : '' }}>Cotizado</option>
                <option value="en_progreso" {{ request('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                <option value="pausado" {{ request('estado') == 'pausado' ? 'selected' : '' }}>Pausado</option>
                <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
            <select name="fuente" aria-label="Filtrar por fuente">
                <option value="">Todas las fuentes</option>
                <option value="directo" {{ request('fuente') == 'directo' ? 'selected' : '' }}>Directo</option>
                <option value="workana" {{ request('fuente') == 'workana' ? 'selected' : '' }}>Workana</option>
            </select>
            <select name="periodo" aria-label="Filtrar por período">
                <option value="">Cualquier período</option>
                <option value="mes_actual" {{ request('periodo') == 'mes_actual' ? 'selected' : '' }}>Este mes</option>
                <option value="mes_anterior" {{ request('periodo') == 'mes_anterior' ? 'selected' : '' }}>Mes anterior</option>
                <option value="este_anio" {{ request('periodo') == 'este_anio' ? 'selected' : '' }}>Este año</option>
            </select>
        </div>

        <div class="ip-filters-row2">
            <select name="desarrollador" aria-label="Filtrar por desarrollador">
                <option value="">Todos los desarrolladores</option>
                @foreach($desarrolladores as $dev)
                    <option value="{{ $dev }}" {{ request('desarrollador') == $dev ? 'selected' : '' }}>{{ $dev }}</option>
                @endforeach
            </select>
            <select name="cobro" aria-label="Estado de cobro">
                <option value="">Cualquier cobro</option>
                <option value="pendiente" {{ request('cobro') == 'pendiente' ? 'selected' : '' }}>Me deben (pendiente)</option>
                <option value="pagado_total" {{ request('cobro') == 'pagado_total' ? 'selected' : '' }}>Pagado total</option>
                <option value="sin_cobros" {{ request('cobro') == 'sin_cobros' ? 'selected' : '' }}>Sin cobros aún</option>
            </select>
            <select name="pago_dev" aria-label="Estado pago desarrollador">
                <option value="">Cualquier pago dev</option>
                <option value="pendiente" {{ request('pago_dev') == 'pendiente' ? 'selected' : '' }}>Debo al dev</option>
                <option value="al_dia" {{ request('pago_dev') == 'al_dia' ? 'selected' : '' }}>Dev al día</option>
                <option value="sin_dev_asignado" {{ request('pago_dev') == 'sin_dev_asignado' ? 'selected' : '' }}>Sin dev asignado</option>
            </select>
            <select name="recurrente" aria-label="Filtrar recurrentes">
                <option value="">Recurrente: todos</option>
                <option value="si" {{ request('recurrente') == 'si' ? 'selected' : '' }}>Sí</option>
                <option value="no" {{ request('recurrente') == 'no' ? 'selected' : '' }}>No</option>
            </select>
            <select name="moneda" aria-label="Moneda">
                <option value="">Cualquier moneda</option>
                <option value="COP" {{ request('moneda') == 'COP' ? 'selected' : '' }}>COP</option>
                <option value="USD" {{ request('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
            </select>
            <select name="orden" aria-label="Ordenar por">
                <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más recientes</option>
                <option value="mayor_saldo_cliente" {{ request('orden') == 'mayor_saldo_cliente' ? 'selected' : '' }}>Mayor saldo cliente</option>
                <option value="mayor_deuda_dev" {{ request('orden') == 'mayor_deuda_dev' ? 'selected' : '' }}>Mayor deuda dev</option>
                <option value="mayor_precio" {{ request('orden') == 'mayor_precio' ? 'selected' : '' }}>Mayor precio</option>
                <option value="fecha_entrega" {{ request('orden') == 'fecha_entrega' ? 'selected' : '' }}>Próxima entrega</option>
            </select>
            <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Aplicar</button>
            @if(count($activeFilters) > 0)
                <a href="{{ route('admin.internal-projects.index') }}" class="btn-clear"><i class="fas fa-times"></i> Limpiar</a>
            @endif
        </div>

        @if(count($activeFilters) > 0)
            <div class="ip-chips">
                @foreach($activeFilters as $key => $label)
                    <a href="{{ request()->fullUrlWithQuery([$key => null]) }}" class="ip-chip" title="Quitar filtro">
                        {{ $label }} <i class="fas fa-times"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </form>

    <div class="ip-list">
        @forelse($projects as $project)
            @php
                $totalPagado = $project->payments_sum_monto ?? 0;
                $porcentaje = $project->precio > 0 ? round(($totalPagado / $project->precio) * 100) : 0;
                $porcentaje = min($porcentaje, 100);
                $progressColor = $porcentaje >= 100 ? '#28a745' : ($porcentaje >= 50 ? '#007BFF' : '#f7a831');

                $saldoCliente = max((float) $project->precio - (float) $totalPagado, 0);
                $totalPagadoDev = $project->developer_payments_sum_monto ?? 0;
                $pagoDev = (float) ($project->desarrollador_pago ?? 0);
                $pctDev = $pagoDev > 0 ? min(round(($totalPagadoDev / $pagoDev) * 100), 100) : 0;
                $saldoDev = max($pagoDev - (float) $totalPagadoDev, 0);
                $devColor = $pctDev >= 100 ? '#10b981' : '#7c3aed';

                $estadoActivo = !in_array($project->estado, ['cancelado', 'completado']);
                $muestraCobrar = $saldoCliente > 0 && $project->estado !== 'cancelado';
                $muestraPagarDev = $saldoDev > 0 && $project->desarrollador_nombre;
                $muestraSinDev = !$project->desarrollador_nombre && $estadoActivo;
            @endphp
            <a href="{{ route('admin.internal-projects.show', $project) }}" class="ip-card">
                <div class="ip-card-body">
                    <div class="ip-card-top">
                        <h3 class="ip-card-name">{{ $project->nombre }}</h3>
                        <span class="estado-badge" style="background: {{ $project->estado_color }}15; color: {{ $project->estado_color }};">
                            <i class="fas fa-circle" style="font-size: 0.4rem;"></i>
                            {{ $project->estado_label }}
                        </span>
                        <span class="fuente-badge">{{ $project->fuente == 'workana' ? 'Workana' : 'Directo' }}</span>
                        @if($project->es_recurrente)
                            <span class="flag-badge recurrente"><i class="fas fa-sync-alt"></i> Recurrente</span>
                        @endif
                        @if($muestraCobrar)
                            <span class="flag-badge cobrar"><i class="fas fa-hand-holding-usd"></i> Me deben</span>
                        @endif
                        @if($muestraPagarDev)
                            <span class="flag-badge pagar-dev"><i class="fas fa-paper-plane"></i> Pagar dev</span>
                        @endif
                        @if($muestraSinDev)
                            <span class="flag-badge sin-dev"><i class="fas fa-exclamation-triangle"></i> Sin dev</span>
                        @endif
                    </div>
                    <p class="ip-card-client">
                        <i class="fas fa-user"></i> {{ $project->cliente_nombre }}
                        @if($project->cliente_contacto)
                            <span style="color:#ccc;">|</span>
                            <i class="fas fa-phone"></i> {{ $project->cliente_contacto }}
                        @endif
                    </p>
                    <div class="ip-card-meta">
                        @if($project->desarrollador_nombre)
                            <span class="ip-meta-item"><i class="fas fa-laptop-code"></i> <strong>{{ $project->desarrollador_nombre }}</strong></span>
                        @endif
                        @if($project->fecha_inicio)
                            <span class="ip-meta-item"><i class="fas fa-calendar"></i> {{ $project->fecha_inicio->format('d/m/Y') }}</span>
                        @endif
                        @if(!$project->es_recurrente && $project->fecha_entrega)
                            <span class="ip-meta-item"><i class="fas fa-flag-checkered"></i> {{ $project->fecha_entrega->format('d/m/Y') }}</span>
                        @endif
                        <span class="ip-meta-item"><i class="fas fa-file-alt"></i> {{ $project->files_count }} archivos</span>
                        <span class="ip-meta-item"><i class="fas fa-money-bill-wave"></i> {{ $project->payments_count }} pagos</span>
                        @if($pagoDev > 0)
                            <span class="ip-meta-item"><i class="fas fa-paper-plane"></i> {{ $project->developer_payments_count }} pagos dev</span>
                        @endif
                    </div>
                </div>

                <div class="ip-finance">
                    <div class="ip-price">
                        {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->precio, 0, ',', '.') }}
                        <small>{{ $project->moneda }}</small>
                    </div>

                    <div class="progress-row">
                        <div class="progress-label">
                            <span class="label-name">Cliente</span>
                            <span class="label-pct">{{ $porcentaje }}%</span>
                        </div>
                        <div class="ip-progress-bar">
                            <div class="ip-progress-fill" style="width: {{ $porcentaje }}%; background: {{ $progressColor }};"></div>
                        </div>
                    </div>

                    @if($saldoCliente > 0 && $project->estado !== 'cancelado')
                        <div class="saldo-line alert">
                            <span>Saldo:</span>
                            <strong>{{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($saldoCliente, 0, ',', '.') }}</strong>
                        </div>
                    @endif

                    @if($pagoDev > 0)
                        <div class="progress-row">
                            <div class="progress-label">
                                <span class="label-name">Dev</span>
                                <span class="label-pct">{{ $pctDev }}%</span>
                            </div>
                            <div class="ip-progress-bar">
                                <div class="ip-progress-fill" style="width: {{ $pctDev }}%; background: {{ $devColor }};"></div>
                            </div>
                        </div>
                        @if($saldoDev > 0)
                            <div class="saldo-line pagar-dev">
                                <span>Debo:</span>
                                <strong>{{ ($project->desarrollador_moneda ?? 'COP') == 'COP' ? '$' : 'US$' }}{{ number_format($saldoDev, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                    @endif
                </div>
            </a>
        @empty
            <div class="ip-empty">
                <i class="fas fa-briefcase"></i>
                <h3>No hay proyectos que coincidan con tu búsqueda</h3>
                <p>Ajusta los filtros o crea un nuevo proyecto para empezar.</p>
                <a href="{{ route('admin.internal-projects.create') }}" class="btn-new" style="background: var(--gradient-blue); border: none; margin-top: 1rem;">
                    <i class="fas fa-plus-circle"></i> Crear Proyecto
                </a>
            </div>
        @endforelse
    </div>

    @if($projects->total() > 0)
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Mostrando <strong>{{ $projects->firstItem() }}</strong>–<strong>{{ $projects->lastItem() }}</strong> de <strong>{{ $projects->total() }}</strong> proyectos
            </div>

            @if($projects->hasPages())
                <div>{{ $projects->links() }}</div>
            @endif

            <form method="GET" class="per-page-select">
                @foreach(request()->except(['per_page', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label for="per_page">Por página:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()">
                    @foreach([10, 15, 25, 50] as $n)
                        <option value="{{ $n }}" {{ (int) request('per_page', 15) === $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    @endif
</div>
@endsection
