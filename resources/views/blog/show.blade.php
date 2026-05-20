@php
    /* ─────────────────────────────────────────────────────────────────
       SEO premium para /blog/{slug}.
       Cada post tiene su propia SEO row (Page hasOne Seo).
       Si no tiene, fallback a defaults derivados del post.
       ───────────────────────────────────────────────────────────────── */
    $postUrl       = route('blog.show', $post->slug);
    $featuredAsset = $post->featured_image ? asset('storage/'.$post->featured_image) : asset('images/logo.png');
    $postSeo       = $post->seo ?? null;

    $seoTitle = $postSeo->meta_title       ?? ($post->title . ' | Blog MY Tech');
    $seoDesc  = $postSeo->meta_description ?? ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content ?? ''), 160));

    /* Schema BlogPosting auto-generado */
    $autoSchema = [
        '@context'         => 'https://schema.org',
        '@type'            => 'BlogPosting',
        'headline'         => $post->title,
        'description'      => $seoDesc,
        'url'              => $postUrl,
        'inLanguage'       => 'es',
        'image'            => $featuredAsset,
        'datePublished'    => $post->published_at?->toIso8601String(),
        'dateModified'     => $post->updated_at?->toIso8601String(),
        'author' => [
            '@type' => 'Person',
            'name'  => $post->author ?: 'MY Tech Solutions',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name'  => 'MY Tech Solutions',
            'url'   => url('/'),
            'logo'  => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id'   => $postUrl,
        ],
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',   'item' => route('blog.index')],
                $post->category
                    ? ['@type' => 'ListItem', 'position' => 3, 'name' => ucfirst(str_replace('-', ' ', $post->category)), 'item' => route('blog.category', $post->category)]
                    : null,
                ['@type' => 'ListItem', 'position' => $post->category ? 4 : 3, 'name' => $post->title, 'item' => $postUrl],
            ])),
        ],
    ];

    if ($post->reading_time) {
        $autoSchema['timeRequired'] = 'PT'.$post->reading_time.'M';
    }
    if ($post->category) {
        $autoSchema['articleSection'] = ucfirst(str_replace('-', ' ', $post->category));
    }
    $tagsArr = $post->getTagsArray();
    if (! empty($tagsArr)) {
        $autoSchema['keywords'] = implode(', ', $tagsArr);
    }

    /* Construir objeto $seo para layouts.app-home */
    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'canonical_url'       => $postSeo->canonical_url ?? $postUrl,
        'robots'              => $postSeo->robots ?? 'index,follow',
        'og_title'            => $postSeo->og_title ?? $seoTitle,
        'og_description'      => $postSeo->og_description ?? $seoDesc,
        'og_url'              => $postUrl,
        'og_type'             => 'article',
        'og_image'            => $postSeo->og_image ? asset('storage/'.$postSeo->og_image) : $featuredAsset,
        'og_site_name'        => 'MY Tech Solutions',
        'twitter_card'        => $postSeo->twitter_card ?? 'summary_large_image',
        'twitter_title'       => $postSeo->twitter_title ?? $seoTitle,
        'twitter_description' => $postSeo->twitter_description ?? $seoDesc,
        'twitter_image'       => $postSeo->twitter_image ? asset('storage/'.$postSeo->twitter_image) : $featuredAsset,
        'schema_markup'       => (! empty($postSeo->schema_markup))
            ? (is_array($postSeo->schema_markup) ? $postSeo->schema_markup : json_decode($postSeo->schema_markup, true))
            : $autoSchema,
    ];
@endphp

@extends('layouts.app-home')

@section('content')
    @include('partials.blog-detalle.hero')
    @include('partials.blog-detalle.content')
    @include('partials.blog-detalle.related')

    {{-- CTA dark final --}}
    @php
        $page = (object) [
            'content' => json_encode([
                'cta_eyebrow'          => '[ Conversemos ]',
                'cta_title_main'       => '¿Listo para llevar lo aprendido',
                'cta_title_accent'     => 'a tu negocio',
                'cta_subtitle'         => 'Si lo que leíste se ajusta a un proyecto que tienes en mente, cuéntanos. Respondemos en 24h con una propuesta clara y sin compromiso.',
                'cta_whatsapp_text'    => 'Hablemos por WhatsApp',
                'cta_form_button_text' => 'Cotizar mi proyecto',
            ], JSON_UNESCAPED_UNICODE),
        ];
    @endphp
    @include('partials.home.cta-intermedio')

    {{-- Banner sutil de copia exitosa --}}
    <div class="mt-bd-copy-toast" data-bd-toast aria-hidden="true">
        ✓ Enlace copiado
    </div>
@endsection
