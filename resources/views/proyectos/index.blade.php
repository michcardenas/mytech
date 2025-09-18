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
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        padding: 80px 0 60px;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 60vh;
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
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        background: linear-gradient(45deg, #ffffff 30%, #e3f2fd 70%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: glow 3s ease-in-out infinite alternate;
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
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 25px;
        padding: 2.5rem 2rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
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
        padding: 60px 0 40px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
        position: relative;
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

    /* Proyectos Grid - Masonry Style */
    .proyectos-section {
        padding: 60px 0 120px;
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 50%, #f0f8ff 100%);
        position: relative;
    }

    .proyectos-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 15% 25%, rgba(0, 123, 255, 0.03) 0%, transparent 50%),
            radial-gradient(circle at 85% 75%, rgba(0, 212, 255, 0.03) 0%, transparent 50%);
        pointer-events: none;
    }

    .proyectos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 2.5rem;
        margin-top: 2rem;
        position: relative;
        z-index: 2;
    }

    .proyecto-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 
            0 20px 60px rgba(0, 123, 255, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.5);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        border: 1px solid rgba(0, 123, 255, 0.1);
        transform-style: preserve-3d;
    }

    .proyecto-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(0, 123, 255, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .proyecto-card:hover::before {
        opacity: 1;
    }

    .proyecto-card:hover {
        transform: translateY(-20px) rotateX(5deg) rotateY(-2deg);
        box-shadow: 
            0 40px 80px rgba(0, 123, 255, 0.2),
            0 0 0 1px rgba(0, 212, 255, 0.3);
    }

    .proyecto-card.featured {
        grid-column: span 2;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.95) 0%, rgba(25, 35, 85, 0.95) 100%);
        backdrop-filter: blur(20px);
        color: white;
        transform: scale(1.02);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .proyecto-card.featured .proyecto-title {
        color: white;
    }

    .proyecto-card.featured .proyecto-description {
        color: rgba(255, 255, 255, 0.9);
    }

    .proyecto-card.featured .proyecto-badge {
        background: rgba(255, 255, 255, 0.9);
        color: var(--primary-blue);
    }

    .proyecto-card.featured:hover .proyecto-badge {
        background: white;
        color: var(--primary-blue);
    }

    .proyecto-card.featured:hover {
        transform: translateY(-25px) rotateX(3deg) rotateY(-1deg) scale(1.02);
        box-shadow: 
            0 40px 80px rgba(0, 123, 255, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.4);
    }

    .proyecto-header {
        position: relative;
        height: 220px;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .proyecto-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 70%),
            radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 70%),
            url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="15" height="15" patternUnits="userSpaceOnUse"><path d="M 15 0 L 0 0 0 15" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        animation: bg-shift 10s ease-in-out infinite alternate;
    }

    @keyframes bg-shift {
        0% { transform: scale(1) rotate(0deg); }
        100% { transform: scale(1.05) rotate(2deg); }
    }

    .proyecto-logo {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        box-shadow: 
            0 20px 60px rgba(0, 0, 0, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 3;
    }

    .proyecto-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 15px;
        filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
    }

    .proyecto-card:hover .proyecto-logo {
        transform: translate(-50%, -50%) scale(1.1) rotate(-3deg);
        box-shadow: 
            0 30px 80px rgba(0, 0, 0, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.4);
    }

    .proyecto-badge {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary-blue);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        z-index: 3;
    }

    .proyecto-card:hover .proyecto-badge {
        transform: translateY(-5px);
        background: var(--primary-blue);
        color: white;
    }

    .proyecto-content {
        padding: 2.5rem;
        position: relative;
    }

    .proyecto-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1.2rem;
        color: var(--dark-text);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        line-height: 1.3;
    }

    .country-flag {
        font-size: 1.3rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .proyecto-description {
        color: #6c757d;
        line-height: 1.7;
        margin-bottom: 2rem;
        font-size: 1rem;
        opacity: 0.9;
    }

    .proyecto-tech {
        display: flex;
        flex-wrap: wrap;
        gap: 0.7rem;
        margin-bottom: 2rem;
    }

    .tech-tag {
        background: linear-gradient(135deg, var(--primary-blue), #00d4ff);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
        position: relative;
        overflow: hidden;
    }

    .tech-tag::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .tech-tag:hover::before {
        left: 100%;
    }

    .tech-tag:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    }

    .proyecto-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .proyecto-status {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-live {
        background: var(--gradient-success);
        color: white;
    }

    .status-development {
        background: linear-gradient(135deg, #ffc107, #ff8c00);
        color: white;
    }

    .visit-btn {
        background: var(--gradient-primary);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    .visit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 123, 255, 0.4);
        color: white;
    }

    /* Categorías especiales */
    .cat-travel .proyecto-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .cat-booking .proyecto-header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .cat-admin .proyecto-header { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .cat-restaurant .proyecto-header { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    .cat-legal .proyecto-header { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .cat-tech .proyecto-header { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

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
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .feature-item h4 {
            font-size: 1.1rem;
        }
        
        .showcase-intro {
            padding: 40px 0 30px;
            margin-top: -15px;
        }
        
        .showcase-header h2 { 
            font-size: 2rem; 
            line-height: 1.3;
        }
        
        .showcase-header p { 
            font-size: 1rem; 
            margin-bottom: 2rem;
        }
        
        .scroll-indicator {
            margin-top: 1rem;
        }
        
        .scroll-indicator i {
            font-size: 1.5rem;
        }
        
        .scroll-indicator span {
            font-size: 0.8rem;
        }
        
        .proyectos-section {
            padding: 40px 0 80px;
        }
        
        .proyectos-grid { 
            grid-template-columns: 1fr; 
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .proyecto-card.featured { 
            grid-column: span 1; 
        }
        
        .proyecto-content { 
            padding: 1.5rem;
        }
        
        .proyecto-header {
            height: 160px;
        }
        
        .proyecto-logo {
            width: 80px;
            height: 80px;
            padding: 10px;
            border-radius: 18px;
        }
        
        .proyecto-logo img {
            border-radius: 12px;
        }
        
        .proyecto-badge {
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 12px;
            bottom: 15px;
            left: 15px;
        }
        
        .proyecto-title {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        
        .country-flag {
            font-size: 1rem;
        }
        
        .proyecto-description {
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        
        .proyecto-tech {
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .tech-tag {
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
            border-radius: 20px;
        }
        
        .proyecto-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .proyecto-status {
            text-align: center;
            padding: 0.5rem 1rem;
        }
        
        .visit-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 20px;
            justify-content: center;
            font-size: 0.9rem;
        }
    }

    /* Responsive para tablets */
    @media (min-width: 769px) and (max-width: 1024px) {
        .hero-proyectos {
            padding: 80px 0 60px;
        }
        
        .proyectos-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }
        
        .proyecto-card.featured {
            grid-column: span 2;
        }
        
        .proyecto-logo {
            width: 100px;
            height: 100px;
            padding: 12px;
        }
    }

    /* Responsive para pantallas muy pequeñas */
    @media (max-width: 480px) {
        .hero-proyectos {
            padding: 40px 0 30px;
        }
        
        .hero-proyectos h1 {
            font-size: 2rem;
        }
        
        .hero-features {
            margin-top: 1.5rem;
        }
        
        .feature-item {
            padding: 1.5rem 1rem;
        }
        
        .showcase-intro {
            padding: 30px 0 20px;
        }
        
        .showcase-header h2 {
            font-size: 1.8rem;
        }
        
        .proyectos-section {
            padding: 30px 0 60px;
        }
        
        .proyecto-content {
            padding: 1.2rem;
        }
        
        .proyecto-header {
            height: 140px;
        }
        
        .proyecto-logo {
            width: 70px;
            height: 70px;
            padding: 8px;
        }
    }
</style>
@endpush

@section('content')
@extends('layouts.app')

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

<!-- Filtros -->
<section class="showcase-intro">
    <div class="container">
        <div class="showcase-header">
            <h2>{{ $data['showcase_title'] ?? 'Portfolio de Proyectos Exitosos' }}</h2>
            <p>{{ $data['showcase_description'] ?? 'Cada proyecto cuenta una historia de transformación digital y crecimiento empresarial' }}</p>
            <div class="scroll-indicator">
                <i class="fas fa-mouse"></i>
                <span>{{ $data['scroll_indicator_text'] ?? 'Descubre mis proyectos' }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Proyectos Grid -->
<section class="proyectos-section">
    <div class="container">
        <div class="proyectos-grid" id="proyectosGrid">
            <!-- VoyConVos - Featured -->
            <div class="proyecto-card featured cat-travel">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/voyconvos.png') }}" alt="VoyConVos Logo">
                    </div>
                    <div class="proyecto-badge">Viajes & Movilidad</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        VoyConVos <span class="country-flag">🇦🇷</span>
                    </h3>
                    <p class="proyecto-description">
                        Plataforma de viajes compartidos con reservas, geolocalización, chat entre pasajeros y conductores. 
                        Integración completa con Google Maps, Search Console y SEO técnico avanzado.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Vue.js</span>
                        <span class="tech-tag">Google Maps API</span>
                        <span class="tech-tag">WebSockets</span>
                        <span class="tech-tag">SEO</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://voyconvos.com/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hostella -->
            <div class="proyecto-card cat-booking">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/hostella.png') }}" alt="Hostella Logo">
                    </div>
                    <div class="proyecto-badge">Reservas & Booking</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        Hostella <span class="country-flag">🇨🇴</span>
                    </h3>
                    <p class="proyecto-description">
                        App tipo Booking para hostales con panel de administración completo, 
                        integración con GESTY para gestión hotelera y pagos seguros vía Stripe.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">React</span>
                        <span class="tech-tag">Stripe</span>
                        <span class="tech-tag">GESTY API</span>
                        <span class="tech-tag">MySQL</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://hostella.co/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- FlexFood -->
            <div class="proyecto-card cat-restaurant">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/flexfood.png') }}" alt="FlexFood Logo">
                    </div>
                    <div class="proyecto-badge">Restaurantes & QR</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        FlexFood <span class="country-flag">🇪🇸</span>
                    </h3>
                    <p class="proyecto-description">
                        Sistema para restaurantes completo y dinámico. QR por zona (bar, terraza, salón), 
                        menús en tiempo real, comandas inteligentes y gestión de roles ultra rápida.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">JavaScript</span>
                        <span class="tech-tag">QR Generator</span>
                        <span class="tech-tag">Real-time</span>
                        <span class="tech-tag">PWA</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://flexfood.es/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- TuMesa -->
            <div class="proyecto-card cat-restaurant">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/tumesa.png') }}" alt="TuMesa Logo">
                    </div>
                    <div class="proyecto-badge">Gastronomía & Chefs</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        TuMesa <span class="country-flag">🇦🇷</span>
                    </h3>
                    <p class="proyecto-description">
                        Plataforma para conectar comensales con chefs artesanales. Reservas, 
                        pagos vía Mercado Pago, autenticación Google y Maps integrado.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Vue.js</span>
                        <span class="tech-tag">Mercado Pago</span>
                        <span class="tech-tag">Google Auth</span>
                        <span class="tech-tag">Maps API</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://tumesa.ar/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- IPvestment -->
            <div class="proyecto-card cat-admin">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/ipvestment.png') }}" alt="IPvestment Logo">
                    </div>
                    <div class="proyecto-badge">Gestión Inmobiliaria</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        IPvestment <span class="country-flag">🇩🇴</span>
                    </h3>
                    <p class="proyecto-description">
                        Plataforma para gestión de condominios y apartamentos en República Dominicana. 
                        Control de residentes, gastos comunes y comunicación interna.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Bootstrap</span>
                        <span class="tech-tag">MySQL</span>
                        <span class="tech-tag">PDF Reports</span>
                        <span class="tech-tag">Notifications</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://ipinvestmentsrd.com/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jufman Kitchen -->
            <div class="proyecto-card cat-booking">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/jufman.png') }}" alt="Jufman Kitchen Logo">
                    </div>
                    <div class="proyecto-badge">Diseño & Hogar</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        Jufman Kitchen <span class="country-flag">🇺🇸</span>
                    </h3>
                    <p class="proyecto-description">
                        Página para diseño y mantenimiento de cocinas en Minnesota, USA. 
                        Asesorías personalizadas y sistema de agendamiento de mantenimientos.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">JavaScript</span>
                        <span class="tech-tag">Calendar</span>
                        <span class="tech-tag">Contact Forms</span>
                        <span class="tech-tag">SEO</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://jufmankitchendesigns.com/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Calendarix -->
            <div class="proyecto-card cat-booking">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/calendarix.png') }}" alt="Calendarix Logo">
                    </div>
                    <div class="proyecto-badge">SaaS & Productividad</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        Calendarix <span class="country-flag">🇺🇾</span>
                    </h3>
                    <p class="proyecto-description">
                        Plataforma por planes para emprendedores. Agenda citas, gestiona colaboradores, 
                        servicios, productos, stock y métricas desde un panel moderno.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Vue.js</span>
                        <span class="tech-tag">Calendar</span>
                        <span class="tech-tag">Analytics</span>
                        <span class="tech-tag">SaaS</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://calendarix.uy/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Montano&Co -->
            <div class="proyecto-card cat-legal">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <img src="{{ asset('images/logos/montano.png') }}" alt="Montano&Co Logo">
                    </div>
                    <div class="proyecto-badge">Legal & Corporativo</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        Montano&Co <span class="country-flag">🇨🇴</span>
                    </h3>
                    <p class="proyecto-description">
                        Página institucional para consultora legal. Panel Laravel con edición total, 
                        SEO editable, roles y estética 100% administrable por el cliente.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Bootstrap</span>
                        <span class="tech-tag">CMS</span>
                        <span class="tech-tag">SEO</span>
                        <span class="tech-tag">Admin Panel</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://montanoandco.net/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Electralhome -->
            <div class="proyecto-card cat-legal">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <div style="background: linear-gradient(135deg, #007bff, #00d4ff); border-radius: 15px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.8rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">EH</div>
                    </div>
                    <div class="proyecto-badge">Servicios Técnicos</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        Electralhome <span class="country-flag">🇨🇴</span>
                    </h3>
                    <p class="proyecto-description">
                        Landing institucional de servicio técnico en Laravel. 
                        Optimizada para posicionamiento local, editable y ligera.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Bootstrap</span>
                        <span class="tech-tag">SEO Local</span>
                        <span class="tech-tag">Contact Forms</span>
                        <span class="tech-tag">MySQL</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://serviciotecnicoelectralhome.com/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- Offiesco LATAM -->
            <div class="proyecto-card cat-admin">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 15px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.8rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">OL</div>
                    </div>
                    <div class="proyecto-badge">E-commerce & Gestión</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        Offiesco LATAM <span class="country-flag">🇨🇴</span>
                    </h3>
                    <p class="proyecto-description">
                        Vitrina digital para la empresa T&T, con gestión de clientes, vendedores, productos, 
                        ingresos y egresos. Exportación de informes en Excel/PDF.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Vue.js</span>
                        <span class="tech-tag">Excel Export</span>
                        <span class="tech-tag">PDF Reports</span>
                        <span class="tech-tag">Analytics</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://offiescolatam.com/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <!-- OnlyEscorts -->
            <div class="proyecto-card cat-admin">
                <div class="proyecto-header">
                    <div class="proyecto-logo">
                        <div style="background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 15px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 1.8rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">OE</div>
                    </div>
                    <div class="proyecto-badge">Plataforma Especializada</div>
                </div>
                <div class="proyecto-content">
                    <h3 class="proyecto-title">
                        OnlyEscorts <span class="country-flag">🇨🇱</span>
                    </h3>
                    <p class="proyecto-description">
                        Plataforma para agencias de escorts en Chile, con gestión avanzada de perfiles, 
                        disponibilidad, fotos y visibilidad segmentada.
                    </p>
                    <div class="proyecto-tech">
                        <span class="tech-tag">Laravel</span>
                        <span class="tech-tag">Vue.js</span>
                        <span class="tech-tag">Image Management</span>
                        <span class="tech-tag">Authentication</span>
                        <span class="tech-tag">MySQL</span>
                    </div>
                    <div class="proyecto-footer">
                        <span class="proyecto-status status-live">🟢 En Vivo</span>
                        <a href="https://onlyescorts.cl/" target="_blank" class="visit-btn">
                            <i class="fas fa-external-link-alt"></i>
                            Visitar Proyecto
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const proyectoCards = document.querySelectorAll('.proyecto-card');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    const isMobile = window.innerWidth <= 768;
    
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