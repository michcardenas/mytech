@php
    /* ─────────────────────────────────────────────────────────────────
       /sobre-nosotros — Manifiesto cinemático.
       SEO BD-driven con fallbacks fuertes. $data viene del controller.
       ───────────────────────────────────────────────────────────────── */
    $aboutUrl = route('sobre_nosotros.index');

    $seoTitle = $seo?->meta_title       ?? 'Sobre nosotros · MY Tech Solutions — Manifiesto de un estudio LATAM';
    $seoDesc  = $seo?->meta_description ?? 'Construimos software a medida con el rigor que aplica cualquier equipo top, pero desde LATAM y para LATAM. Conoce qué creemos, cómo trabajamos y por qué.';

    $autoSchema = [
        '@context' => 'https://schema.org',
        '@type'    => 'AboutPage',
        'url'      => $aboutUrl,
        'inLanguage' => 'es',
        'name'     => $seoTitle,
        'description' => $seoDesc,
        'mainEntity' => [
            '@type' => 'Organization',
            'name'  => 'MY Tech Solutions',
            'url'   => url('/'),
            'logo'  => asset('images/logo.png'),
            'foundingDate' => $data['founding_year'] ?? '2022',
            'areaServed' => ['CO', 'AR', 'CL', 'MX', 'GT', 'CR', 'ES', 'US', 'DO', 'EC', 'UY', 'AU'],
            'sameAs' => array_values(array_filter([
                $data['social_linkedin'] ?? null,
                $data['social_instagram'] ?? null,
                $data['social_github'] ?? null,
            ])),
            /* AggregateRating — promedio de reviews 5⭐ a través de todos los casos
               de éxito del portfolio. Activa rich snippet de estrellas globales en
               las SERPs de marca y para resultados de "MY Tech Solutions". */
            'aggregateRating' => [
                '@type'       => 'AggregateRating',
                'ratingValue' => '5',
                'bestRating'  => '5',
                'worstRating' => '1',
                'ratingCount' => (string) \App\Models\Proyecto::whereNotNull('testimonio')->where('testimonio', '!=', '')->count(),
                'reviewCount' => (string) \App\Models\Proyecto::whereNotNull('testimonio')->where('testimonio', '!=', '')->count(),
            ],
        ],
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio',          'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Sobre nosotros',  'item' => $aboutUrl],
            ],
        ],
    ];

    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'canonical_url'       => $seo->canonical_url ?? $aboutUrl,
        'robots'              => $seo->robots ?? 'index,follow',
        'og_title'            => $seo->og_title ?? $seoTitle,
        'og_description'      => $seo->og_description ?? $seoDesc,
        'og_url'              => $aboutUrl,
        'og_type'             => 'website',
        'og_image'            => $seo->og_image
            ? (\Illuminate\Support\Str::startsWith($seo->og_image, ['http://', 'https://']) ? $seo->og_image : asset('storage/'.$seo->og_image))
            : asset('images/og-image.png'),
        'og_site_name'        => 'MY Tech Solutions',
        'twitter_card'        => $seo->twitter_card ?? 'summary_large_image',
        'twitter_title'       => $seo->twitter_title ?? $seoTitle,
        'twitter_description' => $seo->twitter_description ?? $seoDesc,
        'twitter_image'       => $seo->twitter_image
            ? (\Illuminate\Support\Str::startsWith($seo->twitter_image, ['http://', 'https://']) ? $seo->twitter_image : asset('storage/'.$seo->twitter_image))
            : asset('images/og-image.png'),
        'schema_markup'       => $autoSchema,
    ];
@endphp

@extends('layouts.app-home')

@section('content')

    @include('partials.sobre-nosotros.prologo',  ['data' => $data])
    @include('partials.sobre-nosotros.tesis',    ['data' => $data])
    @include('partials.sobre-nosotros.numeros',  ['data' => $data])
    @include('partials.sobre-nosotros.credo',    ['data' => $data])
    @include('partials.sobre-nosotros.gente',    ['data' => $data])
    @include('partials.sobre-nosotros.creditos', ['data' => $data])

@endsection
