@extends('layouts.app')

@section('title', 'Sobre Nosotros - MY Tech Solutions')

@push('styles')
<style>
    /* Variables */
    :root {
        --gradient-primary: linear-gradient(135deg, #007bff 0%, #1a5cff 100%);
        --gradient-secondary: linear-gradient(135deg, #00d4ff 0%, #007bff 100%);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* Hero Section - Efectos de Circuitos */
    .hero-about {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
        padding: 80px 0 60px;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 80vh;
        display: flex;
        align-items: center;
    }

    .hero-about::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            linear-gradient(135deg, rgba(0, 123, 255, 0.9) 0%, rgba(25, 35, 85, 0.95) 100%);
        z-index: 1;
    }

    .circuit-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
        opacity: 0.3;
        background-image: 
            /* Líneas horizontales */
            linear-gradient(90deg, transparent 0%, transparent 48%, rgba(0, 212, 255, 0.8) 49%, rgba(0, 212, 255, 0.8) 51%, transparent 52%, transparent 100%),
            /* Líneas verticales */
            linear-gradient(0deg, transparent 0%, transparent 48%, rgba(0, 212, 255, 0.6) 49%, rgba(0, 212, 255, 0.6) 51%, transparent 52%, transparent 100%),
            /* Puntos de conexión */
            radial-gradient(circle at 25% 25%, rgba(0, 212, 255, 0.8) 2px, transparent 3px),
            radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.6) 1px, transparent 2px),
            radial-gradient(circle at 50% 50%, rgba(0, 212, 255, 0.7) 1.5px, transparent 2.5px),
            radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.5) 1px, transparent 2px),
            radial-gradient(circle at 80% 20%, rgba(0, 212, 255, 0.6) 1px, transparent 2px);
        background-size: 
            150px 150px,
            150px 150px,
            150px 150px,
            200px 200px,
            180px 180px,
            160px 160px,
            140px 140px;
        animation: circuit-flow 20s linear infinite;
    }

    @keyframes circuit-flow {
        0% { 
            transform: translateX(0) translateY(0);
            opacity: 0.3;
        }
        50% { 
            transform: translateX(-20px) translateY(-10px);
            opacity: 0.5;
        }
        100% { 
            transform: translateX(-40px) translateY(-20px);
            opacity: 0.3;
        }
    }

    .floating-nodes {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 3;
        pointer-events: none;
    }

    .circuit-node {
        position: absolute;
        width: 8px;
        height: 8px;
        background: #00d4ff;
        border: 2px solid rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        box-shadow: 
            0 0 10px #00d4ff,
            0 0 20px rgba(0, 212, 255, 0.5);
        animation: node-pulse 3s ease-in-out infinite;
    }

    .circuit-node::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 50%;
        animation: ripple 2s linear infinite;
    }

    @keyframes node-pulse {
        0%, 100% { 
            transform: scale(1);
            box-shadow: 
                0 0 10px #00d4ff,
                0 0 20px rgba(0, 212, 255, 0.5);
        }
        50% { 
            transform: scale(1.2);
            box-shadow: 
                0 0 15px #00d4ff,
                0 0 30px rgba(0, 212, 255, 0.8);
        }
    }

    @keyframes ripple {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(3);
            opacity: 0;
        }
    }

    .circuit-node:nth-child(1) {
        top: 20%;
        left: 15%;
        animation-delay: 0s;
    }

    .circuit-node:nth-child(2) {
        top: 60%;
        right: 20%;
        animation-delay: 1s;
    }

    .circuit-node:nth-child(3) {
        top: 35%;
        right: 10%;
        animation-delay: 2s;
    }

    .circuit-node:nth-child(4) {
        top: 75%;
        left: 25%;
        animation-delay: 0.5s;
    }

    .circuit-node:nth-child(5) {
        top: 45%;
        left: 45%;
        animation-delay: 1.5s;
    }

    .data-stream {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 3;
        pointer-events: none;
    }

    .data-particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(0, 212, 255, 0.8);
        border-radius: 50%;
        box-shadow: 0 0 6px rgba(0, 212, 255, 0.6);
        animation: data-flow 8s linear infinite;
    }

    @keyframes data-flow {
        0% {
            transform: translateX(0) translateY(0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateX(100vw) translateY(20px);
            opacity: 0;
        }
    }

    .data-particle:nth-child(1) {
        top: 25%;
        animation-delay: 0s;
    }

    .data-particle:nth-child(2) {
        top: 45%;
        animation-delay: 2s;
    }

    .data-particle:nth-child(3) {
        top: 65%;
        animation-delay: 4s;
    }

    .data-particle:nth-child(4) {
        top: 35%;
        animation-delay: 6s;
    }

    .hero-content {
        position: relative;
        z-index: 4;
        max-width: 900px;
        margin: 0 auto;
        text-align: center;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        padding: 1rem 2rem;
        margin-bottom: 2rem;
        font-weight: 600;
        color: #00d4ff;
        animation: badge-glow 4s ease-in-out infinite;
        box-shadow: 
            0 10px 30px rgba(0, 212, 255, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    @keyframes badge-glow {
        0%, 100% { 
            box-shadow: 
                0 10px 30px rgba(0, 212, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        50% { 
            box-shadow: 
                0 15px 40px rgba(0, 212, 255, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
    }

    .hero-about h1 {
        font-size: 4.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        background: linear-gradient(45deg, #ffffff 20%, #e3f2fd 50%, #00d4ff 80%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1.1;
        text-shadow: 0 0 40px rgba(255, 255, 255, 0.1);
    }

    .hero-about .lead {
        font-size: 1.4rem;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        line-height: 1.6;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(20px);
        color: white;
        padding: 1.2rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 
            0 15px 35px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }

    .hero-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .hero-cta:hover::before {
        left: 100%;
    }

    .hero-cta:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-5px) scale(1.05);
        box-shadow: 
            0 25px 50px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        color: white;
    }

    /* Story Section */
    .story-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e3f2fd 100%);
        position: relative;
    }

    .story-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 10% 20%, rgba(0, 123, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 90% 80%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .story-content {
        position: relative;
        z-index: 2;
    }

    .section-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-header h2 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary-blue) 0%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: 2px;
    }

    .section-header p {
        font-size: 1.3rem;
        color: #6c757d;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.7;
    }

    .story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-top: 2rem;
    }

    .story-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #495057;
    }

    .story-text p {
        margin-bottom: 1.5rem;
    }

    .story-visual {
        position: relative;
        text-align: center;
    }

    .visual-element {
        width: 300px;
        height: 300px;
        background: var(--gradient-primary);
        border-radius: 30px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 30px 60px rgba(0, 123, 255, 0.2);
        animation: gentle-float 6s ease-in-out infinite;
    }

    @keyframes gentle-float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(2deg); }
    }

    .visual-element::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="visualGrid" width="15" height="15" patternUnits="userSpaceOnUse"><circle cx="7.5" cy="7.5" r="1" fill="rgba(255,255,255,0.3)"/></pattern></defs><rect width="100" height="100" fill="url(%23visualGrid)"/></svg>');
    }

    .visual-icon {
        font-size: 6rem;
        color: white;
        z-index: 2;
        position: relative;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
    }

    /* Values Section */
    .values-section {
        padding: 100px 0;
        background: white;
        position: relative;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .value-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 20px 60px rgba(0, 123, 255, 0.1);
        border: 1px solid rgba(0, 123, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .value-card:hover::before {
        transform: scaleX(1);
    }

    .value-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 30px 80px rgba(0, 123, 255, 0.2);
    }

    .value-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2rem;
        color: white;
        box-shadow: 0 15px 30px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
    }

    .value-card:hover .value-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .value-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--dark-text);
    }

    .value-card p {
        color: #6c757d;
        line-height: 1.7;
    }

    /* Location Section */
    .location-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #e3f2fd 100%);
        position: relative;
    }

    .location-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 15% 25%, rgba(0, 123, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 85% 75%, rgba(0, 212, 255, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .location-content {
        position: relative;
        z-index: 2;
    }

    .location-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-top: 3rem;
    }

    .location-info {
        padding: 2rem;
    }

    .location-info h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--dark-text);
    }

    .location-info p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #495057;
        margin-bottom: 2rem;
    }

    .contact-info {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.1);
        border: 1px solid rgba(0, 123, 255, 0.1);
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        background: rgba(0, 123, 255, 0.05);
        transform: translateX(10px);
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        background: var(--gradient-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
    }

    .contact-text {
        flex: 1;
    }

    .contact-text h4 {
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: var(--dark-text);
    }

    .contact-text p {
        color: #6c757d;
        margin: 0;
        font-size: 0.95rem;
    }

    .map-container {
        position: relative;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 123, 255, 0.15);
        background: white;
        padding: 15px;
    }

    .map-container iframe {
        width: 100%;
        height: 400px;
        border-radius: 15px;
        border: none;
        display: block;
    }

    .map-overlay {
        position: absolute;
        top: 25px;
        left: 25px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        z-index: 10;
    }

    .map-overlay h5 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--primary-blue);
        font-size: 1rem;
    }

    .map-overlay p {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }

    /* Contact CTA Section */
    .contact-cta {
        padding: 100px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        text-align: center;
        position: relative;
    }

    .cta-content {
        max-width: 700px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .cta-content h2 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--primary-blue) 0%, #00d4ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .cta-content p {
        font-size: 1.3rem;
        color: #6c757d;
        margin-bottom: 3rem;
        line-height: 1.6;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-cta {
        background: var(--gradient-primary);
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 15px 30px rgba(0, 123, 255, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
        border: none;
        cursor: pointer;
    }

    .btn-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .btn-secondary {
        background: transparent;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
    }

    .btn-secondary:hover {
        background: var(--primary-blue);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-about {
            padding: 60px 0 40px;
            min-height: 50vh;
        }
        
        .hero-about h1 {
            font-size: 2.5rem;
        }
        
        .section-header h2 {
            font-size: 2.2rem;
        }
        
        .story-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .story-visual {
            order: -1;
        }
        
        .visual-element {
            width: 250px;
            height: 250px;
        }
        
        .visual-icon {
            font-size: 4rem;
        }
        
        .values-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .value-card {
            padding: 2rem 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .stat-item {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
        }
        
        .location-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .location-info {
            order: 1;
            padding: 1rem;
        }
        
        .map-container {
            order: -1;
        }
        
        .map-container iframe {
            height: 300px;
        }
        
        .contact-info {
            padding: 1.5rem;
        }
        
        .contact-item {
            padding: 0.8rem;
            margin-bottom: 1rem;
        }
        
        .contact-icon {
            width: 45px;
            height: 45px;
            font-size: 1rem;
        }
        
        .cta-content h2 {
            font-size: 2.2rem;
        }
        
        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-cta {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .hero-about h1 {
            font-size: 2rem;
        }
        
        .hero-badge {
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .location-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .map-container iframe {
            height: 250px;
        }
        
        .map-overlay {
            top: 15px;
            left: 15px;
            padding: 0.8rem 1rem;
        }
        
        .contact-info {
            padding: 1rem;
        }
        
        .contact-item {
            padding: 0.6rem;
        }
        
        .visual-element {
            width: 200px;
            height: 200px;
        }
        
        .visual-icon {
            font-size: 3rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-about">
    <!-- Fondo de circuitos animado -->
    <div class="circuit-bg"></div>
    
    <!-- Nodos de circuito que pulsan -->
    <div class="floating-nodes">
        <div class="circuit-node"></div>
        <div class="circuit-node"></div>
        <div class="circuit-node"></div>
        <div class="circuit-node"></div>
        <div class="circuit-node"></div>
    </div>
    
    <!-- Partículas de datos que fluyen -->
    <div class="data-stream">
        <div class="data-particle"></div>
        <div class="data-particle"></div>
        <div class="data-particle"></div>
        <div class="data-particle"></div>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-code"></i>
                <span>Expertos en Desarrollo Web</span>
            </div>
            <h1>Construimos el Futuro Digital de tu Empresa</h1>
            <p class="lead">
                Somos MY Tech Solutions, especialistas en crear <strong>soluciones de software a medida</strong> 
                que transforman ideas en plataformas exitosas. Cada línea de código está diseñada 
                para impulsar el crecimiento de tu negocio.
            </p>
            <a href="#contact" class="hero-cta">
                <i class="fas fa-rocket"></i>
                Comenzar mi Proyecto
            </a>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="story-section">
    <div class="container">
        <div class="story-content">
            <div class="section-header">
                <h2>Nuestra Historia</h2>
                <p>La pasión por la tecnología nos impulsa a crear soluciones excepcionales</p>
            </div>
            
            <div class="story-grid">
                <div class="story-text">
                    <p>
                        <strong>MY Tech Solutions nació de una visión clara:</strong> democratizar el acceso a tecnología 
                        de primer nivel para empresas de todos los tamaños. Comenzamos como desarrolladores 
                        independientes y evolucionamos hacia una consultora especializada en desarrollo web.
                    </p>
                    <p>
                        Hoy, nuestras aplicaciones operan exitosamente en <strong>7 países</strong>, desde plataformas 
                        de viajes compartidos en Argentina hasta sistemas de gestión hotelera en Colombia. 
                        No usamos plantillas: cada proyecto es una obra única, programada desde cero.
                    </p>
                    <p>
                        Nuestra metodología combina las últimas tecnologías (Laravel, Vue.js, React) con 
                        un enfoque centrado en resultados. No solo programamos, <strong>construimos herramientas 
                        que generan ingresos</strong> para nuestros clientes.
                    </p>
                </div>
                
                <div class="story-visual">
                    <div class="visual-element">
                        <div class="visual-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="values-section">
    <div class="container">
        <div class="section-header">
            <h2>Nuestros Valores</h2>
            <p>Los principios que guían cada proyecto que desarrollamos</p>
        </div>
        
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <h3>Innovación Constante</h3>
                <p>
                    Utilizamos las tecnologías más avanzadas del mercado. Cada proyecto incorpora 
                    las últimas tendencias en desarrollo web para garantizar soluciones futuro-proof.
                </p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Enfoque en Resultados</h3>
                <p>
                    No solo programamos, creamos herramientas que generan ROI. Cada funcionalidad 
                    está diseñada para impulsar el crecimiento y la eficiencia de tu negocio.
                </p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Colaboración Transparente</h3>
                <p>
                    Trabajamos como una extensión de tu equipo. Comunicación constante, entregas 
                    regulares y total transparencia en cada etapa del desarrollo.
                </p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Calidad Garantizada</h3>
                <p>
                    Código limpio, arquitectura escalable y pruebas exhaustivas. Nuestras aplicaciones 
                    están diseñadas para crecer contigo durante años.
                </p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>Alcance Global</h3>
                <p>
                    Experiencia comprobada desarrollando para múltiples países y culturas. 
                    Entendemos las necesidades locales con estándares internacionales.
                </p>
            </div>
            
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <h3>Soporte Continuo</h3>
                <p>
                    Tu éxito es nuestro éxito. Ofrecemos mantenimiento, actualizaciones y soporte 
                    técnico para asegurar el óptimo funcionamiento de tu plataforma.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="location-section">
    <div class="container">
        <div class="location-content">
            <div class="section-header">
                <h2>Nuestra Ubicación</h2>
                <p>Nos encontramos en Bogotá, Colombia, listos para impulsar tu proyecto digital</p>
            </div>
            
            <div class="location-grid">
                <div class="location-info">
                    <h3>Trabajamos Desde el Corazón de Bogotá</h3>
                    <p>
                        Nuestro equipo opera desde Bogotá, Colombia, pero nuestro alcance es global. 
                        Desarrollamos proyectos para clientes en múltiples países, combinando la 
                        calidez del servicio colombiano con estándares internacionales de calidad.
                    </p>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Ubicación</h4>
                                <p>Bogotá, Colombia</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="contact-text">
                                <h4>WhatsApp</h4>
                                <p>+57 312 370 8407</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <p>contacto@mytechsolutions.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Horario de Atención</h4>
                                <p>Lunes a Viernes, 8:00 AM - 6:00 PM (COT)</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="map-container">
                    <div class="map-overlay">
                        <h5>MY Tech Solutions</h5>
                        <p>Bogotá, Colombia</p>
                    </div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3249.012195470509!2d-74.13449935362908!3d4.600360674860746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1757988380012!5m2!1ses-419!2sco" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="contact-cta" id="contact">
    <div class="container">
        <div class="cta-content">
            <h2>¿Listo para Digitalizar tu Idea?</h2>
            <p>
                Conversemos sobre tu proyecto y descubre cómo podemos transformar tu visión 
                en una solución digital exitosa que impulse el crecimiento de tu negocio.
            </p>
            <div class="cta-buttons">
                <a href="https://wa.me/573123708407?text=Hola,%20me%20interesa%20conocer%20más%20sobre%20sus%20servicios%20de%20desarrollo%20web" 
                   target="_blank" 
                   class="btn-cta">
                    <i class="fab fa-whatsapp"></i>
                    Contactar por WhatsApp
                </a>
                <a href="mailto:info@mytechsolutions.com" class="btn-cta btn-secondary">
                    <i class="fas fa-envelope"></i>
                    Enviar Email
                </a>
            </div>
        </div>
    </div>
</section>
@endsection