@extends('layouts.app')

@section('title', $post->title . ' - Blog MY Tech Solutions')

@section('meta_description', $post->excerpt ?: Str::limit(strip_tags($post->content), 160))

@section('meta_keywords', $post->tags ?: ($post->category_name ?? 'blog, tecnología'))

@section('custom_seo', true)

@push('meta')
    {{-- Canonical --}}
    <link rel="canonical" href="{{ route('blog.show', $post->slug) }}">

    {{-- Open Graph para Artículo --}}
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 200) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    <meta property="og:site_name" content="MY Tech Solutions">
    @if($post->featured_image)
        <meta property="og:image" content="{{ url(Storage::url($post->featured_image)) }}">
        <meta property="og:image:alt" content="{{ $post->title }}">
    @endif

    {{-- Article Specific Open Graph --}}
    <meta property="article:published_time" content="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author ?? 'Admin' }}">
    @if($post->category)
        <meta property="article:section" content="{{ $post->category_name }}">
    @endif
    @foreach($post->getTagsArray() as $tag)
        <meta property="article:tag" content="{{ $tag }}">
    @endforeach

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 200) }}">
    @if($post->featured_image)
        <meta name="twitter:image" content="{{ url(Storage::url($post->featured_image)) }}">
    @endif

    {{-- Schema.org Article --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $post->title }}",
        "description": "{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 200) }}",
        @if($post->featured_image)
        "image": "{{ url(Storage::url($post->featured_image)) }}",
        @endif
        "author": {
            "@type": "Person",
            "name": "{{ $post->author ?? 'Admin' }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "MY Tech Solutions",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/icon.png') }}"
            }
        },
        "datePublished": "{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ route('blog.show', $post->slug) }}"
        }
        @if($post->reading_time)
        ,"timeRequired": "PT{{ $post->reading_time }}M"
        @endif
    }
    </script>
@endpush

@push('styles')
<style>
    .article-hero {
        position: relative;
        height: 450px;
        overflow: hidden;
        display: flex;
        align-items: flex-end;
    }

    .article-hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .article-hero-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .article-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.1) 100%);
    }

    .article-hero-content {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
        padding: 3rem 1.5rem;
        color: white;
        text-align: center;
    }

    .article-category {
        display: inline-block;
        background: var(--gradient-blue);
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .article-category:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .article-title {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
        .article-title {
            font-size: 1.6rem;
        }

        .article-hero {
            height: 350px;
        }

        .article-hero-content {
            padding: 2rem 1rem;
        }

        .article-meta {
            gap: 0.75rem;
            font-size: 0.85rem;
        }

        .article-category {
            font-size: 0.75rem;
            padding: 0.4rem 1rem;
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 480px) {
        .article-title {
            font-size: 1.35rem;
        }

        .article-hero {
            height: 300px;
        }

        .article-meta {
            flex-direction: column;
            gap: 0.4rem;
        }
    }

    .article-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        font-size: 0.95rem;
        opacity: 0.9;
    }

    .article-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .article-meta-item i {
        color: var(--primary-blue);
    }

    /* Article Container */
    .article-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem 1.5rem;
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 3rem;
    }

    @media (max-width: 992px) {
        .article-container {
            grid-template-columns: 1fr;
            padding: 2rem 1rem;
            gap: 2rem;
        }

        .article-sidebar {
            position: static;
        }
    }

    /* Article Content */
    .article-content {
        background: var(--white);
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
        .article-content {
            padding: 1.5rem;
            border-radius: 12px;
        }
    }

    @media (max-width: 480px) {
        .article-content {
            padding: 1rem;
        }

        .article-body {
            font-size: 1rem;
            line-height: 1.75;
        }

        .article-body h2 {
            font-size: 1.4rem;
        }

        .article-body h3 {
            font-size: 1.2rem;
        }

        .article-body pre {
            padding: 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .article-body blockquote {
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
        }
    }

    .article-body {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #333;
    }

    .article-body h1, .article-body h2, .article-body h3, .article-body h4 {
        color: var(--dark-text);
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .article-body h2 {
        font-size: 1.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(0, 123, 255, 0.1);
    }

    .article-body h3 {
        font-size: 1.4rem;
    }

    .article-body p {
        margin-bottom: 1.5rem;
    }

    .article-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .article-body a {
        color: var(--primary-blue);
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .article-body a:hover {
        border-bottom-color: var(--primary-blue);
    }

    .article-body ul, .article-body ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .article-body li {
        margin-bottom: 0.5rem;
    }

    .article-body blockquote {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-left: 4px solid var(--primary-blue);
        padding: 1.5rem 2rem;
        margin: 2rem 0;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: #555;
    }

    .article-body pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 1.5rem;
        border-radius: 12px;
        overflow-x: auto;
        margin: 2rem 0;
    }

    .article-body code {
        background: rgba(0, 123, 255, 0.1);
        color: var(--primary-blue);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .article-body pre code {
        background: none;
        color: inherit;
        padding: 0;
    }

    /* Tags */
    .article-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    .article-tags-label {
        font-weight: 600;
        color: var(--dark-text);
        margin-right: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tag-link {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: rgba(0, 123, 255, 0.08);
        color: var(--dark-text);
        border-radius: 20px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .tag-link:hover {
        background: var(--primary-blue);
        color: white;
    }

    /* Author Box */
    .author-box {
        display: flex;
        gap: 1.5rem;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.03) 0%, rgba(0, 123, 255, 0.08) 100%);
        border-radius: 16px;
        margin-top: 2rem;
        border: 1px solid rgba(0, 123, 255, 0.1);
    }

    .author-avatar-large {
        width: 80px;
        height: 80px;
        background: var(--gradient-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2rem;
        flex-shrink: 0;
    }

    .author-info h4 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.25rem;
    }

    .author-info p {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 576px) {
        .author-box {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem;
        }

        .author-avatar-large {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
        }

        .author-info p {
            font-size: 0.9rem;
        }
    }

    /* Share Buttons */
    .share-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    .share-title {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .share-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .share-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        color: white;
    }

    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.linkedin { background: #0a66c2; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.copy { background: #6c757d; }

    .share-btn:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 480px) {
        .share-buttons {
            flex-wrap: wrap;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
    }

    /* Sidebar */
    .article-sidebar {
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
        font-size: 1.1rem;
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

    /* Table of Contents */
    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .toc-list li {
        margin-bottom: 0.5rem;
    }

    .toc-list a {
        display: block;
        padding: 0.5rem 0.75rem;
        color: #666;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        border-left: 2px solid transparent;
    }

    .toc-list a:hover {
        background: rgba(0, 123, 255, 0.05);
        color: var(--primary-blue);
        border-left-color: var(--primary-blue);
        padding-left: 1rem;
    }

    /* Related Posts */
    .related-post {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .related-post:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .related-post-image {
        width: 80px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
    }

    .related-post-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-post-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .related-post-content h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.25rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .related-post-content h4 a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .related-post-content h4 a:hover {
        color: var(--primary-blue);
    }

    .related-post-date {
        font-size: 0.8rem;
        color: #888;
    }

    /* Navigation */
    .article-navigation {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 576px) {
        .article-navigation {
            grid-template-columns: 1fr;
        }
    }

    .nav-post {
        padding: 1.25rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.03) 0%, rgba(0, 123, 255, 0.08) 100%);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 123, 255, 0.1);
    }

    .nav-post:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.15);
    }

    .nav-post-label {
        font-size: 0.8rem;
        color: var(--primary-blue);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-post-title {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 0.95rem;
        line-height: 1.4;
    }

    .nav-post.next {
        text-align: right;
    }

    .nav-post.next .nav-post-label {
        justify-content: flex-end;
    }

    /* Back to Blog */
    .back-to-blog {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-blue);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .back-to-blog:hover {
        gap: 0.75rem;
        color: var(--primary-dark);
    }

    /* CTA Box */
    .cta-box {
        background: var(--gradient-blue);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        color: white;
    }

    .cta-box h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .cta-box p {
        opacity: 0.9;
        margin-bottom: 1.25rem;
        font-size: 0.95rem;
    }

    .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: white;
        color: var(--primary-blue);
        padding: 0.875rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        color: var(--primary-blue);
    }

    @media (max-width: 480px) {
        .sidebar-widget {
            padding: 1.25rem;
            border-radius: 12px;
        }

        .cta-box h3 {
            font-size: 1.15rem;
        }

        .cta-box p {
            font-size: 0.9rem;
        }

        .cta-btn {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<header class="article-hero">
    @if($post->featured_image)
        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="article-hero-bg">
    @else
        <div class="article-hero-placeholder"></div>
    @endif
    <div class="article-hero-overlay"></div>
    <div class="article-hero-content">
        @if($post->category)
            <a href="{{ route('blog.category', $post->category) }}" class="article-category">
                {{ $post->category_name }}
            </a>
        @endif
        <h1 class="article-title">{{ $post->title }}</h1>
        <div class="article-meta">
            <span class="article-meta-item">
                <i class="far fa-user"></i>
                {{ $post->author ?? 'Admin' }}
            </span>
            <span class="article-meta-item">
                <i class="far fa-calendar-alt"></i>
                {{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}
            </span>
            <span class="article-meta-item">
                <i class="far fa-clock"></i>
                {{ $post->reading_time ?? $post->calculateReadingTime() }} min de lectura
            </span>
        </div>
    </div>
</header>

<div class="article-container">
    <!-- Main Content -->
    <article class="article-main">
        <a href="{{ route('blog.index') }}" class="back-to-blog">
            <i class="fas fa-arrow-left"></i>
            Volver al Blog
        </a>

        <div class="article-content">
            <div class="article-body">
                {!! $post->content !!}
            </div>

            <!-- Tags -->
            @if($post->tags)
                <div class="article-tags">
                    <span class="article-tags-label">
                        <i class="fas fa-tags"></i>
                        Etiquetas:
                    </span>
                    @foreach($post->getTagsArray() as $tag)
                        <a href="{{ route('blog.tag', Str::slug($tag)) }}" class="tag-link">{{ $tag }}</a>
                    @endforeach
                </div>
            @endif

            <!-- Author Box -->
            <div class="author-box">
                <div class="author-avatar-large">
                    {{ strtoupper(substr($post->author ?? 'A', 0, 1)) }}
                </div>
                <div class="author-info">
                    <h4>{{ $post->author ?? 'Admin' }}</h4>
                    <p>Apasionado por la tecnología y el desarrollo de soluciones innovadoras. Compartiendo conocimiento para ayudar a otros a crecer.</p>
                </div>
            </div>

            <!-- Share Section -->
            <div class="share-section">
                <h4 class="share-title">
                    <i class="fas fa-share-alt"></i>
                    Compartir artículo
                </h4>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn facebook" title="Compartir en Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn twitter" title="Compartir en Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="share-btn linkedin" title="Compartir en LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" class="share-btn whatsapp" title="Compartir en WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <button class="share-btn copy" onclick="copyLink()" title="Copiar enlace">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
        </div>
    </article>

    <!-- Sidebar -->
    <aside class="article-sidebar">
        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
            <div class="sidebar-widget">
                <h3 class="sidebar-title">
                    <i class="fas fa-newspaper"></i>
                    Artículos Relacionados
                </h3>
                @foreach($relatedPosts as $related)
                    <div class="related-post">
                        <div class="related-post-image">
                            @if($related->featured_image)
                                <img src="{{ Storage::url($related->featured_image) }}" alt="{{ $related->title }}">
                            @else
                                <div class="related-post-placeholder">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            @endif
                        </div>
                        <div class="related-post-content">
                            <h4><a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a></h4>
                            <span class="related-post-date">
                                {{ $related->published_at ? $related->published_at->format('d M, Y') : $related->created_at->format('d M, Y') }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- CTA Box -->
        <div class="sidebar-widget cta-box">
            <h3>¿Tienes un proyecto en mente?</h3>
            <p>Convierte tu idea en realidad con nuestro equipo de expertos.</p>
            <a href="{{ route('contacto.index') }}" class="cta-btn">
                <i class="fas fa-rocket"></i>
                Hablemos
            </a>
        </div>

        <!-- Categories -->
        <div class="sidebar-widget">
            <h3 class="sidebar-title">
                <i class="fas fa-folder"></i>
                Categorías
            </h3>
            <ul class="toc-list">
                @foreach(\App\Models\Page::$blogCategories as $slug => $name)
                    <li>
                        <a href="{{ route('blog.category', $slug) }}">{{ $name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>

@push('scripts')
<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Enlace copiado al portapapeles');
    });
}
</script>
@endpush
@endsection
