@extends('layouts.app-home')

{{--
    Landing: Software ERP a la Medida.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = software-erp-a-la-medida), editable desde
    /admin/seo/{id}/edit. Aquí solo va el CONTENIDO + el FAQPage (head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, necesito un ERP a la medida para mi empresa.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/manzer-erp-gestion-integral-agroforestal');

    $faqs = [
        [
            'q' => '¿En qué se diferencia de un ERP enlatado como SAP o Siigo?',
            'a' => 'Un ERP enlatado te obliga a adaptar tu operación a su forma de trabajar y a pagar licencias por usuario y por módulo. El nuestro se construye sobre cómo funciona tu negocio: tienes justo los módulos que necesitas, el código es tuyo y no pagas licencias recurrentes por cada persona que lo usa.',
        ],
        [
            'q' => '¿Se integra con la facturación electrónica de la DIAN?',
            'a' => 'Sí. La facturación electrónica sale del mismo ERP: emites factura de venta, notas y documento soporte sin salir a otro portal. También integramos SIIGO si ya lo usas para tu contabilidad.',
        ],
        [
            'q' => '¿Qué módulos puede tener?',
            'a' => 'Inventario y kardex, ventas y cotizaciones, compras y proveedores, cartera y crédito, listas de precios, producción y órdenes de trabajo, contabilidad y reportes. Armamos el alcance según tu operación; no pagas por lo que no usas.',
        ],
        [
            'q' => '¿Puedo migrar la información que ya tengo?',
            'a' => 'Sí. Importamos tus productos, clientes, saldos e inventario desde Excel o desde tu sistema actual, para que arranques con tu información real y no desde cero.',
        ],
        [
            'q' => '¿Empezamos con todo o por partes?',
            'a' => 'Por partes. Arrancamos con el módulo que más te duele —casi siempre inventario y ventas—, lo dejamos funcionando y desde ahí ampliamos. Ves resultado antes de invertir en todo.',
        ],
        [
            'q' => '¿Cuánto cuesta y en cuánto tiempo?',
            'a' => 'Un ERP a la medida arranca desde aproximadamente USD 1.500 (~$6.000.000 COP) y escala según los módulos e integraciones. Cotizamos por fases para que el núcleo esté operando en semanas, no en meses.',
        ],
    ];

    $capacidades = [
        ['icon' => 'doc', 'title' => 'Inventario y kardex', 'desc' => 'Control de stock con variantes, kardex, mínimos y alertas. Sabes qué tienes y qué se mueve, al instante.'],
        ['icon' => 'summary', 'title' => 'Ventas y cotizaciones', 'desc' => 'Cotizaciones que se vuelven pedido y ajustan stock. Listas de precios por cliente y canal.'],
        ['icon' => 'shield', 'title' => 'Cartera y crédito', 'desc' => 'Cupos, abonos y estados de cuenta. Sabes quién te debe y cuánto, sin buscar en papeles.'],
        ['icon' => 'scan', 'title' => 'Compras y proveedores', 'desc' => 'Órdenes de compra, recepción y costos actualizados que alimentan el inventario solo.'],
        ['icon' => 'plug', 'title' => 'Integrado con la DIAN', 'desc' => 'La facturación electrónica sale del mismo ERP, sin volver a digitar en otro portal.'],
        ['icon' => 'mail', 'title' => 'Reportes y roles', 'desc' => 'Indicadores en tiempo real y permisos por rol: cada quien ve lo que le toca.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Mapeamos tu operación', 'desc' => 'Revisamos cómo compras, vendes, cobras y produces hoy, y qué módulos necesitas de verdad.'],
        ['num' => '02', 'title' => 'Construimos el núcleo', 'desc' => 'Empezamos por el módulo que más te duele —normalmente inventario y ventas— y lo dejamos funcionando.'],
        ['num' => '03', 'title' => 'Integramos y automatizamos', 'desc' => 'Conectamos la facturación DIAN, tus reportes y las herramientas que ya usas.'],
        ['num' => '04', 'title' => 'En producción y creciendo', 'desc' => 'Queda operando con tu equipo. Ampliamos a compras, cartera, producción y contabilidad por fases.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-erp-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-erp-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        ERP · Gestión empresarial
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Un ERP a la medida que se ajusta a <span class="text-mt-accent">tu operación</span>, no al revés.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Desarrollamos ERPs a la medida en Laravel que unifican inventario, ventas, compras, cartera, producción y contabilidad en una sola plataforma. Se integra con la facturación DIAN, con lo que ya usas, y escala contigo. No pagas licencias por usuario.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Inventario, ventas, compras, cartera y producción en un solo lugar',
                        'Integrado con la facturación electrónica de la DIAN',
                        'Reportes en tiempo real y accesos por rol',
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
                        Cotizar mi ERP a la medida
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>
            </div>

            {{-- Mockup: panel de ERP --}}
            <div class="flex justify-center lg:justify-end" data-animate>
                <div class="mt-erp-panel">
                    <div class="mt-erp-panel-head">
                        <div>
                            <span class="mt-erp-panel-badge">Panel ERP</span>
                            <p class="mt-erp-panel-title">Operación general</p>
                        </div>
                        <span class="mt-erp-status">
                            <span class="mt-erp-status-dot" aria-hidden="true"></span>
                            En línea
                        </span>
                    </div>

                    <div class="mt-erp-panel-body">
                        <div class="mt-erp-kpis">
                            <div class="mt-erp-kpi">
                                <span class="mt-erp-kpi-label">Ventas del mes</span>
                                <span class="mt-erp-kpi-val">$ 38.400.000</span>
                            </div>
                            <div class="mt-erp-kpi">
                                <span class="mt-erp-kpi-label">Stock bajo</span>
                                <span class="mt-erp-kpi-val">12</span>
                            </div>
                            <div class="mt-erp-kpi">
                                <span class="mt-erp-kpi-label">Cartera</span>
                                <span class="mt-erp-kpi-val">$ 9.1M</span>
                            </div>
                        </div>

                        <div class="mt-erp-modules">
                            @foreach (['Inventario', 'Ventas', 'Compras', 'Cartera', 'Producción', 'Contabilidad'] as $mod)
                                <span class="mt-erp-module"><span class="mt-erp-check" aria-hidden="true">&#10003;</span> {{ $mod }}</span>
                            @endforeach
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
            <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Construido con</span>
            @foreach ([
                'Laravel',
                'Inventario y Kardex',
                'Facturación DIAN',
                'Reportes',
                'Tu operación',
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
                Tu información no debería vivir en diez planillas distintas.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Inventario en un Excel, ventas en otro, cartera en un cuaderno y compras en la cabeza de alguien. A fin de mes nada cuadra y armar el informe de gerencia toma días.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Islas de información', 'd' => 'El inventario está en un Excel, las ventas en otro y la cartera en un cuaderno. Nada cuadra a fin de mes.'],
                ['t' => 'Software enlatado que no encaja', 'd' => 'Pagas por módulos que no usas y te falta justo el que tu negocio necesita.'],
                ['t' => 'Reportes a mano', 'd' => 'Armar el informe de gerencia toma días de copiar y pegar entre planillas.'],
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
                Toda tu operación en una sola plataforma que es tuya.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Un ERP construido sobre cómo funciona tu negocio: los módulos que necesitas, integrados entre sí y con la DIAN, sin licencias por usuario.
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
                    <h2 class="mt-4 text-section font-display text-mt-text">Empezamos por el módulo que más te duele.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        No montamos todo de golpe. Arrancamos con inventario y ventas, lo dejamos funcionando y ampliamos por fases.
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
                Un ERP que gestiona toda una operación agroforestal.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para <strong class="text-white">Manzer Agroforestal</strong> desarrollamos un ERP a la medida que integra la gestión completa del negocio: inventario, operaciones, clientes y procesos internos en una sola plataforma, con más de 90 tablas modeladas sobre la operación real de la empresa.
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
                    ['k' => '90+', 'v' => 'Tablas modeladas'],
                    ['k' => 'Un panel', 'v' => 'Toda la operación'],
                    ['k' => 'Por rol', 'v' => 'Accesos y permisos'],
                    ['k' => 'A medida', 'v' => 'Sobre su proceso'],
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
                <h2 class="mt-4 text-section font-display text-mt-text">Un ERP se paga con el orden que le pone a tu negocio.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución que es <strong class="text-mt-text">tuya</strong>: tu código, sin licencias por usuario, cotizamos por módulos.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Diagnóstico de tu operación y de los módulos que necesitas',
                        'Núcleo de inventario y ventas funcionando',
                        'Integración con la facturación electrónica DIAN',
                        'Reportes, roles y soporte para tu equipo',
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
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;1.500</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $6.000.000 COP · por módulos</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi ERP a la medida
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende de los módulos e integraciones que necesites.
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
                        ¿Tienes otra duda sobre tu ERP a la medida? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
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
    <div class="mt-erp-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Deja de perseguir la información entre planillas.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos cómo opera tu empresa y te decimos qué ERP a la medida necesitas. Sin compromiso.
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
    .mt-erp-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-erp-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup: panel de ERP */
    .mt-erp-panel {
        width: 100%; max-width: 400px;
        border-radius: 20px; background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-erp-panel-head {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;
        padding: 1.1rem 1.2rem; background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
    }
    .mt-erp-panel-badge {
        font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace; color: #6B7280;
    }
    .mt-erp-panel-title { margin-top: 0.2rem; font-size: 20px; font-weight: 700; color: #111827; letter-spacing: 0.02em; }
    .mt-erp-status {
        display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap;
        font-size: 11px; font-weight: 600; color: #047857;
        background: rgba(5,150,105,0.08); border: 1px solid rgba(5,150,105,0.22);
        border-radius: 999px; padding: 0.3rem 0.6rem;
    }
    .mt-erp-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #10B981; box-shadow: 0 0 0 3px rgba(16,185,129,0.15); }
    .mt-erp-panel-body { padding: 1.15rem 1.2rem; }
    .mt-erp-kpis { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.6rem; }
    .mt-erp-kpi {
        display: flex; flex-direction: column; gap: 0.3rem;
        padding: 0.6rem 0.65rem; border-radius: 12px; background: #F5F7FF; border: 1px solid rgba(37,99,235,0.14);
    }
    .mt-erp-kpi-label { font-size: 10.5px; color: #6B7280; line-height: 1.25; }
    .mt-erp-kpi-val { font-size: 13.5px; font-weight: 700; color: #111827; line-height: 1.25; }
    .mt-erp-modules { margin-top: 0.95rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem 0.75rem; }
    .mt-erp-module { display: flex; align-items: center; gap: 0.4rem; font-size: 12px; color: #374151; line-height: 1.35; }
    .mt-erp-check { color: #2563EB; font-weight: 700; flex-shrink: 0; }
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
