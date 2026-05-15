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

    .btn-secondary {
        background: #6c757d;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .form-container {
        background: var(--white);
        border: 2px solid rgba(0, 123, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: var(--shadow-soft);
    }

    .form-section {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        border-left: 4px solid var(--primary-blue);
    }

    /* Hero image uploader */
    .hero-image-uploader {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 1.25rem;
        align-items: start;
    }
    .hero-image-preview {
        width: 100%;
        height: 180px;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        background: #f8fafc;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: 0.85rem; text-align: center; padding: 0.75rem;
        overflow: hidden;
    }
    .hero-image-preview.has-image { border-style: solid; border-color: var(--primary-blue); padding: 0; }
    .hero-image-preview img, .hero-image-preview video { width: 100%; height: 100%; object-fit: cover; display: block; }

    .file-drop {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        background: #fafbfc;
        cursor: pointer;
        transition: all 0.25s ease;
        display: block;
    }
    .file-drop:hover { border-color: var(--primary-blue); background: #eef5ff; }
    .file-drop i { font-size: 1.6rem; color: var(--primary-blue); display: block; margin-bottom: 0.4rem; }
    .file-drop .file-drop-title { font-weight: 700; color: var(--dark-text); font-size: 0.92rem; }
    .file-drop .file-drop-hint { color: #666; font-size: 0.78rem; margin-top: 0.25rem; }
    .file-drop input[type="file"] { display: none; }
    .file-drop .file-selected { display: none; color: #059669; font-weight: 600; font-size: 0.82rem; margin-top: 0.5rem; }
    .file-drop.has-file .file-selected { display: block; }

    .remove-image-row {
        margin-top: 0.75rem;
        padding: 0.6rem 0.85rem;
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #c53030;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .remove-image-row input { accent-color: #c53030; }

    .section-title {
        color: var(--dark-text);
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: var(--dark-text);
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.65rem 0.9rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 0.92rem;
        transition: var(--transition);
        background: var(--white);
    }

    .form-control:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .form-control-lg {
        min-height: 120px;
        resize: vertical;
    }

    .btn-primary {
        background: var(--gradient-blue);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: var(--transition);
        box-shadow: var(--shadow-soft);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .preview-section {
        background: rgba(0, 123, 255, 0.05);
        border: 2px dashed rgba(0, 123, 255, 0.2);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        margin-top: 1rem;
    }

    .preview-text {
        color: #666;
        font-style: italic;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="edit-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-home"></i>
            Editar Página de Inicio
        </h1>
        <a href="{{ route('admin.pages.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-container">
            <!-- Sección Hero -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-star"></i>
                    Sección Principal (Hero)
                </h3>

                @php
                    $homeContent = json_decode($page->content ?? '{}', true);
                @endphp

                <div class="form-row">
                    <div class="form-group">
                        <label for="hero_badge" class="form-label">Etiqueta Principal</label>
                        <input type="text" class="form-control" id="hero_badge" name="hero_badge"
                               value="{{ old('hero_badge', $homeContent['hero_badge'] ?? '💻 Tu Página Web Profesional') }}"
                               placeholder="💻 Tu Página Web Profesional">
                        <small class="form-text">Texto que aparece en la etiqueta azul</small>
                        @error('hero_badge')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="hero_title" class="form-label">Título Principal</label>
                        <input type="text" class="form-control" id="hero_title" name="hero_title"
                               value="{{ old('hero_title', $homeContent['hero_title'] ?? 'Lleva tu negocio al mundo digital') }}"
                               placeholder="Lleva tu negocio al mundo digital">
                        @error('hero_title')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="hero_description" class="form-label">Descripción Principal</label>
                    <textarea class="form-control form-control-lg" id="hero_description" name="hero_description"
                              placeholder="Descripción que explica tus servicios">{{ old('hero_description', $homeContent['hero_description'] ?? 'Creo páginas web que te ayudan a vender más, atraer nuevos clientes y hacer crecer tu negocio. Sin complicaciones técnicas.') }}</textarea>
                    @error('hero_description')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hero_button_text" class="form-label">Texto del Botón Principal</label>
                    <input type="text" class="form-control" id="hero_button_text" name="hero_button_text"
                           value="{{ old('hero_button_text', $homeContent['hero_button_text'] ?? 'Quiero mi página web') }}"
                           placeholder="Quiero mi página web">
                    @error('hero_button_text')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Media del Hero (Imagen o Video) -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-photo-video"></i>
                    Imagen o video del Hero
                </h3>
                <p class="form-text" style="margin: -0.5rem 0 1rem 0;">
                    Se muestra en la pantalla del laptop, la primera impresión de tu página. Puedes subir una imagen (screenshot de tu web, por ejemplo) o un video corto en loop.
                </p>

                @php
                    $heroMedia = $homeContent['hero_media'] ?? ($homeContent['hero_image'] ?? null);
                    $heroExt = $heroMedia ? strtolower(pathinfo($heroMedia, PATHINFO_EXTENSION)) : null;
                    $heroIsVideo = in_array($heroExt, ['mp4', 'webm', 'mov']);
                @endphp

                <div class="hero-image-uploader">
                    <div class="hero-image-preview {{ $heroMedia ? 'has-image' : '' }}" id="hero-preview">
                        @if($heroMedia && $heroIsVideo)
                            <video id="hero-preview-video" src="{{ asset('storage/' . $heroMedia) }}" autoplay muted loop playsinline
                                   style="width:100%; height:100%; object-fit:cover;"></video>
                        @elseif($heroMedia)
                            <img id="hero-preview-img" src="{{ asset('storage/' . $heroMedia) }}" alt="Hero"
                                 style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <span>Sin imagen ni video</span>
                        @endif
                    </div>

                    <div>
                        <label class="file-drop" id="hero-drop" for="hero_media">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div class="file-drop-title">{{ $heroMedia ? 'Cambiar archivo' : 'Subir imagen o video' }}</div>
                            <div class="file-drop-hint">Imagen: JPG/PNG/WEBP · Video: MP4/WEBM/MOV · máx 20MB</div>
                            <div class="file-selected" id="hero-file-name"></div>
                            <input type="file" id="hero_media" name="hero_media" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime">
                        </label>
                        @error('hero_media')
                            <div class="form-text text-danger" style="margin-top:0.5rem;">{{ $message }}</div>
                        @enderror

                        <div class="form-text" style="margin-top:0.5rem;">
                            <i class="fas fa-lightbulb" style="color:#f59e0b;"></i>
                            Tip: un video corto (5-10s) en loop se ve muy moderno y atrae la atención del cliente.
                        </div>

                        @if($heroMedia)
                            <label class="remove-image-row">
                                <input type="checkbox" name="remove_hero_media" value="1">
                                <i class="fas fa-trash-alt"></i>
                                Quitar archivo actual al guardar
                            </label>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Beneficios -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-check-circle"></i>
                    Beneficios
                </h3>

                <div class="form-group">
                    <label for="benefit_1" class="form-label">Beneficio 1</label>
                    <input type="text" class="form-control" id="benefit_1" name="benefit_1"
                           value="{{ old('benefit_1', $homeContent['benefit_1'] ?? 'Más clientes te encuentran en Google') }}"
                           placeholder="Beneficio 1">
                    @error('benefit_1')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="benefit_2" class="form-label">Beneficio 2</label>
                    <input type="text" class="form-control" id="benefit_2" name="benefit_2"
                           value="{{ old('benefit_2', $homeContent['benefit_2'] ?? 'Vendes las 24 horas del día') }}"
                           placeholder="Beneficio 2">
                    @error('benefit_2')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="benefit_3" class="form-label">Beneficio 3</label>
                    <input type="text" class="form-control" id="benefit_3" name="benefit_3"
                           value="{{ old('benefit_3', $homeContent['benefit_3'] ?? 'Te ves profesional ante la competencia') }}"
                           placeholder="Beneficio 3">
                    @error('benefit_3')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Hero · Pruebas Sociales (Stats) -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-chart-line"></i>
                    Hero · Pruebas Sociales
                </h3>
                <p class="text-muted mb-3" style="font-size: 0.85rem;">
                    Fila de 3 cifras que aparece debajo de los botones del hero. Mantén los valores cortos (números o "+N").
                </p>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hero_stat_1_value" class="form-label">Stat 1 · Valor</label>
                        <input type="text" class="form-control" id="hero_stat_1_value" name="hero_stat_1_value"
                               value="{{ old('hero_stat_1_value', $homeContent['hero_stat_1_value'] ?? '28') }}"
                               placeholder="28" maxlength="16">
                        @error('hero_stat_1_value')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="hero_stat_1_label" class="form-label">Stat 1 · Etiqueta</label>
                        <input type="text" class="form-control" id="hero_stat_1_label" name="hero_stat_1_label"
                               value="{{ old('hero_stat_1_label', $homeContent['hero_stat_1_label'] ?? 'Proyectos en producción') }}"
                               placeholder="Proyectos en producción" maxlength="64">
                        @error('hero_stat_1_label')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hero_stat_2_value" class="form-label">Stat 2 · Valor</label>
                        <input type="text" class="form-control" id="hero_stat_2_value" name="hero_stat_2_value"
                               value="{{ old('hero_stat_2_value', $homeContent['hero_stat_2_value'] ?? '11') }}"
                               placeholder="11" maxlength="16">
                        @error('hero_stat_2_value')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="hero_stat_2_label" class="form-label">Stat 2 · Etiqueta</label>
                        <input type="text" class="form-control" id="hero_stat_2_label" name="hero_stat_2_label"
                               value="{{ old('hero_stat_2_label', $homeContent['hero_stat_2_label'] ?? 'Países') }}"
                               placeholder="Países" maxlength="64">
                        @error('hero_stat_2_label')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="hero_stat_3_value" class="form-label">Stat 3 · Valor</label>
                        <input type="text" class="form-control" id="hero_stat_3_value" name="hero_stat_3_value"
                               value="{{ old('hero_stat_3_value', $homeContent['hero_stat_3_value'] ?? '+5') }}"
                               placeholder="+5" maxlength="16">
                        @error('hero_stat_3_value')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="hero_stat_3_label" class="form-label">Stat 3 · Etiqueta</label>
                        <input type="text" class="form-control" id="hero_stat_3_label" name="hero_stat_3_label"
                               value="{{ old('hero_stat_3_label', $homeContent['hero_stat_3_label'] ?? 'Años desarrollando') }}"
                               placeholder="Años desarrollando" maxlength="64">
                        @error('hero_stat_3_label')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sección de Clientes -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Sección de Clientes
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="clients_title" class="form-label">Título de Clientes</label>
                        <input type="text" class="form-control" id="clients_title" name="clients_title"
                               value="{{ old('clients_title', $homeContent['clients_title'] ?? 'Empresas que confían en mi trabajo') }}"
                               placeholder="Empresas que confían en mi trabajo">
                        @error('clients_title')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="clients_subtitle" class="form-label">Subtítulo de Clientes</label>
                        <input type="text" class="form-control" id="clients_subtitle" name="clients_subtitle"
                               value="{{ old('clients_subtitle', $homeContent['clients_subtitle'] ?? 'He desarrollado aplicaciones web exitosas para:') }}"
                               placeholder="He desarrollado aplicaciones web exitosas para:">
                        @error('clients_subtitle')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="clients_button_text" class="form-label">Texto del Botón "Ver Más"</label>
                    <input type="text" class="form-control" id="clients_button_text" name="clients_button_text"
                           value="{{ old('clients_button_text', $homeContent['clients_button_text'] ?? 'Conoce más de nuestro trabajo') }}"
                           placeholder="Conoce más de nuestro trabajo">
                    @error('clients_button_text')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Mockups y Elementos de Éxito -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-mobile-alt"></i>
                    Dispositivos y Badges de Éxito
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone_label" class="form-label">Etiqueta del Móvil</label>
                        <input type="text" class="form-control" id="phone_label" name="phone_label"
                               value="{{ old('phone_label', $homeContent['phone_label'] ?? 'Así se verá en móvil') }}"
                               placeholder="Así se verá en móvil">
                        @error('phone_label')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="laptop_label" class="form-label">Etiqueta de la Laptop</label>
                        <input type="text" class="form-control" id="laptop_label" name="laptop_label"
                               value="{{ old('laptop_label', $homeContent['laptop_label'] ?? 'Así se verá en computadora') }}"
                               placeholder="Así se verá en computadora">
                        @error('laptop_label')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="success_badge_1" class="form-label">Badge de Éxito 1</label>
                        <input type="text" class="form-control" id="success_badge_1" name="success_badge_1"
                               value="{{ old('success_badge_1', $homeContent['success_badge_1'] ?? 'Te encuentran fácil') }}"
                               placeholder="Te encuentran fácil">
                        @error('success_badge_1')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="success_badge_2" class="form-label">Badge de Éxito 2</label>
                        <input type="text" class="form-control" id="success_badge_2" name="success_badge_2"
                               value="{{ old('success_badge_2', $homeContent['success_badge_2'] ?? 'Más ventas 24/7 trabajando') }}"
                               placeholder="Más ventas 24/7 trabajando">
                        @error('success_badge_2')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="success_badge_3" class="form-label">Badge de Éxito 3</label>
                    <input type="text" class="form-control" id="success_badge_3" name="success_badge_3"
                           value="{{ old('success_badge_3', $homeContent['success_badge_3'] ?? 'Imagen confiable') }}"
                           placeholder="Imagen confiable">
                    @error('success_badge_3')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Sección SEO -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-search"></i>
                    Configuración SEO
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Título</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $page->seo->meta_title ?? '') }}"
                               placeholder="Título SEO para la página">
                        <small class="form-text">Recomendado: 50-60 caracteres</small>
                        @error('meta_title')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="focus_keyword" class="form-label">Palabra Clave Principal</label>
                        <input type="text" class="form-control" id="focus_keyword" name="focus_keyword"
                               value="{{ old('focus_keyword', $page->seo->focus_keyword ?? '') }}"
                               placeholder="Palabra clave principal">
                        @error('focus_keyword')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="meta_description" class="form-label">Meta Descripción</label>
                    <textarea class="form-control" id="meta_description" name="meta_description"
                              placeholder="Descripción que aparece en los resultados de búsqueda">{{ old('meta_description', $page->seo->meta_description ?? '') }}</textarea>
                    <small class="form-text">Recomendado: 150-160 caracteres</small>
                    @error('meta_description')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="meta_keywords" class="form-label">Palabras Clave</label>
                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                           value="{{ old('meta_keywords', $page->seo->meta_keywords ?? '') }}"
                           placeholder="palabra1, palabra2, palabra3">
                    <small class="form-text">Separa las palabras clave con comas</small>
                    @error('meta_keywords')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="og_title" class="form-label">Título para Redes Sociales</label>
                        <input type="text" class="form-control" id="og_title" name="og_title"
                               value="{{ old('og_title', $page->seo->og_title ?? '') }}"
                               placeholder="Título que aparece al compartir en redes sociales">
                        @error('og_title')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="canonical_url" class="form-label">URL Canónica</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                               value="{{ old('canonical_url', $page->seo->canonical_url ?? '') }}"
                               placeholder="https://example.com/pagina">
                        @error('canonical_url')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="og_description" class="form-label">Descripción para Redes Sociales</label>
                    <textarea class="form-control" id="og_description" name="og_description"
                              placeholder="Descripción que aparece al compartir en redes sociales">{{ old('og_description', $page->seo->og_description ?? '') }}</textarea>
                    @error('og_description')
                        <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="sitemap_priority" class="form-label">Prioridad en Sitemap</label>
                        <select class="form-control" id="sitemap_priority" name="sitemap_priority">
                            <option value="1.0" {{ old('sitemap_priority', $page->seo->sitemap_priority ?? '') == '1.0' ? 'selected' : '' }}>1.0 - Muy Alta</option>
                            <option value="0.8" {{ old('sitemap_priority', $page->seo->sitemap_priority ?? '') == '0.8' ? 'selected' : '' }}>0.8 - Alta</option>
                            <option value="0.5" {{ old('sitemap_priority', $page->seo->sitemap_priority ?? '') == '0.5' ? 'selected' : '' }}>0.5 - Media</option>
                            <option value="0.3" {{ old('sitemap_priority', $page->seo->sitemap_priority ?? '') == '0.3' ? 'selected' : '' }}>0.3 - Baja</option>
                        </select>
                        @error('sitemap_priority')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sitemap_changefreq" class="form-label">Frecuencia de Cambios</label>
                        <select class="form-control" id="sitemap_changefreq" name="sitemap_changefreq">
                            <option value="daily" {{ old('sitemap_changefreq', $page->seo->sitemap_changefreq ?? '') == 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="weekly" {{ old('sitemap_changefreq', $page->seo->sitemap_changefreq ?? '') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ old('sitemap_changefreq', $page->seo->sitemap_changefreq ?? '') == 'monthly' ? 'selected' : '' }}>Mensual</option>
                            <option value="yearly" {{ old('sitemap_changefreq', $page->seo->sitemap_changefreq ?? '') == 'yearly' ? 'selected' : '' }}>Anual</option>
                        </select>
                        @error('sitemap_changefreq')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" name="sitemap_include" value="1"
                                   {{ old('sitemap_include', $page->seo->sitemap_include ?? true) ? 'checked' : '' }}
                                   style="margin-right: 0.5rem;">
                            Incluir en Sitemap
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $page->seo->is_active ?? true) ? 'checked' : '' }}
                                   style="margin-right: 0.5rem;">
                            SEO Activo
                        </label>
                    </div>
                </div>
            </div>

            <!-- Vista Previa -->
            <div class="preview-section">
                <h4><i class="fas fa-eye me-2"></i>Vista Previa</h4>
                <p class="preview-text">Los cambios se reflejarán en la página de inicio después de guardar</p>
                <a href="{{ route('home') }}" target="_blank" class="btn-secondary">
                    <i class="fas fa-external-link-alt"></i>
                    Ver Página de Inicio
                </a>
            </div>

            <!-- Botón de Guardar -->
            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    (function () {
        const input = document.getElementById('hero_media');
        const drop = document.getElementById('hero-drop');
        const preview = document.getElementById('hero-preview');
        const fileName = document.getElementById('hero-file-name');
        const removeCheckbox = document.querySelector('input[name="remove_hero_media"]');
        if (!input) return;

        const renderPreview = (src, isVideo) => {
            preview.classList.add('has-image');
            preview.innerHTML = isVideo
                ? `<video src="${src}" autoplay muted loop playsinline style="width:100%; height:100%; object-fit:cover;"></video>`
                : `<img src="${src}" alt="Hero" style="width:100%; height:100%; object-fit:cover;">`;
        };

        input.addEventListener('change', function () {
            if (!input.files || !input.files[0]) return;
            const file = input.files[0];
            const isVideo = file.type.startsWith('video/');
            fileName.textContent = 'Seleccionado: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(1) + ' MB)';
            drop.classList.add('has-file');
            const reader = new FileReader();
            reader.onload = e => renderPreview(e.target.result, isVideo);
            reader.readAsDataURL(file);
            if (removeCheckbox) removeCheckbox.checked = false;
        });

        if (removeCheckbox) {
            removeCheckbox.addEventListener('change', function () {
                if (this.checked) {
                    input.value = '';
                    fileName.textContent = '';
                    drop.classList.remove('has-file');
                    preview.classList.remove('has-image');
                    preview.innerHTML = '<span>Archivo se eliminará al guardar</span>';
                } else {
                    preview.innerHTML = '<span>Cambios pendientes — vuelve a subir o guarda</span>';
                }
            });
        }
    })();
</script>
@endsection