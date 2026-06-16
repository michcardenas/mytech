@extends('layouts.app_admin')

@section('content')
<style>
    .pe-wrap { padding:1.5rem 1.75rem; max-width:1050px; }
    .pe-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1rem; }
    .pe-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .pe-sub { color:#64748B; font-size:.9rem; margin:0; }
    .pe-totals { display:flex; gap:.85rem; margin-bottom:1.3rem; flex-wrap:wrap; }
    .pe-total { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:.85rem 1.15rem; }
    .pe-total .n { font-size:1.4rem; font-weight:800; color:#0F172A; line-height:1; }
    .pe-total .l { font-size:.72rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; margin-top:.3rem; }
    .pe-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; }
    .pe-table { width:100%; font-size:.88rem; }
    .pe-table th { font-size:.72rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.7rem 1rem; text-align:left; }
    .pe-table td { padding:.75rem 1rem; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
    .pe-table tr:last-child td { border-bottom:none; }
    .pe-table a.name { color:#0F172A; font-weight:700; text-decoration:none; }
    .pe-table a.name:hover { color:#2563EB; }
    .pe-fuente { display:inline-flex; align-items:center; gap:.3rem; font-size:.72rem; font-weight:700; padding:.1rem .45rem; border-radius:6px; color:#fff; }
    .pe-motivo { background:#FEF2F2; color:#B91C1C; border-radius:7px; padding:.15rem .55rem; font-size:.78rem; display:inline-block; }
    .pe-select { border:1px solid #E2E8F0; border-radius:9px; padding:.5rem .8rem; font-size:.85rem; background:#fff; }
</style>

<div class="pe-wrap">
    <div class="pe-head">
        <div>
            <h1 class="pe-title">Leads perdidos</h1>
            <p class="pe-sub">Oportunidades que no se concretaron — con su motivo, para aprender y ajustar.</p>
        </div>
        @if($isAdmin)
            <form method="GET" action="{{ route('pipeline.perdidos') }}">
                <select name="comercial" class="pe-select" onchange="this.form.submit()">
                    <option value="">Todas las comerciales</option>
                    @foreach($comerciales as $c)
                        <option value="{{ $c->id }}" @selected($filtro == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>

    <div class="pe-totals">
        <div class="pe-total"><div class="n">{{ $leads->count() }}</div><div class="l">Leads perdidos</div></div>
        <div class="pe-total"><div class="n">${{ number_format((float) $valorPerdido, 0, ',', '.') }}</div><div class="l">Valor no concretado</div></div>
    </div>

    <div class="pe-card">
        <table class="pe-table">
            <thead>
                <tr>
                    <th>Lead</th>
                    <th>Fuente</th>
                    <th>Valor</th>
                    <th>Motivo</th>
                    @if($isAdmin)<th>Comercial</th>@endif
                    <th>Perdido</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                    <tr>
                        <td>
                            <a href="{{ route('pipeline.leads.show', $lead) }}" class="name">{{ $lead->nombre }}</a>
                            @if($lead->empresa)<div class="text-muted small">{{ $lead->empresa }}</div>@endif
                        </td>
                        <td><span class="pe-fuente" style="background:{{ $lead->fuente_color }}"><i class="{{ $lead->fuente_icon }}"></i> {{ $lead->fuente_label }}</span></td>
                        <td class="fw-semibold">{{ $lead->valor_formateado }}</td>
                        <td>@if($lead->motivo_perdido)<span class="pe-motivo">{{ $lead->motivo_perdido }}</span>@else<span class="text-muted small">—</span>@endif</td>
                        @if($isAdmin)<td class="text-muted">{{ $lead->user->name }}</td>@endif
                        <td class="text-muted small">{{ optional($lead->lost_at)->format('d/m/Y') ?? $lead->updated_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="{{ $isAdmin ? 6 : 5 }}" class="text-center text-muted py-4">No hay leads perdidos. 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
