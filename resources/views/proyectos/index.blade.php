@extends('layouts.app')

@section('title', 'Proyectos - MY Tech Solutions')

@push('styles')
<style>
    /* Variables */
    :root {
        --gradient-primary: linear-gradient(135deg, #007bff 0%, #1a5cff 100%);
        --gradient-secondary: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
        --gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* Hero Section */
    .hero-proyectos {
        background: linear-gradient(135deg, #1a2a6c 0%, #007bff 50%, #00d4ff 100%);
        padding: 100px 0 80px;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 65vh;
        box-shadow: 0 10px 40px rgba(0, 123, 255, 0.2);
    }

    .hero-proyectos::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3) 0%, transparent 50%),
            url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="heroGrid" width="15" height="15" patternUnits="userSpaceOnUse"><path d="M 15 0 L 0 0 0 15" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23heroGrid)"/></svg>');
        animation: floating 6s ease-in-out infinite;
    }

    .hero-proyectos::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: 
            radial-gradient(circle, rgba(0, 212, 255, 0.1) 1px, transparent 1px),
            radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
        background-size: 50px 50px, 80px 80px;
        animation: drift 20s linear infinite;
        pointer-events: none;
    }

    @keyframes drift {
        0% { transform: rotate(0deg) translateX(0) translateY(0); }
        100% { transform: rotate(360deg) translateX(20px) translateY(20px); }
    }

    @keyframes floating {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-proyectos h1 {
        font-size: 4.5rem;
        font-weight: 900;
        margin-bottom: 2rem;
        background: linear-gradient(45deg, #ffffff 0%, #e3f2fd 50%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: glow 3s ease-in-out infinite alternate;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }

    @keyframes glow {
        from { filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.5)); }
        to { filter: drop-shadow(0 0 30px rgba(0, 212, 255, 0.8)); }
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 50px;
        padding: 0.8rem 1.5rem;
        margin-bottom: 2rem;
        font-weight: 600;
        color: #00d4ff;
        animation: pulse-glow 3s ease-in-out infinite;
    }

    @keyframes pulse-glow {
        0%, 100% { 
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
            transform: scale(1.02);
        }
    }

    .hero-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .feature-item {
        text-align: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 30px;
        padding: 3rem 2.5rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .feature-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .feature-item:hover::before {
        left: 100%;
    }

    .feature-item:hover {
        transform: translateY(-15px) scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 123, 255, 0.2);
        border-color: rgba(0, 212, 255, 0.5);
    }

    .feature-icon {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        display: block;
        filter: drop-shadow(0 5px 15px rgba(0, 212, 255, 0.3));
    }

    .feature-item h4 {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: white;
    }

    .feature-item p {
        opacity: 0.9;
        line-height: 1.6;
        margin: 0;
    }

    /* Filtros */
    .filters-section {
        padding: 80px 0 60px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 50%, #f0f8ff 100%);
        position: relative;
        box-shadow:
            inset 0 10px 30px rgba(0, 123, 255, 0.05),
            0 5px 20px rgba(0, 0, 0, 0.03);
    }

    .filters-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(0,123,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.5;
    }

    .filter-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        position: relative;
        z-index: 2;
    }

    .filter-btn {
        padding: 12px 24px;
        border: 2px solid var(--primary-blue);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        color: var(--primary-blue);
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
    }

    .filter-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: var(--gradient-primary);
        transition: left 0.3s ease;
        z-index: 1;
    }

    .filter-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transition: all 0.3s ease;
        transform: translate(-50%, -50%);
        z-index: 2;
    }

    .filter-btn:hover::after {
        width: 300px;
        height: 300px;
    }

    .filter-btn span {
        position: relative;
        z-index: 3;
    }

    .filter-btn:hover::before,
    .filter-btn.active::before {
        left: 0;
    }

    .filter-btn:hover span,
    .filter-btn.active span {
        color: white;
    }

    /* Proyectos Grid - Minimal & Elegant */
    .proyectos-section {
        padding: 80px 0 120px;
        background: #fafbfc;
        position: relative;
    }

    .proyectos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
        position: relative;
        z-index: 2;
    }

    /* Minimal Card Design */
    .proyecto-card {
        position: relative;
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .proyecto-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #007bff;
    }

    .proyecto-card.featured {
        border: 2px solid #007bff;
        background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
    }

    /* Destacado Tag */
    .banner-tag {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 10;
    }

    .banner-tag span {
        background: #007bff;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Header Section */
    .proyecto-header {
        padding: 2rem 1.5rem 1.5rem;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
    }

    .proyecto-logo {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        background: #f9fafb;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
    }

    .proyecto-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }

    .proyecto-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .country-flag {
        font-size: 1.1rem;
    }

    .category-badge {
        display: inline-block;
        background: #f3f4f6;
        color: #6b7280;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    /* Content Section */
    .proyecto-content {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .proyecto-description {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex-grow: 1;
    }

    /* Tech Stack - Minimal Pills */
    .tech-lists {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .tech-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        color: #374151;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tech-item svg {
        display: none;
    }

    /* Footer Section */
    .proyecto-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #f3f4f6;
        background: #fafbfc;
        margin-top: auto;
    }

    .status-container {
        margin-bottom: 1rem;
        text-align: center;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-live {
        background: #d1fae5;
        color: #065f46;
    }

    .status-development {
        background: #fef3c7;
        color: #92400e;
    }

    /* Button - Clean & Simple */
    .visit-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .visit-btn:hover {
        background: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        color: white;
    }

    .visit-btn i {
        font-size: 0.85rem;
    }


    /* Responsive */
    @media (max-width: 768px) {
        .hero-proyectos {
            padding: 60px 0 40px;
            min-height: 50vh;
        }

        .hero-proyectos h1 {
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .hero-features {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-item {
            padding: 2rem 1.5rem;
            border-radius: 20px;
        }

        .proyectos-section {
            padding: 40px 0 80px;
        }

        .proyectos-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .proyecto-card.featured {
            grid-column: span 1;
        }

        .proyecto-header {
            padding: 1.5rem 1rem 1rem;
        }

        .proyecto-logo {
            width: 70px;
            height: 70px;
        }

        .proyecto-name {
            font-size: 1.1rem;
        }

        .country-flag {
            font-size: 1rem;
        }

        .proyecto-content {
            padding: 1.25rem;
        }

        .proyecto-description {
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .tech-lists {
            gap: 0.4rem;
        }

        .tech-item {
            font-size: 0.7rem;
            padding: 3px 8px;
        }

        .proyecto-footer {
            padding: 1rem 1.25rem;
        }

        .visit-btn {
            padding: 10px;
            font-size: 0.85rem;
        }
    }

    /* Responsive para tablets */
    @media (min-width: 769px) and (max-width: 1024px) {
        .proyectos-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .proyecto-card.featured {
            grid-column: span 2;
        }
    }

    /* Responsive para pantallas muy pequeñas */
    @media (max-width: 480px) {
        .hero-proyectos h1 {
            font-size: 2rem;
        }

        .proyectos-section {
            padding: 30px 0 60px;
        }

        .proyecto-logo {
            width: 60px;
            height: 60px;
        }

        .proyecto-name {
            font-size: 1rem;
        }

        .proyecto-content {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-proyectos">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-star"></i>
                <span>{{ $data['hero_badge'] ?? 'Proyectos Destacados' }}</span>
            </div>
            <h1>{{ $data['hero_title'] ?? 'Transformando Ideas en Realidad Digital' }}</h1>
            <p class="lead" style="font-size: 1.4rem; opacity: 0.95; max-width: 800px; margin: 0 auto 3rem;">
                {!! $data['hero_description'] ?? 'Cada proyecto que desarrollo está diseñado para <strong>impulsar el crecimiento</strong> de tu negocio. Desde plataformas de viajes hasta sistemas administrativos, mis soluciones están <strong>operando exitosamente</strong> en múltiples países.' !!}
            </p>
            
            <div class="hero-features">
                <div class="feature-item">
                    <div class="feature-icon">{{ $data['hero_feature_1_icon'] ?? '🌎' }}</div>
                    <h4>{{ $data['hero_feature_1_title'] ?? 'Alcance Internacional' }}</h4>
                    <p>{{ $data['hero_feature_1_description'] ?? 'Proyectos exitosos en América y Europa' }}</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">{{ $data['hero_feature_2_icon'] ?? '🚀' }}</div>
                    <h4>{{ $data['hero_feature_2_title'] ?? 'Tecnología Avanzada' }}</h4>
                    <p>{{ $data['hero_feature_2_description'] ?? 'Laravel, Vue.js, React y las últimas tendencias' }}</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">{{ $data['hero_feature_3_icon'] ?? '💎' }}</div>
                    <h4>{{ $data['hero_feature_3_title'] ?? 'Calidad Premium' }}</h4>
                    <p>{{ $data['hero_feature_3_description'] ?? 'Cada proyecto único y a la medida' }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filtros y Showcase -->
<section class="filters-section">
    <div class="container">
        <div class="showcase-header text-center mb-5">
            <h2 style="font-size: 3rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 1rem; background: linear-gradient(135deg, #1a2a6c, #007bff, #00d4ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                {{ $data['showcase_title'] ?? 'Portfolio de Proyectos Exitosos' }}
            </h2>
            <p style="font-size: 1.2rem; color: #6c757d; max-width: 700px; margin: 0 auto 2rem;">
                {{ $data['showcase_description'] ?? 'Cada proyecto cuenta una historia de transformación digital y crecimiento empresarial' }}
            </p>
            <div class="scroll-indicator" style="cursor: pointer; display: inline-flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem; background: rgba(0, 123, 255, 0.05); border-radius: 15px; transition: all 0.3s ease;">
                <i class="fas fa-chevron-down" style="font-size: 1.5rem; color: var(--primary-blue); animation: bounce 2s infinite;"></i>
                <span style="font-size: 0.9rem; font-weight: 600; color: var(--primary-blue);">{{ $data['scroll_indicator_text'] ?? 'Descubre mis proyectos' }}</span>
            </div>
        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">
                <span>✨ Todos los Proyectos</span>
            </button>
            <button class="filter-btn" data-category="travel">
                <span>🌍 Viajes & Movilidad</span>
            </button>
            <button class="filter-btn" data-category="booking">
                <span>🏨 Reservas & Booking</span>
            </button>
            <button class="filter-btn" data-category="restaurant">
                <span>🍽️ Gastronomía</span>
            </button>
            <button class="filter-btn" data-category="admin">
                <span>⚙️ Gestión & Admin</span>
            </button>
            <button class="filter-btn" data-category="legal">
                <span>⚖️ Legal & Corporativo</span>
            </button>
            <button class="filter-btn" data-category="ecommerce">
                <span>🛒 E-commerce</span>
            </button>
        </div>
    </div>
</section>

<style>
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.scroll-indicator:hover {
    background: rgba(0, 123, 255, 0.1) !important;
    transform: translateY(5px);
}

.showcase-header {
    position: relative;
    z-index: 2;
}
</style>

<!-- Proyectos Grid -->
<section class="proyectos-section">
    <div class="container">
        <div class="proyectos-grid" id="proyectosGrid">
            @forelse($proyectos as $proyecto)
            <div class="proyecto-card {{ $proyecto->destacado ? 'featured' : '' }}" data-category="{{ $proyecto->categoria }}">
                <!-- Destacado Badge -->
                @if($proyecto->destacado)
                <div class="banner-tag">
                    <span>★ DESTACADO</span>
                </div>
                @endif

                <a href="{{ route('proyectos.show', $proyecto->slug) }}" style="text-decoration: none; color: inherit; display: contents;">
                    <!-- Header -->
                    <div class="proyecto-header">
                        <div class="proyecto-logo">
                            @if($proyecto->logo)
                                <img src="{{ asset('storage/' . $proyecto->logo) }}" alt="{{ $proyecto->nombre }}">
                            @else
                                @php
                                    $categoryIcons = [
                                        'travel' => '✈️',
                                        'booking' => '🏨',
                                        'restaurant' => '🍽️',
                                        'admin' => '⚙️',
                                        'legal' => '⚖️',
                                        'tech' => '💻',
                                        'ecommerce' => '🛒',
                                    ];
                                    $icon = $categoryIcons[$proyecto->categoria] ?? '🚀';
                                @endphp
                                <div style="font-size: 2.5rem;">{{ $icon }}</div>
                            @endif
                        </div>

                        <div class="proyecto-name">
                            {{ $proyecto->nombre }} <span class="country-flag">{{ $proyecto->bandera_emoji }}</span>
                        </div>

                        <span class="category-badge">{{ $proyecto->badge_text }}</span>
                    </div>

                    <!-- Content -->
                    <div class="proyecto-content">
                        <p class="proyecto-description">
                            {!! strip_tags($proyecto->descripcion, '<strong><em><b><i>') !!}
                        </p>

                        <!-- Tech Stack Pills -->
                        <div class="tech-lists">
                            @foreach(collect($proyecto->tecnologias)->take(4) as $tecnologia)
                            <div class="tech-item">
                                <span>{{ $tecnologia }}</span>
                            </div>
                            @endforeach
                            @if(count($proyecto->tecnologias) > 4)
                            <div class="tech-item" style="background: #e5e7eb; color: #6b7280;">
                                <span>+{{ count($proyecto->tecnologias) - 4 }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </a>

                <!-- Footer -->
                <div class="proyecto-footer">
                    <div class="status-container">
                        <span class="status-badge status-{{ $proyecto->estado == 'en_vivo' ? 'live' : 'development' }}">
                            @if($proyecto->estado == 'en_vivo')
                                ● En Vivo
                            @elseif($proyecto->estado == 'en_desarrollo')
                                ● En Desarrollo
                            @else
                                ● Pausado
                            @endif
                        </span>
                    </div>

                    @if($proyecto->url)
                        <a href="{{ $proyecto->url }}" target="_blank" class="visit-btn" onclick="event.stopPropagation();">
                            Ver Proyecto <i class="fas fa-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('proyectos.show', $proyecto->slug) }}" class="visit-btn">
                            Ver Detalles <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>

            @empty
            <div class="col-12 text-center py-5" style="grid-column: 1 / -1;">
                <i class="fas fa-folder-open fa-3x mb-3" style="color: #ccc;"></i>
                <h4>No hay proyectos disponibles</h4>
                <p style="color: #999;">Los proyectos aparecerán aquí cuando sean creados desde el panel de administración</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const proyectoCards = document.querySelectorAll('.proyecto-card');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const isMobile = window.innerWidth <= 768;

    // Funcionalidad de filtros
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Añadir clase active al botón clickeado
            this.classList.add('active');

            const category = this.getAttribute('data-category');

            proyectoCards.forEach(card => {
                if (category === 'all') {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
                    }, 50);
                } else {
                    const cardCategory = card.getAttribute('data-category') || card.classList.contains('cat-' + category);
                    if (cardCategory === category || cardCategory) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
                        }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = isMobile ? 'translateY(30px)' : 'translateY(50px) rotateX(10deg)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                }
            });
        });
    });
    
    // Smooth scroll cuando se hace click en el indicador
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function() {
            document.querySelector('.proyectos-section').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }
    
    // Animación de entrada para las tarjetas (optimizada para móvil)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const delay = isMobile ? index * 50 : index * 100; // Delay más rápido en móvil
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0) rotateX(0) rotateY(0)';
                }, delay);
            }
        });
    }, {
        threshold: isMobile ? 0.05 : 0.1, // Threshold más bajo en móvil
        rootMargin: '0px 0px -20px 0px'
    });
    
    proyectoCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = isMobile ? 'translateY(30px)' : 'translateY(50px) rotateX(10deg)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Efecto parallax sutil (solo en desktop)
    if (!isMobile) {
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3; // Reducido para mejor performance
            
            const heroSection = document.querySelector('.hero-proyectos');
            if (heroSection) {
                heroSection.style.transform = `translateY(${rate}px)`;
            }
            ticking = false;
        }
        
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        }
        
        window.addEventListener('scroll', requestTick, { passive: true });
    }
    
    // Efecto de hover mejorado (solo en desktop)
    if (!isMobile) {
        proyectoCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });
    }
    
    // Mejora la performance en móvil
    if (isMobile) {
        // Deshabilitar animaciones complejas en móvil para mejor performance
        const style = document.createElement('style');
        style.textContent = `
            @media (max-width: 768px) {
                *, *::before, *::after {
                    animation-duration: 0.5s !important;
                    animation-delay: 0s !important;
                }
                
                .proyecto-card:hover {
                    transform: translateY(-8px) !important;
                }
                
                .tech-tag:hover {
                    transform: translateY(-2px) scale(1.02) !important;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Optimización del scroll suave
    document.documentElement.style.scrollBehavior = 'smooth';
});
</script>
@endpush
@endsection