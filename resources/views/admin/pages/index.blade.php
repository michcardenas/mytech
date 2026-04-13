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
        --gradient-teal: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        --gradient-purple: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        --gradient-orange: linear-gradient(135deg, #e17055 0%, #fdcb6e 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pages-container {
        background: var(--light-gray);
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.75rem 2rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header-left h1 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-header-left p {
        margin: 0;
        opacity: 0.85;
        font-size: 0.9rem;
    }

    .btn-create {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        padding: 0.7rem 1.4rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .btn-create:hover {
        background: rgba(255,255,255,0.35);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    /* Alert */
    .alert-success {
        background: var(--white);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.2);
        border-left: 4px solid #28a745;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        box-shadow: var(--shadow-soft);
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 0.45rem 1rem;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid rgba(0,0,0,0.08);
        background: var(--white);
        color: #666;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .filter-tab:hover,
    .filter-tab.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
        text-decoration: none;
    }

    .filter-tab .count {
        background: rgba(0,0,0,0.08);
        padding: 0.1rem 0.45rem;
        border-radius: 6px;
        font-size: 0.72rem;
    }

    .filter-tab:hover .count,
    .filter-tab.active .count {
        background: rgba(255,255,255,0.25);
    }

    /* Pages Grid */
    .pages-grid {
        display: grid;
        gap: 1rem;
    }

    /* Page Card */
    .page-card {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        padding: 1.5rem;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: start;
    }

    .page-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(0, 123, 255, 0.12);
    }

    .page-card-body {
        min-width: 0;
    }

    .page-card-title-row {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.35rem;
        flex-wrap: wrap;
    }

    .page-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
    }

    /* Type Badges */
    .type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .type-badge.page {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }

    .type-badge.landing {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .type-badge.blog {
        background: rgba(111, 66, 193, 0.1);
        color: #6f42c1;
    }

    /* SEO Status */
    .seo-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.15rem 0.55rem;
        border-radius: 6px;
    }

    .seo-badge.configured {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .seo-badge.pending {
        background: rgba(255, 193, 7, 0.15);
        color: #d39e00;
    }

    .page-slug {
        color: #999;
        font-size: 0.82rem;
        margin: 0.15rem 0 0.5rem 0;
        font-family: monospace;
    }

    .page-excerpt {
        color: #777;
        font-size: 0.85rem;
        line-height: 1.5;
        margin: 0 0 0.75rem 0;
    }

    .page-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.78rem;
        color: #888;
    }

    .meta-item i {
        font-size: 0.72rem;
        color: #aaa;
    }

    /* Action Buttons */
    .page-actions {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        flex-shrink: 0;
    }

    .btn-action {
        padding: 0.45rem 0.9rem;
        font-size: 0.78rem;
        border-radius: 9px;
        border: none;
        font-weight: 600;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
        justify-content: center;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-edit {
        background: rgba(255, 193, 7, 0.12);
        color: #d39e00;
    }

    .btn-edit:hover {
        background: #ffc107;
        color: #212529;
    }

    .btn-seo {
        background: rgba(23, 162, 184, 0.12);
        color: #138496;
    }

    .btn-seo:hover {
        background: #17a2b8;
        color: white;
    }

    .btn-sections {
        background: rgba(40, 167, 69, 0.12);
        color: #1e7e34;
    }

    .btn-sections:hover {
        background: #28a745;
        color: white;
    }

    .btn-view {
        background: rgba(0, 123, 255, 0.12);
        color: var(--primary-blue);
    }

    .btn-view:hover {
        background: var(--primary-blue);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
    }

    .empty-state i {
        font-size: 3.5rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: #888;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #aaa;
        margin-bottom: 1.5rem;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav > div:first-child {
        display: none;
    }

    .pagination-wrapper nav > div:last-child {
        width: 100%;
    }

    .pagination-wrapper nav > div:last-child > div:first-child {
        display: none;
    }

    .pagination-wrapper .flex.justify-between,
    .pagination-wrapper p.text-sm {
        display: none;
    }

    .pagination-wrapper span[aria-current="page"] span,
    .pagination-wrapper a[rel] {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 0.6rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        margin: 0 0.15rem;
    }

    .pagination-wrapper span[aria-current="page"] span {
        background: var(--gradient-blue);
        color: white;
        box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
    }

    .pagination-wrapper a {
        background: var(--white);
        color: var(--dark-text);
        border: 1px solid rgba(0,0,0,0.08);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 0.6rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: var(--transition);
        margin: 0 0.15rem;
    }

    .pagination-wrapper a:hover {
        background: rgba(0, 123, 255, 0.08);
        color: var(--primary-blue);
        border-color: rgba(0, 123, 255, 0.2);
        text-decoration: none;
    }

    .pagination-wrapper span[aria-disabled="true"] span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 0.6rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        background: var(--light-gray);
        color: #ccc;
        border: 1px solid rgba(0,0,0,0.04);
        margin: 0 0.15rem;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .pages-container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .page-card {
            grid-template-columns: 1fr;
        }

        .page-actions {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .filter-tabs {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .page-header-left h1 {
            font-size: 1.3rem;
        }

        .page-card-title-row {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="pages-container">
    {{-- Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <h1>
                <i class="fas fa-file-alt"></i>
                Gestion de Paginas
            </h1>
            <p><i class="fas fa-info-circle me-1"></i> Edita el contenido de las paginas de tu sitio web</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="btn-create">
            <i class="fas fa-plus-circle"></i>
            Crear Nueva Pagina
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="filter-tabs">
        <span class="filter-tab active" data-filter="all">
            <i class="fas fa-layer-group"></i> Todas
            <span class="count">{{ $pages->total() }}</span>
        </span>
        <span class="filter-tab" data-filter="page">
            <i class="fas fa-file-alt"></i> Paginas
        </span>
        <span class="filter-tab" data-filter="landing">
            <i class="fas fa-rocket"></i> Landings
        </span>
        <span class="filter-tab" data-filter="blog">
            <i class="fas fa-blog"></i> Blog
        </span>
    </div>

    {{-- Pages List --}}
    <div class="pages-grid">
        @forelse($pages as $page)
            <div class="page-card" data-type="{{ $page->type }}">
                <div class="page-card-body">
                    <div class="page-card-title-row">
                        <h3 class="page-card-title">{{ $page->title }}</h3>
                        @if($page->type === 'landing')
                            <span class="type-badge landing"><i class="fas fa-rocket"></i> Landing</span>
                        @elseif($page->type === 'blog')
                            <span class="type-badge blog"><i class="fas fa-blog"></i> Blog</span>
                        @else
                            <span class="type-badge page"><i class="fas fa-file-alt"></i> Pagina</span>
                        @endif
                        @if($page->seo)
                            <span class="seo-badge configured"><i class="fas fa-check-circle"></i> SEO</span>
                        @else
                            <span class="seo-badge pending"><i class="fas fa-exclamation-circle"></i> SEO</span>
                        @endif
                    </div>

                    <p class="page-slug">/{{ $page->slug }}</p>

                    @if($page->content)
                        @php
                            $content = $page->content;
                            if (str_starts_with(trim($content), '{') && str_ends_with(trim($content), '}')) {
                                $decoded = json_decode($content, true);
                                if ($decoded) {
                                    $displayText = '';
                                    foreach (['hero_title', 'title', 'hero_description', 'description', 'text', 'content'] as $field) {
                                        if (isset($decoded[$field]) && !empty($decoded[$field])) {
                                            $displayText .= $decoded[$field] . ' ';
                                        }
                                    }
                                    $content = trim($displayText) ?: 'Contenido en formato JSON';
                                }
                            }
                        @endphp
                        <p class="page-excerpt">{{ Str::limit(strip_tags($content), 120) }}</p>
                    @endif

                    <div class="page-meta">
                        <span class="meta-item">
                            <i class="fas fa-puzzle-piece"></i>
                            {{ $page->sections ? $page->sections->count() : 0 }} secciones
                        </span>
                        @if($page->sections && $page->sections->where('is_active', true)->count() > 0)
                            <span class="meta-item">
                                <i class="fas fa-check"></i>
                                {{ $page->sections->where('is_active', true)->count() }} activas
                            </span>
                        @endif
                        @if($page->seo && $page->seo->meta_title)
                            <span class="meta-item">
                                <i class="fas fa-search"></i>
                                Meta titulo configurado
                            </span>
                        @endif
                        @if($page->is_active)
                            <span class="meta-item" style="color: #28a745;">
                                <i class="fas fa-circle" style="font-size:0.5rem; color:#28a745;"></i>
                                Activa
                            </span>
                        @else
                            <span class="meta-item" style="color: #dc3545;">
                                <i class="fas fa-circle" style="font-size:0.5rem; color:#dc3545;"></i>
                                Borrador
                            </span>
                        @endif
                    </div>
                </div>

                <div class="page-actions">
                    {{-- Editar --}}
                    @if($page->slug === 'servicios')
                        <a href="{{ route('admin.pages.servicios.edit') }}" class="btn-action btn-edit" title="Editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @elseif($page->slug === 'proyectos')
                        <a href="{{ route('admin.pages.proyectos.edit') }}" class="btn-action btn-edit" title="Editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @elseif($page->slug === 'contacto')
                        <a href="{{ route('admin.pages.contacto.edit') }}" class="btn-action btn-edit" title="Editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @else
                        <a href="{{ route('admin.pages.edit', $page) }}" class="btn-action btn-edit" title="Editar">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    @endif

                    {{-- SEO --}}
                    <a href="{{ route('admin.seo.edit', $page) }}" class="btn-action btn-seo" title="Configurar SEO">
                        <i class="fas fa-search"></i> SEO
                    </a>

                    {{-- Secciones --}}
                    @if(($page->sections && $page->sections->count() > 0) || in_array($page->type, ['landing', 'blog']))
                        <a href="{{ route('admin.pages.sections', $page) }}" class="btn-action btn-sections" title="Secciones">
                            <i class="fas fa-puzzle-piece"></i> Secciones
                        </a>
                    @endif

                    {{-- Ver en frontend --}}
                    @if($page->type === 'landing' && $page->is_active)
                        <a href="/{{ $page->slug }}" target="_blank" class="btn-action btn-view" title="Ver pagina">
                            <i class="fas fa-external-link-alt"></i> Ver
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h3>No hay paginas en la base de datos</h3>
                <p>Las paginas se crearan automaticamente cuando visites el sitio web.</p>
                <a href="{{ route('admin.pages.create') }}" class="btn-create" style="background: var(--gradient-blue); color: white; border: none;">
                    <i class="fas fa-plus-circle"></i> Crear Primera Pagina
                </a>
            </div>
        @endforelse
    </div>

    @if($pages->hasPages())
        <div class="pagination-wrapper">
            {{ $pages->links() }}
        </div>
    @endif
</div>

<script>
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            document.querySelectorAll('.page-card').forEach(card => {
                if (filter === 'all' || card.dataset.type === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
