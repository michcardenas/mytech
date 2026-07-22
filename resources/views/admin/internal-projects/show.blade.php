@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --primary-dark: #0056b3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --success: #28a745;
        --danger: #dc3545;
        --warning: #f7a831;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --gradient-success: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        --gradient-danger: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);
        --gradient-warning: linear-gradient(135deg, #f7a831 0%, #d38a1a 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .show-container { background: var(--light-gray); max-width: 1200px; margin: 0 auto; padding: 2rem; min-height: 80vh; }

    /* HEADER */
    .show-header { padding: 1.5rem 2rem; background: var(--gradient-blue); border-radius: 16px; color: white; margin-bottom: 1.25rem; box-shadow: var(--shadow-soft); }
    .show-header-top { display: flex; justify-content: space-between; align-items: start; gap: 1rem; flex-wrap: wrap; }
    .show-header h1 { font-size: 1.4rem; font-weight: 700; margin: 0.4rem 0 0.3rem; color: white; }
    .show-header-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .h-badge { padding: 0.25rem 0.6rem; border-radius: 8px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .h-badge-estado { background: rgba(255,255,255,0.22); color: white; }
    .h-badge-fuente { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.95); }
    .h-badge-rec { background: rgba(255,255,255,0.22); color: white; }
    .show-header-cliente { font-size: 0.85rem; opacity: 0.92; }
    .show-header-cliente i { margin-right: 0.3rem; opacity: 0.8; }
    .show-header-actions { display: flex; gap: 0.5rem; flex-shrink: 0; flex-wrap: wrap; }
    .btn-h { padding: 0.5rem 0.95rem; border-radius: 10px; font-weight: 600; text-decoration: none; font-size: 0.8rem; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.4rem; border: none; cursor: pointer; }
    .btn-h-back { background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); color: white; }
    .btn-h-back:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; }
    .btn-h-edit { background: white; color: var(--primary-blue); }
    .btn-h-edit:hover { background: #f0f7ff; color: var(--primary-dark); text-decoration: none; }
    .btn-h-cobro { background: #F59E0B; color: white; border: none; cursor: pointer; }
    .btn-h-cobro:hover { background: #D97706; color: white; text-decoration: none; }
    .btn-h-cobro.dropdown-toggle::after { margin-left: .4rem; }
    .btn-h-delete { background: rgba(220,53,69,0.85); color: white; }
    .btn-h-delete:hover { background: var(--danger); }

    /* KPI CARDS */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.85rem; margin-bottom: 1.25rem; }
    .kpi-card { background: white; border-radius: 14px; padding: 1.1rem 1.25rem; box-shadow: var(--shadow-soft); border: 1px solid rgba(0,0,0,0.04); position: relative; overflow: hidden; }
    .kpi-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; background: var(--primary-blue); }
    .kpi-card.kpi-success::before { background: var(--success); }
    .kpi-card.kpi-danger::before { background: var(--danger); }
    .kpi-card.kpi-warning::before { background: var(--warning); }
    .kpi-card.kpi-utilidad { background: var(--gradient-success); color: white; }
    .kpi-card.kpi-utilidad::before { display: none; }
    .kpi-card.kpi-utilidad-neg { background: var(--gradient-danger); color: white; }
    .kpi-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; opacity: 0.65; letter-spacing: 0.4px; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.35rem; }
    .kpi-card.kpi-utilidad .kpi-label, .kpi-card.kpi-utilidad-neg .kpi-label { opacity: 0.9; }
    .kpi-value { font-size: 1.4rem; font-weight: 800; color: var(--dark-text); line-height: 1.1; }
    .kpi-card.kpi-utilidad .kpi-value, .kpi-card.kpi-utilidad-neg .kpi-value { color: white; }
    .kpi-sub { font-size: 0.72rem; color: #999; margin-top: 0.25rem; }
    .kpi-card.kpi-utilidad .kpi-sub, .kpi-card.kpi-utilidad-neg .kpi-sub { color: rgba(255,255,255,0.85); }
    .kpi-progress { margin-top: 0.6rem; height: 5px; background: #f0f0f0; border-radius: 3px; overflow: hidden; }
    .kpi-progress-fill { height: 100%; border-radius: 3px; transition: width 0.5s; }

    /* TABS */
    .tabs-wrapper { background: white; border-radius: 14px; box-shadow: var(--shadow-soft); border: 1px solid rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.25rem; }
    .tabs-nav { display: flex; gap: 0; border-bottom: 1px solid rgba(0,0,0,0.06); background: rgba(0,0,0,0.015); overflow-x: auto; }
    .tab-btn { padding: 0.95rem 1.4rem; background: none; border: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: #777; display: inline-flex; align-items: center; gap: 0.5rem; border-bottom: 3px solid transparent; transition: var(--transition); white-space: nowrap; }
    .tab-btn:hover { color: var(--primary-blue); background: rgba(0,123,255,0.04); }
    .tab-btn.active { color: var(--primary-blue); border-bottom-color: var(--primary-blue); background: white; }
    .tab-btn .tab-count { background: rgba(0,0,0,0.08); padding: 0.1rem 0.5rem; border-radius: 10px; font-size: 0.7rem; font-weight: 700; color: #666; }
    .tab-btn.active .tab-count { background: rgba(0,123,255,0.15); color: var(--primary-blue); }
    .tab-content { display: none; padding: 1.5rem; }
    .tab-content.active { display: block; }

    /* INFO ITEM (cliente data) */
    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
    .info-item { display: flex; flex-direction: column; gap: 0.2rem; padding: 0.75rem 1rem; background: var(--light-gray); border-radius: 10px; border-left: 3px solid var(--primary-blue); }
    .info-item-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: #888; letter-spacing: 0.3px; }
    .info-item-value { font-size: 0.9rem; font-weight: 600; color: var(--dark-text); word-break: break-word; }
    .info-item a { color: var(--primary-blue); text-decoration: none; }
    .info-item a:hover { text-decoration: underline; }

    /* ITEM LIST */
    .item-list { list-style: none; padding: 0; margin: 0 0 1rem; }
    .item-row { display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border-radius: 10px; gap: 0.75rem; transition: var(--transition); background: var(--light-gray); margin-bottom: 0.4rem; }
    .item-row:hover { background: #eef5ff; }
    .item-info { flex: 1; min-width: 0; }
    .item-primary { font-size: 0.9rem; font-weight: 700; color: var(--dark-text); display: flex; align-items: center; gap: 0.4rem; }
    .item-secondary { font-size: 0.75rem; color: #888; margin-top: 0.15rem; }
    .item-amount { font-weight: 800; color: var(--dark-text); font-size: 0.95rem; white-space: nowrap; text-align: right; }
    .item-amount small { font-size: 0.65rem; color: #999; font-weight: 600; margin-left: 0.2rem; }
    .item-amount-sub { font-size: 0.7rem; color: var(--success); font-weight: 600; margin-top: 0.15rem; }
    .btn-delete-sm { width: 28px; height: 28px; border-radius: 8px; border: none; background: rgba(220,53,69,0.08); color: var(--danger); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; transition: var(--transition); flex-shrink: 0; }
    .btn-delete-sm:hover { background: var(--danger); color: white; }
    .btn-receipt-sm { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .7rem; border-radius:8px; background:rgba(37,99,235,.08); color:#2563EB; text-decoration:none; font-size:.72rem; font-weight:700; transition:var(--transition); border:1px solid rgba(37,99,235,.16); flex-shrink:0; }
    .btn-receipt-sm:hover { background:#2563EB; color:#fff; text-decoration:none; }
    .btn-receipt-sm i { font-size:.7rem; }

    /* ADD FORM */
    .add-form-card { background: linear-gradient(135deg, rgba(0,123,255,0.04) 0%, rgba(0,123,255,0.01) 100%); border: 1px dashed rgba(0,123,255,0.2); border-radius: 12px; padding: 1.1rem 1.25rem; }
    .add-form-card h4 { font-size: 0.85rem; font-weight: 700; color: var(--dark-text); margin: 0 0 0.85rem; display: flex; align-items: center; gap: 0.4rem; }
    .add-form-card h4 i { color: var(--primary-blue); }
    .add-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.6rem; align-items: end; }
    .add-field label { font-size: 0.7rem; font-weight: 700; color: #777; margin-bottom: 0.25rem; display: block; text-transform: uppercase; letter-spacing: 0.3px; }
    .add-field label small { text-transform: none; font-weight: 500; color: #aaa; letter-spacing: 0; }
    .add-field input, .add-field select, .add-field textarea { width: 100%; font-size: 0.85rem; padding: 0.5rem 0.75rem; border: 2px solid #e9ecef; border-radius: 9px; background: white; transition: var(--transition); }
    .add-field input:focus, .add-field select:focus, .add-field textarea:focus { border-color: var(--primary-blue); outline: none; box-shadow: 0 0 0 3px rgba(0,123,255,0.08); }
    .btn-add { padding: 0.55rem 1.2rem; border-radius: 10px; border: none; background: var(--gradient-blue); color: white; font-weight: 700; font-size: 0.82rem; cursor: pointer; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap; box-shadow: 0 3px 10px rgba(0,123,255,0.25); }
    .btn-add:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(0,123,255,0.35); }
    .btn-add.btn-add-success { background: var(--gradient-success); box-shadow: 0 3px 10px rgba(40,167,69,0.25); }
    .btn-add.btn-add-warning { background: var(--gradient-warning); box-shadow: 0 3px 10px rgba(247,168,49,0.25); }

    /* ALERT */
    .alert-success { background: white; color: #155724; border: 1px solid rgba(40,167,69,0.2); border-left: 4px solid var(--success); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 1.25rem; font-weight: 500; box-shadow: var(--shadow-soft); }

    /* FILES & MISC */
    .file-icon { font-size: 1.4rem; color: #666; margin-right: 0.5rem; flex-shrink: 0; }
    .file-download { padding: 0.4rem 0.85rem; border-radius: 8px; background: rgba(0,123,255,0.1); color: var(--primary-blue); font-size: 0.75rem; font-weight: 700; text-decoration: none; transition: var(--transition); }
    .file-download:hover { background: var(--primary-blue); color: white; text-decoration: none; }
    .notes-content { font-size: 0.9rem; color: #555; line-height: 1.7; white-space: pre-wrap; padding: 1rem; background: var(--light-gray); border-radius: 10px; }
    .no-data { color: #bbb; font-size: 0.9rem; font-style: italic; text-align: center; padding: 2rem 1rem; }
    .no-data i { display: block; font-size: 2.5rem; margin-bottom: 0.5rem; color: #ddd; }

    .section-summary { display: flex; align-items: center; gap: 1.25rem; padding: 1rem 1.25rem; background: var(--light-gray); border-radius: 12px; margin-bottom: 1.25rem; flex-wrap: wrap; }
    .section-summary .ss-stat { text-align: center; min-width: 80px; }
    .ss-stat-num { font-size: 1.2rem; font-weight: 800; }
    .ss-stat-label { font-size: 0.68rem; color: #888; text-transform: uppercase; font-weight: 700; letter-spacing: 0.3px; }
    .ss-progress-bar { flex: 1; min-width: 150px; height: 8px; background: #e0e0e0; border-radius: 4px; overflow: hidden; }
    .ss-progress-fill { height: 100%; transition: width 0.5s; }

    @media (max-width: 768px) {
        .show-container { padding: 1rem; }
        .show-header-top { flex-direction: column; }
        .show-header-actions { width: 100%; }
        .kpi-grid { grid-template-columns: repeat(2, 1fr); }
        .add-row { grid-template-columns: 1fr; }
        .section-summary { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="show-container">
    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    {{-- ============ HEADER ============ --}}
    <div class="show-header">
        <div class="show-header-top">
            <div style="min-width: 0; flex: 1;">
                <div class="show-header-badges">
                    <span class="h-badge h-badge-estado"><i class="fas fa-circle" style="font-size:0.45rem;"></i> {{ $project->estado_label }}</span>
                    <span class="h-badge h-badge-fuente">{{ $project->fuente == 'workana' ? 'Workana' : 'Directo' }}</span>
                    @if($project->es_recurrente)
                        <span class="h-badge h-badge-rec"><i class="fas fa-sync-alt"></i> Recurrente</span>
                    @endif
                </div>
                <h1>{{ $project->nombre }}</h1>
                <div class="show-header-cliente">
                    <i class="fas fa-user"></i> {{ $project->cliente_nombre }}
                    @if($project->fuente_url)
                        <a href="{{ $project->fuente_url }}" target="_blank" style="color:rgba(255,255,255,0.85); margin-left:0.5rem;"><i class="fas fa-external-link-alt"></i></a>
                    @endif
                </div>
            </div>
            <div class="show-header-actions">
                <a href="{{ route('admin.internal-projects.index') }}" class="btn-h btn-h-back"><i class="fas fa-arrow-left"></i> Volver</a>
                @if($project->es_recurrente)
                    <div class="dropdown">
                        <button class="btn-h btn-h-cobro dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-invoice"></i> Cuenta de cobro
                        </button>
                        <ul class="dropdown-menu" style="border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,.12); padding:.4rem; min-width:220px;">
                            <li><a class="dropdown-item" style="border-radius:8px; padding:.55rem .8rem; font-size:.87rem;" href="{{ route('admin.internal-projects.cuenta-cobro', ['internal_project' => $project, 'mes' => now()->format('Y-m')]) }}" target="_blank">
                                <i class="fas fa-calendar-check" style="color:#F59E0B; width:18px;"></i> {{ ucfirst(now()->translatedFormat('F Y')) }} <small style="color:#94A3B8;">(mes actual)</small>
                            </a></li>
                            <li><a class="dropdown-item" style="border-radius:8px; padding:.55rem .8rem; font-size:.87rem;" href="{{ route('admin.internal-projects.cuenta-cobro', ['internal_project' => $project, 'mes' => now()->subMonth()->format('Y-m')]) }}" target="_blank">
                                <i class="fas fa-calendar" style="color:#64748B; width:18px;"></i> {{ ucfirst(now()->subMonth()->translatedFormat('F Y')) }} <small style="color:#94A3B8;">(mes anterior)</small>
                            </a></li>
                            <li><hr style="margin:.3rem 0; border-color:#F1F5F9;"></li>
                            <li>
                                <form onsubmit="event.preventDefault(); const m=this.mes.value; if(m) window.open('{{ route('admin.internal-projects.cuenta-cobro', $project) }}?mes='+m, '_blank');" style="padding:.4rem .6rem; display:flex; gap:.3rem; align-items:center;">
                                    <input type="month" name="mes" required style="flex:1; padding:.35rem .5rem; border:1px solid #E2E8F0; border-radius:6px; font-size:.82rem;">
                                    <button type="submit" style="padding:.35rem .7rem; border:none; background:#F59E0B; color:#fff; border-radius:6px; font-size:.78rem; font-weight:700; cursor:pointer;">Ver</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    @php
                        $saldoActual = max((float) $project->precio - (float) $project->payments->sum('monto'), 0);
                    @endphp
                    @if($saldoActual > 0)
                        <a href="{{ route('admin.internal-projects.cuenta-cobro', $project) }}" target="_blank" class="btn-h btn-h-cobro">
                            <i class="fas fa-file-invoice"></i> Cuenta de cobro
                        </a>
                    @endif
                @endif
                <a href="{{ route('admin.internal-projects.edit', $project) }}" class="btn-h btn-h-edit"><i class="fas fa-edit"></i> Editar</a>
                <form action="{{ route('admin.internal-projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Eliminar este proyecto y todos sus datos?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-h btn-h-delete"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ KPI CARDS ============ --}}
    @php
        $pctClient = $project->precio > 0 ? min(round(($project->total_pagado / $project->precio) * 100), 100) : 0;
        $colorClient = $pctClient >= 100 ? 'var(--success)' : ($pctClient >= 50 ? 'var(--primary-blue)' : 'var(--warning)');
        $pctDev = ($project->desarrollador_pago ?? 0) > 0 ? min(round(($project->total_pagado_dev / $project->desarrollador_pago) * 100), 100) : 0;
        $colorDev = $pctDev >= 100 ? 'var(--success)' : ($pctDev >= 50 ? 'var(--primary-blue)' : 'var(--warning)');
        $utilidad = $project->utilidad;
        $utilClass = $utilidad >= 0 ? 'kpi-utilidad' : 'kpi-utilidad-neg';
    @endphp
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label"><i class="fas fa-tag"></i> Precio Acordado</div>
            <div class="kpi-value">{{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->precio, 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $project->moneda }} &middot; Cliente</div>
        </div>
        <div class="kpi-card kpi-success">
            <div class="kpi-label"><i class="fas fa-money-bill-wave"></i> Cobrado</div>
            <div class="kpi-value">{{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->total_pagado, 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $pctClient }}% del precio</div>
            <div class="kpi-progress"><div class="kpi-progress-fill" style="width:{{ $pctClient }}%; background: {{ $colorClient }};"></div></div>
        </div>
        <div class="kpi-card kpi-warning">
            <div class="kpi-label"><i class="fas fa-laptop-code"></i> Pagado al Dev</div>
            <div class="kpi-value">${{ number_format($project->total_pagado_dev, 0, ',', '.') }}</div>
            <div class="kpi-sub">@if($project->desarrollador_pago) de ${{ number_format($project->desarrollador_pago, 0, ',', '.') }} &middot; {{ $pctDev }}% @else Sin acuerdo definido @endif</div>
            @if($project->desarrollador_pago)
                <div class="kpi-progress"><div class="kpi-progress-fill" style="width:{{ $pctDev }}%; background: {{ $colorDev }};"></div></div>
            @endif
        </div>
        <div class="kpi-card kpi-danger">
            <div class="kpi-label"><i class="fas fa-receipt"></i> Otros Gastos</div>
            <div class="kpi-value">${{ number_format($project->total_gastos, 0, ',', '.') }}</div>
            <div class="kpi-sub">{{ $project->expenses->count() }} {{ Str::plural('item', $project->expenses->count()) }}</div>
        </div>
        @if($project->comision_calculada > 0 || $project->vendedor_id)
            @php
                $comCalc = (float) $project->comision_calculada;
                $comPag = (float) $project->total_pagado_gestion;
                $pctGest = $comCalc > 0 ? min(round(($comPag / $comCalc) * 100), 100) : 0;
                $colorGest = $pctGest >= 100 ? 'var(--success)' : ($pctGest >= 50 ? 'var(--primary-blue)' : 'var(--warning)');
            @endphp
            <div class="kpi-card" style="--col: #059669;">
                <div class="kpi-label"><i class="fas fa-handshake"></i> Gestión (vendedor)</div>
                <div class="kpi-value">${{ number_format($comPag, 0, ',', '.') }}</div>
                <div class="kpi-sub">
                    @if($comCalc > 0)
                        de ${{ number_format($comCalc, 0, ',', '.') }} · {{ $pctGest }}%
                    @else
                        sin comisión definida
                    @endif
                </div>
                @if($comCalc > 0)
                    <div class="kpi-progress"><div class="kpi-progress-fill" style="width:{{ $pctGest }}%; background: {{ $colorGest }};"></div></div>
                @endif
            </div>
        @endif
        <div class="kpi-card {{ $utilClass }}">
            <div class="kpi-label"><i class="fas fa-chart-line"></i> Utilidad Neta</div>
            <div class="kpi-value">${{ number_format($utilidad, 0, ',', '.') }}</div>
            <div class="kpi-sub">Recibido COP &minus; Dev &minus; Gastos</div>
        </div>
    </div>

    {{-- ============ TABS ============ --}}
    <div class="tabs-wrapper">
        <div class="tabs-nav">
            <button class="tab-btn active" data-tab="info"><i class="fas fa-info-circle"></i> Informacion</button>
            <button class="tab-btn" data-tab="cobros"><i class="fas fa-money-bill-wave"></i> Cobros <span class="tab-count">{{ $project->payments->count() }}</span></button>
            <button class="tab-btn" data-tab="dev"><i class="fas fa-laptop-code"></i> Pagos al Dev <span class="tab-count">{{ $project->developerPayments->count() }}</span></button>
            <button class="tab-btn" data-tab="gestion"><i class="fas fa-handshake"></i> Pagos de Gestión <span class="tab-count">{{ $project->gestionPayments->count() }}</span></button>
            <button class="tab-btn" data-tab="gastos"><i class="fas fa-receipt"></i> Otros Gastos <span class="tab-count">{{ $project->expenses->count() }}</span></button>
            <button class="tab-btn" data-tab="archivos"><i class="fas fa-paperclip"></i> Archivos <span class="tab-count">{{ $project->files->count() }}</span></button>
            <button class="tab-btn" data-tab="notas"><i class="fas fa-sticky-note"></i> Notas</button>
        </div>

        {{-- TAB: INFORMACION --}}
        <div class="tab-content active" id="tab-info">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-item-label">Cliente</span>
                    <span class="info-item-value">{{ $project->cliente_nombre }}</span>
                </div>
                @if($project->cliente_contacto)
                <div class="info-item">
                    <span class="info-item-label">Contacto</span>
                    <span class="info-item-value">{{ $project->cliente_contacto }}</span>
                </div>
                @endif
                @if($project->cliente_email)
                <div class="info-item">
                    <span class="info-item-label">Email Cliente</span>
                    <span class="info-item-value"><a href="mailto:{{ $project->cliente_email }}">{{ $project->cliente_email }}</a></span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-item-label">Fecha Inicio</span>
                    <span class="info-item-value">{{ $project->fecha_inicio ? $project->fecha_inicio->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-item-label">Fecha Entrega</span>
                    <span class="info-item-value">
                        @if($project->es_recurrente)
                            <span style="color:var(--primary-blue);"><i class="fas fa-sync-alt"></i> Recurrente</span>
                        @else
                            {{ $project->fecha_entrega ? $project->fecha_entrega->format('d/m/Y') : '-' }}
                        @endif
                    </span>
                </div>
                @if($project->fecha_facturacion)
                    @php
                        $diasFact = (int) now()->startOfDay()->diffInDays($project->fecha_facturacion->startOfDay(), false);
                        $factColor = $diasFact < 0 ? '#DC2626' : ($diasFact <= 3 ? '#B45309' : '#0F766E');
                    @endphp
                    <div class="info-item">
                        <span class="info-item-label"><i class="fas fa-file-invoice-dollar" style="color:#aaa;"></i> Fecha de facturación</span>
                        <span class="info-item-value" style="color:{{ $factColor }}; font-weight:700;">
                            {{ $project->fecha_facturacion->format('d/m/Y') }}
                            @if($diasFact < 0)
                                <small style="font-weight:600;">· vencida hace {{ abs($diasFact) }}d</small>
                            @elseif($diasFact === 0)
                                <small style="font-weight:600;">· hoy</small>
                            @elseif($diasFact <= 7)
                                <small style="font-weight:600;">· en {{ $diasFact }}d</small>
                            @endif
                        </span>
                    </div>
                @endif
                @if($project->notas_facturacion)
                    <div class="info-item" style="grid-column:1/-1;">
                        <span class="info-item-label"><i class="fas fa-sticky-note" style="color:#aaa;"></i> Notas de facturación</span>
                        <span class="info-item-value" style="white-space:pre-line;">{{ $project->notas_facturacion }}</span>
                    </div>
                @endif
                @if($project->desarrollador_nombre)
                <div class="info-item">
                    <span class="info-item-label">Desarrollador</span>
                    <span class="info-item-value"><i class="fas fa-laptop-code" style="color:#aaa;"></i> {{ $project->desarrollador_nombre }}</span>
                </div>
                @endif
                @if($project->desarrollador_email)
                <div class="info-item">
                    <span class="info-item-label">Email Dev</span>
                    <span class="info-item-value"><a href="mailto:{{ $project->desarrollador_email }}">{{ $project->desarrollador_email }}</a></span>
                </div>
                @endif
                @if($project->desarrollador_pago)
                <div class="info-item">
                    <span class="info-item-label">Pago Acordado Dev</span>
                    <span class="info-item-value">{{ $project->desarrollador_moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->desarrollador_pago, 0, ',', '.') }} {{ $project->desarrollador_moneda }}</span>
                </div>
                @endif
            </div>
            @if($project->descripcion)
                <div style="margin-top: 1.25rem;">
                    <div style="font-size:0.78rem; font-weight:700; color:#777; text-transform:uppercase; letter-spacing:0.3px; margin-bottom:0.5rem;"><i class="fas fa-align-left"></i> Descripcion</div>
                    <div class="notes-content">{{ $project->descripcion }}</div>
                </div>
            @endif
        </div>

        {{-- TAB: COBROS (Cliente) --}}
        <div class="tab-content" id="tab-cobros">
            @php
                $saldo = $project->saldo_pendiente;
            @endphp
            <div class="section-summary">
                <div class="ss-stat">
                    <div class="ss-stat-num" style="color: {{ $colorClient }};">{{ $pctClient }}%</div>
                    <div class="ss-stat-label">Cobrado</div>
                </div>
                <div class="ss-progress-bar"><div class="ss-progress-fill" style="width:{{ $pctClient }}%; background: {{ $colorClient }};"></div></div>
                <div class="ss-stat">
                    <div class="ss-stat-num" style="color: {{ $saldo > 0 ? 'var(--danger)' : 'var(--success)' }};">
                        {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format(abs($saldo), 0, ',', '.') }}
                    </div>
                    <div class="ss-stat-label">{{ $saldo > 0 ? 'Pendiente' : 'Saldado' }}</div>
                </div>
                <div class="ss-stat">
                    <div class="ss-stat-num" style="color: var(--success);">${{ number_format($project->total_recibido_cop, 0, ',', '.') }}</div>
                    <div class="ss-stat-label">Recibido COP</div>
                </div>
            </div>

            @if($project->payments->count() > 0)
                <ul class="item-list">
                    @foreach($project->payments as $payment)
                        <li class="item-row">
                            <div class="item-info">
                                <div class="item-primary"><i class="fas fa-calendar-day" style="color:#aaa; font-size:0.8rem;"></i> {{ $payment->fecha->format('d/m/Y') }}</div>
                                <div class="item-secondary">
                                    {{ $payment->metodo ?: 'Sin metodo' }}
                                    @if($payment->referencia) &middot; Ref: {{ $payment->referencia }} @endif
                                    @if($payment->nota) &middot; {{ $payment->nota }} @endif
                                </div>
                            </div>
                            <div class="item-amount">
                                {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($payment->monto, 0, ',', '.') }}<small>{{ $project->moneda }}</small>
                                @if($payment->monto_recibido_cop)
                                    <div class="item-amount-sub"><i class="fas fa-arrow-down"></i> ${{ number_format($payment->monto_recibido_cop, 0, ',', '.') }} COP neto</div>
                                @endif
                            </div>
                            <a href="{{ route('admin.internal-projects.payments.receipt', [$project, $payment]) }}"
                               target="_blank"
                               class="btn-receipt-sm"
                               title="Generar recibo con membrete MyTech">
                                <i class="fas fa-file-invoice"></i> Recibo
                            </a>
                            <form action="{{ route('admin.internal-projects.payments.destroy', [$project, $payment]) }}" method="POST" onsubmit="return confirm('Eliminar este pago?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-data"><i class="fas fa-money-bill-wave"></i> No hay cobros registrados</div>
            @endif

            <div class="add-form-card">
                <h4><i class="fas fa-plus-circle"></i> Registrar Cobro</h4>
                <form action="{{ route('admin.internal-projects.payments.store', $project) }}" method="POST">
                    @csrf
                    <div class="add-row">
                        <div class="add-field"><label>Monto *</label><input type="text" inputmode="decimal" name="monto" required class="js-money-input" placeholder="0"></div>
                        <div class="add-field"><label>Fecha *</label><input type="date" name="fecha" required value="{{ date('Y-m-d') }}"></div>
                        <div class="add-field">
                            <label>Metodo</label>
                            <select name="metodo">
                                <option value="">Seleccionar</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                                <option value="Bancolombia">Bancolombia</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Workana">Workana</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Efectivo">Efectivo</option>
                            </select>
                        </div>
                        <div class="add-field"><label>Recibido COP <small>(neto)</small></label><input type="text" inputmode="decimal" name="monto_recibido_cop" class="js-money-input" placeholder="Despues de impuestos"></div>
                        <div class="add-field"><label>Referencia</label><input type="text" name="referencia" placeholder="# transaccion"></div>
                        <div class="add-field"><button type="submit" class="btn-add btn-add-success"><i class="fas fa-plus"></i> Registrar</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB: PAGOS DEV --}}
        <div class="tab-content" id="tab-dev">
            @if($project->desarrollador_pago)
                @php $saldoDev = $project->saldo_pendiente_dev; @endphp
                <div class="section-summary">
                    <div class="ss-stat">
                        <div class="ss-stat-num" style="color: {{ $colorDev }};">{{ $pctDev }}%</div>
                        <div class="ss-stat-label">Pagado Dev</div>
                    </div>
                    <div class="ss-progress-bar"><div class="ss-progress-fill" style="width:{{ $pctDev }}%; background: {{ $colorDev }};"></div></div>
                    <div class="ss-stat">
                        <div class="ss-stat-num" style="color: {{ $saldoDev > 0 ? 'var(--danger)' : 'var(--success)' }};">
                            {{ $project->desarrollador_moneda == 'COP' ? '$' : 'US$' }}{{ number_format(abs($saldoDev), 0, ',', '.') }}
                        </div>
                        <div class="ss-stat-label">{{ $saldoDev > 0 ? 'Por pagar' : 'Saldado' }}</div>
                    </div>
                </div>
            @endif

            @if($project->developerPayments->count() > 0)
                <ul class="item-list">
                    @foreach($project->developerPayments as $devPayment)
                        <li class="item-row">
                            <div class="item-info">
                                <div class="item-primary"><i class="fas fa-calendar-day" style="color:#aaa; font-size:0.8rem;"></i> {{ $devPayment->fecha->format('d/m/Y') }}</div>
                                <div class="item-secondary">
                                    {{ $devPayment->metodo ?: 'Sin metodo' }}
                                    @if($devPayment->referencia) &middot; Ref: {{ $devPayment->referencia }} @endif
                                    @if($devPayment->nota) &middot; {{ $devPayment->nota }} @endif
                                </div>
                            </div>
                            <div class="item-amount">
                                {{ $devPayment->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($devPayment->monto, 0, ',', '.') }}<small>{{ $devPayment->moneda }}</small>
                            </div>
                            <form action="{{ route('admin.internal-projects.developer-payments.destroy', [$project, $devPayment]) }}" method="POST" onsubmit="return confirm('Eliminar este pago?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-data"><i class="fas fa-laptop-code"></i> No hay pagos al desarrollador registrados</div>
            @endif

            <div class="add-form-card">
                <h4><i class="fas fa-plus-circle"></i> Registrar Pago al Desarrollador @if($project->desarrollador_nombre)<span style="color:#999; font-weight:500;">- {{ $project->desarrollador_nombre }}</span>@endif</h4>
                <form action="{{ route('admin.internal-projects.developer-payments.store', $project) }}" method="POST">
                    @csrf
                    <div class="add-row">
                        <div class="add-field"><label>Monto *</label><input type="text" inputmode="decimal" name="monto" required class="js-money-input" placeholder="0"></div>
                        <div class="add-field">
                            <label>Moneda</label>
                            <select name="moneda">
                                <option value="COP" {{ ($project->desarrollador_moneda ?? 'COP') == 'COP' ? 'selected' : '' }}>COP</option>
                                <option value="USD" {{ ($project->desarrollador_moneda ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        <div class="add-field"><label>Fecha *</label><input type="date" name="fecha" required value="{{ date('Y-m-d') }}"></div>
                        <div class="add-field">
                            <label>Metodo</label>
                            <select name="metodo">
                                <option value="">Seleccionar</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                                <option value="Bancolombia">Bancolombia</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Efectivo">Efectivo</option>
                            </select>
                        </div>
                        <div class="add-field"><label>Nota</label><input type="text" name="nota" placeholder="Opcional"></div>
                        <div class="add-field"><button type="submit" class="btn-add btn-add-warning"><i class="fas fa-plus"></i> Registrar</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB: PAGOS DE GESTION (vendedor) --}}
        <div class="tab-content" id="tab-gestion">
            @php
                $comCalc = (float) $project->comision_calculada;
                $comPag = (float) $project->total_pagado_gestion;
                $saldoGest = max($comCalc - $comPag, 0);
                $pctG = $comCalc > 0 ? min(round(($comPag / $comCalc) * 100), 100) : 0;
                $colG = $pctG >= 100 ? 'var(--success)' : ($pctG >= 50 ? 'var(--primary-blue)' : 'var(--warning)');
            @endphp

            @if($project->vendedor_id && $project->vendedor)
                <div class="info-grid" style="margin-bottom: 1.25rem;">
                    <div class="info-item" style="border-left-color: #059669;">
                        <span class="info-item-label">Vendedor</span>
                        <span class="info-item-value"><i class="fas fa-user-tie" style="color:#aaa;"></i> {{ $project->vendedor->nombre }}</span>
                    </div>
                    @if($project->vendedor->telefono)
                        <div class="info-item" style="border-left-color: #059669;">
                            <span class="info-item-label">Teléfono</span>
                            <span class="info-item-value">{{ $project->vendedor->telefono }}</span>
                        </div>
                    @endif
                    @if($project->vendedor->email)
                        <div class="info-item" style="border-left-color: #059669;">
                            <span class="info-item-label">Email</span>
                            <span class="info-item-value"><a href="mailto:{{ $project->vendedor->email }}">{{ $project->vendedor->email }}</a></span>
                        </div>
                    @endif
                    <div class="info-item" style="border-left-color: #059669;">
                        <span class="info-item-label">Comisión pactada</span>
                        <span class="info-item-value">
                            @if($project->comision_tipo === 'porcentaje')
                                {{ $project->comision_valor }}% sobre (precio − pago dev)
                            @elseif($project->comision_tipo === 'monto')
                                Monto fijo: {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->comision_valor, 0, ',', '.') }}
                            @else
                                <span style="color:#aaa;">Sin comisión definida</span>
                            @endif
                        </span>
                    </div>
                </div>
            @endif

            @if($comCalc > 0)
                <div class="section-summary">
                    <div class="ss-stat">
                        <div class="ss-stat-num" style="color: {{ $colG }};">{{ $pctG }}%</div>
                        <div class="ss-stat-label">Pagado</div>
                    </div>
                    <div class="ss-progress-bar"><div class="ss-progress-fill" style="width:{{ $pctG }}%; background: {{ $colG }};"></div></div>
                    <div class="ss-stat">
                        <div class="ss-stat-num" style="color: #059669;">${{ number_format($comCalc, 0, ',', '.') }}</div>
                        <div class="ss-stat-label">Comisión total</div>
                    </div>
                    <div class="ss-stat">
                        <div class="ss-stat-num" style="color: {{ $saldoGest > 0 ? 'var(--danger)' : 'var(--success)' }};">${{ number_format($saldoGest, 0, ',', '.') }}</div>
                        <div class="ss-stat-label">{{ $saldoGest > 0 ? 'Por pagar' : 'Saldado' }}</div>
                    </div>
                </div>
            @endif

            @if($project->gestionPayments->count() > 0)
                <ul class="item-list">
                    @foreach($project->gestionPayments as $gestionPayment)
                        <li class="item-row">
                            <div class="item-info">
                                <div class="item-primary"><i class="fas fa-calendar-day" style="color:#aaa; font-size:0.8rem;"></i> {{ $gestionPayment->fecha->format('d/m/Y') }}</div>
                                <div class="item-secondary">
                                    {{ $gestionPayment->metodo ?: 'Sin método' }}
                                    @if($gestionPayment->referencia) &middot; Ref: {{ $gestionPayment->referencia }} @endif
                                    @if($gestionPayment->nota) &middot; {{ $gestionPayment->nota }} @endif
                                </div>
                            </div>
                            <div class="item-amount" style="color: #059669;">
                                {{ $gestionPayment->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($gestionPayment->monto, 0, ',', '.') }}<small>{{ $gestionPayment->moneda }}</small>
                            </div>
                            <form action="{{ route('admin.internal-projects.gestion-payments.destroy', [$project, $gestionPayment]) }}" method="POST" onsubmit="return confirm('Eliminar este pago de gestión?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-data"><i class="fas fa-handshake"></i> No hay pagos de gestión registrados</div>
            @endif

            @if(!$project->vendedor_id)
                <div class="add-form-card" style="border-color: rgba(247,168,49,0.3); background: linear-gradient(135deg, rgba(247,168,49,0.05) 0%, rgba(247,168,49,0.01) 100%);">
                    <h4><i class="fas fa-info-circle" style="color: var(--warning) !important;"></i> Sin vendedor asignado</h4>
                    <p style="font-size:0.85rem; color:#666; margin:0;">
                        Primero asigna un vendedor al proyecto desde <a href="{{ route('admin.internal-projects.edit', $project) }}">Editar</a> para poder registrar pagos de gestión.
                    </p>
                </div>
            @else
                <div class="add-form-card">
                    <h4><i class="fas fa-plus-circle"></i> Registrar Pago de Gestión
                        <span style="color:#999; font-weight:500;">- {{ $project->vendedor->nombre }}</span>
                    </h4>
                    <form action="{{ route('admin.internal-projects.gestion-payments.store', $project) }}" method="POST">
                        @csrf
                        <div class="add-row">
                            <div class="add-field">
                                <label>Monto *</label>
                                <input type="text" inputmode="decimal" name="monto" required
                                       class="js-money-input"
                                       placeholder="0" value="{{ $saldoGest > 0 ? number_format($saldoGest, 0, '.', '') : '' }}">
                            </div>
                            <div class="add-field">
                                <label>Moneda</label>
                                <select name="moneda">
                                    <option value="COP" {{ $project->moneda == 'COP' ? 'selected' : '' }}>COP</option>
                                    <option value="USD" {{ $project->moneda == 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                            </div>
                            <div class="add-field"><label>Fecha *</label><input type="date" name="fecha" required value="{{ date('Y-m-d') }}"></div>
                            <div class="add-field">
                                <label>Método</label>
                                <select name="metodo">
                                    <option value="">Seleccionar</option>
                                    <option value="Transferencia">Transferencia</option>
                                    <option value="Nequi">Nequi</option>
                                    <option value="Daviplata">Daviplata</option>
                                    <option value="Bancolombia">Bancolombia</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Efectivo">Efectivo</option>
                                </select>
                            </div>
                            <div class="add-field"><label>Referencia</label><input type="text" name="referencia" placeholder="# transacción"></div>
                            <div class="add-field"><label>Nota</label><input type="text" name="nota" placeholder="Opcional"></div>
                            <div class="add-field" style="grid-column: 1 / -1;">
                                <button type="submit" class="btn-add" style="background: linear-gradient(135deg, #34d399 0%, #059669 100%); box-shadow: 0 3px 10px rgba(5,150,105,0.25);">
                                    <i class="fas fa-plus"></i> Registrar Pago de Gestión
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        {{-- TAB: OTROS GASTOS --}}
        <div class="tab-content" id="tab-gastos">
            <div class="section-summary">
                <div class="ss-stat">
                    <div class="ss-stat-num" style="color: var(--danger);">${{ number_format($project->total_gastos, 0, ',', '.') }}</div>
                    <div class="ss-stat-label">Total Gastos</div>
                </div>
                <div class="ss-stat">
                    <div class="ss-stat-num">{{ $project->expenses->count() }}</div>
                    <div class="ss-stat-label">Items</div>
                </div>
            </div>

            @if($project->expenses->count() > 0)
                <ul class="item-list">
                    @foreach($project->expenses as $expense)
                        <li class="item-row">
                            <div class="item-info">
                                <div class="item-primary">
                                    <i class="fas fa-tag" style="color:#aaa; font-size:0.78rem;"></i> {{ $expense->concepto }}
                                    @if($expense->categoria)
                                        <span style="background:rgba(0,123,255,0.1); color:var(--primary-blue); padding:0.1rem 0.5rem; border-radius:6px; font-size:0.65rem; font-weight:700; text-transform:uppercase;">{{ $expense->categoria }}</span>
                                    @endif
                                </div>
                                <div class="item-secondary">
                                    {{ $expense->fecha->format('d/m/Y') }}
                                    @if($expense->descripcion) &middot; {{ $expense->descripcion }} @endif
                                </div>
                            </div>
                            <div class="item-amount" style="color: var(--danger);">
                                &minus;{{ $expense->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($expense->monto, 0, ',', '.') }}<small>{{ $expense->moneda }}</small>
                            </div>
                            <form action="{{ route('admin.internal-projects.expenses.destroy', [$project, $expense]) }}" method="POST" onsubmit="return confirm('Eliminar este gasto?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-data"><i class="fas fa-receipt"></i> No hay otros gastos registrados</div>
            @endif

            <div class="add-form-card">
                <h4><i class="fas fa-plus-circle"></i> Registrar Otro Gasto</h4>
                <form action="{{ route('admin.internal-projects.expenses.store', $project) }}" method="POST">
                    @csrf
                    <div class="add-row">
                        <div class="add-field"><label>Concepto *</label><input type="text" name="concepto" required placeholder="Ej: Hosting, Dominio, Plugin"></div>
                        <div class="add-field">
                            <label>Categoria</label>
                            <select name="categoria">
                                <option value="">Sin categoria</option>
                                <option value="Hosting">Hosting</option>
                                <option value="Dominio">Dominio</option>
                                <option value="Licencia">Licencia / Plugin</option>
                                <option value="Diseno">Diseno</option>
                                <option value="Publicidad">Publicidad</option>
                                <option value="Comision">Comision plataforma</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="add-field"><label>Monto *</label><input type="text" inputmode="decimal" name="monto" required class="js-money-input" placeholder="0"></div>
                        <div class="add-field">
                            <label>Moneda</label>
                            <select name="moneda">
                                <option value="COP">COP</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div class="add-field"><label>Fecha *</label><input type="date" name="fecha" required value="{{ date('Y-m-d') }}"></div>
                        <div class="add-field" style="grid-column: 1 / -1;"><label>Descripcion / Para que</label><input type="text" name="descripcion" placeholder="Detalles del gasto..."></div>
                        <div class="add-field" style="grid-column: 1 / -1;"><button type="submit" class="btn-add" style="background: var(--gradient-danger); box-shadow: 0 3px 10px rgba(220,53,69,0.25);"><i class="fas fa-plus"></i> Registrar Gasto</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB: ARCHIVOS --}}
        <div class="tab-content" id="tab-archivos">
            @if($project->files->count() > 0)
                <ul class="item-list">
                    @foreach($project->files as $file)
                        <li class="item-row">
                            <i class="fas {{ $file->icono }} file-icon"></i>
                            <div class="item-info">
                                <div class="item-primary">{{ $file->nombre }}</div>
                                <div class="item-secondary">{{ $file->tamano_formateado }} &middot; {{ Str::afterLast($file->archivo, '.') }}</div>
                            </div>
                            <a href="{{ Storage::url($file->archivo) }}" target="_blank" class="file-download"><i class="fas fa-download"></i> Abrir</a>
                            <form action="{{ route('admin.internal-projects.files.destroy', [$project, $file]) }}" method="POST" onsubmit="return confirm('Eliminar este archivo?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="no-data"><i class="fas fa-paperclip"></i> No hay archivos adjuntos</div>
            @endif

            <div class="add-form-card">
                <h4><i class="fas fa-upload"></i> Subir Archivo</h4>
                <form action="{{ route('admin.internal-projects.files.store', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="add-row">
                        <div class="add-field"><label>Nombre *</label><input type="text" name="nombre" required placeholder="Ej: Cotizacion, Plan de trabajo"></div>
                        <div class="add-field" style="grid-column: span 2;"><label>Archivo * (max 20MB)</label><input type="file" name="archivo" required></div>
                        <div class="add-field"><button type="submit" class="btn-add"><i class="fas fa-upload"></i> Subir</button></div>
                    </div>
                </form>
            </div>
        </div>

        {{-- TAB: NOTAS --}}
        <div class="tab-content" id="tab-notas">
            @if($project->notas)
                <div class="notes-content">{{ $project->notas }}</div>
            @else
                <div class="no-data"><i class="fas fa-sticky-note"></i> Sin notas. Edita el proyecto para agregarlas.</div>
            @endif
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('tab-' + tab).classList.add('active');
        });
    });

    /* ──────────────────────────────────────────────────────────────────
       Money input formatting: puntos de miles mientras escribís.
       1.234.567 visual → 1234567 al backend (limpio antes de submit).
       Acepta coma decimal opcional (1.234,50 → 1234.50).
       ────────────────────────────────────────────────────────────────── */
    (function () {
        const nf = new Intl.NumberFormat('es-CO', { maximumFractionDigits: 2 });

        const cleanRaw = (str) => {
            // Quita todo lo que no sea dígito o coma decimal
            return String(str || '').replace(/[^\d,]/g, '');
        };

        const formatVisual = (raw) => {
            if (!raw) return '';
            const parts = raw.split(',');
            const intPart = parts[0].replace(/^0+(?=\d)/, ''); // sin ceros a la izquierda
            const intFormatted = intPart === '' ? '' : nf.format(parseInt(intPart, 10) || 0);
            if (parts.length > 1) {
                return intFormatted + ',' + parts[1].slice(0, 2);
            }
            return intFormatted;
        };

        const toBackendValue = (visual) => {
            // "1.234.567,89" → "1234567.89"
            return String(visual || '').replace(/\./g, '').replace(',', '.');
        };

        const init = (input) => {
            // Si trae value precargado (ej: saldo gestión), formatearlo
            if (input.value) {
                const num = parseFloat(input.value.replace(',', '.'));
                if (!isNaN(num)) {
                    input.value = nf.format(num);
                }
            }
            input.addEventListener('input', (e) => {
                const raw = cleanRaw(e.target.value);
                e.target.value = formatVisual(raw);
            });
            // Limpia antes de submit
            const form = input.closest('form');
            if (form && !form.dataset.moneyHookAttached) {
                form.dataset.moneyHookAttached = '1';
                form.addEventListener('submit', () => {
                    form.querySelectorAll('.js-money-input').forEach(el => {
                        el.value = toBackendValue(el.value);
                    });
                });
            }
        };

        document.querySelectorAll('.js-money-input').forEach(init);
    })();
</script>
@endsection
