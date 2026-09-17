@extends('layouts.app-home')

{{--
    Landing: Integración de Sistemas / APIs.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = integracion-de-sistemas), editable desde
    /admin/seo/{id}/edit. Aquí solo va el CONTENIDO + el FAQPage (head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, necesito integrar y conectar los sistemas de mi empresa.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/clc-facturacion-electronica');

    $faqs = [
        [
            'q' => '¿Qué sistemas pueden integrar?',
            'a' => 'Cualquiera que exponga una API: ERPs, CRMs, plataformas de e-commerce (Shopify, WooCommerce, tiendas a la medida), pasarelas de pago (Wompi, Stripe, Mercado Pago, Sistecrédito), facturación DIAN y SIIGO, WhatsApp, y los servicios de Google como Gmail, Drive y Calendar.',
        ],
        [
            'q' => '¿Pueden conectar mi tienda con la facturación DIAN?',
            'a' => 'Sí. Es de lo más pedido: que cada venta de tu e-commerce o tu ERP genere la factura electrónica ante la DIAN y quede registrada en tu contabilidad, sin que nadie la vuelva a digitar.',
        ],
        [
            'q' => '¿Y si uno de mis sistemas no tiene API?',
            'a' => 'Lo evaluamos. Muchas veces hay una API no documentada, un archivo de exportación o una base de datos a la que sí podemos llegar. Si de verdad no hay forma, te lo decimos de frente antes de empezar.',
        ],
        [
            'q' => '¿Qué pasa si una integración falla?',
            'a' => 'Las construimos con reintentos, colas y registro de cada evento. Si un sistema está caído, la información no se pierde: se reintenta cuando vuelve. Y queda trazabilidad de todo lo que se sincronizó.',
        ],
        [
            'q' => '¿La sincronización es en tiempo real?',
            'a' => 'Depende del sistema. Cuando la plataforma soporta webhooks, es casi en tiempo real; cuando no, sincronizamos por intervalos. Lo definimos según lo que tu operación necesite.',
        ],
        [
            'q' => '¿Cuánto cuesta y en cuánto tiempo?',
            'a' => 'Una integración a la medida arranca desde aproximadamente USD 800 (~$3.200.000 COP) por integración y varía según los sistemas y el volumen de datos. Cotizamos por puente: empezamos por el que más horas te está costando.',
        ],
    ];

    $capacidades = [
        ['icon' => 'plug', 'title' => 'Conectamos lo que ya usas', 'desc' => 'ERP, CRM, e-commerce, POS, pasarelas de pago y cualquier servicio con API. No cambias de herramienta: las unimos.'],
        ['icon' => 'shield', 'title' => 'Facturación DIAN y SIIGO', 'desc' => 'Que la venta de tu tienda o ERP genere la factura electrónica y quede en tu contabilidad, sin volver a digitar.'],
        ['icon' => 'scan', 'title' => 'Sincronización en dos vías', 'desc' => 'Inventario, precios, pedidos y clientes sincronizados entre plataformas, en ambos sentidos y en tiempo real.'],
        ['icon' => 'mail', 'title' => 'WhatsApp y Google', 'desc' => 'Conectamos WhatsApp, Gmail, Drive y Calendar para automatizar avisos, documentos y agenda.'],
        ['icon' => 'doc', 'title' => 'Webhooks y colas', 'desc' => 'Integraciones robustas con reintentos, colas y registro de cada evento. Si algo falla, se recupera solo.'],
        ['icon' => 'summary', 'title' => 'Trazabilidad total', 'desc' => 'Cada dato que se mueve queda registrado: sabes qué se sincronizó, cuándo y con qué resultado.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Mapeamos tus sistemas', 'desc' => 'Listamos las plataformas que usas y dónde se está pasando información a mano hoy.'],
        ['num' => '02', 'title' => 'Diseñamos las integraciones', 'desc' => 'Definimos qué datos fluyen, en qué dirección y con qué reglas, para evitar duplicados y conflictos.'],
        ['num' => '03', 'title' => 'Construimos y conectamos', 'desc' => 'Desarrollamos las APIs y los puentes, con webhooks, colas y validaciones.'],
        ['num' => '04', 'title' => 'En producción y monitoreado', 'desc' => 'Queda sincronizando solo. Monitoreamos los eventos y resolvemos cualquier caso borde.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-int-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-int-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Integración · APIs
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Que tus sistemas <span class="text-mt-accent">hablen entre sí</span>, sin copiar y pegar.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Conectamos las plataformas que ya usas &mdash;ERP, CRM, e-commerce, pagos, facturación DIAN y SIIGO, WhatsApp y Google&mdash; con integraciones y APIs a la medida. Los datos fluyen solos entre tus sistemas, en ambos sentidos.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'ERP, CRM, e-commerce, pagos, DIAN/SIIGO, WhatsApp y Google',
                        'Sincronización en ambos sentidos, con webhooks y colas',
                        'Se acaba el trabajo manual de pasar datos de un lado a otro',
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
                        Cotizar mi integración
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>
            </div>

            {{-- Mockup: diagrama de integración --}}
            <div class="flex justify-center lg:justify-end" data-animate>
                <div class="mt-int-diagram">
                    <div class="mt-int-diagram-head">
                        <div>
                            <span class="mt-int-diagram-badge">Sistemas conectados</span>
                            <p class="mt-int-diagram-title">Tu operación</p>
                        </div>
                        <span class="mt-int-status">
                            <span class="mt-int-status-dot" aria-hidden="true"></span>
                            Sincronizando
                        </span>
                    </div>

                    <div class="mt-int-diagram-body">
                        <div class="mt-int-center">Tu operación</div>

                        <div class="mt-int-nodes">
                            @foreach (['ERP', 'Pagos', 'DIAN / SIIGO', 'WhatsApp', 'Google'] as $node)
                                <span class="mt-int-node">{{ $node }}</span>
                            @endforeach
                        </div>

                        <div class="mt-int-checks">
                            <span><span class="mt-int-check" aria-hidden="true">&#10003;</span> Pedido sincronizado</span>
                            <span><span class="mt-int-check" aria-hidden="true">&#10003;</span> Factura emitida</span>
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
            <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Trabajamos con</span>
            @foreach ([
                'APIs REST',
                'Webhooks',
                'SIIGO / DIAN',
                'Pasarelas de pago',
                'Google · WhatsApp',
            ] as $item)
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
                Tú no deberías ser el puente entre tus propios sistemas.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Alguien pasa a mano los pedidos de la web al ERP, y del ERP a la facturación. Cada plataforma tiene su propia verdad y el proceso depende de una persona, no del sistema.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Copiar y pegar sin fin', 'd' => 'Alguien pasa a mano los pedidos de la web al ERP, y del ERP a la facturación. Horas perdidas y errores.'],
                ['t' => 'Sistemas que no se hablan', 'd' => 'Cada plataforma tiene su propia verdad. El inventario de la web no coincide con el del ERP.'],
                ['t' => 'Procesos que se caen', 'd' => 'Cuando la persona que hacía el puente falta, el proceso se detiene. Depende de alguien, no del sistema.'],
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
                Un puente entre tus plataformas que trabaja solo, 24/7.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Integraciones a la medida que sincronizan tus datos entre sistemas, en ambos sentidos, con reintentos y trazabilidad.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($capacidades as $c)
                <div class="group rounded-2xl border border-mt-border bg-white p-6 transition-colors duration-300 hover:border-mt-accent" data-animate>
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                        @switch($c['icon'])
                            @case('mail')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6"/></svg>
                                @break
                            @case('doc')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8l-5-5z"/><path stroke-linecap="round" d="M9 13h6M9 17h4"/></svg>
                                @break
                            @case('scan')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V6a2 2 0 012-2h2M16 4h2a2 2 0 012 2v2M20 16v2a2 2 0 01-2 2h-2M8 20H6a2 2 0 01-2-2v-2"/><path stroke-linecap="round" d="M4 12h16"/></svg>
                                @break
                            @case('summary')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 10h16M4 14h10M4 18h6"/></svg>
                                @break
                            @case('shield')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 5-3.5 8.4-8 9-4.5-.6-8-4-8-9V6l8-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12l1.8 1.8L15 10"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7V4h6v3M9 7H7a2 2 0 00-2 2v3a4 4 0 004 4h6a4 4 0 004-4V9a2 2 0 00-2-2h-2M12 16v4"/></svg>
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
                    <h2 class="mt-4 text-section font-display text-mt-text">Empezamos por el puente que más horas te cuesta.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Mapeamos dónde estás copiando y pegando hoy, diseñamos el flujo de datos y lo dejamos sincronizando solo.
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
{{-- CASO REAL                                                     --}}
{{-- ============================================================= --}}
<section id="caso" class="relative py-28 md:py-36 bg-mt-bg-dark overflow-hidden scroll-mt-24">
    <div class="mt-container relative z-10">
        <div class="max-w-3xl" data-animate>
            <span class="font-mono text-[11px] md:text-xs uppercase tracking-[0.22em] text-mt-accent-on-dark">Caso real · en producción</span>
            <h2 class="mt-4 text-section font-display text-white">
                Facturación, contabilidad y DIAN, conectadas en un solo flujo.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para <strong class="text-white">CLC &amp; CIA</strong> integramos su facturación electrónica con <strong class="text-white">SIIGO</strong> y la <strong class="text-white">DIAN</strong>: la emisión, la validación y el registro contable quedaron en un solo flujo, eliminando el trabajo de pasar cada factura entre plataformas a mano.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $casoUrl }}" class="mt-btn-primary">
                        Ver el caso completo <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-ghost mt-btn-ghost-on-dark">
                        Quiero algo así
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 grid grid-cols-2 gap-4" data-animate>
                @foreach ([
                    ['k' => 'SIIGO', 'v' => 'Contabilidad'],
                    ['k' => 'DIAN', 'v' => 'Validación'],
                    ['k' => 'Un flujo', 'v' => 'Sin doble digitación'],
                    ['k' => 'API', 'v' => 'Sincronizado'],
                ] as $m)
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-5">
                        <div class="text-2xl md:text-3xl font-display font-semibold text-white leading-none tracking-tight">{{ $m['k'] }}</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.14em] text-mt-text-on-dark leading-snug">{{ $m['v'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- PRECIOS                                                       --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-10 items-center">
            <div data-animate>
                <span class="mt-eyebrow-gray">Inversión</span>
                <h2 class="mt-4 text-section font-display text-mt-text">Se paga con las horas que tu equipo deja de pasar datos a mano.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución que es <strong class="text-mt-text">tuya</strong>: tu código, integrada a tu operación, sin volver a digitar en portales ajenos. Cotizamos por puente para que veas resultado rápido.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Diagnóstico de tus sistemas y de los puentes manuales de hoy',
                        'Diseño de las integraciones y el flujo de datos',
                        'Desarrollo de las APIs con webhooks y colas',
                        'Monitoreo, trazabilidad y soporte',
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
                <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Proyecto a la medida</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-mt-text-2 text-lg">desde</span>
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;800</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $3.200.000 COP · por integración</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi integración
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende de los sistemas y el volumen de datos que necesites conectar.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- FAQ                                                           --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32" data-animate>
                    <span class="mt-eyebrow-gray">Preguntas frecuentes</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">Lo que más nos preguntan.</h2>
                    <p class="mt-6 text-mt-text-2 text-base leading-relaxed">
                        ¿Tienes otra duda sobre tus integraciones? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
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
    <div class="mt-int-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Deja de ser el puente entre tus propios sistemas.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos qué plataformas usas y dónde estás copiando y pegando, y te decimos cómo conectarlas. Sin compromiso.
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
{{-- SCHEMA: FAQPage (via head_extras para no duplicar el @graph)  --}}
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
    .mt-int-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-int-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup: diagrama de integración de sistemas */
    .mt-int-diagram {
        width: 100%; max-width: 400px;
        border-radius: 20px; background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-int-diagram-head {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;
        padding: 1.1rem 1.2rem; background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
    }
    .mt-int-diagram-badge {
        font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace; color: #6B7280;
    }
    .mt-int-diagram-title { margin-top: 0.2rem; font-size: 20px; font-weight: 700; color: #111827; letter-spacing: 0.02em; }
    .mt-int-status {
        display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap;
        font-size: 11px; font-weight: 600; color: #2563EB;
        background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.22);
        border-radius: 999px; padding: 0.3rem 0.6rem;
    }
    .mt-int-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
    .mt-int-diagram-body { padding: 1.15rem 1.2rem; }
    .mt-int-center {
        text-align: center; font-size: 14px; font-weight: 700; color: #fff;
        background: #111827; border-radius: 12px; padding: 0.65rem 1rem;
    }
    .mt-int-nodes {
        margin-top: 0.9rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.55rem;
    }
    .mt-int-node {
        position: relative; text-align: center; font-size: 12.5px; font-weight: 600; color: #374151;
        background: #F5F7FF; border: 1px solid rgba(37,99,235,0.16); border-radius: 10px;
        padding: 0.5rem 0.4rem;
    }
    .mt-int-node::before {
        content: ''; position: absolute; top: -0.55rem; left: 50%; width: 1px; height: 0.55rem;
        background: rgba(37,99,235,0.35);
    }
    .mt-int-checks { margin-top: 0.95rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem 0.75rem; }
    .mt-int-checks span { display: flex; align-items: center; gap: 0.4rem; font-size: 12px; color: #374151; line-height: 1.35; }
    .mt-int-check { color: #2563EB; font-weight: 700; flex-shrink: 0; }
</style>
@endpush

@push('scripts')
<script>
    /* Red de seguridad: si el observer de reveals de la home no corre en esta
       ruta, revelamos el contenido igual para no dejar nada invisible. */
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
