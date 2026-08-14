@extends('layouts.app-home')

{{--
    Landing: Desarrollo de E-commerce / Tiendas online a la medida.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = desarrollo-ecommerce), editable desde /admin/seo/{id}/edit.
    Aquí solo va el CONTENIDO + el FAQPage (via head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, quiero una tienda online (e-commerce) a la medida para mi negocio.');
    $waUrl = 'https://wa.me/'.$waNumber.'?text='.$waMsg;
    $casoUrl = url('/proyectos/nuvion-glass');

    // Tiendas reales en producción (enlace interno al caso = prueba + SEO)
    $tiendas = [
        ['nombre' => 'Nuvion Glass', 'pais' => '🇲🇽', 'slug' => 'nuvion-glass', 'desc' => 'Lentes con filtro de luz azul · Stripe'],
        ['nombre' => 'Esnova Market', 'pais' => '🇨🇴', 'slug' => 'esnova', 'desc' => 'Marketplace multi-vendedor'],
        ['nombre' => 'Choco Art', 'pais' => '🇨🇴', 'slug' => 'choco-art', 'desc' => 'Chocolatería artesanal'],
        ['nombre' => 'Imani', 'pais' => '🇪🇨', 'slug' => 'imani', 'desc' => 'Tienda de accesorios'],
        ['nombre' => 'Anabelle', 'pais' => '🇨🇴', 'slug' => 'anabelle', 'desc' => 'Moda y accesorios'],
        ['nombre' => 'Offiesco LATAM', 'pais' => '🇨🇴', 'slug' => 'offiesco-latam', 'desc' => 'Suministros de oficina'],
    ];

    $faqs = [
        [
            'q' => '¿Qué es un e-commerce a la medida y en qué se diferencia de Shopify o WooCommerce?',
            'a' => 'Es una tienda online desarrollada específicamente para tu negocio, no un molde alquilado. A diferencia de Shopify o las plantillas, el código y los datos son tuyos, no pagas comisión por venta ni mensualidad de plataforma, y la tienda se adapta a tu operación (catálogo, precios, flujos, integraciones) sin los límites de un plan.',
        ],
        [
            'q' => '¿Puedo cobrar con Stripe, Wompi, Mercado Pago o pago contra entrega?',
            'a' => 'Sí. Integramos las pasarelas que necesites —Stripe, Wompi, Mercado Pago, Sistecrédito, PSE— y también pago contra entrega o transferencia. El checkout se optimiza para que se abandonen menos carritos.',
        ],
        [
            'q' => '¿La tienda queda optimizada para SEO y para vender?',
            'a' => 'Sí. La construimos con SEO técnico de fábrica: URLs limpias, velocidad de carga, datos estructurados de producto (schema), y una experiencia de compra móvil primero pensada para convertir visitas en ventas, no solo para verse bonita.',
        ],
        [
            'q' => '¿Se integra con mi facturación (DIAN/SIIGO), inventario y envíos?',
            'a' => 'Sí. Conectamos la tienda con tu facturación electrónica (DIAN, SIIGO), tu inventario, transportadoras y ERPs. Es justo lo que las plantillas genéricas no permiten hacer bien.',
        ],
        [
            'q' => '¿Cuánto cuesta desarrollar una tienda online a la medida?',
            'a' => 'Depende del catálogo, las pasarelas y las integraciones, pero un e-commerce a la medida arranca desde aproximadamente USD 1.200 (~$4.800.000 COP). En MY Tech Solutions cotizamos por fases, sin comisiones por venta ni costos ocultos.',
        ],
        [
            'q' => '¿En cuánto tiempo queda lista mi tienda?',
            'a' => 'Una primera versión vendiendo suele estar lista en pocas semanas. Empezamos por el catálogo y el checkout, lanzamos, y luego iteramos con más funcionalidades, integraciones y campañas.',
        ],
    ];

    $capacidades = [
        ['icon' => 'box', 'title' => 'Catálogo e inventario', 'desc' => 'Productos, variantes, categorías y stock en tiempo real, administrados desde tu propio panel.'],
        ['icon' => 'card', 'title' => 'Pagos que convierten', 'desc' => 'Stripe, Wompi, Mercado Pago, Sistecrédito, PSE y pago contra entrega, con checkout optimizado.'],
        ['icon' => 'bolt', 'title' => 'Checkout sin fricción', 'desc' => 'Rápido y móvil primero, diseñado para que se abandonen menos carritos y cierres más ventas.'],
        ['icon' => 'dashboard', 'title' => 'Panel propio, sin comisiones', 'desc' => 'Administras pedidos, clientes y promociones. Sin comisión por venta ni mensualidad de plataforma.'],
        ['icon' => 'search', 'title' => 'SEO técnico de fábrica', 'desc' => 'URLs limpias, velocidad, schema de producto y todo para posicionar y aparecer en Google.'],
        ['icon' => 'plug', 'title' => 'Integraciones a la medida', 'desc' => 'Facturación (DIAN/SIIGO), envíos, ERPs, WhatsApp y lo que tu operación necesite.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Definimos tu tienda', 'desc' => 'Catálogo, pagos, envíos y diferenciadores. Salimos con un plan claro y sin ambigüedades.'],
        ['num' => '02', 'title' => 'Diseñamos y construimos', 'desc' => 'UX de venta + desarrollo en Laravel, con previews semanales para que ajustes en el camino.'],
        ['num' => '03', 'title' => 'Pagos, SEO y pruebas', 'desc' => 'Conectamos las pasarelas, optimizamos para Google y probamos cada flujo de compra contigo.'],
        ['num' => '04', 'title' => 'Lanzamos y crecemos', 'desc' => 'Subimos a producción, capacitamos a tu equipo e iteramos con mejoras y campañas.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-ec-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-ec-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Tiendas online · a la medida
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Tiendas online a la medida que <span class="text-mt-accent">venden</span>, no plantillas que estorban.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Desarrollamos tu e-commerce a la medida sobre Laravel: catálogo, pagos, checkout optimizado y SEO técnico. El código y los datos son tuyos &mdash; sin comisiones por venta ni límites de plantilla.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Pagos con Stripe, Wompi, Mercado Pago y contra entrega',
                        'Checkout rápido y móvil primero para vender más',
                        'SEO técnico de fábrica para aparecer en Google',
                    ] as $bullet)
                        <li class="flex items-start gap-3 text-mt-text">
                            <span class="flex-shrink-0 w-6 h-6 mt-0.5 rounded-full bg-mt-accent-soft border border-mt-accent-line flex items-center justify-center">
                                <svg class="w-3 h-3 text-mt-accent" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-base md:text-[17px]">{{ $bullet }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-11 flex flex-wrap gap-3.5" data-animate>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-primary">
                        Quiero mi tienda online
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#tiendas" class="mt-btn-ghost">
                        Ver tiendas reales
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-mt-border grid grid-cols-3 gap-x-6 sm:gap-x-10 gap-y-6 max-w-lg" data-animate>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight flex items-baseline">+<span data-counter="10" data-counter-decimals="0" aria-label="10">0</span></div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Tiendas en producción</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight">0%</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Comisión por venta</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight">100%</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Tuyo: código y datos</div>
                    </div>
                </div>
            </div>

            {{-- Mockup de tienda (ventana de navegador) --}}
            <div class="relative flex justify-center lg:justify-end" data-animate>
                <div class="mt-ec-window">
                    <div class="mt-ec-window-bar">
                        <span class="mt-ec-dot" style="background:#ff5f57"></span>
                        <span class="mt-ec-dot" style="background:#febc2e"></span>
                        <span class="mt-ec-dot" style="background:#28c840"></span>
                        <span class="mt-ec-urlbar">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                            tutienda.com
                        </span>
                    </div>
                    <div class="mt-ec-window-body">
                        <div class="mt-ec-card">
                            <div class="mt-ec-card-media" aria-hidden="true">
                                <span class="mt-ec-badge">-20%</span>
                                <span class="mt-ec-media-emoji">🕶️</span>
                            </div>
                            <div class="mt-ec-card-info">
                                <div class="mt-ec-stars" aria-hidden="true">★★★★★</div>
                                <div class="mt-ec-card-title">Lentes Blue-Block</div>
                                <div class="mt-ec-card-price">
                                    <span class="mt-ec-price-now">$120.000</span>
                                    <span class="mt-ec-price-old">$150.000</span>
                                </div>
                                <div class="mt-ec-add">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2.3 2.3M17 13l1.3 2.3M9 20a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                    Agregar al carrito
                                </div>
                            </div>
                        </div>
                        <div class="mt-ec-checkout">
                            <div>
                                <div class="mt-ec-checkout-label">Total del carrito</div>
                                <div class="mt-ec-checkout-total">$240.000</div>
                            </div>
                            <span class="mt-ec-pay">Pagar seguro 🔒</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- TRUST BAR                                                     --}}
{{-- ============================================================= --}}
<section class="bg-mt-bg-2 border-y border-mt-border py-8">
    <div class="mt-container">
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-mt-text-2">
            <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Pagos e integraciones</span>
            @foreach (['Stripe', 'Wompi', 'Mercado Pago', 'Sistecrédito', 'PSE', 'DIAN / SIIGO'] as $item)
                <span class="inline-flex items-center gap-2 text-sm font-medium text-mt-text">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent"></span>{{ $item }}
                </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- PROBLEMA                                                      --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white">
    <div class="mt-container">
        <div class="max-w-3xl" data-animate>
            <span class="mt-eyebrow-gray">El problema</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                Tu tienda no debería pagar renta a una plantilla.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Las plantillas y marketplaces cobran comisión por cada venta, te limitan a lo que su plan permite y no se adaptan a tu operación. Terminas pagando más, vendiendo menos y sin ser dueño de tu propio negocio digital.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Comisiones que se comen tu margen', 'd' => 'Cada venta paga un porcentaje a la plataforma, mes tras mes, para siempre.'],
                ['t' => 'Límites de plantilla', 'd' => 'Lo que tu negocio necesita casi nunca cabe en lo que el plan te deja hacer.'],
                ['t' => 'Lentas y sin SEO real', 'd' => 'Cargan lento y no posicionan, así que dependes de pagar publicidad para vender.'],
            ] as $p)
                <div class="rounded-2xl border border-mt-border bg-mt-bg-2 p-6" data-animate>
                    <div class="w-10 h-10 rounded-xl bg-white border border-mt-border flex items-center justify-center text-mt-accent mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.4 14.55A2 2 0 003.6 21.4h16.8a2 2 0 001.72-3l-8.4-14.55a2 2 0 00-3.44 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-display font-semibold text-mt-text leading-tight">{{ $p['t'] }}</h3>
                    <p class="mt-2 text-mt-text-2 text-[14.5px] leading-relaxed">{{ $p['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CAPACIDADES / SOLUCIÓN                                        --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-14" data-animate>
            <span class="mt-eyebrow">La solución</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                Todo lo de una tienda seria, hecho a tu medida.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Una plataforma de e-commerce propia, con todo lo que necesitas para vender y crecer sin depender de nadie.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($capacidades as $c)
                <div class="group rounded-2xl border border-mt-border bg-white p-6 transition-colors duration-300 hover:border-mt-accent" data-animate>
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                        @switch($c['icon'])
                            @case('box')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8l-9-5-9 5 9 5 9-5zM3 8v8l9 5 9-5V8M12 13v8"/></svg>
                                @break
                            @case('card')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path stroke-linecap="round" d="M3 10h18"/></svg>
                                @break
                            @case('bolt')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 3L4 14h6l-1 7 9-11h-6l1-7z"/></svg>
                                @break
                            @case('dashboard')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                                @break
                            @case('search')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M21 21l-4.3-4.3"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7V4a1 1 0 011-1h1a1 1 0 011 1v3M9 7h3M6 7h12l-1 12a2 2 0 01-2 2H9a2 2 0 01-2-2L6 7z"/></svg>
                        @endswitch
                    </span>
                    <h3 class="mt-4 text-lg font-display font-semibold text-mt-text leading-tight">{{ $c['title'] }}</h3>
                    <p class="mt-2 text-mt-text-2 text-[14.5px] leading-relaxed">{{ $c['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CÓMO FUNCIONA                                                 --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32" data-animate>
                    <span class="mt-eyebrow-gray">Cómo funciona</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">De una idea a una tienda que vende.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Cuatro fases, comunicación constante y entregables claros. Nosotros lo construimos, tú lo apruebas.
                    </p>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-8 text-mt-accent hover:gap-3 transition-all text-sm font-mono uppercase tracking-wider">
                        Empezar ahora <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
            <div class="lg:col-span-8 flex flex-col gap-4">
                @foreach ($pasos as $paso)
                    <div class="flex items-start gap-5 rounded-2xl border border-mt-border bg-white p-6 md:p-7 transition-colors duration-300 hover:border-mt-accent" data-animate>
                        <span class="flex-shrink-0 font-display font-semibold text-2xl md:text-3xl text-mt-accent/25 leading-none w-12">{{ $paso['num'] }}</span>
                        <div>
                            <h3 class="text-lg md:text-xl font-display font-semibold text-mt-text leading-tight">{{ $paso['title'] }}</h3>
                            <p class="mt-2 text-mt-text-2 text-[15px] leading-relaxed">{{ $paso['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CASO REAL + TIENDAS                                           --}}
{{-- ============================================================= --}}
<section id="tiendas" class="relative py-28 md:py-36 bg-mt-bg-dark overflow-hidden scroll-mt-24">
    <div class="mt-container relative z-10">
        <div class="max-w-3xl" data-animate>
            <span class="font-mono text-[11px] md:text-xs uppercase tracking-[0.22em] text-mt-accent-on-dark">Tiendas reales · en producción</span>
            <h2 class="mt-4 text-section font-display text-white">
                No es teoría: tiendas que ya venden en varios países.
            </h2>
            <p class="mt-6 text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                Un ejemplo: <strong class="text-white">Nuvion Glass</strong> (México) es un e-commerce de lentes con filtro de luz azul que desarrollamos a la medida, con <strong class="text-white">checkout de Stripe</strong> y SEO, hoy en producción en nuvionglass.com.mx.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ $casoUrl }}" class="mt-btn-primary">
                    Ver el caso completo <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-ghost mt-btn-ghost-on-dark">
                    Quiero una tienda así
                </a>
            </div>
        </div>

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-4" data-animate>
            @foreach ($tiendas as $t)
                <a href="{{ url('/proyectos/'.$t['slug']) }}" class="group flex flex-col rounded-2xl border border-white/10 bg-white/[0.04] p-5 transition-colors duration-300 hover:border-white/25 hover:bg-white/[0.07]">
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-display font-semibold text-white text-lg leading-tight">{{ $t['nombre'] }}</span>
                        <span class="text-xl" aria-hidden="true">{{ $t['pais'] }}</span>
                    </div>
                    <p class="mt-1.5 text-mt-text-on-dark text-[13.5px] leading-snug">{{ $t['desc'] }}</p>
                    <div class="mt-auto pt-5 flex items-center justify-between gap-3">
                        <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-on-dark transition-colors duration-300 group-hover:text-white">Ver caso</span>
                        <span class="relative inline-flex items-center justify-center w-9 h-9 rounded-full border border-white/20 text-white overflow-hidden transition-all duration-300 group-hover:border-mt-accent" aria-hidden="true">
                            <span class="absolute inset-0 bg-mt-accent scale-0 rounded-full transition-transform duration-300 ease-out group-hover:scale-100"></span>
                            <svg class="relative w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- COMPARATIVA                                                   --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-12" data-animate>
            <span class="mt-eyebrow-gray">Por qué a la medida</span>
            <h2 class="mt-4 text-section font-display text-mt-text">Tu tienda, tus reglas &mdash; no las del dueño de la plantilla.</h2>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-mt-border" data-animate>
            <table class="w-full min-w-[640px] text-left border-collapse">
                <thead>
                    <tr class="bg-mt-bg-2 text-mt-text">
                        <th class="p-4 md:p-5 font-mono text-[11px] uppercase tracking-[0.14em] text-mt-text-3 font-medium"></th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Marketplace</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Plantilla (Shopify)</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-accent-line bg-mt-accent-soft text-mt-accent">Tienda a la medida</th>
                    </tr>
                </thead>
                <tbody class="text-[14.5px]">
                    @foreach ([
                        'Dueño del código y los datos',
                        'Sin comisión por cada venta',
                        'Se adapta a tu operación y flujo',
                        'SEO técnico real (posicionar en Google)',
                        'Integraciones a medida (facturación, ERP)',
                        'Escala sin límites de plan',
                    ] as $i => $fila)
                        <tr class="{{ $i % 2 ? 'bg-mt-bg-2/50' : 'bg-white' }} border-t border-mt-border">
                            <td class="p-4 md:p-5 text-mt-text font-medium">{{ $fila }}</td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">✕</span>
                            </td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ in_array($i, [2, 3, 4]) ? '≈' : '✕' }}</span>
                            </td>
                            <td class="p-4 md:p-5 border-l border-mt-accent-line bg-mt-accent-soft/40 text-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-mt-accent text-white">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- PRECIOS                                                       --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-10 items-center">
            <div data-animate>
                <span class="mt-eyebrow-gray">Inversión</span>
                <h2 class="mt-4 text-section font-display text-mt-text">Una inversión, no una renta mensual eterna.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una tienda que es <strong class="text-mt-text">tuya</strong>. Cotizamos por fases y con precios claros, sin comisiones por venta ni sorpresas. Empiezas vendiendo y escalas cuando quieras.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Catálogo, carrito y checkout optimizado',
                        'Pasarelas de pago y pago contra entrega',
                        'Panel de administración y reportes',
                        'SEO técnico + capacitación de tu equipo',
                    ] as $inc)
                        <li class="flex items-start gap-3 text-mt-text">
                            <span class="flex-shrink-0 w-6 h-6 mt-0.5 rounded-full bg-mt-accent-soft border border-mt-accent-line flex items-center justify-center">
                                <svg class="w-3 h-3 text-mt-accent" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-[15px]">{{ $inc }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-3xl border border-mt-border bg-white p-8 md:p-10 shadow-mt-medium" data-animate>
                <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Tienda a la medida</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-mt-text-2 text-lg">desde</span>
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;1.200</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $4.800.000 COP · por fases</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi tienda
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende del catálogo, las pasarelas y las integraciones.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- FAQ                                                           --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32" data-animate>
                    <span class="mt-eyebrow-gray">Preguntas frecuentes</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">Lo que más nos preguntan.</h2>
                    <p class="mt-6 text-mt-text-2 text-base leading-relaxed">
                        ¿Tienes otra duda? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
                    </p>
                </div>
            </div>
            <div class="lg:col-span-8 flex flex-col divide-y divide-mt-border border-t border-b border-mt-border" data-animate>
                @foreach ($faqs as $faq)
                    <details class="group py-5">
                        <summary class="flex items-start justify-between gap-4 cursor-pointer list-none">
                            <span class="text-lg font-display font-semibold text-mt-text leading-snug">{{ $faq['q'] }}</span>
                            <span class="flex-shrink-0 mt-1 w-6 h-6 rounded-full border border-mt-border flex items-center justify-center text-mt-accent transition-transform duration-300 group-open:rotate-45" aria-hidden="true">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                            </span>
                        </summary>
                        <p class="mt-3 pr-10 text-mt-text-2 text-[15px] leading-relaxed">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CTA FINAL                                                     --}}
{{-- ============================================================= --}}
<section class="relative py-24 md:py-32 bg-mt-bg-dark overflow-hidden">
    <div class="mt-ec-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Deja de pagarle renta a una plantilla. Ten tu propia tienda.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos qué vendes y te mostramos cómo se vería tu e-commerce a la medida. Sin compromiso.
        </p>
        <div class="mt-10 flex flex-wrap gap-4 justify-center" data-animate>
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-primary">
                Hablar por WhatsApp
                <span aria-hidden="true">&rarr;</span>
            </a>
            <a href="{{ route('contacto.index') }}" class="mt-btn-ghost mt-btn-ghost-on-dark">
                Agendar una llamada
            </a>
        </div>
    </div>
</section>

@endsection

{{-- ============================================================= --}}
{{-- SCHEMA: FAQPage (via head_extras)                             --}}
{{-- ============================================================= --}}
@push('head_extras')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => [
        '@type' => 'Question',
        'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ])->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

{{-- ============================================================= --}}
{{-- ESTILOS ESPECÍFICOS DE LA LANDING                             --}}
{{-- ============================================================= --}}
@push('styles')
<style>
    .mt-ec-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 80% 6%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 6% 22%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-ec-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Ventana de navegador / storefront */
    .mt-ec-window {
        width: 100%; max-width: 400px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-ec-window-bar {
        display: flex; align-items: center; gap: 7px;
        padding: 0.7rem 0.85rem;
        background: #F3F4F6; border-bottom: 1px solid #E5E7EB;
    }
    .mt-ec-dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
    .mt-ec-urlbar {
        margin-left: 0.5rem; flex: 1;
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #E5E7EB; border-radius: 8px;
        padding: 0.28rem 0.6rem; font-size: 12px; color: #6B7280;
        font-family: 'JetBrains Mono', monospace;
    }
    .mt-ec-window-body { padding: 1.1rem; background: #F9FAFB; }
    .mt-ec-card {
        border: 1px solid #E5E7EB; border-radius: 14px; background: #fff; overflow: hidden;
    }
    .mt-ec-card-media {
        position: relative; height: 150px;
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        display: flex; align-items: center; justify-content: center;
    }
    .mt-ec-media-emoji { font-size: 3.5rem; }
    .mt-ec-badge {
        position: absolute; top: 10px; left: 10px;
        background: #2563EB; color: #fff; font-size: 11px; font-weight: 700;
        padding: 3px 9px; border-radius: 999px;
        font-family: 'JetBrains Mono', monospace;
    }
    .mt-ec-card-info { padding: 0.85rem 1rem 1rem; }
    .mt-ec-stars { color: #F59E0B; font-size: 13px; letter-spacing: 1px; }
    .mt-ec-card-title { font-weight: 600; color: #1F2937; margin-top: 3px; font-size: 15px; }
    .mt-ec-card-price { display: flex; align-items: baseline; gap: 8px; margin-top: 4px; }
    .mt-ec-price-now { font-weight: 700; color: #1F2937; font-size: 18px; }
    .mt-ec-price-old { color: #9CA3AF; text-decoration: line-through; font-size: 13px; }
    .mt-ec-add {
        margin-top: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 8px;
        background: #2563EB; color: #fff; font-weight: 600; font-size: 14px;
        padding: 0.6rem; border-radius: 10px;
        box-shadow: 0 4px 14px rgba(37,99,235,0.25);
    }
    .mt-ec-checkout {
        margin-top: 0.9rem; display: flex; align-items: center; justify-content: space-between;
        background: #fff; border: 1px solid #E5E7EB; border-radius: 12px; padding: 0.7rem 0.9rem;
    }
    .mt-ec-checkout-label { font-size: 11px; color: #9CA3AF; font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: 0.08em; }
    .mt-ec-checkout-total { font-weight: 700; color: #1F2937; font-size: 18px; }
    .mt-ec-pay {
        background: #0B1220; color: #fff; font-weight: 600; font-size: 13px;
        padding: 0.55rem 0.9rem; border-radius: 9px;
    }
</style>
@endpush

@push('scripts')
<script>
    /* Red de seguridad: si el observer de reveals no corre, revela el contenido igual. */
    window.addEventListener('load', function () {
        setTimeout(function () {
            if (!document.querySelector('[data-animate].is-visible')) {
                document.querySelectorAll('[data-animate]').forEach(function (el) {
                    el.classList.add('is-visible');
                });
            }
        }, 700);
    });
</script>
@endpush
