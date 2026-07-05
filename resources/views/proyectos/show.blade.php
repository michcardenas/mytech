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
        ? (\Illuminate\Support\Str::startsWith($proyecto->og_image, ['http://', 'https://']) ? $proyecto->og_image : asset('storage/' . $proyecto->og_image))
        : ($proyecto->logo
            ? (\Illuminate\Support\Str::startsWith($proyecto->logo, ['http://', 'https://']) ? $proyecto->logo : asset('storage/' . $proyecto->logo))
            : asset('images/og-image.png'));

    $twImage    = $proyecto->twitter_image
        ? (\Illuminate\Support\Str::startsWith($proyecto->twitter_image, ['http://', 'https://']) ? $proyecto->twitter_image : asset('storage/' . $proyecto->twitter_image))
        : $ogImage;

    /* Whitelist de Google para Review Snippets (parent permitido del Review).
       CreativeWork NO está, por eso Google reporta error. */
    $reviewParentWhitelist = [
        'SoftwareApplication', 'WebApplication', 'MobileApplication',
        'Product', 'Service', 'LocalBusiness', 'Organization',
        'Book', 'Course', 'Event', 'HowTo', 'Movie',
        'MusicAlbum', 'MusicPlaylist', 'Recipe', 'Game',
    ];

    /* Google solo acepta estos valores para applicationCategory.
       Nuestras categorias internas ("admin", "booking", "ecommerce", etc) NO son válidas. */
    $googleAppCategories = [
        'BusinessApplication', 'CommunicationApplication', 'DesignApplication',
        'DeveloperApplication', 'EducationalApplication', 'FinanceApplication',
        'GameApplication', 'HealthApplication', 'LifestyleApplication',
        'MultimediaApplication', 'NetworkingApplication', 'ReferenceApplication',
        'SecurityApplication', 'ShoppingApplication', 'SocialNetworkingApplication',
        'SportsApplication', 'TravelApplication', 'UtilitiesApplication',
    ];
    /* Mapping de nuestras categorias a las de Google */
    $categoryMap = [
        'admin'        => 'BusinessApplication',
        'erp'          => 'BusinessApplication',
        'crm'          => 'BusinessApplication',
        'saas'         => 'BusinessApplication',
        'gestion'      => 'BusinessApplication',
        'fintech'      => 'FinanceApplication',
        'finanzas'     => 'FinanceApplication',
        'ecommerce'    => 'ShoppingApplication',
        'tienda'       => 'ShoppingApplication',
        'marketplace'  => 'ShoppingApplication',
        'booking'      => 'TravelApplication',
        'travel'       => 'TravelApplication',
        'reservas'     => 'TravelApplication',
        'educacion'    => 'EducationalApplication',
        'salud'        => 'HealthApplication',
        'fitness'      => 'HealthApplication',
        'inmobiliaria' => 'BusinessApplication',
        'logistica'    => 'BusinessApplication',
        'restaurant'   => 'LifestyleApplication',
        'gastronomia'  => 'LifestyleApplication',
        'social'       => 'SocialNetworkingApplication',
        'comunicacion' => 'CommunicationApplication',
        'whatsapp'     => 'CommunicationApplication',
        'automatizacion' => 'BusinessApplication',
        'ia'           => 'BusinessApplication',
    ];
    $mapAppCategory = function ($raw) use ($googleAppCategories, $categoryMap) {
        if (! $raw) return 'BusinessApplication';
        // Si ya es válida (ej: alguien metió "BusinessApplication" en BD), pasala
        if (in_array($raw, $googleAppCategories, true)) return $raw;
        // Normaliza para matching
        $key = strtolower(trim($raw));
        return $categoryMap[$key] ?? 'BusinessApplication';
    };

    /* ── Construir Schema.org auto-generado o usar override custom ── */
    if (! empty($proyecto->schema_markup)) {
        // override custom (string JSON o array)
        $schemaMarkup = is_array($proyecto->schema_markup)
            ? $proyecto->schema_markup
            : json_decode($proyecto->schema_markup, true);

        /* DEFENSA universal: si el override trae applicationCategory inválido lo sanitizamos
           siempre, sin importar el @type (BD puede tener legacy "admin", "booking", etc). */
        if (is_array($schemaMarkup) && isset($schemaMarkup['applicationCategory'])) {
            $schemaMarkup['applicationCategory'] = $mapAppCategory($schemaMarkup['applicationCategory']);
        }

        /* DEFENSA: si el override de BD trae @type no permitido pero tiene review,
           Google rechaza. Forzamos @type a SoftwareApplication. */
        if (is_array($schemaMarkup) && isset($schemaMarkup['@type'])) {
            $overrideType = $schemaMarkup['@type'];
            if (! in_array($overrideType, $reviewParentWhitelist, true)) {
                $schemaMarkup['@type'] = 'SoftwareApplication';
                // SoftwareApplication requiere applicationCategory + operatingSystem
                // SIEMPRE re-mapeamos para garantizar valor válido de Google (override del BD)
                $schemaMarkup['applicationCategory'] = $mapAppCategory($schemaMarkup['applicationCategory'] ?? $proyecto->categoria);
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
            $s['applicationCategory'] = $mapAppCategory($proyecto->categoria);
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

    /* ── Keywords: fusiona focus + secundarias + tecnologías (dedup) ── */
    $keywordsList = collect([$proyecto->focus_keyword])
        ->merge(is_array($proyecto->secondary_keywords) ? $proyecto->secondary_keywords : [])
        ->merge($proyecto->meta_keywords ? array_map('trim', explode(',', $proyecto->meta_keywords)) : [])
        ->filter()
        ->map(fn ($k) => trim($k))
        ->unique()
        ->values();

    /* ── Tags de article: tecnologías + keywords secundarias ── */
    $articleTags = collect(is_array($proyecto->tecnologias) ? $proyecto->tecnologias : [])
        ->merge(is_array($proyecto->secondary_keywords) ? $proyecto->secondary_keywords : [])
        ->filter()->unique()->values()->all();

    $ogImageAlt = $proyecto->alt_og_image ?: ($proyecto->nombre . ' — caso de éxito MY Tech Solutions');

    /* ── Objeto $seo plug-and-play para el layout app-home ── */
    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'meta_keywords'       => $keywordsList->isNotEmpty() ? $keywordsList->implode(', ') : null,
        'author'              => $proyecto->author ?: 'MY Tech Solutions',
        'canonical_url'       => $canonical,
        'robots'              => $robots,
        'og_title'            => $proyecto->og_title ?: $seoTitle,
        'og_description'      => $proyecto->og_description ?: $seoDesc,
        'og_url'              => $detailUrl,
        'og_type'             => $proyecto->og_type ?: 'article',
        'og_image'            => $ogImage,
        'og_image_alt'        => $ogImageAlt,
        'og_image_width'      => config('seo.og_image_width'),
        'og_image_height'     => config('seo.og_image_height'),
        'og_site_name'        => 'MY Tech Solutions',
        'twitter_card'        => $proyecto->twitter_card ?: 'summary_large_image',
        'twitter_title'       => $proyecto->twitter_title ?: ($proyecto->og_title ?: $seoTitle),
        'twitter_description' => $proyecto->twitter_description ?: ($proyecto->og_description ?: $seoDesc),
        'twitter_image'       => $twImage,
        'twitter_image_alt'   => $ogImageAlt,
        // article:* — solo se emiten si og:type = article (el layout lo controla)
        'article_published_time' => $proyecto->publicado_en ? $proyecto->publicado_en->toIso8601String() : ($proyecto->created_at ? $proyecto->created_at->toIso8601String() : null),
        'article_modified_time'  => $proyecto->updated_at ? $proyecto->updated_at->toIso8601String() : null,
        'article_author'         => $proyecto->author ?: 'MY Tech Solutions',
        'article_section'        => $proyecto->industria ?: ucfirst($proyecto->categoria),
        'article_tags'           => $articleTags,
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
    @include('partials.proyecto-detalle.faq')
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
