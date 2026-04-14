@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --primary-dark: #0056b3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .show-container {
        background: var(--light-gray);
        max-width: 1100px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    /* Header */
    .show-header {
        padding: 1.75rem 2rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        margin-bottom: 1.5rem;
    }

    .show-header-top {
        display: flex;
        justify-content: space-between;
        align-items: start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .show-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.3rem 0;
        color: white;
    }

    .show-header-badges {
        display: flex;
        gap: 0.4rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .h-badge {
        padding: 0.3rem 0.7rem;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .h-badge-estado { background: rgba(255,255,255,0.2); color: white; }
    .h-badge-fuente { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.9); }

    .show-header-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .btn-h {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        font-size: 0.82rem;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: none;
        cursor: pointer;
    }

    .btn-h-back { background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); color: white; }
    .btn-h-back:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; }

    .btn-h-edit { background: rgba(255,255,255,0.9); color: var(--primary-blue); }
    .btn-h-edit:hover { background: white; color: var(--primary-dark); text-decoration: none; }

    .btn-h-delete { background: rgba(220,53,69,0.8); color: white; }
    .btn-h-delete:hover { background: #dc3545; }

    /* Info Grid */
    .show-header-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }

    .info-item-label { font-size: 0.72rem; opacity: 0.7; text-transform: uppercase; font-weight: 600; }
    .info-item-value { font-size: 0.95rem; font-weight: 700; }

    /* Alert */
    .alert-success {
        background: var(--white);
        color: #155724;
        border: 1px solid rgba(40,167,69,0.2);
        border-left: 4px solid #28a745;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        box-shadow: var(--shadow-soft);
    }

    /* Content Grid */
    .show-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .show-grid .full-width { grid-column: 1 / -1; }

    /* Section */
    .show-section {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }

    .show-section-header {
        padding: 0.85rem 1.25rem;
        background: rgba(0,0,0,0.015);
        border-bottom: 1px solid rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .show-section-header h3 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--dark-text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .show-section-header h3 i { color: var(--primary-blue); font-size: 0.85rem; }

    .show-section-body { padding: 1.25rem; }

    /* Payment Progress */
    .payment-summary {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .pay-stat { text-align: center; }
    .pay-stat-num { font-size: 1.25rem; font-weight: 800; color: var(--dark-text); }
    .pay-stat-label { font-size: 0.7rem; color: #888; text-transform: uppercase; font-weight: 600; }

    .pay-progress-bar {
        flex: 1;
        min-width: 150px;
        height: 10px;
        background: #eee;
        border-radius: 5px;
        overflow: hidden;
    }

    .pay-progress-fill { height: 100%; border-radius: 5px; transition: width 0.5s ease; }

    /* Payment/File List */
    .item-list { list-style: none; padding: 0; margin: 0; }

    .item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        gap: 0.75rem;
    }

    .item-row:last-child { border-bottom: none; }

    .item-info { flex: 1; min-width: 0; }

    .item-primary { font-size: 0.88rem; font-weight: 600; color: var(--dark-text); }
    .item-secondary { font-size: 0.75rem; color: #999; }

    .item-amount { font-weight: 700; color: var(--dark-text); font-size: 0.9rem; white-space: nowrap; }

    .btn-delete-sm {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: none;
        background: rgba(220,53,69,0.08);
        color: #dc3545;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .btn-delete-sm:hover { background: #dc3545; color: white; }

    .file-icon { font-size: 1.3rem; color: #666; margin-right: 0.5rem; flex-shrink: 0; }

    .file-download {
        padding: 0.3rem 0.7rem;
        border-radius: 8px;
        background: rgba(0,123,255,0.08);
        color: var(--primary-blue);
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .file-download:hover { background: var(--primary-blue); color: white; text-decoration: none; }

    /* Add Forms */
    .add-form {
        padding: 1rem 1.25rem;
        background: rgba(0,123,255,0.02);
        border-top: 1px solid rgba(0,0,0,0.04);
    }

    .add-form h4 {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .add-form h4 i { color: var(--primary-blue); }

    .add-row {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        align-items: end;
    }

    .add-field { flex: 1; min-width: 120px; }
    .add-field label { font-size: 0.72rem; font-weight: 600; color: #888; margin-bottom: 0.2rem; display: block; }
    .add-field input, .add-field select { font-size: 0.82rem; padding: 0.45rem 0.7rem; }

    .btn-add {
        padding: 0.45rem 1rem;
        border-radius: 10px;
        border: none;
        background: var(--gradient-blue);
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-add:hover { transform: translateY(-1px); }

    /* Notas */
    .notes-content {
        font-size: 0.88rem;
        color: #555;
        line-height: 1.7;
        white-space: pre-wrap;
    }

    .no-data { color: #bbb; font-size: 0.85rem; font-style: italic; }

    @media (max-width: 768px) {
        .show-container { padding: 1rem; }
        .show-grid { grid-template-columns: 1fr; }
        .show-header-top { flex-direction: column; }
        .show-header-actions { flex-wrap: wrap; }
        .show-header-info { grid-template-columns: repeat(2, 1fr); }
        .payment-summary { flex-direction: column; align-items: stretch; }
        .add-row { flex-direction: column; }
        .add-field { min-width: auto; }
    }
</style>

<div class="show-container">
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="show-header">
        <div class="show-header-top">
            <div>
                <div class="show-header-badges">
                    <span class="h-badge h-badge-estado">{{ $project->estado_label }}</span>
                    <span class="h-badge h-badge-fuente">{{ $project->fuente == 'workana' ? 'Workana' : 'Directo' }}</span>
                </div>
                <h1>{{ $project->nombre }}</h1>
                <span style="opacity: 0.8; font-size: 0.88rem;">
                    <i class="fas fa-user me-1"></i> {{ $project->cliente_nombre }}
                    @if($project->fuente_url)
                        <a href="{{ $project->fuente_url }}" target="_blank" style="color: rgba(255,255,255,0.8); margin-left: 0.5rem;"><i class="fas fa-external-link-alt"></i></a>
                    @endif
                </span>
            </div>
            <div class="show-header-actions">
                <a href="{{ route('admin.internal-projects.index') }}" class="btn-h btn-h-back"><i class="fas fa-arrow-left"></i> Volver</a>
                <a href="{{ route('admin.internal-projects.edit', $project) }}" class="btn-h btn-h-edit"><i class="fas fa-edit"></i> Editar</a>
                <form action="{{ route('admin.internal-projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Eliminar este proyecto y todos sus datos?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-h btn-h-delete"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>

        <div class="show-header-info">
            <div class="info-item">
                <span class="info-item-label">Precio</span>
                <span class="info-item-value">
                    {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->precio, 0, ',', '.') }}
                    <small style="opacity:0.7">{{ $project->moneda }}</small>
                </span>
            </div>
            <div class="info-item">
                <span class="info-item-label">Pagado</span>
                <span class="info-item-value">
                    {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->total_pagado, 0, ',', '.') }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-item-label">Inicio</span>
                <span class="info-item-value">{{ $project->fecha_inicio ? $project->fecha_inicio->format('d/m/Y') : '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-item-label">Entrega</span>
                <span class="info-item-value">
                    @if($project->es_recurrente)
                        <span style="color:#007BFF; font-weight:700;"><i class="fas fa-sync-alt"></i> Recurrente</span>
                    @else
                        {{ $project->fecha_entrega ? $project->fecha_entrega->format('d/m/Y') : '-' }}
                    @endif
                </span>
            </div>
            @if($project->cliente_contacto)
            <div class="info-item">
                <span class="info-item-label">Contacto</span>
                <span class="info-item-value">{{ $project->cliente_contacto }}</span>
            </div>
            @endif
            @if($project->cliente_email)
            <div class="info-item">
                <span class="info-item-label">Email</span>
                <span class="info-item-value">{{ $project->cliente_email }}</span>
            </div>
            @endif
            @if($project->desarrollador_nombre)
            <div class="info-item">
                <span class="info-item-label">Desarrollador</span>
                <span class="info-item-value"><i class="fas fa-laptop-code" style="font-size:0.75rem; opacity:0.7;"></i> {{ $project->desarrollador_nombre }}</span>
            </div>
            @endif
            @if($project->desarrollador_email)
            <div class="info-item">
                <span class="info-item-label">Email Dev</span>
                <span class="info-item-value">{{ $project->desarrollador_email }}</span>
            </div>
            @endif
            @if($project->desarrollador_pago)
            <div class="info-item">
                <span class="info-item-label">Pago Dev</span>
                <span class="info-item-value">
                    {{ $project->desarrollador_moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->desarrollador_pago, 0, ',', '.') }}
                    <small style="opacity:0.7">{{ $project->desarrollador_moneda }}</small>
                </span>
            </div>
            @endif
        </div>
    </div>

    {{-- UTILIDAD --}}
    @if($project->desarrollador_pago || $project->total_pagado_dev > 0 || $project->total_recibido_cop > 0)
    @php
        $utilidad = $project->utilidad;
        $utilColor = $utilidad >= 0 ? '#28a745' : '#dc3545';
    @endphp
    <div class="show-section" style="margin-bottom: 1.25rem;">
        <div class="show-section-header">
            <h3><i class="fas fa-chart-line"></i> Utilidad del Proyecto</h3>
        </div>
        <div class="show-section-body">
            <div class="payment-summary">
                <div class="pay-stat">
                    <div class="pay-stat-num" style="color:#28a745;">${{ number_format($project->total_recibido_cop, 0, ',', '.') }}</div>
                    <div class="pay-stat-label">Recibido (COP)</div>
                </div>
                <div class="pay-stat">
                    <div class="pay-stat-num" style="color:#dc3545;">${{ number_format($project->total_pagado_dev, 0, ',', '.') }}</div>
                    <div class="pay-stat-label">Pagado al Dev</div>
                </div>
                <div class="pay-stat" style="flex:1; border-left: 2px dashed #eee; padding-left: 1.5rem;">
                    <div class="pay-stat-num" style="color: {{ $utilColor }}; font-size: 1.6rem;">${{ number_format($utilidad, 0, ',', '.') }}</div>
                    <div class="pay-stat-label">Utilidad neta</div>
                </div>
            </div>
            <p style="margin: 0.75rem 0 0; font-size: 0.75rem; color: #999;">
                <i class="fas fa-info-circle"></i> Calculado con el monto real recibido en COP (despues de impuestos/comisiones) menos lo pagado al desarrollador.
            </p>
        </div>
    </div>
    @endif

    <div class="show-grid">
        {{-- PAGOS --}}
        <div class="show-section">
            <div class="show-section-header">
                <h3><i class="fas fa-money-bill-wave"></i> Pagos</h3>
            </div>
            <div class="show-section-body">
                @php
                    $porcentaje = $project->porcentaje_pagado;
                    $porcentaje = min($porcentaje, 100);
                    $progressColor = $porcentaje >= 100 ? '#28a745' : ($porcentaje >= 50 ? '#007BFF' : '#f7a831');
                    $saldo = $project->saldo_pendiente;
                @endphp

                <div class="payment-summary">
                    <div class="pay-stat">
                        <div class="pay-stat-num" style="color: {{ $progressColor }};">{{ $porcentaje }}%</div>
                        <div class="pay-stat-label">Pagado</div>
                    </div>
                    <div class="pay-progress-bar">
                        <div class="pay-progress-fill" style="width: {{ $porcentaje }}%; background: {{ $progressColor }};"></div>
                    </div>
                    <div class="pay-stat">
                        <div class="pay-stat-num" style="color: {{ $saldo > 0 ? '#dc3545' : '#28a745' }};">
                            {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format(abs($saldo), 0, ',', '.') }}
                        </div>
                        <div class="pay-stat-label">{{ $saldo > 0 ? 'Pendiente' : 'Saldado' }}</div>
                    </div>
                </div>

                @if($project->payments->count() > 0)
                    <ul class="item-list">
                        @foreach($project->payments as $payment)
                            <li class="item-row">
                                <div class="item-info">
                                    <div class="item-primary">{{ $payment->fecha->format('d/m/Y') }}</div>
                                    <div class="item-secondary">
                                        {{ $payment->metodo ?: 'Sin metodo' }}
                                        @if($payment->referencia) &middot; Ref: {{ $payment->referencia }} @endif
                                        @if($payment->nota) &middot; {{ $payment->nota }} @endif
                                    </div>
                                </div>
                                <div style="text-align:right;">
                                    <span class="item-amount">
                                        {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($payment->monto, 0, ',', '.') }}
                                    </span>
                                    @if($payment->monto_recibido_cop)
                                        <div style="font-size: 0.7rem; color:#28a745; font-weight:600; margin-top:0.15rem;">
                                            <i class="fas fa-arrow-down"></i> ${{ number_format($payment->monto_recibido_cop, 0, ',', '.') }} COP recibido
                                        </div>
                                    @endif
                                </div>
                                <form action="{{ route('admin.internal-projects.payments.destroy', [$project, $payment]) }}" method="POST" onsubmit="return confirm('Eliminar este pago?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="no-data">No hay pagos registrados</p>
                @endif
            </div>

            <div class="add-form">
                <h4><i class="fas fa-plus-circle"></i> Registrar Pago</h4>
                <form action="{{ route('admin.internal-projects.payments.store', $project) }}" method="POST">
                    @csrf
                    <div class="add-row">
                        <div class="add-field">
                            <label>Monto *</label>
                            <input type="number" name="monto" class="form-control" required step="0.01" min="0.01" placeholder="0.00">
                        </div>
                        <div class="add-field">
                            <label>Fecha *</label>
                            <input type="date" name="fecha" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="add-field">
                            <label>Metodo</label>
                            <select name="metodo" class="form-control">
                                <option value="">Seleccionar</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Workana">Workana</option>
                                <option value="Tarjeta">Tarjeta</option>
                            </select>
                        </div>
                        <div class="add-field">
                            <label>Recibido COP <small style="color:#aaa;">(neto)</small></label>
                            <input type="number" name="monto_recibido_cop" class="form-control" step="0.01" min="0" placeholder="Despues de impuestos">
                        </div>
                        <div class="add-field">
                            <label>Referencia</label>
                            <input type="text" name="referencia" class="form-control" placeholder="# transaccion">
                        </div>
                        <button type="submit" class="btn-add"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PAGOS AL DESARROLLADOR --}}
        <div class="show-section full-width">
            <div class="show-section-header">
                <h3><i class="fas fa-laptop-code"></i> Pagos al Desarrollador @if($project->desarrollador_nombre)<small style="color:#999; font-weight:500;">- {{ $project->desarrollador_nombre }}</small>@endif</h3>
            </div>
            <div class="show-section-body">
                @if($project->desarrollador_pago)
                    @php
                        $pagadoDev = $project->total_pagado_dev;
                        $pctDev = $project->desarrollador_pago > 0 ? min(round(($pagadoDev / $project->desarrollador_pago) * 100), 100) : 0;
                        $colorDev = $pctDev >= 100 ? '#28a745' : ($pctDev >= 50 ? '#007BFF' : '#f7a831');
                        $saldoDev = $project->saldo_pendiente_dev;
                    @endphp
                    <div class="payment-summary">
                        <div class="pay-stat">
                            <div class="pay-stat-num" style="color: {{ $colorDev }};">{{ $pctDev }}%</div>
                            <div class="pay-stat-label">Pagado al Dev</div>
                        </div>
                        <div class="pay-progress-bar">
                            <div class="pay-progress-fill" style="width: {{ $pctDev }}%; background: {{ $colorDev }};"></div>
                        </div>
                        <div class="pay-stat">
                            <div class="pay-stat-num" style="color: {{ $saldoDev > 0 ? '#dc3545' : '#28a745' }};">
                                {{ $project->desarrollador_moneda == 'COP' ? '$' : 'US$' }}{{ number_format(abs($saldoDev), 0, ',', '.') }}
                            </div>
                            <div class="pay-stat-label">{{ $saldoDev > 0 ? 'Por pagar' : 'Saldado' }}</div>
                        </div>
                    </div>
                @endif

                @if($project->developerPayments->count() > 0)
                    <ul class="item-list">
                        @foreach($project->developerPayments as $devPayment)
                            <li class="item-row">
                                <div class="item-info">
                                    <div class="item-primary">{{ $devPayment->fecha->format('d/m/Y') }}</div>
                                    <div class="item-secondary">
                                        {{ $devPayment->metodo ?: 'Sin metodo' }}
                                        @if($devPayment->referencia) &middot; Ref: {{ $devPayment->referencia }} @endif
                                        @if($devPayment->nota) &middot; {{ $devPayment->nota }} @endif
                                    </div>
                                </div>
                                <span class="item-amount">
                                    {{ $devPayment->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($devPayment->monto, 0, ',', '.') }}
                                    <small style="color:#999; font-weight:500;">{{ $devPayment->moneda }}</small>
                                </span>
                                <form action="{{ route('admin.internal-projects.developer-payments.destroy', [$project, $devPayment]) }}" method="POST" onsubmit="return confirm('Eliminar este pago al desarrollador?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="no-data">No hay pagos al desarrollador registrados</p>
                @endif
            </div>

            <div class="add-form">
                <h4><i class="fas fa-plus-circle"></i> Registrar Pago al Desarrollador</h4>
                <form action="{{ route('admin.internal-projects.developer-payments.store', $project) }}" method="POST">
                    @csrf
                    <div class="add-row">
                        <div class="add-field">
                            <label>Monto *</label>
                            <input type="number" name="monto" class="form-control" required step="0.01" min="0.01" placeholder="0.00">
                        </div>
                        <div class="add-field" style="max-width: 100px;">
                            <label>Moneda</label>
                            <select name="moneda" class="form-control">
                                <option value="COP" {{ ($project->desarrollador_moneda ?? 'COP') == 'COP' ? 'selected' : '' }}>COP</option>
                                <option value="USD" {{ ($project->desarrollador_moneda ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        <div class="add-field">
                            <label>Fecha *</label>
                            <input type="date" name="fecha" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="add-field">
                            <label>Metodo</label>
                            <select name="metodo" class="form-control">
                                <option value="">Seleccionar</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Nequi">Nequi</option>
                                <option value="Daviplata">Daviplata</option>
                                <option value="Bancolombia">Bancolombia</option>
                                <option value="PayPal">PayPal</option>
                                <option value="Efectivo">Efectivo</option>
                            </select>
                        </div>
                        <div class="add-field">
                            <label>Nota</label>
                            <input type="text" name="nota" class="form-control" placeholder="Opcional">
                        </div>
                        <button type="submit" class="btn-add"><i class="fas fa-plus"></i> Agregar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ARCHIVOS --}}
        <div class="show-section">
            <div class="show-section-header">
                <h3><i class="fas fa-paperclip"></i> Archivos</h3>
            </div>
            <div class="show-section-body">
                @if($project->files->count() > 0)
                    <ul class="item-list">
                        @foreach($project->files as $file)
                            <li class="item-row">
                                <i class="fas {{ $file->icono }} file-icon"></i>
                                <div class="item-info">
                                    <div class="item-primary">{{ $file->nombre }}</div>
                                    <div class="item-secondary">{{ $file->tamano_formateado }} &middot; {{ Str::afterLast($file->archivo, '.') }}</div>
                                </div>
                                <a href="{{ Storage::url($file->archivo) }}" target="_blank" class="file-download">
                                    <i class="fas fa-download"></i> Abrir
                                </a>
                                <form action="{{ route('admin.internal-projects.files.destroy', [$project, $file]) }}" method="POST" onsubmit="return confirm('Eliminar este archivo?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete-sm"><i class="fas fa-times"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="no-data">No hay archivos adjuntos</p>
                @endif
            </div>

            <div class="add-form">
                <h4><i class="fas fa-upload"></i> Subir Archivo</h4>
                <form action="{{ route('admin.internal-projects.files.store', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="add-row">
                        <div class="add-field">
                            <label>Nombre *</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Cotizacion, Plan de trabajo">
                        </div>
                        <div class="add-field" style="flex: 2;">
                            <label>Archivo * (max 20MB)</label>
                            <input type="file" name="archivo" class="form-control" required>
                        </div>
                        <button type="submit" class="btn-add"><i class="fas fa-upload"></i> Subir</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DESCRIPCION --}}
        @if($project->descripcion)
        <div class="show-section">
            <div class="show-section-header">
                <h3><i class="fas fa-info-circle"></i> Descripcion</h3>
            </div>
            <div class="show-section-body">
                <div class="notes-content">{{ $project->descripcion }}</div>
            </div>
        </div>
        @endif

        {{-- NOTAS --}}
        <div class="show-section">
            <div class="show-section-header">
                <h3><i class="fas fa-sticky-note"></i> Notas</h3>
            </div>
            <div class="show-section-body">
                @if($project->notas)
                    <div class="notes-content">{{ $project->notas }}</div>
                @else
                    <p class="no-data">Sin notas</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
