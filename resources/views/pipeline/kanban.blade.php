@extends('layouts.app_admin')

@section('content')
<style>
    .pl-wrap { padding: 1.5rem 1.75rem; }
    .pl-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem; }
    .pl-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0; }
    .pl-sub { color:#64748B; font-size:.9rem; margin:.15rem 0 0; }

    .pl-metrics { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:.85rem; margin-bottom:1.4rem; }
    .pl-metric { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:.9rem 1.1rem; }
    .pl-metric .n { font-size:1.5rem; font-weight:800; color:#0F172A; line-height:1; }
    .pl-metric .l { font-size:.74rem; text-transform:uppercase; letter-spacing:.04em; color:#94A3B8; font-weight:700; margin-top:.35rem; }
    .pl-metric.alert .n { color:#DC2626; }

    .pl-board { display:flex; gap:1rem; overflow-x:auto; padding-bottom:1rem; align-items:flex-start; }
    .pl-col { flex:0 0 290px; background:#F1F5F9; border-radius:14px; display:flex; flex-direction:column; max-height:calc(100vh - 320px); }
    .pl-col-head { display:flex; align-items:center; gap:.5rem; padding:.85rem 1rem; font-weight:700; color:#0F172A; font-size:.9rem; position:sticky; top:0; }
    .pl-col-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; }
    .pl-col-count { margin-left:auto; background:#fff; color:#475569; font-size:.75rem; font-weight:700; padding:.05rem .5rem; border-radius:999px; border:1px solid #E2E8F0; }
    .pl-cards { padding:.25rem .6rem 1rem; overflow-y:auto; flex:1; min-height:40px; }

    .pl-card { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:.8rem .85rem; margin-bottom:.6rem; box-shadow:0 1px 2px rgba(16,24,40,.04); cursor:grab; transition:box-shadow .15s, border-color .15s; border-left:3px solid var(--c,#CBD5E1); }
    .pl-card:hover { box-shadow:0 6px 18px rgba(37,99,235,.10); border-color:#DBEAFE; }
    .pl-card.is-vencido { border-left-color:#DC2626; }
    .pl-card-top { display:flex; align-items:center; gap:.4rem; margin-bottom:.4rem; }
    .pl-fuente { display:inline-flex; align-items:center; gap:.3rem; font-size:.7rem; font-weight:700; padding:.12rem .45rem; border-radius:6px; color:#fff; }
    .pl-card-name { font-weight:700; color:#0F172A; font-size:.92rem; text-decoration:none; display:block; line-height:1.25; }
    .pl-card-name:hover { color:#2563EB; }
    .pl-card-empresa { font-size:.78rem; color:#94A3B8; margin-top:.1rem; }
    .pl-card-meta { display:flex; align-items:center; justify-content:space-between; margin-top:.6rem; font-size:.76rem; }
    .pl-card-valor { font-weight:700; color:#16A34A; }
    .pl-card-dias { color:#94A3B8; }
    .pl-next { margin-top:.55rem; font-size:.74rem; display:flex; align-items:center; gap:.35rem; color:#64748B; background:#F8FAFC; border-radius:7px; padding:.3rem .45rem; }
    .pl-next.vencido { color:#B91C1C; background:#FEF2F2; }
    .pl-next.hoy { color:#B45309; background:#FFFBEB; }

    .pl-empty { text-align:center; color:#CBD5E1; font-size:.8rem; padding:1.5rem 0; }
    .sortable-ghost { opacity:.4; }
    .sortable-drag { transform:rotate(2deg); }

    .pl-btn { display:inline-flex; align-items:center; gap:.45rem; background:#2563EB; color:#fff; border:none; padding:.6rem 1.05rem; border-radius:10px; font-weight:600; font-size:.88rem; text-decoration:none; cursor:pointer; transition:background .2s, transform .2s; }
    .pl-btn:hover { background:#1D4ED8; color:#fff; transform:translateY(-1px); }
    .pl-select { border:1px solid #E2E8F0; border-radius:9px; padding:.5rem .8rem; font-size:.85rem; color:#334155; background:#fff; }
</style>

<div class="pl-wrap">
    <div class="pl-head">
        <div>
            <h1 class="pl-title">Pipeline comercial</h1>
            <p class="pl-sub">Arrastra las tarjetas para mover cada lead de etapa.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if($isAdmin)
                <form method="GET" action="{{ route('pipeline.index') }}">
                    <select name="comercial" class="pl-select" onchange="this.form.submit()">
                        <option value="">Todas las comerciales</option>
                        @foreach($comerciales as $c)
                            <option value="{{ $c->id }}" @selected($filtroComercial == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
            <a href="{{ route('pipeline.perdidos') }}" class="pl-select text-decoration-none" style="color:#B91C1C;display:inline-flex;align-items:center;gap:.4rem">
                <i class="fas fa-ban"></i> Perdidos @if($perdidosCount)<span style="background:#FEE2E2;border-radius:999px;padding:0 .45rem;font-weight:700">{{ $perdidosCount }}</span>@endif
            </a>
            <button type="button" class="pl-btn" data-bs-toggle="modal" data-bs-target="#nuevoLeadModal">
                <i class="fas fa-plus"></i> Nuevo lead
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif

    <div class="pl-metrics">
        <div class="pl-metric"><div class="n">{{ $totalAbiertos }}</div><div class="l">Leads abiertos</div></div>
        <div class="pl-metric"><div class="n">${{ number_format((float) $valorPipeline, 0, ',', '.') }}</div><div class="l">Valor en pipeline</div></div>
        <div class="pl-metric {{ $vencidos ? 'alert' : '' }}"><div class="n">{{ $vencidos }}</div><div class="l">Seguimientos vencidos</div></div>
        <div class="pl-metric"><div class="n">{{ $reunionesProximas }}</div><div class="l">Reuniones próximas</div></div>
    </div>

    <div class="pl-board">
        @foreach($etapas as $key => $meta)
            @php $cards = $leadsPorEtapa[$key] ?? collect(); @endphp
            <div class="pl-col">
                <div class="pl-col-head">
                    <span class="pl-col-dot" style="background:{{ $meta['color'] }}"></span>
                    {{ $meta['label'] }}
                    <span class="pl-col-count">{{ $cards->count() }}</span>
                </div>
                <div class="pl-cards" data-etapa="{{ $key }}">
                    @forelse($cards as $lead)
                        <div class="pl-card {{ $lead->esta_vencido ? 'is-vencido' : '' }}" data-id="{{ $lead->id }}" style="--c:{{ $meta['color'] }}">
                            <div class="pl-card-top">
                                <span class="pl-fuente" style="background:{{ $lead->fuente_color }}">
                                    <i class="{{ $lead->fuente_icon }}"></i> {{ $lead->fuente_label }}
                                </span>
                            </div>
                            <a href="{{ route('pipeline.leads.show', $lead) }}" class="pl-card-name">{{ $lead->nombre }}</a>
                            @if($lead->empresa)<div class="pl-card-empresa">{{ $lead->empresa }}</div>@endif
                            <div class="pl-card-meta">
                                <span class="pl-card-valor">{{ $lead->valor_formateado }}</span>
                                <span class="pl-card-dias"><i class="far fa-clock"></i> {{ $lead->dias_en_etapa }}d</span>
                            </div>
                            @if($lead->proxima_accion_at)
                                <div class="pl-next {{ $lead->esta_vencido ? 'vencido' : ($lead->es_hoy ? 'hoy' : '') }}">
                                    <i class="fas fa-bell"></i>
                                    {{ $lead->proxima_accion_at->format('d/m') }} ·
                                    {{ \Illuminate\Support\Str::limit($lead->proxima_accion_nota ?: 'Seguimiento', 22) }}
                                </div>
                            @endif
                            @if($isAdmin)
                                <div class="pl-card-empresa mt-1"><i class="fas fa-user-tie"></i> {{ $lead->user->name }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="pl-empty">Sin leads</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('pipeline.partials.nuevo-lead-modal')

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const stageUrl = "{{ url('pipeline/leads') }}";

    document.querySelectorAll('.pl-cards').forEach(function (col) {
        new Sortable(col, {
            group: 'pipeline',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function (evt) {
                const card  = evt.item;
                const id    = card.getAttribute('data-id');
                const dest  = evt.to;
                const etapa = dest.getAttribute('data-etapa');
                const ids   = Array.from(dest.querySelectorAll('.pl-card')).map(c => c.getAttribute('data-id'));

                // Actualizar contadores de columnas
                document.querySelectorAll('.pl-col').forEach(function (colEl) {
                    const cards = colEl.querySelector('.pl-cards');
                    const count = colEl.querySelector('.pl-col-count');
                    if (cards && count) count.textContent = cards.querySelectorAll('.pl-card').length;
                });

                fetch(stageUrl + '/' + id + '/stage', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ etapa: etapa, ids: ids })
                }).then(r => {
                    if (!r.ok) throw new Error('stage');
                    return r.json();
                }).then(data => {
                    if (data.estado === 'ganado') {
                        // refrescar para reflejar conversión disponible
                    }
                }).catch(() => {
                    showToast('No se pudo mover el lead. Recarga la página.', 'error');
                });
            }
        });
    });
})();
</script>
@endsection
