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
    <div class="det-filters">
        <div class="det-chips">
            @foreach($estadoChips as $key => $label)
                <a href="{{ route('admin.internal-projects.detalle', array_filter(['estado' => $key, 'buscar' => $filters['buscar']])) }}"
                   class="det-chip {{ $filters['estado'] === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" class="det-search">
            @if($filters['estado'])
                <input type="hidden" name="estado" value="{{ $filters['estado'] }}">
            @endif
            <input type="text" name="buscar" placeholder="Buscar proyecto, cliente o dev..." value="{{ $filters['buscar'] }}">
            <button type="submit"><i class="fas fa-search"></i> Buscar</button>
            @if($filters['buscar'])
                <a href="{{ route('admin.internal-projects.detalle', array_filter(['estado' => $filters['estado']])) }}" class="clear">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Tabla --}}
    <div class="det-table-wrap">
        <div class="det-table-scroll">
            <table class="det-table">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Cliente</th>
                        <th>Fechas</th>
                        <th style="text-align:right;">Precio</th>
                        <th style="text-align:right;">Cobrado</th>
                        <th style="text-align:right;">Saldo cli.</th>
                        <th style="text-align:right;">Pago dev</th>
                        <th style="text-align:right;">Abonado</th>
                        <th style="text-align:right;">Saldo dev</th>
                        <th style="text-align:right;">Gastos</th>
                        <th style="text-align:right;">Utilidad</th>
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
                            // Utilidad aproximada usando tasa fija (puede variar vs accesor que usa monto_recibido_cop exacto)
                            $ingresoCop = $moneda === 'USD' ? $cobrado * $usdCop : $cobrado;
                            $devCop = $devMoneda === 'USD' ? $abonadoDev * $usdCop : $abonadoDev;
                            $gastosCop = $gastos; // expenses se registra con moneda propia; aproximamos
                            $utilidad = $ingresoCop - $devCop - $gastosCop;
                        @endphp
                        <tr onclick="window.location='{{ route('admin.internal-projects.show', $p) }}'">
                            <td>
                                <a href="{{ route('admin.internal-projects.show', $p) }}" class="det-proj-name" onclick="event.stopPropagation();">{{ $p->nombre }}</a>
                                <div class="det-badges">
                                    <span class="det-estado" style="background: {{ $p->estado_color }}15; color: {{ $p->estado_color }};">{{ $p->estado_label }}</span>
                                    <span class="det-fuente">{{ $p->fuente == 'workana' ? 'Workana' : 'Directo' }}</span>
                                    @if($p->es_recurrente)<span class="det-fuente" style="background: rgba(0,123,255,0.12); color:#0056b3;">Recurrente</span>@endif
                                </div>
                            </td>
                            <td><div class="det-cliente">{{ $p->cliente_nombre }}</div>
                                @if($p->desarrollador_nombre)
                                    <div style="font-size:0.72rem; color:#aaa; margin-top:0.2rem;"><i class="fas fa-laptop-code"></i> {{ $p->desarrollador_nombre }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="det-fechas">
                                    @if($p->fecha_inicio)<div><i class="fas fa-play-circle"></i>{{ $p->fecha_inicio->format('d/m/y') }}</div>@endif
                                    @if($p->fecha_entrega && !$p->es_recurrente)<div><i class="fas fa-flag-checkered"></i>{{ $p->fecha_entrega->format('d/m/y') }}</div>@endif
                                    @if(!$p->fecha_inicio && !$p->fecha_entrega)<span style="color:#bbb;">—</span>@endif
                                </div>
                            </td>
                            <td class="mono">{{ $fmtMoneda($p->precio, $moneda) }}</td>
                            <td class="mono ing">
                                {{ $fmtMoneda($cobrado, $moneda) }}
                                <span class="sub">{{ $p->payments_count }} pagos</span>
                            </td>
                            <td class="mono {{ $saldoCli > 0 && $p->estado !== 'cancelado' ? 'rojo' : 'mute' }}">
                                {{ $fmtMoneda($saldoCli, $moneda) }}
                            </td>
                            <td class="mono">
                                @if($pagoDev > 0){{ $fmtMoneda($pagoDev, $devMoneda) }}@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono dev">
                                @if($pagoDev > 0){{ $fmtMoneda($abonadoDev, $devMoneda) }}<span class="sub">{{ $p->developer_payments_count }} pagos</span>@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono {{ $saldoDev > 0 ? 'dev' : 'mute' }}">
                                @if($pagoDev > 0){{ $fmtMoneda($saldoDev, $devMoneda) }}@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono {{ $gastos > 0 ? 'gas' : 'mute' }}">
                                @if($gastos > 0){{ $fmtMoneda($gastos, 'COP') }}<span class="sub">{{ $p->expenses_count }}</span>@else <span style="color:#bbb;">—</span>@endif
                            </td>
                            <td class="mono {{ $utilidad >= 0 ? 'verde' : 'rojo' }}">
                                {{ $fmtCop($utilidad) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="det-empty">
                                <i class="fas fa-inbox"></i>
                                <p>No hay proyectos que coincidan con los filtros.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
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
@endsection
