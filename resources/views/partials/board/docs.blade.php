{{-- Documentos generales del proyecto. Variables: $project, $documentos, $esAdmin --}}
@php
    $docsStoreUrl = $esAdmin
        ? route('admin.internal-projects.docs.store', $project)
        : route('portal.developer.docs.store', $project);
@endphp
<style>
    .pdocs { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.2rem; }
    .pdocs-head { display: flex; align-items: center; gap: 0.5rem; font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.4px; margin-bottom: 0.6rem; }
    .pdocs-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .pdoc { display: inline-flex; align-items: center; gap: 0.5rem; background: #f8fafc; border: 1px solid #eef2f7; border-radius: 9px; padding: 0.4rem 0.6rem; font-size: 0.78rem; }
    .pdoc a { color: #2563eb; text-decoration: none; font-weight: 600; }
    .pdoc a:hover { text-decoration: underline; }
    .pdoc .sz { font-size: 0.66rem; color: #94a3b8; }
    .pdoc .rm { border: none; background: transparent; color: #cbd5e1; cursor: pointer; }
    .pdoc .rm:hover { color: #dc2626; }
    .pdocs-add { display: flex; gap: 0.4rem; align-items: center; margin-top: 0.7rem; flex-wrap: wrap; }
    .pdocs-add input[type=text] { font-size: 0.8rem; padding: 0.4rem 0.6rem; border: 1px solid #e2e8f0; border-radius: 8px; }
    .pdocs-add input[type=file] { font-size: 0.78rem; }
    .pdocs-add button { border: none; background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; border-radius: 8px; padding: 0.42rem 0.8rem; font-weight: 700; font-size: 0.78rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.35rem; }
    .pdocs-empty { font-size: 0.8rem; color: #94a3b8; }
</style>
<div class="pdocs">
    <div class="pdocs-head"><i class="fas fa-folder-open"></i> Documentos del proyecto</div>
    <div class="pdocs-list">
        @forelse($documentos as $f)
            @php
                $docDelUrl = $esAdmin
                    ? route('admin.project-files.destroy', $f)
                    : route('portal.developer.docs.destroy', $f);
            @endphp
            <span class="pdoc">
                <a href="{{ Storage::url($f->archivo) }}" target="_blank"><i class="fas {{ $f->icono }}"></i> {{ $f->nombre }}</a>
                <span class="sz">{{ $f->tamano_formateado }}</span>
                <form action="{{ $docDelUrl }}" method="POST" onsubmit="return confirm('¿Eliminar documento?');" style="display:inline; margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="rm" title="Eliminar"><i class="fas fa-times"></i></button>
                </form>
            </span>
        @empty
            <span class="pdocs-empty">Aún no hay documentos generales.</span>
        @endforelse
    </div>
    <form class="pdocs-add" action="{{ $docsStoreUrl }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre (opcional)" maxlength="255">
        <input type="file" name="archivo" required>
        <button type="submit"><i class="fas fa-upload"></i> Subir documento</button>
    </form>
</div>
