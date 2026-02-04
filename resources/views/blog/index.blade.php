@extends('layouts.app')

@section('title', 'Blog - MY Tech Solutions')

@section('meta_description', 'Descubre las últimas tendencias en tecnología, desarrollo web y marketing digital. Artículos, tutoriales y casos de éxito de MY Tech Solutions.')

@section('meta_keywords', 'blog tecnología, desarrollo web, marketing digital, tutoriales programación, casos de éxito, MY Tech Solutions')

@section('custom_seo', true)

@push('meta')
    {{-- Open Graph para Blog Index --}}
    <meta property="og:title" content="Blog - MY Tech Solutions">
    <meta property="og:description" content="Descubre las últimas tendencias en tecnología, desarrollo web y marketing digital.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('blog.index') }}">
    <meta property="og:site_name" content="MY Tech Solutions">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Blog - MY Tech Solutions">
    <meta name="twitter:description" content="Descubre las últimas tendencias en tecnología, desarrollo web y marketing digital.">
@endpush

@push('styles')
<style>
    .blog-hero {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        padding: 4rem 0;
        text-align: center;
        border-bottom: 1px solid rgba(0, 123, 255, 0.1);
    }

    .blog-hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: var(--dark-text);
        margin-bottom: 1rem;
    }

    .blog-hero-title span {
        background: var(--gradient-blue);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .blog-hero-subtitle {
        font-size: 1.2rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    .blog-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 1.5rem;
    }

    .blog-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 3rem;
    }

    @media (max-width: 992px) {
        .blog-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Blog Grid */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .blog-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Blog Card */
    .blog-card {
        background: var(--white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 123, 255, 0.15);
        border-color: rgba(0, 123, 255, 0.2);
    }

    .blog-card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .blog-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .blog-card:hover .blog-card-image img {
        transform: scale(1.08);
    }

    .blog-card-image-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        opacity: 0.7;
    }

    .blog-card-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: var(--gradient-blue);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .blog-card-content {
        padding: 1.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .blog-card-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #888;
    }

    .blog-card-meta span {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .blog-card-meta i {
        color: var(--primary-blue);
    }

    .blog-card-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.75rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-card-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .blog-card-title a:hover {
        color: var(--primary-blue);
    }

    .blog-card-excerpt {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .blog-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .blog-card-author {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .author-avatar {
        width: 36px;
        height: 36px;
        background: var(--gradient-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .author-name {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 0.9rem;
    }

    .blog-card-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-blue);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .blog-card-link:hover {
        gap: 0.75rem;
        color: var(--primary-dark);
    }

    /* Sidebar */
    .blog-sidebar {
        position: sticky;
        top: 100px;
    }

    .sidebar-widget {
        background: var(--white);
        border-radius: 20px;
        padding: 1.75rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .sidebar-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--primary-blue);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-title i {
        color: var(--primary-blue);
    }

    /* Categories */
    .category-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .category-list li {
        margin-bottom: 0.5rem;
    }

    .category-list a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        color: var(--dark-text);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .category-list a:hover,
    .category-list a.active {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 123, 255, 0.15) 100%);
        color: var(--primary-blue);
        padding-left: 1.25rem;
    }

    .category-count {
        background: rgba(0, 123, 255, 0.1);
        color: var(--primary-blue);
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Tags */
    .tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tag-link {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: rgba(0, 123, 255, 0.08);
        color: var(--dark-text);
        border-radius: 20px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .tag-link:hover {
        background: var(--primary-blue);
        color: white;
        transform: translateY(-2px);
    }

    /* Newsletter */
    .newsletter-form {
        margin-top: 1rem;
    }

    .newsletter-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .newsletter-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }

    .newsletter-btn {
        width: 100%;
        padding: 0.875rem;
        background: var(--gradient-blue);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .newsletter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 123, 255, 0.3);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 4rem;
        color: #ccc;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #666;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination .page-item .page-link {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        color: var(--dark-text);
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e0e0e0;
        background: var(--white);
    }

    .pagination .page-item.active .page-link,
    .pagination .page-item .page-link:hover {
        background: var(--gradient-blue);
        color: white;
        border-color: var(--primary-blue);
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Featured Post */
    .featured-post {
        grid-column: 1 / -1;
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 0;
        border-radius: 24px;
        overflow: hidden;
        background: var(--white);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .featured-post {
            grid-template-columns: 1fr;
        }
    }

    .featured-post .blog-card-image {
        height: 100%;
        min-height: 350px;
    }

    .featured-post .blog-card-content {
        padding: 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .featured-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #ffd700 0%, #ffb800 100%);
        color: #333;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        width: fit-content;
    }

    .featured-post .blog-card-title {
        font-size: 1.8rem;
        -webkit-line-clamp: 3;
    }

    .featured-post .blog-card-excerpt {
        -webkit-line-clamp: 4;
    }

    /* Search Box */
    .search-box {
        position: relative;
        margin-bottom: 2rem;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="blog-hero">
    <div class="container">
        <h1 class="blog-hero-title">Nuestro <span>Blog</span></h1>
        <p class="blog-hero-subtitle">Descubre las últimas tendencias en tecnología, desarrollo web y marketing digital</p>
    </div>
</section>

<div class="blog-container">
    <div class="blog-layout">
        <!-- Main Content -->
        <main>
            @if($posts->count() > 0)
                <div class="blog-grid">
                    @foreach($posts as $index => $post)
                        @if($index === 0 && $posts->currentPage() === 1)
                            <!-- Featured Post (First) -->
                            <article class="featured-post">
                                <div class="blog-card-image">
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}">
                                    @else
                                        <div class="blog-card-image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="blog-card-content">
                                    <span class="featured-badge">
                                        <i class="fas fa-star"></i>
                                        Destacado
                                    </span>
                                    @if($post->category)
                                        <span class="blog-card-category" style="position: static; margin-bottom: 1rem;">
                                            {{ $post->category_name }}
                                        </span>
                                    @endif
                                    <h2 class="blog-card-title">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>
                                    <div class="blog-card-meta">
                                        <span><i class="far fa-calendar-alt"></i> {{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                                        <span><i class="far fa-clock"></i> {{ $post->reading_time ?? $post->calculateReadingTime() }} min</span>
                                    </div>
                                    <p class="blog-card-excerpt">
                                        {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 200) }}
                                    </p>
                                    <div class="blog-card-footer">
                                        <div class="blog-card-author">
                                            <div class="author-avatar">
                                                {{ strtoupper(substr($post->author ?? 'A', 0, 1)) }}
                                            </div>
                                            <span class="author-name">{{ $post->author ?? 'Admin' }}</span>
                                        </div>
                                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-link">
                                            Leer más <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @else
                            <!-- Regular Post Card -->
                            <article class="blog-card">
                                <div class="blog-card-image">
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}">
                                    @else
                                        <div class="blog-card-image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                    @if($post->category)
                                        <span class="blog-card-category">{{ $post->category_name }}</span>
                                    @endif
                                </div>
                                <div class="blog-card-content">
                                    <div class="blog-card-meta">
                                        <span><i class="far fa-calendar-alt"></i> {{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                                        <span><i class="far fa-clock"></i> {{ $post->reading_time ?? $post->calculateReadingTime() }} min</span>
                                    </div>
                                    <h2 class="blog-card-title">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>
                                    <p class="blog-card-excerpt">
                                        {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                                    </p>
                                    <div class="blog-card-footer">
                                        <div class="blog-card-author">
                                            <div class="author-avatar">
                                                {{ strtoupper(substr($post->author ?? 'A', 0, 1)) }}
                                            </div>
                                            <span class="author-name">{{ $post->author ?? 'Admin' }}</span>
                                        </div>
                                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-link">
                                            Leer <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="pagination-wrapper">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-blog"></i>
                    <h3>Próximamente</h3>
                    <p>Estamos preparando contenido increíble para ti. ¡Vuelve pronto!</p>
                </div>
            @endif
        </main>

        <!-- Sidebar -->
        <aside class="blog-sidebar">
            <!-- Search -->
            <div class="sidebar-widget">
                <h3 class="sidebar-title">
                    <i class="fas fa-search"></i>
                    Buscar
                </h3>
                <form action="{{ route('blog.index') }}" method="GET">
                    <div class="search-box" style="margin-bottom: 0;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="q" class="search-input" placeholder="Buscar artículos..." value="{{ request('q') }}">
                    </div>
                </form>
            </div>

            <!-- Categories -->
            <div class="sidebar-widget">
                <h3 class="sidebar-title">
                    <i class="fas fa-folder"></i>
                    Categorías
                </h3>
                <ul class="category-list">
                    <li>
                        <a href="{{ route('blog.index') }}" class="{{ !request('category') ? 'active' : '' }}">
                            Todas
                        </a>
                    </li>
                    @foreach($categories as $slug => $name)
                        <li>
                            <a href="{{ route('blog.category', $slug) }}" class="{{ request()->segment(3) == $slug ? 'active' : '' }}">
                                {{ $name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Popular Tags -->
            @if(isset($allTags) && $allTags->count() > 0)
                <div class="sidebar-widget">
                    <h3 class="sidebar-title">
                        <i class="fas fa-tags"></i>
                        Etiquetas Populares
                    </h3>
                    <div class="tags-cloud">
                        @foreach($allTags as $tag => $count)
                            <a href="{{ route('blog.tag', Str::slug($tag)) }}" class="tag-link">
                                {{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Newsletter -->
            <div class="sidebar-widget" style="background: var(--gradient-blue); color: white;">
                <h3 class="sidebar-title" style="color: white; border-bottom-color: rgba(255,255,255,0.3);">
                    <i class="fas fa-envelope"></i>
                    Newsletter
                </h3>
                <p style="margin-bottom: 1rem; opacity: 0.9;">Recibe las últimas novedades directamente en tu correo.</p>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="Tu email" style="border-color: rgba(255,255,255,0.3);">
                    <button type="submit" class="newsletter-btn" style="background: white; color: var(--primary-blue);">
                        Suscribirse
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection
