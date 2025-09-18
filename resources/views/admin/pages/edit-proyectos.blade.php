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

    .feature-item {
        background: rgba(0, 123, 255, 0.02);
        border: 1px solid rgba(0, 123, 255, 0.1);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
    }

    .feature-item h5 {
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

    .emoji-preview {
        font-size: 2rem;
        margin-right: 1rem;
        width: 50px;
        text-align: center;
    }

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        color: #055160;
        border-left: 4px solid #0dcaf0;
    }
</style>

<div class="edit-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Editar Página de Proyectos
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
        Esta página permite editar solo el contenido del banner principal. Las tarjetas de proyectos individuales se mantienen como están programadas.
    </div>

    <form action="{{ route('admin.pages.proyectos.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="proyectosTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button">
                    <i class="fas fa-home me-2"></i>Sección Hero
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="showcase-tab" data-bs-toggle="tab" data-bs-target="#showcase" type="button">
                    <i class="fas fa-star me-2"></i>Showcase Intro
                </button>
            </li>
        </ul>

        <div class="tab-content" id="proyectosTabContent">
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
                                <input type="text" class="form-control" name="hero_badge" value="{{ $data['hero_badge'] ?? 'Proyectos Destacados' }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Título Principal</label>
                                <input type="text" class="form-control" name="hero_title" value="{{ $data['hero_title'] ?? 'Transformando Ideas en Realidad Digital' }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Descripción Principal</label>
                                <textarea class="form-control" name="hero_description" rows="6" required>{{ $data['hero_description'] ?? 'Cada proyecto que desarrollo está diseñado para impulsar el crecimiento de tu negocio. Desde plataformas de viajes hasta sistemas administrativos, mis soluciones están operando exitosamente en múltiples países.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-4">
                        <i class="fas fa-star me-2"></i>
                        Features del Hero (3 elementos)
                    </h4>

                    <!-- Feature 1 -->
                    <div class="feature-item">
                        <h5><span class="emoji-preview">{{ $data['hero_feature_1_icon'] ?? '🌎' }}</span> Feature 1</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Emoji/Ícono</label>
                                    <input type="text" class="form-control" name="hero_feature_1_icon" value="{{ $data['hero_feature_1_icon'] ?? '🌎' }}" placeholder="🌎">
                                    <small class="text-muted">Puedes usar emojis o clases de FontAwesome</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="hero_feature_1_title" value="{{ $data['hero_feature_1_title'] ?? 'Alcance Internacional' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <input type="text" class="form-control" name="hero_feature_1_description" value="{{ $data['hero_feature_1_description'] ?? 'Proyectos exitosos en América y Europa' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-item">
                        <h5><span class="emoji-preview">{{ $data['hero_feature_2_icon'] ?? '🚀' }}</span> Feature 2</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Emoji/Ícono</label>
                                    <input type="text" class="form-control" name="hero_feature_2_icon" value="{{ $data['hero_feature_2_icon'] ?? '🚀' }}" placeholder="🚀">
                                    <small class="text-muted">Puedes usar emojis o clases de FontAwesome</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="hero_feature_2_title" value="{{ $data['hero_feature_2_title'] ?? 'Tecnología Avanzada' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <input type="text" class="form-control" name="hero_feature_2_description" value="{{ $data['hero_feature_2_description'] ?? 'Laravel, Vue.js, React y las últimas tendencias' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-item">
                        <h5><span class="emoji-preview">{{ $data['hero_feature_3_icon'] ?? '💎' }}</span> Feature 3</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Emoji/Ícono</label>
                                    <input type="text" class="form-control" name="hero_feature_3_icon" value="{{ $data['hero_feature_3_icon'] ?? '💎' }}" placeholder="💎">
                                    <small class="text-muted">Puedes usar emojis o clases de FontAwesome</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Título</label>
                                    <input type="text" class="form-control" name="hero_feature_3_title" value="{{ $data['hero_feature_3_title'] ?? 'Calidad Premium' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Descripción</label>
                                    <input type="text" class="form-control" name="hero_feature_3_description" value="{{ $data['hero_feature_3_description'] ?? 'Cada proyecto único y a la medida' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Showcase Section -->
            <div class="tab-pane fade" id="showcase" role="tabpanel">
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-star"></i>
                        Sección Showcase Intro
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Título de la Sección</label>
                                <input type="text" class="form-control" name="showcase_title" value="{{ $data['showcase_title'] ?? 'Portfolio de Proyectos Exitosos' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Descripción de la Sección</label>
                                <textarea class="form-control" name="showcase_description" rows="3" required>{{ $data['showcase_description'] ?? 'Cada proyecto cuenta una historia de transformación digital y crecimiento empresarial' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Texto del Scroll Indicator</label>
                                <input type="text" class="form-control" name="scroll_indicator_text" value="{{ $data['scroll_indicator_text'] ?? 'Descubre mis proyectos' }}" required>
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
    // Actualizar preview de emojis cuando cambie el input
    document.querySelectorAll('input[name*="_icon"]').forEach(input => {
        input.addEventListener('input', function() {
            const emojiPreview = this.closest('.feature-item').querySelector('.emoji-preview');
            if (emojiPreview && this.value.trim()) {
                emojiPreview.textContent = this.value.trim();
            }
        });
    });
</script>
@endsection