@extends('layouts.app-home')

{{--
    Landing: CRM a la medida.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = software-crm-a-la-medida), editable desde
    /admin/seo/{id}/edit. Aquí solo va el CONTENIDO + el FAQPage (head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, necesito un CRM a la medida para mi equipo comercial.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/formula-high-ticket-crm-ventas-telefonicas');

    $faqs = [
        [
            'q' => '¿En qué se diferencia de HubSpot o Salesforce?',
            'a' => 'Los CRM genéricos traen cientos de campos y funciones que no aplican a tu negocio, y cobran licencia por usuario al mes. El tuyo se construye sobre tu proceso real de venta: solo lo que usas, el código es tuyo y sin pagar por cada persona del equipo.',
        ],
        [
            'q' => '¿Se integra con WhatsApp?',
            'a' => 'Sí. WhatsApp es donde de verdad conversan tus clientes, así que lo conectamos al CRM para que cada conversación quede asociada al lead. También integramos correo y agenda.',
        ],
        [
            'q' => '¿El equipo comercial de verdad lo va a usar?',
            'a' => 'Esa es la clave de hacerlo a la medida: como refleja exactamente cómo vende tu equipo y no tiene campos de relleno, se vuelve más fácil que el cuaderno o el Excel. Además capacitamos a tu gente.',
        ],
        [
            'q' => '¿Puedo ver reportes por vendedor?',
            'a' => 'Sí. Cuántos negocios tiene cada asesor, en qué etapa están, cuánto ha cerrado y qué tiene pendiente, con permisos por rol para que cada quien vea lo que le corresponde.',
        ],
        [
            'q' => '¿Puedo migrar mis contactos actuales?',
            'a' => 'Sí. Importamos tus leads y clientes desde Excel o desde tu herramienta actual para que arranques con tu base real.',
        ],
        [
            'q' => '¿Cuánto cuesta y en cuánto tiempo?',
            'a' => 'Un CRM a la medida arranca desde aproximadamente USD 900 (~$3.600.000 COP) y escala según las integraciones y automatizaciones. Cotizamos por fases: primero el pipeline funcionando y desde ahí ampliamos.',
        ],
    ];

    $capacidades = [
        ['icon' => 'summary', 'title' => 'Pipeline por etapas', 'desc' => 'Tus etapas reales de venta, con tablero visual. Arrastras el negocio de una columna a otra y todos ven en qué va.'],
        ['icon' => 'doc', 'title' => 'Leads y seguimiento', 'desc' => 'Cada contacto con su bitácora: qué se habló, cuándo volver a llamar y qué sigue. Nada se cae.'],
        ['icon' => 'mail', 'title' => 'Cotizaciones y propuestas', 'desc' => 'Generas la propuesta desde el mismo lead y le haces seguimiento hasta el cierre.'],
        ['icon' => 'plug', 'title' => 'Integrado con WhatsApp', 'desc' => 'El canal donde de verdad conversan tus clientes, conectado al CRM y a la agenda.'],
        ['icon' => 'shield', 'title' => 'Reportes por asesor', 'desc' => 'Cuántos negocios, en qué etapa, cuánto cerró cada quién. Con permisos por rol.'],
        ['icon' => 'scan', 'title' => 'Recordatorios y agenda', 'desc' => 'Alertas de próxima acción y agenda de reuniones para que ningún lead se enfríe.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Mapeamos tu proceso de venta', 'desc' => 'Definimos tus etapas reales, tus fuentes de leads y qué datos importan de verdad.'],
        ['num' => '02', 'title' => 'Montamos tu pipeline', 'desc' => 'Construimos el tablero y la ficha de lead a tu medida, sin campos de relleno.'],
        ['num' => '03', 'title' => 'Conectamos tus canales', 'desc' => 'Integramos WhatsApp, correo y agenda para que todo quede registrado solo.'],
        ['num' => '04', 'title' => 'En producción y midiendo', 'desc' => 'Tu equipo lo usa a diario. Ajustamos reportes y automatizaciones sobre datos reales.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-crm-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-crm-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        CRM · Ventas
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Un CRM a la medida que refleja <span class="text-mt-accent">cómo vendes tú</span>.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Desarrollamos CRMs a la medida en Laravel con tu pipeline de ventas, tus etapas, tus leads y tus reportes. Integrado con WhatsApp, correo y tu operación. Tu código, sin licencias por usuario ni campos que te sobran.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Pipeline por etapas, tal como vende tu equipo',
                        'Leads, seguimiento, cotizaciones y recordatorios',
                        'Integrado con WhatsApp, correo y tu agenda',
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
                        Cotizar mi CRM a la medida
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>
            </div>

            {{-- Mockup: tablero kanban de pipeline de ventas --}}
            <div class="flex justify-center lg:justify-end" data-animate>
                <div class="mt-crm-board">
                    <div class="mt-crm-board-head">
                        <div>
                            <span class="mt-crm-board-badge">Tablero</span>
                            <p class="mt-crm-board-num">Pipeline de ventas</p>
                        </div>
                        <span class="mt-crm-status">
                            <span class="mt-crm-status-dot" aria-hidden="true"></span>
                            12 negocios abiertos
                        </span>
                    </div>

                    <div class="mt-crm-board-body">
                        <div class="mt-crm-cols">
                            @foreach ([
                                ['t' => 'Prospecto', 'n' => 4, 'cards' => [
                                    ['c' => 'Andina S.A.S.', 'v' => '$8.4M'],
                                    ['c' => 'Grupo Vélez', 'v' => '$3.1M'],
                                ]],
                                ['t' => 'Contactado', 'n' => 3, 'cards' => [
                                    ['c' => 'Textiles Ruiz', 'v' => '$5.6M'],
                                    ['c' => 'Comercial Nova', 'v' => '$2.2M'],
                                ]],
                                ['t' => 'Propuesta', 'n' => 3, 'cards' => [
                                    ['c' => 'Ferreinsumos', 'v' => '$11.9M'],
                                ]],
                                ['t' => 'Ganado', 'n' => 2, 'cards' => [
                                    ['c' => 'Distrimoda', 'v' => '$6.7M'],
                                ]],
                            ] as $col)
                                <div class="mt-crm-col">
                                    <div class="mt-crm-col-head">
                                        <span>{{ $col['t'] }}</span>
                                        <span class="mt-crm-col-count">{{ $col['n'] }}</span>
                                    </div>
                                    <div class="mt-crm-col-cards">
                                        @foreach ($col['cards'] as $lead)
                                            <div class="mt-crm-lead">
                                                <span class="mt-crm-lead-name">{{ $lead['c'] }}</span>
                                                <span class="mt-crm-lead-val">{{ $lead['v'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
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
                'Pipeline de ventas',
                'WhatsApp',
                'Reportes por asesor',
                'Tu proceso',
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
                Las ventas no se pierden por falta de leads, sino por falta de seguimiento.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Unos leads en WhatsApp, otros en un Excel, otros en la cabeza del vendedor. Sin un lugar común, los negocios se enfrían y nadie sabe en qué va cada uno.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Leads en mil lados', 'd' => 'Unos en WhatsApp, otros en un Excel, otros en la cabeza del vendedor. Se pierden ventas por no hacer seguimiento.'],
                ['t' => 'CRM genérico que nadie usa', 'd' => 'Salesforce o HubSpot con 200 campos que no aplican a tu negocio; el equipo termina volviendo al cuaderno.'],
                ['t' => 'Sin visibilidad', 'd' => 'No sabes cuántos negocios hay abiertos, en qué etapa ni quién no está haciendo su trabajo.'],
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
                Un CRM que tu equipo sí usa, porque está hecho a su medida.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Tus etapas reales de venta, sin campos de relleno, integrado con los canales donde de verdad conversas con tus clientes.
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
                    <h2 class="mt-4 text-section font-display text-mt-text">Lo construimos sobre tu forma de vender.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Definimos tus etapas y tus datos reales, montamos el pipeline y conectamos tus canales. Sin plantillas ajenas.
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
                Un CRM para un equipo de ventas telefónicas de alto ticket.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para <strong class="text-white">Fórmula High Ticket</strong> desarrollamos un CRM de ventas telefónicas a la medida: gestión de leads, pipeline por etapas y seguimiento del equipo comercial, pensado para cerrar negocios de ticket alto por teléfono sin que ningún prospecto se pierda.
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
                    ['k' => 'Pipeline', 'v' => 'Por etapas de venta'],
                    ['k' => 'Leads', 'v' => 'Con seguimiento'],
                    ['k' => 'Equipo', 'v' => 'Reportes por asesor'],
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
                <h2 class="mt-4 text-section font-display text-mt-text">Se paga con los negocios que hoy se te pierden por no hacer seguimiento.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución que es <strong class="text-mt-text">tuya</strong>: tu código, sin licencias por usuario, integrada a tu operación. Cotizamos por fases para que veas resultado rápido.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Diagnóstico de tu proceso comercial y tus etapas',
                        'Pipeline y ficha de lead a la medida',
                        'Integración con WhatsApp, correo y agenda',
                        'Reportes por asesor, roles y soporte',
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
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;900</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $3.600.000 COP · por fases</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi CRM a la medida
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende de las integraciones y automatizaciones que necesites.
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
                        ¿Tienes otra duda sobre tu CRM a la medida? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
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
    <div class="mt-crm-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Que ningún lead se vuelva a enfriar.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos cómo vende tu equipo y te mostramos el CRM a la medida que necesita. Sin compromiso.
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
    .mt-crm-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-crm-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup: tablero kanban de pipeline de ventas */
    .mt-crm-board {
        width: 100%; max-width: 400px;
        border-radius: 20px; background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-crm-board-head {
        display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;
        padding: 1.1rem 1.2rem; background: #F9FAFB; border-bottom: 1px solid #E5E7EB;
    }
    .mt-crm-board-badge {
        font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace; color: #6B7280;
    }
    .mt-crm-board-num { margin-top: 0.2rem; font-size: 18px; font-weight: 700; color: #111827; letter-spacing: 0.01em; }
    .mt-crm-status {
        display: inline-flex; align-items: center; gap: 0.4rem; white-space: nowrap;
        font-size: 11px; font-weight: 600; color: #2563EB;
        background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.22);
        border-radius: 999px; padding: 0.3rem 0.6rem;
    }
    .mt-crm-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }
    .mt-crm-board-body { padding: 1rem; overflow-x: auto; }
    .mt-crm-cols { display: grid; grid-auto-flow: column; grid-auto-columns: minmax(90px, 1fr); gap: 0.6rem; }
    .mt-crm-col { background: #F9FAFB; border: 1px solid #EEF0F3; border-radius: 12px; padding: 0.55rem 0.5rem; display: flex; flex-direction: column; gap: 0.5rem; min-width: 90px; }
    .mt-crm-col-head { display: flex; align-items: center; justify-content: space-between; gap: 0.3rem; font-size: 10.5px; font-weight: 700; color: #374151; letter-spacing: 0.01em; }
    .mt-crm-col-count { display: inline-flex; align-items: center; justify-content: center; min-width: 16px; height: 16px; padding: 0 0.25rem; border-radius: 999px; background: rgba(37,99,235,0.1); color: #2563EB; font-size: 10px; font-weight: 700; }
    .mt-crm-col-cards { display: flex; flex-direction: column; gap: 0.4rem; }
    .mt-crm-lead {
        display: flex; flex-direction: column; gap: 0.15rem;
        background: #fff; border: 1px solid #E5E7EB; border-radius: 8px;
        padding: 0.4rem 0.5rem;
        box-shadow: 0 1px 2px rgba(15,23,42,0.04);
    }
    .mt-crm-lead-name { font-size: 10.5px; font-weight: 600; color: #111827; line-height: 1.25; }
    .mt-crm-lead-val { font-size: 10px; color: #6B7280; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
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
