@extends('layouts.app-home')

{{--
    Landing: Desarrollo de software a la medida (SaaS, ERP, CRM, plataformas).
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = software-a-la-medida), editable desde /admin/seo/{id}/edit.
    Aquí solo va el CONTENIDO + el FAQPage (via head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, quiero desarrollar un software a la medida para mi empresa.');
    $waUrl = 'https://wa.me/'.$waNumber.'?text='.$waMsg;
    $casoUrl = url('/proyectos/talent-map-gestion-talento-nom035');

    // Plataformas reales en producción (enlace interno al caso = prueba + SEO)
    $plataformas = [
        ['nombre' => 'Talent Map', 'pais' => '🇲🇽', 'slug' => 'talent-map-gestion-talento-nom035', 'desc' => 'SaaS de gestión de talento (NOM-035)'],
        ['nombre' => 'Sinden', 'pais' => '🇨🇴', 'slug' => 'sinden-ordenes-produccion', 'desc' => 'ERP de órdenes y producción'],
        ['nombre' => 'CLC & CIA', 'pais' => '🇨🇴', 'slug' => 'clc-facturacion-electronica', 'desc' => 'Facturación electrónica DIAN'],
        ['nombre' => 'Lp(a)ction', 'pais' => '🇪🇸', 'slug' => 'lpaction', 'desc' => 'Plataforma de formación médica'],
        ['nombre' => 'Formula High Ticket', 'pais' => '🇦🇷', 'slug' => 'formula-high-ticket-crm-ventas-telefonicas', 'desc' => 'CRM para ventas telefónicas'],
        ['nombre' => 'Protección Laboral', 'pais' => '🇨🇴', 'slug' => 'proteccion-laboral', 'desc' => 'Software de gestión jurídica'],
    ];

    $faqs = [
        [
            'q' => '¿Qué es el software a la medida y en qué se diferencia de uno enlatado?',
            'a' => 'Es un sistema desarrollado específicamente para tu empresa, no un producto genérico al que tienes que adaptar tu forma de trabajar. Se ajusta a tu operación real, se integra con lo que ya usas, no tiene límites de licencia por usuario o módulo, y el código y los datos son tuyos.',
        ],
        [
            'q' => '¿Qué tipos de software desarrollan?',
            'a' => 'Plataformas SaaS multi-tenant, ERPs y paneles administrativos, CRMs y pipelines de ventas, marketplaces y plataformas de dos lados, portales de cliente/proveedor, automatizaciones e integraciones. En Laravel, con React o Vue y paneles administrativos robustos.',
        ],
        [
            'q' => '¿Se integra con lo que ya uso (facturación, ERPs, WhatsApp, pagos)?',
            'a' => 'Sí. Conectamos tu software con facturación electrónica (DIAN, SIIGO), pasarelas de pago, WhatsApp, Google Calendar, ERPs y cualquier API. Justamente eliminar el trabajo manual entre sistemas es una de las mayores ganancias del software a la medida.',
        ],
        [
            'q' => '¿El código y los datos son míos?',
            'a' => 'Sí. A diferencia del software enlatado por suscripción, con una solución a la medida eres dueño del código y de los datos. No dependes de los planes ni de las decisiones de un proveedor externo, y puedes escalar o modificar el sistema cuando quieras.',
        ],
        [
            'q' => '¿Cuánto cuesta desarrollar un software a la medida?',
            'a' => 'Depende del alcance y los módulos, pero un software a la medida arranca desde aproximadamente USD 1.500 (~$6.000.000 COP) para un panel o MVP, y escala según funcionalidades, usuarios e integraciones. En MY Tech Solutions cotizamos por fases, sin sorpresas.',
        ],
        [
            'q' => '¿Cómo trabajan y en cuánto tiempo veo resultados?',
            'a' => 'Trabajamos por fases con previews semanales: descubrimos tu operación, diseñamos la arquitectura, construimos con entregas continuas y lanzamos con capacitación y soporte. Sueles ver una primera versión funcionando en pocas semanas y luego iteramos con más módulos.',
        ],
    ];

    $tipos = [
        ['icon' => 'cloud', 'title' => 'Plataformas SaaS', 'desc' => 'Multi-tenant por suscripción, con múltiples empresas, roles, planes y pagos recurrentes.'],
        ['icon' => 'dashboard', 'title' => 'ERP y paneles administrativos', 'desc' => 'Inventarios, facturación, órdenes, reportes y roles, hechos a la operación de tu empresa.'],
        ['icon' => 'funnel', 'title' => 'CRM y pipelines de ventas', 'desc' => 'Gestiona leads, clientes y ventas con el flujo real de tu equipo, no con uno impuesto.'],
        ['icon' => 'network', 'title' => 'Marketplaces y plataformas', 'desc' => 'Plataformas de dos lados, portales y comunidades que conectan usuarios y transacciones.'],
        ['icon' => 'plug', 'title' => 'Automatizaciones e integraciones', 'desc' => 'Conectamos tus sistemas (DIAN, WhatsApp, pagos, APIs) y eliminamos el trabajo manual.'],
        ['icon' => 'chart', 'title' => 'Portales y dashboards', 'desc' => 'Reportes en vivo, tableros de control y portales de cliente o proveedor.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Descubrimos', 'desc' => 'Entendemos tu operación, objetivos y procesos. Salimos con un plan técnico claro y sin ambigüedades.'],
        ['num' => '02', 'title' => 'Diseñamos', 'desc' => 'Arquitectura, stack e integraciones + UX, validado contigo antes de escribir una línea de código.'],
        ['num' => '03', 'title' => 'Construimos', 'desc' => 'Desarrollo iterativo con previews semanales. Código limpio, documentado y probado. Sin sorpresas.'],
        ['num' => '04', 'title' => 'Lanzamos y soportamos', 'desc' => 'Deploy, capacitación y soporte continuo. Iteramos con mejoras y nuevos módulos cuando los necesites.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-sw-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-sw-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Software a medida · SaaS · ERP · CRM
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Software a la medida que se <span class="text-mt-accent">ajusta a tu empresa</span>, no al revés.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Diseñamos y desarrollamos plataformas SaaS, ERPs, CRMs y sistemas a la medida sobre Laravel. Automatizamos tu operación, integramos lo que ya usas y te damos software que escala &mdash; con el código y los datos de tu lado.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Hecho a tu proceso real, no a una plantilla enlatada',
                        'Se integra con facturación, pagos, WhatsApp y tus sistemas',
                        'El código y los datos son tuyos, sin límites de licencia',
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
                        Cotizar mi software
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#plataformas" class="mt-btn-ghost">
                        Ver plataformas reales
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-mt-border grid grid-cols-3 gap-x-6 sm:gap-x-10 gap-y-6 max-w-lg" data-animate>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight flex items-baseline">+<span data-counter="37" data-counter-decimals="0" aria-label="37">0</span></div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Proyectos entregados</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight flex items-baseline">+<span data-counter="11" data-counter-decimals="0" aria-label="11">0</span></div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Países</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight">100%</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">A tu medida</div>
                    </div>
                </div>
            </div>

            {{-- Mockup de dashboard / panel SaaS --}}
            <div class="relative flex justify-center lg:justify-end" data-animate>
                <div class="mt-sw-window">
                    <div class="mt-sw-window-bar">
                        <span class="mt-sw-dot" style="background:#ff5f57"></span>
                        <span class="mt-sw-dot" style="background:#febc2e"></span>
                        <span class="mt-sw-dot" style="background:#28c840"></span>
                        <span class="mt-sw-urlbar">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg>
                            panel.tuempresa.com
                        </span>
                    </div>
                    <div class="mt-sw-app">
                        <aside class="mt-sw-side" aria-hidden="true">
                            <span class="mt-sw-logo"></span>
                            <span class="mt-sw-nav is-active"></span>
                            <span class="mt-sw-nav"></span>
                            <span class="mt-sw-nav"></span>
                            <span class="mt-sw-nav"></span>
                        </aside>
                        <div class="mt-sw-main">
                            <div class="mt-sw-kpis">
                                <div class="mt-sw-kpi">
                                    <span class="mt-sw-kpi-label">Ventas</span>
                                    <span class="mt-sw-kpi-val">$48.2M</span>
                                </div>
                                <div class="mt-sw-kpi">
                                    <span class="mt-sw-kpi-label">Órdenes</span>
                                    <span class="mt-sw-kpi-val">1.284</span>
                                </div>
                                <div class="mt-sw-kpi">
                                    <span class="mt-sw-kpi-label">Activos</span>
                                    <span class="mt-sw-kpi-val">96%</span>
                                </div>
                            </div>
                            <div class="mt-sw-chart" aria-hidden="true">
                                <span style="height:38%"></span>
                                <span style="height:58%"></span>
                                <span style="height:46%"></span>
                                <span style="height:72%"></span>
                                <span style="height:63%"></span>
                                <span style="height:88%"></span>
                                <span style="height:78%"></span>
                            </div>
                            <div class="mt-sw-rows" aria-hidden="true">
                                <span></span><span></span><span></span>
                            </div>
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
            <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Stack</span>
            @foreach (['Laravel', 'React · Vue', 'Filament', 'MySQL', 'APIs REST', 'Multi-tenant'] as $item)
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
                Tu empresa creció. Tus hojas de cálculo, no.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Los Excel se rompen, el software enlatado te obliga a trabajar como no trabajas, y cada sistema vive en su isla. El resultado: tareas manuales, errores, información dispersa y decisiones a ciegas.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Excel que ya no da más', 'd' => 'Se rompen, se duplican y nadie sabe cuál es la versión buena. No escalan con tu operación.'],
                ['t' => 'Software enlatado que no encaja', 'd' => 'Pagas por módulos que no usas y te falta justo lo que tu negocio necesita.'],
                ['t' => 'Sistemas que no se hablan', 'd' => 'Copias y pegas entre herramientas, con errores y horas perdidas cada semana.'],
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
{{-- QUÉ CONSTRUIMOS                                               --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-14" data-animate>
            <span class="mt-eyebrow">La solución</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                El software que tu operación necesita, sea cual sea.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Desde un panel administrativo hasta una plataforma SaaS completa. Construimos exactamente lo que tu empresa necesita para operar y crecer.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($tipos as $c)
                <div class="group rounded-2xl border border-mt-border bg-white p-6 transition-colors duration-300 hover:border-mt-accent" data-animate>
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                        @switch($c['icon'])
                            @case('cloud')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 18a4 4 0 01-.5-7.97 5.5 5.5 0 0110.6-1.06A4.5 4.5 0 0117 18H7z"/></svg>
                                @break
                            @case('dashboard')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                                @break
                            @case('funnel')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18l-7 8v6l-4-2v-4L3 5z"/></svg>
                                @break
                            @case('network')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="6" cy="6" r="2.2"/><circle cx="18" cy="6" r="2.2"/><circle cx="12" cy="18" r="2.2"/><path stroke-linecap="round" d="M7.7 7.5L11 15.5M16.3 7.5L13 15.5M8 6h8"/></svg>
                                @break
                            @case('chart')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 3L4 14h6l-1 7 9-11h-6l1-7z"/></svg>
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
                    <span class="mt-eyebrow-gray">Cómo trabajamos</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">De un problema real a software en producción.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Cuatro fases, comunicación constante y entregables claros en cada paso.
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
{{-- CASO REAL + PLATAFORMAS                                       --}}
{{-- ============================================================= --}}
<section id="plataformas" class="relative py-28 md:py-36 bg-mt-bg-dark overflow-hidden scroll-mt-24">
    <div class="mt-container relative z-10">
        <div class="max-w-3xl" data-animate>
            <span class="font-mono text-[11px] md:text-xs uppercase tracking-[0.22em] text-mt-accent-on-dark">Plataformas reales · en producción</span>
            <h2 class="mt-4 text-section font-display text-white">
                Software que empresas ya usan todos los días.
            </h2>
            <p class="mt-6 text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                Un ejemplo: <strong class="text-white">Talent Map</strong> (México) es una plataforma SaaS de gestión de talento humano que desarrollamos a la medida, con múltiples paneles y cumplimiento de la NOM-035, hoy en producción.
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

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-4" data-animate>
            @foreach ($plataformas as $t)
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
            <h2 class="mt-4 text-section font-display text-mt-text">El software debe adaptarse a ti, no tú a él.</h2>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-mt-border" data-animate>
            <table class="w-full min-w-[640px] text-left border-collapse">
                <thead>
                    <tr class="bg-mt-bg-2 text-mt-text">
                        <th class="p-4 md:p-5 font-mono text-[11px] uppercase tracking-[0.14em] text-mt-text-3 font-medium"></th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Excel / manual</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Software enlatado</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-accent-line bg-mt-accent-soft text-mt-accent">Software a la medida</th>
                    </tr>
                </thead>
                <tbody class="text-[14.5px]">
                    @foreach ([
                        'Se ajusta a tu proceso real',
                        'Dueño del código y los datos',
                        'Escala sin límites de licencia',
                        'Se integra con lo que ya usas',
                        'Automatiza y reduce errores',
                        'Soporte de quien lo construyó',
                    ] as $i => $fila)
                        <tr class="{{ $i % 2 ? 'bg-mt-bg-2/50' : 'bg-white' }} border-t border-mt-border">
                            <td class="p-4 md:p-5 text-mt-text font-medium">{{ $fila }}</td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ in_array($i, [0]) ? '≈' : '✕' }}</span>
                            </td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ in_array($i, [1, 4]) ? '≈' : '✕' }}</span>
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
                <h2 class="mt-4 text-section font-display text-mt-text">Por fases, sin sorpresas y con código tuyo.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Empiezas con lo esencial &mdash; un panel o un MVP &mdash; y escalas con más módulos cuando el negocio lo pida. Cotizamos por fases y con precios claros. El software es <strong class="text-mt-text">tuyo</strong>.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Descubrimiento y arquitectura a tu operación',
                        'Desarrollo por fases con previews semanales',
                        'Integraciones con tus sistemas y APIs',
                        'Capacitación, soporte y evolución continua',
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
                <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Software a la medida</span>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-mt-text-2 text-lg">desde</span>
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;1.500</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $6.000.000 COP · por fases</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi proyecto
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende del alcance, los módulos y las integraciones.
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
    <div class="mt-sw-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Deja de pelear con tus herramientas. Ten el software que tu empresa merece.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos qué necesita tu operación y te proponemos cómo resolverlo con software a la medida. Sin compromiso.
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
    .mt-sw-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 80% 6%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 6% 22%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-sw-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Ventana de navegador / dashboard SaaS */
    .mt-sw-window {
        width: 100%; max-width: 420px;
        border-radius: 18px; background: #fff; border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-sw-window-bar {
        display: flex; align-items: center; gap: 7px;
        padding: 0.7rem 0.85rem; background: #F3F4F6; border-bottom: 1px solid #E5E7EB;
    }
    .mt-sw-dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
    .mt-sw-urlbar {
        margin-left: 0.5rem; flex: 1;
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #E5E7EB; border-radius: 8px;
        padding: 0.28rem 0.6rem; font-size: 12px; color: #6B7280;
        font-family: 'JetBrains Mono', monospace;
    }
    .mt-sw-app { display: flex; background: #F9FAFB; min-height: 300px; }
    .mt-sw-side {
        width: 62px; flex-shrink: 0; background: #0B1220;
        display: flex; flex-direction: column; align-items: center; gap: 12px;
        padding: 16px 0;
    }
    .mt-sw-logo { width: 26px; height: 26px; border-radius: 8px; background: #2563EB; margin-bottom: 6px; }
    .mt-sw-nav { width: 30px; height: 6px; border-radius: 3px; background: rgba(255,255,255,0.14); }
    .mt-sw-nav.is-active { background: #60A5FA; width: 34px; }
    .mt-sw-main { flex: 1; padding: 18px; display: flex; flex-direction: column; gap: 14px; }
    .mt-sw-kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .mt-sw-kpi { background: #fff; border: 1px solid #E5E7EB; border-radius: 10px; padding: 10px; }
    .mt-sw-kpi-label { display: block; font-size: 10px; color: #9CA3AF; font-family: 'JetBrains Mono', monospace; text-transform: uppercase; letter-spacing: 0.06em; }
    .mt-sw-kpi-val { display: block; font-size: 17px; font-weight: 700; color: #1F2937; margin-top: 3px; }
    .mt-sw-chart {
        background: #fff; border: 1px solid #E5E7EB; border-radius: 10px; padding: 14px;
        display: flex; align-items: flex-end; gap: 8px; height: 110px;
    }
    .mt-sw-chart span { flex: 1; background: linear-gradient(180deg, #60A5FA, #2563EB); border-radius: 4px 4px 0 0; }
    .mt-sw-rows { display: flex; flex-direction: column; gap: 7px; }
    .mt-sw-rows span { height: 10px; border-radius: 4px; background: #EEF2F7; }
    .mt-sw-rows span:nth-child(1) { width: 100%; }
    .mt-sw-rows span:nth-child(2) { width: 80%; }
    .mt-sw-rows span:nth-child(3) { width: 60%; }
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
