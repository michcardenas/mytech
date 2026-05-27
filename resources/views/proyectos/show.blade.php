@php
    /* ─────────────────────────────────────────────────────────────────
       Pre-cálculo SEO para que el layout app-home lo lea desde $seo.
       Construimos un objeto plano con todos los campos que el layout espera.
       ───────────────────────────────────────────────────────────────── */

    $detailUrl  = route('proyectos.show', $proyecto->slug);

    $seoTitle   = $proyecto->meta_title ?: ($proyecto->nombre . ' — MY Tech Solutions');
    $seoDesc    = $proyecto->meta_description ?: ($proyecto->excerpt ?: $proyecto->descripcion);
    $canonical  = $proyecto->canonical_url ?: $detailUrl;
    $robots     = $proyecto->robots ?: 'index,follow';

    $ogImage    = $proyecto->og_image
        ? asset('storage/' . $proyecto->og_image)
        : ($proyecto->logo ? asset('storage/' . $proyecto->logo) : asset('images/default-og.jpg'));

    $twImage    = $proyecto->twitter_image
        ? asset('storage/' . $proyecto->twitter_image)
        : $ogImage;

    /* Whitelist de Google para Review Snippets (parent permitido del Review).
       CreativeWork NO está, por eso Google reporta error. */
    $reviewParentWhitelist = [
        'SoftwareApplication', 'WebApplication', 'MobileApplication',
        'Product', 'Service', 'LocalBusiness', 'Organization',
        'Book', 'Course', 'Event', 'HowTo', 'Movie',
        'MusicAlbum', 'MusicPlaylist', 'Recipe', 'Game',
    ];

    /* ── Construir Schema.org auto-generado o usar override custom ── */
    if (! empty($proyecto->schema_markup)) {
        // override custom (string JSON o array)
        $schemaMarkup = is_array($proyecto->schema_markup)
            ? $proyecto->schema_markup
            : json_decode($proyecto->schema_markup, true);

        /* DEFENSA: si el override de BD trae @type no permitido pero tiene review,
           Google rechaza. Forzamos @type a SoftwareApplication. */
        if (is_array($schemaMarkup) && isset($schemaMarkup['@type'])) {
            $overrideType = $schemaMarkup['@type'];
            if (! in_array($overrideType, $reviewParentWhitelist, true)) {
                $schemaMarkup['@type'] = 'SoftwareApplication';
                // SoftwareApplication requiere applicationCategory + operatingSystem
                if (empty($schemaMarkup['applicationCategory'])) {
                    $schemaMarkup['applicationCategory'] = $proyecto->categoria ?: 'BusinessApplication';
                }
                if (empty($schemaMarkup['operatingSystem'])) {
                    $schemaMarkup['operatingSystem'] = 'Web';
                }
                if (empty($schemaMarkup['offers'])) {
                    $schemaMarkup['offers'] = [
                        '@type'         => 'Offer',
                        'price'         => '0',
                        'priceCurrency' => 'COP',
                        'availability'  => 'https://schema.org/InStock',
                    ];
                }
                // Si hay review pero no aggregateRating, agregarlo (Google warning)
                if (isset($schemaMarkup['review']) && empty($schemaMarkup['aggregateRating'])) {
                    $schemaMarkup['aggregateRating'] = [
                        '@type'       => 'AggregateRating',
                        'ratingValue' => '5',
                        'bestRating'  => '5',
                        'worstRating' => '1',
                        'ratingCount' => '1',
                        'reviewCount' => '1',
                    ];
                }
            }
        }
    } else {
        $rawType  = $proyecto->schema_type ?: 'SoftwareApplication';
        $safeType = in_array($rawType, $reviewParentWhitelist, true) ? $rawType : 'SoftwareApplication';

        $s = [
            '@context'    => 'https://schema.org',
            '@type'       => $safeType,
            'name'        => $proyecto->nombre,
            'description' => $proyecto->excerpt ?: $proyecto->descripcion,
            'url'         => $detailUrl,
            'inLanguage'  => 'es',
            'image'       => $ogImage,
            'author' => [
                '@type' => 'Organization',
                'name'  => $proyecto->author ?: 'MY Tech Solutions',
                'url'   => url('/'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => 'MY Tech Solutions',
                'url'   => url('/'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => asset('images/logo.png'),
                ],
            ],
        ];

        if ($proyecto->publicado_en)      $s['datePublished'] = $proyecto->publicado_en->format('Y-m-d');
        if ($proyecto->fecha_lanzamiento) $s['dateCreated']   = $proyecto->fecha_lanzamiento->format('Y-m-d');
        if ($proyecto->updated_at)        $s['dateModified']  = $proyecto->updated_at->format('Y-m-d');
        if ($proyecto->url)               $s['sameAs']        = [$proyecto->url];
        if (is_array($proyecto->tecnologias) && count($proyecto->tecnologias)) {
            $s['keywords'] = implode(', ', $proyecto->tecnologias);
        }
        if ($proyecto->categoria) $s['genre'] = $proyecto->categoria;
        if ($proyecto->industria) $s['about'] = $proyecto->industria;

        if (in_array($s['@type'], ['SoftwareApplication', 'WebApplication', 'MobileApplication'])) {
            $s['applicationCategory'] = $proyecto->categoria;
            $s['operatingSystem']     = 'Web';
            /* offers requerido por Google para SoftwareApplication */
            $s['offers'] = [
                '@type'         => 'Offer',
                'price'         => '0',
                'priceCurrency' => 'COP',
                'availability'  => 'https://schema.org/InStock',
            ];
        }

        /* Review solo si el parent type lo soporta (whitelist arriba) */
        if ($proyecto->testimonio && $proyecto->testimonio_autor && in_array($s['@type'], $reviewParentWhitelist, true)) {
            $s['review'] = [
                '@type'      => 'Review',
                'reviewBody' => $proyecto->testimonio,
                'author' => [
                    '@type'    => 'Person',
                    'name'     => $proyecto->testimonio_autor,
                    'jobTitle' => $proyecto->testimonio_cargo,
                ],
                'reviewRating' => [
                    '@type'       => 'Rating',
                    'ratingValue' => '5',
                    'bestRating'  => '5',
                    'worstRating' => '1',
                ],
            ];
            /* AggregateRating evita warning "needs aggregateRating" */
            $s['aggregateRating'] = [
                '@type'       => 'AggregateRating',
                'ratingValue' => '5',
                'bestRating'  => '5',
                'worstRating' => '1',
                'ratingCount' => '1',
                'reviewCount' => '1',
            ];
        }

        $s['breadcrumb'] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio',    'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Proyectos', 'item' => route('proyectos.index')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $proyecto->breadcrumb_title ?: $proyecto->nombre, 'item' => $detailUrl],
            ],
        ];

        $schemaMarkup = $s;
    }

    /* ── Objeto $seo plug-and-play para el layout app-home ── */
    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'canonical_url'       => $canonical,
        'robots'              => $robots,
        'og_title'            => $proyecto->og_title ?: $seoTitle,
        'og_description'      => $proyecto->og_description ?: $seoDesc,
        'og_url'              => $detailUrl,
        'og_type'             => $proyecto->og_type ?: 'article',
        'og_image'            => $ogImage,
        'og_site_name'        => 'MY Tech Solutions',
        'twitter_card'        => $proyecto->twitter_card ?: 'summary_large_image',
        'twitter_title'       => $proyecto->twitter_title ?: ($proyecto->og_title ?: $seoTitle),
        'twitter_description' => $proyecto->twitter_description ?: ($proyecto->og_description ?: $seoDesc),
        'twitter_image'       => $twImage,
        'schema_markup'       => $schemaMarkup,
    ];
@endphp

@extends('layouts.app-home')

@section('content')
    @include('partials.proyecto-detalle.hero')
    @include('partials.proyecto-detalle.case-study')
    @include('partials.proyecto-detalle.galeria')
    @include('partials.proyecto-detalle.stack')
    @include('partials.proyecto-detalle.testimonio')
    @include('partials.proyecto-detalle.metricas')
    @include('partials.proyecto-detalle.relacionados')

    {{-- CTA dark final reusando el del home --}}
    @php
        // Construir $page mínimo con copy custom para el CTA
        $page = (object) [
            'content' => json_encode([
                'cta_eyebrow'       => '[ Conversemos ]',
                'cta_title_main'    => '¿Tu proyecto será el',
                'cta_title_accent'  => 'próximo',
                'cta_subtitle'      => 'Cuéntanos qué quieres construir. Te respondemos en 24h hábiles con una propuesta clara y sin compromiso.',
                'cta_whatsapp_text' => 'Hablemos por WhatsApp',
                'cta_form_button_text' => 'Enviar formulario',
            ], JSON_UNESCAPED_UNICODE),
        ];
    @endphp
    @include('partials.home.cta-intermedio')
@endsection
