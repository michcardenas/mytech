@extends('layouts.app_admin')

@section('content')
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
    }

    .edit-container {
        background: var(--white);
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-radius: 15px;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .page-title {
        color: var(--dark-text);
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .nav-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 2rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #666;
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-radius: 0;
        transition: var(--transition);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-blue);
        border-bottom: 3px solid var(--primary-blue);
        background: none;
    }

    .form-section {
        background: var(--white);
        padding: 2rem;
        border-radius: 15px;
        border: 2px solid rgba(0, 123, 255, 0.1);
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
    }

    .section-title {
        color: var(--dark-text);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(0, 123, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
    }

    .contact-method-item {
        background: rgba(0, 123, 255, 0.02);
        border: 1px solid rgba(0, 123, 255, 0.1);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .contact-method-item h5 {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: var(--gradient-blue);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
    }

    .icon-preview {
        font-size: 2rem;
        color: var(--primary-blue);
        margin-right: 1rem;
        width: 50px;
        text-align: center;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        border: none;
        font-weight: 500;
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        color: #055160;
        border-left: 4px solid #0dcaf0;
    }

    .info-item {
        background: rgba(0, 123, 255, 0.02);
        border: 1px solid rgba(0, 123, 255, 0.1);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-item h5 {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="edit-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Editar Página de Contacto
        </h1>
        <div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Configura todos los elementos de la página de contacto: hero, métodos de contacto, formulario y mapa.
    </div>

    <form action="{{ route('admin.pages.contacto.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="contactoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button">
                    <i class="fas fa-home me-2"></i>Sección Hero
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="methods-tab" data-bs-toggle="tab" data-bs-target="#methods" type="button">
                    <i class="fas fa-phone me-2"></i>Métodos de Contacto
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="form-tab" data-bs-toggle="tab" data-bs-target="#form" type="button">
                    <i class="fas fa-envelope me-2"></i>Formulario
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map" type="button">
                    <i class="fas fa-map me-2"></i>Mapa e Info
                </button>
            </li>
        </ul>

        <div class="tab-content" id="contactoTabContent">
            <!-- Hero Section -->
            <div class="tab-pane fade show active" id="hero" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-home"></i>
                        Sección Hero Principal
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Badge Text</label>
                                <input type="text" class="form-control" name="hero_badge" value="{{ $data['hero_badge'] ?? 'Tu Próximo Proyecto Comienza Aquí' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Título Principal</label>
                                <input type="text" class="form-control" name="hero_title" value="{{ $data['hero_title'] ?? '¿Listo para Transformar tu Idea en Realidad?' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Descripción Principal</label>
                                <textarea class="form-control" name="hero_description" rows="4" required>{{ $data['hero_description'] ?? 'No esperes más para digitalizar tu negocio. Conversemos sobre tu proyecto y descubre cómo podemos crear la solución digital perfecta que impulse el crecimiento de tu empresa.' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Texto Botón WhatsApp</label>
                                <input type="text" class="form-control" name="hero_whatsapp_text" value="{{ $data['hero_whatsapp_text'] ?? 'Consultoría Gratuita' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Número de WhatsApp</label>
                                <input type="text" class="form-control" name="hero_whatsapp_number" value="{{ $data['hero_whatsapp_number'] ?? '573123708407' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mensaje de WhatsApp</label>
                                <textarea class="form-control" name="hero_whatsapp_message" rows="2" required>{{ $data['hero_whatsapp_message'] ?? 'Hola, quiero digitalizar mi negocio y me interesa una consultoría gratuita' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Texto Botón Formulario</label>
                                <input type="text" class="form-control" name="hero_form_text" value="{{ $data['hero_form_text'] ?? 'Enviar mi Proyecto' }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Methods Section -->
            <div class="tab-pane fade" id="methods" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-phone"></i>
                        Métodos de Contacto
                    </h3>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="methods_title" value="{{ $data['methods_title'] ?? 'Múltiples Formas de Contactarnos' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="methods_description" rows="2" required>{{ $data['methods_description'] ?? 'Elige la opción que más te convenga para comenzar tu proyecto' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp Method -->
                    <div class="contact-method-item">
                        <h5><i class="{{ $data['method_1_icon'] ?? 'fab fa-whatsapp' }}"></i> WhatsApp Directo</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['method_1_icon'] ?? 'fab fa-whatsapp' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="method_1_icon" value="{{ $data['method_1_icon'] ?? 'fab fa-whatsapp' }}" placeholder="fab fa-whatsapp">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="method_1_title" value="{{ $data['method_1_title'] ?? 'WhatsApp Directo' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Número de WhatsApp</label>
                                    <input type="text" class="form-control" name="method_1_number" value="{{ $data['method_1_number'] ?? '+57 312 370 8407' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="method_1_description" rows="4" required>{{ $data['method_1_description'] ?? 'La forma más rápida de contactarnos. Te respondemos en menos de 30 minutos durante horario laboral. Perfecto para consultas rápidas y coordinación de reuniones.' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mensaje de WhatsApp</label>
                                    <textarea class="form-control" name="method_1_message" rows="2" required>{{ $data['method_1_message'] ?? 'Hola, me interesa conocer más sobre sus servicios de desarrollo web' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Call Method -->
                    <div class="contact-method-item">
                        <h5><i class="{{ $data['method_2_icon'] ?? 'fas fa-video' }}"></i> Videollamada</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['method_2_icon'] ?? 'fas fa-video' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="method_2_icon" value="{{ $data['method_2_icon'] ?? 'fas fa-video' }}" placeholder="fas fa-video">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="method_2_title" value="{{ $data['method_2_title'] ?? 'Videollamada de Consultoría' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Texto del Botón</label>
                                    <input type="text" class="form-control" name="method_2_button" value="{{ $data['method_2_button'] ?? 'Agendar Consultoría' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="method_2_description" rows="4" required>{{ $data['method_2_description'] ?? 'Agenda una videollamada gratuita de 30 minutos para analizar tu proyecto en detalle. Revisamos tus necesidades y te damos una propuesta inicial sin compromiso.' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Mensaje para Agendar</label>
                                    <textarea class="form-control" name="method_2_message" rows="2" required>{{ $data['method_2_message'] ?? 'Hola, me gustaría agendar una videollamada de consultoría gratuita' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Method -->
                    <div class="contact-method-item">
                        <h5><i class="{{ $data['method_3_icon'] ?? 'fas fa-envelope' }}"></i> Email</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['method_3_icon'] ?? 'fas fa-envelope' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="method_3_icon" value="{{ $data['method_3_icon'] ?? 'fas fa-envelope' }}" placeholder="fas fa-envelope">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="method_3_title" value="{{ $data['method_3_title'] ?? 'Email Profesional' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="method_3_email" value="{{ $data['method_3_email'] ?? 'contacto@mytechsolutions.com' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="method_3_description" rows="4" required>{{ $data['method_3_description'] ?? 'Para consultas detalladas, envío de documentos o comunicación formal. Te respondemos en máximo 24 horas con una propuesta personalizada.' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="tab-pane fade" id="form" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-envelope"></i>
                        Sección del Formulario
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="form_title" value="{{ $data['form_title'] ?? 'Cuéntanos sobre tu Proyecto' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="form_description" rows="3" required>{{ $data['form_description'] ?? 'Completa el formulario y te contactaremos en menos de 24 horas con una propuesta personalizada' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título del Formulario</label>
                                <input type="text" class="form-control" name="form_header_title" value="{{ $data['form_header_title'] ?? 'Formulario de Proyecto' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Descripción del Formulario</label>
                                <textarea class="form-control" name="form_header_description" rows="3" required>{{ $data['form_header_description'] ?? 'Todos los campos son importantes para crear la mejor propuesta para ti' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Texto del Botón Enviar</label>
                                <input type="text" class="form-control" name="form_submit_text" value="{{ $data['form_submit_text'] ?? 'Enviar Proyecto y Recibir Propuesta' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Email de Destino</label>
                                <input type="email" class="form-control" name="form_email_to" value="{{ $data['form_email_to'] ?? 'contacto@mytechsolutions.com' }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="tab-pane fade" id="map" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-map"></i>
                        Mapa e Información de Contacto
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="map_title" value="{{ $data['map_title'] ?? 'Nuestra Oficina en Bogotá' }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="map_description" rows="4" required>{{ $data['map_description'] ?? 'Trabajamos desde el corazón de Bogotá, Colombia, pero nuestro alcance es global. Desarrollamos proyectos para clientes en múltiples países, combinando la calidez del servicio colombiano con estándares internacionales.' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">URL del Mapa (Google Maps Embed)</label>
                                <textarea class="form-control" name="map_url" rows="4" required>{{ $data['map_url'] ?? 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3249.012195470509!2d-74.13449935362908!3d4.600360674860746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1757988380012!5m2!1ses-419!2sco' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Información de Contacto (4 elementos)
                    </h4>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Info 1 - Ubicación -->
                            <div class="info-item">
                                <h5><i class="{{ $data['info_1_icon'] ?? 'fas fa-map-marker-alt' }}"></i> Información 1</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono</label>
                                    <input type="text" class="form-control" name="info_1_icon" value="{{ $data['info_1_icon'] ?? 'fas fa-map-marker-alt' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="info_1_title" value="{{ $data['info_1_title'] ?? 'Ubicación' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Texto</label>
                                    <input type="text" class="form-control" name="info_1_text" value="{{ $data['info_1_text'] ?? 'Bogotá, Colombia' }}" required>
                                </div>
                            </div>

                            <!-- Info 2 - Horario -->
                            <div class="info-item">
                                <h5><i class="{{ $data['info_2_icon'] ?? 'fas fa-clock' }}"></i> Información 2</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono</label>
                                    <input type="text" class="form-control" name="info_2_icon" value="{{ $data['info_2_icon'] ?? 'fas fa-clock' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="info_2_title" value="{{ $data['info_2_title'] ?? 'Horario de Atención' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Texto</label>
                                    <input type="text" class="form-control" name="info_2_text" value="{{ $data['info_2_text'] ?? 'Lunes a Viernes: 8:00 AM - 6:00 PM (COT)' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Info 3 - Alcance -->
                            <div class="info-item">
                                <h5><i class="{{ $data['info_3_icon'] ?? 'fas fa-globe' }}"></i> Información 3</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono</label>
                                    <input type="text" class="form-control" name="info_3_icon" value="{{ $data['info_3_icon'] ?? 'fas fa-globe' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="info_3_title" value="{{ $data['info_3_title'] ?? 'Alcance' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Texto</label>
                                    <input type="text" class="form-control" name="info_3_text" value="{{ $data['info_3_text'] ?? 'Proyectos en América y Europa' }}" required>
                                </div>
                            </div>

                            <!-- Info 4 - Soporte -->
                            <div class="info-item">
                                <h5><i class="{{ $data['info_4_icon'] ?? 'fas fa-headset' }}"></i> Información 4</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono</label>
                                    <input type="text" class="form-control" name="info_4_icon" value="{{ $data['info_4_icon'] ?? 'fas fa-headset' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="info_4_title" value="{{ $data['info_4_title'] ?? 'Soporte' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Texto</label>
                                    <input type="text" class="form-control" name="info_4_text" value="{{ $data['info_4_text'] ?? 'Disponible 24/7 para proyectos activos' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de Guardar -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save me-2"></i>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
    // Actualizar preview de iconos cuando cambie el input
    document.querySelectorAll('input[name*="_icon"]').forEach(input => {
        input.addEventListener('input', function() {
            const iconPreview = this.closest('.contact-method-item, .info-item').querySelector('.icon-preview i') || 
                               this.parentElement.querySelector('.icon-preview i');
            if (iconPreview && this.value.trim()) {
                iconPreview.className = this.value.trim();
            }
        });
    });
</script>
@endsection