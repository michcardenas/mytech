@php
    /* /blog/categoria/{category} — Listado filtrado por categoría.
       SEO dinámico basado en la categoría. */
    $catUrl = route('blog.category', $category);
    $totalPosts = $posts->total();

    // Colores por categoría (mismo mapping que en /blog)
    $catTints = [
        'tecnologia'   => '#2563EB',
        'desarrollo'   => '#10B981',
        'diseno'       => '#EC4899',
        'marketing'    => '#F59E0B',
        'negocios'     => '#8B5CF6',
        'tutoriales'   => '#0F766E',
        'noticias'     => '#EF4444',
        'casos-exito'  => '#2563EB',
    ];
    $tint = $catTints[$category] ?? '#2563EB';

    $seoTitle = "{$categoryName} — Artículos del Blog | MY Tech Solutions";
    $seoDesc  = "Artículos sobre {$categoryName}. {$totalPosts} guías técnicas, casos y análisis sobre desarrollo de software, SaaS, IA y SEO en LATAM.";

    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'canonical_url'       => $catUrl,
        'robots'              => $totalPosts > 0 ? 'index,follow' : 'noindex,follow',
        'og_title'            => $seoTitle,
        'og_description'      => $seoDesc,
        'og_url'              => $catUrl,
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
            'url'        => $catUrl,
            'inLanguage' => 'es',
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio',       'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',         'item' => route('blog.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $categoryName,  'item' => $catUrl],
                ],
            ],
            'mainEntity' => [
                '@type'           => 'ItemList',
                'numberOfItems'   => $totalPosts,
                'itemListElement' => $posts->take(10)->map(fn($p, $i) => [
                    '@type'    => 'ListItem',
                    'position' => $i + 1,
                    'url'      => route('blog.show', $p->slug),
                    'name'     => $p->title,
                ])->values()->toArray(),
            ],
        ],
    ];
@endphp

@extends('layouts.app-home')

@section('content')

{{-- ════════════════ HERO categoría ════════════════ --}}
<section class="mt-blog-hero relative pt-36 pb-20 md:pb-28 overflow-hidden bg-white" data-blog-hero style="--cat-tint: {{ $tint }};">
    <div class="mt-blog-hero-bg" aria-hidden="true"></div>
    <div class="mt-blog-hero-watermark" aria-hidden="true" data-blog-watermark>{{ strtoupper($categoryName) }}</div>

    <div class="mt-container relative z-10">
        <nav class="mt-blog-breadcrumb mono" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Inicio</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('blog.index') }}">Blog</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $categoryName }}</span>
        </nav>

        <span class="mt-blog-hero-eyebrow" data-animate>
            <span class="mt-blog-hero-dot" style="background: {{ $tint }};"></span>
            [ Categoría · {{ strtoupper($categoryName) }} ]
        </span>

        <h1 class="mt-blog-hero-title text-balance" data-animate>
            Artículos sobre
            <span class="italic" style="color: {{ $tint }};">{{ strtolower($categoryName) }}</span>.
        </h1>

        <p class="mt-blog-hero-lead" data-animate>
            {{ $totalPosts }} {{ $totalPosts === 1 ? 'artículo' : 'artículos' }} en esta categoría — guías técnicas, casos reales y análisis sobre {{ strtolower($categoryName) }} en el contexto del desarrollo de software a medida.
        </p>

        <div class="mt-blog-hero-meta mono" data-animate>
            <span class="mt-blog-hero-stat">
                <strong>{{ $totalPosts }}</strong>
                <em>{{ $totalPosts === 1 ? 'artículo' : 'artículos' }}</em>
            </span>
            <span class="mt-blog-hero-stat">
                <strong>{{ count($categories) }}</strong>
                <em>categorías totales</em>
            </span>
            <a href="{{ route('blog.index') }}" class="mt-blog-hero-stat-link">
                ← Ver todo el blog
            </a>
        </div>
    </div>
</section>

{{-- ════════════════ Grid de posts ════════════════ --}}
@if($posts->count() > 0)
    @include('partials.blog.grid', [
        'posts'           => $posts,
        'categories'      => $categories,
        'allTags'         => [],
        'currentCategory' => $category,
        'hideHeader'      => true,  // hero ya está arriba, no duplicar
        'skipFirst'       => false, // no hay featured post en /categoria, mostrar todos
    ])
@else
    <section class="mt-blog-empty py-24 md:py-32 text-center">
        <div class="mt-container">
            <span class="mt-eyebrow-gray">[ Sin contenido aún ]</span>
            <h2 class="text-section font-display font-bold text-mt-text mt-3 mb-4">
                Todavía no publicamos en
                <span class="italic" style="color: {{ $tint }};">{{ strtolower($categoryName) }}</span>.
            </h2>
            <p class="text-mt-text-muted text-lg max-w-xl mx-auto mb-8">
                Estamos preparando contenido sobre esta categoría. Mientras tanto, explora el resto del blog.
            </p>
            <a href="{{ route('blog.index') }}" class="mt-btn-primary">
                Ver todos los artículos <span aria-hidden="true">→</span>
            </a>
        </div>
    </section>
@endif

{{-- ════════════════ CTA dark final ════════════════ --}}
@php
    $page = (object) [
        'content' => json_encode([
            'cta_eyebrow'          => '[ Conversemos ]',
            'cta_title_main'       => '¿Te ayudamos a construir',
            'cta_title_accent'     => 'algo así',
            'cta_subtitle'         => 'Si lo que lees aquí te suena al proyecto que tienes en mente, cuéntanos. Respondemos en 24h con una propuesta clara.',
            'cta_whatsapp_text'    => 'Hablemos por WhatsApp',
            'cta_form_button_text' => 'Cotizar mi proyecto',
        ], JSON_UNESCAPED_UNICODE),
    ];
@endphp
@include('partials.home.cta-intermedio')

@endsection
