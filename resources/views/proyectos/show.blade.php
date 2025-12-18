@extends('layouts.app')

@section('title', $proyecto->meta_title ?? $proyecto->nombre . ' - MY Tech Solutions')

@push('meta')
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $proyecto->meta_description ?? $proyecto->descripcion }}">
    <meta name="keywords" content="{{ $proyecto->meta_keywords ?? '' }}">
    <meta name="author" content="MY Tech Solutions">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('proyectos.show', $proyecto->slug) }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('proyectos.show', $proyecto->slug) }}">
    <meta property="og:title" content="{{ $proyecto->meta_title ?? $proyecto->nombre }}">
    <meta property="og:description" content="{{ $proyecto->meta_description ?? $proyecto->descripcion }}">
    <meta property="og:image" content="{{ $proyecto->og_image ? asset('storage/' . $proyecto->og_image) : ($proyecto->logo ? asset('storage/' . $proyecto->logo) : asset('images/default-og.jpg')) }}">
    <meta property="og:locale" content="es_ES">
    <meta property="og:site_name" content="MY Tech Solutions">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('proyectos.show', $proyecto->slug) }}">
    <meta property="twitter:title" content="{{ $proyecto->meta_title ?? $proyecto->nombre }}">
    <meta property="twitter:description" content="{{ $proyecto->meta_description ?? $proyecto->descripcion }}">
    <meta property="twitter:image" content="{{ $proyecto->og_image ? asset('storage/' . $proyecto->og_image) : ($proyecto->logo ? asset('storage/' . $proyecto->logo) : asset('images/default-og.jpg')) }}">

    <!-- JSON-LD Schema Markup -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "{{ $proyecto->nombre }}",
        "description": "{{ $proyecto->descripcion }}",
        "url": "{{ $proyecto->url }}",
        "applicationCategory": "{{ $proyecto->categoria }}",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "author": {
            "@type": "Organization",
            "name": "MY Tech Solutions",
            "url": "{{ url('/') }}"
        }
        @if($proyecto->fecha_lanzamiento)
        ,"datePublished": "{{ $proyecto->fecha_lanzamiento->format('Y-m-d') }}"
        @endif
        @if($proyecto->logo)
        ,"image": "{{ asset('storage/' . $proyecto->logo) }}"
        @endif
    }
    </script>
@endpush

@push('styles')
<style>
    /* ========================================
       TECHNOLOGICAL MODERN DESIGN SYSTEM
       Brand Colors & Animations
       ======================================== */
    :root {
        --color-primary: #007bff;
        --color-secondary: #00d4ff;
        --color-gradient-start: #1a2a6c;
        --color-gradient-mid: #0052a3;
        --color-gradient-end: #007bff;
        --color-light: #f8f9fa;
        --color-light-blue: #e3f2fd;
        --color-white: #ffffff;
        --color-dark: #1a1a1a;
        --color-gray-800: #2d3748;
        --color-gray-700: #4a5568;
        --color-gray-600: #718096;
        --color-gray-400: #cbd5e0;
        --color-gray-200: #e2e8f0;
        --color-gray-100: #f7fafc;
        --font-system: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 20px rgba(0, 123, 255, 0.2); }
        50% { box-shadow: 0 0 40px rgba(0, 123, 255, 0.4); }
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    body {
        font-family: var(--font-system);
        -webkit-font-smoothing: antialiased;
        background: var(--color-white);
        color: var(--color-dark);
    }

    /* ANIMATED BACKGROUND GRID */
    .tech-grid-bg {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(0, 212, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        opacity: 0.3;
        pointer-events: none;
    }

    /* HERO SECTION - COMPACT */
    .proyecto-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(227, 242, 253, 0.5) 0%, rgba(255, 255, 255, 1) 100%);
        padding: 6rem 0 3rem;
        overflow: hidden;
    }

    .proyecto-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(0, 123, 255, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 80% 50%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-container {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        z-index: 2;
    }

    .proyecto-badge-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--color-primary);
        padding: 0.5rem 1rem;
        background: rgba(0, 123, 255, 0.08);
        border-radius: 100px;
        border: 1px solid rgba(0, 123, 255, 0.2);
        margin-bottom: 1rem;
        animation: pulse-glow 3s ease-in-out infinite;
    }

    .proyecto-title-hero {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.02em;
        color: var(--color-dark);
        margin-bottom: 1rem;
        max-width: 900px;
        animation: fadeInUp 0.8s ease-out;
    }

    .proyecto-lead {
        font-size: 1.125rem;
        line-height: 1.6;
        color: var(--color-gray-700);
        max-width: 680px;
        font-weight: 400;
        margin-bottom: 2rem;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    .proyecto-lead p {
        margin-bottom: 0.75rem;
    }

    .proyecto-lead strong,
    .proyecto-lead b {
        font-weight: 700;
        color: var(--color-dark);
    }

    .proyecto-lead em,
    .proyecto-lead i {
        font-style: italic;
    }

    .proyecto-lead a {
        color: var(--color-primary);
        text-decoration: underline;
    }

    .hero-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--color-gray-200);
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--color-gray-600);
    }

    .meta-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--color-primary);
    }

    .hero-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
        flex-wrap: wrap;
        animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    .btn-primary {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: var(--color-primary);
        color: var(--color-white);
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 123, 255, 0.4);
        background: #0056b3;
        color: var(--color-white);
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        background: var(--color-white);
        color: var(--color-primary);
        border: 2px solid var(--color-primary);
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: var(--color-primary);
        color: var(--color-white);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 123, 255, 0.3);
    }

    /* SECTIONS - COMPACT */
    .content-section {
        position: relative;
        padding: 3.5rem 0;
        background: var(--color-white);
    }

    .content-section.bg-tech {
        background: var(--color-light);
        position: relative;
    }

    .content-section.bg-tech::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            linear-gradient(rgba(0, 123, 255, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 123, 255, 0.02) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }

    .section-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }

    .section-narrow {
        max-width: 800px;
        margin: 0 auto;
    }

    .section-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.75rem;
        display: inline-block;
    }

    .section-title {
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.02em;
        color: var(--color-dark);
        margin-bottom: 1.25rem;
    }

    .section-text {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--color-gray-700);
    }

    /* Quill Content Styling */
    .section-text p {
        margin-bottom: 1rem;
    }

    .section-text strong,
    .section-text b {
        font-weight: 700;
        color: var(--color-dark);
    }

    .section-text em,
    .section-text i {
        font-style: italic;
        color: var(--color-gray-800);
    }

    .section-text u {
        text-decoration: underline;
    }

    .section-text a {
        color: var(--color-primary);
        text-decoration: underline;
        transition: all 0.2s;
    }

    .section-text a:hover {
        color: var(--color-secondary);
    }

    .section-text ul,
    .section-text ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .section-text li {
        margin-bottom: 0.5rem;
        line-height: 1.7;
    }

    .section-text h1,
    .section-text h2,
    .section-text h3 {
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: var(--color-dark);
    }

    .section-text h1 {
        font-size: 2rem;
    }

    .section-text h2 {
        font-size: 1.5rem;
    }

    .section-text h3 {
        font-size: 1.25rem;
    }

    /* IMPACT SECTION */
    .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .impact-card {
        position: relative;
        text-align: center;
        padding: 2rem 1.5rem;
        background: var(--color-white);
        border: 2px solid var(--color-gray-200);
        border-radius: 1rem;
        transition: all 0.3s;
        overflow: hidden;
    }

    .impact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--color-primary);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .impact-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary);
        box-shadow: 0 10px 40px rgba(0, 123, 255, 0.15);
    }

    .impact-card:hover::before {
        opacity: 1;
    }

    .impact-number {
        font-size: 2.75rem;
        font-weight: 800;
        color: var(--color-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .impact-label {
        font-size: 0.8125rem;
        color: var(--color-gray-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
    }

    /* TECH TAGS */
    .tech-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.625rem;
        margin-top: 1.5rem;
    }

    .tech-tag {
        padding: 0.625rem 1.25rem;
        background: var(--color-white);
        border: 2px solid var(--color-gray-200);
        border-radius: 100px;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--color-gray-700);
        transition: all 0.3s;
    }

    .tech-tag:hover {
        background: var(--color-primary);
        border-color: var(--color-primary);
        color: var(--color-white);
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 123, 255, 0.3);
    }

    /* GALLERY - VISUAL FIRST */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .gallery-item {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 2px solid var(--color-gray-200);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: var(--color-white);
        cursor: pointer;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.15) 0%, rgba(0, 212, 255, 0.15) 100%);
        opacity: 0;
        transition: opacity 0.4s;
        z-index: 1;
        pointer-events: none;
    }

    .gallery-item:hover::before {
        opacity: 1;
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary);
        box-shadow: 0 15px 50px rgba(0, 123, 255, 0.2);
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        max-height: 500px;
        transition: transform 0.4s;
        position: relative;
        z-index: 0;
    }

    .gallery-item:hover img {
        transform: scale(1.03);
    }

    /* LIGHTBOX MODAL */
    .lightbox-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        cursor: zoom-out;
        animation: fadeIn 0.3s ease;
    }

    .lightbox-modal.active {
        display: flex;
    }

    .lightbox-modal img {
        max-width: 90%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 0.5rem;
        box-shadow: 0 25px 100px rgba(0, 0, 0, 0.5);
        animation: zoomIn 0.3s ease;
    }

    .lightbox-close {
        position: absolute;
        top: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        color: white;
        font-size: 2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        backdrop-filter: blur(10px);
    }

    .lightbox-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* TESTIMONIAL */
    .testimonial {
        max-width: 800px;
        margin: 2rem auto 0;
        padding: 2.5rem;
        background: var(--color-light-blue);
        border-radius: 1rem;
        border: 2px solid var(--color-primary);
        position: relative;
        overflow: hidden;
    }

    .testimonial::before {
        content: '"';
        position: absolute;
        top: 0.5rem;
        left: 1.5rem;
        font-size: 6rem;
        font-weight: 800;
        color: rgba(0, 123, 255, 0.08);
        line-height: 1;
    }

    .testimonial-text {
        position: relative;
        font-size: 1.25rem;
        line-height: 1.65;
        color: var(--color-gray-800);
        margin-bottom: 1.5rem;
        font-weight: 400;
        font-style: italic;
    }

    .testimonial-author {
        font-weight: 700;
        color: var(--color-primary);
        font-size: 1rem;
    }

    .testimonial-role {
        font-size: 0.8125rem;
        color: var(--color-gray-600);
        margin-top: 0.25rem;
    }

    /* RELATED PROJECTS */
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
        margin-top: 2rem;
    }

    .related-card {
        position: relative;
        display: block;
        padding: 2rem;
        background: var(--color-white);
        border: 2px solid var(--color-gray-200);
        border-radius: 1rem;
        text-decoration: none;
        transition: all 0.3s;
        overflow: hidden;
    }

    .related-card::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--color-primary);
        transform: translateX(-100%);
        transition: transform 0.3s;
    }

    .related-card:hover::before {
        transform: translateX(0);
    }

    .related-card:hover {
        border-color: var(--color-primary);
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 123, 255, 0.15);
    }

    .related-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-dark);
        margin-bottom: 0.5rem;
    }

    .related-desc {
        font-size: 0.875rem;
        color: var(--color-gray-700);
        line-height: 1.6;
    }

    /* CTA SECTION */
    .cta-section {
        position: relative;
        background: linear-gradient(135deg, #1a2a6c 0%, #0052a3 50%, #007bff 100%);
        background-size: 200% 200%;
        animation: gradient-shift 15s ease infinite;
        color: var(--color-white);
        padding: 5rem 0;
        text-align: center;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at center, rgba(0, 212, 255, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .cta-title {
        position: relative;
        font-size: clamp(1.875rem, 4vw, 3rem);
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 1rem;
        color: var(--color-white);
    }

    .cta-text {
        position: relative;
        font-size: 1.125rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    .cta-section .btn-secondary {
        background: transparent;
        border-color: rgba(255, 255, 255, 0.5);
        color: var(--color-white);
    }

    .cta-section .btn-secondary:hover {
        background: var(--color-white);
        border-color: var(--color-white);
        color: var(--color-primary);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .proyecto-hero {
            padding: 4rem 0 2rem;
        }

        .hero-meta {
            gap: 1.5rem;
        }

        .content-section {
            padding: 2.5rem 0;
        }

        .impact-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .testimonial {
            padding: 2rem 1.5rem;
        }

        .testimonial-text {
            font-size: 1.125rem;
        }

        .hero-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
            justify-content: center;
        }

        .cta-section {
            padding: 3.5rem 0;
        }
    }

    /* SCROLL ANIMATIONS */
    .scroll-animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="proyecto-hero">
    <div class="hero-container">
        <div class="proyecto-badge-hero">{{ $proyecto->badge_text }}</div>

        <h1 class="proyecto-title-hero">
            {{ $proyecto->nombre }} <span style="opacity: 0.7;">{{ $proyecto->bandera_emoji }}</span>
        </h1>

        <div class="proyecto-lead">{!! $proyecto->descripcion !!}</div>

        <div class="hero-actions">
            @if($proyecto->url)
                <a href="{{ $proyecto->url }}" target="_blank" class="btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 3h3v3M13 3L7 9M5 13h6a2 2 0 002-2V7"/></svg>
                    Visitar Proyecto
                </a>
            @endif
            <a href="{{ route('contacto.index') }}" class="btn-secondary">
                Proyecto Similar
            </a>
        </div>

        @if($proyecto->fecha_lanzamiento || $proyecto->duracion_desarrollo || $proyecto->equipo_size || $proyecto->visitas_mensuales)
        <div class="hero-meta">
            @if($proyecto->fecha_lanzamiento)
            <div class="meta-item">
                <span class="meta-label">Año</span>
                <span class="meta-value">{{ $proyecto->fecha_lanzamiento->format('Y') }}</span>
            </div>
            @endif
            @if($proyecto->duracion_desarrollo)
            <div class="meta-item">
                <span class="meta-label">Duración</span>
                <span class="meta-value">{{ $proyecto->duracion_desarrollo }}</span>
            </div>
            @endif
            @if($proyecto->equipo_size)
            <div class="meta-item">
                <span class="meta-label">Equipo</span>
                <span class="meta-value">{{ $proyecto->equipo_size }} personas</span>
            </div>
            @endif
            @if($proyecto->visitas_mensuales)
            <div class="meta-item">
                <span class="meta-label">Tráfico</span>
                <span class="meta-value">{{ number_format($proyecto->visitas_mensuales) }}/mes</span>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>

<!-- RESULTADOS E IMPACTO -->
@if($proyecto->resultados)
<section class="content-section bg-tech">
    <div class="section-container section-narrow scroll-animate">
        <div class="section-label">Impacto</div>
        <h2 class="section-title">Resultados</h2>
        <div class="section-text">
            {!! $proyecto->resultados !!}
        </div>
    </div>
</section>
@endif

<!-- SOBRE EL PROYECTO -->
@if($proyecto->descripcion_extendida)
<section class="content-section">
    <div class="section-container section-narrow scroll-animate">
        <div class="section-label">Contexto</div>
        <h2 class="section-title">Sobre el Proyecto</h2>
        <div class="section-text">
            {!! $proyecto->descripcion_extendida !!}
        </div>
    </div>
</section>
@endif

<!-- EL DESAFÍO Y LA SOLUCIÓN -->
@if($proyecto->desafio || $proyecto->solucion)
<section class="content-section bg-tech">
    <div class="section-container">
        <div class="row g-5">
            @if($proyecto->desafio)
            <div class="col-lg-6 scroll-animate">
                <div class="section-label">Problema</div>
                <h3 class="section-title">El Desafío</h3>
                <div class="section-text">
                    {!! $proyecto->desafio !!}
                </div>
            </div>
            @endif

            @if($proyecto->solucion)
            <div class="col-lg-6 scroll-animate">
                <div class="section-label">Enfoque</div>
                <h3 class="section-title">La Solución</h3>
                <div class="section-text">
                    {!! $proyecto->solucion !!}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- TECNOLOGÍAS -->
<section class="content-section">
    <div class="section-container section-narrow scroll-animate">
        <div class="section-label">Stack</div>
        <h2 class="section-title">Tecnologías</h2>
        <div class="tech-grid">
            @foreach($proyecto->tecnologias as $tecnologia)
                <span class="tech-tag">{{ $tecnologia }}</span>
            @endforeach
        </div>
    </div>
</section>

<!-- GALERÍA -->
@if($proyecto->galeria && count($proyecto->galeria) > 0)
<section class="content-section bg-tech">
    <div class="section-container scroll-animate">
        <div class="section-label">Visual</div>
        <h2 class="section-title">Galería del Proyecto</h2>
        <p class="section-text" style="margin-bottom: 2rem;">Haz clic en cualquier imagen para verla en tamaño completo</p>
        <div class="gallery-grid">
            @foreach($proyecto->galeria as $index => $imagen)
                <div class="gallery-item" onclick="openLightbox('{{ asset('storage/' . $imagen) }}')">
                    <img src="{{ asset('storage/' . $imagen) }}" alt="{{ $proyecto->nombre }} - Imagen {{ $index + 1 }}" loading="lazy">
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightbox" onclick="closeLightbox()">
    <div class="lightbox-close" onclick="closeLightbox()">&times;</div>
    <img src="" alt="Imagen ampliada" id="lightbox-img">
</div>
@endif

<!-- TESTIMONIO -->
@if($proyecto->testimonio)
<section class="content-section">
    <div class="section-container scroll-animate">
        <div class="testimonial">
            <div class="testimonial-text">"{{ $proyecto->testimonio }}"</div>
            <div class="testimonial-author">{{ $proyecto->testimonio_autor }}</div>
            @if($proyecto->testimonio_cargo)
                <div class="testimonial-role">{{ $proyecto->testimonio_cargo }}</div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- PROYECTOS RELACIONADOS -->
@if($proyectosRelacionados->count() > 0)
<section class="content-section bg-tech">
    <div class="section-container scroll-animate">
        <div class="section-label">Más Trabajos</div>
        <h2 class="section-title">Proyectos Relacionados</h2>
        <div class="related-grid">
            @foreach($proyectosRelacionados as $relacionado)
                <a href="{{ route('proyectos.show', $relacionado->slug) }}" class="related-card">
                    <h5 class="related-title">{{ $relacionado->nombre }} {{ $relacionado->bandera_emoji }}</h5>
                    <p class="related-desc">{!! Str::limit(strip_tags($relacionado->descripcion), 100) !!}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA FINAL -->
<section class="cta-section">
    <div class="section-container">
        <h2 class="cta-title">¿Listo para crear algo extraordinario?</h2>
        <p class="cta-text">Transformo ideas en productos digitales que generan resultados reales.</p>
        <div class="hero-actions" style="justify-content: center;">
            <a href="{{ route('contacto.index') }}" class="btn-primary">Iniciar Proyecto</a>
            <a href="{{ route('proyectos.index') }}" class="btn-secondary">Ver Más Proyectos</a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Scroll Animation Observer
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all elements with scroll-animate class
        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });
    });

    // Lightbox Functions
    function openLightbox(imageSrc) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');

        lightboxImg.src = imageSrc;
        lightbox.classList.add('active');

        // Prevent body scroll when lightbox is open
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.remove('active');

        // Restore body scroll
        document.body.style.overflow = '';
    }

    // Close lightbox with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });

    // Prevent img click from closing lightbox
    document.addEventListener('DOMContentLoaded', function() {
        const lightboxImg = document.getElementById('lightbox-img');
        if (lightboxImg) {
            lightboxImg.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
@endpush
@endsection
