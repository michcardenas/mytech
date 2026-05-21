@php
    /* /blog/tag/{tag} — Listado filtrado por tag. */
    $tagUrl     = route('blog.tag', $tag);
    $totalPosts = $posts->total();
    $tagDisplay = ucfirst(str_replace('-', ' ', $tag));
    $tint       = '#2563EB';

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

{{-- ════════════════ HERO tag (mismo pattern que /blog index) ════════════════ --}}
<section class="mt-blog-hero relative pt-36 pb-20 md:pb-28 overflow-hidden bg-white"
         data-blog-hero
         style="--cat-tint: {{ $tint }};">

    <div class="mt-blog-hero-watermark" aria-hidden="true" data-blog-watermark>
        <span>#{{ strtoupper($tagDisplay) }}</span>
    </div>

    <div class="mt-container relative z-10">
        <div class="max-w-5xl">

            <nav class="mb-6 font-mono text-[12px] uppercase tracking-[0.16em] text-mt-text-3 flex items-center gap-2 flex-wrap"
                 aria-label="Breadcrumb"
                 data-animate>
                <a href="{{ url('/') }}" class="hover:text-mt-accent transition-colors">Inicio</a>
                <span aria-hidden="true" class="opacity-40">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-mt-accent transition-colors">Blog</a>
                <span aria-hidden="true" class="opacity-40">/</span>
                <span class="text-mt-text" aria-current="page">#{{ $tagDisplay }}</span>
            </nav>

            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent"></span>
                    Tag · #{{ strtoupper($tagDisplay) }}
                </span>
            </div>

            <h1 class="mt-7 font-display font-bold text-mt-text leading-[0.95] tracking-tight text-balance
                       text-[clamp(2.5rem,7vw,6rem)]"
                data-blog-hero-title>
                <span class="inline-block">Artículos con</span>
                <span class="inline-block text-mt-accent italic font-display">#{{ strtolower($tagDisplay) }}</span>.
            </h1>

            <p class="mt-8 max-w-2xl text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                @if($totalPosts > 0)
                    {{ $totalPosts === 1 ? '1 publicación encontrada' : $totalPosts . ' publicaciones encontradas' }}
                    con este tag — explora los temas relacionados.
                @else
                    No hay artículos con este tag todavía. Mientras tanto, explora el resto del blog.
                @endif
            </p>

            <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-3 font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3"
                 data-animate>
                <span class="inline-flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-mt-accent"></span>
                    <strong class="text-mt-text font-display text-[14px] font-semibold">{{ $totalPosts }}</strong>
                    {{ $totalPosts === 1 ? 'artículo' : 'artículos' }}
                </span>
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-1.5 text-mt-accent hover:text-mt-text transition-colors normal-case tracking-normal font-sans text-[13px]">
                    <span aria-hidden="true">←</span> Ver todo el blog
                </a>
            </div>

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
    <section class="py-24 md:py-32 text-center bg-mt-bg-2 border-t border-mt-border">
        <div class="mt-container">
            <span class="inline-block font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3 mb-4">
                [ Sin contenido aún ]
            </span>
            <h2 class="font-display font-bold text-mt-text leading-tight text-balance text-[clamp(1.75rem,4vw,2.75rem)] mb-5">
                No hay artículos con
                <span class="text-mt-accent italic">#{{ strtolower($tagDisplay) }}</span>.
            </h2>
            <p class="text-mt-text-muted text-base md:text-lg max-w-xl mx-auto mb-8 leading-relaxed">
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
