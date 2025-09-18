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

    .pages-container {
        background: var(--white);
        max-width: 1200px;
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

    .btn-primary {
        background: var(--gradient-blue);
        border: none;
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

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
        text-decoration: none;
    }

    .pages-grid {
        display: grid;
        gap: 1.5rem;
    }

    .page-card {
        background: var(--white);
        border: 2px solid rgba(0, 123, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
    }

    .page-card:hover {
        border-color: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .page-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .page-card-title {
        color: var(--dark-text);
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
    }

    .page-slug {
        color: #666;
        font-size: 0.9rem;
        margin: 0.25rem 0;
    }

    .page-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 25px;
        border: none;
        font-weight: 500;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
        color: white;
        transform: translateY(-1px);
    }

    .btn-warning {
        background: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background: #e0a800;
        color: #212529;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-success:hover {
        background: #218838;
        color: white;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
        color: white;
        transform: translateY(-1px);
    }

    .sections-info {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(0, 123, 255, 0.05);
        border-radius: 10px;
        border-left: 4px solid var(--primary-blue);
    }

    .sections-count {
        color: var(--dark-text);
        font-weight: 600;
        margin: 0;
        font-size: 0.9rem;
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

    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .pages-container {
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

        .page-card-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .page-actions {
            flex-wrap: wrap;
        }
    }
</style>

<div class="pages-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-alt"></i>
            Gestión de Páginas
        </h1>
        <div class="page-info">
            <p style="color: #666; margin: 0; font-size: 1rem;">
                <i class="fas fa-info-circle me-2"></i>
                Edita el contenido de las páginas de tu sitio web
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="pages-grid">
        @forelse($pages as $page)
            <div class="page-card">
                <div class="page-card-header">
                    <div>
                        <h3 class="page-card-title">{{ $page->title }}</h3>
                        <p class="page-slug">{{ $page->slug }}</p>
                        
                        <!-- Indicador de estado SEO -->
                        @if($page->seo)
                            <span class="seo-status seo-configured" title="SEO configurado">
                                <i class="fas fa-search"></i>
                                SEO Configurado
                            </span>
                        @else
                            <span class="seo-status seo-missing" title="SEO no configurado">
                                <i class="fas fa-exclamation-triangle"></i>
                                SEO Pendiente
                            </span>
                        @endif
                    </div>
                    
                  <div class="page-actions">
                        <!-- Botón Editar -->
                        @if($page->slug === 'servicios')
                            <a href="{{ route('admin.pages.servicios.edit') }}" class="btn-sm btn-warning" title="Editar página">
                                <i class="fas fa-edit"></i>
                                Editar
                            </a>
                        @elseif($page->slug === 'proyectos')
                            <a href="{{ route('admin.pages.proyectos.edit') }}" class="btn-sm btn-warning" title="Editar página">
                                <i class="fas fa-edit"></i>
                                Editar
                            </a>
                        @else
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn-sm btn-warning" title="Editar página">
                                <i class="fas fa-edit"></i>
                                Editar
                            </a>
                        @endif
                        
                        <!-- Botón SEO -->
                        <a href="{{ route('admin.seo.edit', $page) }}" class="btn-sm btn-info" title="Configurar SEO">
                            <i class="fas fa-search"></i>
                            SEO
                        </a>
                        
                        <!-- Botón Secciones (si existen) -->
                        @if($page->sections && $page->sections->count() > 0)
                        <a href="{{ route('admin.pages.sections', $page) }}" class="btn-sm btn-success" title="Gestionar secciones">
                            <i class="fas fa-puzzle-piece"></i>
                            Secciones
                        </a>
                        @endif
                    </div>
                </div>

                @if($page->content)
                    <div class="page-content">
                        @php
                            $content = $page->content;
                            // If content is JSON, try to extract meaningful text
                            if (str_starts_with(trim($content), '{') && str_ends_with(trim($content), '}')) {
                                $decoded = json_decode($content, true);
                                if ($decoded) {
                                    $displayText = '';
                                    // Extract common fields that might contain readable content
                                    foreach (['hero_title', 'title', 'hero_description', 'description', 'text', 'content'] as $field) {
                                        if (isset($decoded[$field]) && !empty($decoded[$field])) {
                                            $displayText .= $decoded[$field] . ' ';
                                        }
                                    }
                                    $content = trim($displayText) ?: 'Contenido en formato JSON';
                                }
                            }
                        @endphp
                        <p>{{ Str::limit(strip_tags($content), 150) }}</p>
                    </div>
                @endif

                <div class="sections-info">
                    <p class="sections-count">
                        <i class="fas fa-puzzle-piece me-1"></i>
                        {{ $page->sections ? $page->sections->count() : 0 }} sección{{ ($page->sections ? $page->sections->count() : 0) != 1 ? 'es' : '' }}
                        @if($page->sections && $page->sections->where('is_active', true)->count() > 0)
                            ({{ $page->sections->where('is_active', true)->count() }} activa{{ $page->sections->where('is_active', true)->count() != 1 ? 's' : '' }})
                        @endif
                    </p>
                    
                    <!-- Información adicional de SEO -->
                    @if($page->seo)
                        <p class="seo-info">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            <small>Meta título: {{ $page->seo->meta_title ? 'Configurado' : 'Sin configurar' }}</small>
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="page-card" style="text-align: center; padding: 3rem;">
                <i class="fas fa-file-alt" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <h3 style="color: #666; margin-bottom: 1rem;">No hay páginas en la base de datos</h3>
                <p style="color: #999; margin-bottom: 2rem;">Las páginas se crearán automáticamente cuando visites el sitio web.</p>
            </div>
        @endforelse
    </div>

    @if($pages->hasPages())
        <div class="pagination-wrapper">
            {{ $pages->links() }}
        </div>
    @endif
</div>
@endsection