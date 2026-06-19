@extends('layouts.app_admin')

@section('content')
<style>
    .co-wrap { padding:1.5rem 1.75rem; max-width:1150px; }
    .co-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .8rem; }
    .co-tabs { display:flex; gap:.4rem; margin-bottom:1.3rem; border-bottom:1px solid #E5E7EB; flex-wrap:wrap; }
    .co-tab { padding:.55rem 1rem; font-weight:600; font-size:.9rem; color:#64748B; text-decoration:none; border-bottom:2px solid transparent; }
    .co-tab.active { color:#2563EB; border-bottom-color:#2563EB; }
    .co-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.1rem 1.25rem; }
    .kpi-grid { display:grid; grid-template-columns:repeat(6,1fr); gap:.8rem; margin-bottom:1.3rem; }
    @media (max-width:900px){ .kpi-grid{ grid-template-columns:repeat(3,1fr);} }
    @media (max-width:560px){ .kpi-grid{ grid-template-columns:repeat(2,1fr);} }
    .kpi { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:.85rem 1rem; }
    .kpi .n { font-size:1.5rem; font-weight:800; color:#0F172A; line-height:1; }
    .kpi .l { font-size:.72rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; margin-top:.3rem; }
    .chart { display:flex; align-items:flex-end; gap:3px; height:140px; padding-top:.5rem; }
    .chart .bar { flex:1; background:#DBEAFE; border-radius:3px 3px 0 0; min-height:2px; position:relative; transition:background .12s; }
    .chart .bar:hover { background:#2563EB; }
    .chart .bar.hoy { background:#2563EB; }
    .rep-table { width:100%; font-size:.85rem; }
    .rep-table th { font-size:.7rem; text-transform:uppercase; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.55rem .5rem; text-align:left; }
    .rep-table td { padding:.5rem .5rem; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
    .rep-table tbody tr:hover { background:#F8FAFC; }
    .num { text-align:right; font-variant-numeric:tabular-nums; }
    .co-badge { font-size:.72rem; font-weight:700; padding:.12rem .5rem; border-radius:999px; color:#fff; }
    .pill { font-size:.7rem; font-weight:700; padding:.1rem .45rem; border-radius:999px; background:#EEF2F7; color:#475569; }
    .pill.admin { background:#FEF3C7; color:#92400E; }
    .pill.comercial { background:#DBEAFE; color:#1E40AF; }
</style>

<div class="co-wrap">
    <h1 class="co-title">Correos</h1>
    <div class="co-tabs">
        <a href="{{ route('pipeline.correos.index') }}" class="co-tab"><i class="fas fa-paper-plane me-1"></i> Redactar</a>
        <a href="{{ route('pipeline.correos.bandeja') }}" class="co-tab"><i class="fas fa-inbox me-1"></i> Bandeja de entrada</a>
        <a href="{{ route('pipeline.correos.reporte') }}" class="co-tab active"><i class="fas fa-chart-column me-1"></i> Reporte por comercial</a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('pipeline.correos.reporte') }}" class="d-flex gap-2 align-items-end mb-3 flex-wrap">
        <div>
            <label class="form-label small fw-semibold mb-1">Comercial</label>
            <select name="comercial" class="form-select form-select-sm" style="min-width:200px" onchange="this.form.submit()">
                <option value="">Todos</option>
                @foreach($comerciales as $c)
                    <option value="{{ $c->id }}" @selected($comercialId == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label small fw-semibold mb-1">Período</label>
            <select name="dias" class="form-select form-select-sm" onchange="this.form.submit()">
                @foreach([7=>'Últimos 7 días',14=>'Últimos 14 días',30=>'Últimos 30 días',90=>'Últimos 90 días'] as $v=>$t)
                    <option value="{{ $v }}" @selected($dias == $v)>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <noscript><button class="btn btn-sm btn-primary">Filtrar</button></noscript>
    </form>

    {{-- KPIs --}}
    <div class="kpi-grid">
        <div class="kpi"><div class="n">{{ $kpis['hoy'] }}</div><div class="l">Enviados hoy</div></div>
        <div class="kpi"><div class="n">{{ $kpis['semana'] }}</div><div class="l">Esta semana</div></div>
        <div class="kpi"><div class="n">{{ $kpis['mes'] }}</div><div class="l">Este mes</div></div>
        <div class="kpi"><div class="n">{{ $kpis['enviados'] }}</div><div class="l">Total enviados</div></div>
        <div class="kpi"><div class="n" style="color:#DC2626">{{ $kpis['fallidos'] }}</div><div class="l">Fallidos</div></div>
        <div class="kpi"><div class="n" style="color:#F59E0B">{{ $kpis['pendientes'] }}</div><div class="l">En cola</div></div>
    </div>

    {{-- Gráfica diaria --}}
    @php $maxDia = max(1, $porDia->max('n')); $hoyStr = now()->toDateString(); @endphp
    <div class="co-card mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <strong style="font-size:.95rem">Correos enviados por día</strong>
            <span class="text-muted small">Últimos {{ $dias }} días · máx {{ $maxDia }}/día</span>
        </div>
        <div class="chart">
            @foreach($porDia as $d)
                <div class="bar {{ $d->dia === $hoyStr ? 'hoy' : '' }}" style="height:{{ round($d->n / $maxDia * 100) }}%"
                     title="{{ \Carbon\Carbon::parse($d->dia)->translatedFormat('D d M') }}: {{ $d->n }} enviados"></div>
            @endforeach
        </div>
    </div>

    {{-- Tabla por comercial --}}
    <div class="co-card mb-3">
        <strong style="font-size:.95rem"><i class="fas fa-users text-primary me-1"></i> Envíos por persona</strong>
        <div class="table-responsive mt-2">
            <table class="rep-table">
                <thead>
                    <tr>
                        <th>Persona</th><th>Rol</th>
                        <th class="num">Hoy</th><th class="num">Semana</th><th class="num">Mes</th>
                        <th class="num">Total env.</th><th class="num">Fallidos</th><th>Último envío</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td><strong>{{ $u->name }}</strong><div class="text-muted" style="font-size:.72rem">{{ $u->email }}</div></td>
                            <td><span class="pill {{ $u->rol }}">{{ ucfirst($u->rol) }}</span></td>
                            <td class="num">{{ $u->hoy }}</td>
                            <td class="num">{{ $u->semana }}</td>
                            <td class="num">{{ $u->mes }}</td>
                            <td class="num"><strong>{{ $u->enviados }}</strong></td>
                            <td class="num">{{ $u->fallidos ? '⚠️ '.$u->fallidos : '0' }}</td>
                            <td class="text-muted small">{{ $u->ultimo ? \Carbon\Carbon::parse($u->ultimo)->diffForHumans() : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">Sin datos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detalle de envíos --}}
    <div class="co-card">
        <strong style="font-size:.95rem"><i class="fas fa-list text-primary me-1"></i> Detalle de envíos <span class="text-muted fw-normal">(últimos {{ $detalle->count() }})</span></strong>
        <div class="table-responsive mt-2" style="max-height:520px;overflow-y:auto">
            <table class="rep-table">
                <thead>
                    <tr><th>Fecha</th><th>Enviado por</th><th>Destinatario</th><th>Asunto</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse($detalle as $c)
                        <tr>
                            <td class="text-muted small">{{ ($c->sent_at ?? $c->created_at)->format('d/m/y H:i') }}</td>
                            <td>{{ $c->user->name ?? '—' }}</td>
                            <td>{{ $c->para }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($c->asunto, 38) }}</td>
                            <td><span class="co-badge" style="background:{{ $c->estado_color }}">{{ $c->estado_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sin envíos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
