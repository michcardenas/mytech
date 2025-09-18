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

    .service-item, .tech-item, .step-item {
        background: rgba(0, 123, 255, 0.02);
        border: 1px solid rgba(0, 123, 255, 0.1);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .service-item h5, .tech-item h5, .step-item h5 {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .feature-list {
        margin-top: 1rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .btn-add-feature {
        background: #28a745;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.75rem;
        cursor: pointer;
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

    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: var(--transition);
    }

    .file-upload-area:hover {
        border-color: var(--primary-blue);
        background: rgba(0, 123, 255, 0.02);
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
</style>

<div class="edit-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Editar Página de Servicios
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

    <form action="{{ route('admin.pages.servicios.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="serviciosTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button">
                    <i class="fas fa-home me-2"></i>Sección Hero
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="servicios-tab" data-bs-toggle="tab" data-bs-target="#servicios" type="button">
                    <i class="fas fa-cogs me-2"></i>Servicios
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tecnologias-tab" data-bs-toggle="tab" data-bs-target="#tecnologias" type="button">
                    <i class="fas fa-laptop-code me-2"></i>Tecnologías
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="proceso-tab" data-bs-toggle="tab" data-bs-target="#proceso" type="button">
                    <i class="fas fa-tasks me-2"></i>Proceso
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cta-tab" data-bs-toggle="tab" data-bs-target="#cta" type="button">
                    <i class="fas fa-bullhorn me-2"></i>Call to Action
                </button>
            </li>
        </ul>

        <div class="tab-content" id="serviciosTabContent">
            <!-- Hero Section -->
            <div class="tab-pane fade show active" id="hero" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-home"></i>
                        Sección Hero
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título Principal</label>
                                <input type="text" class="form-control" name="hero_title" value="{{ $data['hero_title'] ?? 'Nuestros Servicios' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" name="hero_description" rows="4" required>{{ $data['hero_description'] ?? 'Creamos soluciones digitales a la medida que impulsan el crecimiento de tu empresa. No usamos plantillas, cada proyecto es único y está diseñado específicamente para tus necesidades.' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Video Hero</label>
                                <div class="file-upload-area">
                                    <i class="fas fa-video fa-3x mb-3" style="color: #ccc;"></i>
                                    <p>Arrastra tu video aquí o haz clic para seleccionar</p>
                                    <input type="file" class="form-control" name="hero_video" accept="video/*">
                                    <small class="text-muted">Formatos soportados: MP4, WebM. Tamaño máximo: 50MB</small>
                                </div>
                                
                                <div class="mt-3">
                                    <label class="form-label">URL del Video Actual</label>
                                    <input type="text" class="form-control" name="hero_video_url" value="{{ $data['hero_video_url'] ?? 'videos/hero-video.mp4' }}" placeholder="Ruta del video actual">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servicios Section -->
            <div class="tab-pane fade" id="servicios" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-cogs"></i>
                        Sección de Servicios
                    </h3>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="servicios_title" value="{{ $data['servicios_title'] ?? '¿Qué Desarrollamos Para Ti?' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="servicios_description" rows="3" required>{{ $data['servicios_description'] ?? 'Cada proyecto que creamos está diseñado para ayudar a tu empresa a vender más, organizarse mejor y ofrecer un servicio excepcional.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 1 -->
                    <div class="service-item">
                        <h5><i class="{{ $data['servicio_1_icon'] ?? 'fas fa-store' }}"></i> Servicio 1</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['servicio_1_icon'] ?? 'fas fa-store' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="servicio_1_icon" value="{{ $data['servicio_1_icon'] ?? 'fas fa-store' }}" placeholder="ej: fas fa-store">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="servicio_1_title" value="{{ $data['servicio_1_title'] ?? 'Marketplaces Personalizados' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="servicio_1_description" rows="3" required>{{ $data['servicio_1_description'] ?? 'Plataformas de comercio como MercadoLibre, pero adaptadas a tu nicho específico de mercado.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="feature-list">
                            <label class="form-label">Características:</label>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_1_feature_1" value="{{ $data['servicio_1_feature_1'] ?? 'Sistema de vendedores múltiples' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_1_feature_2" value="{{ $data['servicio_1_feature_2'] ?? 'Pagos integrados y seguros' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_1_feature_3" value="{{ $data['servicio_1_feature_3'] ?? 'Panel administrativo completo' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_1_feature_4" value="{{ $data['servicio_1_feature_4'] ?? 'App móvil nativa opcional' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="service-item">
                        <h5><i class="{{ $data['servicio_2_icon'] ?? 'fas fa-calendar-check' }}"></i> Servicio 2</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['servicio_2_icon'] ?? 'fas fa-calendar-check' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="servicio_2_icon" value="{{ $data['servicio_2_icon'] ?? 'fas fa-calendar-check' }}" placeholder="ej: fas fa-calendar-check">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="servicio_2_title" value="{{ $data['servicio_2_title'] ?? 'Apps de Reservas y Citas' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="servicio_2_description" rows="3" required>{{ $data['servicio_2_description'] ?? 'Sistemas inteligentes para gestionar citas, reservas y horarios de manera automatizada.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="feature-list">
                            <label class="form-label">Características:</label>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_2_feature_1" value="{{ $data['servicio_2_feature_1'] ?? 'Reservas en tiempo real' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_2_feature_2" value="{{ $data['servicio_2_feature_2'] ?? 'Recordatorios automáticos' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_2_feature_3" value="{{ $data['servicio_2_feature_3'] ?? 'Gestión de disponibilidad' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_2_feature_4" value="{{ $data['servicio_2_feature_4'] ?? 'Integración con calendarios' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 3 -->
                    <div class="service-item">
                        <h5><i class="{{ $data['servicio_3_icon'] ?? 'fas fa-utensils' }}"></i> Servicio 3</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['servicio_3_icon'] ?? 'fas fa-utensils' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="servicio_3_icon" value="{{ $data['servicio_3_icon'] ?? 'fas fa-utensils' }}" placeholder="ej: fas fa-utensils">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="servicio_3_title" value="{{ $data['servicio_3_title'] ?? 'Plataformas de Restaurantes' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="servicio_3_description" rows="3" required>{{ $data['servicio_3_description'] ?? 'Menús digitales interactivos con sistema de pedidos y gestión completa del restaurante.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="feature-list">
                            <label class="form-label">Características:</label>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_3_feature_1" value="{{ $data['servicio_3_feature_1'] ?? 'Menú digital con QR' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_3_feature_2" value="{{ $data['servicio_3_feature_2'] ?? 'Pedidos online y delivery' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_3_feature_3" value="{{ $data['servicio_3_feature_3'] ?? 'Gestión de inventario' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_3_feature_4" value="{{ $data['servicio_3_feature_4'] ?? 'Reportes de ventas' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 4 -->
                    <div class="service-item">
                        <h5><i class="{{ $data['servicio_4_icon'] ?? 'fas fa-building' }}"></i> Servicio 4</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['servicio_4_icon'] ?? 'fas fa-building' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="servicio_4_icon" value="{{ $data['servicio_4_icon'] ?? 'fas fa-building' }}" placeholder="ej: fas fa-building">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="servicio_4_title" value="{{ $data['servicio_4_title'] ?? 'Sistemas Administrativos' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="servicio_4_description" rows="3" required>{{ $data['servicio_4_description'] ?? 'Plataformas para condominios, negocios y consultoras que automatizan procesos operativos.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="feature-list">
                            <label class="form-label">Características:</label>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_4_feature_1" value="{{ $data['servicio_4_feature_1'] ?? 'Gestión de clientes/residentes' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_4_feature_2" value="{{ $data['servicio_4_feature_2'] ?? 'Control de pagos y facturación' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_4_feature_3" value="{{ $data['servicio_4_feature_3'] ?? 'Reportes automatizados' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_4_feature_4" value="{{ $data['servicio_4_feature_4'] ?? 'Comunicación interna' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 5 -->
                    <div class="service-item">
                        <h5><i class="{{ $data['servicio_5_icon'] ?? 'fas fa-globe' }}"></i> Servicio 5</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['servicio_5_icon'] ?? 'fas fa-globe' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="servicio_5_icon" value="{{ $data['servicio_5_icon'] ?? 'fas fa-globe' }}" placeholder="ej: fas fa-globe">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="servicio_5_title" value="{{ $data['servicio_5_title'] ?? 'Páginas Web Profesionales' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="servicio_5_description" rows="3" required>{{ $data['servicio_5_description'] ?? 'Sitios web con panel de control y optimizados para aparecer en los primeros lugares de Google.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="feature-list">
                            <label class="form-label">Características:</label>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_5_feature_1" value="{{ $data['servicio_5_feature_1'] ?? 'Diseño responsive y moderno' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_5_feature_2" value="{{ $data['servicio_5_feature_2'] ?? 'SEO optimizado para Google' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_5_feature_3" value="{{ $data['servicio_5_feature_3'] ?? 'Panel de administración' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_5_feature_4" value="{{ $data['servicio_5_feature_4'] ?? 'Velocidad de carga optimizada' }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 6 -->
                    <div class="service-item">
                        <h5><i class="{{ $data['servicio_6_icon'] ?? 'fas fa-cogs' }}"></i> Servicio 6</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-preview">
                                            <i class="{{ $data['servicio_6_icon'] ?? 'fas fa-cogs' }}"></i>
                                        </div>
                                        <input type="text" class="form-control" name="servicio_6_icon" value="{{ $data['servicio_6_icon'] ?? 'fas fa-cogs' }}" placeholder="ej: fas fa-cogs">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="servicio_6_title" value="{{ $data['servicio_6_title'] ?? 'Aplicaciones Web Personalizadas' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" name="servicio_6_description" rows="3" required>{{ $data['servicio_6_description'] ?? 'Sistemas web complejos y especializados que automatizan procesos específicos de tu industria.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="feature-list">
                            <label class="form-label">Características:</label>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_6_feature_1" value="{{ $data['servicio_6_feature_1'] ?? 'CRM y ERP personalizados' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_6_feature_2" value="{{ $data['servicio_6_feature_2'] ?? 'Plataformas de e-learning' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_6_feature_3" value="{{ $data['servicio_6_feature_3'] ?? 'Sistemas de inventario' }}" required>
                            </div>
                            <div class="feature-item">
                                <input type="text" class="form-control" name="servicio_6_feature_4" value="{{ $data['servicio_6_feature_4'] ?? 'Dashboards y analytics' }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tecnologías Section -->
            <div class="tab-pane fade" id="tecnologias" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-laptop-code"></i>
                        Sección de Tecnologías
                    </h3>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="tecnologias_title" value="{{ $data['tecnologias_title'] ?? 'Tecnologías Modernas y Seguras' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="tecnologias_description" rows="3" required>{{ $data['tecnologias_description'] ?? 'Utilizamos las herramientas más avanzadas del mercado para garantizar que tu plataforma sea rápida, confiable, escalable y esté lista para crecer.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_1_icon'] ?? 'fab fa-laravel' }}"></i> Tecnología 1</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_1_icon" value="{{ $data['tech_1_icon'] ?? 'fab fa-laravel' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_1_name" value="{{ $data['tech_1_name'] ?? 'Laravel 12' }}" required>
                                </div>
                            </div>

                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_2_icon'] ?? 'fab fa-js-square' }}"></i> Tecnología 2</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_2_icon" value="{{ $data['tech_2_icon'] ?? 'fab fa-js-square' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_2_name" value="{{ $data['tech_2_name'] ?? 'JavaScript' }}" required>
                                </div>
                            </div>

                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_3_icon'] ?? 'fab fa-react' }}"></i> Tecnología 3</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_3_icon" value="{{ $data['tech_3_icon'] ?? 'fab fa-react' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_3_name" value="{{ $data['tech_3_name'] ?? 'React' }}" required>
                                </div>
                            </div>

                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_4_icon'] ?? 'fab fa-bootstrap' }}"></i> Tecnología 4</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_4_icon" value="{{ $data['tech_4_icon'] ?? 'fab fa-bootstrap' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_4_name" value="{{ $data['tech_4_name'] ?? 'Bootstrap' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_5_icon'] ?? 'fas fa-database' }}"></i> Tecnología 5</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_5_icon" value="{{ $data['tech_5_icon'] ?? 'fas fa-database' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_5_name" value="{{ $data['tech_5_name'] ?? 'MySQL' }}" required>
                                </div>
                            </div>

                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_6_icon'] ?? 'fab fa-aws' }}"></i> Tecnología 6</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_6_icon" value="{{ $data['tech_6_icon'] ?? 'fab fa-aws' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_6_name" value="{{ $data['tech_6_name'] ?? 'AWS Cloud' }}" required>
                                </div>
                            </div>

                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_7_icon'] ?? 'fab fa-docker' }}"></i> Tecnología 7</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_7_icon" value="{{ $data['tech_7_icon'] ?? 'fab fa-docker' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_7_name" value="{{ $data['tech_7_name'] ?? 'Docker' }}" required>
                                </div>
                            </div>

                            <div class="tech-item">
                                <h5><i class="{{ $data['tech_8_icon'] ?? 'fas fa-shield-alt' }}"></i> Tecnología 8</h5>
                                <div class="form-group">
                                    <label class="form-label">Ícono (clase FontAwesome)</label>
                                    <input type="text" class="form-control" name="tech_8_icon" value="{{ $data['tech_8_icon'] ?? 'fas fa-shield-alt' }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="tech_8_name" value="{{ $data['tech_8_name'] ?? 'SSL Security' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Proceso Section -->
            <div class="tab-pane fade" id="proceso" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-tasks"></i>
                        Sección de Proceso de Trabajo
                    </h3>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="proceso_title" value="{{ $data['proceso_title'] ?? 'Nuestro Proceso de Trabajo' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="proceso_description" rows="3" required>{{ $data['proceso_description'] ?? 'Te acompañamos desde la idea inicial hasta el lanzamiento y soporte continuo de tu plataforma.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 1 -->
                    <div class="step-item">
                        <h5><span class="badge bg-primary">1</span> Paso 1</h5>
                        <div class="form-group">
                            <label class="form-label">Título del Paso</label>
                            <input type="text" class="form-control" name="proceso_step_1_title" value="{{ $data['proceso_step_1_title'] ?? 'Análisis y Consultoría' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="proceso_step_1_description" rows="2" required>{{ $data['proceso_step_1_description'] ?? 'Escuchamos tu idea, analizamos tus necesidades y definimos la mejor estrategia para tu proyecto.' }}</textarea>
                        </div>
                    </div>

                    <!-- Paso 2 -->
                    <div class="step-item">
                        <h5><span class="badge bg-primary">2</span> Paso 2</h5>
                        <div class="form-group">
                            <label class="form-label">Título del Paso</label>
                            <input type="text" class="form-control" name="proceso_step_2_title" value="{{ $data['proceso_step_2_title'] ?? 'Diseño y Planificación' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="proceso_step_2_description" rows="2" required>{{ $data['proceso_step_2_description'] ?? 'Creamos prototipos, wireframes y definimos la arquitectura técnica de tu plataforma.' }}</textarea>
                        </div>
                    </div>

                    <!-- Paso 3 -->
                    <div class="step-item">
                        <h5><span class="badge bg-primary">3</span> Paso 3</h5>
                        <div class="form-group">
                            <label class="form-label">Título del Paso</label>
                            <input type="text" class="form-control" name="proceso_step_3_title" value="{{ $data['proceso_step_3_title'] ?? 'Desarrollo a Medida' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="proceso_step_3_description" rows="2" required>{{ $data['proceso_step_3_description'] ?? 'Programamos tu solución utilizando las mejores prácticas y tecnologías más avanzadas.' }}</textarea>
                        </div>
                    </div>

                    <!-- Paso 4 -->
                    <div class="step-item">
                        <h5><span class="badge bg-primary">4</span> Paso 4</h5>
                        <div class="form-group">
                            <label class="form-label">Título del Paso</label>
                            <input type="text" class="form-control" name="proceso_step_4_title" value="{{ $data['proceso_step_4_title'] ?? 'Pruebas y Lanzamiento' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="proceso_step_4_description" rows="2" required>{{ $data['proceso_step_4_description'] ?? 'Realizamos pruebas exhaustivas y te acompañamos en el lanzamiento de tu plataforma.' }}</textarea>
                        </div>
                    </div>

                    <!-- Paso 5 -->
                    <div class="step-item">
                        <h5><span class="badge bg-primary">5</span> Paso 5</h5>
                        <div class="form-group">
                            <label class="form-label">Título del Paso</label>
                            <input type="text" class="form-control" name="proceso_step_5_title" value="{{ $data['proceso_step_5_title'] ?? 'Soporte Continuo' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="proceso_step_5_description" rows="2" required>{{ $data['proceso_step_5_description'] ?? 'Brindamos mantenimiento, actualizaciones y soporte técnico para el crecimiento de tu negocio.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Section -->
            <div class="tab-pane fade" id="cta" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-bullhorn"></i>
                        Sección Call to Action
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título del CTA</label>
                                <input type="text" class="form-control" name="cta_title" value="{{ $data['cta_title'] ?? '¿Listo para Digitalizar tu Idea?' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Descripción del CTA</label>
                                <textarea class="form-control" name="cta_description" rows="3" required>{{ $data['cta_description'] ?? 'Conversemos sobre tu proyecto y descubre cómo podemos ayudarte a crear la solución perfecta para tu negocio.' }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Texto del Botón</label>
                                <input type="text" class="form-control" name="cta_button_text" value="{{ $data['cta_button_text'] ?? 'Hablemos por WhatsApp' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Número de WhatsApp</label>
                                <input type="text" class="form-control" name="whatsapp_number" value="{{ $data['whatsapp_number'] ?? '573123708407' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Mensaje de WhatsApp</label>
                                <textarea class="form-control" name="whatsapp_message" rows="2" required>{{ $data['whatsapp_message'] ?? 'Hola, me interesa conocer más sobre sus servicios de desarrollo web' }}</textarea>
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
            const iconPreview = this.parentElement.querySelector('.icon-preview i');
            if (iconPreview && this.value.trim()) {
                iconPreview.className = this.value.trim();
            }
        });
    });
</script>
@endsection