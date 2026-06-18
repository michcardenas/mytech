@extends('layouts.app_admin')

@section('content')
<style>
    .lr-wrap { padding:1.5rem 1.75rem; max-width:900px; }
    .lr-back { color:#64748B; text-decoration:none; font-size:.85rem; }
    .lr-back:hover { color:#2563EB; }
    .lr-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.3rem 1.5rem; margin-top:.7rem; }
    .lr-asunto { font-size:1.2rem; font-weight:800; color:#0F172A; margin:0 0 .5rem; }
    .lr-meta { color:#64748B; font-size:.85rem; margin-bottom:1rem; }
    .lr-meta strong { color:#0F172A; }
    .lr-adj { display:inline-flex; align-items:center; gap:.35rem; background:#F1F5F9; border-radius:8px; padding:.25rem .6rem; font-size:.8rem; color:#475569; margin:.2rem .3rem .2rem 0; }
    #emailFrame { width:100%; border:1px solid #EEF2F7; border-radius:10px; min-height:320px; background:#fff; }
</style>

<div class="lr-wrap">
    <a href="{{ route('pipeline.correos.bandeja') }}" class="lr-back"><i class="fas fa-arrow-left"></i> Volver a la bandeja</a>

    @if(session('error'))<div class="alert alert-danger py-2 mt-2">{{ session('error') }}</div>@endif

    <div class="lr-card">
        <h1 class="lr-asunto">{{ $correo->asunto }}</h1>
        <div class="lr-meta">
            De: <strong>{{ $correo->nombre }}</strong> &lt;{{ $correo->de }}&gt;
            @if($correo->fecha) · {{ $correo->fecha->format('d/m/Y H:i') }}@endif
        </div>

        @if(count($correo->adjuntos))
            <div class="mb-3">
                @foreach($correo->adjuntos as $adj)
                    <span class="lr-adj"><i class="fas fa-paperclip"></i> {{ $adj }}</span>
                @endforeach
            </div>
        @endif

        <iframe id="emailFrame" sandbox="allow-same-origin"></iframe>
    </div>

    {{-- Responder --}}
    <div class="lr-card">
        <h3 class="fw-bold mb-3" style="font-size:1rem"><i class="fas fa-reply text-primary me-1"></i> Responder a {{ $correo->de }}</h3>
        <form method="POST" action="{{ route('pipeline.correos.responder') }}">
            @csrf
            <input type="hidden" name="para" value="{{ $correo->de }}">
            <input type="hidden" name="asunto" value="{{ $correo->asunto }}">
            <textarea name="cuerpo" class="form-control mb-2" rows="5" required placeholder="Escribe tu respuesta…"></textarea>
            <button class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Enviar respuesta</button>
        </form>
    </div>
</div>

<script>
    (function () {
        const html = @json($correo->html);
        const f = document.getElementById('emailFrame');
        f.srcdoc = html;
        f.addEventListener('load', function () {
            try {
                const h = f.contentWindow.document.body.scrollHeight;
                if (h > 100) f.style.height = (h + 30) + 'px';
            } catch (e) {}
        });
    })();
</script>
@endsection
