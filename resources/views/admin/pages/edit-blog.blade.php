@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-purple: #6f42c1;
        --primary-purple-dark: #5a32a3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --gradient-purple: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);
        --shadow-soft: 0 10px 30px rgba(111, 66, 193, 0.1);
        --shadow-hover: 0 20px 40px rgba(111, 66, 193, 0.15);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .blog-edit-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .blog-header {
        background: var(--white);
        padding: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 2px solid rgba(111, 66, 193, 0.1);
    }

    .blog-title-header {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .blog-title-header i {
        color: var(--primary-purple);
    }

    .blog-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.1) 0%, rgba(111, 66, 193, 0.2) 100%);
        color: var(--primary-purple);
        border: 2px solid rgba(111, 66, 193, 0.2);
    }

    .btn-back {
        background: var(--light-gray);
        color: #666;
        border: 2px solid #e0e0e0;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-back:hover {
        background: #e9ecef;
        border-color: #ccc;
        transform: translateY(-2px);
        color: #666;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 2px solid #28a745;
        border-radius: 15px;
        color: #155724;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .blog-form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .blog-form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-card {
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow-soft);
        padding: 2rem;
        margin-bottom: 2rem;
        border: 2px solid rgba(111, 66, 193, 0.1);
    }

    .form-card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0 0 1.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(111, 66, 193, 0.1);
    }

    .form-card-title i {
        color: var(--primary-purple);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .required-star {
        color: #dc3545;
        margin-left: 0.25rem;
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1.125rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: var(--transition);
        background: var(--white);
        color: var(--dark-text);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 4px rgba(111, 66, 193, 0.1);
    }

    .form-control::placeholder {
        color: #999;
    }

    .form-hint {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-hint i {
        color: var(--primary-purple);
    }

    /* Quill Editor Styles */
    .quill-wrapper {
        background: var(--white);
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        transition: var(--transition);
    }

    .quill-wrapper:focus-within {
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 4px rgba(111, 66, 193, 0.1);
    }

    .ql-toolbar {
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.03) 0%, rgba(111, 66, 193, 0.06) 100%);
        border: none !important;
        border-bottom: 2px solid #e0e0e0 !important;
        padding: 1rem !important;
    }

    .ql-container {
        border: none !important;
        font-size: 1rem;
        min-height: 400px;
    }

    .ql-editor {
        min-height: 400px;
        padding: 1.5rem !important;
        color: var(--dark-text);
        line-height: 1.8;
    }

    .ql-editor.ql-blank::before {
        color: #999;
        font-style: normal;
    }

    /* Featured Image */
    .featured-image-wrapper {
        border: 2px dashed #e0e0e0;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: var(--transition);
        cursor: pointer;
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.02) 0%, rgba(111, 66, 193, 0.05) 100%);
    }

    .featured-image-wrapper:hover {
        border-color: var(--primary-purple);
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.05) 0%, rgba(111, 66, 193, 0.1) 100%);
    }

    .featured-image-wrapper input[type="file"] {
        display: none;
    }

    .featured-image-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        color: #666;
    }

    .featured-image-label i {
        font-size: 3rem;
        color: var(--primary-purple);
        opacity: 0.5;
    }

    .featured-image-preview {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .featured-image-preview img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
    }

    .btn-remove-image {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .btn-remove-image:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    /* Tags Input */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding: 0.5rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        min-height: 50px;
        background: var(--white);
        transition: var(--transition);
    }

    .tags-container:focus-within {
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 4px rgba(111, 66, 193, 0.1);
    }

    .tag-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.75rem;
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.1) 0%, rgba(111, 66, 193, 0.2) 100%);
        color: var(--primary-purple);
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .tag-item .remove-tag {
        cursor: pointer;
        font-size: 1rem;
        opacity: 0.7;
        transition: var(--transition);
    }

    .tag-item .remove-tag:hover {
        opacity: 1;
    }

    .tags-input {
        border: none;
        outline: none;
        padding: 0.4rem;
        flex: 1;
        min-width: 100px;
        font-size: 0.95rem;
    }

    /* Category Select */
    .category-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236f42c1' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    /* Status Cards */
    .status-card {
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.03) 0%, rgba(111, 66, 193, 0.06) 100%);
        border: 2px solid rgba(111, 66, 193, 0.1);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .status-card:hover {
        background: linear-gradient(135deg, rgba(111, 66, 193, 0.06) 0%, rgba(111, 66, 193, 0.1) 100%);
        border-color: var(--primary-purple);
    }

    .status-card input[type="checkbox"] {
        width: 1.5rem;
        height: 1.5rem;
        accent-color: var(--primary-purple);
    }

    .status-card-content {
        flex: 1;
    }

    .status-card-title {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 0.25rem;
    }

    .status-card-desc {
        font-size: 0.85rem;
        color: #666;
    }

    /* Datetime Input */
    .datetime-input {
        position: relative;
    }

    .datetime-input input {
        padding-right: 2.5rem;
    }

    .datetime-input i {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-purple);
        pointer-events: none;
    }

    /* Reading Time */
    .reading-time-display {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.05) 0%, rgba(40, 167, 69, 0.1) 100%);
        border: 2px solid rgba(40, 167, 69, 0.2);
        border-radius: 12px;
        margin-top: 0.5rem;
    }

    .reading-time-display i {
        color: #28a745;
        font-size: 1.5rem;
    }

    .reading-time-display span {
        font-weight: 600;
        color: #28a745;
    }

    /* Submit Button */
    .btn-submit {
        background: var(--gradient-purple);
        color: var(--white);
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
        width: 100%;
        justify-content: center;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(111, 66, 193, 0.4);
    }

    .btn-preview {
        background: var(--white);
        color: var(--primary-purple);
        border: 2px solid var(--primary-purple);
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
        text-decoration: none;
        margin-bottom: 1rem;
    }

    .btn-preview:hover {
        background: rgba(111, 66, 193, 0.05);
        transform: translateY(-2px);
        color: var(--primary-purple);
    }

    /* Word Counter */
    .word-counter {
        text-align: right;
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.5rem;
    }

    /* Error Messages */
    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* SEO Preview */
    .seo-preview {
        background: var(--white);
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .seo-preview-title {
        color: #1a0dab;
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .seo-preview-url {
        color: #006621;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .seo-preview-desc {
        color: #545454;
        font-size: 0.9rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Scheduled Badge */
    .scheduled-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%);
        color: #856404;
        border: 2px solid #ffc107;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-left: 1rem;
    }
</style>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="blog-edit-container">
    <!-- Header -->
    <div class="blog-header">
        <div>
            <h1 class="blog-title-header">
                <i class="fas fa-blog"></i>
                {{ $page->id ? 'Editar Blog' : 'Nuevo Blog' }}
            </h1>
            <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span class="blog-badge">
                    <i class="fas fa-blog"></i>
                    Blog
                </span>
                @if($page->isScheduled())
                    <span class="scheduled-badge">
                        <i class="fas fa-clock"></i>
                        Programado
                    </span>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Volver
        </a>
    </div>

    @if (session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data" id="blogForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="blog">

        <div class="blog-form-grid">
            <!-- Columna Principal -->
            <div class="main-column">
                <!-- Título del Blog -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-heading"></i>
                        Información Principal
                    </h2>

                    <div class="form-group">
                        <label for="title" class="form-label">
                            Título del Artículo
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="{{ old('title', $page->title) }}"
                               placeholder="Escribe un título atractivo para tu artículo"
                               required>
                        @error('title')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slug" class="form-label">
                            URL (Slug)
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" id="slug" name="slug" class="form-control"
                               value="{{ old('slug', $page->slug) }}"
                               placeholder="url-del-articulo"
                               pattern="[a-z0-9\-]+"
                               required>
                        <div class="form-hint">
                            <i class="fas fa-link"></i>
                            {{ url('/blog') }}/<span id="slugPreview">{{ $page->slug ?: 'url-del-articulo' }}</span>
                        </div>
                        @error('slug')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Contenido del Blog -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-file-alt"></i>
                        Contenido del Artículo
                    </h2>

                    <div class="form-group">
                        <label class="form-label">
                            Extracto / Resumen
                        </label>
                        <textarea name="excerpt" class="form-control" rows="3"
                                  placeholder="Breve descripción que aparecerá en las previsualizaciones (máx. 300 caracteres)"
                                  maxlength="300">{{ old('excerpt', $page->excerpt) }}</textarea>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Este texto se mostrará en listados y tarjetas de blog
                        </div>
                        @error('excerpt')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Contenido Principal
                            <span class="required-star">*</span>
                        </label>
                        <div class="quill-wrapper">
                            <div id="editor">{!! old('content', $page->content) !!}</div>
                            <input type="hidden" name="content" id="contentInput">
                        </div>
                        <div class="word-counter">
                            <span id="wordCount">0</span> palabras | <span id="readingTimeCalc">0</span> min de lectura
                        </div>
                        @error('content')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- SEO Preview -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-search"></i>
                        Vista Previa en Google
                    </h2>
                    <div class="seo-preview">
                        <div class="seo-preview-title" id="seoTitle">{{ $page->title ?: 'Título del artículo' }}</div>
                        <div class="seo-preview-url">{{ url('/blog') }}/<span id="seoSlug">{{ $page->slug ?: 'url-del-articulo' }}</span></div>
                        <div class="seo-preview-desc" id="seoDesc">{{ $page->excerpt ?: 'El extracto de tu artículo aparecerá aquí...' }}</div>
                    </div>
                    <div class="form-hint" style="margin-top: 1rem;">
                        <i class="fas fa-lightbulb"></i>
                        Para SEO avanzado, visita la sección de SEO después de guardar
                    </div>
                </div>
            </div>

            <!-- Columna Lateral -->
            <div class="sidebar-column">
                <!-- Publicación -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-paper-plane"></i>
                        Publicación
                    </h2>

                    <div class="form-group">
                        <label class="status-card">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                            <div class="status-card-content">
                                <div class="status-card-title">Artículo Activo</div>
                                <div class="status-card-desc">Visible públicamente en el blog</div>
                            </div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="published_at" class="form-label">
                            Fecha de Publicación
                        </label>
                        <div class="datetime-input">
                            <input type="datetime-local" id="published_at" name="published_at" class="form-control"
                                   value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Deja vacío para publicar inmediatamente
                        </div>
                    </div>

                    <a href="{{ $page->slug ? url('/blog/' . $page->slug) : '#' }}"
                       class="btn-preview" target="_blank"
                       {{ !$page->slug ? 'style=pointer-events:none;opacity:0.5' : '' }}>
                        <i class="fas fa-eye"></i>
                        Vista Previa
                    </a>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Guardar Artículo
                    </button>
                </div>

                <!-- Imagen Destacada -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-image"></i>
                        Imagen Destacada
                    </h2>

                    @if($page->featured_image)
                        <div class="featured-image-preview">
                            <img src="{{ Storage::url($page->featured_image) }}" alt="Imagen destacada">
                            <button type="button" class="btn-remove-image" onclick="removeFeaturedImage()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" name="remove_featured_image" id="removeFeaturedImage" value="0">
                    @endif

                    <div class="featured-image-wrapper" onclick="document.getElementById('featured_image').click()">
                        <input type="file" id="featured_image" name="featured_image" accept="image/*">
                        <label class="featured-image-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="imageFileName">{{ $page->featured_image ? 'Cambiar imagen' : 'Subir imagen' }}</span>
                            <small>JPG, PNG o WebP (máx. 2MB)</small>
                        </label>
                    </div>
                    @error('featured_image')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Categoría y Tags -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-folder"></i>
                        Categoría y Etiquetas
                    </h2>

                    <div class="form-group">
                        <label for="category" class="form-label">Categoría</label>
                        <select id="category" name="category" class="form-control category-select">
                            <option value="">Selecciona una categoría</option>
                            @foreach(\App\Models\Page::$blogCategories as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('category', $page->category) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Etiquetas</label>
                        <div class="tags-container" id="tagsContainer">
                            @foreach($page->getTagsArray() as $tag)
                                <span class="tag-item">
                                    {{ $tag }}
                                    <span class="remove-tag" onclick="removeTag(this)">&times;</span>
                                </span>
                            @endforeach
                            <input type="text" class="tags-input" id="tagInput"
                                   placeholder="Escribe y presiona Enter">
                        </div>
                        <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', $page->tags) }}">
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i>
                            Presiona Enter para agregar cada etiqueta
                        </div>
                        @error('tags')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Autor y Tiempo -->
                <div class="form-card">
                    <h2 class="form-card-title">
                        <i class="fas fa-user-edit"></i>
                        Autor
                    </h2>

                    <div class="form-group">
                        <label for="author" class="form-label">Nombre del Autor</label>
                        <input type="text" id="author" name="author" class="form-control"
                               value="{{ old('author', $page->author ?? auth()->user()->name ?? 'Admin') }}"
                               placeholder="Nombre del autor">
                        @error('author')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="reading_time" class="form-label">Tiempo de Lectura (min)</label>
                        <input type="number" id="reading_time" name="reading_time" class="form-control"
                               value="{{ old('reading_time', $page->reading_time) }}"
                               placeholder="Se calcula automáticamente" min="1">
                        <div class="reading-time-display">
                            <i class="fas fa-clock"></i>
                            <span id="readingTimeDisplay">{{ $page->reading_time ?? 0 }} min de lectura</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Quill
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Escribe el contenido de tu artículo aquí...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'font': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    // Contar palabras y calcular tiempo de lectura
    function updateWordCount() {
        const text = quill.getText().trim();
        const wordCount = text ? text.split(/\s+/).length : 0;
        const readingTime = Math.max(1, Math.ceil(wordCount / 200));

        document.getElementById('wordCount').textContent = wordCount;
        document.getElementById('readingTimeCalc').textContent = readingTime;
        document.getElementById('readingTimeDisplay').textContent = readingTime + ' min de lectura';

        // Auto-fill reading time if empty
        const readingTimeInput = document.getElementById('reading_time');
        if (!readingTimeInput.value) {
            readingTimeInput.value = readingTime;
        }
    }

    quill.on('text-change', updateWordCount);
    updateWordCount();

    // Sincronizar contenido al enviar
    document.getElementById('blogForm').addEventListener('submit', function(e) {
        const content = quill.root.innerHTML;
        document.getElementById('contentInput').value = content;

        // Update reading time
        const text = quill.getText().trim();
        const wordCount = text ? text.split(/\s+/).length : 0;
        const readingTime = Math.max(1, Math.ceil(wordCount / 200));
        const readingTimeInput = document.getElementById('reading_time');
        if (!readingTimeInput.value) {
            readingTimeInput.value = readingTime;
        }
    });

    // Auto-generar slug desde título
    document.getElementById('title').addEventListener('input', function(e) {
        const slug = e.target.value
            .toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');

        document.getElementById('slug').value = slug;
        document.getElementById('slugPreview').textContent = slug || 'url-del-articulo';
        document.getElementById('seoSlug').textContent = slug || 'url-del-articulo';
        document.getElementById('seoTitle').textContent = e.target.value || 'Título del artículo';
    });

    // Actualizar slug preview
    document.getElementById('slug').addEventListener('input', function(e) {
        document.getElementById('slugPreview').textContent = e.target.value || 'url-del-articulo';
        document.getElementById('seoSlug').textContent = e.target.value || 'url-del-articulo';
    });

    // Actualizar SEO description
    document.querySelector('textarea[name="excerpt"]').addEventListener('input', function(e) {
        document.getElementById('seoDesc').textContent = e.target.value || 'El extracto de tu artículo aparecerá aquí...';
    });

    // Preview de imagen destacada
    document.getElementById('featured_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('imageFileName').textContent = file.name;
        }
    });

    // Manejo de tags
    const tagInput = document.getElementById('tagInput');
    const tagsContainer = document.getElementById('tagsContainer');
    const tagsHidden = document.getElementById('tagsHidden');

    function updateTagsHidden() {
        const tags = [];
        tagsContainer.querySelectorAll('.tag-item').forEach(tag => {
            tags.push(tag.textContent.replace('×', '').trim());
        });
        tagsHidden.value = tags.join(',');
    }

    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const value = this.value.trim();
            if (value) {
                const tagSpan = document.createElement('span');
                tagSpan.className = 'tag-item';
                tagSpan.innerHTML = value + ' <span class="remove-tag" onclick="removeTag(this)">&times;</span>';
                tagsContainer.insertBefore(tagSpan, tagInput);
                this.value = '';
                updateTagsHidden();
            }
        }
    });

    window.removeTag = function(element) {
        element.parentElement.remove();
        updateTagsHidden();
    };
});

// Remover imagen destacada
function removeFeaturedImage() {
    document.getElementById('removeFeaturedImage').value = '1';
    document.querySelector('.featured-image-preview').style.display = 'none';
}
</script>
@endsection
