{{-- resources/views/admin/pages/sections.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .section-card { background: #2a3441; border: 1px solid #00A9E0; border-radius: 8px; margin-bottom: 20px; }
    .section-header { background: #1a252f; padding: 15px; border-bottom: 1px solid rgba(0, 169, 224, 0.3); }
    .section-body { padding: 20px; }
    .form-control, .form-select { background: #101820; border: 1px solid #00A9E0; color: #FCFAF1; }
    .form-control:focus, .form-select:focus { background: #101820; border-color: #f7a831; color: #FCFAF1; box-shadow: 0 0 0 0.2rem rgba(247, 168, 49, 0.25); }
    .btn-success { background-color: #00A9E0; border-color: #00A9E0; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    h2, h4 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .badge-active { background-color: #00A9E0; }
    .badge-inactive { background-color: #6c757d; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">📝 Gestionar Secciones</h2>
                <p class="text-light mb-0">Página: <strong>{{ ucfirst(str_replace('-', ' ', $page->slug)) }}</strong></p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Páginas
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Secciones -->
        @forelse($sections as $section)
        <div class="section-card">
            <div class="section-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-puzzle-piece me-2"></i>
                        {{ ucfirst($section->name) }}
                        <span class="badge {{ $section->is_active ? 'badge-active' : 'badge-inactive' }} ms-2">
                            {{ $section->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </h4>
                    <small class="text-light">Orden: {{ $section->order }}</small>
                </div>
            </div>

            <div class="section-body">
                <form action="{{ route('admin.pages.sections.update', [$page, $section]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Título -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="title" class="form-control" value="{{ $section->title }}" required>
                        </div>

                        <!-- Estado activo -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" 
                                       {{ $section->is_active ? 'checked' : '' }}>
                                <label class="form-check-label text-light">Sección activa</label>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="mb-3">
                        <label class="form-label">Contenido</label>
                        <textarea name="content" class="form-control" rows="4">{{ $section->content }}</textarea>
                    </div>

                    <!-- Imágenes actuales -->
                    @if($section->getImagesArray())
                    <div class="mb-3">
                        <label class="form-label">Imágenes actuales</label>
                        <div class="row g-2">
                            @foreach($section->getImagesArray() as $index => $image)
                            <div class="col-md-3">
                                <div class="position-relative">
                                    <img src="{{ Storage::url($image) }}" class="img-fluid rounded" style="height: 100px; object-fit: cover; width: 100%;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                            onclick="deleteImage({{ $section->id }}, {{ $index }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Nuevas imágenes -->
                    <div class="mb-3">
                        <label class="form-label">Agregar imágenes</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>

                    <!-- Videos -->
                    <div class="mb-3">
                        <label class="form-label">URLs de Videos (una por línea)</label>
                        <textarea name="video_urls" class="form-control" rows="3">{{ implode("\n", $section->getVideosArray()) }}</textarea>
                    </div>

                    <!-- Botón guardar -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
            <h4 class="text-light">No hay secciones creadas</h4>
            <p class="text-muted">Esta página aún no tiene secciones configuradas.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function deleteImage(sectionId, imageIndex) {
    if (confirm('¿Estás seguro de eliminar esta imagen?')) {
        fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/images`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ image_index: imageIndex })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar la imagen');
            }
        });
    }
}
</script>
@endsection