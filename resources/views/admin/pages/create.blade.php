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

    .create-page-container {
        background: var(--white);
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-header-create {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-radius: 20px;
        border: 2px solid rgba(0, 123, 255, 0.1);
    }

    .page-title-create {
        color: var(--dark-text);
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
    }

    .page-subtitle-create {
        color: #666;
        font-size: 1.1rem;
        margin: 0;
    }

    .form-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }

    .form-section-title {
        color: var(--dark-text);
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-label {
        display: block;
        color: var(--dark-text);
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .required-star {
        color: #dc3545;
        margin-left: 0.25rem;
    }

    .type-selector {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .type-option {
        position: relative;
    }

    .type-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .type-card {
        border: 3px solid #e0e0e0;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background: var(--white);
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .type-card:hover {
        border-color: #007BFF;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
    }

    .type-option input[type="radio"]:checked + .type-card {
        border-color: #007BFF;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(0, 123, 255, 0.1) 100%);
        box-shadow: 0 8px 20px rgba(0, 123, 255, 0.25);
    }

    .type-option input[type="radio"]:checked + .type-card.type-landing {
        border-color: #28a745;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
    }

    .type-option input[type="radio"]:checked + .type-card.type-blog {
        border-color: #6f42c1;
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.05) 0%, rgba(111, 66, 193, 0.1) 100%);
    }

    .type-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #666;
        transition: var(--transition);
    }

    .type-option input[type="radio"]:checked + .type-card .type-icon {
        color: #007BFF;
        transform: scale(1.1);
    }

    .type-option input[type="radio"]:checked + .type-card.type-landing .type-icon {
        color: #28a745;
    }

    .type-option input[type="radio"]:checked + .type-card.type-blog .type-icon {
        color: #6f42c1;
    }

    .type-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
    }

    .type-description {
        font-size: 0.85rem;
        color: #666;
    }

    .form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: var(--transition);
        background: var(--white);
    }

    .form-input:focus {
        outline: none;
        border-color: #007BFF;
        box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
    }

    .input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-prefix {
        color: #666;
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .input-hint {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-group {
        display: flex;
        align-items: start;
        gap: 1rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.03) 0%, rgba(0, 123, 255, 0.06) 100%);
        border-radius: 12px;
        border: 2px solid rgba(0, 123, 255, 0.1);
        cursor: pointer;
        transition: var(--transition);
    }

    .checkbox-group:hover {
        background: linear-gradient(135deg, rgba(0, 123, 255, 0.06) 0%, rgba(0, 123, 255, 0.1) 100%);
        border-color: rgba(0, 123, 255, 0.2);
    }

    .checkbox-input {
        width: 1.5rem;
        height: 1.5rem;
        cursor: pointer;
        accent-color: #007BFF;
    }

    .checkbox-label-text {
        flex: 1;
    }

    .checkbox-title {
        font-weight: 600;
        color: var(--dark-text);
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .checkbox-description {
        font-size: 0.875rem;
        color: #666;
    }

    .info-box {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 2px solid #2196F3;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-box-header {
        display: flex;
        align-items: start;
        gap: 1rem;
    }

    .info-icon {
        font-size: 1.5rem;
        color: #2196F3;
        margin-top: 0.25rem;
    }

    .info-content {
        flex: 1;
    }

    .info-title {
        font-weight: 700;
        color: #1565c0;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list li {
        color: #1976d2;
        padding: 0.5rem 0;
        padding-left: 1.5rem;
        position: relative;
    }

    .info-list li::before {
        content: "✓";
        position: absolute;
        left: 0;
        font-weight: 700;
        color: #2196F3;
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 1rem 2rem;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--gradient-blue);
        color: var(--white);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
    }

    .btn-secondary {
        background: #f8f9fa;
        color: #666;
        border: 2px solid #e0e0e0;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        border-color: #ccc;
    }

    .examples-section {
        margin-top: 3rem;
        padding: 2rem;
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border-radius: 20px;
        border: 2px solid #4caf50;
    }

    .examples-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2e7d32;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .examples-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .example-card {
        background: var(--white);
        padding: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .example-title {
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
    }

    .example-slug {
        color: #666;
        font-family: monospace;
        font-size: 0.9rem;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .type-selector {
            grid-template-columns: 1fr;
        }

        .examples-grid {
            grid-template-columns: 1fr;
        }

        .btn-group {
            flex-direction: column;
        }
    }
</style>

<div class="create-page-container">
    {{-- Header --}}
    <div class="page-header-create">
        <h1 class="page-title-create">
            <i class="fas fa-plus-circle"></i>
            Crear Nueva Página
        </h1>
        <p class="page-subtitle-create">Crea una nueva página, landing page o entrada de blog</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf

        <div class="form-card">
            {{-- Tipo de Página --}}
            <div class="form-group">
                <h2 class="form-section-title">
                    <i class="fas fa-layer-group"></i>
                    Tipo de Página
                </h2>
                <div class="type-selector">
                    <label class="type-option">
                        <input type="radio" name="type" value="page" checked>
                        <div class="type-card">
                            <i class="fas fa-file-alt type-icon"></i>
                            <div class="type-name">Página Normal</div>
                            <div class="type-description">Sobre Nosotros, Servicios, etc.</div>
                        </div>
                    </label>

                    <label class="type-option">
                        <input type="radio" name="type" value="landing">
                        <div class="type-card type-landing">
                            <i class="fas fa-rocket type-icon"></i>
                            <div class="type-name">Landing Page</div>
                            <div class="type-description">Páginas de conversión SEO</div>
                        </div>
                    </label>

                    <label class="type-option">
                        <input type="radio" name="type" value="blog">
                        <div class="type-card type-blog">
                            <i class="fas fa-blog type-icon"></i>
                            <div class="type-name">Blog</div>
                            <div class="type-description">Artículos y contenido</div>
                        </div>
                    </label>
                </div>
                @error('type')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Título --}}
            <div class="form-group">
                <label for="title" class="form-label">
                    Título de la Página
                    <span class="required-star">*</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    class="form-input"
                    placeholder="Ej: Software a Medida en Bogotá"
                    required>
                <div class="input-hint">
                    <i class="fas fa-lightbulb"></i>
                    Este será el título principal que verán tus visitantes
                </div>
                @error('title')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Slug --}}
            <div class="form-group">
                <label for="slug" class="form-label">
                    URL Amigable (Slug)
                    <span class="required-star">*</span>
                </label>
                <div class="input-group">
                    <span class="input-prefix">{{ url('/') }}/</span>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        value="{{ old('slug') }}"
                        class="form-input"
                        placeholder="software-a-medida-bogota"
                        pattern="[a-z0-9\-]+"
                        required>
                </div>
                <div class="input-hint">
                    <i class="fas fa-info-circle"></i>
                    Solo letras minúsculas, números y guiones. Se genera automáticamente del título.
                </div>
                @error('slug')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Estado Activo --}}
            <div class="form-group">
                <label class="checkbox-group">
                    <input type="checkbox" name="is_active" value="1" checked class="checkbox-input">
                    <div class="checkbox-label-text">
                        <div class="checkbox-title">
                            <i class="fas fa-toggle-on"></i>
                            Página Activa
                        </div>
                        <div class="checkbox-description">
                            La página estará visible públicamente en tu sitio web
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="info-box">
            <div class="info-box-header">
                <i class="fas fa-info-circle info-icon"></i>
                <div class="info-content">
                    <div class="info-title">¿Qué pasa después de crear la página?</div>
                    <ul class="info-list">
                        <li>Serás redirigido a la gestión de <strong>secciones</strong></li>
                        <li>Podrás agregar secciones como: Hero, Problema, Solución, FAQs, etc.</li>
                        <li>Cada sección tiene datos personalizables en formato JSON</li>
                        <li>También podrás configurar el SEO completo desde el admin de páginas</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Crear Página
            </button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>

    {{-- Ejemplos de Landing Pages --}}
    <div class="examples-section">
        <h3 class="examples-title">
            <i class="fas fa-lightbulb"></i>
            Ideas de Landing Pages para SEO
        </h3>
        <div class="examples-grid">
            <div class="example-card">
                <div class="example-title">Software a Medida</div>
                <div class="example-slug">software-a-medida-bogota</div>
            </div>
            <div class="example-card">
                <div class="example-title">ERP Personalizado</div>
                <div class="example-slug">erp-a-medida</div>
            </div>
            <div class="example-card">
                <div class="example-title">Desarrollo Mobile</div>
                <div class="example-slug">desarrollo-app-movil-colombia</div>
            </div>
            <div class="example-card">
                <div class="example-title">E-commerce</div>
                <div class="example-slug">tienda-online-personalizada</div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-generar slug desde el título
    document.getElementById('title').addEventListener('input', function(e) {
        const slug = e.target.value
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Quitar acentos
            .replace(/[^a-z0-9\s-]/g, '') // Solo letras, números, espacios y guiones
            .replace(/\s+/g, '-') // Espacios a guiones
            .replace(/-+/g, '-') // Múltiples guiones a uno solo
            .replace(/^-|-$/g, ''); // Quitar guiones al inicio/fin

        document.getElementById('slug').value = slug;
    });

    // Animación de selección de tipo
    const typeOptions = document.querySelectorAll('.type-option');
    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            typeOptions.forEach(opt => {
                opt.querySelector('.type-card').style.transform = 'scale(1)';
            });
            this.querySelector('.type-card').style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.querySelector('.type-card').style.transform = 'scale(1)';
            }, 200);
        });
    });
</script>
@endsection
