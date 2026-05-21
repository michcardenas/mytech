<!DOCTYPE html>
<html lang="es" class="bg-mt-bg text-mt-text">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
        SEO source-of-truth: tabla `seo` (editable desde /admin/seo/{id}/edit).
        El layout NO debe hardcodear texto. Si una página no pasa $seo, se usa
        el fallback hardcoded como red de seguridad — pero no es el camino feliz.
    --}}
    @php
        $seoRow            = $seo ?? null;
        $metaTitleVal      = $seoRow->meta_title       ?? 'Agencia de Software a Medida en Colombia | MY Tech Solutions';
        $metaDescVal       = $seoRow->meta_description ?? '37 proyectos en producción en 11 países. Software a medida en Laravel, React e IA para empresas LATAM, USA y EU. Cotiza gratis · respuesta en 24h.';
        $canonicalVal      = $seoRow->canonical_url    ?? 'https://mytechsolutionsco.com/';
        $robotsVal         = $seoRow->robots           ?? 'index,follow';
        $ogTitleVal        = $seoRow->og_title         ?? $metaTitleVal;
        $ogDescVal         = $seoRow->og_description   ?? $metaDescVal;
        $ogUrlVal          = $seoRow->og_url           ?? $canonicalVal;
        $ogTypeVal         = $seoRow->og_type          ?? 'website';
        $ogImageVal        = $seoRow->og_image         ?? asset('images/logo.png');
        $ogSiteVal         = $seoRow->og_site_name     ?? 'MY Tech Solutions';
        $twCardVal         = $seoRow->twitter_card     ?? 'summary_large_image';
        $twTitleVal        = $seoRow->twitter_title    ?? $ogTitleVal;
        $twDescVal         = $seoRow->twitter_description ?? $ogDescVal;
        $twImageVal        = $seoRow->twitter_image    ?? $ogImageVal;
    @endphp

    <title>{{ $metaTitleVal }}</title>
    <meta name="description" content="{{ $metaDescVal }}">
    <meta name="robots" content="{{ $robotsVal }},max-image-preview:large">

    <link rel="canonical" href="{{ $canonicalVal }}">
    <meta name="google-site-verification" content="Yk8ILwU3yKtRTW0Zspxa9tKAFR3mRyI3idT0SpNvSIo">
    <meta name="msvalidate.01" content="808CD1DC4ADF1CDC768B784CFB343FAD">

    <meta property="og:locale" content="es_CO">
    <meta property="og:site_name" content="{{ $ogSiteVal }}">
    <meta property="og:title" content="{{ $ogTitleVal }}">
    <meta property="og:description" content="{{ $ogDescVal }}">
    <meta property="og:url" content="{{ $ogUrlVal }}">
    <meta property="og:type" content="{{ $ogTypeVal }}">
    <meta property="og:image" content="{{ $ogImageVal }}">
    <meta name="twitter:card" content="{{ $twCardVal }}">
    <meta name="twitter:title" content="{{ $twTitleVal }}">
    <meta name="twitter:description" content="{{ $twDescVal }}">
    <meta name="twitter:image" content="{{ $twImageVal }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|inter-tight:600,700,800|jetbrains-mono:400,500&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    {{--
        Schema.org JSON-LD desde BD (tabla `seo.schema_markup`).
        Editable desde /admin/seo/{id}/edit. Si la página no pasa $seo o el
        schema viene vacío, se renderiza un Organization mínimo como red de seguridad.
    --}}
    @php
        $schemaPayload = ($seoRow && !empty($seoRow->schema_markup))
            ? $seoRow->schema_markup
            : [
                '@context' => 'https://schema.org',
                '@type'    => 'Organization',
                'name'     => 'MY Tech Solutions',
                'url'      => 'https://mytechsolutionsco.com',
                'logo'     => 'https://mytechsolutionsco.com/images/icon.png',
            ];
    @endphp
    <script type="application/ld+json">
{!! json_encode($schemaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    {{-- GTM --}}
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MDMLQKMM');</script>

    {{-- Meta Pixel (condicional) --}}
    @if(config('services.meta.pixel_id'))
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ config('services.meta.pixel_id') }}');
        fbq('track', 'PageView');
        </script>
    @endif

    {{-- GA4 --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FDPVS72L91"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-FDPVS72L91');
    </script>

    {{-- Vite assets --}}
    @vite(['resources/css/home.css', 'resources/js/home/index.js'])

    @stack('styles')
    @stack('head_extras')
</head>
<body class="bg-mt-bg text-mt-text antialiased font-sans">

    {{-- GTM noscript --}}
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MDMLQKMM"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    @include('partials.home.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.home.footer')
    @include('partials.home.widget-whatsapp')

    {{-- Tracking global: clicks WhatsApp / email --}}
    <script>
    (function() {
        window.dataLayer = window.dataLayer || [];
        function getType(href) {
            if (!href) return null;
            var h = href.toLowerCase();
            if (h.indexOf('wa.me') !== -1 || h.indexOf('api.whatsapp.com') !== -1) return 'whatsapp';
            if (h.indexOf('mailto:') === 0) return 'email';
            if (h.indexOf('tel:') === 0)    return 'phone';
            return null;
        }
        function getLocation(el) {
            if (el.closest('.mt-wa-float')) return 'float';
            if (el.closest('footer'))       return 'footer';
            if (el.closest('nav, header'))  return 'header';
            return 'content';
        }
        document.addEventListener('click', function(e) {
            var link = e.target.closest('a');
            if (!link) return;
            var t = getType(link.getAttribute('href'));
            if (!t) return;
            var loc = getLocation(link);
            window.dataLayer.push({ event: 'click_' + t, click_location: loc, page_path: location.pathname });
            @if(config('services.meta.pixel_id'))
            if (typeof fbq !== 'undefined' && (t === 'whatsapp' || t === 'email')) {
                fbq('track', 'Contact', { content_name: t + '_' + loc });
            }
            @endif
        }, true);
    })();
    </script>

    @stack('scripts')
</body>
</html>
