@extends('layouts.app')

@php
    $homeContent = [];
    if (isset($page) && $page && $page->content) {
        $homeContent = json_decode($page->content, true) ?? [];
    }
    $heroMedia = $homeContent['hero_media'] ?? ($homeContent['hero_image'] ?? null);
    $heroMediaExt = $heroMedia ? strtolower(pathinfo($heroMedia, PATHINFO_EXTENSION)) : null;
    $heroMediaIsVideo = in_array($heroMediaExt, ['mp4', 'webm', 'mov']);
@endphp

@if($heroMedia)
    @section('body_class', 'has-hero-media-page')
    @section('navbar_class', 'over-hero')
@endif

@section('content')
<section class="hero-simple {{ $heroMedia ? 'has-hero-media' : '' }}">
    @if($heroMedia)
        <div class="hero-bg-media" aria-hidden="true">
            @if($heroMediaIsVideo)
                <video id="heroVideo" src="{{ asset('storage/' . $heroMedia) }}"
                       autoplay muted loop playsinline preload="auto"></video>
            @else
                <img src="{{ asset('storage/' . $heroMedia) }}" alt="">
            @endif
        </div>
        <div class="hero-bg-overlay" aria-hidden="true"></div>
    @endif
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">

                    <div class="hero-badge">
                        {{ $homeContent['hero_badge'] ?? '💻 Tu Página Web Profesional' }}
                    </div>
                    <h1 class="hero-title">
                        {!! $homeContent['hero_title'] ?? 'Lleva tu <span class="text-blue">negocio</span> al mundo digital' !!}
                    </h1>
                    <p class="hero-description">
                        {!! $homeContent['hero_description'] ?? 'Creo páginas web que te ayudan a <strong>vender más</strong>, atraer nuevos clientes y hacer crecer tu negocio. Sin complicaciones técnicas.' !!}
                    </p>
                    
                    <div class="benefits">
                        <div class="benefit">
                            <span class="benefit-icon">✅</span>
                            <span>{{ $homeContent['benefit_1'] ?? 'Más clientes te encuentran en Google' }}</span>
                        </div>
                        <div class="benefit">
                            <span class="benefit-icon">✅</span>
                            <span>{{ $homeContent['benefit_2'] ?? 'Vendes las 24 horas del día' }}</span>
                        </div>
                        <div class="benefit">
                            <span class="benefit-icon">✅</span>
                            <span>{{ $homeContent['benefit_3'] ?? 'Te ves profesional ante la competencia' }}</span>
                        </div>
                    </div>

                    <div class="hero-actions">
                        <a href="#contacto" class="btn-primary">
                            <span>{{ $homeContent['hero_button_text'] ?? 'Quiero mi página web' }}</span>
                            <span class="btn-arrow">→</span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-visual-col">
                @if(!$heroMedia)
                <div class="hero-visual">
                    <div class="phone-mockup">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                <div class="website-demo">
                                    <!-- Header del sitio -->
                                    <div class="demo-header">
                                        <div class="demo-logo">MYTECH</div>
                                        <div class="demo-menu">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>

                                    <!-- Contenido principal -->
                                    <div class="demo-content">
                                        <div class="demo-title">Soluciones Web</div>
                                        <div class="demo-subtitle">Software a tu medida</div>
                                        <div class="demo-button">Cotizar</div>

                                        <div class="demo-gallery">
                                            <div class="demo-image"></div>
                                            <div class="demo-image"></div>
                                            <div class="demo-image"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="phone-label">
                            {{ $homeContent['phone_label'] ?? 'Así se verá en móvil' }}
                        </div>
                    </div>

                    <div class="laptop-mockup">
                        <div class="laptop-screen">
                            <div class="laptop-header">
                                <div class="laptop-dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <div class="laptop-url">mytechsolutionsco.com</div>
                            </div>
                            <div class="laptop-content">
                                <div class="laptop-nav">
                                    <div class="nav-logo">MYTECH SOLUTIONS</div>
                                    <div class="nav-links">
                                        <span>Inicio</span>
                                        <span>Servicios</span>
                                        <span>Contacto</span>
                                    </div>
                                </div>
                                <div class="laptop-hero">
                                    <div class="laptop-text">
                                        <div class="text-big">Software a tu medida</div>
                                        <div class="text-small">Desarrollo web profesional</div>
                                        <div class="cta-button">Comenzar proyecto</div>
                                    </div>
                                    <div class="laptop-image-placeholder">
                                        <span>💻</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="laptop-label">
                            {{ $homeContent['laptop_label'] ?? 'Así se verá en computadora' }}
                        </div>
                    </div>

                    <!-- Elementos de éxito -->
                    <div class="success-elements">
                        <div class="success-badge google">
                            <span class="badge-icon">🔍</span>
                            <div>
                                <strong></strong>
                                <small>{{ $homeContent['success_badge_1'] ?? 'Te encuentran fácil' }}</small>
                            </div>
                        </div>

                        <div class="success-badge sales">
                            <span class="badge-icon">💰</span>
                            <div>
                                <small>{{ $homeContent['success_badge_2'] ?? 'Más ventas 24/7 trabajando' }}</small>
                            </div>
                        </div>

                        <div class="success-badge professional">
                            <span class="badge-icon">⭐</span>
                            <div>
                                <small>{{ $homeContent['success_badge_3'] ?? 'Imagen confiable' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!-- Trust Strip — proyectos reales en reveal card -->
<section class="trust-strip">
    @php
        // Construir lista de proyectos para el reveal-card. Solo los que tengan logo real.
        $trustLogos = [];
        if (isset($proyectos) && $proyectos->count() > 0) {
            foreach ($proyectos as $proyecto) {
                if (!$proyecto->logo) continue; // ignorar los que no tienen logo
                $trustLogos[] = [
                    'url'  => $proyecto->url ?: route('proyectos.index'),
                    'name' => $proyecto->nombre,
                    'img'  => $proyecto->logo,
                    // marca true si la ruta del logo es absoluta o ya viene con storage/
                    'is_storage' => true,
                ];
            }
        }
        // Fallback al set hardcodeado si no hay proyectos
        if (empty($trustLogos)) {
            $trustLogos = [
                ['url' => 'https://voyconvos.com/',           'name' => 'VoyConVos',     'img' => 'voyconvos.png',  'is_storage' => false],
                ['url' => 'https://hostella.co/',             'name' => 'Hostella',      'img' => 'hostella.png',   'is_storage' => false],
                ['url' => 'https://flexfood.es/',             'name' => 'FlexFood',      'img' => 'flexfood.png',   'is_storage' => false],
                ['url' => 'https://tumesa.ar/',               'name' => 'TuMesa',        'img' => 'tumesa.png',     'is_storage' => false],
                ['url' => 'https://calendarix.uy/',           'name' => 'Calendarix',    'img' => 'calendarix.png', 'is_storage' => false],
                ['url' => 'https://ipinvestmentsrd.com/',     'name' => 'IPvestment',    'img' => 'ipvestment.png', 'is_storage' => false],
                ['url' => 'https://jufmankitchendesigns.com/','name' => 'Jufman Kitchen','img' => 'jufman.png',     'is_storage' => false],
                ['url' => 'https://montanoandco.net/',        'name' => 'Montano&Co',    'img' => 'montano.png',    'is_storage' => false],
            ];
        }
    @endphp

    <div class="container">
        <p class="trust-eyebrow-simple">
            {{ $homeContent['clients_subtitle'] ?? 'Equipos que confían en nuestro trabajo' }}
        </p>

        @php
            // Construimos un set extendido cicleando los logos hasta llenar 20 tiles + 1 CTA = 21 (grid 7x3)
            $extendedLogos = [];
            $cycleLen = max(1, count($trustLogos));
            for ($i = 0; $i < 20; $i++) {
                $extendedLogos[] = $trustLogos[$i % $cycleLen];
            }
        @endphp

        <div class="reveal-wrap">
            <div class="reveal-main">
                @foreach($extendedLogos as $i => $logo)
                    @php
                        $logoSrc = ($logo['is_storage'] ?? false)
                            ? asset('storage/' . $logo['img'])
                            : asset('images/logos/' . $logo['img']);
                    @endphp
                    <a href="{{ $logo['url'] }}" target="_blank" rel="noopener noreferrer"
                       class="reveal-tile" title="{{ $logo['name'] }}">
                        <img src="{{ $logoSrc }}" alt="{{ $logo['name'] }}" loading="lazy">
                    </a>
                @endforeach
                <a href="{{ route('proyectos.index') }}" class="reveal-tile reveal-tile-cta">
                    <span class="reveal-cta-label">Ver más</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
                <p class="reveal-text">
                    {!! $homeContent['reveal_hint'] ?? 'PASA EL MOUSE<br><br>PARA VER<br><br>NUESTRO TRABAJO' !!}
                </p>
                <div class="reveal-back" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Landing Pages -->
<section class="servicios-section-welcome">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="servicios-title-welcome">
                {{ $serviciosData['servicios_title'] ?? 'Nuestras Soluciones' }}
            </h2>
            <p class="servicios-subtitle-welcome">
                {{ $serviciosData['servicios_description'] ?? 'Descubre cómo podemos ayudarte a alcanzar tus objetivos digitales' }}
            </p>
        </div>

        @php
            // Construye una lista unificada de servicios.
            // Si hay landings activas, las usa. Si no, cae a los 6 servicios hardcodeados de $serviciosData.
            $serviciosTiles = [];

            if ($landings && $landings->count() > 0) {
                foreach ($landings as $landing) {
                    $serviciosTiles[] = [
                        'icon'  => 'fa-rocket',
                        'title' => $landing->title,
                        'desc'  => $landing->seo && $landing->seo->meta_description
                                    ? Str::limit($landing->seo->meta_description, 80)
                                    : Str::limit(strip_tags($landing->content ?? 'Descubre más sobre esta solución'), 80),
                        'url'   => route('landing.show', $landing->slug),
                    ];
                }
            } else {
                $defaults = [
                    ['fa-store', 'Marketplaces Personalizados', 'Plataformas de comercio adaptadas a tu nicho específico.'],
                    ['fa-calendar-check', 'Apps de Reservas y Citas', 'Sistemas inteligentes para gestionar citas y horarios.'],
                    ['fa-utensils', 'Plataformas de Restaurantes', 'Menús digitales con sistema de pedidos y gestión.'],
                    ['fa-building', 'Sistemas Administrativos', 'Plataformas para condominios, negocios y consultoras.'],
                    ['fa-globe', 'Páginas Web Profesionales', 'Sitios optimizados SEO con panel de control completo.'],
                    ['fa-cogs', 'Aplicaciones Personalizadas', 'CRM, ERP y sistemas a medida de tu industria.'],
                ];
                foreach ($defaults as $idx => [$icon, $title, $desc]) {
                    $n = $idx + 1;
                    $serviciosTiles[] = [
                        'icon'  => $serviciosData["servicio_{$n}_icon"] ?? 'fas ' . $icon,
                        'title' => $serviciosData["servicio_{$n}_title"] ?? $title,
                        'desc'  => $serviciosData["servicio_{$n}_description"] ?? $desc,
                        'url'   => null,
                    ];
                }
            }
        @endphp

        <div class="serv-grid">
            @foreach($serviciosTiles as $idx => $serv)
                @php
                    // Asegurar prefijo "fas" si solo viene fa-xxx
                    $iconCls = str_starts_with($serv['icon'], 'fa') && !str_contains($serv['icon'], ' ')
                        ? 'fas ' . $serv['icon']
                        : $serv['icon'];
                @endphp
                @if($serv['url'])
                <a href="{{ $serv['url'] }}" class="serv-tile" style="--tile-i: {{ $idx }};">
                @else
                <div class="serv-tile" style="--tile-i: {{ $idx }};">
                @endif
                    <span class="serv-tile-num">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="serv-tile-icon"><i class="{{ $iconCls }}"></i></span>
                    <h3 class="serv-tile-title">{{ $serv['title'] }}</h3>
                    <p class="serv-tile-text">{{ $serv['desc'] }}</p>
                    @if($serv['url'])
                        <span class="serv-tile-link">
                            Ver más
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    @endif
                @if($serv['url'])
                </a>
                @else
                </div>
                @endif
            @endforeach
        </div>

        <!-- CTA al final de servicios -->
        <div class="services-cta-welcome text-center">
            <h3 class="services-cta-title-welcome">
                {{ $serviciosData['cta_title'] ?? '¿Listo para Digitalizar tu Idea?' }}
            </h3>
            <p class="services-cta-text-welcome">
                {{ $serviciosData['cta_description'] ?? 'Conversemos sobre tu proyecto y descubre cómo podemos ayudarte a crear la solución perfecta para tu negocio.' }}
            </p>
            <a href="https://wa.me/{{ $serviciosData['whatsapp_number'] ?? '573337246403' }}?text={{ urlencode($serviciosData['whatsapp_message'] ?? 'Hola, me interesa conocer más sobre sus servicios de desarrollo web') }}"
               target="_blank"
               class="btn-whatsapp-welcome">
                <i class="fab fa-whatsapp"></i>
                <span>{{ $serviciosData['cta_button_text'] ?? 'Hablemos por WhatsApp' }}</span>
            </a>
        </div>
    </div>
</section>

<style>
/* === SERVICIOS — Tiles estilo Uiverse pero en paleta de marca === */
.serv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 3rem;
    /* Permitir que las tiles se expandan sobre las vecinas al rotar/escalar */
    overflow: visible;
}
.serv-tile {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.45rem;
    padding: 1.5rem 1.4rem 1.4rem;
    border-radius: 18px;
    background: var(--tile-bg, #d6e7ff);
    color: #0f172a;
    text-decoration: none;
    overflow: hidden;
    /* Mismo timing que el componente Uiverse referencia */
    transition: all 1s ease-in-out;
    border: 1px solid rgba(0, 86, 179, 0.05);
    isolation: isolate;
    z-index: 1;
}
.serv-tile > * { position: relative; z-index: 1; }
/* Animación calcada de Uiverse: scale + rotate 360deg + 1s ease-in-out */
.serv-tile:hover {
    transform: scale(1.15);
    rotate: 360deg;
    box-shadow: 0 22px 50px rgba(0, 86, 179, 0.28);
    z-index: 5;
    text-decoration: none;
    color: #0f172a;
}

/* Paleta — variaciones del azul corporativo (cycling) */
.serv-tile:nth-child(6n+1) { --tile-bg: #d6e7ff; --tile-accent: #0056b3; }
.serv-tile:nth-child(6n+2) { --tile-bg: #cfe2ff; --tile-accent: #003d82; }
.serv-tile:nth-child(6n+3) { --tile-bg: #e0eaff; --tile-accent: #1d4ed8; }
.serv-tile:nth-child(6n+4) { --tile-bg: #c8dafc; --tile-accent: #0056b3; }
.serv-tile:nth-child(6n+5) { --tile-bg: #d3def7; --tile-accent: #003d82; }
.serv-tile:nth-child(6n)   { --tile-bg: #bbd2f5; --tile-accent: #0056b3; }

/* Numerito grande estilo "01, 02..." */
.serv-tile-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--tile-accent);
    line-height: 1;
    letter-spacing: -0.04em;
    opacity: 0.85;
}

/* Icono en chip glassmórfico */
.serv-tile-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px; height: 44px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    color: var(--tile-accent);
    font-size: 1.15rem;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.6);
    transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    margin-top: 0.3rem;
}
.serv-tile:hover .serv-tile-icon {
    transform: scale(1.08);
}

.serv-tile-title {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0.45rem 0 0;
    line-height: 1.25;
    letter-spacing: -0.01em;
}
.serv-tile-text {
    font-size: 0.85rem;
    color: #475569;
    line-height: 1.5;
    margin: 0;
}

.serv-tile-link {
    margin-top: auto;
    padding-top: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--tile-accent);
    transition: gap 0.3s ease;
}
.serv-tile:hover .serv-tile-link { gap: 0.55rem; }
.serv-tile-link svg { transition: transform 0.3s ease; }
.serv-tile:hover .serv-tile-link svg { transform: translateX(2px); }

@media (prefers-reduced-motion: reduce) {
    .serv-tile, .serv-tile-icon, .serv-tile-link svg { transition: none; }
    .serv-tile:hover { transform: none; }
}

@media (max-width: 768px) {
    .serv-grid { grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.75rem; }
    .serv-tile { padding: 1.2rem 1.1rem; }
    .serv-tile-num { font-size: 1.8rem; }
    .serv-tile-icon { width: 40px; height: 40px; font-size: 1rem; }
    .serv-tile-title { font-size: 0.95rem; }
    .serv-tile-text { font-size: 0.8rem; }
}

/* === TRUST STRIP — Reveal card (inspirado en Uiverse / Praashoo7) === */
.trust-strip {
    background: #ffffff;
    padding: 4rem 0 5rem;
    position: relative;
}
.trust-strip .container { text-align: center; }

.trust-eyebrow-simple {
    text-align: center;
    font-size: 0.78rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin: 0 0 2.5rem;
}

/* Wrapper que centra la card */
.reveal-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem 0;
}

/* Tarjeta principal — grid responsivo 7×3 → 5×3 → 3×3 */
.reveal-main {
    position: relative;
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-template-rows: repeat(3, 1fr);
    gap: 0;
    width: 100%;
    max-width: 1100px;
    aspect-ratio: 7 / 3;
    cursor: pointer;
    isolation: isolate;
}

/* Background gradient detrás (paleta de marca: azul → azul oscuro) */
.reveal-back {
    position: absolute;
    inset: 0;
    border-radius: 22px;
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 60%, #003d82 100%);
    box-shadow: inset 0 0 180px 0 rgba(255, 255, 255, 0.18),
                0 25px 70px -18px rgba(0, 86, 179, 0.45);
    z-index: -2;
    transition: opacity 0.4s ease;
    pointer-events: none;
}

/* Texto centrado por defecto */
.reveal-text {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
    color: white;
    font-weight: 800;
    font-size: 1rem;
    text-align: center;
    letter-spacing: 0.3em;
    line-height: 1.2;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    z-index: 3;
    transition: opacity 0.35s ease;
    pointer-events: none;
}

/* Tiles de logos (glassmorphism por defecto, logo invisible) */
.reveal-tile {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.16);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid transparent;
    transition: all 0.4s ease-in-out;
    text-decoration: none;
    overflow: hidden;
}
.reveal-tile img,
.reveal-tile .reveal-cta-label,
.reveal-tile svg {
    opacity: 0;
    max-width: 100%;
    max-height: 95px;
    width: auto;
    height: auto;
    object-fit: contain;
    transition: opacity 0.35s ease 0.05s, transform 0.35s ease;
}

/* Drop-shadow ligero sobre los logos para que los blancos/claros sean visibles
   sobre cualquier fondo sin perder el color natural del logo */
.reveal-tile img {
    filter: drop-shadow(0 1px 2px rgba(15, 23, 42, 0.18))
            drop-shadow(0 0 1px rgba(15, 23, 42, 0.12));
}

/* Esquinas redondeadas dinámicas según breakpoint (7×3 desktop por defecto) */
.reveal-tile:first-child { border-top-left-radius: 22px; }
.reveal-tile:nth-child(7) { border-top-right-radius: 22px; }
.reveal-tile:nth-child(15) { border-bottom-left-radius: 22px; }
.reveal-tile-cta { border-bottom-right-radius: 22px; }

/* Hover sobre la tarjeta principal (desktop) o clase .is-revealed (mobile/scroll) */
.reveal-main:hover .reveal-back,
.reveal-main.is-revealed .reveal-back { opacity: 0; }
.reveal-main:hover .reveal-text,
.reveal-main.is-revealed .reveal-text { opacity: 0; }
.reveal-main:hover .reveal-tile,
.reveal-main.is-revealed .reveal-tile {
    margin: 6px;
    border-radius: 16px;
    /* Fondo con leve tinte slate-azul para que logos blancos/claros se distingan */
    background: linear-gradient(135deg, #f8fafc 0%, #eef2f6 100%);
    border: 1px solid rgba(0, 123, 255, 0.1);
    box-shadow: 0 6px 20px rgba(0, 86, 179, 0.08),
                inset 0 0 0 1px rgba(255, 255, 255, 0.6);
}
.reveal-main:hover .reveal-tile img,
.reveal-main:hover .reveal-tile .reveal-cta-label,
.reveal-main:hover .reveal-tile svg,
.reveal-main.is-revealed .reveal-tile img,
.reveal-main.is-revealed .reveal-tile .reveal-cta-label,
.reveal-main.is-revealed .reveal-tile svg {
    opacity: 1;
}

/* Hover individual sobre un tile — fondo azul de marca para resaltar
   el logo (incluso los blancos quedan a la vista contra el azul) */
.reveal-tile:hover {
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%) !important;
    border-color: transparent !important;
    box-shadow: 0 14px 32px rgba(0, 86, 179, 0.32) !important;
    transform: scale(1.06);
    z-index: 2;
}
.reveal-tile:hover img {
    transform: scale(1.1);
    /* Drop-shadow blanco sutil sobre fondo azul para logos oscuros/multicolor */
    filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.25))
            drop-shadow(0 0 0.5px rgba(255, 255, 255, 0.3));
}

/* Tile CTA "Ver más" — colores de marca */
.reveal-tile-cta {
    flex-direction: column;
    gap: 0.4rem;
    color: #0f172a;
}
.reveal-tile-cta .reveal-cta-label {
    font-size: 0.85rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #0f172a;
}
.reveal-main:hover .reveal-tile-cta,
.reveal-main.is-revealed .reveal-tile-cta {
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%) !important;
    color: white !important;
    border-color: transparent !important;
}
.reveal-main:hover .reveal-tile-cta .reveal-cta-label,
.reveal-main.is-revealed .reveal-tile-cta .reveal-cta-label {
    color: white !important;
}
.reveal-main:hover .reveal-tile-cta svg,
.reveal-main.is-revealed .reveal-tile-cta svg {
    color: white;
    stroke: white;
}
.reveal-tile-cta:hover {
    background: linear-gradient(135deg, #0056b3 0%, #003d82 100%) !important;
    border-color: transparent !important;
    box-shadow: 0 16px 36px rgba(0, 86, 179, 0.4) !important;
    transform: scale(1.06);
}
.reveal-tile-cta:hover svg {
    transform: translateX(4px);
}

@media (prefers-reduced-motion: reduce) {
    .reveal-tile,
    .reveal-tile img,
    .reveal-back,
    .reveal-text { transition: none; }
}

/* Touch devices: tap feedback en tiles + animación de entrada al revelar */
@media (hover: none) {
    .reveal-tile:active {
        background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%) !important;
        border-color: transparent !important;
        transform: scale(0.97);
    }
    .reveal-tile:active img {
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.25));
    }

    /* Transiciones más lentas en touch para que la apertura se sienta cinematográfica */
    .reveal-main.is-revealed .reveal-tile {
        transition: all 0.85s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .reveal-main.is-revealed .reveal-back { transition: opacity 0.9s ease 0.15s; }
    .reveal-main.is-revealed .reveal-text { transition: opacity 0.7s ease; }

    /* Fade-in encadenado de los tiles, ritmo pausado */
    .reveal-main.is-revealed .reveal-tile {
        animation: revealTileIn 0.95s cubic-bezier(0.22, 1, 0.36, 1) both;
    }
    .reveal-main.is-revealed .reveal-tile:nth-child(1)  { animation-delay: 0.05s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(2)  { animation-delay: 0.15s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(3)  { animation-delay: 0.25s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(4)  { animation-delay: 0.35s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(5)  { animation-delay: 0.45s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(6)  { animation-delay: 0.55s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(7)  { animation-delay: 0.65s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(8)  { animation-delay: 0.75s; }
    .reveal-main.is-revealed .reveal-tile:nth-child(n+9) { animation-delay: 0.85s; }
}

@keyframes revealTileIn {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
}

/* Tablet: 5×3 (15 tiles visibles, oculta 16-20) */
@media (max-width: 1024px) {
    .reveal-main {
        grid-template-columns: repeat(5, 1fr);
        aspect-ratio: 5 / 3;
        max-width: 760px;
    }
    /* Oculta tiles 16-20 (el CTA es el 21, sigue visible) */
    .reveal-tile:nth-child(n+16):not(.reveal-tile-cta) { display: none; }
    /* Resetear esquinas del layout 7×3 y aplicar las del 5×3 */
    .reveal-tile:nth-child(7) { border-top-right-radius: 0; }
    .reveal-tile:nth-child(15) { border-bottom-left-radius: 0; }
    .reveal-tile:nth-child(5) { border-top-right-radius: 22px; }
    .reveal-tile:nth-child(11) { border-bottom-left-radius: 22px; }
    .reveal-tile img { max-height: 80px; }
}

/* Mobile: 3×3 (8 logos + CTA, oculta 9-20) */
@media (max-width: 768px) {
    .trust-strip { padding: 3rem 0 4rem; }
    .trust-eyebrow-simple { font-size: 0.72rem; margin-bottom: 2rem; }
    .reveal-main {
        grid-template-columns: repeat(3, 1fr);
        aspect-ratio: 1 / 1;
        max-width: 380px;
    }
    .reveal-tile:nth-child(n+9):not(.reveal-tile-cta) { display: none; }
    /* Resetear esquinas del layout 5×3 y aplicar las del 3×3 */
    .reveal-tile:nth-child(5) { border-top-right-radius: 0; }
    .reveal-tile:nth-child(11) { border-bottom-left-radius: 0; }
    .reveal-tile:nth-child(3) { border-top-right-radius: 22px; }
    .reveal-tile:nth-child(7) { border-bottom-left-radius: 22px; }
    .reveal-tile img { max-height: 65px; }
    .reveal-text { font-size: 0.85rem; letter-spacing: 0.24em; }
    .reveal-tile-cta .reveal-cta-label { font-size: 0.75rem; }
}

@media (max-width: 380px) {
    .reveal-main {
        max-width: 290px;
    }
    .reveal-tile img { max-height: 52px; }
    .reveal-text { font-size: 0.7rem; letter-spacing: 0.2em; }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .logos-track {
        animation: none;
    }
    
    .logo-item,
    .client-logo,
    .logo-label,
    .logo-overlay {
        transition: none;
    }
}

/* Mejor experiencia en pantallas táctiles */
@media (hover: none) and (pointer: coarse) {
    .logo-item:hover {
        transform: none;
    }
    
    .logo-overlay {
        opacity: 80;
        visibility: visible;
        position: static;
        transform: none;
        background: rgba(0, 123, 255, 0);
        color: #007BFF;
        margin-top: 0.5rem;
        font-size: 0.7rem;
    }
    
    .logo-item:hover .client-logo {
        transform: none;
    }
}

/* Focus states para accesibilidad */
.logo-item:focus {
    outline: 2px solid #007BFF;
    outline-offset: 2px;
}

.logo-item:focus-visible {
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.3);
}

.hero-simple {
    background: linear-gradient(135deg, #f8fafc 0%, #e1e8ed 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
}

.hero-content {
    animation: fadeInLeft 1s ease-out;
}

.hero-badge {
    display: inline-block;
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 2rem;
    margin-top: 2rem;
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    color: #1e293b;
}

.text-blue {
    color: #007BFF;
}

.hero-description {
    font-size: 1.3rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 2rem;
}

.hero-description strong {
    color: #007BFF;
    font-weight: 700;
}

.benefits {
    margin-bottom: 2.5rem;
}

.benefit {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 1.1rem;
    color: #475569;
}

.benefit-icon {
    font-size: 1.2rem;
}

.hero-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 3rem;
}

.btn-primary {
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    color: white;
    padding: 18px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.2rem;
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.4);
    max-width: 300px;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 123, 255, 0.5);
    color: white;
}

.btn-arrow {
    font-size: 1.5rem;
    transition: transform 0.3s ease;
}

.btn-primary:hover .btn-arrow {
    transform: translateX(5px);
}

.trust-text {
    color: #10b981;
    font-weight: 600;
    text-align: center;
}

.hero-visual {
    animation: fadeInRight 1s ease-out;
    position: relative;
    height: 600px;
}

/* Phone Mockup */
.phone-mockup {
    position: absolute;
    top: 20px;
    right: 80px;
    z-index: 3;
}

.phone-frame {
    width: 200px;
    height: 360px;
    background: #1e293b;
    border-radius: 25px;
    padding: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.phone-screen {
    width: 100%;
    height: 100%;
    background: white;
    border-radius: 18px;
    overflow: hidden;
}

.website-demo {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.demo-header {
    background: #007BFF;
    color: white;
    padding: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.demo-logo {
    font-size: 0.8rem;
    font-weight: 700;
}

.demo-menu {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.demo-menu span {
    width: 15px;
    height: 2px;
    background: white;
}

.demo-content {
    padding: 15px;
    flex: 1;
}

.demo-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 5px;
}

.demo-subtitle {
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 10px;
}

.demo-button {
    background: #007BFF;
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 15px;
}

.demo-gallery {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 5px;
}

.demo-image {
    aspect-ratio: 1;
    background: #e2e8f0;
    border-radius: 8px;
}

.phone-label {
    text-align: center;
    margin-top: 10px;
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 600;
}

/* Laptop Mockup */
.laptop-mockup {
    position: absolute;
    top: 150px;
    left: 20px;
    z-index: 2;
}

.laptop-screen {
    width: 300px;
    height: 200px;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
}

.laptop-header {
    background: #f1f5f9;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #e2e8f0;
}

.laptop-dots {
    display: flex;
    gap: 4px;
}

.laptop-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.laptop-dots span:nth-child(1) { background: #ef4444; }
.laptop-dots span:nth-child(2) { background: #f59e0b; }
.laptop-dots span:nth-child(3) { background: #10b981; }

.laptop-url {
    font-size: 0.7rem;
    color: #64748b;
    font-weight: 500;
}

.laptop-content {
    height: calc(100% - 32px);
}

/* Hero con media de fondo (fullscreen) */
.hero-simple.has-hero-media {
    overflow: hidden;
    position: relative;
    padding: 0;
    min-height: 100vh;
    /* Fallback: si el video no carga o no autoplay, se ve este gradiente de paleta */
    background:
        radial-gradient(ellipse at 30% 30%, rgba(0, 123, 255, 0.35), transparent 55%),
        radial-gradient(ellipse at 75% 70%, rgba(124, 58, 237, 0.30), transparent 55%),
        linear-gradient(135deg, #0b1221 0%, #0f172a 60%, #1e293b 100%);
}

.hero-bg-media {
    position: absolute;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    animation: heroBgFade 1.1s cubic-bezier(0.22, 1, 0.36, 1);
}

.hero-bg-media img,
.hero-bg-media video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* Padding superior para que el texto no quede pegado al nav transparente */
.hero-simple.has-hero-media .container {
    padding-top: var(--navbar-height, 80px);
    padding-bottom: 2rem;
}

/* Respeto a usuarios con movimiento reducido */
@media (prefers-reduced-motion: reduce) {
    .hero-simple.has-hero-media .hero-bg-media video {
        display: none;
    }
    .hero-simple.has-hero-media .hero-bg-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 30% 30%, rgba(0, 123, 255, 0.4), transparent 60%),
            radial-gradient(ellipse at 75% 70%, rgba(124, 58, 237, 0.35), transparent 60%),
            linear-gradient(135deg, #0b1221 0%, #1e293b 100%);
    }
}

/* Overlay: doble capa — una clara del lado del texto, otra con tinte azul para unificar paleta */
.hero-bg-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background:
        linear-gradient(95deg,
            rgba(248, 250, 252, 0.98) 0%,
            rgba(248, 250, 252, 0.94) 38%,
            rgba(248, 250, 252, 0.68) 58%,
            rgba(248, 250, 252, 0.22) 82%,
            rgba(248, 250, 252, 0.05) 100%),
        linear-gradient(180deg,
            rgba(0, 123, 255, 0.07) 0%,
            transparent 45%,
            rgba(0, 86, 179, 0.10) 100%);
}

.hero-simple.has-hero-media .container {
    position: relative;
    z-index: 2;
}

.hero-simple.has-hero-media .hero-content {
    animation: heroContentIn 0.9s cubic-bezier(0.22, 1, 0.36, 1);
    position: relative;
    padding: 1.25rem 1.5rem 1.25rem 0;
}

/* Halo sutil detrás del texto para reforzar legibilidad sin "box" visible */
.hero-simple.has-hero-media .hero-content::before {
    content: '';
    position: absolute;
    inset: -0.5rem -2rem -0.5rem -2rem;
    background: radial-gradient(ellipse at 30% 50%,
        rgba(255, 255, 255, 0.85) 0%,
        rgba(255, 255, 255, 0.55) 40%,
        rgba(255, 255, 255, 0) 75%);
    z-index: -1;
    pointer-events: none;
    filter: blur(6px);
}

.hero-simple.has-hero-media .hero-badge {
    background: rgba(0, 123, 255, 0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 123, 255, 0.25);
    box-shadow: 0 4px 14px rgba(0, 123, 255, 0.12);
}

.hero-simple.has-hero-media .hero-title {
    color: #0f172a;
    text-shadow:
        0 1px 2px rgba(255, 255, 255, 0.75),
        0 2px 18px rgba(255, 255, 255, 0.4);
    letter-spacing: -0.01em;
}

.hero-simple.has-hero-media .hero-description {
    color: #1e293b;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
}

.hero-simple.has-hero-media .benefit {
    color: #0f172a;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.65);
    font-weight: 600;
}

.hero-simple.has-hero-media .btn-primary {
    box-shadow: 0 10px 30px rgba(0, 86, 179, 0.35), 0 2px 8px rgba(0, 86, 179, 0.2);
}

.hero-simple.has-hero-media .hero-visual-col { display: none; }

@keyframes heroBgFade {
    from { opacity: 0; transform: scale(1.04); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes heroContentIn {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Tablet: overlay vertical más uniforme */
@media (max-width: 992px) {
    .hero-simple.has-hero-media .hero-bg-overlay {
        background:
            linear-gradient(180deg,
                rgba(248, 250, 252, 0.95) 0%,
                rgba(248, 250, 252, 0.85) 45%,
                rgba(248, 250, 252, 0.72) 75%,
                rgba(248, 250, 252, 0.6) 100%),
            linear-gradient(180deg,
                rgba(0, 123, 255, 0.06) 0%,
                transparent 50%,
                rgba(0, 86, 179, 0.08) 100%);
    }
    .hero-simple.has-hero-media .hero-content::before {
        inset: -0.5rem -1rem;
    }
}

/* Móvil: overlay más sólido para máxima legibilidad */
@media (max-width: 576px) {
    .hero-simple.has-hero-media .hero-bg-overlay {
        background:
            linear-gradient(180deg,
                rgba(248, 250, 252, 0.97) 0%,
                rgba(248, 250, 252, 0.9) 50%,
                rgba(248, 250, 252, 0.78) 100%);
    }
    .hero-simple.has-hero-media .hero-content {
        padding: 1rem 0;
    }
    .hero-simple.has-hero-media .hero-content::before {
        display: none;
    }
}

.laptop-nav {
    background: #007BFF;
    color: white;
    padding: 8px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-logo {
    font-size: 0.8rem;
    font-weight: 700;
}

.nav-links {
    display: flex;
    gap: 10px;
    font-size: 0.6rem;
}

.laptop-hero {
    padding: 15px;
    display: flex;
    gap: 15px;
    height: calc(100% - 32px);
}

.laptop-text {
    flex: 1;
}

.text-big {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 5px;
}

.text-small {
    font-size: 0.6rem;
    color: #64748b;
    margin-bottom: 8px;
}

.cta-button {
    background: #007BFF;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.6rem;
    font-weight: 600;
    display: inline-block;
}

.laptop-image-placeholder {
    width: 80px;
    height: 60px;
    background: #e2e8f0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    color: #64748b;
    text-align: center;
}

.laptop-label {
    text-align: center;
    margin-top: 10px;
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 600;
}

/* Success Elements */
.success-elements {
    position: absolute;
    bottom: 50px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 15px;
}

.success-badge {
    background: white;
    border: 2px solid #007BFF;
    border-radius: 20px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 5px 20px rgba(0, 123, 255, 0.2);
    animation: badgeFloat 3s ease-in-out infinite;
    min-width: 120px;
}

.success-badge.google { animation-delay: 0s; }
.success-badge.sales { animation-delay: 1s; }
.success-badge.professional { animation-delay: 2s; }

.badge-icon {
    font-size: 1.5rem;
}

.success-badge strong {
    display: block;
    font-size: 0.8rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.success-badge small {
    display: block;
    font-size: 0.65rem;
    color: #64748b;
}

/* Animaciones */
@keyframes fadeInLeft {
    from { opacity: 0; transform: translateX(-50px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes fadeInRight {
    from { opacity: 0; transform: translateX(50px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes badgeFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
}

/* Responsive */
@media (max-width: 992px) {
    .phone-mockup {
        right: 40px;
        top: 50px;
    }
    
    .laptop-mockup {
        left: 10px;
        top: 200px;
    }
    
    .phone-frame {
        width: 160px;
        height: 280px;
    }
    
    .laptop-screen {
        width: 240px;
        height: 160px;
    }
}

/* Responsive - Móvil mejorado */
@media (max-width: 768px) {
    .hero-simple {
        padding: 2rem 0;
    }
    
    .hero-title {
        font-size: 2.2rem;
        margin-bottom: 1rem;
    }
    
    .hero-description {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .benefits {
        margin-bottom: 2rem;
    }
    
    .benefit {
        font-size: 0.95rem;
        margin-bottom: 10px;
    }
    
    .btn-primary {
        font-size: 1rem;
        padding: 14px 20px;
        max-width: 100%;
        justify-content: center;
        gap: 8px;
    }
    
    .hero-visual {
        height: auto;
        margin-top: 3rem;
        padding: 0 1rem;
    }
    
    /* Móvil: Stack vertical centrado */
    .phone-mockup {
        position: static;
        margin: 0 auto 2rem;
        display: block;
    }
    
    .phone-frame {
        width: 180px;
        height: 320px;
        margin: 0 auto;
    }
    
    .laptop-mockup {
        position: static;
        margin: 0 auto 2rem;
        display: block;
    }
    
    .laptop-screen {
        width: 280px;
        height: 180px;
        margin: 0 auto;
    }
    
    .success-elements {
        position: static;
        transform: none;
        justify-content: center;
        margin: 2rem auto 0;
        flex-wrap: wrap;
        max-width: 100%;
        padding: 0 1rem;
    }
    
    .success-badge {
        min-width: 90px;
        padding: 10px 8px;
        flex: 1;
        max-width: 120px;
    }
    
    .success-badge strong {
        font-size: 0.75rem;
    }
    
    .success-badge small {
        font-size: 0.6rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .hero-description {
        font-size: 0.95rem;
    }
    
    .benefit {
        font-size: 0.9rem;
    }
    
    .btn-primary {
        font-size: 0.95rem;
        padding: 12px 18px;
    }
    
    .phone-frame {
        width: 160px;
        height: 280px;
    }
    
    .laptop-screen {
        width: 250px;
        height: 160px;
    }
    
    .success-elements {
        gap: 6px;
        margin-top: 1.5rem;
    }
    
    .success-badge {
        min-width: 80px;
        padding: 8px 6px;
    }
    
    .badge-icon {
        font-size: 1.1rem;
    }
    
    .success-badge strong {
        font-size: 0.7rem;
    }
    
    .success-badge small {
        font-size: 0.55rem;
    }
}

.button {
    position: relative;
    text-decoration: none;
    color: #fff;
    background: linear-gradient(45deg, #007BFF, #10b981, #0056b3);
    padding: 14px 25px;
    border-radius: 10px;
    font-size: 1.25em;
    cursor: pointer;
    border: none;
    font-weight: 600;
}

.button span {
    position: relative;
    z-index: 1;
}

.button::before {
    content: "";
    position: absolute;
    inset: 1px;
    background: #1e293b;
    border-radius: 9px;
    transition: 0.5s;
}

.button:hover::before {
    opacity: 0.7;
}

.button::after {
    content: "";
    position: absolute;
    inset: 0px;
    background: linear-gradient(45deg, #007BFF, #10b981, #0056b3);
    border-radius: 9px;
    transition: 0.5s;
    opacity: 0;
    filter: blur(20px);
}

.button:hover::after {
    opacity: 1;
}

.button:hover {
    transform: translateY(-2px);
}

/* Estilos de la Sección de Servicios en Welcome */
.servicios-section-welcome {
    padding: 6rem 0;
    background: white;
    position: relative;
}

.servicios-section-welcome::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #007BFF 50%, transparent 100%);
}

.servicios-title-welcome {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
}

.servicios-subtitle-welcome {
    font-size: 1.2rem;
    color: #6c757d;
    max-width: 600px;
    margin: 0 auto;
}

.servicio-card-welcome {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    border: 1px solid rgba(0, 123, 255, 0.1);
    position: relative;
    overflow: hidden;
}

.servicio-card-welcome::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.servicio-card-welcome:hover::before {
    transform: scaleX(1);
}

.servicio-card-welcome:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0, 123, 255, 0.15);
    border-color: rgba(0, 123, 255, 0.3);
}

.servicio-icon-welcome {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
    transition: all 0.4s ease;
    box-shadow: 0 10px 30px rgba(0, 123, 255, 0.3);
}

.servicio-card-welcome:hover .servicio-icon-welcome {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 15px 40px rgba(0, 123, 255, 0.4);
}

.servicio-card-welcome h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #1e293b;
    text-align: center;
}

.servicio-card-welcome p {
    color: #6c757d;
    line-height: 1.7;
    margin-bottom: 1.5rem;
    text-align: center;
}

.servicio-features-welcome {
    list-style: none;
    padding: 0;
    margin: 0;
}

.servicio-features-welcome li {
    padding: 0.5rem 0;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.servicio-features-welcome li::before {
    content: '✓';
    color: #007BFF;
    font-weight: bold;
    width: 20px;
    text-align: center;
}

/* Estilos para Landing Cards */
.landing-card {
    display: flex;
    flex-direction: column;
}

.landing-card p {
    flex: 1;
    margin-bottom: 1.5rem;
}

.landing-card-footer {
    margin-top: auto;
    text-align: center;
}

.btn-landing-ver {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.btn-landing-ver:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
    color: white;
}

.btn-landing-ver i {
    transition: transform 0.3s ease;
}

.btn-landing-ver:hover i {
    transform: translateX(3px);
}

.services-cta-welcome {
    background: linear-gradient(135deg, #f8fafc 0%, #e1e8ed 100%);
    border-radius: 25px;
    padding: 3rem 2rem;
    border: 2px solid rgba(0, 123, 255, 0.1);
    position: relative;
    overflow: hidden;
}

.services-cta-welcome::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(0, 123, 255, 0.05) 0%, transparent 70%);
    animation: ctaPulse 8s ease-in-out infinite;
}

@keyframes ctaPulse {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(10%, 10%); }
}

.services-cta-title-welcome {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 1rem;
    position: relative;
}

.services-cta-text-welcome {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
}

.btn-whatsapp-welcome {
    display: inline-flex;
    align-items: center;
    gap: 0.8rem;
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    color: white;
    padding: 1.2rem 2.5rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(37, 211, 102, 0.3);
    position: relative;
}

.btn-whatsapp-welcome i {
    font-size: 1.5rem;
}

.btn-whatsapp-welcome:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(37, 211, 102, 0.4);
    color: white;
}

/* Responsive para servicios */
@media (max-width: 768px) {
    .servicios-section-welcome {
        padding: 4rem 0;
    }

    .servicios-title-welcome {
        font-size: 2rem;
    }

    .servicios-subtitle-welcome {
        font-size: 1rem;
    }

    .servicio-card-welcome {
        padding: 2rem;
    }

    .servicio-icon-welcome {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
    }

    .servicio-card-welcome h3 {
        font-size: 1.3rem;
    }

    .services-cta-welcome {
        padding: 2.5rem 1.5rem;
    }

    .services-cta-title-welcome {
        font-size: 1.7rem;
    }

    .services-cta-text-welcome {
        font-size: 1rem;
    }

    .btn-whatsapp-welcome {
        padding: 1rem 2rem;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .servicios-section-welcome {
        padding: 3rem 0;
    }

    .servicios-title-welcome {
        font-size: 1.7rem;
    }

    .servicios-subtitle-welcome {
        font-size: 0.95rem;
    }

    .servicio-card-welcome {
        padding: 1.5rem;
    }

    .servicio-icon-welcome {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .servicio-card-welcome h3 {
        font-size: 1.2rem;
    }

    .servicio-card-welcome p {
        font-size: 0.95rem;
    }

    .servicio-features-welcome li {
        font-size: 0.9rem;
    }

    .services-cta-welcome {
        padding: 2rem 1rem;
    }

    .services-cta-title-welcome {
        font-size: 1.5rem;
    }

    .services-cta-text-welcome {
        font-size: 0.95rem;
    }

    .btn-whatsapp-welcome {
        padding: 0.9rem 1.8rem;
        font-size: 0.95rem;
    }
}
</style>

<script>
    (function () {
        const v = document.getElementById('heroVideo');
        if (!v) return;
        let played = false;
        v.addEventListener('playing', () => { played = true; });
        const tryPlay = v.play();
        if (tryPlay && typeof tryPlay.catch === 'function') {
            tryPlay.catch(() => { v.style.display = 'none'; });
        }
        setTimeout(() => {
            if (!played && v.readyState < 2) { v.style.display = 'none'; }
        }, 2500);
    })();

    /* Reveal-card: en dispositivos sin :hover (touch), revelar al hacer scroll. */
    (function () {
        const card = document.querySelector('.reveal-main');
        if (!card) return;
        const isTouch = window.matchMedia('(hover: none)').matches
                     || !window.matchMedia('(hover: hover)').matches;
        if (!isTouch) return;

        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        card.classList.add('is-revealed');
                        obs.unobserve(card);
                    }
                });
            }, { threshold: 0.35, rootMargin: '0px 0px -10% 0px' });
            obs.observe(card);
        } else {
            card.classList.add('is-revealed');
        }
    })();
</script>
@endsection