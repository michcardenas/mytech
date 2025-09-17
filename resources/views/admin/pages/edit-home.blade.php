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
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(0, 123, 255, 0.02);
        border-radius: 10px;
        border-left: 4px solid var(--primary-blue);
    }

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
        padding: 0.75rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 1rem;
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
@endsection