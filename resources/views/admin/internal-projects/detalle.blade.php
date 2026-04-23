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
        --gradient-purple: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
        --gradient-success: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        --gradient-warning: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        --gradient-danger: linear-gradient(135deg, #ff6b6b 0%, #dc3545 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .det-container { background: var(--light-gray); max-width: 1440px; margin: 0 auto; padding: 2rem; min-height: 80vh; }

    .det-header {
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        margin-bottom: 1.5rem; padding: 1.75rem 2rem; background: var(--gradient-blue);
        border-radius: 16px; color: white;
    }
    .det-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem 0; color: white; display: flex; align-items: center; gap: 0.75rem; }
    .det-header p { margin: 0; opacity: 0.85; font-size: 0.88rem; }
    .btn-ghost { background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); border: 2px solid rgba(255,255,255,0.4); color: white; padding: 0.6rem 1.1rem; border-radius: 12px; font-weight: 600; text-decoration: none; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
    .btn-ghost:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; transform: translateY(-2px); }

    /* Filtros */
    .det-filters { background: var(--white); padding: 1rem 1.25rem; border-radius: 14px; box-shadow: var(--shadow-soft); margin-bottom: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; justify-content: space-between; }
    .det-chips { display: flex; flex-wrap: wrap; gap: 0.4rem; }
    .det-chip { padding: 0.4rem 0.9rem; border-radius: 20px; border: 1.5px solid #e9ecef; background: var(--white); color: #666; font-weight: 600; font-size: 0.78rem; text-decoration: none; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.35rem; }
    .det-chip:hover { border-color: var(--primary-blue); color: var(--primary-blue); text-decoration: none; }
    .det-chip.active { background: var(--gradient-blue); color: white; border-color: transparent; }
    .det-chip.active:hover { color: white; }
    .det-chip .count { background: rgba(255,255,255,0.3); padding: 0 0.4rem; border-radius: 10px; font-size: 0.72rem; }
    .det-chip:not(.active) .count { background: #f1f3f5; color: #666; }
    .det-search { display: flex; gap: 0.5rem; align-items: center; }
    .det-search input { padding: 0.5rem 0.85rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 0.85rem; width: 240px; transition: var(--transition); }
    .det-search input:focus { border-color: var(--primary-blue); outline: none; }
    .det-search button { padding: 0.5rem 0.95rem; border: none; border-radius: 10px; background: var(--gradient-blue); color: white; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; }
    .det-search a.clear { padding: 0.5rem 0.9rem; border: 1px solid #ddd; border-radius: 10px; color: #666; text-decoration: none; font-size: 0.82rem; font-weight: 600; background: white; }

    /* Tabla */
    .det-table-wrap { background: var(--white); border-radius: 14px; box-shadow: var(--shadow-soft); overflow: hidden; margin-bottom: 1.5rem; }
    .det-table-scroll { overflow-x: auto; }
    .det-table { width: 100%; border-collapse: collapse; font-size: 0.83rem; min-width: 1200px; }
    .det-table thead th {
        position: sticky; top: 0; background: #f8fafc; z-index: 5;
        text-align: left; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.3px;
        color: #666; font-weight: 700; padding: 0.75rem 0.8rem; border-bottom: 2px solid #e9ecef; white-space: nowrap;
    }
    .det-table tbody td { padding: 0.75rem 0.8rem; border-bottom: 1px solid #f1f3f5; color: var(--dark-text); vertical-align: middle; }
    .det-table tbody tr { transition: background 0.2s; cursor: pointer; }
    .det-table tbody tr:hover { background: #fafbfc; }
    .det-table tbody tr:last-child td { border-bottom: none; }

    .det-proj-name { font-weight: 700; color: var(--dark-text); text-decoration: none; display: block; margin-bottom: 0.15rem; }
    .det-proj-name:hover { color: var(--primary-blue); text-decoration: none; }
    .det-badges { display: flex; flex-wrap: wrap; gap: 0.25rem; margin-top: 0.15rem; }
    .det-estado { padding: 0.1rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2px; }
    .det-fuente { padding: 0.1rem 0.45rem; border-radius: 6px; font-size: 0.62rem; font-weight: 700; text-transform: uppercase; background: rgba(0,0,0,0.06); color: #666; }

    .det-cliente { color: #666; font-size: 0.82rem; }

    .det-fechas { font-size: 0.78rem; color: #666; line-height: 1.4; white-space: nowrap; }
    .det-fechas i { color: #aaa; margin-right: 0.25rem; font-size: 0.7rem; }

    .mono { font-weight: 700; text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .mono .sub { display: block; font-size: 0.7rem; color: #aaa; font-weight: 500; margin-top: 0.1rem; }
    .mono.ing { color: #059669; }
    .mono.dev { color: var(--purple); }
    .mono.gas { color: #b45309; }
    .mono.rojo { color: var(--danger); }
    .mono.verde { color: #059669; }
    .mono.mute { color: #bbb; }

    .det-empty { padding: 3rem 1rem; text-align: center; color: #aaa; }
    .det-empty i { font-size: 2.5rem; color: #ddd; display: block; margin-bottom: 0.5rem; }

    .det-pagination { padding: 0.85rem 1.25rem; background: #fafbfc; border-top: 1px solid #f1f3f5; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem; font-size: 0.82rem; color: #666; }

    /* Resumen empresa */
    .resumen-section-title { font-size: 1.1rem; font-weight: 800; color: var(--dark-text); margin: 1.5rem 0 1rem 0; display: flex; align-items: center; gap: 0.6rem; }

    .resumen-grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
    .resumen-card { border-radius: 14px; padding: 1.25rem 1.5rem; color: white; box-shadow: var(--shadow-soft); position: relative; overflow: hidden; }
    .resumen-card.ing { background: var(--gradient-success); }
    .resumen-card.dev { background: var(--gradient-purple); }
    .resumen-card.gas { background: var(--gradient-warning); }
    .resumen-card.uti { background: var(--gradient-blue); }
    .resumen-card.uti.negative { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
    .resumen-label { font-size: 0.72rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; opacity: 0.9; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem; }
    .resumen-value { font-size: 1.7rem; font-weight: 800; line-height: 1.1; }
    .resumen-meta { font-size: 0.76rem; opacity: 0.9; margin-top: 0.25rem; }
    .resumen-icon { position: absolute; right: 1rem; top: 1rem; font-size: 2.4rem; opacity: 0.18; }

    .resumen-grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
    .resumen-mini { background: var(--white); border-radius: 12px; padding: 1rem 1.25rem; box-shadow: var(--shadow-soft); border-left: 4px solid var(--primary-blue); }
    .resumen-mini.rojo { border-left-color: var(--danger); }
    .resumen-mini.morado { border-left-color: var(--purple); }
    .resumen-mini .resumen-label { color: #888; opacity: 1; }
    .resumen-mini .resumen-value { color: var(--dark-text); font-size: 1.3rem; }
    .resumen-mini.rojo .resumen-value { color: var(--danger); }
    .resumen-mini.morado .resumen-value { color: var(--purple); }
    .resumen-mini .resumen-meta { color: #aaa; }

    .resumen-estados { background: var(--white); border-radius: 12px; padding: 1rem 1.25rem; box-shadow: var(--shadow-soft); margin-bottom: 1rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.75rem; }
    .est-box { text-align: center; padding: 0.5rem; border-right: 1px solid #f1f3f5; }
    .est-box:last-child { border-right: none; }
    .est-box-num { font-size: 1.4rem; font-weight: 800; color: var(--dark-text); line-height: 1; }
    .est-box-label { font-size: 0.7rem; text-transform: uppercase; color: #888; font-weight: 600; margin-top: 0.3rem; letter-spacing: 0.3px; }

    .resumen-grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; }
    .resumen-top { background: var(--white); border-radius: 12px; padding: 1.1rem 1.3rem; box-shadow: var(--shadow-soft); display: flex; align-items: center; gap: 1rem; }
    .resumen-top-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: white; flex-shrink: 0; }
    .resumen-top-icon.verde { background: var(--gradient-success); }
    .resumen-top-icon.azul { background: var(--gradient-blue); }
    .resumen-top-body { flex: 1; min-width: 0; }
    .resumen-top-label { font-size: 0.7rem; text-transform: uppercase; color: #888; font-weight: 700; letter-spacing: 0.3px; margin-bottom: 0.15rem; }
    .resumen-top-name { font-size: 1rem; font-weight: 700; color: var(--dark-text); margin-bottom: 0.15rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .resumen-top-name a { color: var(--dark-text); text-decoration: none; }
    .resumen-top-name a:hover { color: var(--primary-blue); }
    .resumen-top-value { font-size: 0.88rem; font-weight: 700; color: var(--primary-blue); }
    .resumen-top-value.verde { color: #059669; }

    /* Filtros extra */
    .det-filters-row2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.6rem; padding: 0.75rem 1.25rem 1rem; border-top: 1px dashed #e9ecef; }
    .det-filters-row2 select { padding: 0.5rem 0.8rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 0.82rem; background: var(--white); color: var(--dark-text); width: 100%; transition: var(--transition); }
    .det-filters-row2 select:focus { border-color: var(--primary-blue); outline: none; }
    .det-filters-wrap { display: flex; flex-direction: column; }

    /* Columnas toggle — dropdown moderno con switches */
    .cols-dropdown { position: relative; display: inline-block; }
    .btn-cols {
        padding: 0.5rem 0.9rem; border: 1.5px solid #e9ecef; background: white; color: #555;
        font-weight: 600; font-size: 0.82rem; border-radius: 10px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.45rem; transition: var(--transition);
    }
    .btn-cols:hover { border-color: var(--primary-blue); color: var(--primary-blue); background: #f7faff; }
    .btn-cols .cols-counter {
        background: var(--primary-blue); color: white; font-size: 0.68rem; font-weight: 700;
        padding: 0.05rem 0.45rem; border-radius: 10px; line-height: 1.4;
    }
    .btn-cols .chev { font-size: 0.7rem; opacity: 0.7; transition: transform 0.2s; }
    .cols-dropdown.open .btn-cols .chev { transform: rotate(180deg); }
    .cols-dropdown.open .btn-cols { border-color: var(--primary-blue); color: var(--primary-blue); }

    .cols-menu {
        position: absolute; right: 0; top: calc(100% + 8px);
        background: white; border-radius: 14px;
        box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(0,0,0,0.04);
        width: 280px; z-index: 30; display: none;
        overflow: hidden;
        animation: colsMenuIn 0.18s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .cols-menu.open { display: block; }
    @keyframes colsMenuIn {
        from { opacity: 0; transform: translateY(-6px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .cols-menu-head {
        padding: 0.85rem 1rem 0.7rem; border-bottom: 1px solid #f1f3f5;
        display: flex; justify-content: space-between; align-items: center;
    }
    .cols-menu-head h4 {
        font-size: 0.78rem; font-weight: 700; color: var(--dark-text);
        margin: 0; display: flex; align-items: center; gap: 0.45rem;
    }
    .cols-menu-head h4 i { color: var(--primary-blue); font-size: 0.82rem; }
    .cols-menu-head .cols-sub { font-size: 0.7rem; color: #aaa; font-weight: 500; }

    .cols-menu-body { padding: 0.4rem 0.5rem; max-height: 320px; overflow-y: auto; }

    /* Fila de columna: icon + nombre + switch */
    .cols-menu-body label {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.55rem 0.65rem; border-radius: 9px;
        cursor: pointer; transition: background 0.15s;
        font-size: 0.85rem; color: var(--dark-text); font-weight: 500;
    }
    .cols-menu-body label:hover { background: #f8fafc; }
    .cols-menu-body label .col-icon {
        width: 26px; height: 26px; border-radius: 7px;
        background: #f1f5f9; color: #64748b;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.72rem; flex-shrink: 0;
    }
    .cols-menu-body label.is-checked .col-icon { background: rgba(0,123,255,0.12); color: var(--primary-blue); }
    .cols-menu-body label .col-name { flex: 1; }
    .cols-menu-body label.is-checked .col-name { color: var(--dark-text); font-weight: 600; }
    .cols-menu-body label:not(.is-checked) .col-name { color: #94a3b8; }

    /* Toggle switch */
    .cols-menu-body input[type="checkbox"] {
        appearance: none; -webkit-appearance: none;
        width: 34px; height: 20px; background: #cbd5e1; border-radius: 999px;
        position: relative; cursor: pointer; transition: background 0.2s;
        flex-shrink: 0;
    }
    .cols-menu-body input[type="checkbox"]::before {
        content: ''; position: absolute; top: 2px; left: 2px;
        width: 16px; height: 16px; border-radius: 50%; background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        transition: transform 0.2s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .cols-menu-body input[type="checkbox"]:checked { background: var(--primary-blue); }
    .cols-menu-body input[type="checkbox"]:checked::before { transform: translateX(14px); }

    .cols-menu-actions {
        border-top: 1px solid #f1f3f5; padding: 0.6rem 0.6rem;
        display: flex; gap: 0.4rem; background: #fafbfc;
    }
    .cols-menu-actions button {
        flex: 1; padding: 0.5rem 0.6rem; border: none; border-radius: 8px;
        background: white; color: #555; font-size: 0.78rem; font-weight: 600;
        cursor: pointer; transition: var(--transition);
        display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem;
        border: 1px solid #e9ecef;
    }
    .cols-menu-actions button:hover { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
    .cols-menu-actions button.secondary:hover { background: #64748b; border-color: #64748b; }

    /* tfoot de totales */
    .det-table tfoot td {
        position: sticky; bottom: 0; background: #f8fafc; z-index: 4;
        padding: 0.85rem 0.8rem; border-top: 2px solid #e9ecef; font-weight: 800; font-size: 0.85rem;
    }
    .det-table tfoot td.total-label { color: #888; text-transform: uppercase; letter-spacing: 0.3px; font-size: 0.72rem; }

    @media (max-width: 768px) {
        .det-container { padding: 1rem; }
        .det-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .det-filters { flex-direction: column; align-items: stretch; }
        .det-search { flex-wrap: wrap; }
        .det-search input { width: 100%; }
        .resumen-estados { grid-template-columns: repeat(2, 1fr); }
        .est-box { border-right: none; border-bottom: 1px solid #f1f3f5; padding-bottom: 0.75rem; }
    }
</style>

@php
    $fmtCop = fn ($v) => '$' . number_format((float) $v, 0, ',', '.');
    $fmtMoneda = fn ($v, $m) => ($m === 'USD' ? 'US$' : '$') . number_format((float) $v, 0, ',', '.') . ($m === 'USD' ? ' USD' : '');
    $estadoChips = [
        '' => 'Todos',
        'en_progreso' => 'Activos',
        'completado' => 'Completados',
        'pausado' => 'Pausados',
        'cotizado' => 'Cotizados',
        'cancelado' => 'Cancelados',
    ];
@endphp

<div class="det-container">
    <div class="det-header">
        <div>
            <h1><i class="fas fa-table"></i> Detalle de proyectos</h1>
            <p>Vista proyecto-por-proyecto con pagos, abonos y gastos · resumen de empresa al final</p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.internal-projects.index') }}" class="btn-ghost">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
            <a href="{{ route('admin.internal-projects.stats') }}" class="btn-ghost">
                <i class="fas fa-chart-pie"></i> Estadísticas
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" class="det-filters-wrap" style="background: var(--white); border-radius: 14px; box-shadow: var(--shadow-soft); margin-bottom: 1.25rem;">
        <div class="det-filters" style="box-shadow:none; margin-bottom:0; border-radius:0; background:transparent;">
            <div class="det-chips">
                @foreach($estadoChips as $key => $label)
                    <a href="{{ route('admin.internal-projects.detalle', array_filter(['estado' => $key, 'buscar' => $filters['buscar'], 'desarrollador' => $filters['desarrollador'], 'fuente' => $filters['fuente'], 'vendedor' => $filters['vendedor'], 'orden' => $filters['orden'] !== 'reciente' ? $filters['orden'] : null])) }}"
                       class="det-chip {{ $filters['estado'] === $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="det-search">
                @if($filters['estado'])<input type="hidden" name="estado" value="{{ $filters['estado'] }}">@endif
                <input type="text" name="buscar" placeholder="Buscar proyecto, cliente o dev..." value="{{ $filters['buscar'] }}">
                <div class="cols-dropdown" id="colsDropdown">
                    <button type="button" class="btn-cols" id="colsBtn">
                        <i class="fas fa-columns"></i>
                        <span>Columnas</span>
                        <span class="cols-counter" id="colsCounter">10</span>
                        <i class="fas fa-chevron-down chev"></i>
                    </button>
                    <div class="cols-menu" id="colsMenu">
                        <div class="cols-menu-head">
                            <h4><i class="fas fa-sliders-h"></i> Mostrar columnas</h4>
                            <span class="cols-sub"><span id="colsVisibleCount">10</span> de 10</span>
                        </div>
                        <div class="cols-menu-body">
                            @php
                                $colDefs = [
                                    ['cliente', 'fa-user', 'Cliente'],
                                    ['fechas', 'fa-calendar', 'Fechas'],
                                    ['precio', 'fa-tag', 'Precio'],
                                    ['cobrado', 'fa-hand-holding-usd', 'Cobrado'],
                                    ['saldo_cli', 'fa-balance-scale', 'Saldo cliente'],
                                    ['pago_dev', 'fa-laptop-code', 'Pago dev'],
                                    ['abonado', 'fa-paper-plane', 'Abonado dev'],
                                    ['saldo_dev', 'fa-wallet', 'Saldo dev'],
                                    ['gestion', 'fa-handshake', 'Gestión'],
                                    ['gastos', 'fa-receipt', 'Gastos'],
                                    ['utilidad', 'fa-chart-line', 'Utilidad'],
                                ];
                            @endphp
                            @foreach($colDefs as [$col, $icon, $label])
                                <label class="is-checked" data-col-row="{{ $col }}">
                                    <span class="col-icon"><i class="fas {{ $icon }}"></i></span>
                                    <span class="col-name">{{ $label }}</span>
                                    <input type="checkbox" data-col="{{ $col }}" checked>
                                </label>
                            @endforeach
                        </div>
                        <div class="cols-menu-actions">
                            <button type="button" onclick="detalleCols.setAll(true)"><i class="fas fa-eye"></i> Mostrar todo</button>
                            <button type="button" class="secondary" onclick="detalleCols.setAll(false)"><i class="fas fa-eye-slash"></i> Ocultar todo</button>
                        </div>
                    </div>
                </div>
                <button type="submit"><i class="fas fa-search"></i> Aplicar</button>
                @if($filters['buscar'] || $filters['desarrollador'] || $filters['fuente'] || $filters['vendedor'] || $filters['orden'] !== 'reciente' || (int) $filters['per_page'] !== 30)
                    <a href="{{ route('admin.internal-projects.detalle', array_filter(['estado' => $filters['estado']])) }}" class="clear">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                @endif
            </div>
        </div>

        <div class="det-filters-row2">
            <select name="desarrollador" aria-label="Desarrollador">
                <option value="">Todos los devs</option>
                @foreach($desarrolladores as $dev)
                    <option value="{{ $dev }}" {{ $filters['desarrollador'] === $dev ? 'selected' : '' }}>{{ $dev }}</option>
                @endforeach
            </select>
            <select name="fuente" aria-label="Fuente">
                <option value="">Cualquier fuente</option>
                <option value="directo" {{ $filters['fuente'] === 'directo' ? 'selected' : '' }}>Directo</option>
                <option value="workana" {{ $filters['fuente'] === 'workana' ? 'selected' : '' }}>Workana</option>
            </select>
            <select name="vendedor" aria-label="Vendedor / gestor">
                <option value="">Todos los gestores</option>
                <option value="sin" {{ $filters['vendedor'] === 'sin' ? 'selected' : '' }}>Sin gestor</option>
                @foreach($vendedores as $v)
                    <option value="{{ $v->id }}" {{ (string) $filters['vendedor'] === (string) $v->id ? 'selected' : '' }}>{{ $v->nombre }}</option>
                @endforeach
            </select>
            <select name="orden" aria-label="Orden">
                <option value="reciente" {{ $filters['orden'] === 'reciente' ? 'selected' : '' }}>Más recientes</option>
                <option value="nombre" {{ $filters['orden'] === 'nombre' ? 'selected' : '' }}>Nombre (A-Z)</option>
                <option value="mayor_precio" {{ $filters['orden'] === 'mayor_precio' ? 'selected' : '' }}>Mayor precio</option>
                <option value="mayor_saldo_cliente" {{ $filters['orden'] === 'mayor_saldo_cliente' ? 'selected' : '' }}>Mayor saldo cliente</option>
                <option value="mayor_saldo_dev" {{ $filters['orden'] === 'mayor_saldo_dev' ? 'selected' : '' }}>Mayor deuda dev</option>
                <option value="fecha_entrega" {{ $filters['orden'] === 'fecha_entrega' ? 'selected' : '' }}>Próxima entrega</option>
            </select>
            <select name="per_page" aria-label="Por página">
                @foreach([15, 30, 50, 100] as $n)
                    <option value="{{ $n }}" {{ (int) $filters['per_page'] === $n ? 'selected' : '' }}>{{ $n }} por página</option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="det-table-wrap">
        <div class="det-table-scroll">
            <table class="det-table" id="detTable">
                <thead>
                    <tr>
                        <th data-col="proyecto">Proyecto</th>
                        <th data-col="cliente">Cliente</th>
                        <th data-col="fechas">Fechas</th>
                        <th data-col="precio" style="text-align:right;">Precio</th>
                        <th data-col="cobrado" style="text-align:right;">Cobrado</th>
                        <th data-col="saldo_cli" style="text-align:right;">Saldo cli.</th>
                        <th data-col="pago_dev" style="text-align:right;">Pago dev</th>
                        <th data-col="abonado" style="text-align:right;">Abonado</th>
                        <th data-col="saldo_dev" style="text-align:right;">Saldo dev</th>
                        <th data-col="gestion" style="text-align:right;" title="Comisión al vendedor (pactada vs abonada)">Gestión</th>
                        <th data-col="gastos" style="text-align:right;">Gastos</th>
                        <th data-col="utilidad" style="text-align:right;" title="Utilidad = Cobrado − (Pago dev asignado) − Comisión gestión − Gastos">Utilidad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $p)
                        @php
                            $moneda = $p->moneda;
                            $devMoneda = $p->desarrollador_moneda ?? 'COP';
                            $cobrado = (float) ($p->payments_sum ?? 0);
                            $saldoCli = max((float) $p->precio - $cobrado, 0);
                            $pagoDev = (float) ($p->desarrollador_pago ?? 0);
                            $abonadoDev = (float) ($p->developer_payments_sum ?? 0);
                            $saldoDev = max($pagoDev - $abonadoDev, 0);
                            $gastos = (float) ($p->expenses_sum ?? 0);

                            // Comisión de gestión en moneda del proyecto
                            $comision = 0;
                            if ($p->comision_tipo && $p->comision_valor) {
                                if ($p->comision_tipo === 'monto') {
                                    $comision = (float) $p->comision_valor;
                                } else {
                                    $pagoDevEnMoneda = $pagoDev;
                                    if ($devMoneda !== $moneda) {
                                        $pagoDevEnMoneda = $devMoneda === 'USD' && $moneda === 'COP'
                                            ? $pagoDev * $usdCop
                                            : ($devMoneda === 'COP' && $moneda === 'USD' ? $pagoDev / $usdCop : $pagoDev);
                                    }
                                    $comision = max((float) $p->precio - $pagoDevEnMoneda, 0) * ((float) $p->comision_valor / 100);
                                }
                            }
                            $abonadoGestion = (float) ($p->gestion_payments_sum ?? 0);
                            $saldoGestion = max($comision - $abonadoGestion, 0);

                            // Utilidad de caja = lo cobrado − lo que REALMENTE se pagó (dev + gestión) − gastos
                            // Si el proyecto es USD y registraste `monto_recibido_cop` (neto), usamos eso en vez de convertir.
                            $netoCopReal = (float) ($p->payments_sum_cop ?? 0);
                            $ingresoCop = $moneda === 'USD'
                                ? ($netoCopReal > 0 ? $netoCopReal : $cobrado * $usdCop)
                                : $cobrado;
                            $abonadoDevCop = $devMoneda === 'USD' ? $abonadoDev * $usdCop : $abonadoDev;
                            $abonadoGestionCop = $moneda === 'USD' ? $abonadoGestion * $usdCop : $abonadoGestion;
                            $gastosCop = $gastos;
                            $utilidad = $ingresoCop - $abonadoDevCop - $abonadoGestionCop - $gastosCop;
                        @endphp
                        <tr onclick="window.location='{{ route('admin.internal-projects.show', $p) }}'">
                            <td data-col="proyecto">
                                <a href="{{ route('admin.internal-projects.show', $p) }}" class="det-proj-name" onclick="event.stopPropagation();">{{ $p->nombre }}</a>
                                <div class="det-badges">
                                    <span class="det-estado" style="background: {{ $p->estado_color }}15; color: {{ $p->estado_color }};">{{ $p->estado_label }}</span>
                                    <span class="det-fuente">{{ $p->fuente == 'workana' ? 'Workana' : 'Directo' }}</span>
                                    @if($p->es_recurrente)<span class="det-fuente" style="background: rgba(0,123,255,0.12); color:#0056b3;">Recurrente</span>@endif
                                </div>
                            </td>
                            <td data-col="cliente"><div class="det-cliente">{{ $p->cliente_nombre }}</div>
                                @if($p->desarrollador_nombre)
                                    <div style="font-size:0.72rem; color:#aaa; margin-top:0.2rem;"><i class="fas fa-laptop-code"></i> {{ $p->desarrollador_nombre }}</div>
                                @endif
                            </td>
                            <td data-col="fechas">
                                <div class="det-fechas">
                                    @if($p->fecha_inicio)<div><i class="fas fa-play-circle"></i>{{ $p->fecha_inicio->format('d/m/y') }}</div>@endif
                                    @if($p->fecha_entrega && !$p->es_recurrente)<div><i class="fas fa-flag-checkered"></i>{{ $p->fecha_entrega->format('d/m/y') }}</div>@endif
                                    @if(!$p->fecha_inicio && !$p->fecha_entrega)<span style="color:#bbb;">—</span>@endif
                                </div>
                            </td>
                            <td class="mono" data-col="precio">{{ $fmtMoneda($p->precio, $moneda) }}</td>
                            <td class="mono ing" data-col="cobrado">
                                {{ $fmtMoneda($cobrado, $moneda) }}
                                @php
                                    if ($moneda === 'USD') {
                                        $netoCop = (float) ($p->payments_sum_cop ?? 0);
                                        $cobradoEquiv = $netoCop > 0 ? $netoCop : ($cobrado * $usdCop);
                                        $cobradoEquivLabel = $netoCop > 0 ? 'neto COP' : '≈ COP';
                                    } elseif ($cobrado > 0) {
                                        $cobradoEquiv = $cobrado / $usdCop;
                                        $cobradoEquivLabel = '≈ USD';
                                    } else {
                                        $cobradoEquiv = 0;
                                        $cobradoEquivLabel = '';
                                    }
                                @endphp
                                @if($cobradoEquiv > 0)
                                    <span class="sub" style="color:#059669; font-weight:600;">
                                        @if($moneda === 'USD')
                                            {{ $fmtCop($cobradoEquiv) }} {{ $cobradoEquivLabel }}
                                        @else
                                            US${{ number_format($cobradoEquiv, 0, ',', '.') }} {{ $cobradoEquivLabel }}
                                        @endif
                                    </span>
                                @endif
                                <span class="sub">{{ $p->payments_count }} pagos</span>
                            </td>
                            <td class="mono {{ $saldoCli > 0 && $p->estado !== 'cancelado' ? 'rojo' : 'mute' }}" data-col="saldo_cli">
                                {{ $fmtMoneda($saldoCli, $moneda) }}
                            </td>
                            <td class="mono" data-col="pago_dev">
                                @if($pagoDev > 0){{ $fmtMoneda($pagoDev, $devMoneda) }}@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono dev" data-col="abonado">
                                @if($pagoDev > 0 || $abonadoDev > 0){{ $fmtMoneda($abonadoDev, $devMoneda) }}<span class="sub">{{ $p->developer_payments_count }} pagos</span>@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono {{ $saldoDev > 0 ? 'dev' : 'mute' }}" data-col="saldo_dev">
                                @if($pagoDev > 0){{ $fmtMoneda($saldoDev, $devMoneda) }}@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono" data-col="gestion" style="color: {{ $saldoGestion > 0 ? '#059669' : ($comision > 0 ? '#94a3b8' : '#bbb') }};"
                                title="{{ $p->vendedor?->nombre ?? 'Sin vendedor' }}">
                                @if($comision > 0)
                                    {{ $fmtMoneda($saldoGestion, $moneda) }}
                                    <span class="sub">
                                        {{ $p->vendedor?->nombre ? \Illuminate\Support\Str::limit($p->vendedor->nombre, 14) : 'vendedor' }}
                                        @if($comision > 0) · {{ $fmtMoneda($comision, $moneda) }}@endif
                                    </span>
                                @elseif($p->vendedor_id)
                                    <span style="color:#bbb;">—</span>
                                    <span class="sub">{{ $p->vendedor?->nombre ? \Illuminate\Support\Str::limit($p->vendedor->nombre, 14) : '' }}</span>
                                @else
                                    <span style="color:#bbb;">—</span>
                                @endif
                            </td>
                            <td class="mono {{ $gastos > 0 ? 'gas' : 'mute' }}" data-col="gastos">
                                @if($gastos > 0){{ $fmtMoneda($gastos, 'COP') }}<span class="sub">{{ $p->expenses_count }}</span>@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono {{ $utilidad > 0 ? 'verde' : ($utilidad < 0 ? 'rojo' : 'mute') }}" data-col="utilidad"
                                title="Utilidad de caja: cobrado − abonado al dev − abonado gestión − gastos">
                                {{ $fmtCop($utilidad) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="det-empty">
                                <i class="fas fa-inbox"></i>
                                <p>No hay proyectos que coincidan con los filtros.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($projects->count() > 0)
                    @php
                        // Render helper para celdas cliente con COP + USD separados
                        $renderSplit = function ($cop, $usd, $cls = '') use ($fmtCop) {
                            $out = '';
                            $hasCop = $cop > 0;
                            $hasUsd = $usd > 0;
                            if ($hasCop && $hasUsd) {
                                $out .= '<div>' . $fmtCop($cop) . '</div>';
                                $out .= '<div class="sub" style="color:#059669; font-weight:700;">US$' . number_format($usd, 0, ',', '.') . ' USD</div>';
                            } elseif ($hasUsd) {
                                $out .= 'US$' . number_format($usd, 0, ',', '.') . ' <small style="font-size:0.72rem; color:#888; font-weight:500;">USD</small>';
                            } else {
                                $out .= $fmtCop($cop);
                            }
                            return $out;
                        };
                    @endphp
                    <tfoot>
                        <tr>
                            <td class="total-label" data-col="proyecto">Total página ({{ $projects->count() }} proyectos)</td>
                            <td data-col="cliente"></td>
                            <td data-col="fechas"></td>
                            <td class="mono" data-col="precio">
                                {!! $renderSplit($pageTotals['precio_cop_native'], $pageTotals['precio_usd_native']) !!}
                            </td>
                            <td class="mono ing" data-col="cobrado">
                                {!! $renderSplit($pageTotals['cobrado_cop_native'], $pageTotals['cobrado_usd_native']) !!}
                            </td>
                            <td class="mono {{ ($pageTotals['saldo_cliente_cop_native'] + $pageTotals['saldo_cliente_usd_native']) > 0 ? 'rojo' : 'mute' }}" data-col="saldo_cli">
                                {!! $renderSplit($pageTotals['saldo_cliente_cop_native'], $pageTotals['saldo_cliente_usd_native']) !!}
                            </td>
                            <td class="mono" data-col="pago_dev">{{ $fmtCop($pageTotals['pago_dev_cop']) }}</td>
                            <td class="mono dev" data-col="abonado">{{ $fmtCop($pageTotals['abonado_dev_cop']) }}</td>
                            <td class="mono {{ $pageTotals['saldo_dev_cop'] > 0 ? 'dev' : 'mute' }}" data-col="saldo_dev">{{ $fmtCop($pageTotals['saldo_dev_cop']) }}</td>
                            <td class="mono" data-col="gestion" style="color: {{ $pageTotals['saldo_gestion_cop'] > 0 ? '#059669' : '#bbb' }};">
                                {{ $fmtCop($pageTotals['saldo_gestion_cop']) }}
                                <span class="sub" style="color:#aaa;">de {{ $fmtCop($pageTotals['comision_cop']) }}</span>
                            </td>
                            <td class="mono {{ $pageTotals['gastos_cop'] > 0 ? 'gas' : 'mute' }}" data-col="gastos">{{ $fmtCop($pageTotals['gastos_cop']) }}</td>
                            <td class="mono {{ $pageTotals['utilidad_cop'] >= 0 ? 'verde' : 'rojo' }}" data-col="utilidad">{{ $fmtCop($pageTotals['utilidad_cop']) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        @if($projects->total() > 0)
            <div class="det-pagination">
                <span>Mostrando <strong>{{ $projects->firstItem() }}</strong>–<strong>{{ $projects->lastItem() }}</strong> de <strong>{{ $projects->total() }}</strong> proyectos</span>
                @if($projects->hasPages())
                    {{ $projects->links() }}
                @endif
            </div>
        @endif
    </div>

    {{-- Resumen de empresa --}}
    <h2 class="resumen-section-title"><i class="fas fa-building"></i> Resumen de la empresa</h2>

    <div class="resumen-grid-4">
        <div class="resumen-card ing">
            <i class="fas fa-hand-holding-usd resumen-icon"></i>
            <div class="resumen-label"><i class="fas fa-arrow-down"></i> Ingresos totales</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['total_ingresos']) }}</div>
            <div class="resumen-meta">todo lo cobrado a clientes</div>
        </div>
        <div class="resumen-card dev">
            <i class="fas fa-paper-plane resumen-icon"></i>
            <div class="resumen-label"><i class="fas fa-laptop-code"></i> Pagos a devs</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['total_pagado_devs']) }}</div>
            <div class="resumen-meta">suma de todas las transferencias</div>
        </div>
        <div class="resumen-card gas">
            <i class="fas fa-receipt resumen-icon"></i>
            <div class="resumen-label"><i class="fas fa-shopping-cart"></i> Gastos totales</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['total_gastos']) }}</div>
            <div class="resumen-meta">pagos extras de proyectos</div>
        </div>
        <div class="resumen-card uti {{ $companyTotals['utilidad_total'] < 0 ? 'negative' : '' }}">
            <i class="fas fa-coins resumen-icon"></i>
            <div class="resumen-label"><i class="fas fa-chart-line"></i> Utilidad total</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['utilidad_total']) }}</div>
            <div class="resumen-meta">Margen: {{ $companyTotals['margen'] }}%</div>
        </div>
    </div>

    <div class="resumen-grid-3">
        <div class="resumen-mini rojo">
            <div class="resumen-label"><i class="fas fa-hand-holding-usd"></i> Por cobrar a clientes</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['por_cobrar']) }}</div>
            <div class="resumen-meta">saldos pendientes activos</div>
        </div>
        <div class="resumen-mini morado">
            <div class="resumen-label"><i class="fas fa-paper-plane"></i> Por pagar a devs</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['por_pagar_dev']) }}</div>
            <div class="resumen-meta">saldos pendientes activos</div>
        </div>
        <div class="resumen-mini">
            <div class="resumen-label"><i class="fas fa-file-signature"></i> Total contratado</div>
            <div class="resumen-value">{{ $fmtCop($companyTotals['total_contratado']) }}</div>
            <div class="resumen-meta">valor de todos los proyectos</div>
        </div>
    </div>

    <div class="resumen-estados">
        <div class="est-box">
            <div class="est-box-num">{{ $companyTotals['proyectos_total'] }}</div>
            <div class="est-box-label">Total</div>
        </div>
        <div class="est-box">
            <div class="est-box-num" style="color: var(--primary-blue);">{{ $companyTotals['proyectos_activos'] }}</div>
            <div class="est-box-label">Activos</div>
        </div>
        <div class="est-box">
            <div class="est-box-num" style="color: #059669;">{{ $companyTotals['proyectos_completados'] }}</div>
            <div class="est-box-label">Completados</div>
        </div>
        <div class="est-box">
            <div class="est-box-num" style="color: #6c757d;">{{ $companyTotals['proyectos_pausados'] }}</div>
            <div class="est-box-label">Pausados</div>
        </div>
        <div class="est-box">
            <div class="est-box-num" style="color: var(--danger);">{{ $companyTotals['proyectos_cancelados'] }}</div>
            <div class="est-box-label">Cancelados</div>
        </div>
        <div class="est-box">
            <div class="est-box-num" style="color: var(--purple);">{{ $companyTotals['devs_activos'] }}</div>
            <div class="est-box-label">Devs activos</div>
        </div>
    </div>

    <div class="resumen-grid-2">
        @if($companyTotals['proyecto_top'])
            <a href="{{ route('admin.internal-projects.show', $companyTotals['proyecto_top']['id']) }}" style="text-decoration:none;">
                <div class="resumen-top">
                    <div class="resumen-top-icon verde"><i class="fas fa-trophy"></i></div>
                    <div class="resumen-top-body">
                        <div class="resumen-top-label">Proyecto más rentable</div>
                        <div class="resumen-top-name">{{ $companyTotals['proyecto_top']['nombre'] }}</div>
                        <div class="resumen-top-value verde">{{ $fmtCop($companyTotals['proyecto_top']['utilidad']) }} de utilidad</div>
                    </div>
                </div>
            </a>
        @endif
        @if($companyTotals['cliente_top'])
            <div class="resumen-top">
                <div class="resumen-top-icon azul"><i class="fas fa-user-tie"></i></div>
                <div class="resumen-top-body">
                    <div class="resumen-top-label">Cliente con mayor facturación</div>
                    <div class="resumen-top-name">{{ $companyTotals['cliente_top']['nombre'] }}</div>
                    <div class="resumen-top-value">{{ $fmtCop($companyTotals['cliente_top']['ingresos']) }} facturados</div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    window.detalleCols = (function () {
        const STORAGE_KEY = 'internal-projects-detalle-cols';
        const dropdown = document.getElementById('colsDropdown');
        const btn = document.getElementById('colsBtn');
        const menu = document.getElementById('colsMenu');
        const counter = document.getElementById('colsCounter');
        const visibleTxt = document.getElementById('colsVisibleCount');
        const checkboxes = menu ? menu.querySelectorAll('input[type="checkbox"][data-col]') : [];
        const total = checkboxes.length;

        function applyCol(col, visible) {
            document.querySelectorAll('#detTable [data-col="' + col + '"]').forEach(el => {
                el.style.display = visible ? '' : 'none';
            });
            const row = menu.querySelector('label[data-col-row="' + col + '"]');
            if (row) row.classList.toggle('is-checked', visible);
        }

        function updateCounter() {
            const n = [...checkboxes].filter(cb => cb.checked).length;
            if (counter) counter.textContent = n;
            if (visibleTxt) visibleTxt.textContent = n;
        }

        function saveState() {
            const state = {};
            checkboxes.forEach(cb => { state[cb.dataset.col] = cb.checked; });
            try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch (e) {}
        }

        function loadState() {
            try {
                const raw = localStorage.getItem(STORAGE_KEY);
                if (!raw) return;
                const state = JSON.parse(raw);
                checkboxes.forEach(cb => {
                    if (state[cb.dataset.col] === false) {
                        cb.checked = false;
                        applyCol(cb.dataset.col, false);
                    }
                });
            } catch (e) {}
        }

        function setAll(visible) {
            checkboxes.forEach(cb => {
                cb.checked = visible;
                applyCol(cb.dataset.col, visible);
            });
            saveState();
            updateCounter();
        }

        function toggle() {
            const open = menu.classList.toggle('open');
            dropdown.classList.toggle('open', open);
        }
        function close() {
            menu.classList.remove('open');
            dropdown.classList.remove('open');
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                applyCol(cb.dataset.col, cb.checked);
                saveState();
                updateCounter();
            });
        });

        if (btn) btn.addEventListener('click', (e) => { e.stopPropagation(); toggle(); });

        document.addEventListener('click', (e) => {
            if (!menu) return;
            if (!menu.contains(e.target) && !e.target.closest('#colsBtn')) {
                close();
            }
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') close();
        });

        loadState();
        updateCounter();
        return { setAll };
    })();
</script>
@endsection
