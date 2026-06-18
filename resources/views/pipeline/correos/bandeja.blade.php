@extends('layouts.app_admin')

@section('content')
<style>
    .co-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .co-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .8rem; }
    .co-tabs { display:flex; gap:.4rem; margin-bottom:1.3rem; border-bottom:1px solid #E5E7EB; }
    .co-tab { padding:.55rem 1rem; font-weight:600; font-size:.9rem; color:#64748B; text-decoration:none; border-bottom:2px solid transparent; }
    .co-tab.active { color:#2563EB; border-bottom-color:#2563EB; }
    .co-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; }
    .mail-row { display:flex; align-items:center; gap:1rem; padding:.85rem 1.1rem; border-bottom:1px solid #F1F5F9; text-decoration:none; color:inherit; transition:background .12s; }
    .mail-row:hover { background:#F8FAFC; }
    .mail-row:last-child { border-bottom:none; }
    .mail-row.no-visto { background:#F0F7FF; }
    .mail-de { width:210px; font-weight:600; color:#0F172A; font-size:.88rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex-shrink:0; }
    .mail-asunto { flex:1; color:#334155; font-size:.88rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .mail-row.no-visto .mail-de, .mail-row.no-visto .mail-asunto { font-weight:700; color:#0F172A; }
    .mail-fecha { color:#94A3B8; font-size:.78rem; white-space:nowrap; flex-shrink:0; }
    .mail-dot { width:8px; height:8px; border-radius:50%; background:#2563EB; flex-shrink:0; }
    .co-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem; gap:1rem; flex-wrap:wrap; }
</style>

<div class="co-wrap">
    <h1 class="co-title">Correos</h1>
    <div class="co-tabs">
        <a href="{{ route('pipeline.correos.index') }}" class="co-tab"><i class="fas fa-paper-plane me-1"></i> Redactar</a>
        <a href="{{ route('pipeline.correos.bandeja') }}" class="co-tab active"><i class="fas fa-inbox me-1"></i> Bandeja de entrada @if($sinLeer)<span class="badge bg-primary ms-1">{{ $sinLeer }}</span>@endif</a>
    </div>

    @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif

    <div class="co-bar">
        <p class="text-muted small mb-0">
            Bandeja de <strong>{{ $remitente }}</strong> · {{ $mensajes->count() }} correos
            @if($ultimaSync)· <span title="Última sincronización">actualizado {{ \Carbon\Carbon::parse($ultimaSync)->diffForHumans() }}</span>@endif
        </p>
        <form method="POST" action="{{ route('pipeline.correos.sincronizar') }}" id="syncForm" class="mb-0">
            @csrf
            <button class="btn btn-sm btn-outline-primary" id="syncBtn"><i class="fas fa-rotate me-1"></i> Sincronizar</button>
        </form>
    </div>

    <div class="co-card">
        @forelse($mensajes as $m)
            <a href="{{ route('pipeline.correos.leer', $m->uid) }}" class="mail-row {{ $m->visto ? '' : 'no-visto' }}">
                @if(!$m->visto)<span class="mail-dot"></span>@else<span style="width:8px"></span>@endif
                <span class="mail-de">{{ $m->nombre ?: $m->de }}</span>
                <span class="mail-asunto">{{ $m->asunto }}</span>
                <span class="mail-fecha">{{ $m->fecha ? $m->fecha->format('d/m H:i') : '' }}</span>
            </a>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                Aún no hay correos sincronizados.<br>
                <span class="small">Pulsa <strong>Sincronizar</strong> para traer los correos de tu buzón.</span>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.getElementById('syncForm').addEventListener('submit', function () {
        const b = document.getElementById('syncBtn');
        b.disabled = true;
        b.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sincronizando… (puede tardar)';
    });
</script>
@endsection
