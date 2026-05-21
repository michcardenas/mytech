@php
    /* /blog/categoria/{category} — Listado filtrado por categoría.
       SEO dinámico basado en la categoría. */
    $catUrl     = route('blog.category', $category);
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
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',   'item' => route('blog.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $categoryName, 'item' => $catUrl],
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

{{-- ════════════════ HERO categoría (mismo pattern que /blog index) ════════════════ --}}
<section class="mt-blog-hero relative pt-36 pb-20 md:pb-28 overflow-hidden bg-white"
         data-blog-hero
         style="--cat-tint: {{ $tint }};">

    {{-- Watermark gigante con nombre de categoría --}}
    <div class="mt-blog-hero-watermark" aria-hidden="true" data-blog-watermark>
        <span>{{ strtoupper($categoryName) }}</span>
    </div>

    <div class="mt-container relative z-10">
        <div class="max-w-5xl">

            {{-- Breadcrumb minimal --}}
            <nav class="mb-6 font-mono text-[12px] uppercase tracking-[0.16em] text-mt-text-3 flex items-center gap-2 flex-wrap"
                 aria-label="Breadcrumb"
                 data-animate>
                <a href="{{ url('/') }}" class="hover:text-mt-accent transition-colors">Inicio</a>
                <span aria-hidden="true" class="opacity-40">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-mt-accent transition-colors">Blog</a>
                <span aria-hidden="true" class="opacity-40">/</span>
                <span class="text-mt-text" aria-current="page">{{ $categoryName }}</span>
            </nav>

            {{-- Eyebrow tipo badge con dot del color de la categoría --}}
            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border bg-white font-mono text-[11px] uppercase tracking-[0.18em]"
                      style="border-color: {{ $tint }}33; color: {{ $tint }}; background: {{ $tint }}0d;">
                    <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $tint }};"></span>
                    Categoría · {{ strtoupper($categoryName) }}
                </span>
            </div>

            {{-- Title gigante con accent color de la categoría --}}
            <h1 class="mt-7 font-display font-bold text-mt-text leading-[0.95] tracking-tight text-balance
                       text-[clamp(2.5rem,7vw,6rem)]"
                data-blog-hero-title>
                <span class="inline-block">Artículos sobre</span>
                <span class="inline-block italic font-display"
                      style="color: {{ $tint }};">{{ strtolower($categoryName) }}</span>.
            </h1>

            {{-- Description --}}
            <p class="mt-8 max-w-2xl text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                @if($totalPosts > 0)
                    {{ $totalPosts === 1 ? '1 publicación en esta categoría' : $totalPosts . ' publicaciones en esta categoría' }}
                    — guías técnicas, casos reales y análisis sobre {{ strtolower($categoryName) }} aplicado al desarrollo de software a medida.
                @else
                    Estamos preparando contenido sobre {{ strtolower($categoryName) }}. Mientras tanto, explora el resto del blog.
                @endif
            </p>

            {{-- Meta inline editorial con counts y back link --}}
            <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-3 font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3"
                 data-animate>
                <span class="inline-flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full" style="background: {{ $tint }};"></span>
                    <strong class="text-mt-text font-display text-[14px] font-semibold">{{ $totalPosts }}</strong>
                    {{ $totalPosts === 1 ? 'artículo' : 'artículos' }}
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full" style="background: {{ $tint }};"></span>
                    <strong class="text-mt-text font-display text-[14px] font-semibold">{{ count($categories) }}</strong>
                    categorías totales
                </span>
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-1.5 text-mt-accent hover:text-mt-text transition-colors normal-case tracking-normal font-sans text-[13px]">
                    <span aria-hidden="true">←</span> Ver todo el blog
                </a>
            </div>

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
        'hideHeader'      => true,
        'skipFirst'       => false,
    ])
@else
    <section class="py-24 md:py-32 text-center bg-mt-bg-2 border-t border-mt-border">
        <div class="mt-container">
            <span class="inline-block font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3 mb-4">
                [ Sin contenido aún ]
            </span>
            <h2 class="font-display font-bold text-mt-text leading-tight text-balance text-[clamp(1.75rem,4vw,2.75rem)] mb-5">
                Estamos preparando contenido sobre
                <span class="italic" style="color: {{ $tint }};">{{ strtolower($categoryName) }}</span>.
            </h2>
            <p class="text-mt-text-muted text-base md:text-lg max-w-xl mx-auto mb-8 leading-relaxed">
                Aún no publicamos en esta categoría. Mientras tanto, explora el resto del blog o cuéntanos qué te interesa que escribamos.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('blog.index') }}" class="mt-btn-primary">
                    Ver todos los artículos <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-btn-ghost">
                    Sugerir un tema
                </a>
            </div>
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
