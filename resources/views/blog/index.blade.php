@php
    /* SEO leído desde BD (tabla `seo` page_id={blog}) — editable en admin.
       Fallback: defaults inline si no hay registro SEO en BD. */
    $blogUrl    = route('blog.index');
    $totalPosts = $posts->total();

    if (! isset($seo) || ! $seo) {
        // Fallback hardcoded por si la page 'blog' no fue creada
        $seo = (object) [
            'meta_title'          => 'Blog — Desarrollo de software a medida, SaaS y automatizaciones | MY Tech',
            'meta_description'    => 'Guías técnicas y casos reales sobre desarrollo a medida, SaaS, automatización con IA y SEO para empresas LATAM. '.$totalPosts.' artículos.',
            'canonical_url'       => $blogUrl,
            'robots'              => 'index,follow',
            'og_title'            => 'Blog técnico — MY Tech Solutions',
            'og_description'      => 'Guías, casos y análisis sobre desarrollo de software, SaaS, IA y SEO en LATAM.',
            'og_url'              => $blogUrl,
            'og_type'             => 'website',
            'og_image'            => asset('images/logo.png'),
            'og_site_name'        => 'MY Tech Solutions',
            'twitter_card'        => 'summary_large_image',
            'twitter_title'       => 'Blog técnico — MY Tech Solutions',
            'twitter_description' => 'Guías, casos y análisis sobre desarrollo de software a medida, SaaS, IA y SEO en LATAM.',
            'twitter_image'       => asset('images/logo.png'),
            'schema_markup'       => null,
        ];
    }

    // Schema BlogPosting auto-generado para los últimos posts (siempre dinámico)
    $autoBlogSchema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Blog',
        'name'        => $seo->meta_title ?? 'Blog — MY Tech Solutions',
        'description' => $seo->meta_description ?? '',
        'url'         => $blogUrl,
        'inLanguage'  => 'es',
        'publisher'   => [
            '@type' => 'Organization',
            'name'  => 'MY Tech Solutions',
            'url'   => url('/'),
            'logo'  => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
        ],
        'blogPost' => $posts->take(10)->map(fn($p) => array_filter([
            '@type'         => 'BlogPosting',
            'headline'      => $p->title,
            'url'           => route('blog.show', $p->slug),
            'datePublished' => $p->published_at?->toIso8601String(),
            'image'         => $p->featured_image ? asset('storage/'.$p->featured_image) : null,
            'author'        => $p->author ? ['@type' => 'Person', 'name' => $p->author] : null,
        ]))->values()->toArray(),
    ];

    // Si admin no puso schema custom en BD, usamos el auto-generado dinámico
    if (empty($seo->schema_markup)) {
        // El objeto BD es de la tabla seo (Eloquent) y podemos mutarlo en memoria
        if (is_object($seo) && method_exists($seo, 'setAttribute')) {
            $seo->schema_markup = $autoBlogSchema;
        } else {
            $seo->schema_markup = $autoBlogSchema;
        }
    }
@endphp

@extends('layouts.app-home')

@section('content')
    @include('partials.blog.hero')
    @include('partials.blog.featured')
    @include('partials.blog.grid')

    {{-- CTA dark final --}}
    @php
        $page = (object) [
            'content' => json_encode([
                'cta_eyebrow'          => '[ ¿Listo? ]',
                'cta_title_main'       => '¿Tu negocio será el',
                'cta_title_accent'     => 'próximo caso',
                'cta_subtitle'         => 'Pasa de leer guías a construir tu propio caso de éxito. Cotiza tu proyecto en 24h sin compromiso.',
                'cta_whatsapp_text'    => 'Hablemos por WhatsApp',
                'cta_form_button_text' => 'Cotizar mi proyecto',
            ], JSON_UNESCAPED_UNICODE),
        ];
    @endphp
    @include('partials.home.cta-intermedio')
@endsection
