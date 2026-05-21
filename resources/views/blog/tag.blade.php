@php
    /* /blog/tag/{tag} — Listado filtrado por tag. */
    $tagUrl = route('blog.tag', $tag);
    $totalPosts = $posts->total();
    $tagDisplay = ucfirst(str_replace('-', ' ', $tag));
    $tint = '#2563EB';

    $seoTitle = "#{$tagDisplay} — Artículos del Blog | MY Tech Solutions";
    $seoDesc  = "Artículos etiquetados con #{$tagDisplay}. {$totalPosts} publicaciones sobre desarrollo de software, SaaS y tecnología.";

    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'canonical_url'       => $tagUrl,
        /* Tag pages suelen tener mucha repetición / index bloat —
           dejamos noindex,follow para no comer crawl budget pero pasar señales */
        'robots'              => 'noindex,follow',
        'og_title'            => $seoTitle,
        'og_description'      => $seoDesc,
        'og_url'              => $tagUrl,
        'og_type'             => 'website',
        'og_image'            => asset('images/logo.png'),
        'og_site_name'        => 'MY Tech Solutions',
        'twitter_card'        => 'summary_large_image',
        'twitter_title'       => $seoTitle,
        'twitter_description' => $seoDesc,
        'twitter_image'       => asset('images/logo.png'),
        'schema_markup'       => [
            '@context'   => 'https://schema.org',
            '@type'      => 'CollectionPage',
            'name'       => $seoTitle,
            'description'=> $seoDesc,
            'url'        => $tagUrl,
            'inLanguage' => 'es',
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',   'item' => route('blog.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => "#{$tagDisplay}", 'item' => $tagUrl],
                ],
            ],
        ],
    ];
@endphp

@extends('layouts.app-home')

@section('content')

{{-- HERO tag --}}
<section class="mt-blog-hero relative pt-36 pb-20 md:pb-28 overflow-hidden bg-white" data-blog-hero style="--cat-tint: {{ $tint }};">
    <div class="mt-blog-hero-bg" aria-hidden="true"></div>
    <div class="mt-blog-hero-watermark" aria-hidden="true" data-blog-watermark>#{{ strtoupper($tagDisplay) }}</div>

    <div class="mt-container relative z-10">
        <nav class="mt-blog-breadcrumb mono" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Inicio</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('blog.index') }}">Blog</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">#{{ $tagDisplay }}</span>
        </nav>

        <span class="mt-blog-hero-eyebrow" data-animate>
            <span class="mt-blog-hero-dot" style="background: {{ $tint }};"></span>
            [ Tag · #{{ strtoupper($tagDisplay) }} ]
        </span>

        <h1 class="mt-blog-hero-title text-balance" data-animate>
            Artículos etiquetados con
            <span class="italic" style="color: {{ $tint }};">#{{ strtolower($tagDisplay) }}</span>.
        </h1>

        <p class="mt-blog-hero-lead" data-animate>
            {{ $totalPosts }} {{ $totalPosts === 1 ? 'publicación encontrada' : 'publicaciones encontradas' }} con este tag.
        </p>

        <div class="mt-blog-hero-meta mono" data-animate>
            <span class="mt-blog-hero-stat">
                <strong>{{ $totalPosts }}</strong>
                <em>{{ $totalPosts === 1 ? 'artículo' : 'artículos' }}</em>
            </span>
            <a href="{{ route('blog.index') }}" class="mt-blog-hero-stat-link">
                ← Ver todo el blog
            </a>
        </div>
    </div>
</section>

{{-- Grid de posts --}}
@if($posts->count() > 0)
    @include('partials.blog.grid', [
        'posts'      => $posts,
        'categories' => $categories,
        'allTags'    => [],
        'currentTag' => $tag,
        'hideHeader' => true,
        'skipFirst'  => false,
    ])
@else
    <section class="mt-blog-empty py-24 md:py-32 text-center">
        <div class="mt-container">
            <span class="mt-eyebrow-gray">[ Sin contenido aún ]</span>
            <h2 class="text-section font-display font-bold text-mt-text mt-3 mb-4">
                No hay artículos con
                <span class="italic" style="color: {{ $tint }};">#{{ strtolower($tagDisplay) }}</span>.
            </h2>
            <p class="text-mt-text-muted text-lg max-w-xl mx-auto mb-8">
                Prueba con otra etiqueta o explora el blog completo.
            </p>
            <a href="{{ route('blog.index') }}" class="mt-btn-primary">
                Ver todos los artículos <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>
@endif

@php
    $page = (object) [
        'content' => json_encode([
            'cta_eyebrow'          => '[ Conversemos ]',
            'cta_title_main'       => '¿Te ayudamos a construir',
            'cta_title_accent'     => 'algo así',
            'cta_subtitle'         => 'Si esto te suena al proyecto que tienes en mente, cuéntanos. Respondemos en 24h con una propuesta clara.',
            'cta_whatsapp_text'    => 'Hablemos por WhatsApp',
            'cta_form_button_text' => 'Cotizar mi proyecto',
        ], JSON_UNESCAPED_UNICODE),
    ];
@endphp
@include('partials.home.cta-intermedio')

@endsection
