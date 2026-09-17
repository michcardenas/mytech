@extends('layouts.app-home')

{{--
    Landing: Software de Facturación Electrónica DIAN.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = software-facturacion-electronica-dian), editable desde
    /admin/seo/{id}/edit. Aquí solo va el CONTENIDO + el FAQPage (head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, necesito facturación electrónica DIAN a la medida para mi empresa.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/clc-facturacion-electronica');

    $faqs = [
        [
            'q' => '¿El software queda homologado por la DIAN?',
            'a' => 'Sí. Desarrollamos e integramos la facturación electrónica siguiendo la resolución vigente de la DIAN: emisión de facturas de venta, notas crédito y débito y documento soporte en formato XML UBL 2.1, generación del CUFE, firma y validación ante la DIAN, y representación gráfica en PDF con su código QR. Trabajamos sobre un proveedor tecnológico ya autorizado o sobre tu habilitación, según lo que más te convenga.',
        ],
        [
            'q' => '¿Se integra con SIIGO y con el software que ya uso?',
            'a' => 'Sí. Conectamos la facturación con SIIGO, con tu ERP, tu e-commerce o tu punto de venta mediante API, para que factures desde donde ya trabajas sin volver a digitar. Para CLC & CIA integramos la emisión con SIIGO y la DIAN; para Great Baby construimos un motor de exportación a SIIGO. La factura se genera, se valida y se envía sola.',
        ],
        [
            'q' => '¿Qué documentos electrónicos cubre?',
            'a' => 'Factura electrónica de venta, notas crédito y notas débito, y documento soporte en adquisiciones a no obligados a facturar. Cada documento se emite en XML UBL 2.1, se valida ante la DIAN y se entrega al adquiriente en PDF con QR y CUFE. Si tu operación necesita nómina electrónica o documentos de exportación, lo evaluamos en el alcance.',
        ],
        [
            'q' => '¿Sirve para facturación de exportación?',
            'a' => 'Sí. Es justo el caso de CLC & CIA, una comercializadora de zona franca: su plataforma resuelve la facturación electrónica de exportación con SIIGO y la DIAN, unificando plantillas de factura que antes vivían dispersas. La facturación de exportación tiene reglas propias y las contemplamos en el desarrollo.',
        ],
        [
            'q' => '¿Qué pasa si la DIAN rechaza un documento?',
            'a' => 'El sistema valida antes de enviar y te muestra el estado real de cada documento: aceptado, rechazado o pendiente, con el motivo devuelto por la DIAN. Si algo se rechaza, sabes exactamente qué corregir y puedes reenviar. Nada se queda en un limbo silencioso.',
        ],
        [
            'q' => '¿Cuánto cuesta y en cuánto tiempo queda listo?',
            'a' => 'Un desarrollo o integración de facturación electrónica a la medida arranca desde aproximadamente USD 750 (~$3.000.000 COP) y varía según las integraciones (SIIGO, ERP, e-commerce) y los tipos de documento. Cotizamos por fases: primero dejamos emitiendo la factura de venta y desde ahí ampliamos a notas y documento soporte.',
        ],
    ];

    $capacidades = [
        ['icon' => 'doc', 'title' => 'Emisión en XML UBL 2.1', 'desc' => 'Factura de venta, notas crédito y débito y documento soporte en el formato exacto que exige la DIAN, con su CUFE.'],
        ['icon' => 'shield', 'title' => 'Validación ante la DIAN', 'desc' => 'Cada documento se valida antes de entregarlo. Ves el estado real: aceptado, rechazado o pendiente, con el motivo.'],
        ['icon' => 'plug', 'title' => 'Integrado con SIIGO', 'desc' => 'Conectamos la emisión con SIIGO y tu contabilidad para que no vuelvas a digitar la misma factura dos veces.'],
        ['icon' => 'scan', 'title' => 'PDF con QR y CUFE', 'desc' => 'Representación gráfica lista para el adquiriente: PDF con código QR, CUFE y tu marca, enviado por correo automáticamente.'],
        ['icon' => 'mail', 'title' => 'Envío automático', 'desc' => 'La factura se genera, se valida y se envía al cliente sola, desde el mismo sistema donde ya trabajas.'],
        ['icon' => 'summary', 'title' => 'Facturación de exportación', 'desc' => 'Reglas propias de zona franca y exportación contempladas, como en la plataforma de CLC & CIA.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Revisamos tu operación y tu habilitación', 'desc' => 'Vemos cómo facturas hoy, qué documentos necesitas y si vamos sobre tu habilitación DIAN o sobre un proveedor autorizado.'],
        ['num' => '02', 'title' => 'Conectamos tu facturación y SIIGO', 'desc' => 'Integramos la emisión con SIIGO, tu ERP, e-commerce o punto de venta por API, para que factures desde donde ya trabajas.'],
        ['num' => '03', 'title' => 'Emisión, validación y entrega', 'desc' => 'Dejamos emitiendo XML UBL 2.1 con CUFE, validando ante la DIAN y entregando el PDF con QR al adquiriente.'],
        ['num' => '04', 'title' => 'En producción y con soporte', 'desc' => 'Queda operando dentro de tu sistema. Monitoreamos estados, resolvemos rechazos y ampliamos a más documentos.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-fe-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-fe-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Facturación electrónica · DIAN
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Software de facturación electrónica <span class="text-mt-accent">DIAN</span>, dentro de tu operación.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Desarrollamos e integramos facturación electrónica homologada por la DIAN dentro del software que ya usas: emisión en XML UBL 2.1, CUFE, validación en tiempo real e integración con SIIGO. No es una plantilla genérica: se ajusta a cómo factura tu empresa.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Facturas, notas crédito y débito y documento soporte',
                        'Validación ante la DIAN con el estado real de cada documento',
                        'Integrado con SIIGO, tu ERP, e-commerce o punto de venta',
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
                        Cotizar mi facturación DIAN
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>
            </div>

            {{-- Mockup: factura electrónica validada --}}
            <div class="flex justify-center lg:justify-end" data-animate>
                <div class="mt-fe-doc">
                    <div class="mt-fe-doc-head">
                        <div>
                            <span class="mt-fe-doc-badge">Factura electrónica de venta</span>
                            <p class="mt-fe-doc-num">FE&nbsp;1042</p>
                        </div>
                        <span class="mt-fe-status">
                            <span class="mt-fe-status-dot" aria-hidden="true"></span>
                            Aceptada por la DIAN
                        </span>
                    </div>

                    <div class="mt-fe-doc-body">
                        <div class="mt-fe-row"><span>Adquiriente</span><strong>Comercial Andina S.A.S.</strong></div>
                        <div class="mt-fe-row"><span>NIT</span><strong>900.482.117-3</strong></div>
                        <div class="mt-fe-row"><span>Total</span><strong>$ 4.760.000</strong></div>
                        <div class="mt-fe-cufe">
                            <span class="mt-fe-cufe-label">CUFE</span>
                            <span class="mt-fe-cufe-val">a1f9c3e2b7&hellip;8d4e0c</span>
                        </div>
                        <div class="mt-fe-checks">
                            <span><span class="mt-fe-check" aria-hidden="true">&#10003;</span> XML UBL 2.1 generado</span>
                            <span><span class="mt-fe-check" aria-hidden="true">&#10003;</span> Validado ante la DIAN</span>
                            <span><span class="mt-fe-check" aria-hidden="true">&#10003;</span> PDF con QR enviado</span>
                            <span><span class="mt-fe-check" aria-hidden="true">&#10003;</span> Registrado en SIIGO</span>
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
            <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Compatible con</span>
            @foreach ([
                'DIAN',
                'XML UBL 2.1',
                'SIIGO',
                'Laravel',
                'Tu ERP o e-commerce',
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
                Facturar no debería sacarte de tu sistema.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Vender en una plataforma y volver a digitar la factura en otra. Plantillas de factura regadas, documentos que se rechazan sin que nadie se entere, y un proveedor genérico que no entiende cómo factura tu negocio. Es tiempo perdido y riesgo con la DIAN.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Doble digitación', 'd' => 'Vendes en tu tienda o ERP y vuelves a teclear la factura en otro portal. La misma información, dos veces.'],
                ['t' => 'Rechazos silenciosos', 'd' => 'Un documento se rechaza ante la DIAN y te enteras tarde, cuando ya es un problema contable.'],
                ['t' => 'Plantillas dispersas', 'd' => 'Cada tipo de factura vive en un archivo distinto. Nadie tiene la versión correcta a la mano.'],
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
                Facturación electrónica que emite, valida y entrega sola.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Todo el ciclo del documento electrónico dentro del software que ya usas, cumpliendo la resolución de la DIAN y sin volver a digitar.
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
                    <h2 class="mt-4 text-section font-display text-mt-text">De cómo facturas hoy a facturar sin fricción.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Vamos por fases: primero dejamos emitiendo la factura de venta y desde ahí ampliamos a notas y documento soporte.
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
                Facturación electrónica de exportación con SIIGO y la DIAN.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para <strong class="text-white">CLC &amp; CIA</strong>, una comercializadora de zona franca, desarrollamos una plataforma de <strong class="text-white">facturación electrónica de exportación</strong> integrada con <strong class="text-white">SIIGO</strong> y la <strong class="text-white">DIAN</strong>. Resolvió el caos de plantillas de factura dispersas y dejó la emisión, validación y registro contable en un solo flujo.
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
                    ['k' => 'SIIGO', 'v' => 'Contabilidad integrada'],
                    ['k' => 'DIAN', 'v' => 'Validación electrónica'],
                    ['k' => 'Export.', 'v' => 'Reglas de zona franca'],
                    ['k' => 'UBL 2.1', 'v' => 'XML con CUFE'],
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
                <h2 class="mt-4 text-section font-display text-mt-text">Se paga con el tiempo que dejas de perder facturando.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución que es <strong class="text-mt-text">tuya</strong>: tu código, integrada a tu operación, sin volver a digitar en portales ajenos. Cotizamos por fases para que veas resultado rápido.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Emisión de factura de venta en XML UBL 2.1 con CUFE',
                        'Validación ante la DIAN y estado de cada documento',
                        'Integración con SIIGO, tu ERP, e-commerce o POS',
                        'Notas crédito/débito, documento soporte y soporte técnico',
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
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;750</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $3.000.000 COP · por fases</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi facturación DIAN
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende de las integraciones y los tipos de documento que necesites.
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
                        ¿Tienes otra duda sobre la DIAN o SIIGO? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
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
    <div class="mt-fe-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Deja de digitar facturas dos veces.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos cómo facturas hoy y te decimos cómo integrar la facturación electrónica DIAN dentro de tu sistema. Sin compromiso.
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
    .mt-fe-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-fe-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup: documento de factura electrónica */
    .mt-fe-doc {
        width: 100%; max-width: 400px;
        border-radius: 20px; background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-fe-doc-head {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;
        padding: 1.1rem 1.2rem; background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
    }
    .mt-fe-doc-badge {
        font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace; color: #6B7280;
    }
    .mt-fe-doc-num { margin-top: 0.2rem; font-size: 20px; font-weight: 700; color: #111827; letter-spacing: 0.02em; }
    .mt-fe-status {
        display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap;
        font-size: 11px; font-weight: 600; color: #047857;
        background: rgba(5,150,105,0.08); border: 1px solid rgba(5,150,105,0.22);
        border-radius: 999px; padding: 0.3rem 0.6rem;
    }
    .mt-fe-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #10B981; box-shadow: 0 0 0 3px rgba(16,185,129,0.15); }
    .mt-fe-doc-body { padding: 1.15rem 1.2rem; }
    .mt-fe-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.4rem 0; font-size: 13.5px; color: #6B7280; border-bottom: 1px dashed #EEF0F3; }
    .mt-fe-row strong { color: #111827; font-weight: 600; }
    .mt-fe-cufe {
        margin-top: 0.85rem; display: flex; align-items: center; gap: 0.6rem;
        padding: 0.55rem 0.7rem; border-radius: 10px; background: #F5F7FF; border: 1px solid rgba(37,99,235,0.16);
    }
    .mt-fe-cufe-label {
        font-size: 10px; font-weight: 700; letter-spacing: 0.12em; color: #2563EB;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }
    .mt-fe-cufe-val { font-size: 12.5px; color: #374151; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    .mt-fe-checks { margin-top: 0.95rem; display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem 0.75rem; }
    .mt-fe-checks span { display: flex; align-items: center; gap: 0.4rem; font-size: 12px; color: #374151; line-height: 1.35; }
    .mt-fe-check { color: #2563EB; font-weight: 700; flex-shrink: 0; }
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
