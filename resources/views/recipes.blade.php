@extends('layouts.app')

@section('title', 'Servicios - ElectraHome | Reparación y Mantenimiento de Electrodomésticos')

@section('content')

<div class="services-page">
    <!-- Hero Section -->
    @if(isset($sectionsData['hero']) && $sectionsData['hero'])
        @php $heroSection = $sectionsData['hero']; @endphp
        <section class="services-hero">
            <div class="hero-background">
                @if($heroSection->getImagesArray())
                    <img src="{{ Storage::url($heroSection->getImagesArray()[0]) }}" alt="Servicios ElectraHome" class="hero-bg-image">
                @else
                    <img src="{{ asset('images/hero-services.jpg') }}" alt="Servicios ElectraHome" class="hero-bg-image">
                @endif
                <div class="hero-overlay"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <h1 class="hero-title">{{ $heroSection->title ?? 'Nuestros Servicios' }}</h1>
                        <p class="hero-subtitle">{{ $heroSection->content ?? 'Servicio técnico especializado en línea blanca y electrodomésticos Oster en Quito' }}</p>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="services-hero">
            <div class="hero-background">
                <img src="{{ asset('images/hero-services.jpg') }}" alt="Servicios ElectraHome" class="hero-bg-image">
                <div class="hero-overlay"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <h1 class="hero-title">Nuestros Servicios</h1>
                        <p class="hero-subtitle">Servicio técnico especializado en línea blanca y electrodomésticos Oster en Quito</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Services Section -->
    @if(isset($sectionsData['intro']) && $sectionsData['intro'])
        @php $introSection = $sectionsData['intro']; @endphp
        <section class="main-services">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">{{ $introSection->title ?? '¿Qué Hacemos?' }}</h2>
                        <p class="section-description">
                            {{ $introSection->content ?? 'Somos especialistas en reparación, mantenimiento e instalación de electrodomésticos. Con más de 10 años de experiencia, brindamos servicio técnico certificado en toda la ciudad de Quito.' }}
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Reparación Especializada -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <h3 class="service-title">{{ $introSection->getCustomData('repair_title', 'Reparación Especializada') }}</h3>
                            <p class="service-description">
                                {{ $introSection->getCustomData('repair_description', 'Diagnóstico y reparación de fallas en todos los tipos de electrodomésticos con repuestos originales y garantía.') }}
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('repair_feature_1', 'Diagnóstico gratuito') }}</li>
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('repair_feature_2', 'Repuestos originales') }}</li>
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('repair_feature_3', 'Garantía incluida') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Mantenimiento Preventivo -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h3 class="service-title">{{ $introSection->getCustomData('maintenance_title', 'Mantenimiento Preventivo') }}</h3>
                            <p class="service-description">
                                {{ $introSection->getCustomData('maintenance_description', 'Servicios de limpieza y mantenimiento programado para prolongar la vida útil de tus electrodomésticos.') }}
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('maintenance_feature_1', 'Limpieza profunda') }}</li>
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('maintenance_feature_2', 'Revisión completa') }}</li>
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('maintenance_feature_3', 'Planes de mantenimiento') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Instalación Profesional -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="service-title">{{ $introSection->getCustomData('installation_title', 'Instalación Profesional') }}</h3>
                            <p class="service-description">
                                {{ $introSection->getCustomData('installation_description', 'Instalación segura y correcta de electrodomésticos nuevos con conexiones eléctricas y de agua certificadas.') }}
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('installation_feature_1', 'Instalación certificada') }}</li>
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('installation_feature_2', 'Pruebas de funcionamiento') }}</li>
                                <li><i class="fas fa-check"></i>{{ $introSection->getCustomData('installation_feature_3', 'Capacitación de uso') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="main-services">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">¿Qué Hacemos?</h2>
                        <p class="section-description">
                            Somos especialistas en reparación, mantenimiento e instalación de electrodomésticos. 
                            Con más de 10 años de experiencia, brindamos servicio técnico certificado en toda la ciudad de Quito.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Reparación -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-wrench"></i>
                            </div>
                            <h3 class="service-title">Reparación Especializada</h3>
                            <p class="service-description">
                                Diagnóstico y reparación de fallas en todos los tipos de electrodomésticos con repuestos originales y garantía.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Diagnóstico gratuito</li>
                                <li><i class="fas fa-check"></i>Repuestos originales</li>
                                <li><i class="fas fa-check"></i>Garantía incluida</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Mantenimiento -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h3 class="service-title">Mantenimiento Preventivo</h3>
                            <p class="service-description">
                                Servicios de limpieza y mantenimiento programado para prolongar la vida útil de tus electrodomésticos.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Limpieza profunda</li>
                                <li><i class="fas fa-check"></i>Revisión completa</li>
                                <li><i class="fas fa-check"></i>Planes de mantenimiento</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Instalación -->
                    <div class="col-lg-4 col-md-6">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="service-title">Instalación Profesional</h3>
                            <p class="service-description">
                                Instalación segura y correcta de electrodomésticos nuevos con conexiones eléctricas y de agua certificadas.
                            </p>
                            <ul class="service-features">
                                <li><i class="fas fa-check"></i>Instalación certificada</li>
                                <li><i class="fas fa-check"></i>Pruebas de funcionamiento</li>
                                <li><i class="fas fa-check"></i>Capacitación de uso</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Appliances Section -->
    @if(isset($sectionsData['services_list']) && $sectionsData['services_list'])
        @php $servicesSection = $sectionsData['services_list']; @endphp
        <section class="appliances-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="section-title">{{ $servicesSection->title ?? 'Electrodomésticos que Reparamos' }}</h2>
                        <p class="section-description">
                            {{ $servicesSection->content ?? 'Trabajamos con todas las marcas y modelos de línea blanca. Nuestros técnicos están capacitados para reparar cualquier electrodoméstico del hogar.' }}
                        </p>

                        <div class="appliances-grid">
                            <div class="appliance-item">
                                <div class="appliance-icon">{{ $servicesSection->getCustomData('service_1_icon', '🏠') }}</div>
                                <div class="appliance-info">
                                    <h4>{{ $servicesSection->getCustomData('service_1_title', 'Línea Blanca') }}</h4>
                                    <p>{{ $servicesSection->getCustomData('service_1_desc', 'Lavadoras, secadoras, refrigeradoras, cocinas, microondas, calefones, lavavajillas, aspiradoras') }}</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">{{ $servicesSection->getCustomData('service_2_icon', '⚡') }}</div>
                                <div class="appliance-info">
                                    <h4>{{ $servicesSection->getCustomData('service_2_title', 'Electrodomésticos Oster') }}</h4>
                                    <p>{{ $servicesSection->getCustomData('service_2_desc', 'Licuadoras, freidoras de aire, extractores, sanducheras, procesadores de alimentos') }}</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">{{ $servicesSection->getCustomData('service_3_icon', '🔧') }}</div>
                                <div class="appliance-info">
                                    <h4>{{ $servicesSection->getCustomData('service_3_title', 'Todas las Marcas') }}</h4>
                                    <p>{{ $servicesSection->getCustomData('service_3_desc', 'LG, Samsung, Whirlpool, Electrolux, Mabe, Indurama, Oster y más') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="appliances-image">
                            @if($servicesSection->getImagesArray())
                                <img src="{{ Storage::url($servicesSection->getImagesArray()[0]) }}" alt="Reparación de Electrodomésticos" class="img-fluid rounded">
                            @else
                                <img src="{{ asset('images/appliances-repair.jpg') }}" alt="Reparación de Electrodomésticos" class="img-fluid rounded">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="appliances-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h2 class="section-title">Electrodomésticos que Reparamos</h2>
                        <p class="section-description">
                            Trabajamos con todas las marcas y modelos de línea blanca. Nuestros técnicos están capacitados 
                            para reparar cualquier electrodoméstico del hogar.
                        </p>

                        <div class="appliances-grid">
                            <div class="appliance-item">
                                <div class="appliance-icon">🏠</div>
                                <div class="appliance-info">
                                    <h4>Línea Blanca</h4>
                                    <p>Lavadoras, secadoras, refrigeradoras, cocinas, microondas, calefones, lavavajillas, aspiradoras</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">⚡</div>
                                <div class="appliance-info">
                                    <h4>Electrodomésticos Oster</h4>
                                    <p>Licuadoras, freidoras de aire, extractores, sanducheras, procesadores de alimentos</p>
                                </div>
                            </div>

                            <div class="appliance-item">
                                <div class="appliance-icon">🔧</div>
                                <div class="appliance-info">
                                    <h4>Todas las Marcas</h4>
                                    <p>LG, Samsung, Whirlpool, Electrolux, Mabe, Indurama, Oster y más</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="appliances-image">
                            <img src="{{ asset('images/appliances-repair.jpg') }}" alt="Reparación de Electrodomésticos" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Process Section -->
    @if(isset($sectionsData['process']) && $sectionsData['process'])
        @php $processSection = $sectionsData['process']; @endphp
        <section class="process-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">{{ $processSection->title ?? '¿Cómo Trabajamos?' }}</h2>
                        <p class="section-description">
                            {{ $processSection->content ?? 'Nuestro proceso es simple, rápido y transparente. Te acompañamos desde el primer contacto hasta que tu electrodoméstico quede funcionando perfectamente.' }}
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_1_number', '1') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_1_title', 'Contacto') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_1_desc', 'Llámanos o escríbenos por WhatsApp. Te atendemos inmediatamente y agendamos tu cita.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_2_number', '2') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_2_title', 'Diagnóstico') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_2_desc', 'Nuestro técnico visita tu hogar, revisa el electrodoméstico y te da un diagnóstico gratuito.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_3_number', '3') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-hammer"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_3_title', 'Reparación') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_3_desc', 'Una vez aprobado el presupuesto, realizamos la reparación con repuestos originales.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">{{ $processSection->getCustomData('step_4_number', '4') }}</div>
                            <div class="step-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h4 class="step-title">{{ $processSection->getCustomData('step_4_title', 'Garantía') }}</h4>
                            <p class="step-description">
                                {{ $processSection->getCustomData('step_4_desc', 'Tu electrodoméstico queda funcionando perfecto y con garantía por nuestro trabajo.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="process-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">¿Cómo Trabajamos?</h2>
                        <p class="section-description">
                            Nuestro proceso es simple, rápido y transparente. Te acompañamos desde el primer contacto hasta 
                            que tu electrodoméstico quede funcionando perfectamente.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">1</div>
                            <div class="step-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h4 class="step-title">Contacto</h4>
                            <p class="step-description">
                                Llámanos o escríbenos por WhatsApp. Te atendemos inmediatamente y agendamos tu cita.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">2</div>
                            <div class="step-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4 class="step-title">Diagnóstico</h4>
                            <p class="step-description">
                                Nuestro técnico visita tu hogar, revisa el electrodoméstico y te da un diagnóstico gratuito.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">3</div>
                            <div class="step-icon">
                                <i class="fas fa-hammer"></i>
                            </div>
                            <h4 class="step-title">Reparación</h4>
                            <p class="step-description">
                                Una vez aprobado el presupuesto, realizamos la reparación con repuestos originales.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="process-step">
                            <div class="step-number">4</div>
                            <div class="step-icon">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <h4 class="step-title">Garantía</h4>
                            <p class="step-description">
                                Tu electrodoméstico queda funcionando perfecto y con garantía por nuestro trabajo.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Oster Products Section - AHORA DINÁMICO -->
    @if(isset($sectionsData['oster_section']) && $sectionsData['oster_section'])
        @php $osterSection = $sectionsData['oster_section']; @endphp
        <section class="oster-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="oster-image">
                            @if($osterSection->getImagesArray())
                                <img src="{{ Storage::url($osterSection->getImagesArray()[0]) }}" alt="Productos Oster" class="img-fluid rounded">
                            @else
                                <img src="{{ asset('images/oster-products.jpg') }}" alt="Productos Oster" class="img-fluid rounded">
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h2 class="section-title">{{ $osterSection->title ?? 'Especialistas en Oster' }}</h2>
                        <p class="section-description">
                            {{ $osterSection->content ?? 'Además de nuestro servicio técnico, también vendemos y reparamos la línea completa de electrodomésticos Oster. Somos distribuidores autorizados con repuestos originales.' }}
                        </p>

                        <div class="oster-services">
                            <div class="oster-service">
                                <i class="fas fa-shopping-cart"></i>
                                <div>
                                    <h4>{{ $osterSection->getCustomData('oster_service_1_title', 'Venta de Productos Oster') }}</h4>
                                    <p>{{ $osterSection->getCustomData('oster_service_1_desc', 'Licuadoras, freidoras de aire, extractores, sanducheras y más') }}</p>
                                </div>
                            </div>

                            <div class="oster-service">
                                <i class="fas fa-wrench"></i>
                                <div>
                                    <h4>{{ $osterSection->getCustomData('oster_service_2_title', 'Reparación Especializada Oster') }}</h4>
                                    <p>{{ $osterSection->getCustomData('oster_service_2_desc', 'Servicio técnico autorizado con repuestos originales') }}</p>
                                </div>
                            </div>

                            <div class="oster-service">
                                <i class="fas fa-medal"></i>
                                <div>
                                    <h4>{{ $osterSection->getCustomData('oster_service_3_title', 'Garantía Oficial') }}</h4>
                                    <p>{{ $osterSection->getCustomData('oster_service_3_desc', 'Respaldamos nuestros productos y servicios con garantía completa') }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ $osterSection->getCustomData('button_url', route('shop.index')) }}" class="btn btn-primary btn-lg mt-4">
                            <i class="fas fa-eye me-2"></i>{{ $osterSection->getCustomData('button_text', 'Ver Productos Oster') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="oster-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="oster-image">
                            <img src="{{ asset('images/oster-products.jpg') }}" alt="Productos Oster" class="img-fluid rounded">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h2 class="section-title">Especialistas en Oster</h2>
                        <p class="section-description">
                            Además de nuestro servicio técnico, también vendemos y reparamos la línea completa de 
                            electrodomésticos Oster. Somos distribuidores autorizados con repuestos originales.
                        </p>

                        <div class="oster-services">
                            <div class="oster-service">
                                <i class="fas fa-shopping-cart"></i>
                                <div>
                                    <h4>Venta de Productos Oster</h4>
                                    <p>Licuadoras, freidoras de aire, extractores, sanducheras y más</p>
                                </div>
                            </div>

                            <div class="oster-service">
                                <i class="fas fa-wrench"></i>
                                <div>
                                    <h4>Reparación Especializada Oster</h4>
                                    <p>Servicio técnico autorizado con repuestos originales</p>
                                </div>
                            </div>

                            <div class="oster-service">
                                <i class="fas fa-medal"></i>
                                <div>
                                    <h4>Garantía Oficial</h4>
                                    <p>Respaldamos nuestros productos y servicios con garantía completa</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg mt-4">
                            <i class="fas fa-eye me-2"></i>Ver Productos Oster
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Coverage Area Section - AHORA DINÁMICO -->
    @if(isset($sectionsData['coverage_section']) && $sectionsData['coverage_section'])
        @php $coverageSection = $sectionsData['coverage_section']; @endphp
        <section class="coverage-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">{{ $coverageSection->title ?? 'Zona de Cobertura' }}</h2>
                        <p class="section-description">
                            {{ $coverageSection->content ?? 'Brindamos servicio técnico a domicilio en toda la ciudad de Quito y sus valles. No importa dónde estés, llegamos hasta ti.' }}
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>{{ $coverageSection->getCustomData('zone_1_title', 'Norte de Quito') }}</h4>
                            <p>{{ $coverageSection->getCustomData('zone_1_areas', 'Carcelén, La Delicia, Comité del Pueblo, Carapungo, Calderón') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>{{ $coverageSection->getCustomData('zone_2_title', 'Centro de Quito') }}</h4>
                            <p>{{ $coverageSection->getCustomData('zone_2_areas', 'Centro Histórico, La Mariscal, La Carolina, González Suárez') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>{{ $coverageSection->getCustomData('zone_3_title', 'Sur de Quito') }}</h4>
                            <p>{{ $coverageSection->getCustomData('zone_3_areas', 'Quitumbe, Solanda, La Magdalena, Chillogallo, Guamaní') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>{{ $coverageSection->getCustomData('zone_4_title', 'Valles') }}</h4>
                            <p>{{ $coverageSection->getCustomData('zone_4_areas', 'Cumbayá, Tumbaco, Conocoto, San Rafael, Sangolquí') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>{{ $coverageSection->getCustomData('zone_5_title', 'Oeste de Quito') }}</h4>
                            <p>{{ $coverageSection->getCustomData('zone_5_areas', 'La Mitad del Mundo, Pomasqui, San Antonio, Nayón') }}</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>{{ $coverageSection->getCustomData('zone_6_title', 'Sectores Especiales') }}</h4>
                            <p>{{ $coverageSection->getCustomData('zone_6_areas', 'Consulta disponibilidad para otras zonas metropolitanas') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="coverage-section">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title">Zona de Cobertura</h2>
                        <p class="section-description">
                            Brindamos servicio técnico a domicilio en toda la ciudad de Quito y sus valles. 
                            No importa dónde estés, llegamos hasta ti.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>Norte de Quito</h4>
                            <p>Carcelén, La Delicia, Comité del Pueblo, Carapungo, Calderón</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>Centro de Quito</h4>
                            <p>Centro Histórico, La Mariscal, La Carolina, González Suárez</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>Sur de Quito</h4>
                            <p>Quitumbe, Solanda, La Magdalena, Chillogallo, Guamaní</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>Valles</h4>
                            <p>Cumbayá, Tumbaco, Conocoto, San Rafael, Sangolquí</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>Oeste de Quito</h4>
                            <p>La Mitad del Mundo, Pomasqui, San Antonio, Nayón</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="coverage-area">
                            <h4><i class="fas fa-map-marker-alt me-2"></i>Sectores Especiales</h4>
                            <p>Consulta disponibilidad para otras zonas metropolitanas</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- CTA Section - AHORA COMPLETAMENTE DINÁMICO -->
    @if(isset($sectionsData['cta']) && $sectionsData['cta'])
        @php $ctaSection = $sectionsData['cta']; @endphp
        <section class="cta-section">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h2 class="cta-title">{{ $ctaSection->title ?? '¿Necesitas Ayuda con tus Electrodomésticos?' }}</h2>
                        <p class="cta-description">
                            {{ $ctaSection->content ?? 'No esperes más. Contacta a nuestros expertos y recibe atención inmediata. Diagnóstico gratuito y presupuesto sin compromiso.' }}
                        </p>
                        
                        <div class="cta-buttons">
                            <a href="{{ $ctaSection->getCustomData('whatsapp_url', 'https://wa.me/593987654321') }}" target="_blank" class="btn btn-whatsapp btn-lg me-3">
                                <i class="fab fa-whatsapp me-2"></i>{{ $ctaSection->getCustomData('button_primary_text', 'WhatsApp') }}
                            </a>
                            <a href="{{ $ctaSection->getCustomData('contact_url', route('contact.index')) }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-envelope me-2"></i>{{ $ctaSection->getCustomData('button_secondary_text', 'Contactar') }}
                            </a>
                        </div>

                        <div class="contact-info mt-4">
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $ctaSection->getCustomData('business_hours', 'Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 4:00 PM') }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $ctaSection->getCustomData('phone_number', '+593 2 234 5678') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="cta-section">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h2 class="cta-title">¿Necesitas Ayuda con tus Electrodomésticos?</h2>
                        <p class="cta-description">
                            No esperes más. Contacta a nuestros expertos y recibe atención inmediata. 
                            Diagnóstico gratuito y presupuesto sin compromiso.
                        </p>
                        
                        <div class="cta-buttons">
                            <a href="https://wa.me/593987654321" target="_blank" class="btn btn-whatsapp btn-lg me-3">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </a>
                            <a href="{{ route('contact.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-envelope me-2"></i>Contactar
                            </a>
                        </div>

                        <div class="contact-info mt-4">
                            <div class="contact-item">
                                <i class="fas fa-clock"></i>
                                <span>Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 4:00 PM</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+593 2 234 5678</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
<style>
.services-page {
    font-family: 'Inter', sans-serif;
}

/* Hero Section */
.services-hero {
    position: relative;
    height: 60vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 169, 224, 0.9) 0%, rgba(0, 207, 180, 0.8) 100%);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 900;
    color: white;
    margin-bottom: 20px;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.3rem;
    color: rgba(255, 255, 255, 0.95);
    position: relative;
    z-index: 2;
    font-weight: 300;
}

/* Main Services */
.main-services {
    padding: 100px 0;
    background: #f8f9fa;
}

.section-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: #101820;
    margin-bottom: 30px;
    line-height: 1.2;
}

.section-description {
    font-size: 1.2rem;
    line-height: 1.7;
    color: #666;
    margin-bottom: 50px;
}

.service-card {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.4s ease;
    height: 100%;
    border: 3px solid transparent;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0, 169, 224, 0.2);
    border-color: #00A9E0;
}

.service-icon {
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    color: white;
    font-size: 2rem;
}

.service-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #101820;
    margin-bottom: 20px;
}

.service-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 25px;
}

.service-features {
    list-style: none;
    padding: 0;
    text-align: left;
}

.service-features li {
    padding: 8px 0;
    color: #555;
    font-size: 0.9rem;
}

.service-features i {
    color: #00A9E0;
    margin-right: 10px;
    font-size: 0.8rem;
}

/* Appliances Section */
.appliances-section {
    padding: 100px 0;
    background: white;
}

.appliances-grid {
    display: flex;
    flex-direction: column;
    gap: 30px;
    margin-top: 40px;
}

.appliance-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.appliance-item:hover {
    background: rgba(0, 169, 224, 0.05);
    transform: translateX(10px);
}

.appliance-icon {
    font-size: 3rem;
    flex-shrink: 0;
}

.appliance-info h4 {
    color: #00A9E0;
    font-weight: 700;
    margin-bottom: 10px;
}

.appliance-info p {
    color: #666;
    margin: 0;
    line-height: 1.5;
}

.appliances-image {
    position: relative;
}

.appliances-image::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 20px;
    right: -20px;
    bottom: -20px;
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    border-radius: 15px;
    z-index: -1;
}

/* Process Section */
.process-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.process-step {
    text-align: center;
    position: relative;
    padding: 30px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.process-step:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 169, 224, 0.15);
}

.step-number {
    position: absolute;
    top: -20px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.step-icon {
    background: rgba(0, 169, 224, 0.1);
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 20px auto 25px;
    color: #00A9E0;
    font-size: 2rem;
}

.step-title {
    color: #101820;
    font-weight: 700;
    margin-bottom: 15px;
}

.step-description {
    color: #666;
    line-height: 1.5;
    font-size: 0.95rem;
}

/* Oster Section */
.oster-section {
    padding: 100px 0;
    background: white;
}

.oster-image {
    position: relative;
}

.oster-image::after {
    content: '';
    position: absolute;
    top: -20px;
    left: -20px;
    right: 20px;
    bottom: 20px;
    background: linear-gradient(135deg, #00CFB4, #00A9E0);
    border-radius: 15px;
    z-index: -1;
}

.oster-services {
    margin: 40px 0;
}

.oster-service {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.oster-service:hover {
    background: rgba(0, 169, 224, 0.05);
    transform: translateX(10px);
}

.oster-service i {
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.oster-service h4 {
    color: #101820;
    font-weight: 700;
    margin-bottom: 5px;
}

.oster-service p {
    color: #666;
    margin: 0;
    font-size: 0.9rem;
}

/* Coverage Section */
.coverage-section {
    padding: 100px 0;
    background: #f8f9fa;
}

.coverage-area {
    background: white;
    padding: 30px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.coverage-area:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 169, 224, 0.15);
}

.coverage-area h4 {
    color: #00A9E0;
    font-weight: 700;
    margin-bottom: 15px;
}

.coverage-area p {
    color: #666;
    margin: 0;
    line-height: 1.5;
}

/* CTA Section */
.cta-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #101820 0%, #1a252f 100%);
    color: white;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 20px;
}

.cta-description {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 40px;
    opacity: 0.9;
}

.cta-buttons {
    margin-bottom: 40px;
}

.btn-whatsapp {
    background: #25d366;
    border-color: #25d366;
    color: white;
}

.btn-whatsapp:hover {
    background: #128c7e;
    border-color: #128c7e;
    color: white;
}

.btn-primary {
    background: linear-gradient(135deg, #00A9E0, #00CFB4);
    border: none;
    padding: 15px 30px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #00CFB4, #00A9E0);
    transform: translateY(-2px);
}

.contact-info {
    opacity: 0.8;
}

.contact-item {
    display: inline-block;
    margin: 0 20px;
    font-size: 0.9rem;
}

.contact-item i {
    margin-right: 8px;
    color: #00A9E0;
}

/* Responsive */
@media (max-width: 992px) {
    .hero-title {
        font-size: 2.8rem;
    }
    
    .section-title {
        font-size: 2.4rem;
    }
    
    .appliances-grid {
        gap: 20px;
    }
    
    .appliance-item {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .main-services, 
    .appliances-section, 
    .process-section, 
    .oster-section, 
    .coverage-section, 
    .cta-section {
        padding: 60px 0;
    }
    
    .service-card {
        padding: 30px 20px;
    }
    
    .cta-buttons .btn {
        display: block;
        margin: 10px auto;
        width: 80%;
    }
    
    .contact-item {
        display: block;
        margin: 10px 0;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .step-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

@endsection