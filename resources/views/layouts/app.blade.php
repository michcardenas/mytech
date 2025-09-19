<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="msvalidate.01" content="808CD1DC4ADF1CDC768B784CFB343FAD" />
<meta name="google-site-verification" content="Yk8ILwU3yKtRTW0Zspxa9tKAFR3mRyI3idT0SpNvSIo" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- TITLE - SEO o fallback --}}
    <title>
        @if(isset($seo) && $seo && $seo->meta_title)
            {{ $seo->meta_title }}
        @else
            @yield('title', 'MY Tech Solutions')
        @endif
    </title>
    
    {{-- META DESCRIPTION --}}
    @if(isset($seo) && $seo && $seo->meta_description)
        <meta name="description" content="{{ $seo->meta_description }}">
    @endif
    
    {{-- META KEYWORDS --}}
    @if(isset($seo) && $seo && $seo->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
    
    {{-- ROBOTS --}}
    @if(isset($seo) && $seo && $seo->robots)
        <meta name="robots" content="{{ $seo->robots }}">
    @else
        <meta name="robots" content="index, follow">
    @endif
    
    {{-- CANONICAL URL --}}
    @if(isset($seo) && $seo && $seo->canonical_url)
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @endif
    
    {{-- FOCUS KEYWORD (para análisis interno) --}}
    @if(isset($seo) && $seo && $seo->focus_keyword)
        <meta name="focus-keyword" content="{{ $seo->focus_keyword }}">
    @endif

    {{-- OPEN GRAPH (FACEBOOK/LINKEDIN) --}}
    @if(isset($seo) && $seo)
        {{-- OG Title --}}
        <meta property="og:title" content="{{ $seo->og_title ?: ($seo->meta_title ?: 'MY Tech Solutions') }}">
        
        {{-- OG Description --}}
        <meta property="og:description" content="{{ $seo->og_description ?: ($seo->meta_description ?: 'Soluciones tecnológicas profesionales') }}">
        
        {{-- OG Type --}}
        <meta property="og:type" content="{{ $seo->og_type ?: 'website' }}">
        
        {{-- OG URL --}}
        @if($seo->og_url)
            <meta property="og:url" content="{{ $seo->og_url }}">
        @elseif($seo->canonical_url)
            <meta property="og:url" content="{{ $seo->canonical_url }}">
        @else
            <meta property="og:url" content="{{ request()->url() }}">
        @endif
        
        {{-- OG Image --}}
        @if($seo->og_image)
            <meta property="og:image" content="{{ $seo->og_image }}">
            <meta property="og:image:alt" content="{{ $seo->og_title ?: $seo->meta_title ?: 'MY Tech Solutions' }}">
        @endif
        
        {{-- OG Site Name --}}
        <meta property="og:site_name" content="{{ $seo->og_site_name ?: 'MY Tech Solutions' }}">
    @else
        {{-- Fallback Open Graph si no hay SEO --}}
        <meta property="og:title" content="@yield('title', 'MY Tech Solutions')">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ request()->url() }}">
        <meta property="og:site_name" content="MY Tech Solutions">
    @endif

    {{-- TWITTER CARDS --}}
    @if(isset($seo) && $seo)
        {{-- Twitter Card Type --}}
        <meta name="twitter:card" content="{{ $seo->twitter_card ?: 'summary_large_image' }}">
        
        {{-- Twitter Title --}}
        <meta name="twitter:title" content="{{ $seo->twitter_title ?: ($seo->og_title ?: ($seo->meta_title ?: 'MY Tech Solutions')) }}">
        
        {{-- Twitter Description --}}
        <meta name="twitter:description" content="{{ $seo->twitter_description ?: ($seo->og_description ?: ($seo->meta_description ?: 'Soluciones tecnológicas profesionales')) }}">
        
        {{-- Twitter Image --}}
        @if($seo->twitter_image)
            <meta name="twitter:image" content="{{ $seo->twitter_image }}">
        @elseif($seo->og_image)
            <meta name="twitter:image" content="{{ $seo->og_image }}">
        @endif
        
        {{-- Twitter Site --}}
        @if($seo->twitter_site)
            <meta name="twitter:site" content="{{ $seo->twitter_site }}">
        @endif
        
        {{-- Twitter Creator --}}
        @if($seo->twitter_creator)
            <meta name="twitter:creator" content="{{ $seo->twitter_creator }}">
        @endif
    @else
        {{-- Fallback Twitter si no hay SEO --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('title', 'MY Tech Solutions')">
    @endif

    {{-- STRUCTURED DATA / SCHEMA.ORG --}}
    @if(isset($seo) && $seo && $seo->schema_markup)
        <script type="application/ld+json">
            {!! is_string($seo->schema_markup) ? $seo->schema_markup : json_encode($seo->schema_markup) !!}
        </script>
    @else
        {{-- Schema básico por defecto --}}
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "MY Tech Solutions",
            "url": "{{ request()->root() }}",
            "description": "Soluciones tecnológicas profesionales"
        }
        </script>
    @endif

    {{-- FAVICON --}}
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icon.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icon.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/icon.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">

    {{-- BOOTSTRAP CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- GOOGLE FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- SITEMAP HINT (para crawlers) --}}
    @if(isset($seo) && $seo && $seo->sitemap_include)
        <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">
    @endif

    {{-- ESTILOS ADICIONALES DE PÁGINAS ESPECÍFICAS --}}
    @stack('styles')

    <style>
        :root {
            --primary-blue: #007BFF;
            --primary-dark: #0056b3;
            --dark-text: #2c3e50;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
            --shadow-soft: 0 10px 30px rgba(0, 123, 255, 0.1);
            --shadow-hover: 0 20px 40px rgba(0, 123, 255, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --navbar-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--dark-text);
            background: var(--white);
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 20px rgba(0, 123, 255, 0.1);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: var(--transition);
            height: var(--navbar-height);
        }

        .navbar-custom.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 2px 30px rgba(0, 123, 255, 0.15);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            z-index: 1001;
        }

        .logo-img {
            height: 50px;
            width: auto;
            max-width: 200px;
            object-fit: contain;
        }

        /* Desktop Navigation */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
            margin: 0;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-link {
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: var(--transition);
            padding: 0.5rem 0;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--primary-blue);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-blue);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-contact {
            background: var(--gradient-blue);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            color: white;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-text);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            z-index: 1001;
        }

        .mobile-menu-btn:hover {
            background: rgba(0, 123, 255, 0.1);
        }

        .mobile-menu-btn.active {
            color: var(--primary-blue);
        }

        /* Mobile Navigation Overlay */
        .mobile-nav-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Navigation */
        .mobile-nav {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100vh;
            background: var(--white);
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transition: var(--transition);
            padding: var(--navbar-height) 0 2rem 0;
        }

        .mobile-nav.active {
            right: 0;
        }

        .mobile-nav-menu {
            display: flex;
            flex-direction: column;
            gap: 0;
            padding: 1rem 0;
        }

        .mobile-nav-menu li {
            list-style: none;
        }

        .mobile-nav-menu .nav-link {
            display: block;
            padding: 1rem 2rem;
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .mobile-nav-menu .nav-link:hover {
            background: rgba(0, 123, 255, 0.05);
            color: var(--primary-blue);
            padding-left: 2.5rem;
        }

        .mobile-nav-menu .nav-link::after {
            display: none;
        }

        .mobile-contact {
            margin: 2rem;
            padding: 1rem;
            background: var(--gradient-blue);
            color: white;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .mobile-contact:hover {
            transform: translateY(-2px);
            color: white;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-top: var(--navbar-height);
            min-height: calc(100vh - var(--navbar-height));
        }

        /* ===== FOOTER ===== */
        .footer-custom {
            background: var(--dark-text);
            color: white;
            padding: 3rem 0 1rem;
            text-align: center;
        }

        .footer-brand {
            margin-bottom: 2rem;
        }

        .footer-logo {
            width: 60px;
            height: 60px;
            background: var(--gradient-blue);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .footer-brand h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .footer-brand p {
            color: #adb5bd;
            margin: 0;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .social-link {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            font-size: 1.2rem;
        }

        .social-link:hover {
            background: var(--primary-blue);
            transform: translateY(-3px);
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 2rem;
            color: #adb5bd;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */
        
        /* Tablets y móviles */
        @media (max-width: 768px) {
            :root {
                --navbar-height: 70px;
            }

            .nav-menu {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .navbar-container {
                padding: 0 1rem;
            }

            .logo-img {
                height: 40px;
            }

            .main-content {
                margin-top: var(--navbar-height);
            }

            .mobile-nav {
                width: 280px;
                right: -280px;
            }

            .footer-custom {
                padding: 2.5rem 0 1rem;
            }

            .social-link {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }

        /* Móviles pequeños */
        @media (max-width: 480px) {
            :root {
                --navbar-height: 65px;
            }

            .navbar-container {
                padding: 0 0.8rem;
            }

            .logo-img {
                height: 35px;
            }

            .mobile-nav {
                width: 100%;
                right: -100%;
            }

            .mobile-nav-menu .nav-link {
                padding: 1.2rem 1.5rem;
                font-size: 1rem;
            }

            .mobile-contact {
                margin: 1.5rem;
            }

            .footer-custom {
                padding: 2rem 0 1rem;
            }

            .footer-logo {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .footer-brand h3 {
                font-size: 1.3rem;
            }

            .social-link {
                width: 42px;
                height: 42px;
                font-size: 1rem;
            }
        }

        /* Mejoras de accesibilidad */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Mejor experiencia en pantallas táctiles */
        @media (hover: none) and (pointer: coarse) {
            .nav-link:hover,
            .mobile-nav-menu .nav-link:hover {
                background: rgba(0, 123, 255, 0.1);
            }
        }

        .whatsapp-float {
    position: fixed;
    bottom: 25px;
    right: 25px;
    z-index: 9999;
    animation: fadeInUp 0.8s ease-out;
}

.whatsapp-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
    border-radius: 50%;
    color: white;
    text-decoration: none;
    font-size: 1.8rem;
    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.3);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.whatsapp-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at center, rgba(255,255,255,0.3) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.whatsapp-btn:hover::before {
    opacity: 1;
}

.whatsapp-btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 35px rgba(37, 211, 102, 0.4);
    color: white;
}

.whatsapp-btn i {
    position: relative;
    z-index: 2;
    animation: pulse 2s infinite;
}

/* Tooltip */
.whatsapp-tooltip {
    position: absolute;
    right: 70px;
    top: 50%;
    transform: translateY(-50%);
    background: #1e293b;
    color: white;
    padding: 0.7rem 1rem;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    z-index: 1;
}

.whatsapp-tooltip::after {
    content: '';
    position: absolute;
    left: 100%;
    top: 50%;
    transform: translateY(-50%);
    border: 6px solid transparent;
    border-left-color: #1e293b;
}

.whatsapp-btn:hover .whatsapp-tooltip {
    opacity: 1;
    visibility: visible;
    right: 75px;
}

/* Animaciones */
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

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .whatsapp-float {
        bottom: 20px;
        right: 20px;
    }
    
    .whatsapp-btn {
        width: 55px;
        height: 55px;
        font-size: 1.6rem;
    }
    
    .whatsapp-tooltip {
        right: 65px;
        font-size: 0.8rem;
        padding: 0.6rem 0.8rem;
    }
    
    .whatsapp-btn:hover .whatsapp-tooltip {
        right: 70px;
    }
}

@media (max-width: 480px) {
    .whatsapp-float {
        bottom: 15px;
        right: 15px;
    }
    
    .whatsapp-btn {
        width: 50px;
        height: 50px;
        font-size: 1.4rem;
    }
    
    /* Ocultar tooltip en móviles muy pequeños */
    .whatsapp-tooltip {
        display: none;
    }
}

/* Mejoras de accesibilidad */
@media (prefers-reduced-motion: reduce) {
    .whatsapp-btn i {
        animation: none;
    }
    
    .whatsapp-float {
        animation: none;
    }
    
    .whatsapp-btn,
    .whatsapp-tooltip {
        transition: none;
    }
}

/* Focus states para accesibilidad */
.whatsapp-btn:focus {
    outline: 3px solid rgba(37, 211, 102, 0.5);
    outline-offset: 2px;
}

.whatsapp-btn:focus-visible {
    box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.3);
}
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar-custom" id="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="logo-img">
            </a>

            <!-- Desktop Navigation -->
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link">Inicio</a></li>
                <li><a href="{{ route('servicios.index') }}" class="nav-link">Servicios</a></li>
                <li><a href="{{ route('proyectos.index') }}" class="nav-link">Proyectos</a></li>
                <li><a href="{{ route('sobre_nosotros.index') }}" class="nav-link">Sobre Nosotros</a></li>
                <li>
                    <a href="{{ route('contacto.index') }}" class="btn-contact">
                        <i class="fas fa-comments"></i>
                        Hablemos
                    </a>
                </li>
            </ul>

            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Abrir menú de navegación">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav" id="mobileNav">
        <ul class="mobile-nav-menu">
            <li><a href="{{ route('home') }}" class="nav-link">Inicio</a></li>
            <li><a href="{{ route('servicios.index') }}" class="nav-link">Servicios</a></li>
            <li><a href="{{ route('proyectos.index') }}" class="nav-link">Proyectos</a></li>
            <li><a href="{{ route('sobre_nosotros.index') }}" class="nav-link">Sobre Nosotros</a></li>
        </ul>
        <a href="{{ route('contacto.index') }}" class="mobile-contact">
            <i class="fas fa-comments"></i>
            Contactar
        </a>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>

<footer class="footer-custom">              
    <div class="container">                      
        <div class="footer-brand">                                  
            <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions Logo" class="logo-img">                                         
            <h3>MY Tech Solutions</h3>                              
            <p>Desarrollo web profesional que impulsa tu negocio</p>                      
        </div>                                

        <div class="social-links">                              
          
            <a href="https://www.facebook.com/profile.php?id=61575108256490" target="_blank" class="social-link" title="Facebook" aria-label="Visitar Facebook">                                      
                <i class="fab fa-facebook-f"></i>                              
            </a>
            <a href="https://www.instagram.com/mytech_solutions?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="social-link" title="Instagram" aria-label="Visitar Instagram">                                      
                <i class="fab fa-instagram"></i>                              
            </a>                             
            <a href="https://wa.me/573123708407" target="_blank" class="social-link" title="WhatsApp" aria-label="Contactar por WhatsApp">                                      
                <i class="fab fa-whatsapp"></i>                              
            </a>                              
            <a href="mailto:" class="social-link" title="Email" aria-label="Enviar email">                                      
                <i class="fas fa-envelope"></i>                              
            </a>                      
        </div>                                

        <div class="footer-bottom">                              
            <p>&copy; {{ date('Y') }} MY Tech Solutions. Todos los derechos reservados.</p>                      
        </div>              
    </div>      
</footer>
<div class="whatsapp-float" id="whatsappFloat">
    <a href="https://wa.me/573123708407?text=Hola,%20me%20interesa%20conocer%20más%20sobre%20sus%20servicios%20de%20desarrollo%20web" 
       target="_blank" 
       class="whatsapp-btn" 
       title="Contactar por WhatsApp"
       aria-label="Contactar por WhatsApp">
        <i class="fab fa-whatsapp"></i>
        <span class="whatsapp-tooltip">¿Necesitas ayuda?</span>
    </a>
</div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variables
        const navbar = document.getElementById('navbar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-menu .nav-link, .mobile-contact');
        
        let isMenuOpen = false;
        let ticking = false;

        // Navbar scroll effect
        function updateNavbar() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            ticking = false;
        }

        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }

        window.addEventListener('scroll', requestTick);

        // Mobile menu toggle
        function toggleMobileMenu() {
            isMenuOpen = !isMenuOpen;
            
            if (isMenuOpen) {
                // Abrir menú
                mobileNav.classList.add('active');
                mobileNavOverlay.classList.add('active');
                mobileMenuBtn.classList.add('active');
                mobileMenuBtn.querySelector('i').classList.replace('fa-bars', 'fa-times');
                document.body.style.overflow = 'hidden';
                
                // Agregar atributos de accesibilidad
                mobileMenuBtn.setAttribute('aria-expanded', 'true');
                mobileNav.setAttribute('aria-hidden', 'false');
            } else {
                // Cerrar menú
                mobileNav.classList.remove('active');
                mobileNavOverlay.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                mobileMenuBtn.querySelector('i').classList.replace('fa-times', 'fa-bars');
                document.body.style.overflow = 'auto';
                
                // Actualizar atributos de accesibilidad
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
                mobileNav.setAttribute('aria-hidden', 'true');
            }
        }

        // Event listeners
        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        mobileNavOverlay.addEventListener('click', toggleMobileMenu);

        // Cerrar menú al hacer click en enlaces
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (isMenuOpen) {
                    toggleMobileMenu();
                }
            });
        });

        // Smooth scrolling para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                
                if (target) {
                    const navbarHeight = navbar.offsetHeight;
                    const offsetTop = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                    
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Cerrar menú con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMenuOpen) {
                toggleMobileMenu();
            }
        });

        // Cerrar menú al cambiar orientación o redimensionar
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && isMenuOpen) {
                toggleMobileMenu();
            }
        });

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar atributos de accesibilidad iniciales
            mobileMenuBtn.setAttribute('aria-expanded', 'false');
            mobileNav.setAttribute('aria-hidden', 'true');

               const whatsappFloat = document.getElementById('whatsappFloat');
    
    // Mostrar/ocultar basado en scroll (opcional)
    let lastScrollTop = 0;
    let hideTimeout;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Mostrar siempre (comentar las líneas de abajo si quieres que siempre esté visible)
        // if (scrollTop > lastScrollTop && scrollTop > 100) {
        //     // Scrolling down
        //     whatsappFloat.style.transform = 'translateY(100px)';
        // } else {
        //     // Scrolling up
        //     whatsappFloat.style.transform = 'translateY(0)';
        // }
        
        lastScrollTop = scrollTop;
        });
        // Agregar efecto de click (opcional)
    const whatsappBtn = document.querySelector('.whatsapp-btn');
    whatsappBtn.addEventListener('click', function() {
        // Puedes agregar analytics o tracking aquí
        console.log('WhatsApp button clicked');
    });
        });
    </script>

    @stack('scripts')
</body>
</html>