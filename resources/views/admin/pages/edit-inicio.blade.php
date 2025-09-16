{{-- resources/views/admin/pages/edit-inicio.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .section-card { background: #2a3441; border: 1px solid #00A9E0; border-radius: 8px; margin-bottom: 25px; }
    .section-header { background: #1a252f; padding: 15px; border-bottom: 1px solid rgba(0, 169, 224, 0.3); }
    .section-body { padding: 20px; }
    .form-control, .form-select, .form-control:focus { background: #101820; border: 1px solid #00A9E0; color: #FCFAF1; }
    .form-control:focus { border-color: #f7a831; box-shadow: 0 0 0 0.2rem rgba(247, 168, 49, 0.25); }
    .btn-success { background-color: #00A9E0; border-color: #00A9E0; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    h2, h4 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .badge-hero { background-color: #f7a831; }
    .badge-featured { background-color: #00A9E0; }
    .badge-cta { background-color: #28a745; }
    .badge-categories { background-color: #6f42c1; }
    .image-preview { height: 80px; width: 80px; object-fit: cover; border-radius: 4px; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-home"></i> Editar Página de Inicio</h2>
                <p class="text-light mb-0">Gestiona el contenido de todas las secciones de la página principal</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Secciones de la página de inicio -->
        @php
            $sectionConfigs = [
                'hero' => ['name' => 'Hero / Banner Principal', 'icon' => 'fas fa-play-circle', 'badge' => 'badge-hero'],
                'featured' => ['name' => 'Productos Destacados', 'icon' => 'fas fa-star', 'badge' => 'badge-featured'], 
                'cta' => ['name' => 'Call to Action', 'icon' => 'fas fa-bullhorn', 'badge' => 'badge-cta'],
                'categories' => ['name' => 'Categorías', 'icon' => 'fas fa-th-large', 'badge' => 'badge-categories']
            ];
        @endphp

        @foreach($page->sections()->ordered()->get() as $section)
            @php $config = $sectionConfigs[$section->name] ?? ['name' => ucfirst($section->name), 'icon' => 'fas fa-puzzle-piece', 'badge' => 'bg-secondary']; @endphp
            
            <div class="section-card">
                <!-- Header de sección -->
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="{{ $config['icon'] }} me-2"></i>
                            {{ $config['name'] }}
                            <span class="badge {{ $config['badge'] }} ms-2">
                                {{ $section->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </h4>
                        <div class="text-end">
                            <small class="text-light">Orden: {{ $section->order }}</small>
                            <br>
                            <small class="text-muted">ID: {{ $section->name }}</small>
                        </div>
                    </div>
                </div>

                <!-- Body de sección -->
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Título -->
                            <div class="col-md-8 mb-3">
                                <label class="form-label"><i class="fas fa-heading me-1"></i> Título</label>
                                <input type="text" name="title" class="form-control" value="{{ $section->title }}" required>
                            </div>

                     

                        <!-- Contenido -->
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-align-left me-1"></i> Contenido / Descripción</label>
                            <textarea name="content" class="form-control" rows="3" 
                                      placeholder="Descripción o contenido de texto para esta sección...">{{ $section->content }}</textarea>
                        </div>

                        <!-- Contenido específico por sección -->
                        @if($section->name === 'hero')
                            <!-- SECCIÓN HERO: Video O Imágenes -->
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="alert" style="background-color: rgba(247, 168, 49, 0.1); border: 1px solid #f7a831; color: #FCFAF1;">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Importante:</strong> Puedes subir <strong>UN VIDEO</strong> o <strong>IMÁGENES</strong>, no ambos. 
                                        Si subes un video, las imágenes se ignorarán.
                                    </div>
                                </div>
                            </div>

                            <!-- Selector de tipo de media -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label"><i class="fas fa-film me-1"></i> Tipo de contenido visual</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="media_type" id="media_video_{{ $section->id }}" 
                                               value="video" {{ $section->getVideosArray() ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="media_video_{{ $section->id }}">
                                            <i class="fas fa-video me-1"></i> Video de Fondo
                                        </label>

                                        <input type="radio" class="btn-check" name="media_type" id="media_images_{{ $section->id }}" 
                                               value="images" {{ !$section->getVideosArray() ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info" for="media_images_{{ $section->id }}">
                                            <i class="fas fa-images me-1"></i> Imágenes de Fondo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor para Video -->
                            <div id="video_container_{{ $section->id }}" class="mb-4" style="display: {{ $section->getVideosArray() ? 'block' : 'none' }}">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="form-label">
                                            <i class="fas fa-video me-1"></i> Subir Video de Fondo
                                        </label>
                                        <input type="file" name="hero_video" class="form-control" accept="video/*">
                                        <small class="text-muted">Máximo 50MB. Formatos: MP4, WebM, MOV</small>
                                    </div>
                                    <div class="col-md-4">
                                        @if($section->getVideosArray())
                                            <label class="form-label">Video actual</label>
                                            <div class="border rounded p-2" style="border-color: #00A9E0;">
                                                <br>
                                                <small class="text-light">{{ basename($section->getVideosArray()[0]) }}</small>
                                                <br>
                                          
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Contenedor para Imágenes -->
                            <div id="images_container_{{ $section->id }}" class="mb-4" style="display: {{ !$section->getVideosArray() ? 'block' : 'none' }}">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Imágenes actuales -->
                                        @if($section->getImagesArray())
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-images me-1"></i> 
                                                Imágenes actuales ({{ count($section->getImagesArray()) }})
                                            </label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($section->getImagesArray() as $index => $image)
                                                <div class="position-relative">
                                                    <img src="{{ Storage::url($image) }}" class="image-preview">
                                                    <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                                            style="top: -5px; right: -5px; padding: 2px 6px;"
                                                            onclick="deleteImage('{{ $section->name }}', {{ $section->id }}, {{ $index }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Nuevas imágenes -->
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-upload me-1"></i> 
                                                Agregar imágenes de fondo
                                            </label>
                                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                            <small class="text-muted">Máximo 2MB por imagen. Recomendado: 1920x1080px</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @else
                            <!-- OTRAS SECCIONES: Solo texto -->
                            <div class="alert" style="background-color: rgba(108, 117, 125, 0.1); border: 1px solid #6c757d; color: #FCFAF1;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Sección de solo texto:</strong> Esta sección solo maneja contenido textual.
                            </div>
                        @endif

                        <!-- Información específica por sección -->
                        @switch($section->name)
                            @case('hero')
                                <div class="alert" style="background-color: rgba(247, 168, 49, 0.1); border: 1px solid #f7a831; color: #FCFAF1;">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Sección Hero:</strong> Esta es la primera sección que ven los visitantes. 
                                    Recomendado: Video de fondo + título llamativo + descripción breve + botón de acción.
                                </div>
                                @break
                            
                            @case('featured')
                                <div class="alert" style="background-color: rgba(0, 169, 224, 0.1); border: 1px solid #00A9E0; color: #FCFAF1;">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Productos Destacados:</strong> Se muestran automáticamente desde la base de datos. 
                                    Solo edita el título y descripción de la sección.
                                </div>
                                @break
                                
                            @case('cta')
                                <div class="alert" style="background-color: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; color: #FCFAF1;">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Call to Action:</strong> Sección para motivar la compra. 
                                    Usa un título persuasivo y descripción que invite a la acción.
                                </div>
                                @break
                                
                            @case('categories')
                                <div class="alert" style="background-color: rgba(111, 66, 193, 0.1); border: 1px solid #6f42c1; color: #FCFAF1;">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Categorías:</strong> Se muestran automáticamente desde la base de datos. 
                                    Solo edita el título y descripción de la sección.
                                </div>
                                @break
                        @endswitch

                        <!-- Botón guardar -->
                        <div class="text-end pt-3 border-top" style="border-color: rgba(0, 169, 224, 0.3) !important;">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i> Guardar Cambios de {{ $config['name'] }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        @if($page->sections->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h4 class="text-warning">No hay secciones configuradas</h4>
            <p class="text-light">Ejecuta el comando Tinker para crear las secciones de la página de inicio.</p>
            <div class="mt-3 p-3" style="background: rgba(247, 168, 49, 0.1); border-radius: 8px; border: 1px solid #f7a831;">
                <code style="color: #f7a831;">php artisan tinker</code>
                <p class="text-light mt-2 mb-0">Luego ejecuta el código para crear las 4 secciones.</p>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
// Toggle entre Video e Imágenes en Hero
document.querySelectorAll('input[name="media_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const sectionId = this.id.split('_')[2]; // Extraer ID de la sección
        const videoContainer = document.getElementById(`video_container_${sectionId}`);
        const imagesContainer = document.getElementById(`images_container_${sectionId}`);
        
        if (this.value === 'video') {
            videoContainer.style.display = 'block';
            imagesContainer.style.display = 'none';
        } else {
            videoContainer.style.display = 'none';
            imagesContainer.style.display = 'block';
        }
    });
});

// Función para eliminar imagen
function deleteImage(sectionName, sectionId, imageIndex) {
    if (confirm(`¿Estás seguro de eliminar esta imagen de la sección ${sectionName}?`)) {
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
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al conectar con el servidor');
        });
    }
}

// Función para eliminar video actual


// Auto-submit prevention
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
});

// Validación antes del submit para Hero
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const heroVideoInput = this.querySelector('input[name="hero_video"]');
        const mediaTypeRadio = this.querySelector('input[name="media_type"]:checked');
        
        if (heroVideoInput && mediaTypeRadio) {
            // Si seleccionó video pero no subió video y no hay video actual
            const currentVideoExists = this.querySelector('.btn-danger[onclick*="removeCurrentVideo"]') !== null;
            
            if (mediaTypeRadio.value === 'video' && !heroVideoInput.files.length && !currentVideoExists) {
                e.preventDefault();
                alert('Debes subir un video o cambiar a modo imágenes.');
                this.querySelector('button[type="submit"]').disabled = false;
                this.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-save me-2"></i> Guardar Cambios de Hero';
                return false;
            }
        }
    });
});
</script>
@endsection