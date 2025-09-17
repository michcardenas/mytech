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
        --shadow-hover: 0 20px 40px rgba(0, 123, 255, 0.15);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .edit-container {
        background: var(--white);
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-radius: 15px;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .page-title {
        color: var(--dark-text);
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .form-container {
        background: var(--white);
        border: 2px solid rgba(0, 123, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: var(--shadow-soft);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: var(--dark-text);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 1rem;
        transition: var(--transition);
        background: var(--white);
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .form-control-lg {
        min-height: 150px;
        resize: vertical;
    }

    .btn-primary {
        background: var(--gradient-blue);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        border: none;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .page-info {
        background: rgba(0, 123, 255, 0.05);
        border: 2px solid rgba(0, 123, 255, 0.1);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .page-info h4 {
        color: var(--dark-text);
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .page-info p {
        color: #666;
        margin: 0;
        line-height: 1.6;
    }

    .sections-info {
        background: rgba(40, 167, 69, 0.05);
        border: 2px solid rgba(40, 167, 69, 0.1);
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .sections-info h4 {
        color: #28a745;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-success {
        background: #28a745;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-success:hover {
        background: #218838;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .form-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="edit-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Editar: {{ $page->title }}
        </h1>
        <a href="{{ route('admin.pages.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="page-info">
        <h4>
            <i class="fas fa-info-circle"></i>
            Información de la Página
        </h4>
        <p><strong>Slug:</strong> {{ $page->slug }}</p>
        <p><strong>Última actualización:</strong> {{ $page->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-container">
            <div class="form-group">
                <label for="title" class="form-label">
                    <i class="fas fa-heading me-1"></i>
                    Título de la Página
                </label>
                <input type="text" class="form-control" id="title" name="title"
                       value="{{ old('title', $page->title) }}" required>
                @error('title')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">
                    <i class="fas fa-link me-1"></i>
                    URL (Slug)
                </label>
                <input type="text" class="form-control" id="slug" name="slug"
                       value="{{ old('slug', $page->slug) }}" required>
                <small class="form-text">La URL amigable de la página (ej: servicios, contacto)</small>
                @error('slug')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="content" class="form-label">
                    <i class="fas fa-file-text me-1"></i>
                    Contenido Principal
                </label>
                <textarea class="form-control form-control-lg" id="content" name="content"
                          placeholder="Contenido principal de la página">{{ old('content', $page->content) }}</textarea>
                <small class="form-text">Contenido en texto plano o HTML básico</small>
                @error('content')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>

    @if($page->sections->count() > 0)
    <div class="sections-info">
        <h4>
            <i class="fas fa-puzzle-piece"></i>
            Secciones de la Página
        </h4>
        <p>Esta página tiene {{ $page->sections->count() }} sección{{ $page->sections->count() != 1 ? 'es' : '' }}. Puedes gestionarlas desde el área de secciones.</p>
        <a href="{{ route('admin.pages.sections', $page) }}" class="btn-success">
            <i class="fas fa-cogs"></i>
            Gestionar Secciones
        </a>
    </div>
    @endif
</div>
@endsection