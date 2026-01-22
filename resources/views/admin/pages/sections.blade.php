{{-- resources/views/admin/pages/sections.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --primary-dark: #0056b3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --shadow-soft: 0 10px 30px rgba(0, 123, 255, 0.1);
    }

    body, .container {
        background: #f5f7fa !important;
        color: var(--dark-text);
    }

    .sections-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-header-sections {
        background: var(--white);
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .page-title-sections {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark-text);
        margin: 0;
    }

    .page-subtitle {
        color: #666;
        margin: 0.5rem 0 0 0;
    }

    .btn-back {
        background: var(--light-gray);
        color: #666;
        border: 2px solid #e0e0e0;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: #e9ecef;
        border-color: #ccc;
        transform: translateY(-2px);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
        border-radius: 15px;
        color: #155724;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        font-weight: 600;
    }

    .section-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        margin-bottom: 2rem;
        overflow: hidden;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .section-header {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        padding: 1.5rem;
        border-bottom: 2px solid rgba(0, 123, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        color: #007BFF;
        font-size: 1.3rem;
    }

    .badge-status {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-active {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 2px solid #28a745;
    }

    .badge-inactive {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 2px solid #dc3545;
    }

    .section-order {
        background: rgba(0, 123, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        color: #007BFF;
    }

    .section-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1.125rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
        background: var(--white);
        color: var(--dark-text);
    }

    .form-control:focus {
        outline: none;
        border-color: #007BFF;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }

    .form-check-switch {
        background: rgba(0, 123, 255, 0.05);
        padding: 1rem;
        border-radius: 12px;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #007BFF;
        border-color: #007BFF;
    }

    .form-check-label {
        font-weight: 600;
        color: var(--dark-text);
        margin-left: 0.5rem;
    }

    /* Estilos para Quill Editor */
    .quill-wrapper {
        background: var(--white);
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .quill-wrapper:focus-within {
        border-color: #007BFF;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }

    .ql-toolbar {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.03) 0%, rgba(0, 123, 255, 0.06) 100%);
        border: none !important;
        border-bottom: 2px solid #e0e0e0 !important;
        padding: 1rem !important;
    }

    .ql-container {
        border: none !important;
        font-size: 1rem;
        min-height: 200px;
    }

    .ql-editor {
        min-height: 200px;
        padding: 1.25rem !important;
        color: var(--dark-text);
    }

    .ql-editor.ql-blank::before {
        color: #999;
        font-style: normal;
    }

    .btn-save {
        background: var(--gradient-blue);
        color: var(--white);
        border: none;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
    }

    .image-preview {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e0e0e0;
    }

    .image-preview img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .btn-delete-image {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .btn-delete-image:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .file-input-wrapper {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.03) 0%, rgba(0, 123, 255, 0.06) 100%);
        border: 2px dashed #007BFF;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .file-input-wrapper:hover {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.06) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-color: #0056b3;
    }

    .file-input-wrapper input[type="file"] {
        display: none;
    }

    .file-input-label {
        color: #007BFF;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
    }

    .empty-icon {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #666;
        font-size: 1.1rem;
    }
</style>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="sections-container">
    <!-- Header -->
    <div class="page-header-sections">
        <div>
            <h1 class="page-title-sections">
                <i class="fas fa-puzzle-piece"></i>
                Gestionar Secciones
            </h1>
            <p class="page-subtitle">
                Página: <strong>{{ $page->title }}</strong>
                @if($page->type === 'landing')
                    <span class="badge-status badge-active" style="margin-left: 0.5rem;">
                        <i class="fas fa-rocket"></i> Landing
                    </span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Secciones -->
    @forelse($sections as $section)
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-layer-group section-icon"></i>
                {{ ucfirst($section->name) }}
                <span class="badge-status {{ $section->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $section->is_active ? 'Activa' : 'Inactiva' }}
                </span>
            </h2>
            <div class="section-order">
                Orden: {{ $section->order }}
            </div>
        </div>

        <div class="section-body">
            <form action="{{ route('admin.pages.sections.update', [$page, $section]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Campo oculto para el nombre (no editable) -->
                <input type="hidden" name="name" value="{{ $section->name }}">
                <input type="hidden" name="order" value="{{ $section->order }}">

                <div class="row">
                    <!-- Título -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-heading"></i>
                                Título
                            </label>
                            <input type="text" name="title" class="form-control" value="{{ $section->title }}" required>
                        </div>
                    </div>

                    <!-- Estado activo -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i>
                                Estado
                            </label>
                            <div class="form-check-switch">
                                <!-- Hidden input para asegurar que siempre se envíe un valor -->
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="active-{{ $section->id }}" value="1"
                                       {{ $section->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="active-{{ $section->id }}">
                                    Sección activa
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido con Quill -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Contenido
                    </label>
                    <div class="quill-wrapper">
                        <div id="editor-{{ $section->id }}" class="quill-editor">{!! $section->content !!}</div>
                        <input type="hidden" name="content" id="content-{{ $section->id }}">
                    </div>
                </div>

                <!-- Imágenes actuales -->
                @if($section->getImagesArray())
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-images"></i>
                        Imágenes actuales
                    </label>
                    <div class="row g-3">
                        @foreach($section->getImagesArray() as $index => $image)
                        <div class="col-md-3">
                            <div class="image-preview">
                                <img src="{{ Storage::url($image) }}" alt="Image {{ $index }}">
                                <button type="button" class="btn-delete-image"
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
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-upload"></i>
                        Agregar imágenes
                    </label>
                    <div class="file-input-wrapper" onclick="document.getElementById('images-{{ $section->id }}').click()">
                        <input type="file" name="images[]" id="images-{{ $section->id }}" multiple accept="image/*">
                        <label class="file-input-label" for="images-{{ $section->id }}">
                            <i class="fas fa-cloud-upload-alt fa-2x"></i>
                            <span id="file-label-{{ $section->id }}">Haz clic para seleccionar imágenes</span>
                            <small style="color: #666;">o arrastra archivos aquí (máx. 10MB por imagen)</small>
                        </label>
                    </div>
                    <div id="file-preview-{{ $section->id }}" class="mt-2" style="display: none;">
                        <small class="text-success">
                            <i class="fas fa-check-circle"></i>
                            <span id="file-count-{{ $section->id }}"></span>
                        </small>
                    </div>
                </div>

                <!-- Videos -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-video"></i>
                        URLs de Videos (una por línea)
                    </label>
                    <textarea name="video_urls" class="form-control" rows="3" placeholder="https://youtube.com/watch?v=...">{{ implode("\n", $section->getVideosArray()) }}</textarea>
                </div>

                <!-- Botón guardar -->
                <div class="text-end">
                    <button type="submit" class="btn-save" id="submit-{{ $section->id }}">
                        <i class="fas fa-save"></i>
                        Guardar Cambios
                    </button>
                </div>

                <!-- Debug: Mostrar errores de validación -->
                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-puzzle-piece"></i>
        </div>
        <h3 class="empty-title">No hay secciones creadas</h3>
        <p class="empty-text">Esta página aún no tiene secciones configuradas.</p>
    </div>
    @endforelse
</div>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
// Inicializar Quill para cada sección
document.addEventListener('DOMContentLoaded', function() {
    @foreach($sections as $section)
    const quill{{ $section->id }} = new Quill('#editor-{{ $section->id }}', {
        theme: 'snow',
        placeholder: 'Escribe el contenido de la sección aquí...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Sincronizar contenido con input hidden al enviar
    const form{{ $section->id }} = document.querySelector('#editor-{{ $section->id }}').closest('form');
    form{{ $section->id }}.addEventListener('submit', function(e) {
        const content = quill{{ $section->id }}.root.innerHTML;
        document.getElementById('content-{{ $section->id }}').value = content;
        console.log('Guardando contenido sección {{ $section->id }}:', content);
    });
    @endforeach
});

// Eliminar imagen
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

// Preview de archivos seleccionados
@foreach($sections as $section)
document.getElementById('images-{{ $section->id }}').addEventListener('change', function(e) {
    const files = e.target.files;
    const fileLabel = document.getElementById('file-label-{{ $section->id }}');
    const filePreview = document.getElementById('file-preview-{{ $section->id }}');
    const fileCount = document.getElementById('file-count-{{ $section->id }}');

    if (files.length > 0) {
        const fileNames = Array.from(files).map(f => f.name).join(', ');
        fileLabel.textContent = files.length + ' archivo(s) seleccionado(s)';
        fileCount.textContent = fileNames;
        filePreview.style.display = 'block';
        console.log('Archivos seleccionados:', fileNames);
    } else {
        fileLabel.textContent = 'Haz clic para seleccionar imágenes';
        filePreview.style.display = 'none';
    }
});
@endforeach
</script>
@endsection
