@extends('layouts.app_admin')

@section('content')
<style>
    .db-wrap { padding:1.5rem 1.75rem; max-width:1200px; }
    .db-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.3rem; }
    .db-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0; }
    .db-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:.9rem; margin-bottom:1.4rem; }
    .db-stat { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1rem 1.15rem; }
    .db-stat .ic { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:.95rem; margin-bottom:.6rem; }
    .db-stat .n { font-size:1.5rem; font-weight:800; color:#0F172A; line-height:1; }
    .db-stat .l { font-size:.74rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; margin-top:.3rem; }
    .db-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; margin-bottom:1.3rem; }
    .db-card h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0 0 1rem; }
    .funnel-row { display:flex; align-items:center; gap:.8rem; margin-bottom:.6rem; }
    .funnel-label { width:110px; font-size:.83rem; font-weight:600; color:#475569; display:flex; align-items:center; gap:.4rem; }
    .funnel-bar-wrap { flex:1; background:#F1F5F9; border-radius:7px; height:26px; overflow:hidden; }
    .funnel-bar { height:100%; border-radius:7px; display:flex; align-items:center; justify-content:flex-end; padding:0 .55rem; color:#fff; font-size:.78rem; font-weight:700; min-width:28px; transition:width .4s; }
    .db-table { width:100%; font-size:.86rem; }
    .db-table th { font-size:.72rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.5rem .6rem; text-align:left; }
    .db-table td { padding:.6rem .6rem; border-bottom:1px solid #F1F5F9; }
    .db-select { border:1px solid #E2E8F0; border-radius:9px; padding:.5rem .8rem; font-size:.85rem; background:#fff; }
</style>

<div class="db-wrap">
    <div class="db-head">
        <h1 class="db-title">Dashboard comercial</h1>
        <form method="GET" action="{{ route('pipeline.dashboard') }}">
            <select name="comercial" class="db-select" onchange="this.form.submit()">
                <option value="">Todas las comerciales</option>
                @foreach($comerciales as $c)
                    <option value="{{ $c->id }}" @selected($filtroComercial == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- KPIs --}}
    <div class="db-grid">
        <div class="db-stat"><div class="ic" style="background:#2563EB"><i class="fas fa-layer-group"></i></div><div class="n">{{ $abiertos }}</div><div class="l">Leads abiertos</div></div>
        <div class="db-stat"><div class="ic" style="background:#16A34A"><i class="fas fa-trophy"></i></div><div class="n">{{ $ganados }}</div><div class="l">Ganados</div></div>
        <a href="{{ route('pipeline.perdidos', $filtroComercial ? ['comercial' => $filtroComercial] : []) }}" class="db-stat" style="display:block;color:inherit;text-decoration:none">
            <div class="ic" style="background:#DC2626"><i class="fas fa-ban"></i></div><div class="n">{{ $perdidos }}</div><div class="l">Perdidos →</div>
        </a>
        <div class="db-stat"><div class="ic" style="background:#8B5CF6"><i class="fas fa-percent"></i></div><div class="n">{{ $winRate }}%</div><div class="l">Conversión</div></div>
        <div class="db-stat"><div class="ic" style="background:#06B6D4"><i class="fas fa-sack-dollar"></i></div><div class="n">${{ number_format((float) $valorPipeline,0,',','.') }}</div><div class="l">En pipeline</div></div>
        <div class="db-stat"><div class="ic" style="background:#0F172A"><i class="fas fa-circle-check"></i></div><div class="n">${{ number_format((float) $valorCerrado,0,',','.') }}</div><div class="l">Cerrado</div></div>
        <div class="db-stat"><div class="ic" style="background:{{ $vencidos ? '#DC2626' : '#94A3B8' }}"><i class="fas fa-triangle-exclamation"></i></div><div class="n">{{ $vencidos }}</div><div class="l">Vencidos</div></div>
    </div>

    <div class="row">
        {{-- Embudo --}}
        <div class="col-lg-7">
            <div class="db-card">
                <h3>Embudo por etapa</h3>
                @php $maxEtapa = max(1, collect($porEtapa)->max()); @endphp
                @foreach($etapas as $key => $meta)
                    @php $val = $porEtapa[$key] ?? 0; $pct = round($val / $maxEtapa * 100); @endphp
                    <div class="funnel-row">
                        <div class="funnel-label"><span style="width:8px;height:8px;border-radius:50%;background:{{ $meta['color'] }};display:inline-block"></span>{{ $meta['label'] }}</div>
                        <div class="funnel-bar-wrap">
                            <div class="funnel-bar" style="width:{{ max($pct,6) }}%; background:{{ $meta['color'] }}">{{ $val }}</div>
                        </div>
                    </div>
                @endforeach
                <div class="text-muted small mt-3">
                    <i class="fas fa-file-invoice me-1"></i> {{ $propuestasEnviadas }} propuestas ·
                    <i class="fas fa-calendar-check ms-2 me-1"></i> {{ $reunionesAgendadas }} reuniones agendadas ·
                    <i class="fas fa-xmark ms-2 me-1"></i> {{ $perdidos }} perdidos
                </div>
            </div>
        </div>

        {{-- Comisiones --}}
        <div class="col-lg-5">
            <div class="db-card">
                <h3>Comisiones</h3>
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Comisión total</span><strong>${{ number_format((float) $comisionTotal,0,',','.') }}</strong></div>
                <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Pagada</span><strong class="text-success">${{ number_format((float) $comisionPagada,0,',','.') }}</strong></div>
                <div class="d-flex justify-content-between py-2"><span class="text-muted">Pendiente</span><strong class="text-danger">${{ number_format((float) $comisionPendiente,0,',','.') }}</strong></div>
                <a href="{{ route('pipeline.commissions') }}" class="btn btn-outline-primary btn-sm w-100 mt-2"><i class="fas fa-hand-holding-dollar me-1"></i> Gestionar comisiones</a>
            </div>
        </div>
    </div>

    {{-- Ranking por comercial --}}
    <div class="db-card">
        <h3>Desempeño por comercial</h3>
        <div class="table-responsive">
            <table class="db-table">
                <thead><tr><th>Comercial</th><th>Leads</th><th>Ganados</th><th>Pipeline</th><th>Cerrado</th><th>Comisión</th></tr></thead>
                <tbody>
                    @forelse($ranking as $r)
                        <tr>
                            <td class="fw-semibold">{{ $r['comercial']->name }}</td>
                            <td>{{ $r['leads'] }}</td>
                            <td><span class="badge bg-success-subtle text-success">{{ $r['ganados'] }}</span></td>
                            <td>${{ number_format((float) $r['pipeline'],0,',','.') }}</td>
                            <td>${{ number_format((float) $r['cerrado'],0,',','.') }}</td>
                            <td class="fw-semibold">${{ number_format((float) $r['comision'],0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted text-center py-3">Aún no hay comerciales con actividad.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
