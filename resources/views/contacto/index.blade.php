@php
    /* ─────────────────────────────────────────────────────────────────
       SEO premium /contacto. BD-driven con fallbacks.
       ───────────────────────────────────────────────────────────────── */
    $contactoUrl = route('contacto.index');

    $seoTitle = $seo?->meta_title       ?? 'Contacto · MY Tech Solutions — Cotiza tu proyecto en 24h';
    $seoDesc  = $seo?->meta_description ?? 'Cuéntanos tu proyecto y recibe una propuesta clara en 24 horas. Desarrollo web, SaaS, automatización y soluciones a medida en LATAM.';

    $autoSchema = [
        '@context' => 'https://schema.org',
        '@type'    => 'ContactPage',
        'url'      => $contactoUrl,
        'inLanguage' => 'es',
        'name'     => $seoTitle,
        'description' => $seoDesc,
        'mainEntity' => [
            '@type' => 'Organization',
            'name'  => 'MY Tech Solutions',
            'url'   => url('/'),
            'email' => $data['method_3_email'] ?? 'contacto@mytechsolutionsco.com',
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'telephone' => '+57 333 724 6403',
                    'contactType' => 'customer service',
                    'areaServed' => ['CO', 'AR', 'CL', 'MX', 'GT', 'CR', 'ES'],
                    'availableLanguage' => ['Spanish', 'English'],
                ],
            ],
        ],
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio',   'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contacto', 'item' => $contactoUrl],
            ],
        ],
    ];

    $seo = (object) [
        'meta_title'          => $seoTitle,
        'meta_description'    => $seoDesc,
        'canonical_url'       => $seo->canonical_url ?? $contactoUrl,
        'robots'              => $seo->robots ?? 'index,follow',
        'og_title'            => $seo->og_title ?? $seoTitle,
        'og_description'      => $seo->og_description ?? $seoDesc,
        'og_url'              => $contactoUrl,
        'og_type'             => 'website',
        'og_image'            => $seo->og_image ? asset('storage/'.$seo->og_image) : asset('images/logo.png'),
        'og_site_name'        => 'MY Tech Solutions',
        'twitter_card'        => $seo->twitter_card ?? 'summary_large_image',
        'twitter_title'       => $seo->twitter_title ?? $seoTitle,
        'twitter_description' => $seo->twitter_description ?? $seoDesc,
        'twitter_image'       => $seo->twitter_image ? asset('storage/'.$seo->twitter_image) : asset('images/logo.png'),
        'schema_markup'       => $autoSchema,
    ];

    $waNumber  = $data['hero_whatsapp_number'] ?? '573337246403';
    $waMessage = $data['hero_whatsapp_message'] ?? 'Hola, quiero cotizar un proyecto con MY Tech Solutions.';
    $waUrl     = 'https://wa.me/'.preg_replace('/\D/', '', $waNumber).'?text='.urlencode($waMessage);
    $contactEmail = $data['method_3_email'] ?? 'contacto@mytechsolutionsco.com';
@endphp

@extends('layouts.app-home')

@section('content')

{{-- ════════════════ HERO ════════════════ --}}
<section class="mt-cnt-hero" data-cnt-hero>
    <div class="mt-cnt-hero-bg" aria-hidden="true"></div>
    <div class="mt-cnt-hero-grid" aria-hidden="true"></div>
    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-12 gap-10 items-end">

            {{-- Left: copy editorial --}}
            <div class="lg:col-span-7">
                <span class="mt-cnt-eyebrow" data-animate>
                    <span class="mt-cnt-eyebrow-dot"></span>
                    {{ $data['hero_eyebrow'] ?? '[ Hablemos de tu proyecto ]' }}
                </span>
                <h1 class="mt-cnt-title" data-cnt-title>{{ $data['hero_title'] ?? 'Convertimos ideas en plataformas en producción.' }}</h1>
                <p class="mt-cnt-lead" data-animate>
                    {{ $data['hero_description'] ?? 'Cuéntanos qué quieres construir. Te respondemos en menos de 24 horas con una propuesta clara, sin promesas vacías y sin formularios eternos.' }}
                </p>

                <div class="mt-cnt-promesas" data-animate-children>
                    @php
                        $promesas = array_values(array_filter([
                            $data['hero_promise_1'] ?? 'Respuesta humana en <strong>menos de 24h</strong>',
                            $data['hero_promise_2'] ?? 'Diagnóstico inicial <strong>gratuito y sin compromiso</strong>',
                            $data['hero_promise_3'] ?? 'Propuesta con <strong>alcance, tiempos y precio</strong> antes de empezar',
                        ]));
                    @endphp
                    @foreach($promesas as $p)
                        <div class="mt-cnt-promesa">
                            <svg class="mt-cnt-promesa-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{!! $p !!}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-cnt-cta-row" data-animate>
                    <a href="#form" class="mt-btn-primary mt-cnt-cta-scroll" data-cnt-scroll-form>
                        {{ $data['hero_form_text'] ?? 'Empezar cotización' }}
                        <span aria-hidden="true">↓</span>
                    </a>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-btn-ghost">
                        <svg class="w-[18px] h-[18px]" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        {{ $data['hero_whatsapp_text'] ?? 'Hablar por WhatsApp' }}
                    </a>
                </div>
            </div>

            {{-- Right: card de canales rápidos --}}
            <div class="lg:col-span-5 lg:pl-8" data-animate>
                <div class="mt-cnt-channels">
                    <span class="mt-cnt-channels-label">Canales directos</span>

                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-cnt-channel">
                        <div class="mt-cnt-channel-icon" style="background: linear-gradient(135deg, #25d366, #128c7e);">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="mt-cnt-channel-body">
                            <span class="mt-cnt-channel-eyebrow">Respuesta &lt; 30 min</span>
                            <span class="mt-cnt-channel-title">WhatsApp directo</span>
                            <span class="mt-cnt-channel-meta">{{ $data['method_1_number'] ?? '+57 333 724 6403' }}</span>
                        </div>
                        <span class="mt-cnt-channel-arrow" aria-hidden="true">→</span>
                    </a>

                    <a href="mailto:{{ $contactEmail }}" class="mt-cnt-channel">
                        <div class="mt-cnt-channel-icon" style="background: linear-gradient(135deg, #2563EB, #1D4ED8);">
                            <svg class="w-5 h-5 text-white" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="mt-cnt-channel-body">
                            <span class="mt-cnt-channel-eyebrow">Respuesta &lt; 24h</span>
                            <span class="mt-cnt-channel-title">Email profesional</span>
                            <span class="mt-cnt-channel-meta">{{ $contactEmail }}</span>
                        </div>
                        <span class="mt-cnt-channel-arrow" aria-hidden="true">→</span>
                    </a>

                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-cnt-channel">
                        <div class="mt-cnt-channel-icon" style="background: linear-gradient(135deg, #0F172A, #1F2937);">
                            <svg class="w-5 h-5 text-white" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="mt-cnt-channel-body">
                            <span class="mt-cnt-channel-eyebrow">30 min · sin costo</span>
                            <span class="mt-cnt-channel-title">Videollamada de discovery</span>
                            <span class="mt-cnt-channel-meta">Agendamos contigo por WhatsApp</span>
                        </div>
                        <span class="mt-cnt-channel-arrow" aria-hidden="true">→</span>
                    </a>

                    <div class="mt-cnt-channels-foot">
                        <span class="mt-cnt-pulse"></span>
                        <span>Disponibles ahora · LATAM &amp; EU</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════ FORM PREMIUM ════════════════ --}}
<section class="mt-cnt-form-section" id="form">
    <div class="mt-cnt-form-bg" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="mt-cnt-form-head" data-animate>
            <span class="mt-eyebrow-gray">{{ $data['form_eyebrow'] ?? '[ Cuéntanos tu proyecto ]' }}</span>
            <h2 class="text-section font-display font-bold text-mt-text mt-3 text-balance">
                {{ $data['form_title'] ?? 'Un formulario corto.' }}
                <span class="text-mt-accent italic">{{ $data['form_title_accent'] ?? 'Una propuesta seria.' }}</span>
            </h2>
            <p class="mt-cnt-form-sub">{{ $data['form_description'] ?? 'Mientras más detalles compartas, más precisa será la propuesta. Promedio para completarlo: 90 segundos.' }}</p>
        </div>

        <div class="mt-cnt-form-wrap">

            {{-- Side panel — context, no a contact card --}}
            <aside class="mt-cnt-form-side" data-animate>
                <div class="mt-cnt-side-step">
                    <span class="mt-cnt-side-num">01</span>
                    <div>
                        <h4>Recibimos tu solicitud</h4>
                        <p>Confirmación inmediata en pantalla y por correo.</p>
                    </div>
                </div>
                <div class="mt-cnt-side-step">
                    <span class="mt-cnt-side-num">02</span>
                    <div>
                        <h4>Analizamos el alcance</h4>
                        <p>Un líder técnico revisa tu caso y prepara preguntas clave.</p>
                    </div>
                </div>
                <div class="mt-cnt-side-step">
                    <span class="mt-cnt-side-num">03</span>
                    <div>
                        <h4>Conversamos en 24h</h4>
                        <p>Videollamada o WhatsApp. Sin guion comercial.</p>
                    </div>
                </div>
                <div class="mt-cnt-side-step">
                    <span class="mt-cnt-side-num">04</span>
                    <div>
                        <h4>Propuesta clara</h4>
                        <p>Alcance, tiempos y precio antes de firmar nada.</p>
                    </div>
                </div>

                <div class="mt-cnt-side-trust">
                    <div class="mt-cnt-trust-pill">
                        <strong>40+</strong>
                        <span>plataformas en producción</span>
                    </div>
                    <div class="mt-cnt-trust-pill">
                        <strong>7 países</strong>
                        <span>CO · AR · CL · MX · GT · CR · ES</span>
                    </div>
                </div>
            </aside>

            {{-- Form --}}
            <form class="mt-cnt-form" action="{{ route('contacto.store') }}" method="POST" data-cnt-form>
                @csrf

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="mt-cnt-alert mt-cnt-alert-success" role="status">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-cnt-alert mt-cnt-alert-error" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mt-cnt-alert mt-cnt-alert-error" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <strong>Faltan algunos datos:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Fieldset 1: identidad --}}
                <div class="mt-cnt-fieldset" data-cnt-step>
                    <div class="mt-cnt-fieldset-head">
                        <span class="mt-cnt-fieldset-num">01</span>
                        <h3>¿Quién nos contacta?</h3>
                    </div>

                    <div class="mt-cnt-grid-2">
                        <label class="mt-cnt-field">
                            <span class="mt-cnt-field-label">Nombre completo *</span>
                            <input type="text" name="nombre" required value="{{ old('nombre') }}" placeholder="Ej. Camila Rodríguez" autocomplete="name">
                            <span class="mt-cnt-field-line" aria-hidden="true"></span>
                        </label>

                        <label class="mt-cnt-field">
                            <span class="mt-cnt-field-label">Email *</span>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="tu@empresa.com" autocomplete="email">
                            <span class="mt-cnt-field-line" aria-hidden="true"></span>
                        </label>

                        <label class="mt-cnt-field">
                            <span class="mt-cnt-field-label">WhatsApp *</span>
                            <input type="tel" name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="+57 312 345 6789" autocomplete="tel">
                            <span class="mt-cnt-field-line" aria-hidden="true"></span>
                        </label>

                        <label class="mt-cnt-field">
                            <span class="mt-cnt-field-label">Empresa u organización *</span>
                            <input type="text" name="empresa" required value="{{ old('empresa') }}" placeholder="Nombre comercial o proyecto" autocomplete="organization">
                            <span class="mt-cnt-field-line" aria-hidden="true"></span>
                        </label>
                    </div>
                </div>

                {{-- Fieldset 2: tipo de proyecto (chips) --}}
                <div class="mt-cnt-fieldset" data-cnt-step>
                    <div class="mt-cnt-fieldset-head">
                        <span class="mt-cnt-fieldset-num">02</span>
                        <h3>¿Qué tipo de proyecto necesitas?</h3>
                    </div>

                    <input type="hidden" name="tipo_proyecto" id="tipo_proyecto" value="{{ old('tipo_proyecto') }}" required>
                    <div class="mt-cnt-chips" data-cnt-chip-group="tipo_proyecto">
                        @php
                            $tipos = [
                                ['v' => 'web',         'l' => 'Sitio web profesional',       'd' => 'Marca, landings, alta velocidad y SEO'],
                                ['v' => 'ecommerce',   'l' => 'E-commerce / Tienda online', 'd' => 'Catálogo, pagos, logística'],
                                ['v' => 'app',         'l' => 'App web a medida',           'd' => 'CRM, dashboards, automatización'],
                                ['v' => 'saas',        'l' => 'Plataforma SaaS',            'd' => 'Multi-tenant, suscripciones, API'],
                                ['v' => 'marketplace', 'l' => 'Marketplace',                'd' => 'Tipo MercadoLibre, multi-vendor'],
                                ['v' => 'booking',     'l' => 'Sistema de reservas',        'd' => 'Citas, agenda, recordatorios'],
                                ['v' => 'admin',       'l' => 'Sistema interno / CRM',      'd' => 'Operaciones, métricas, equipos'],
                                ['v' => 'otros',       'l' => 'Otro',                       'd' => 'Cuéntanos abajo en detalle'],
                            ];
                        @endphp
                        @foreach($tipos as $t)
                            <button type="button" class="mt-cnt-chip {{ old('tipo_proyecto') === $t['v'] ? 'is-active' : '' }}"
                                    data-chip-value="{{ $t['v'] }}" data-chip-target="tipo_proyecto">
                                <span class="mt-cnt-chip-title">{{ $t['l'] }}</span>
                                <span class="mt-cnt-chip-desc">{{ $t['d'] }}</span>
                                <span class="mt-cnt-chip-check" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Fieldset 3: presupuesto (chips) --}}
                <div class="mt-cnt-fieldset" data-cnt-step>
                    <div class="mt-cnt-fieldset-head">
                        <span class="mt-cnt-fieldset-num">03</span>
                        <h3>¿Cuál es tu presupuesto estimado?</h3>
                    </div>

                    <input type="hidden" name="presupuesto" id="presupuesto" value="{{ old('presupuesto') }}" required>
                    <div class="mt-cnt-chips mt-cnt-chips-budget" data-cnt-chip-group="presupuesto">
                        @php
                            $budgets = [
                                ['v' => '0-300',     'l' => '< $300 USD',           'd' => 'Landing o sitio simple'],
                                ['v' => '300-500',   'l' => '$300 – $500 USD',      'd' => 'Web profesional con CMS'],
                                ['v' => '500-1000',  'l' => '$500 – $1,000 USD',    'd' => 'E-commerce o web compleja'],
                                ['v' => '1000-2000', 'l' => '$1,000 – $2,000 USD',  'd' => 'App web o sistema interno'],
                                ['v' => '2000+',     'l' => '$2,000+ USD',          'd' => 'SaaS, marketplace, integraciones'],
                                ['v' => 'consultar', 'l' => 'Prefiero conversarlo', 'd' => 'Lo definimos en la reunión'],
                            ];
                        @endphp
                        @foreach($budgets as $b)
                            <button type="button" class="mt-cnt-chip {{ old('presupuesto') === $b['v'] ? 'is-active' : '' }}"
                                    data-chip-value="{{ $b['v'] }}" data-chip-target="presupuesto">
                                <span class="mt-cnt-chip-title">{{ $b['l'] }}</span>
                                <span class="mt-cnt-chip-desc">{{ $b['d'] }}</span>
                                <span class="mt-cnt-chip-check" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Fieldset 4: descripción --}}
                <div class="mt-cnt-fieldset" data-cnt-step>
                    <div class="mt-cnt-fieldset-head">
                        <span class="mt-cnt-fieldset-num">04</span>
                        <h3>Cuéntanos el detalle</h3>
                    </div>

                    <label class="mt-cnt-field mt-cnt-field-textarea">
                        <span class="mt-cnt-field-label">Descripción del proyecto *</span>
                        <textarea name="descripcion" required rows="6" maxlength="2000" data-cnt-textarea
                            placeholder="¿Qué problema resuelve? ¿Quién lo usará? ¿Qué necesitas que haga? ¿Tienes fecha objetivo? Mientras más detalles, mejor la propuesta.">{{ old('descripcion') }}</textarea>
                        <span class="mt-cnt-field-line" aria-hidden="true"></span>
                        <span class="mt-cnt-field-counter"><span data-cnt-counter>0</span> / 2000</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="mt-cnt-submit-wrap">
                    <button type="submit" class="mt-cnt-submit" data-cnt-submit>
                        <span class="mt-cnt-submit-label">
                            {{ $data['form_submit_text'] ?? 'Enviar y recibir propuesta' }}
                            <span aria-hidden="true">→</span>
                        </span>
                        <span class="mt-cnt-submit-loading" aria-hidden="true">
                            <span class="mt-cnt-spinner"></span>
                            Enviando…
                        </span>
                    </button>
                    <p class="mt-cnt-submit-foot">
                        Al enviar aceptas que te contactemos por los medios proporcionados. Sin spam, jamás.
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ════════════════ TRUST STRIP ════════════════ --}}
<section class="mt-cnt-trust">
    <div class="mt-container">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-px bg-mt-border" data-animate-children>
            @php
                $trustItems = [
                    ['n' => '< 24h', 't' => 'Respuesta garantizada', 'd' => 'A toda solicitud, de lunes a viernes.'],
                    ['n' => 'Gratis', 't' => 'Diagnóstico inicial',  'd' => 'Sin condiciones ni letra chica.'],
                    ['n' => '100%',   't' => 'Propuesta a medida',   'd' => 'No vendemos plantillas pre-armadas.'],
                    ['n' => '0$',     't' => 'Sin compromiso',       'd' => 'Si no encaja, te lo decimos claro.'],
                ];
            @endphp
            @foreach($trustItems as $ti)
                <div class="mt-cnt-trust-cell">
                    <span class="mt-cnt-trust-num">{{ $ti['n'] }}</span>
                    <h4>{{ $ti['t'] }}</h4>
                    <p>{{ $ti['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════ INFO + MAP ════════════════ --}}
<section class="mt-cnt-map-section">
    <div class="mt-container">
        <div class="grid lg:grid-cols-12 gap-12 items-center">

            <div class="lg:col-span-5" data-animate>
                <span class="mt-eyebrow-gray">[ Dónde encontrarnos ]</span>
                <h2 class="text-section font-display font-bold text-mt-text mt-3 leading-tight text-balance">
                    {{ $data['map_title'] ?? 'Bogotá como base.' }}
                    <span class="text-mt-accent italic">LATAM &amp; EU</span> como alcance.
                </h2>
                <p class="mt-5 text-mt-text-muted text-lg leading-relaxed">
                    {{ $data['map_description'] ?? 'Operamos desde Colombia con clientes en siete países (CO, AR, CL, MX, GT, CR, ES). Asincronía cuando funciona, reuniones en vivo cuando hace falta. Tu zona horaria es el límite, no el nuestro.' }}
                </p>

                <div class="mt-cnt-info-grid">
                    <div class="mt-cnt-info-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div>
                            <h4>Ubicación</h4>
                            <p>{{ $data['info_1_text'] ?? 'Bogotá, Colombia' }}</p>
                        </div>
                    </div>
                    <div class="mt-cnt-info-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <h4>Horario</h4>
                            <p>{{ $data['info_2_text'] ?? 'Lunes a viernes · 8:00 AM – 6:00 PM (COT)' }}</p>
                        </div>
                    </div>
                    <div class="mt-cnt-info-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <h4>Alcance</h4>
                            <p>{{ $data['info_3_text'] ?? 'Colombia · Argentina · México · España · USA' }}</p>
                        </div>
                    </div>
                    <div class="mt-cnt-info-row">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <div>
                            <h4>Soporte</h4>
                            <p>{{ $data['info_4_text'] ?? '24/7 para proyectos activos con SLA' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7" data-animate>
                <div class="mt-cnt-map-frame">
                    <div class="mt-cnt-map-overlay">
                        <span class="mt-cnt-map-overlay-eyebrow">MY Tech Solutions HQ</span>
                        <span class="mt-cnt-map-overlay-title">Bogotá, Colombia</span>
                    </div>
                    <iframe src="{{ $data['map_url'] ?? 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3249.012195470509!2d-74.13449935362908!3d4.600360674860746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1757988380012!5m2!1ses-419!2sco' }}"
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Ubicación MY Tech Solutions"></iframe>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
