{{-- resources/views/admin/pages/edit.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .form-container { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .form-label { color: #FCFAF1 !important; font-weight: 500; }
    .form-control, .form-select { background: #101820 !important; border: 1px solid #00A9E0; color: #FCFAF1 !important; }
    .form-control:focus { background: #101820 !important; border-color: #00CFB4 !important; box-shadow: 0 0 0 2px rgba(0, 207, 180, 0.2) !important; color: #FCFAF1 !important; }
    .form-control::placeholder { color: rgba(252, 250, 241, 0.5); }
    .btn-success { background: #00A9E0; border-color: #00A9E0; }
    .btn-success:hover { background: #00CFB4; border-color: #00CFB4; }
    .btn-secondary { background: #6c757d; border-color: #6c757d; }
    .btn-danger { background: #dc3545; border-color: #dc3545; }
    .image-item { background: #101820; border: 1px solid rgba(0, 169, 224, 0.3); border-radius: 8px; padding: 10px; }
    h1 { color: #00A9E0 !important; }
    .text-muted { color: rgba(252, 250, 241, 0.6) !important; }
</style>

<div class="form-container">
    <h1>✏️ Editar: {{ ucfirst(str_replace('-', ' ', $page->slug)) }}</h1>
    
    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div class="mb-3">
            <label class="form-label">Título de la Página *</label>
            <input type="text" name="title" class="form-control" 
                   value="{{ old('title', $page->title) }}" required>
        </div>

        {{-- Contenido --}}
        <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea name="content" rows="10" class="form-control">{{ old('content', $page->content) }}</textarea>
            <small class="text-muted">Puedes usar HTML básico: &lt;h2&gt;, &lt;p&gt;, &lt;br&gt;, &lt;strong&gt;, etc.</small>
        </div>

        {{-- Imágenes Actuales --}}
        @if(count($page->getImagesArray()) > 0)
            <div class="mb-3">
                <label class="form-label">Imágenes Actuales</label>
                <div class="row">
                    @foreach($page->getImagesArray() as $index => $image)
                        <div class="col-md-3 col-6 mb-3" id="image-{{ $index }}">
                            <div class="image-item">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     class="img-fluid rounded mb-2" 
                                     style="height: 100px; width: 100%; object-fit: cover;">
                                <button type="button" 
                                        class="btn btn-danger btn-sm w-100"
                                        onclick="deleteImage({{ $page->id }}, {{ $index }})">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Nuevas Imágenes --}}
        <div class="mb-3">
            <label class="form-label">Agregar Imágenes</label>
            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">Selecciona una o más imágenes (JPG, PNG). Se guardan en storage/pages/</small>
        </div>

        {{-- Videos --}}
        <div class="mb-3">
            <label class="form-label">Videos (URLs)</label>
            <textarea name="video_urls" rows="5" class="form-control" 
                      placeholder="https://youtube.com/watch?v=ejemplo1
https://vimeo.com/123456789
Una URL por línea...">{{ old('video_urls', implode("\n", $page->getVideosArray())) }}</textarea>
            <small class="text-muted">Una URL por línea. YouTube, Vimeo o enlaces directos a videos.</small>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">💾 Guardar</button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
// Eliminar imagen
function deleteImage(pageId, imageIndex) {
    if (confirm('¿Eliminar esta imagen?')) {
        fetch(`/admin/pages/${pageId}/image`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({image_index: imageIndex})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`image-${imageIndex}`).remove();
            } else {
                alert('Error al eliminar');
            }
        });
    }
}
</script>
@endsection