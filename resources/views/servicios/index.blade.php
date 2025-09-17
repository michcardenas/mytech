@extends('layouts.app')

@section('title', 'Servicios - MY Tech Solutions')

@push('styles')
<style>
    /* Hero Section */
    .hero-servicios {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        padding: 120px 0 80px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .hero-servicios::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-servicios h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        background: linear-gradient(45deg, #ffffff 30%, #e3f2fd 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-servicios .lead {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        opacity: 0.95;
        line-height: 1.6;
    }

    /* Servicios Grid */
    .servicios-section {
        padding: 100px 0;
        background: var(--light-gray);
    }

    .servicio-card {
        background: white;
        border-radius: 20px;
        padding: 3rem 2rem;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        height: 100%;
        border: 1px solid rgba(0, 123, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .servicio-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-blue);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .servicio-card:hover::before {
        transform: scaleX(1);
    }

    .servicio-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }

    .servicio-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-blue);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2rem;
        color: white;
        transition: var(--transition);
    }

    .servicio-card:hover .servicio-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .servicio-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--dark-text);
        text-align: center;
    }

    .servicio-card p {
        color: #6c757d;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .servicio-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .servicio-features li {
        padding: 0.5rem 0;
        color: var(--dark-text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .servicio-features li::before {
        content: '✓';
        color: var(--primary-blue);
        font-weight: bold;
        width: 20px;
        text-align: center;
    }

    /* Tecnologías Section */
    .tecnologias-section {
        padding: 100px 0;
        background: white;
    }

    .tech-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .tech-item {
        text-align: center;
        padding: 1.5rem;
        border-radius: 15px;
        background: var(--light-gray);
        transition: var(--transition);
        border: 2px solid transparent;
    }

    .tech-item:hover {
        transform: translateY(-5px);
        border-color: var(--primary-blue);
        background: white;
        box-shadow: var(--shadow-soft);
    }

    .tech-item i {
        font-size: 3rem;
        color: var(--primary-blue);
        margin-bottom: 1rem;
    }

    .tech-item h5 {
        font-weight: 600;
        color: var(--dark-text);
        margin: 0;
    }

    /* Proceso Section */
    .proceso-section {
        padding: 100px 0;
        background: var(--light-gray);
    }

    .proceso-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .proceso-step {
        text-align: center;
        position: relative;
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: var(--gradient-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        position: relative;
        z-index: 2;
    }

    .proceso-step::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-blue) 0%, transparent 100%);
        z-index: 1;
    }

    .proceso-step:last-child::before {
        display: none;
    }

    .proceso-step h4 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--dark-text);
    }

    .proceso-step p {
        color: #6c757d;
        line-height: 1.6;
    }

    /* CTA Section */
    .cta-section {
        padding: 80px 0;
        background: var(--gradient-blue);
        color: white;
        text-align: center;
    }

    .cta-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .cta-content p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }

    .btn-cta {
        background: white;
        color: var(--primary-blue);
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
    }

    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        color: var(--primary-blue);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-servicios h1 {
            font-size: 2.5rem;
        }

        .hero-servicios .lead {
            font-size: 1.1rem;
        }

        .servicio-card {
            padding: 2rem 1.5rem;
        }

        .servicios-section,
        .tecnologias-section,
        .proceso-section {
            padding: 60px 0;
        }

        .proceso-step::before {
            display: none;
        }

        .tech-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1rem;
        }

        .cta-content h2 {
            font-size: 2rem;
        }
    }
    .hero-servicios {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
    padding: 120px 0 80px;
    color: white;
    position: relative;
    overflow: hidden;
}

.hero-servicios::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.hero-content-text {
    position: relative;
    z-index: 2;
    text-align: left;
}

.hero-content-text h1 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    background: linear-gradient(45deg, #ffffff 30%, #e3f2fd 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-content-text .lead {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    line-height: 1.6;
}

.video-container {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: center;
    align-items: center;
}

.video-wrapper {
    position: relative;
    width: 100%;
    max-width: 500px;
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 
        0 25px 50px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
    transition: transform 0.3s ease;
}

.video-wrapper:hover {
    transform: perspective(1000px) rotateY(0deg) rotateX(0deg) scale(1.02);
}

.hero-video {
    width: 100%;
    height: auto;
    display: block;
    aspect-ratio: 16/9;
    object-fit: cover;
}

.video-overlay-effect {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.1) 0%,
        transparent 50%,
        rgba(0, 123, 255, 0.1) 100%
    );
    pointer-events: none;
}

.video-glow {
    position: absolute;
    top: -20px;
    left: -20px;
    right: -20px;
    bottom: -20px;
    background: linear-gradient(45deg, var(--primary-blue), #00d4ff);
    border-radius: 35px;
    opacity: 0.3;
    filter: blur(20px);
    z-index: -1;
    animation: glow-pulse 3s ease-in-out infinite alternate;
}

@keyframes glow-pulse {
    0% { opacity: 0.3; filter: blur(20px); }
    100% { opacity: 0.5; filter: blur(25px); }
}

@media (max-width: 768px) {
    .hero-content-text h1 {
        font-size: 2.5rem;
        text-align: center;
    }
    
    .hero-content-text .lead {
        font-size: 1.1rem;
        text-align: center;
    }
    
    .hero-content-text {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .video-wrapper {
        transform: none;
        max-width: 100%;
    }
    
    .video-wrapper:hover {
        transform: scale(1.02);
    }
}
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-servicios">
    <div class="container">
        <div class="row align-items-center">
            <!-- Contenido de texto -->
            <div class="col-lg-6">
                <div class="hero-content-text">
                    <h1>{{ $data['hero_title'] ?? 'Nuestros Servicios' }}</h1>
                    <p class="lead">
                        {{ $data['hero_description'] ?? 'Creamos soluciones digitales a la medida que impulsan el crecimiento de tu empresa. No usamos plantillas, cada proyecto es único y está diseñado específicamente para tus necesidades.' }}
                    </p>
                </div>
            </div>
            
            <!-- Video contenedor -->
            <div class="col-lg-6">
                <div class="video-container">
                    <div class="video-wrapper">
                        <video autoplay muted loop playsinline class="hero-video">
                            <source src="{{ asset($data['hero_video_url'] ?? 'videos/hero-video.mp4') }}" type="video/mp4">
                            Tu navegador no soporta videos HTML5.
                        </video>
                        <div class="video-overlay-effect"></div>
                    </div>
                    <div class="video-glow"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Servicios Principales -->
<section class="servicios-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--dark-text); margin-bottom: 1rem;">
                {{ $data['servicios_title'] ?? '¿Qué Desarrollamos Para Ti?' }}
            </h2>
            <p style="font-size: 1.2rem; color: #6c757d; max-width: 600px; margin: 0 auto;">
                {{ $data['servicios_description'] ?? 'Cada proyecto que creamos está diseñado para ayudar a tu empresa a vender más, organizarse mejor y ofrecer un servicio excepcional.' }}
            </p>
        </div>

        <div class="row g-4">
            <!-- Servicio 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="servicio-card">
                    <div class="servicio-icon">
                        <i class="{{ $data['servicio_1_icon'] ?? 'fas fa-store' }}"></i>
                    </div>
                    <h3>{{ $data['servicio_1_title'] ?? 'Marketplaces Personalizados' }}</h3>
                    <p>{{ $data['servicio_1_description'] ?? 'Plataformas de comercio como MercadoLibre, pero adaptadas a tu nicho específico de mercado.' }}</p>
                    <ul class="servicio-features">
                        <li>{{ $data['servicio_1_feature_1'] ?? 'Sistema de vendedores múltiples' }}</li>
                        <li>{{ $data['servicio_1_feature_2'] ?? 'Pagos integrados y seguros' }}</li>
                        <li>{{ $data['servicio_1_feature_3'] ?? 'Panel administrativo completo' }}</li>
                        <li>{{ $data['servicio_1_feature_4'] ?? 'App móvil nativa opcional' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Servicio 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="servicio-card">
                    <div class="servicio-icon">
                        <i class="{{ $data['servicio_2_icon'] ?? 'fas fa-calendar-check' }}"></i>
                    </div>
                    <h3>{{ $data['servicio_2_title'] ?? 'Apps de Reservas y Citas' }}</h3>
                    <p>{{ $data['servicio_2_description'] ?? 'Sistemas inteligentes para gestionar citas, reservas y horarios de manera automatizada.' }}</p>
                    <ul class="servicio-features">
                        <li>{{ $data['servicio_2_feature_1'] ?? 'Reservas en tiempo real' }}</li>
                        <li>{{ $data['servicio_2_feature_2'] ?? 'Recordatorios automáticos' }}</li>
                        <li>{{ $data['servicio_2_feature_3'] ?? 'Gestión de disponibilidad' }}</li>
                        <li>{{ $data['servicio_2_feature_4'] ?? 'Integración con calendarios' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Servicio 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="servicio-card">
                    <div class="servicio-icon">
                        <i class="{{ $data['servicio_3_icon'] ?? 'fas fa-utensils' }}"></i>
                    </div>
                    <h3>{{ $data['servicio_3_title'] ?? 'Plataformas de Restaurantes' }}</h3>
                    <p>{{ $data['servicio_3_description'] ?? 'Menús digitales interactivos con sistema de pedidos y gestión completa del restaurante.' }}</p>
                    <ul class="servicio-features">
                        <li>{{ $data['servicio_3_feature_1'] ?? 'Menú digital con QR' }}</li>
                        <li>{{ $data['servicio_3_feature_2'] ?? 'Pedidos online y delivery' }}</li>
                        <li>{{ $data['servicio_3_feature_3'] ?? 'Gestión de inventario' }}</li>
                        <li>{{ $data['servicio_3_feature_4'] ?? 'Reportes de ventas' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Servicio 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="servicio-card">
                    <div class="servicio-icon">
                        <i class="{{ $data['servicio_4_icon'] ?? 'fas fa-building' }}"></i>
                    </div>
                    <h3>{{ $data['servicio_4_title'] ?? 'Sistemas Administrativos' }}</h3>
                    <p>{{ $data['servicio_4_description'] ?? 'Plataformas para condominios, negocios y consultoras que automatizan procesos operativos.' }}</p>
                    <ul class="servicio-features">
                        <li>{{ $data['servicio_4_feature_1'] ?? 'Gestión de clientes/residentes' }}</li>
                        <li>{{ $data['servicio_4_feature_2'] ?? 'Control de pagos y facturación' }}</li>
                        <li>{{ $data['servicio_4_feature_3'] ?? 'Reportes automatizados' }}</li>
                        <li>{{ $data['servicio_4_feature_4'] ?? 'Comunicación interna' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Servicio 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="servicio-card">
                    <div class="servicio-icon">
                        <i class="{{ $data['servicio_5_icon'] ?? 'fas fa-globe' }}"></i>
                    </div>
                    <h3>{{ $data['servicio_5_title'] ?? 'Páginas Web Profesionales' }}</h3>
                    <p>{{ $data['servicio_5_description'] ?? 'Sitios web con panel de control y optimizados para aparecer en los primeros lugares de Google.' }}</p>
                    <ul class="servicio-features">
                        <li>{{ $data['servicio_5_feature_1'] ?? 'Diseño responsive y moderno' }}</li>
                        <li>{{ $data['servicio_5_feature_2'] ?? 'SEO optimizado para Google' }}</li>
                        <li>{{ $data['servicio_5_feature_3'] ?? 'Panel de administración' }}</li>
                        <li>{{ $data['servicio_5_feature_4'] ?? 'Velocidad de carga optimizada' }}</li>
                    </ul>
                </div>
            </div>

            <!-- Servicio 6 -->
            <div class="col-lg-4 col-md-6">
                <div class="servicio-card">
                    <div class="servicio-icon">
                        <i class="{{ $data['servicio_6_icon'] ?? 'fas fa-cogs' }}"></i>
                    </div>
                    <h3>{{ $data['servicio_6_title'] ?? 'Aplicaciones Web Personalizadas' }}</h3>
                    <p>{{ $data['servicio_6_description'] ?? 'Sistemas web complejos y especializados que automatizan procesos específicos de tu industria.' }}</p>
                    <ul class="servicio-features">
                        <li>{{ $data['servicio_6_feature_1'] ?? 'CRM y ERP personalizados' }}</li>
                        <li>{{ $data['servicio_6_feature_2'] ?? 'Plataformas de e-learning' }}</li>
                        <li>{{ $data['servicio_6_feature_3'] ?? 'Sistemas de inventario' }}</li>
                        <li>{{ $data['servicio_6_feature_4'] ?? 'Dashboards y analytics' }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tecnologías -->
<section class="tecnologias-section">
    <div class="container">
        <div class="text-center">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--dark-text); margin-bottom: 1rem;">
                {{ $data['tecnologias_title'] ?? 'Tecnologías Modernas y Seguras' }}
            </h2>
            <p style="font-size: 1.2rem; color: #6c757d; max-width: 700px; margin: 0 auto;">
                {{ $data['tecnologias_description'] ?? 'Utilizamos las herramientas más avanzadas del mercado para garantizar que tu plataforma sea rápida, confiable, escalable y esté lista para crecer.' }}
            </p>
        </div>

        <div class="tech-grid">
            <div class="tech-item">
                <i class="{{ $data['tech_1_icon'] ?? 'fab fa-laravel' }}"></i>
                <h5>{{ $data['tech_1_name'] ?? 'Laravel 12' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_2_icon'] ?? 'fab fa-js-square' }}"></i>
                <h5>{{ $data['tech_2_name'] ?? 'JavaScript' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_3_icon'] ?? 'fab fa-react' }}"></i>
                <h5>{{ $data['tech_3_name'] ?? 'React' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_4_icon'] ?? 'fab fa-bootstrap' }}"></i>
                <h5>{{ $data['tech_4_name'] ?? 'Bootstrap' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_5_icon'] ?? 'fas fa-database' }}"></i>
                <h5>{{ $data['tech_5_name'] ?? 'MySQL' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_6_icon'] ?? 'fab fa-aws' }}"></i>
                <h5>{{ $data['tech_6_name'] ?? 'AWS Cloud' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_7_icon'] ?? 'fab fa-docker' }}"></i>
                <h5>{{ $data['tech_7_name'] ?? 'Docker' }}</h5>
            </div>
            <div class="tech-item">
                <i class="{{ $data['tech_8_icon'] ?? 'fas fa-shield-alt' }}"></i>
                <h5>{{ $data['tech_8_name'] ?? 'SSL Security' }}</h5>
            </div>
        </div>
    </div>
</section>

<!-- Proceso de Trabajo -->
<section class="proceso-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: var(--dark-text); margin-bottom: 1rem;">
                {{ $data['proceso_title'] ?? 'Nuestro Proceso de Trabajo' }}
            </h2>
            <p style="font-size: 1.2rem; color: #6c757d; max-width: 600px; margin: 0 auto;">
                {{ $data['proceso_description'] ?? 'Te acompañamos desde la idea inicial hasta el lanzamiento y soporte continuo de tu plataforma.' }}
            </p>
        </div>

        <div class="proceso-steps">
            <div class="proceso-step">
                <div class="step-number">1</div>
                <h4>{{ $data['proceso_step_1_title'] ?? 'Análisis y Consultoría' }}</h4>
                <p>{{ $data['proceso_step_1_description'] ?? 'Escuchamos tu idea, analizamos tus necesidades y definimos la mejor estrategia para tu proyecto.' }}</p>
            </div>

            <div class="proceso-step">
                <div class="step-number">2</div>
                <h4>{{ $data['proceso_step_2_title'] ?? 'Diseño y Planificación' }}</h4>
                <p>{{ $data['proceso_step_2_description'] ?? 'Creamos prototipos, wireframes y definimos la arquitectura técnica de tu plataforma.' }}</p>
            </div>

            <div class="proceso-step">
                <div class="step-number">3</div>
                <h4>{{ $data['proceso_step_3_title'] ?? 'Desarrollo a Medida' }}</h4>
                <p>{{ $data['proceso_step_3_description'] ?? 'Programamos tu solución utilizando las mejores prácticas y tecnologías más avanzadas.' }}</p>
            </div>

            <div class="proceso-step">
                <div class="step-number">4</div>
                <h4>{{ $data['proceso_step_4_title'] ?? 'Pruebas y Lanzamiento' }}</h4>
                <p>{{ $data['proceso_step_4_description'] ?? 'Realizamos pruebas exhaustivas y te acompañamos en el lanzamiento de tu plataforma.' }}</p>
            </div>

            <div class="proceso-step">
                <div class="step-number">5</div>
                <h4>{{ $data['proceso_step_5_title'] ?? 'Soporte Continuo' }}</h4>
                <p>{{ $data['proceso_step_5_description'] ?? 'Brindamos mantenimiento, actualizaciones y soporte técnico para el crecimiento de tu negocio.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>{{ $data['cta_title'] ?? '¿Listo para Digitalizar tu Idea?' }}</h2>
            <p>
                {{ $data['cta_description'] ?? 'Conversemos sobre tu proyecto y descubre cómo podemos ayudarte a crear la solución perfecta para tu negocio.' }}
            </p>
            <a href="https://wa.me/{{ $data['whatsapp_number'] ?? '573123708407' }}?text={{ urlencode($data['whatsapp_message'] ?? 'Hola, me interesa conocer más sobre sus servicios de desarrollo web') }}" 
               target="_blank" 
               class="btn-cta">
                <i class="fab fa-whatsapp"></i>
                {{ $data['cta_button_text'] ?? 'Hablemos por WhatsApp' }}
            </a>
        </div>
    </div>
</section>
@endsection
