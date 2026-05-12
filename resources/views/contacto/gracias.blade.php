@extends('layouts.app')

@section('title', '¡Solicitud recibida! - MY Tech Solutions')

@push('styles')
<style>
    /* ===========================================================
       PÁGINA DE GRACIAS - MY TECH SOLUTIONS
       Diseño tech/dev: grid animado, glassmorphism, SVG strokes
       =========================================================== */

    .ty-page {
        position: relative;
        min-height: calc(100vh - var(--navbar-height));
        overflow: hidden;
        background: radial-gradient(ellipse at top, #0a1628 0%, #050a14 100%);
        color: #e2e8f0;
        padding: 4rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Grid animado de fondo (tipo terminal) */
    .ty-grid-bg {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 123, 255, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 123, 255, 0.08) 1px, transparent 1px);
        background-size: 50px 50px;
        mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
        -webkit-mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
        animation: ty-grid-move 25s linear infinite;
    }

    @keyframes ty-grid-move {
        0%   { transform: translate(0, 0); }
        100% { transform: translate(50px, 50px); }
    }

    /* Glow orbs flotando */
    .ty-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.5;
        pointer-events: none;
    }
    .ty-orb-1 { width: 400px; height: 400px; background: #007BFF; top: -100px; left: -100px;  animation: ty-float 12s ease-in-out infinite; }
    .ty-orb-2 { width: 350px; height: 350px; background: #00d4ff; bottom: -80px; right: -80px; animation: ty-float 15s ease-in-out infinite reverse; }
    .ty-orb-3 { width: 250px; height: 250px; background: #25d366; top: 50%; left: 50%; transform: translate(-50%, -50%); animation: ty-pulse 8s ease-in-out infinite; opacity: 0.25; }

    @keyframes ty-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50%      { transform: translate(40px, -30px) scale(1.1); }
    }
    @keyframes ty-pulse {
        0%, 100% { opacity: 0.2; transform: translate(-50%, -50%) scale(1); }
        50%      { opacity: 0.4; transform: translate(-50%, -50%) scale(1.2); }
    }

    /* Partículas pequeñas */
    .ty-particle {
        position: absolute;
        width: 4px; height: 4px;
        background: #00d4ff;
        border-radius: 50%;
        box-shadow: 0 0 10px #00d4ff;
        animation: ty-particle-rise 8s linear infinite;
    }
    .ty-particle:nth-child(1)  { left: 10%; animation-delay: 0s;    animation-duration: 9s;  }
    .ty-particle:nth-child(2)  { left: 25%; animation-delay: 2s;    animation-duration: 11s; }
    .ty-particle:nth-child(3)  { left: 50%; animation-delay: 4s;    animation-duration: 7s;  }
    .ty-particle:nth-child(4)  { left: 70%; animation-delay: 1s;    animation-duration: 10s; }
    .ty-particle:nth-child(5)  { left: 85%; animation-delay: 3s;    animation-duration: 8s;  }
    .ty-particle:nth-child(6)  { left: 15%; animation-delay: 5s;    animation-duration: 12s; background: #007BFF; box-shadow: 0 0 10px #007BFF; }
    .ty-particle:nth-child(7)  { left: 60%; animation-delay: 6s;    animation-duration: 9s;  background: #25d366; box-shadow: 0 0 10px #25d366; }

    @keyframes ty-particle-rise {
        0%   { transform: translateY(100vh); opacity: 0; }
        10%  { opacity: 1; }
        90%  { opacity: 1; }
        100% { transform: translateY(-10vh); opacity: 0; }
    }

    /* Tarjeta principal con glassmorphism */
    .ty-card {
        position: relative;
        z-index: 2;
        max-width: 720px;
        width: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 123, 255, 0.25);
        border-radius: 24px;
        padding: 3.5rem 2.5rem;
        box-shadow:
            0 25px 60px rgba(0, 123, 255, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.08);
        animation: ty-card-in 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes ty-card-in {
        0%   { opacity: 0; transform: translateY(40px) scale(0.96); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Etiqueta tipo "terminal status" */
    .ty-status {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.5rem 1rem;
        background: rgba(37, 211, 102, 0.12);
        border: 1px solid rgba(37, 211, 102, 0.35);
        border-radius: 50px;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.85rem;
        color: #25d366;
        margin-bottom: 1.5rem;
        animation: ty-fade-up 0.6s 0.2s both;
    }
    .ty-status .ty-dot {
        width: 8px; height: 8px;
        background: #25d366;
        border-radius: 50%;
        box-shadow: 0 0 10px #25d366;
        animation: ty-blink 1.4s ease-in-out infinite;
    }
    @keyframes ty-blink {
        0%, 100% { opacity: 1; }
        50%      { opacity: 0.3; }
    }

    /* Checkmark animado SVG */
    .ty-check-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
        animation: ty-fade-up 0.6s 0.4s both;
    }
    .ty-check-svg {
        width: 120px; height: 120px;
        filter: drop-shadow(0 0 30px rgba(37, 211, 102, 0.6));
    }
    .ty-check-circle {
        fill: none;
        stroke: #25d366;
        stroke-width: 4;
        stroke-dasharray: 314;
        stroke-dashoffset: 314;
        animation: ty-draw-circle 0.9s 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .ty-check-tick {
        fill: none;
        stroke: #25d366;
        stroke-width: 6;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 50;
        stroke-dashoffset: 50;
        animation: ty-draw-tick 0.4s 1.3s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    @keyframes ty-draw-circle { to { stroke-dashoffset: 0; } }
    @keyframes ty-draw-tick   { to { stroke-dashoffset: 0; } }

    /* Título principal */
    .ty-title {
        font-size: clamp(2rem, 5vw, 2.8rem);
        font-weight: 800;
        text-align: center;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #ffffff 0%, #94a3b8 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.02em;
        animation: ty-fade-up 0.6s 0.6s both;
    }
    .ty-subtitle {
        text-align: center;
        color: #94a3b8;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2.5rem;
        animation: ty-fade-up 0.6s 0.8s both;
    }
    .ty-subtitle strong {
        color: #00d4ff;
        font-weight: 600;
    }

    /* Timeline "qué sigue" */
    .ty-timeline {
        position: relative;
        margin: 2.5rem 0;
        padding-left: 0;
        list-style: none;
        animation: ty-fade-up 0.6s 1s both;
    }
    .ty-timeline::before {
        content: '';
        position: absolute;
        left: 19px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: linear-gradient(180deg, #007BFF 0%, rgba(0, 123, 255, 0.1) 100%);
    }
    .ty-step {
        position: relative;
        padding-left: 60px;
        margin-bottom: 1.5rem;
        opacity: 0;
        animation: ty-fade-right 0.5s forwards;
    }
    .ty-step:nth-child(1) { animation-delay: 1.1s; }
    .ty-step:nth-child(2) { animation-delay: 1.3s; }
    .ty-step:nth-child(3) { animation-delay: 1.5s; }
    .ty-step:last-child   { margin-bottom: 0; }

    .ty-step-num {
        position: absolute;
        left: 0;
        top: 0;
        width: 40px; height: 40px;
        background: rgba(0, 123, 255, 0.15);
        border: 1px solid rgba(0, 123, 255, 0.4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00d4ff;
        font-weight: 700;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.9rem;
        box-shadow: 0 0 20px rgba(0, 123, 255, 0.25);
    }
    .ty-step-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0.4rem 0 0.3rem 0;
    }
    .ty-step-desc {
        color: #94a3b8;
        font-size: 0.95rem;
        line-height: 1.5;
        margin: 0;
    }

    /* Botones CTA */
    .ty-actions {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        margin-top: 2rem;
        animation: ty-fade-up 0.6s 1.7s both;
    }
    .ty-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 1rem 1.75rem;
        border-radius: 14px;
        font-weight: 600;
        text-decoration: none;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        border: none;
        cursor: pointer;
    }
    .ty-btn-wa {
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        color: #fff;
        box-shadow: 0 10px 30px rgba(37, 211, 102, 0.35);
    }
    .ty-btn-wa::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        transition: left 0.7s;
    }
    .ty-btn-wa:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(37, 211, 102, 0.5);
        color: #fff;
    }
    .ty-btn-wa:hover::before { left: 100%; }

    .ty-btn-secondary {
        background: rgba(255, 255, 255, 0.06);
        color: #e2e8f0;
        border: 1px solid rgba(0, 123, 255, 0.3);
    }
    .ty-btn-secondary:hover {
        background: rgba(0, 123, 255, 0.15);
        border-color: rgba(0, 123, 255, 0.6);
        color: #fff;
        transform: translateY(-2px);
    }

    @media (min-width: 640px) {
        .ty-actions { flex-direction: row; justify-content: center; }
        .ty-btn { min-width: 220px; }
    }

    /* Footer note */
    .ty-foot {
        text-align: center;
        margin-top: 2rem;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.8rem;
        color: #64748b;
        animation: ty-fade-up 0.6s 1.9s both;
    }
    .ty-foot .ty-blink-caret {
        display: inline-block;
        width: 8px;
        background: #00d4ff;
        margin-left: 2px;
        animation: ty-blink 1s steps(1) infinite;
    }

    /* Keyframes reutilizables */
    @keyframes ty-fade-up {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes ty-fade-right {
        from { opacity: 0; transform: translateX(-20px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* Accesibilidad */
    @media (prefers-reduced-motion: reduce) {
        .ty-grid-bg, .ty-orb, .ty-particle, .ty-status .ty-dot, .ty-foot .ty-blink-caret { animation: none !important; }
        .ty-card, .ty-step, .ty-actions, .ty-foot, .ty-status, .ty-check-wrapper, .ty-title, .ty-subtitle, .ty-timeline {
            animation: none !important;
            opacity: 1 !important;
            transform: none !important;
        }
        .ty-check-circle, .ty-check-tick { stroke-dashoffset: 0; animation: none; }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .ty-card { padding: 2.5rem 1.5rem; border-radius: 18px; }
        .ty-check-svg { width: 90px; height: 90px; }
        .ty-step { padding-left: 52px; }
        .ty-step-num { width: 36px; height: 36px; font-size: 0.8rem; }
        .ty-timeline::before { left: 17px; }
    }
</style>
@endpush

@section('content')
<section class="ty-page">
    {{-- Fondo animado --}}
    <div class="ty-grid-bg"></div>
    <div class="ty-orb ty-orb-1"></div>
    <div class="ty-orb ty-orb-2"></div>
    <div class="ty-orb ty-orb-3"></div>

    {{-- Partículas flotantes --}}
    <span class="ty-particle"></span>
    <span class="ty-particle"></span>
    <span class="ty-particle"></span>
    <span class="ty-particle"></span>
    <span class="ty-particle"></span>
    <span class="ty-particle"></span>
    <span class="ty-particle"></span>

    {{-- Tarjeta principal --}}
    <div class="ty-card">

        <div style="text-align: center;">
            <span class="ty-status">
                <span class="ty-dot"></span>
                <span>STATUS: <strong style="color:#fff;">REQUEST_RECEIVED</strong></span>
            </span>
        </div>

        <div class="ty-check-wrapper">
            <svg class="ty-check-svg" viewBox="0 0 120 120">
                <circle class="ty-check-circle" cx="60" cy="60" r="50"/>
                <path class="ty-check-tick" d="M38 62 L54 78 L84 46"/>
            </svg>
        </div>

        <h1 class="ty-title">¡Solicitud recibida con éxito!</h1>

        <p class="ty-subtitle">
            Gracias por confiar en <strong>MY Tech Solutions</strong>. Tu proyecto ya está en nuestra bandeja y un especialista te contactará en <strong>menos de 24 horas hábiles</strong>.
        </p>

        <ul class="ty-timeline">
            <li class="ty-step">
                <span class="ty-step-num">01</span>
                <h3 class="ty-step-title">Análisis de tu solicitud</h3>
                <p class="ty-step-desc">Revisamos los detalles, presupuesto y alcance para preparar una propuesta a tu medida.</p>
            </li>
            <li class="ty-step">
                <span class="ty-step-num">02</span>
                <h3 class="ty-step-title">Reunión de descubrimiento</h3>
                <p class="ty-step-desc">Te contactamos para entender a fondo tus objetivos y resolver tus dudas técnicas.</p>
            </li>
            <li class="ty-step">
                <span class="ty-step-num">03</span>
                <h3 class="ty-step-title">Propuesta y arranque</h3>
                <p class="ty-step-desc">Recibes una propuesta clara con tiempos, entregables y costos. Sin sorpresas.</p>
            </li>
        </ul>

        <div class="ty-actions">
            <a href="https://wa.me/573337246403?text=Hola%20MyTech%20Solutions%2C%20acabo%20de%20enviar%20mi%20solicitud%20de%20proyecto%20y%20me%20gustar%C3%ADa%20agilizar%20la%20conversaci%C3%B3n."
               target="_blank"
               rel="noopener"
               class="ty-btn ty-btn-wa">
                <i class="fab fa-whatsapp"></i>
                Agilizar por WhatsApp
            </a>
            <a href="{{ route('proyectos.index') }}" class="ty-btn ty-btn-secondary">
                <i class="fas fa-rocket"></i>
                Ver nuestros proyectos
            </a>
        </div>

        <p class="ty-foot">
            $ mytech --status<span class="ty-blink-caret">&nbsp;</span>
        </p>
    </div>
</section>

{{-- ====================================================== --}}
{{-- TRACKING DE CONVERSIÓN                                   --}}
{{-- ====================================================== --}}

{{-- dataLayer push para GTM (siempre activo) --}}
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'form_submit_contacto',
        'form_name': 'contacto_principal',
        'form_destination': '/gracias'
    });
</script>

{{-- Meta Pixel: evento Lead --}}
@if(config('services.meta.pixel_id'))
<script>
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Lead', {
            content_name: 'Formulario de contacto',
            content_category: 'Lead generation'
        });
    }
</script>
@endif

{{-- Google Ads: conversión (solo si está configurado en .env) --}}
@if(config('services.google_ads.conversion_id') && config('services.google_ads.conversion_label'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_ads.conversion_id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('services.google_ads.conversion_id') }}');
    gtag('event', 'conversion', {
        'send_to': '{{ config('services.google_ads.conversion_id') }}/{{ config('services.google_ads.conversion_label') }}'
    });
</script>
@endif
@endsection
