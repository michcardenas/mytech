@extends('layouts.app_admin')

@section('content')
<style>
    .cm-wrap { padding:1.5rem 1.75rem; max-width:1150px; }
    .cm-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 1.2rem; }
    .cm-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; margin-bottom:1.3rem; }
    .cm-card h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0 0 1rem; }
    .cm-totals { display:grid; grid-template-columns:repeat(3,1fr); gap:.9rem; margin-bottom:1.3rem; }
    .cm-total { border-radius:12px; padding:1rem 1.15rem; color:#fff; }
    .cm-total .n { font-size:1.4rem; font-weight:800; line-height:1; }
    .cm-total .l { font-size:.74rem; text-transform:uppercase; letter-spacing:.03em; opacity:.85; font-weight:700; margin-top:.3rem; }
    .cm-table { width:100%; font-size:.86rem; }
    .cm-table th { font-size:.72rem; text-transform:uppercase; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.5rem .6rem; text-align:left; }
    .cm-table td { padding:.6rem .6rem; border-bottom:1px solid #F1F5F9; }
</style>

<div class="cm-wrap">
    <h1 class="cm-title">Comisiones</h1>

    @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif

    <div class="cm-totals">
        <div class="cm-total" style="background:linear-gradient(135deg,#2563EB,#1D4ED8)"><div class="n">${{ number_format((float)$totalComision,0,',','.') }}</div><div class="l">Comisión total</div></div>
        <div class="cm-total" style="background:linear-gradient(135deg,#16A34A,#15803D)"><div class="n">${{ number_format((float)$totalPagado,0,',','.') }}</div><div class="l">Pagado</div></div>
        <div class="cm-total" style="background:linear-gradient(135deg,#DC2626,#B91C1C)"><div class="n">${{ number_format((float)$totalPendiente,0,',','.') }}</div><div class="l">Pendiente</div></div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="cm-card">
                <h3><i class="fas fa-gear text-primary me-1"></i> Tasa por defecto</h3>
                <p class="text-muted small">Se aplica al convertir un lead en proyecto (editable por proyecto).</p>
                <form method="POST" action="{{ route('pipeline.commissions.update') }}">
                    @csrf @method('PUT')
                    <label class="form-label small fw-semibold">Tipo</label>
                    <select name="tipo" class="form-select form-select-sm mb-2">
                        <option value="porcentaje" @selected($setting->tipo==='porcentaje')>Porcentaje (%)</option>
                        <option value="monto" @selected($setting->tipo==='monto')>Monto fijo</option>
                    </select>
                    <label class="form-label small fw-semibold">Valor</label>
                    <input type="number" step="0.01" min="0" name="valor" class="form-control form-control-sm mb-2" value="{{ $setting->valor }}">
                    <label class="form-label small fw-semibold">Moneda (si es monto)</label>
                    <select name="moneda" class="form-select form-select-sm mb-2">
                        <option value="COP" @selected($setting->moneda==='COP')>COP</option>
                        <option value="USD" @selected($setting->moneda==='USD')>USD</option>
                    </select>
                    <label class="form-label small fw-semibold">Notas</label>
                    <textarea name="notas" class="form-control form-control-sm mb-3" rows="2">{{ $setting->notas }}</textarea>
                    <button class="btn btn-primary btn-sm w-100">Guardar tasa</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="cm-card">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h3 class="mb-0">Comisiones por proyecto cerrado</h3>
                    <form method="GET">
                        <select name="comercial" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Todas</option>
                            @foreach($comerciales as $c)<option value="{{ $c->id }}" @selected($filtroComercial==$c->id)>{{ $c->name }}</option>@endforeach
                        </select>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="cm-table">
                        <thead><tr><th>Proyecto</th><th>Comercial</th><th>Precio</th><th>Comisión</th><th>Pagado</th><th>Pendiente</th></tr></thead>
                        <tbody>
                            @forelse($proyectos as $p)
                                @php $com = (float)$p->comision_calculada; $pag = (float)$p->total_pagado_gestion; @endphp
                                <tr>
                                    <td><a href="{{ route('admin.internal-projects.show', $p) }}" class="fw-semibold text-decoration-none">{{ \Illuminate\Support\Str::limit($p->nombre, 28) }}</a></td>
                                    <td>{{ $p->comercial->name ?? '—' }}</td>
                                    <td>{{ $p->moneda==='USD'?'US$':'$' }}{{ number_format((float)$p->precio,0,',','.') }}</td>
                                    <td class="fw-semibold">${{ number_format($com,0,',','.') }}</td>
                                    <td class="text-success">${{ number_format($pag,0,',','.') }}</td>
                                    <td class="text-danger">${{ number_format(max($com-$pag,0),0,',','.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-3">Aún no hay proyectos cerrados con comisión.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mt-2 mb-0"><i class="fas fa-circle-info me-1"></i> Los pagos de comisión se registran desde cada Proyecto interno (Gestión).</p>
            </div>
        </div>
    </div>
</div>
@endsection
