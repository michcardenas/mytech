@extends('layouts.app')

@section('content')
<section class="hero-simple">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    @php
                        $homeContent = [];
                        if (isset($page) && $page && $page->content) {
                            $homeContent = json_decode($page->content, true) ?? [];
                        }
                    @endphp

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
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="phone-mockup">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                <div class="website-demo">
                                    <!-- Header del sitio -->
                                    <div class="demo-header">
                                        <div class="demo-logo">TU LOGO</div>
                                        <div class="demo-menu">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Contenido principal -->
                                    <div class="demo-content">
                                        <div class="demo-title">Tu Negocio</div>
                                        <div class="demo-subtitle">Aquí va tu mensaje</div>
                                        <div class="demo-button">Contactar</div>
                                        
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
                                <div class="laptop-url">tuempresa.com</div>
                            </div>
                            <div class="laptop-content">
                                <div class="laptop-nav">
                                    <div class="nav-logo">TU EMPRESA</div>
                                    <div class="nav-links">
                                        <span>Inicio</span>
                                        <span>Servicios</span>
                                        <span>Contacto</span>
                                    </div>
                                </div>
                                <div class="laptop-hero">
                                    <div class="laptop-text">
                                        <div class="text-big">Bienvenido a tu empresa</div>
                                        <div class="text-small">Descripción de lo que haces</div>
                                        <div class="cta-button">Solicitar información</div>
                                    </div>
                                    <div class="laptop-image-placeholder">
                                        <span>Tu foto o logo</span>
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
            </div>
        </div>
    </div>
</section>
<!-- Sección de Logos de Clientes Mejorada -->
<section class="clients-logos">
    <div class="container">
        <div class="logos-header">
            <h3>{{ $homeContent['clients_title'] ?? 'Empresas que confían en mi trabajo' }}</h3>
            <p>{{ $homeContent['clients_subtitle'] ?? 'He desarrollado aplicaciones web exitosas para:' }}</p>
        </div>
        
        <div class="logos-carousel">
            <div class="logos-track">
                <!-- Logo 1 - VoyConVos -->
                <a href="https://voyconvos.com/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/voyconvos.png') }}" alt="VoyConVos" class="client-logo">
                    <span class="logo-label">VoyConVos</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 2 - Hostella -->
                <a href="https://hostella.co/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/hostella.png') }}" alt="Hostella" class="client-logo">
                    <span class="logo-label">Hostella</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 3 - FlexFood -->
                <a href="https://flexfood.es/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/flexfood.png') }}" alt="FlexFood" class="client-logo">
                    <span class="logo-label">FlexFood</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 4 - TuMesa -->
                <a href="https://tumesa.ar/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/tumesa.png') }}" alt="TuMesa" class="client-logo">
                    <span class="logo-label">TuMesa</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 5 - Calendarix -->
                <a href="https://calendarix.uy/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/calendarix.png') }}" alt="Calendarix" class="client-logo">
                    <span class="logo-label">Calendarix</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 6 - IPvestment -->
                <a href="https://ipinvestmentsrd.com/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/ipvestment.png') }}" alt="IPvestment" class="client-logo">
                    <span class="logo-label">IPvestment</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 7 - Jufman Kitchen -->
                <a href="https://jufmankitchendesigns.com/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/jufman.png') }}" alt="Jufman Kitchen" class="client-logo">
                    <span class="logo-label">Jufman Kitchen</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Logo 8 - Montano&Co -->
                <a href="https://montanoandco.net/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/montano.png') }}" alt="Montano&Co" class="client-logo">
                    <span class="logo-label">Montano&Co</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <!-- Duplicar para animación continua -->
                <a href="https://voyconvos.com/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/voyconvos.png') }}" alt="VoyConVos" class="client-logo">
                    <span class="logo-label">VoyConVos</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <a href="https://hostella.co/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/hostella.png') }}" alt="Hostella" class="client-logo">
                    <span class="logo-label">Hostella</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <a href="https://flexfood.es/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/flexfood.png') }}" alt="FlexFood" class="client-logo">
                    <span class="logo-label">FlexFood</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
                
                <a href="https://tumesa.ar/" target="_blank" rel="noopener noreferrer" class="logo-item">
                    <img src="{{ asset('images/logos/tumesa.png') }}" alt="TuMesa" class="client-logo">
                    <span class="logo-label">TuMesa</span>
                    <div class="logo-overlay">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Visitar sitio</span>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Indicador mejorado -->
        <div class="carousel-note">

<button class="button">
    <span>{{ $homeContent['clients_button_text'] ?? 'Conoce más de nuestro trabajo' }}</span>
</button>        </div>
    </div>
</section>

<style>
/* Sección de Logos de Clientes */
.clients-logos {
    background: white;
    padding: 4rem 0;
    border-top: 1px solid #e2e8f0;
    overflow: hidden;
}

.logos-header {
    text-align: center;
    margin-bottom: 3rem;
}

.logos-header h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.logos-header p {
    color: #64748b;
    font-size: 1rem;
    margin: 0;
}

.logos-carousel {
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}

.logos-track {
    display: flex;
    animation: logoScroll 30s linear infinite;
    gap: 3rem;
    align-items: center;
}

.logo-item {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    transition: all 0.3s ease;
    min-width: 120px;
}

.logo-item:hover {
    transform: translateY(-5px);
}

.client-logo {
    height: 60px;
    width: auto;
    max-width: 120px;
    object-fit: contain;
    filter: grayscale(100%) opacity(0.7);
    transition: all 0.3s ease;
}

.logo-item:hover .client-logo {
    filter: grayscale(0%) opacity(1);
    transform: scale(1.05);
}

.logo-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
    text-align: center;
    transition: color 0.3s ease;
}

.logo-item:hover .logo-label {
    color: #007BFF;
}

.carousel-note {
    text-align: center;
}

.carousel-note small {
    color: #10b981;
    font-weight: 600;
    background: rgba(16, 185, 129, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

/* Animación del carrusel */
@keyframes logoScroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* Pausa animación al hover */
.logos-carousel:hover .logos-track {
    animation-play-state: paused;
}
/* Sección de Logos de Clientes Mejorada */
.clients-logos {
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    padding: 5rem 0;
    border-top: 1px solid #e2e8f0;
    overflow: hidden;
    position: relative;
}

.clients-logos::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent 0%, #007BFF 50%, transparent 100%);
}

.logos-header {
    text-align: center;
    margin-bottom: 4rem;
}

.logos-header h3 {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 0.8rem;
    position: relative;
}

.logos-header h3::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
    border-radius: 2px;
}

.logos-header p {
    color: #64748b;
    font-size: 1.1rem;
    margin: 0;
    font-weight: 500;
}

.logos-carousel {
    position: relative;
    overflow: hidden;
    margin-bottom: 3rem;
    padding: 1rem 0;
}

.logos-track {
    display: flex;
    animation: logoScroll 35s linear infinite;
    gap: 4rem;
    align-items: center;
}

.logo-item {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.8rem;
    padding: 1.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 140px;
    text-decoration: none;
    position: relative;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    cursor: pointer;
    overflow: hidden;
}

.logo-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 86, 179, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.logo-item:hover::before {
    opacity: 1;
}

.logo-item:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 123, 255, 0.15);
    border-color: rgba(0, 123, 255, 0.2);
}

.client-logo {
    height: 70px;
    width: auto;
    max-width: 140px;
    object-fit: contain;
    filter: grayscale(80%) opacity(0.8);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 2;
    position: relative;
}

.logo-item:hover .client-logo {
    filter: grayscale(0%) opacity(1);
    transform: scale(1.1);
}

.logo-label {
    font-size: 0.9rem;
    font-weight: 700;
    color: #64748b;
    text-align: center;
    transition: all 0.3s ease;
    z-index: 2;
    position: relative;
    letter-spacing: 0.5px;
}

.logo-item:hover .logo-label {
    color: #007BFF;
    transform: translateY(-2px);
}

.logo-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 123, 255, 0.28);
    color: white;
    padding: 0.8rem 1.2rem;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 3;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.2);
    backdrop-filter: blur(15px);
}

.logo-item:hover .logo-overlay {
    opacity: 1;
    visibility: visible;
    transform: translate(-50%, -50%) scale(1);
}

.logo-overlay i {
    font-size: 0.9rem;
}

.carousel-note {
    text-align: center;
}

.stats-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
}

.stat-item strong {
    font-size: 1.5rem;
    font-weight: 800;
    color: #007BFF;
    line-height: 1;
}

.stat-item span {
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 600;
}

.stat-divider {
    width: 1px;
    height: 30px;
    background: linear-gradient(to bottom, transparent, #e2e8f0, transparent);
}

.carousel-subtitle {
    color: #10b981;
    font-weight: 600;
    font-size: 0.9rem;
    margin: 0;
    background: rgba(16, 185, 129, 0.1);
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    border: 1px solid rgba(16, 185, 129, 0.2);
    display: inline-block;
}

/* Animación del carrusel */
@keyframes logoScroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* Pausa animación al hover */
.logos-carousel:hover .logos-track {
    animation-play-state: paused;
}

/* Responsive mejorado */
@media (max-width: 768px) {
    .clients-logos {
        padding: 4rem 0 3rem 0;
    }
    
    .logos-header {
        margin-bottom: 3rem;
    }
    
    .logos-header h3 {
        font-size: 1.6rem;
    }
    
    .logos-header p {
        font-size: 1rem;
    }
    
    .logos-track {
        gap: 2.5rem;
    }
    
    .logo-item {
        min-width: 120px;
        padding: 1.2rem;
        gap: 0.6rem;
    }
    
    .client-logo {
        height: 60px;
        max-width: 120px;
    }
    
    .logo-label {
        font-size: 0.8rem;
    }
    
    .logo-overlay {
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
    }
    
    .btn-more-projects {
        padding: 0.9rem 1.8rem;
        font-size: 0.95rem;
        gap: 0.7rem;
    }
}

@media (max-width: 576px) {
    .clients-logos {
        padding: 3rem 0 2.5rem 0;
    }
    
    .logos-header {
        margin-bottom: 2.5rem;
    }
    
    .logos-header h3 {
        font-size: 1.4rem;
    }
    
    .logos-header p {
        font-size: 0.95rem;
    }
    
    .logos-track {
        gap: 2rem;
    }
    
    .logo-item {
        min-width: 100px;
        padding: 1rem;
    }
    
    .client-logo {
        height: 50px;
        max-width: 100px;
    }
    
    .logo-label {
        font-size: 0.75rem;
    }
    
    .logo-overlay {
        padding: 0.5rem 0.8rem;
        font-size: 0.75rem;
    }
    
    .btn-more-projects {
        padding: 0.8rem 1.5rem;
        font-size: 0.9rem;
        gap: 0.6rem;
    }
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
/* Responsive para logos */
@media (max-width: 768px) {
    .clients-logos {
        padding: 3rem 0;
    }
    
    .logos-header h3 {
        font-size: 1.5rem;
    }
    
    .logos-header p {
        font-size: 0.9rem;
    }
    
    .logos-track {
        gap: 2rem;
    }
    
    .logo-item {
        min-width: 100px;
        padding: 0.8rem;
    }
    
    .client-logo {
        height: 50px;
        max-width: 100px;
    }
    
    .logo-label {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .clients-logos {
        padding: 2rem 0;
    }
    
    .logos-header {
        margin-bottom: 2rem;
    }
    
    .logos-header h3 {
        font-size: 1.3rem;
    }
    
    .logos-track {
        gap: 1.5rem;
    }
    
    .logo-item {
        min-width: 80px;
        padding: 0.6rem;
    }
    
    .client-logo {
        height: 40px;
        max-width: 80px;
    }
    
    .logo-label {
        font-size: 0.7rem;
    }
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
</style>
@endsection