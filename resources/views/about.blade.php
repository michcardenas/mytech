{{-- Reemplaza tu vista about.blade.php con este código --}}
@extends('layouts.app')

@section('title', 'Acerca de Nosotros - ElectraHome')

@section('content')
<div class="about-page">
    {{-- HERO SECTION - Dinámico --}}
    {{-- DEBUG LEGACY SECTION - Agregar antes del LEGACY SECTION --}}
@if(isset($sectionsData['legacy']))
    <div style="background: orange; padding: 15px; margin: 10px; font-family: monospace; border: 2px solid red;">
        <h3>🐛 DEBUG LEGACY SECTION</h3>
        <p><strong>Section exists:</strong> {{ $sectionsData['legacy'] ? 'YES' : 'NO' }}</p>
        @if($sectionsData['legacy'])
            <p><strong>Section name:</strong> {{ $sectionsData['legacy']->name ?? 'NULL' }}</p>
            <p><strong>Images field raw:</strong> {{ $sectionsData['legacy']->images ?? 'NULL' }}</p>
            <p><strong>Images field length:</strong> {{ strlen($sectionsData['legacy']->images ?? '') }}</p>
            
            {{-- Intentar llamar getImagesArray() --}}
            @php
                try {
                    $imagesArray = $sectionsData['legacy']->getImagesArray();
                    $imagesArrayJson = json_encode($imagesArray);
                    $hasImages = !empty($imagesArray);
                } catch (Exception $e) {
                    $imagesArrayJson = 'ERROR: ' . $e->getMessage();
                    $hasImages = false;
                }
            @endphp
            
            <p><strong>getImagesArray() result:</strong> {{ $imagesArrayJson }}</p>
            <p><strong>Has images:</strong> {{ $hasImages ? 'YES' : 'NO' }}</p>
            
            {{-- Método manual --}}
            @if($sectionsData['legacy']->images)
                @php
                    $manualImages = array_filter(explode(',', $sectionsData['legacy']->images));
                    $manualImages = array_map('trim', $manualImages);
                @endphp
                <p><strong>Manual split result:</strong> {{ json_encode($manualImages) }}</p>
                <p><strong>First manual image:</strong> {{ $manualImages[0] ?? 'NO IMAGE' }}</p>
            @endif
        @endif
    </div>
@endif
    @if(isset($sectionsData['hero']) && $sectionsData['hero'])
    @php $heroSection = $sectionsData['hero']; @endphp
    <section class="about-hero">
        <div class="hero-background">
            {{-- Imagen de fondo dinámica o logo por defecto --}}
            @if($heroSection->getImagesArray())
                <img src="{{ asset('storage/' . $heroSection->getImagesArray()[0]) }}" alt="ElectraHome" class="hero-bg-image">
            @else
                <img src="{{ asset('images/logo.png') }}" alt="ElectraHome" class="hero-bg-image">
            @endif
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="hero-title">{{ $heroSection->title ?? 'Acerca de ElectraHome' }}</h1>
                    <p class="hero-subtitle">{{ $heroSection->content ?? 'Tradición en Electrodomésticos de Calidad' }}</p>
                </div>
            </div>
        </div>
    </section>
    @else
    {{-- Fallback si no hay sección hero --}}
    <section class="about-hero">
        <div class="hero-background">
            <img src="{{ asset('images/logo.png') }}" alt="ElectraHome" class="hero-bg-image">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="hero-title">Acerca de ElectraHome</h1>
                    <p class="hero-subtitle">Tradición en Electrodomésticos de Calidad</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Main Content -->
    <section class="about-content">
        <div class="container">

            {{-- LEGACY SECTION - Tradición y Calidad --}}
            @if(isset($sectionsData['legacy']) && $sectionsData['legacy'])
            @php $legacySection = $sectionsData['legacy']; @endphp
            <div class="content-section">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="content-text">
                            <h2 class="section-title">{{ $legacySection->title ?? 'Tradición en Electrodomésticos de Calidad' }}</h2>
                            
                            {{-- Párrafos dinámicos --}}
                            @if($legacySection->getCustomData('paragraph_1'))
                                <p class="section-description">{{ $legacySection->getCustomData('paragraph_1') }}</p>
                            @else
                                <p class="section-description">
                                    En ElectraHome, cada electrodoméstico que ofrecemos representa años de innovación y compromiso 
                                    con la calidad. Nos especializamos en productos <strong>Oster</strong>, una marca reconocida 
                                    mundialmente por su durabilidad, eficiencia y diseño superior.
                                </p>
                            @endif

                            @if($legacySection->getCustomData('paragraph_2'))
                                <p class="section-description">{{ $legacySection->getCustomData('paragraph_2') }}</p>
                            @else
                                <p class="section-description">
                                    Desde licuadoras de alta potencia hasta freidoras de aire revolucionarias, cada producto 
                                    está diseñado para hacer tu vida más fácil y eficiente en la cocina.
                                </p>
                            @endif

                            {{-- Quote dinámico --}}
                            @if($legacySection->getCustomData('quote'))
                                <blockquote class="company-quote">
                                    "{{ $legacySection->getCustomData('quote') }}"
                                </blockquote>
                            @else
                                <blockquote class="company-quote">
                                    "Imagínate una cocina donde cada electrodoméstico funciona a la perfección, donde la calidad 
                                    se encuentra con la innovación. No solo vendemos productos, ofrecemos soluciones que 
                                    transforman tu experiencia culinaria diaria."
                                </blockquote>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content-image">
                            {{-- Imagen dinámica --}}
                            @if($legacySection->getImagesArray())
                                <img src="{{ asset('storage/' . $legacySection->getImagesArray()[0]) }}" alt="{{ $legacySection->title }}" class="section-img">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Productos Oster de Calidad" class="section-img logo-placeholder">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- QUALITY SECTION - Garantía y Servicio --}}
            @if(isset($sectionsData['quality']) && $sectionsData['quality'])
            @php $qualitySection = $sectionsData['quality']; @endphp
            <div class="content-section bg-light-blue">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                        <div class="content-text">
                            <h2 class="section-title">{{ $qualitySection->title ?? 'Garantía Oficial y Servicio Especializado' }}</h2>
                            
                            {{-- Párrafos dinámicos --}}
                            @if($qualitySection->getCustomData('paragraph_1'))
                                <p class="section-description">{{ $qualitySection->getCustomData('paragraph_1') }}</p>
                            @else
                                <p class="section-description">
                                    Como distribuidores autorizados de Oster, ofrecemos garantía oficial en todos nuestros 
                                    productos. Nuestro equipo técnico especializado está capacitado directamente por la marca 
                                    para brindar el mejor servicio postventa de Venezuela.
                                </p>
                            @endif

                            @if($qualitySection->getCustomData('paragraph_2'))
                                <p class="section-description">{{ $qualitySection->getCustomData('paragraph_2') }}</p>
                            @else
                                <p class="section-description">
                                    Cada producto viene con manual en español, repuestos originales disponibles, y un servicio 
                                    técnico que entiende perfectamente las necesidades del mercado venezolano.
                                </p>
                            @endif

                            {{-- Badges dinámicos --}}
                            <div class="quality-badges">
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_1', 'Garantía Oficial') }}</span>
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_2', 'Servicio Técnico') }}</span>
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_3', 'Repuestos Originales') }}</span>
                                <span class="badge-item">✓ {{ $qualitySection->getCustomData('badge_4', 'Soporte en Español') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="content-image">
                            {{-- Imagen dinámica --}}
                            @if($qualitySection->getImagesArray())
                                <img src="{{ asset('storage/' . $qualitySection->getImagesArray()[0]) }}" alt="{{ $qualitySection->title }}" class="section-img">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Servicio Técnico Especializado" class="section-img logo-placeholder">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- PASSION SECTION - Pasión del Equipo --}}
            @if(isset($sectionsData['passion']) && $sectionsData['passion'])
            @php $passionSection = $sectionsData['passion']; @endphp
            <div class="content-section">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="content-text">
                            <h2 class="section-title">{{ $passionSection->title ?? 'La Pasión Detrás del Servicio' }}</h2>
                            
                            {{-- Párrafos dinámicos --}}
                            @if($passionSection->getCustomData('paragraph_1'))
                                <p class="section-description">{{ $passionSection->getCustomData('paragraph_1') }}</p>
                            @else
                                <p class="section-description">
                                    Nuestro equipo no son solo vendedores; somos entusiastas de la cocina que entendemos 
                                    la importancia de tener herramientas confiables. Conocemos cada producto, cada 
                                    característica, y cada beneficio que puede aportar a tu hogar.
                                </p>
                            @endif

                            @if($passionSection->getCustomData('paragraph_2'))
                                <p class="section-description">{{ $passionSection->getCustomData('paragraph_2') }}</p>
                            @else
                                <p class="section-description">
                                    Esta pasión se traduce en un servicio personalizado que no solo te ayuda a encontrar 
                                    el producto perfecto, sino que te acompaña durante toda su vida útil.
                                </p>
                            @endif

                            {{-- Quote del equipo dinámico --}}
                            <div class="team-quote">
                                <div class="quote-content">
                                    <p>"{{ $passionSection->getCustomData('team_quote', 'No solo vendemos electrodomésticos, creamos experiencias culinarias excepcionales para cada familia venezolana.') }}"</p>
                                    <cite>{{ $passionSection->getCustomData('quote_author', '- Equipo ElectraHome, Aragua, Venezuela') }}</cite>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content-image">
                            {{-- Imagen dinámica --}}
                            @if($passionSection->getImagesArray())
                                <img src="{{ asset('storage/' . $passionSection->getImagesArray()[0]) }}" alt="{{ $passionSection->title }}" class="section-img">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Equipo ElectraHome" class="section-img logo-placeholder">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- BENEFITS SECTION - Por Qué Elegir --}}
            @if(isset($sectionsData['benefits']) && $sectionsData['benefits'])
            @php $benefitsSection = $sectionsData['benefits']; @endphp
            <div class="content-section bg-dark-blue">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <h2 class="section-title text-white">{{ $benefitsSection->title ?? 'Por Qué Elegir ElectraHome' }}</h2>
                        
                        {{-- Párrafos dinámicos --}}
                        @if($benefitsSection->getCustomData('paragraph_1'))
                            <p class="section-description text-white mb-4">{{ $benefitsSection->getCustomData('paragraph_1') }}</p>
                        @else
                            <p class="section-description text-white mb-4">
                                Elegir ElectraHome significa elegir productos que duran, un servicio que te respalda, 
                                y una experiencia de compra que supera tus expectativas. Nuestros electrodomésticos 
                                Oster están diseñados para la vida moderna venezolana.
                            </p>
                        @endif

                        @if($benefitsSection->getCustomData('paragraph_2'))
                            <p class="section-description text-white mb-5">{{ $benefitsSection->getCustomData('paragraph_2') }}</p>
                        @else
                            <p class="section-description text-white mb-5">
                                Además, cada compra incluye capacitación gratuita sobre el uso del producto, recetas 
                                exclusivas, y acceso a nuestra comunidad de usuarios donde compartimos tips y trucos culinarios.
                            </p>
                        @endif

                        {{-- Beneficios dinámicos --}}
                        <div class="benefits-grid">
                            <div class="benefit-item">
                                <div class="benefit-icon">{{ $benefitsSection->getCustomData('benefit_1_icon', '⚡') }}</div>
                                <h4>{{ $benefitsSection->getCustomData('benefit_1_title', 'Mejor para Ti') }}</h4>
                                <p>{{ $benefitsSection->getCustomData('benefit_1_desc', 'Productos eficientes, duraderos y fáciles de usar') }}</p>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">{{ $benefitsSection->getCustomData('benefit_2_icon', '🛠️') }}</div>
                                <h4>{{ $benefitsSection->getCustomData('benefit_2_title', 'Mejor Servicio') }}</h4>
                                <p>{{ $benefitsSection->getCustomData('benefit_2_desc', 'Garantía oficial y soporte técnico especializado') }}</p>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">{{ $benefitsSection->getCustomData('benefit_3_icon', '🏠') }}</div>
                                <h4>{{ $benefitsSection->getCustomData('benefit_3_title', 'Mejor Hogar') }}</h4>
                                <p>{{ $benefitsSection->getCustomData('benefit_3_desc', 'Cocinas más eficientes y momentos familiares especiales') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- CTA SECTION - Llamada a la Acción --}}
            @if(isset($sectionsData['cta']) && $sectionsData['cta'])
            @php $ctaSection = $sectionsData['cta']; @endphp
            <div class="content-section">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h2 class="section-title">{{ $ctaSection->title ?? 'Únete a la Familia ElectraHome' }}</h2>
                        <p class="section-description mb-4">
                            {{ $ctaSection->content ?? 'Te invitamos a ser parte de esta historia. Explora nuestra selección de electrodomésticos Oster y descubre la diferencia que hace elegir calidad, servicio y compromiso.' }}
                        </p>
                        <a href="{{ route('shop.index') }}" class="cta-button">
                            {{ $ctaSection->getCustomData('button_text', 'Explorar Productos Ahora') }}
                        </a>
                        
                        {{-- Pregunta final dinámica --}}
                        @if($ctaSection->getCustomData('final_question'))
                            <p class="cta-question">{{ $ctaSection->getCustomData('final_question') }}</p>
                        @else
                            <p class="cta-question">¿Cuál es tu razón para elegir electrodomésticos de calidad?</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>



<style>
.about-page {
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
}

.about-hero {
    position: relative;
    height: 60vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, #101820 0%, #1a252f 100%);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-bg-image {
    max-width: 300px;
    max-height: 200px;
    object-fit: contain;
    opacity: 0.1;
    filter: brightness(0) invert(1);
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.1) 0%, rgba(0, 207, 180, 0.1) 100%);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    color: #FCFAF1;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    position: relative;
    z-index: 2;
    line-height: 1.1;
}

.hero-subtitle {
    font-size: 1.3rem;
    color: rgba(252, 250, 241, 0.9);
    position: relative;
    z-index: 2;
    font-weight: 300;
    line-height: 1.4;
}

.about-content {
    padding: 80px 0;
    background: #FCFAF1;
}

.content-section {
    padding: 60px 0;
    margin-bottom: 40px;
}

.bg-light-blue {
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.05) 0%, rgba(0, 207, 180, 0.05) 100%);
    border: 2px solid rgba(0, 169, 224, 0.2);
    border-radius: 20px;
    margin: 0 20px;
    padding: 50px 40px;
}

.bg-dark-blue {
    background: linear-gradient(135deg, #101820 0%, #1a252f 100%);
    border-radius: 20px;
    margin: 0 20px;
    color: white;
    padding: 50px 40px;
    position: relative;
    overflow: hidden;
}

.bg-dark-blue::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0, 169, 224, 0.1), rgba(0, 207, 180, 0.1));
    z-index: 1;
}

.bg-dark-blue > * {
    position: relative;
    z-index: 2;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #101820;
    margin-bottom: 30px;
    line-height: 1.2;
}

.section-title.text-white {
    color: #FCFAF1;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #555;
    margin-bottom: 25px;
}

.section-description.text-white {
    color: rgba(252, 250, 241, 0.9);
}

.content-image {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.1), rgba(0, 207, 180, 0.1));
    border-radius: 15px;
    border: 2px solid rgba(0, 169, 224, 0.2);
}

.section-img {
    width: 100%;
    max-width: 300px;
    height: auto;
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.logo-placeholder {
    opacity: 0.8;
    filter: drop-shadow(0 10px 20px rgba(0, 169, 224, 0.3));
}

.section-img:hover {
    transform: scale(1.05);
}

.company-quote {
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.1), rgba(0, 207, 180, 0.1));
    border-left: 4px solid #00A9E0;
    padding: 25px 30px;
    margin: 30px 0;
    font-style: italic;
    font-size: 1.1rem;
    line-height: 1.6;
    border-radius: 0 15px 15px 0;
    color: #101820;
}

.quality-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 30px;
}

.badge-item {
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    color: white;
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    white-space: nowrap;
    box-shadow: 0 3px 10px rgba(0, 169, 224, 0.3);
}

.team-quote {
    background: linear-gradient(135deg, #FCFAF1, #F8F6ED);
    border: 2px solid #00A9E0;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 169, 224, 0.2);
    margin-top: 30px;
}

.quote-content p {
    font-size: 1.2rem;
    font-style: italic;
    color: #101820;
    margin-bottom: 15px;
    line-height: 1.5;
}

.quote-content cite {
    color: #00A9E0;
    font-weight: 600;
    font-style: normal;
    font-size: 0.95rem;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-top: 50px;
}

.benefit-item {
    text-align: center;
    padding: 30px 20px;
    background: rgba(0, 169, 224, 0.1);
    border-radius: 15px;
    border: 2px solid rgba(0, 207, 180, 0.3);
    transition: transform 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-5px);
}

.benefit-icon {
    font-size: 3rem;
    margin-bottom: 20px;
    display: block;
}

.benefit-item h4 {
    color: #FCFAF1;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.benefit-item p {
    color: rgba(252, 250, 241, 0.8);
    font-size: 1rem;
    line-height: 1.5;
}

.cta-button {
    display: inline-block;
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    color: white;
    padding: 18px 40px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 169, 224, 0.4);
    margin-bottom: 30px;
    text-align: center;
}

.cta-button:hover {
    background: linear-gradient(135deg, #00CFB4, #00A9E0);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 207, 180, 0.5);
    color: white;
    text-decoration: none;
}

.cta-question {
    font-style: italic;
    color: #666;
    font-size: 1.1rem;
    margin-top: 20px;
}

/* RESPONSIVE STYLES */

/* Large Desktop */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 3.2rem;
    }
    
    .section-title {
        font-size: 2.3rem;
    }
    
    .section-img {
        max-width: 250px;
    }
}

/* Desktop and Large Tablets */
@media (max-width: 992px) {
    .hero-title {
        font-size: 3rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
    }
    
    .section-title {
        font-size: 2.2rem;
    }
    
    .content-section {
        padding: 40px 0;
    }
    
    .about-content {
        padding: 60px 0;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0 15px;
        padding: 35px 25px;
    }
    
    .benefits-grid {
        gap: 30px;
        margin-top: 40px;
    }
    
    .company-quote {
        padding: 20px 25px;
    }
    
    .team-quote {
        padding: 25px;
    }
}

/* Tablets */
@media (max-width: 768px) {
    .about-hero {
        height: 50vh;
        min-height: 350px;
    }
    
    .hero-title {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
        margin-bottom: 25px;
        text-align: center;
    }
    
    .section-description {
        font-size: 1rem;
        margin-bottom: 20px;
    }
    
    .content-section {
        padding: 35px 0;
    }
    
    .about-content {
        padding: 40px 0;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0 10px;
        padding: 30px 20px;
        border-radius: 15px;
    }
    
    .quality-badges {
        justify-content: center;
        gap: 12px;
    }
    
    .badge-item {
        font-size: 0.85rem;
        padding: 8px 16px;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
        gap: 25px;
        margin-top: 35px;
    }
    
    .benefit-item {
        padding: 25px 15px;
    }
    
    .benefit-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .benefit-item h4 {
        font-size: 1.2rem;
        margin-bottom: 12px;
    }
    
    .cta-button {
        padding: 15px 35px;
        font-size: 1rem;
    }
    
    .content-image {
        margin-top: 25px;
        margin-bottom: 25px;
        padding: 30px;
    }
    
    .section-img {
        max-width: 200px;
    }
    
    /* Row reordering for mobile */
    .row .col-lg-6.order-lg-2 {
        order: 1;
    }
    
    .row .col-lg-6.order-lg-1 {
        order: 2;
    }
}

/* Mobile Landscape and Large Mobile */
@media (max-width: 576px) {
    .about-hero {
        height: 45vh;
        min-height: 300px;
    }
    
    .hero-title {
        font-size: 2rem;
        margin-bottom: 12px;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .section-description {
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 18px;
    }
    
    .content-section {
        padding: 30px 0;
        margin-bottom: 30px;
    }
    
    .about-content {
        padding: 30px 0;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0 5px;
        padding: 25px 15px;
        border-radius: 12px;
    }
    
    .company-quote {
        padding: 18px 20px;
        font-size: 1rem;
        margin: 25px 0;
        border-radius: 0 8px 8px 0;
    }
    
    .team-quote {
        padding: 20px;
        margin-top: 25px;
    }
    
    .quote-content p {
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    
    .quote-content cite {
        font-size: 0.9rem;
    }
    
    .quality-badges {
        gap: 10px;
        margin-top: 25px;
    }
    
    .badge-item {
        font-size: 0.8rem;
        padding: 6px 14px;
    }
    
    .benefits-grid {
        gap: 20px;
        margin-top: 30px;
    }
    
    .benefit-item {
        padding: 20px 12px;
    }
    
    .benefit-icon {
        font-size: 2.2rem;
        margin-bottom: 12px;
    }
    
    .benefit-item h4 {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }
    
    .benefit-item p {
        font-size: 0.9rem;
    }
    
    .cta-button {
        padding: 12px 30px;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        width: 100%;
        max-width: 300px;
    }
    
    .cta-question {
        font-size: 1rem;
        margin-top: 15px;
        line-height: 1.4;
    }
    
    .content-image {
        padding: 25px;
    }
    
    .section-img {
        max-width: 150px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .about-hero {
        height: 40vh;
        min-height: 280px;
    }
    
    .hero-title {
        font-size: 1.8rem;
    }
    
    .hero-subtitle {
        font-size: 0.95rem;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .section-description {
        font-size: 0.9rem;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        margin: 0;
        padding: 20px 10px;
        border-radius: 10px;
    }
    
    .company-quote {
        padding: 15px 18px;
        font-size: 0.95rem;
    }
    
    .team-quote {
        padding: 18px;
    }
    
    .quote-content p {
        font-size: 1rem;
    }
    
    .quality-badges {
        gap: 8px;
    }
    
    .badge-item {
        font-size: 0.75rem;
        padding: 5px 12px;
    }
    
    .benefit-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .benefit-item h4 {
        font-size: 1rem;
    }
    
    .benefit-item p {
        font-size: 0.85rem;
    }
    
    .cta-button {
        padding: 10px 25px;
        font-size: 0.9rem;
    }
    
    .content-image {
        padding: 20px;
    }
    
    .section-img {
        max-width: 120px;
    }
}

/* Extra Small Mobile */
@media (max-width: 360px) {
    .hero-title {
        font-size: 1.6rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .bg-light-blue,
    .bg-dark-blue {
        padding: 15px 8px;
    }
}

/* Reduced motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    .section-img {
        transition: none;
    }
    
    .cta-button {
        transition: none;
    }
    
    .section-img:hover {
        transform: none;
    }
    
    .cta-button:hover {
        transform: none;
    }
    
    .benefit-item:hover {
        transform: none;
    }
}
</style>
@endsection