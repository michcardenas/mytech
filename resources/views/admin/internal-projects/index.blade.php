@extends('layouts.app_admin')

@section('content')
<style>
    .pd-wrap { max-width:1320px; margin:0 auto; padding:1.5rem 1.75rem 3rem; background:#F6F7F9; }

    /* Header */
    .pd-hero {
        background: linear-gradient(135deg,#1E293B 0%,#0F172A 100%);
        color:#fff; border-radius:16px; padding:1.5rem 1.75rem; margin-bottom:1.25rem;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;
    }
    .pd-hero h1 { font-size:1.35rem; font-weight:800; margin:0; display:flex; align-items:center; gap:.55rem; color:#fff; letter-spacing:-.02em; }
    .pd-hero p  { font-size:.82rem; opacity:.75; margin:.2rem 0 0; }
    .pd-hero .icon { display:inline-flex; width:36px; height:36px; border-radius:10px; background:rgba(59,130,246,.2); align-items:center; justify-content:center; color:#93C5FD; }
    .pd-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .pd-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.55rem 1rem; border-radius:10px; font-weight:600; font-size:.83rem; text-decoration:none; border:1px solid transparent; transition:all .15s; cursor:pointer; }
    .pd-btn-ghost   { background:rgba(255,255,255,.08); color:#E2E8F0; border-color:rgba(255,255,255,.14); }
    .pd-btn-ghost:hover { background:rgba(255,255,255,.14); color:#fff; }
    .pd-btn-primary { background:#2563EB; color:#fff; border:none; }
    .pd-btn-primary:hover { background:#1D4ED8; color:#fff; }
    .pd-btn-alt { background:#fff; color:#334155; border-color:#E2E8F0; }
    .pd-btn-alt:hover { background:#F1F5F9; color:#0F172A; }

    /* KPI strip */
    .pd-kpis { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:.75rem; margin-bottom:1.25rem; }
    .pd-kpi { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:.9rem 1.1rem; position:relative; overflow:hidden; }
    .pd-kpi::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; background:#CBD5E1; }
    .pd-kpi.info::before  { background:#2563EB; }
    .pd-kpi.debt::before  { background:#7C3AED; }
    .pd-kpi.money::before { background:#0F766E; }
    .pd-kpi.warn::before  { background:#F97316; }
    .pd-kpi-label { font-size:.7rem; text-transform:uppercase; letter-spacing:.05em; color:#94A3B8; font-weight:700; }
    .pd-kpi-value { font-size:1.4rem; font-weight:800; color:#0F172A; margin-top:.15rem; letter-spacing:-.02em; }
    .pd-kpi-sub   { font-size:.74rem; color:#64748B; margin-top:.15rem; }
    .pd-kpi.info .pd-kpi-value  { color:#1D4ED8; }
    .pd-kpi.debt .pd-kpi-value  { color:#6D28D9; }
    .pd-kpi.money .pd-kpi-value { color:#0F766E; }
    .pd-kpi.warn { background:#FFF7ED; border-color:#FED7AA; }
    .pd-kpi.warn .pd-kpi-value  { color:#C2410C; }

    /* Search */
    .pd-search { display:flex; gap:.5rem; margin-bottom:1.5rem; max-width:520px; }
    .pd-search input { flex:1; padding:.6rem .9rem; border:1px solid #E2E8F0; border-radius:10px; background:#fff; font-size:.9rem; }
    .pd-search input:focus { outline:none; border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
    .pd-search button { padding:.6rem 1.1rem; border:none; background:#0F172A; color:#fff; border-radius:10px; font-weight:600; font-size:.85rem; cursor:pointer; }

    /* Dev cards */
    .pd-devs { display:flex; flex-direction:column; gap:1.1rem; }
    .pd-dev { background:#fff; border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; box-shadow:0 1px 2px rgba(15,23,42,.03); }
    .pd-dev.sin-dev { border-color:#FDBA74; background:#FFF7ED; }

    .pd-dev-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid #F1F5F9; background:#FAFBFC; }
    .pd-dev.sin-dev .pd-dev-head { background:transparent; border-bottom-color:#FED7AA; }
    .pd-dev-ident { display:flex; align-items:center; gap:.75rem; min-width:0; }
    .pd-avatar { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#2563EB,#1D4ED8); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.05rem; flex-shrink:0; }
    .pd-dev.sin-dev .pd-avatar { background:linear-gradient(135deg,#F97316,#EA580C); }
    .pd-dev-name { font-size:1.05rem; font-weight:700; color:#0F172A; margin:0; line-height:1.2; }
    .pd-dev-count { font-size:.78rem; color:#64748B; margin-top:.15rem; }

    .pd-dev-stats { display:flex; gap:1.5rem; flex-wrap:wrap; }
    .pd-dev-stat { text-align:right; }
    .pd-dev-stat-lbl { font-size:.68rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; }
    .pd-dev-stat-val { font-size:.95rem; font-weight:700; color:#0F172A; margin-top:.1rem; }
    .pd-dev-stat-val.debt   { color:#7C3AED; }
    .pd-dev-stat-val.income { color:#0F766E; }
    .pd-dev-stat-val.date   { color:#B45309; }
    .pd-dev-stat-val.date.late { color:#DC2626; }

    /* Project rows */
    .pd-proj-list { display:table; width:100%; border-collapse:collapse; }
    .pd-proj { display:table-row; transition:background .12s; }
    .pd-proj:hover { background:#F8FAFC; }
    .pd-proj > .cell { display:table-cell; padding:.85rem 1.25rem; border-top:1px solid #F1F5F9; vertical-align:middle; font-size:.88rem; color:#334155; }
    .pd-proj > .cell.name    { color:#0F172A; font-weight:600; min-width:200px; }
    .pd-proj > .cell.name a  { color:inherit; text-decoration:none; }
    .pd-proj > .cell.name a:hover { color:#2563EB; }
    .pd-proj > .cell.money   { text-align:right; font-variant-numeric:tabular-nums; white-space:nowrap; }
    .pd-proj > .cell.date    { white-space:nowrap; }
    .pd-proj > .cell.estado  { white-space:nowrap; text-align:right; }
    .pd-proj > .cell.acciones { width:1%; white-space:nowrap; text-align:right; padding-right:1rem; }
    .pd-proj-client { color:#64748B; font-size:.78rem; font-weight:500; margin-top:.15rem; }

    .pd-money-primary  { font-weight:700; color:#0F172A; font-size:.92rem; }
    .pd-money-currency { color:#94A3B8; font-size:.72rem; margin-left:.15rem; }
    .pd-money-debt     { color:#7C3AED; font-weight:700; }
    .pd-money-muted    { color:#94A3B8; }

    .pd-date-badge { display:inline-flex; align-items:center; gap:.4rem; padding:.25rem .55rem; border-radius:8px; font-size:.78rem; font-weight:600; }
    .pd-date-badge.ok    { background:#ECFDF5; color:#047857; }
    .pd-date-badge.soon  { background:#FEF3C7; color:#B45309; }
    .pd-date-badge.late  { background:#FEE2E2; color:#B91C1C; }
    .pd-date-badge.recur { background:#EDE9FE; color:#6D28D9; }
    .pd-date-badge.none  { background:#F1F5F9; color:#64748B; }

    .pd-estado { display:inline-flex; align-items:center; gap:.35rem; padding:.22rem .55rem; border-radius:999px; font-size:.72rem; font-weight:700; }
    .pd-estado .dot { width:6px; height:6px; border-radius:999px; background:currentColor; }
    .pd-estado.cotizado    { background:#FEF3C7; color:#B45309; }
    .pd-estado.en_progreso { background:#DBEAFE; color:#1D4ED8; }
    .pd-estado.pausado     { background:#F1F5F9; color:#475569; }
    .pd-estado.completado  { background:#DCFCE7; color:#166534; }
    .pd-estado.cancelado   { background:#FEE2E2; color:#B91C1C; }

    /* Action buttons */
    .pd-action-btn { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #E5E7EB; background:#fff; color:#64748B; cursor:pointer; margin-left:.25rem; transition:all .12s; font-size:.85rem; }
    .pd-action-btn:hover { border-color:#CBD5E1; color:#0F172A; background:#F8FAFC; transform:translateY(-1px); }
    .pd-action-btn.pay-client:hover { border-color:#10B981; color:#059669; background:#ECFDF5; }
    .pd-action-btn.pay-dev:hover    { border-color:#7C3AED; color:#7C3AED; background:#EDE9FE; }
    .pd-action-btn.view:hover       { border-color:#2563EB; color:#2563EB; background:#EFF6FF; }
    .pd-action-btn.edit:hover       { border-color:#64748B; color:#0F172A; background:#F1F5F9; }

    /* Completados */
    .pd-completados { border-top:1px solid #F1F5F9; background:#FAFBFC; }
    .pd-completados-toggle { display:flex; align-items:center; justify-content:space-between; padding:.65rem 1.25rem; cursor:pointer; user-select:none; color:#64748B; font-size:.82rem; font-weight:600; }
    .pd-completados-toggle:hover { background:#F1F5F9; color:#0F172A; }
    .pd-completados-toggle .chev { transition:transform .15s; }
    .pd-completados[open] .pd-completados-toggle .chev { transform:rotate(180deg); }
    .pd-completados[open] .pd-completados-toggle { background:#F1F5F9; color:#0F172A; }

    .pd-empty { text-align:center; padding:3rem 1rem; color:#94A3B8; }
    .pd-empty i { font-size:2rem; margin-bottom:.5rem; opacity:.5; }

    /* Modal input polish */
    .modal-content { border-radius:14px; border:none; }
    .modal-header { border-bottom:1px solid #F1F5F9; padding:1rem 1.25rem; }
    .modal-title  { font-size:1rem; font-weight:700; color:#0F172A; }
    .modal-title small { color:#64748B; font-weight:500; }
    .modal-body   { padding:1.25rem; }
    .modal-body .form-label { font-size:.78rem; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.02em; margin-bottom:.3rem; }
    .modal-body .form-control, .modal-body .form-select { border:1px solid #E2E8F0; border-radius:8px; padding:.55rem .75rem; font-size:.9rem; }
    .modal-body .form-control:focus, .modal-body .form-select:focus { border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
    .modal-footer { border-top:1px solid #F1F5F9; padding:.9rem 1.25rem; }

    @media (max-width:768px) {
        .pd-proj { display:flex; flex-direction:column; align-items:stretch; gap:.4rem; padding:.85rem 1rem; border-top:1px solid #F1F5F9; }
        .pd-proj > .cell { display:flex; justify-content:space-between; padding:.15rem 0; border:none; }
        .pd-proj > .cell.name { justify-content:flex-start; }
        .pd-proj > .cell.money::before,
        .pd-proj > .cell.date::before,
        .pd-proj > .cell.estado::before { content:attr(data-lbl); font-size:.7rem; text-transform:uppercase; color:#94A3B8; font-weight:700; }
        .pd-proj > .cell.acciones { justify-content:flex-end; padding-top:.35rem; }
        .pd-dev-stats { width:100%; justify-content:space-between; }
        .pd-dev-stat { text-align:left; }
    }
</style>

@php
    $fmtMoney = function ($val, $moneda) {
        if ($val === null || $val === '') {
            return '—';
        }
        $prefix = $moneda === 'USD' ? 'US$' : ($moneda === 'EUR' ? '€' : '$');

        return $prefix.number_format((float) $val, 0, ',', '.');
    };
    $fmtCop = fn ($v) => '$'.number_format((float) $v, 0, ',', '.');

    $dateBadge = function ($project) {
        if ($project->es_recurrente) {
            return ['recur', 'Recurrente'];
        }
        if (! $project->fecha_entrega) {
            return ['none', 'Sin fecha'];
        }
        $days = (int) now()->startOfDay()->diffInDays($project->fecha_entrega->startOfDay(), false);
        if ($days < 0) {
            return ['late', 'Vencido '.abs($days).'d'];
        }
        if ($days === 0) {
            return ['late', 'Hoy'];
        }
        if ($days <= 7) {
            return ['soon', 'En '.$days.'d'];
        }

        return ['ok', $project->fecha_entrega->format('d/m/Y')];
    };
@endphp

<div class="pd-wrap">
    <div class="pd-hero">
        <div>
            <h1><span class="icon"><i class="fas fa-users-cog"></i></span> Proyectos por desarrollador</h1>
            <p>Ve qué tiene cada dev, cuánto vale, qué le debes, y cuándo entrega. Sin ruido.</p>
        </div>
        <div class="pd-actions">
            <a href="{{ route('admin.internal-projects.todos') }}" class="pd-btn pd-btn-ghost"><i class="fas fa-list"></i> Ver todos con filtros</a>
            <a href="{{ route('admin.internal-projects.stats') }}" class="pd-btn pd-btn-ghost"><i class="fas fa-chart-pie"></i> Estadísticas</a>
            <a href="{{ route('admin.internal-projects.create') }}" class="pd-btn pd-btn-primary"><i class="fas fa-plus-circle"></i> Nuevo proyecto</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    <div class="pd-kpis">
        <div class="pd-kpi info">
            <div class="pd-kpi-label">Devs activos</div>
            <div class="pd-kpi-value">{{ $resumen['devs_activos'] }}</div>
            <div class="pd-kpi-sub">{{ $resumen['proyectos_activos'] }} proyectos activos</div>
        </div>
        <div class="pd-kpi debt">
            <div class="pd-kpi-label">Por pagar a devs</div>
            <div class="pd-kpi-value">{{ $fmtCop($resumen['por_pagar_dev_cop']) }}</div>
            <div class="pd-kpi-sub">Saldo total pendiente (COP)</div>
        </div>
        <div class="pd-kpi money">
            <div class="pd-kpi-label">Por cobrar</div>
            <div class="pd-kpi-value">{{ $fmtCop($resumen['por_cobrar_cop']) }}</div>
            <div class="pd-kpi-sub">Saldo total pendiente (COP)</div>
        </div>
        @if($resumen['sin_dev'] > 0)
            <div class="pd-kpi warn">
                <div class="pd-kpi-label">Sin desarrollador</div>
                <div class="pd-kpi-value">{{ $resumen['sin_dev'] }}</div>
                <div class="pd-kpi-sub">Proyectos activos por asignar</div>
            </div>
        @endif
    </div>

    <form method="GET" class="pd-search">
        <input type="text" name="buscar" placeholder="Buscar por proyecto, cliente o desarrollador..." value="{{ request('buscar') }}">
        <button type="submit"><i class="fas fa-search"></i> Buscar</button>
    </form>

    <div class="pd-devs">
        @forelse($grupos as $grupo)
            @php
                $activos = $grupo['activos'];
                $completados = $grupo['completados'];
                $sinDev = $grupo['is_sin_dev'];
                $displayName = $sinDev ? 'Sin desarrollador asignado' : $grupo['nombre'];
                $initial = strtoupper(mb_substr($displayName, 0, 1));
            @endphp
            @if($activos->isEmpty() && $completados->isEmpty())
                @continue
            @endif
            <div class="pd-dev {{ $sinDev ? 'sin-dev' : '' }}">
                <div class="pd-dev-head">
                    <div class="pd-dev-ident">
                        <div class="pd-avatar">
                            @if($sinDev)<i class="fas fa-user-slash"></i>@else{{ $initial }}@endif
                        </div>
                        <div>
                            <h3 class="pd-dev-name">{{ $displayName }}</h3>
                            <p class="pd-dev-count">
                                {{ $activos->count() }} activo{{ $activos->count() === 1 ? '' : 's' }}
                                @if($completados->count() > 0)
                                    · {{ $completados->count() }} completado{{ $completados->count() === 1 ? '' : 's' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="pd-dev-stats">
                        @if(! $sinDev && $grupo['por_pagar_dev_cop'] > 0)
                            <div class="pd-dev-stat">
                                <div class="pd-dev-stat-lbl">Le debo</div>
                                <div class="pd-dev-stat-val debt">{{ $fmtCop($grupo['por_pagar_dev_cop']) }}</div>
                            </div>
                        @endif
                        @if($grupo['por_cobrar_cop'] > 0)
                            <div class="pd-dev-stat">
                                <div class="pd-dev-stat-lbl">Por cobrar</div>
                                <div class="pd-dev-stat-val income">{{ $fmtCop($grupo['por_cobrar_cop']) }}</div>
                            </div>
                        @endif
                        @if($grupo['proxima_entrega'])
                            @php $diff = (int) now()->startOfDay()->diffInDays($grupo['proxima_entrega']->startOfDay(), false); @endphp
                            <div class="pd-dev-stat">
                                <div class="pd-dev-stat-lbl">Próxima entrega</div>
                                <div class="pd-dev-stat-val date {{ $diff < 0 ? 'late' : '' }}">
                                    {{ $grupo['proxima_entrega']->format('d/m/Y') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($activos->isNotEmpty())
                    <div class="pd-proj-list">
                        @foreach($activos as $p)
                            @php
                                $saldoDev = max((float) ($p->desarrollador_pago ?? 0) - (float) ($p->developer_payments_sum_monto ?? 0), 0);
                                [$badgeClass, $badgeText] = $dateBadge($p);
                            @endphp
                            <div class="pd-proj">
                                <div class="cell name">
                                    <a href="{{ route('admin.internal-projects.show', $p) }}">{{ $p->nombre }}</a>
                                    <div class="pd-proj-client">{{ $p->cliente_nombre ?: 'Sin cliente' }}</div>
                                </div>
                                <div class="cell money" data-lbl="Valor">
                                    <span class="pd-money-primary">{{ $fmtMoney($p->precio, $p->moneda) }}</span>
                                    <span class="pd-money-currency">{{ $p->moneda }}</span>
                                </div>
                                <div class="cell money" data-lbl="Pago dev">
                                    @if($p->desarrollador_pago)
                                        <span class="pd-money-primary">{{ $fmtMoney($p->desarrollador_pago, $p->desarrollador_moneda ?? 'COP') }}</span>
                                        <span class="pd-money-currency">{{ $p->desarrollador_moneda ?? 'COP' }}</span>
                                        @if($saldoDev > 0)
                                            <div class="pd-money-debt" style="font-size:.72rem; margin-top:.15rem;">Saldo: {{ $fmtMoney($saldoDev, $p->desarrollador_moneda ?? 'COP') }}</div>
                                        @endif
                                    @else
                                        <span class="pd-money-muted">—</span>
                                    @endif
                                </div>
                                <div class="cell date" data-lbl="Entrega">
                                    <span class="pd-date-badge {{ $badgeClass }}"><i class="fas fa-flag-checkered"></i> {{ $badgeText }}</span>
                                </div>
                                <div class="cell estado" data-lbl="Estado">
                                    <span class="pd-estado {{ $p->estado }}"><span class="dot"></span>{{ $p->estado_label }}</span>
                                </div>
                                <div class="cell acciones">
                                    <button type="button" class="pd-action-btn pay-client js-open-pay"
                                        title="Registrar pago del cliente"
                                        data-kind="cliente"
                                        data-project-id="{{ $p->id }}"
                                        data-project-name="{{ $p->nombre }}"
                                        data-moneda="{{ $p->moneda }}"
                                        data-saldo="{{ max((float) $p->precio - (float) ($p->payments_sum_monto ?? 0), 0) }}"
                                        data-action="{{ route('admin.internal-projects.payments.store', $p) }}"
                                    ><i class="fas fa-hand-holding-usd"></i></button>
                                    @if($p->desarrollador_pago && $p->desarrollador_nombre)
                                        <button type="button" class="pd-action-btn pay-dev js-open-pay"
                                            title="Registrar pago al desarrollador"
                                            data-kind="dev"
                                            data-project-id="{{ $p->id }}"
                                            data-project-name="{{ $p->nombre }}"
                                            data-dev-nombre="{{ $p->desarrollador_nombre }}"
                                            data-moneda="{{ $p->desarrollador_moneda ?? 'COP' }}"
                                            data-saldo="{{ $saldoDev }}"
                                            data-action="{{ route('admin.internal-projects.developer-payments.store', $p) }}"
                                        ><i class="fas fa-paper-plane"></i></button>
                                    @endif
                                    <a href="{{ route('admin.internal-projects.show', $p) }}" class="pd-action-btn view" title="Ver detalle"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.internal-projects.edit', $p) }}" class="pd-action-btn edit" title="Editar"><i class="fas fa-pen"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($completados->isNotEmpty())
                    <details class="pd-completados">
                        <summary class="pd-completados-toggle">
                            <span><i class="fas fa-check-circle me-1"></i> Completados / cancelados ({{ $completados->count() }})</span>
                            <i class="fas fa-chevron-down chev"></i>
                        </summary>
                        <div class="pd-proj-list">
                            @foreach($completados as $p)
                                <div class="pd-proj">
                                    <div class="cell name">
                                        <a href="{{ route('admin.internal-projects.show', $p) }}">{{ $p->nombre }}</a>
                                        <div class="pd-proj-client">{{ $p->cliente_nombre ?: 'Sin cliente' }}</div>
                                    </div>
                                    <div class="cell money" data-lbl="Valor">
                                        <span class="pd-money-primary">{{ $fmtMoney($p->precio, $p->moneda) }}</span>
                                        <span class="pd-money-currency">{{ $p->moneda }}</span>
                                    </div>
                                    <div class="cell money" data-lbl="Pago dev">
                                        @if($p->desarrollador_pago)
                                            <span class="pd-money-primary">{{ $fmtMoney($p->desarrollador_pago, $p->desarrollador_moneda ?? 'COP') }}</span>
                                            <span class="pd-money-currency">{{ $p->desarrollador_moneda ?? 'COP' }}</span>
                                        @else
                                            <span class="pd-money-muted">—</span>
                                        @endif
                                    </div>
                                    <div class="cell date" data-lbl="Entrega">
                                        <span class="pd-money-muted" style="font-size:.78rem;">{{ $p->fecha_entrega ? $p->fecha_entrega->format('d/m/Y') : '—' }}</span>
                                    </div>
                                    <div class="cell estado" data-lbl="Estado">
                                        <span class="pd-estado {{ $p->estado }}"><span class="dot"></span>{{ $p->estado_label }}</span>
                                    </div>
                                    <div class="cell acciones">
                                        <a href="{{ route('admin.internal-projects.show', $p) }}" class="pd-action-btn view" title="Ver detalle"><i class="fas fa-eye"></i></a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>
        @empty
            <div class="pd-empty">
                <i class="fas fa-briefcase"></i>
                <h3 style="margin:0; font-size:1rem; color:#64748B;">No hay proyectos que coincidan.</h3>
                <p style="margin:.35rem 0 0; font-size:.85rem;">Ajusta la búsqueda o crea uno nuevo.</p>
            </div>
        @endforelse
    </div>
</div>

@endsection

@push('modals')
{{-- Modal de pago rápido (cliente o dev, según data-kind) --}}
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="payForm" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="payTitle">Registrar pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3" style="font-size:.85rem;">
                    Proyecto: <strong id="payProject">—</strong><br>
                    <span id="paySaldoLine" style="color:#7C3AED; font-size:.8rem;"></span>
                </p>

                <div class="row g-3">
                    <div class="col-8">
                        <label class="form-label">Monto</label>
                        <input type="number" name="monto" step="0.01" min="0.01" class="form-control" id="payMonto" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label">Moneda</label>
                        <select name="moneda" class="form-select" id="payMoneda">
                            <option value="COP">COP</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Método <span class="text-muted">(opcional)</span></label>
                        <input type="text" name="metodo" class="form-control" placeholder="Ej: Nequi, Bancolombia, PayPal...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Referencia <span class="text-muted">(opcional)</span></label>
                        <input type="text" name="referencia" class="form-control" placeholder="Nro. transacción, link, etc.">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Nota <span class="text-muted">(opcional)</span></label>
                        <textarea name="nota" rows="2" class="form-control" placeholder="Detalle del pago..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn pd-btn-primary" id="paySubmit"><i class="fas fa-check"></i> Registrar</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const modalEl = document.getElementById('payModal');
    if (! modalEl || typeof bootstrap === 'undefined') return;

    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('payForm');
    const title = document.getElementById('payTitle');
    const projName = document.getElementById('payProject');
    const monedaEl = document.getElementById('payMoneda');
    const montoEl = document.getElementById('payMonto');
    const saldoLine = document.getElementById('paySaldoLine');
    const monedaWrap = monedaEl.closest('.col-4');
    const monedaSize = monedaEl.closest('.col-4');
    const submitBtn = document.getElementById('paySubmit');

    document.querySelectorAll('.js-open-pay').forEach(btn => {
        btn.addEventListener('click', () => {
            const kind = btn.dataset.kind;
            const isClient = kind === 'cliente';
            form.action = btn.dataset.action;

            title.innerHTML = isClient
                ? '<i class="fas fa-hand-holding-usd me-2" style="color:#10B981;"></i> Pago del cliente'
                : '<i class="fas fa-paper-plane me-2" style="color:#7C3AED;"></i> Pago al desarrollador <small>· ' + btn.dataset.devNombre + '</small>';

            projName.textContent = btn.dataset.projectName;

            const saldo = parseFloat(btn.dataset.saldo || 0);
            const moneda = btn.dataset.moneda || 'COP';
            const prefix = moneda$moneda === 'USD' ? 'US$' : ($moneda === 'EUR' ? '€' : '$');
            const saldoTxt = saldo > 0
                ? 'Saldo pendiente: <strong>' + prefix + Math.round(saldo).toLocaleString('es-CO') + ' ' + moneda + '</strong>'
                : 'Sin saldo pendiente registrado.';
            saldoLine.innerHTML = saldoTxt;

            // Cliente: pago hereda moneda del proyecto (backend no la usa). Dev: puede elegir COP/USD.
            monedaEl.value = moneda;
            if (isClient) {
                monedaWrap.style.display = 'none';
                monedaEl.disabled = true;
                montoEl.parentElement.classList.remove('col-8');
                montoEl.parentElement.classList.add('col-12');
            } else {
                monedaWrap.style.display = '';
                monedaEl.disabled = false;
                montoEl.parentElement.classList.remove('col-12');
                montoEl.parentElement.classList.add('col-8');
            }

            montoEl.value = saldo > 0 ? saldo.toFixed(2) : '';
            submitBtn.className = 'btn ' + (isClient ? 'btn-success' : 'btn-primary');

            setTimeout(() => montoEl.focus(), 200);
            modal.show();
        });
    });
})();
</script>
@endpush
