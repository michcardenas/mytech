@extends('layouts.app-home')

{{--
    Landing: Chatbots con IA para WhatsApp.
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = chatbots-ia-whatsapp), editable desde /admin/seo/{id}/edit.
    Aquí solo va el CONTENIDO + el FAQPage (via head_extras, para no duplicarse
    con el @graph principal que el layout limpia de FAQPage).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, quiero un chatbot con IA para WhatsApp para mi negocio.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/crm-asistente-ia-whatsapp-clinica-jasmin-blanco');

    $faqs = [
        [
            'q' => '¿Qué es un chatbot con IA para WhatsApp?',
            'a' => 'Es un asistente virtual que responde a tus clientes por WhatsApp con lenguaje natural, usando inteligencia artificial (Claude de Anthropic) y solo la información de tu negocio. A diferencia de un bot de botones, entiende lo que le escriben, resuelve dudas, califica al cliente y lo lleva a comprar o agendar, las 24 horas.',
        ],
        [
            'q' => '¿En qué se diferencia de un bot común o de plataformas genéricas?',
            'a' => 'Un bot de flujos (tipo botones) solo sigue un árbol rígido; las plataformas genéricas no se adaptan a tu operación ni a tu normativa. Nuestro asistente entiende contexto real, responde fundamentado en tu base de conocimiento (sin inventar precios ni prometer lo que no ofreces) y se integra a tus pagos, tu agenda y tu CRM. Es software a la medida, no una plantilla.',
        ],
        [
            'q' => '¿El chatbot puede cobrar y agendar citas automáticamente?',
            'a' => 'Sí. El asistente puede cobrar la consulta o el servicio por Mercado Pago o entregar tus números de cuenta, validar que el pago se realizó y solo entonces agendar la cita, sincronizada con Google Calendar y con recordatorios para reducir inasistencias.',
        ],
        [
            'q' => '¿Se conecta a mi número de WhatsApp actual y a mis herramientas?',
            'a' => 'Sí. Se integra con WhatsApp y con las herramientas que ya usas (pasarelas de pago como Mercado Pago, Google Calendar, tu CRM). Todo queda en un solo panel donde ves las conversaciones, el embudo de clientes y las métricas en vivo.',
        ],
        [
            'q' => '¿Cuánto cuesta desarrollar un chatbot con IA para WhatsApp?',
            'a' => 'Depende del alcance, pero una solución a la medida arranca desde aproximadamente USD 900 (~$3.600.000 COP) y escala según las integraciones (pagos, agenda, automatizaciones, CRM). En MY Tech Solutions cotizamos por fases, sin costos ocultos.',
        ],
        [
            'q' => '¿En cuánto tiempo queda funcionando?',
            'a' => 'Una primera versión atendiendo por WhatsApp suele estar lista en pocas semanas. Empezamos conectando tu WhatsApp y cargando la información de tu negocio, entrenamos al asistente con tus reglas, lo probamos contigo y lo dejamos en vivo. Luego iteramos con mejoras.',
        ],
    ];

    $capacidades = [
        ['icon' => 'chat', 'title' => 'Atiende con lenguaje natural', 'desc' => 'Responde como un humano usando IA (Claude), entiende lo que le escriben y mantiene el hilo de la conversación.'],
        ['icon' => 'shield', 'title' => 'Solo tu información, sin alucinar', 'desc' => 'Responde fundamentado en tu base de conocimiento. Nunca inventa precios ni promete lo que no ofreces.'],
        ['icon' => 'card', 'title' => 'Cobra y valida el pago', 'desc' => 'Cobra por Mercado Pago o entrega tus números de cuenta, y confirma el pago antes de avanzar.'],
        ['icon' => 'calendar', 'title' => 'Agenda citas solo', 'desc' => 'Agenda en Google Calendar cuando el pago está confirmado y envía recordatorios para reducir inasistencias.'],
        ['icon' => 'funnel', 'title' => 'CRM y embudo visual', 'desc' => 'Cada conversación se convierte en un cliente dentro de un pipeline tipo Kanban que puedes mover y etiquetar.'],
        ['icon' => 'inbox', 'title' => 'Inbox unificado 24/7', 'desc' => 'Todos los chats en un solo panel, con métricas en vivo y opción de que un humano tome el control cuando quiera.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Conectamos y cargamos', 'desc' => 'Enlazamos tu WhatsApp y cargamos la información real de tu negocio: servicios, precios, reglas y preguntas frecuentes.'],
        ['num' => '02', 'title' => 'Entrenamos el asistente', 'desc' => 'Configuramos el tono, las reglas de cobro y agenda, la normativa que debe respetar y las integraciones (pagos, calendario, CRM).'],
        ['num' => '03', 'title' => 'Probamos contigo', 'desc' => 'Validas conversaciones reales y ajustamos hasta que atienda exactamente como tu mejor asesor lo haría.'],
        ['num' => '04', 'title' => 'En vivo: atiende, cobra y agenda', 'desc' => 'El asistente queda operando 24/7 y tú ves todo desde el panel. Iteramos con mejoras continuas.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-cb-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-cb-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Asistentes de IA · WhatsApp
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Chatbots con IA para WhatsApp que <span class="text-mt-accent">atienden, cobran y agendan</span> por ti.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Desarrollamos asistentes con inteligencia artificial (Claude) que responden a tus clientes por WhatsApp con la información de tu negocio, cobran, validan el pago y agendan la cita &mdash; todo desde un solo panel. A la medida, no una plantilla.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'Atiende 24/7 y responde en segundos, no en horas',
                        'Solo usa tu información: nunca inventa precios ni promete de más',
                        'Cobra, valida el pago y agenda la cita sin intervención manual',
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
                        Quiero mi chatbot con IA
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>

                <div class="mt-12 pt-8 border-t border-mt-border grid grid-cols-3 gap-x-6 sm:gap-x-10 gap-y-6 max-w-lg" data-animate>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight">24/7</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Sin horario ni descanso</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight flex items-baseline">&lt;<span data-counter="5" data-counter-decimals="0" aria-label="5">0</span>s</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">En responder</div>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-display font-semibold text-mt-text leading-none tracking-tight">100%</div>
                        <div class="mt-2 text-[11px] font-mono uppercase tracking-[0.16em] text-mt-text-2 leading-snug">Con tu información</div>
                    </div>
                </div>
            </div>

            {{-- Mockup de conversación de WhatsApp --}}
            <div class="relative flex justify-center lg:justify-end" data-animate>
                <div class="mt-cb-phone">
                    <div class="mt-cb-phone-header">
                        <span class="mt-cb-phone-avatar" aria-hidden="true">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8V4m0 4a4 4 0 00-4 4v1h8v-1a4 4 0 00-4-4zM6 17h12M9 21h6"/></svg>
                        </span>
                        <div class="leading-tight">
                            <div class="text-white text-sm font-semibold">Asistente IA · MY Tech</div>
                            <div class="text-white/80 text-[11px] flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/90 animate-pulse-soft"></span> en línea
                            </div>
                        </div>
                    </div>
                    <div class="mt-cb-phone-body">
                        <div class="mt-cb-bubble mt-cb-in">Hola, ¿cuánto cuesta la valoración capilar? 💇</div>
                        <div class="mt-cb-bubble mt-cb-out">¡Hola! La valoración tiene un valor de $80.000 e incluye diagnóstico con el especialista. ¿Deseas agendar? Puedo dejarte el cupo apenas confirmes el pago 🙌</div>
                        <div class="mt-cb-bubble mt-cb-in">Sí, quiero agendar para el sábado</div>
                        <div class="mt-cb-bubble mt-cb-out">Perfecto. Te dejo el link de pago por Mercado Pago 👉 <span class="underline">mpago.la/valoracion</span></div>
                        <div class="mt-cb-bubble mt-cb-out mt-cb-note">
                            <span class="mt-cb-check" aria-hidden="true">✓</span> Pago confirmado &middot; cita agendada sábado 10:00 a.m. Te enviaré un recordatorio 📅
                        </div>
                        <div class="mt-cb-typing" aria-hidden="true"><span></span><span></span><span></span></div>
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
                'IA de Claude (Anthropic)',
                'WhatsApp',
                'Mercado Pago',
                'Google Calendar',
                'CRM a la medida',
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
                Cada mensaje sin responder es un cliente que se va.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Los clientes escriben por WhatsApp a toda hora y esperan respuesta ya. Contestar cada mensaje, calificar, cobrar y agendar a mano &mdash; mientras atiendes tu negocio &mdash; es imposible de sostener. Y las herramientas genéricas no entienden tu operación ni tu normativa.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Respondes tarde (o no respondes)', 'd' => 'El lead se enfría o se va con la competencia que contestó primero.'],
                ['t' => 'Cobrar y agendar a mano', 'd' => 'Se te va el día en tareas repetitivas y aun así se cuelan citas sin pagar.'],
                ['t' => 'Bots genéricos que no encajan', 'd' => 'Menús rígidos que frustran al cliente y que inventan o prometen lo que no ofreces.'],
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
                Un asistente que trabaja como tu mejor asesor.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                No es un bot de botones. Es un agente con IA a la medida de tu negocio, con todo lo que necesita para atender de verdad.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($capacidades as $c)
                <div class="group rounded-2xl border border-mt-border bg-white p-6 transition-colors duration-300 hover:border-mt-accent" data-animate>
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                        @switch($c['icon'])
                            @case('chat')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8 8 0 01-11.6 7.1L3 21l1.9-6.4A8 8 0 1121 12z"/></svg>
                                @break
                            @case('shield')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 5-3.5 8.4-8 9-4.5-.6-8-4-8-9V6l8-3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12l1.8 1.8L15 10"/></svg>
                                @break
                            @case('card')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path stroke-linecap="round" d="M3 10h18"/></svg>
                                @break
                            @case('calendar')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3.5" y="5" width="17" height="16" rx="2.5"/><path stroke-linecap="round" d="M3.5 10h17M8 3v4M16 3v4"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 15l2 2 4-4"/></svg>
                                @break
                            @case('funnel')
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18l-7 8v6l-4-2v-4L3 5z"/></svg>
                                @break
                            @default
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
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
                    <h2 class="mt-4 text-section font-display text-mt-text">De tu WhatsApp a un asistente que vende.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Cuatro pasos, sin complicaciones técnicas de tu lado. Nosotros lo construimos, tú lo apruebas.
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
                Un CRM con IA que atiende, cobra y agenda por WhatsApp.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para la <strong class="text-white">Dra. Jasmin Blanco</strong> (clínica capilar y estética) desarrollamos un CRM con un asistente de IA (Claude) que atiende a los pacientes por WhatsApp, <strong class="text-white">cobra la consulta</strong> por Mercado Pago o transferencia, <strong class="text-white">valida el pago</strong> y <strong class="text-white">agenda la cita</strong> &mdash; cumpliendo la normativa médica: nunca inventa precios ni promete resultados.
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
                    ['k' => '24/7', 'v' => 'Atención por WhatsApp'],
                    ['k' => '1 panel', 'v' => 'IA + cobro + embudo'],
                    ['k' => '0', 'v' => 'Citas sin pagar'],
                    ['k' => 'Invima', 'v' => 'Cumple normativa'],
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
{{-- COMPARATIVA                                                   --}}
{{-- ============================================================= --}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-12" data-animate>
            <span class="mt-eyebrow-gray">Por qué a la medida</span>
            <h2 class="mt-4 text-section font-display text-mt-text">No es lo mismo un bot que un asistente que entiende tu negocio.</h2>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-mt-border" data-animate>
            <table class="w-full min-w-[640px] text-left border-collapse">
                <thead>
                    <tr class="bg-mt-bg-2 text-mt-text">
                        <th class="p-4 md:p-5 font-mono text-[11px] uppercase tracking-[0.14em] text-mt-text-3 font-medium"></th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Contestar a mano</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-border">Bot genérico</th>
                        <th class="p-4 md:p-5 text-sm font-display font-semibold border-l border-mt-accent-line bg-mt-accent-soft text-mt-accent">Asistente IA a la medida</th>
                    </tr>
                </thead>
                <tbody class="text-[14.5px]">
                    @foreach ([
                        'Entiende lo que le escriben (lenguaje natural)',
                        'Responde solo con tu información, sin inventar',
                        'Cobra y valida el pago',
                        'Agenda citas y envía recordatorios',
                        'Se adapta a tu flujo y tu normativa',
                        'Disponible 24/7 sin cansarse',
                    ] as $i => $fila)
                        <tr class="{{ $i % 2 ? 'bg-mt-bg-2/50' : 'bg-white' }} border-t border-mt-border">
                            <td class="p-4 md:p-5 text-mt-text font-medium">{{ $fila }}</td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ $i === 5 ? '—' : '✕' }}</span>
                            </td>
                            <td class="p-4 md:p-5 border-l border-mt-border text-center">
                                <span class="text-mt-text-3">{{ in_array($i, [5]) ? '≈' : '✕' }}</span>
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
                <h2 class="mt-4 text-section font-display text-mt-text">Software propio, no una suscripción para siempre.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución a la medida que es <strong class="text-mt-text">tuya</strong>. Cotizamos por fases y con precios claros, sin costos ocultos ni sorpresas. Empiezas con lo esencial y escalas cuando quieras.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Asistente de IA entrenado con tu información',
                        'Integración con WhatsApp, pagos y Google Calendar',
                        'Panel con CRM, inbox y métricas en vivo',
                        'Capacitación y soporte para tu equipo',
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
                    Cotizar mi chatbot
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
    <div class="mt-cb-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Deja que la IA atienda, cobre y agende &mdash; tú dedícate a tu negocio.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Te mostramos en una demo cómo se vería tu asistente de WhatsApp con IA. Sin compromiso.
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
    /* Glow decorativo del hero */
    .mt-cb-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-cb-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup de teléfono / chat WhatsApp */
    .mt-cb-phone {
        width: 100%; max-width: 360px;
        border-radius: 28px;
        background: #fff;
        border: 1px solid #E5E7EB;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16), 0 4px 14px rgba(37,99,235,0.06);
        overflow: hidden;
    }
    .mt-cb-phone-header {
        display: flex; align-items: center; gap: 0.7rem;
        padding: 0.9rem 1rem;
        background: linear-gradient(135deg, #128C7E 0%, #075E54 100%);
    }
    .mt-cb-phone-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        background: rgba(255,255,255,0.18); color: #fff;
        display: flex; align-items: center; justify-content: center;
    }
    .mt-cb-phone-body {
        padding: 1.1rem 0.9rem 1.4rem;
        background:
            linear-gradient(180deg, rgba(37,99,235,0.03), transparent 30%),
            #ECE5DD;
        display: flex; flex-direction: column; gap: 0.55rem;
        min-height: 360px;
    }
    .mt-cb-bubble {
        max-width: 82%;
        padding: 0.55rem 0.75rem;
        border-radius: 12px;
        font-size: 13.5px; line-height: 1.45;
        box-shadow: 0 1px 1px rgba(0,0,0,0.06);
    }
    .mt-cb-in  { align-self: flex-start; background: #fff; color: #1F2937; border-top-left-radius: 3px; }
    .mt-cb-out { align-self: flex-end; background: #DCF8C6; color: #1F2937; border-top-right-radius: 3px; }
    .mt-cb-note { background: #D6ECFB; }
    .mt-cb-check { color: #128C7E; font-weight: 700; }
    .mt-cb-typing {
        align-self: flex-start; display: inline-flex; gap: 4px;
        background: #fff; padding: 0.6rem 0.8rem; border-radius: 12px; border-top-left-radius: 3px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.06);
    }
    .mt-cb-typing span {
        width: 7px; height: 7px; border-radius: 50%; background: #9CA3AF;
        animation: mtCbType 1.3s infinite ease-in-out;
    }
    .mt-cb-typing span:nth-child(2) { animation-delay: 0.18s; }
    .mt-cb-typing span:nth-child(3) { animation-delay: 0.36s; }
    @keyframes mtCbType {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
        30% { transform: translateY(-4px); opacity: 1; }
    }
    @media (prefers-reduced-motion: reduce) {
        .mt-cb-typing span { animation: none; }
    }
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
