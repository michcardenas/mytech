@extends('layouts.app_admin')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
    .co-wrap { padding:1.5rem 1.75rem; max-width:1100px; }
    .co-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .co-sub { color:#64748B; font-size:.9rem; margin:0 0 1.2rem; }
    .co-grid { display:grid; grid-template-columns:1.6fr 1fr; gap:1.3rem; align-items:start; }
    @media (max-width:900px){ .co-grid{ grid-template-columns:1fr; } }
    .co-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; }
    .co-card h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0 0 1rem; }
    #editor { height:240px; background:#fff; border-radius:0 0 8px 8px; }
    .ql-toolbar.ql-snow, .ql-container.ql-snow { border-color:#E2E8F0; }
    .ql-toolbar.ql-snow { border-radius:8px 8px 0 0; }
    .co-stat { display:flex; gap:.5rem; align-items:center; font-size:.85rem; margin-bottom:.4rem; }
    .co-dot { width:9px; height:9px; border-radius:50%; }
    .co-table { width:100%; font-size:.84rem; }
    .co-table th { font-size:.7rem; text-transform:uppercase; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.5rem .5rem; text-align:left; }
    .co-table td { padding:.5rem .5rem; border-bottom:1px solid #F1F5F9; }
    .co-badge { font-size:.72rem; font-weight:700; padding:.12rem .5rem; border-radius:999px; color:#fff; }
</style>

<div class="co-wrap">
    <h1 class="co-title">Correos</h1>
    <div style="display:flex;gap:.4rem;margin:.3rem 0 1rem;border-bottom:1px solid #E5E7EB">
        <a href="{{ route('pipeline.correos.index') }}" style="padding:.55rem 1rem;font-weight:600;font-size:.9rem;color:#2563EB;text-decoration:none;border-bottom:2px solid #2563EB"><i class="fas fa-paper-plane me-1"></i> Redactar</a>
        <a href="{{ route('pipeline.correos.bandeja') }}" style="padding:.55rem 1rem;font-weight:600;font-size:.9rem;color:#64748B;text-decoration:none"><i class="fas fa-inbox me-1"></i> Bandeja de entrada</a>
    </div>
    <p class="co-sub">Envía correos desde <strong>{{ $remitente }}</strong>. Se mandan en tandas de 10 por minuto para no caer en spam.</p>

    @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger py-2"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    @if($modoLog)
        <div class="alert alert-warning py-2"><i class="fas fa-triangle-exclamation me-1"></i> <strong>Modo prueba (log):</strong> los correos se registran pero <u>no se envían de verdad</u>. Para enviar real, configura el SMTP en el <code>.env</code> (<code>MAIL_MAILER=smtp</code> + credenciales del buzón).</div>
    @endif

    <div class="co-grid">
        {{-- ===== Redactar ===== --}}
        <div class="co-card">
            <h3><i class="fas fa-paper-plane text-primary"></i> Redactar correo</h3>
            <form method="POST" action="{{ route('pipeline.correos.send') }}" enctype="multipart/form-data" id="correoForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Destinatarios</label>
                    <textarea name="destinatarios" class="form-control" rows="2" required placeholder="correo1@cliente.com, correo2@cliente.com (separados por coma o salto de línea)">{{ old('destinatarios') }}</textarea>
                    <small class="text-muted">Puedes pegar varios correos separados por coma o enter.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Asunto</label>
                    <input type="text" name="asunto" class="form-control" required value="{{ old('asunto') }}" placeholder="Asunto del correo">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Mensaje</label>
                    <div id="editor">{!! old('cuerpo') !!}</div>
                    <textarea name="cuerpo" id="cuerpoInput" class="d-none"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small"><i class="fas fa-paperclip me-1"></i>Adjuntos (imágenes, PDF, etc.)</label>
                    <input type="file" name="adjuntos[]" class="form-control" multiple>
                    <small class="text-muted">Máx. 10 MB por archivo.</small>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Enviar</button>
            </form>
        </div>

        {{-- ===== Estado + enviados ===== --}}
        <div class="co-card">
            <h3><i class="fas fa-chart-simple text-primary"></i> Resumen</h3>
            <div class="co-stat"><span class="co-dot" style="background:#16A34A"></span> Enviados: <strong>{{ $stats['enviado'] }}</strong></div>
            <div class="co-stat"><span class="co-dot" style="background:#F59E0B"></span> En cola: <strong>{{ $stats['pendiente'] }}</strong></div>
            <div class="co-stat"><span class="co-dot" style="background:#DC2626"></span> Fallidos: <strong>{{ $stats['fallido'] }}</strong></div>
            <hr>
            <h3 class="mb-2"><i class="fas fa-clock-rotate-left text-primary"></i> Últimos correos</h3>
            <div class="table-responsive" style="max-height:380px;overflow-y:auto">
                <table class="co-table">
                    <thead><tr><th>Para</th><th>Asunto</th><th>Estado</th></tr></thead>
                    <tbody>
                        @forelse($enviados as $c)
                            <tr>
                                <td>{{ $c->para }}<div class="text-muted" style="font-size:.7rem">{{ $c->created_at->format('d/m H:i') }}@if($isAdmin) · {{ $c->user->name ?? '' }}@endif</div></td>
                                <td>{{ \Illuminate\Support\Str::limit($c->asunto, 24) }}</td>
                                <td><span class="co-badge" style="background:{{ $c->estado_color }}">{{ $c->estado_label }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Aún no has enviado correos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
(function () {
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Escribe tu mensaje… puedes dar formato, poner enlaces e imágenes.',
        modules: { toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ color: [] }, { background: [] }],
            ['link', 'image'],
            ['clean']
        ] }
    });
    const form = document.getElementById('correoForm');
    form.addEventListener('submit', function (e) {
        const html = quill.root.innerHTML;
        if (quill.getText().trim().length === 0) { e.preventDefault(); alert('Escribe un mensaje antes de enviar.'); return; }
        document.getElementById('cuerpoInput').value = html;
    });
})();
</script>
@endsection
