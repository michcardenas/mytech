@extends('layouts.app-home')

{{--
    Landing: Desarrollo de Apps Móviles (iOS y Android).
    SEO (title, meta, OG, schema principal) vive en el registro `seo` de esta
    Page (slug = desarrollo-de-apps-moviles), editable desde
    /admin/seo/{id}/edit. Aquí solo va el CONTENIDO + el FAQPage (head_extras).
--}}

@php
    $waNumber = '573337246403';
    $waMsg = rawurlencode('Hola, quiero desarrollar una app móvil para mi negocio.');
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMsg;
    $casoUrl = url('/proyectos/keriva-app-farmacias-turno-precios');

    $faqs = [
        [
            'q' => '¿Hacen una app para iPhone y otra para Android?',
            'a' => 'No hace falta. Usamos React Native, que nos permite escribir una sola base de código que corre en iOS y Android. Cuesta cerca de la mitad que hacer dos apps nativas por separado y se mantienen sincronizadas.',
        ],
        [
            'q' => '¿Publican la app en App Store y Google Play?',
            'a' => 'Sí. Nos encargamos de todo el proceso de publicación y aprobación en ambas tiendas, incluyendo las cuentas de desarrollador y los requisitos de cada plataforma.',
        ],
        [
            'q' => '¿La app se puede conectar con mi sistema actual?',
            'a' => 'Sí. La app se integra por API con tu inventario, tu CRM, tu web, tu ERP o tu pasarela de pagos, para que sea parte de tu operación y no una isla aparte.',
        ],
        [
            'q' => '¿Sirve React Native para apps serias?',
            'a' => 'Sí. Es la tecnología con la que están hechas apps de empresas grandes. Da rendimiento nativo, acceso a cámara, GPS, notificaciones y pagos, y una sola base para las dos plataformas. Es justo lo que usamos en Keriva y en Vinko.',
        ],
        [
            'q' => '¿Qué necesito para empezar?',
            'a' => 'Con que tengas clara la idea y a quién va dirigida es suficiente. Nosotros aterrizamos el alcance, diseñamos las pantallas y te mostramos un prototipo antes de programar.',
        ],
        [
            'q' => '¿Cuánto cuesta y en cuánto tiempo?',
            'a' => 'Una app a la medida arranca desde aproximadamente USD 1.200 (~$4.800.000 COP) y varía según las funciones e integraciones. Cotizamos por fases para que veas una primera versión funcionando pronto.',
        ],
    ];

    $capacidades = [
        ['icon' => 'plug', 'title' => 'iOS y Android a la vez', 'desc' => 'Con React Native escribimos una sola base de código que corre en iPhone y Android. Mitad de costo, cero desincronización.'],
        ['icon' => 'shield', 'title' => 'Backend y panel propios', 'desc' => 'Tu app viene con su servidor y su panel de administración: controlas usuarios, contenido y datos desde un solo lugar.'],
        ['icon' => 'mail', 'title' => 'Notificaciones push', 'desc' => 'Llegas a tus usuarios directo en el teléfono: pedidos, promociones, recordatorios y avisos.'],
        ['icon' => 'scan', 'title' => 'Pagos y mapas', 'desc' => 'Integramos pasarelas de pago, geolocalización, cámara y todo lo que el celular ofrece.'],
        ['icon' => 'doc', 'title' => 'Publicación en las tiendas', 'desc' => 'Nos encargamos de subir y aprobar tu app en App Store y Google Play.'],
        ['icon' => 'summary', 'title' => 'Integrada con tu operación', 'desc' => 'La app se conecta con tu inventario, tu CRM, tu web o tu ERP para que todo sea un solo sistema.'],
    ];

    $pasos = [
        ['num' => '01', 'title' => 'Definimos el alcance', 'desc' => 'Aterrizamos qué hace la app, para quién y qué debe integrar. Priorizamos lo que da valor primero.'],
        ['num' => '02', 'title' => 'Diseño y prototipo', 'desc' => 'Diseñamos las pantallas y validamos el flujo antes de programar, para no rehacer.'],
        ['num' => '03', 'title' => 'Desarrollo y backend', 'desc' => 'Construimos la app y su servidor, con pruebas en dispositivos reales de iOS y Android.'],
        ['num' => '04', 'title' => 'Publicación y soporte', 'desc' => 'La subimos a las tiendas, la lanzamos y damos soporte y actualizaciones.'],
    ];
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="mt-app-hero relative overflow-hidden bg-white pt-36 pb-24 md:pb-28">
    <div class="mt-app-hero-glow" aria-hidden="true"></div>

    <div class="mt-container relative z-10">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-10 items-center">

            {{-- Copy --}}
            <div class="max-w-xl">
                <div data-animate>
                    <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                        Apps móviles · iOS y Android
                    </span>
                </div>

                <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                    Apps móviles para iOS y Android, con <span class="text-mt-accent">una sola base de código</span>.
                </h1>

                <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                    Desarrollamos apps móviles a la medida con React Native: una sola base para iPhone y Android, backend propio, notificaciones, pagos y mapas, y publicación en App Store y Google Play. Tu app, integrada con tu operación.
                </p>

                <ul class="mt-9 space-y-3.5" data-animate>
                    @foreach ([
                        'iOS y Android con una sola base de código (React Native)',
                        'Backend y panel de administración propios',
                        'Notificaciones push, pagos, mapas y más',
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
                        Cotizar mi app móvil
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                    <a href="#caso" class="mt-btn-ghost">
                        Ver un caso real
                    </a>
                </div>
            </div>

            {{-- Mockup: teléfono con pantalla de app --}}
            <div class="flex justify-center lg:justify-end" data-animate>
                <div class="mt-app-phone">
                    <div class="mt-app-notch" aria-hidden="true"></div>
                    <div class="mt-app-screen">
                        <div class="mt-app-screen-head">
                            <span class="mt-app-screen-title">Farmacias de turno</span>
                            <span class="mt-app-screen-time">9:41</span>
                        </div>

                        <div class="mt-app-list">
                            <div class="mt-app-list-item">
                                <div>
                                    <p class="mt-app-item-name">Farmacia San José</p>
                                    <p class="mt-app-item-sub">Calle 45 #12-30</p>
                                </div>
                                <span class="mt-app-badge-open">Abierta ahora</span>
                            </div>
                            <div class="mt-app-list-item">
                                <div>
                                    <p class="mt-app-item-name">Farmacia Cruz Verde</p>
                                    <p class="mt-app-item-sub">Av. Principal #8-21</p>
                                </div>
                                <span class="mt-app-badge-open">Abierta ahora</span>
                            </div>
                            <div class="mt-app-list-item">
                                <div>
                                    <p class="mt-app-item-name">Farmacia La Rebaja</p>
                                    <p class="mt-app-item-sub">Cra 7 #23-10</p>
                                </div>
                                <span class="mt-app-badge-closed">Cierra 10pm</span>
                            </div>
                        </div>

                        <div class="mt-app-navbar">
                            <span class="mt-app-nav-item mt-app-nav-active">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 4l9 6.5M5 9.5V19a1 1 0 001 1h4v-5h4v5h4a1 1 0 001-1V9.5"/></svg>
                                Inicio
                            </span>
                            <span class="mt-app-nav-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-6.5 7-11a7 7 0 10-14 0c0 4.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.3"/></svg>
                                Mapa
                            </span>
                            <span class="mt-app-nav-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20s-7-4.35-9.5-8.5C1 8 2.5 4.5 6 4.5c2 0 3.5 1.2 4 2.3.5-1.1 2-2.3 4-2.3 3.5 0 5 3.5 3.5 7C19 15.65 12 20 12 20z"/></svg>
                                Favoritos
                            </span>
                            <span class="mt-app-nav-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="3.3"/><path stroke-linecap="round" d="M5 20c1.2-3.2 4-5 7-5s5.8 1.8 7 5"/></svg>
                                Perfil
                            </span>
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
                'React Native',
                'Expo',
                'iOS y Android',
                'App Store y Play',
                'Backend propio',
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
                Una app no debería costar el doble ni quedar aislada.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Hacer una app para iPhone y otra para Android por separado duplica el costo y se desincroniza. Y una app que no habla con tu inventario o tus pagos termina siendo una isla.
            </p>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Dos apps, doble costo', 'd' => 'Hacer una app para iPhone y otra para Android por separado cuesta el doble y se desincroniza.'],
                ['t' => 'Plantillas que no escalan', 'd' => 'Los creadores de apps sin código sirven para un demo, pero se quedan cortos apenas creces.'],
                ['t' => 'App aislada', 'd' => 'Una app que no habla con tu inventario, tu CRM o tus pagos termina siendo una isla que nadie mantiene.'],
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
                Una sola base de código, en las dos tiendas, conectada a tu negocio.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Con React Native tu app corre en iOS y Android desde el mismo código, con backend propio y todas las capacidades del teléfono.
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
                    <h2 class="mt-4 text-section font-display text-mt-text">De la idea a las tiendas, por fases.</h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        Aterrizamos el alcance, diseñamos, programamos y publicamos. Ves un prototipo antes de que escribamos código de producción.
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
                Una app de farmacias de turno para toda República Dominicana.
            </h2>
        </div>

        <div class="mt-12 grid lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-7" data-animate>
                <p class="text-mt-text-on-dark text-base md:text-lg leading-relaxed">
                    Para <strong class="text-white">Keriva</strong> desarrollamos una app móvil multiplataforma que ayuda a los usuarios a encontrar farmacias de turno, precios y disponibilidad. Una sola base de código en React Native (Expo) para iOS y Android, con su backend y publicación en las tiendas.
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
                    ['k' => 'iOS + Android', 'v' => 'Una base de código'],
                    ['k' => 'React Native', 'v' => 'Expo + EAS'],
                    ['k' => 'Backend', 'v' => 'Datos en la nube'],
                    ['k' => 'En tiendas', 'v' => 'App Store y Play'],
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
                <h2 class="mt-4 text-section font-display text-mt-text">Una app bien hecha cuesta menos que dos apps a medias.</h2>
                <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Construimos una solución que es <strong class="text-mt-text">tuya</strong>: tu código, integrada a tu operación, sin depender de plantillas ajenas. Cotizamos por fases para que veas una primera versión pronto.
                </p>
                <ul class="mt-8 space-y-3.5">
                    @foreach ([
                        'Alcance, diseño y prototipo de la app',
                        'App para iOS y Android con una base de código',
                        'Backend, panel de administración y publicación en tiendas',
                        'Notificaciones, integraciones y soporte',
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
                    <span class="text-5xl md:text-6xl font-display font-semibold text-mt-text tracking-tight">USD&nbsp;1.200</span>
                </div>
                <div class="mt-1 text-mt-text-3 font-mono text-sm">≈ $4.800.000 COP · por fases</div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-8 w-full justify-center mt-btn-primary">
                    Cotizar mi app móvil
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-3 w-full justify-center mt-btn-ghost">
                    Prefiero un formulario
                </a>
                <p class="mt-5 text-center text-mt-text-3 text-[12.5px] leading-relaxed">
                    El valor final depende de las funciones e integraciones que necesites.
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
                        ¿Tienes otra duda sobre tu app móvil? Escríbenos por WhatsApp y te respondemos &mdash; sí, con una persona.
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
    <div class="mt-app-cta-glow" aria-hidden="true"></div>
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            Tu negocio, en el bolsillo de tus clientes.
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntanos tu idea de app y te decimos cómo llevarla a iOS y Android sin duplicar el costo. Sin compromiso.
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
    .mt-app-hero-glow {
        position: absolute; inset: 0; pointer-events: none;
        background:
            radial-gradient(60% 55% at 78% 8%, rgba(37,99,235,0.10), transparent 60%),
            radial-gradient(45% 40% at 8% 20%, rgba(37,99,235,0.06), transparent 60%);
    }
    .mt-app-cta-glow {
        position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(50% 60% at 50% 0%, rgba(96,165,250,0.16), transparent 65%);
    }

    /* Mockup: teléfono con pantalla de app */
    .mt-app-phone {
        width: 100%; max-width: 280px;
        border-radius: 32px; background: #111827;
        padding: 10px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18), 0 4px 14px rgba(37,99,235,0.08);
        position: relative;
    }
    .mt-app-notch {
        position: absolute; top: 10px; left: 50%; transform: translateX(-50%);
        width: 92px; height: 20px; background: #111827;
        border-radius: 0 0 14px 14px; z-index: 5;
    }
    .mt-app-screen {
        background: #fff; border-radius: 24px; overflow: hidden;
        min-height: 480px; position: relative;
        display: flex; flex-direction: column;
    }
    .mt-app-screen-head {
        padding: 2rem 1.1rem 0.9rem;
        display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
        border-bottom: 1px solid #F1F2F4;
    }
    .mt-app-screen-title { font-size: 14.5px; font-weight: 700; color: #111827; letter-spacing: -0.01em; }
    .mt-app-screen-time {
        font-size: 11px; color: #6B7280;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }
    .mt-app-list { padding: 0.9rem 1.1rem; display: flex; flex-direction: column; gap: 0.65rem; flex: 1; }
    .mt-app-list-item {
        display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
        padding: 0.75rem 0.85rem; border-radius: 14px;
        background: #F9FAFB; border: 1px solid #F1F2F4;
    }
    .mt-app-item-name { font-size: 13px; font-weight: 600; color: #111827; }
    .mt-app-item-sub { margin-top: 2px; font-size: 11.5px; color: #6B7280; }
    .mt-app-badge-open {
        flex-shrink: 0; white-space: nowrap;
        font-size: 10px; font-weight: 600; color: #047857;
        background: rgba(5,150,105,0.08); border: 1px solid rgba(5,150,105,0.22);
        border-radius: 999px; padding: 0.28rem 0.55rem;
    }
    .mt-app-badge-closed {
        flex-shrink: 0; white-space: nowrap;
        font-size: 10px; font-weight: 600; color: #6B7280;
        background: #F1F2F4; border: 1px solid #E5E7EB;
        border-radius: 999px; padding: 0.28rem 0.55rem;
    }
    .mt-app-navbar {
        display: flex; align-items: center; justify-content: space-around;
        padding: 0.7rem 0.5rem; border-top: 1px solid #F1F2F4; background: #fff;
    }
    .mt-app-nav-item {
        display: flex; flex-direction: column; align-items: center; gap: 0.25rem;
        font-size: 9.5px; font-weight: 500; color: #9CA3AF;
    }
    .mt-app-nav-item svg { width: 18px; height: 18px; }
    .mt-app-nav-active { color: #2563EB; }
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
