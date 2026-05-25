{{-- resources/views/admin/seo/edit.blade.php --}}
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
        --gradient-teal: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        --gradient-purple: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        --gradient-orange: linear-gradient(135deg, #e17055 0%, #fdcb6e 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .seo-container {
        background: var(--light-gray);
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    /* Header */
    .seo-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.75rem 2rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .seo-header-left h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .seo-header-left p {
        margin: 0;
        opacity: 0.85;
        font-size: 0.88rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.35);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    /* Google Preview */
    .google-preview {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-soft);
    }

    .google-preview-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .google-preview-box {
        border: 1px solid #e8e8e8;
        border-radius: 10px;
        padding: 1.25rem;
        background: #fafafa;
    }

    .gp-url {
        font-size: 0.82rem;
        color: #202124;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .gp-url span {
        color: #5f6368;
    }

    .gp-title {
        font-size: 1.15rem;
        color: #1a0dab;
        margin-bottom: 0.25rem;
        font-weight: 400;
        line-height: 1.3;
        cursor: pointer;
    }

    .gp-title:hover {
        text-decoration: underline;
    }

    .gp-desc {
        font-size: 0.85rem;
        color: #4d5156;
        line-height: 1.5;
    }

    /* Alert */
    .alert-success {
        background: var(--white);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.2);
        border-left: 4px solid #28a745;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        box-shadow: var(--shadow-soft);
    }

    .alert-danger {
        background: var(--white);
        color: #721c24;
        border: 1px solid rgba(220, 53, 69, 0.2);
        border-left: 4px solid #dc3545;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        box-shadow: var(--shadow-soft);
    }

    /* Section Cards */
    .seo-section {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        margin-bottom: 1.25rem;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }

    .seo-section-header {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: var(--transition);
    }

    .seo-section-header:hover {
        background: rgba(0,0,0,0.01);
    }

    .seo-section-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: white;
        flex-shrink: 0;
    }

    .section-icon.blue { background: var(--gradient-blue); }
    .section-icon.teal { background: var(--gradient-teal); }
    .section-icon.purple { background: var(--gradient-purple); }
    .section-icon.orange { background: var(--gradient-orange); }
    .section-icon.green { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }

    .seo-section-header h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark-text);
    }

    .seo-section-header .badge {
        padding: 0.2rem 0.6rem;
        border-radius: 8px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-meta { background: rgba(0,123,255,0.1); color: var(--primary-blue); }
    .badge-og { background: rgba(66,103,178,0.1); color: #4267B2; }
    .badge-twitter { background: rgba(29,161,242,0.1); color: #1DA1F2; }
    .badge-schema { background: rgba(40,167,69,0.1); color: #28a745; }
    .badge-extra { background: rgba(111,66,193,0.1); color: #6f42c1; }

    .toggle-icon {
        font-size: 0.85rem;
        color: #aaa;
        transition: transform 0.3s ease;
    }

    .seo-section.collapsed .toggle-icon {
        transform: rotate(-90deg);
    }

    .seo-section-body {
        padding: 0 1.5rem 1.5rem;
        display: block;
    }

    .seo-section.collapsed .seo-section-body {
        display: none;
    }

    /* Form Fields */
    .field-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .field-row.single {
        grid-template-columns: 1fr;
    }

    .field-row.triple {
        grid-template-columns: repeat(3, 1fr);
    }

    .field-group {
        margin-bottom: 0;
    }

    .field-label {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.4rem;
    }

    .field-label i {
        color: #aaa;
        font-size: 0.75rem;
    }

    .form-control, .form-select {
        background: var(--white);
        border: 2px solid #e9ecef;
        color: var(--dark-text);
        border-radius: 10px;
        padding: 0.6rem 0.9rem;
        font-size: 0.88rem;
        transition: var(--transition);
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        outline: none;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 70px;
    }

    .field-hint {
        font-size: 0.75rem;
        color: #999;
        margin-top: 0.25rem;
    }

    .char-counter {
        font-size: 0.72rem;
        font-weight: 700;
        float: right;
    }

    .char-counter.good { color: #28a745; }
    .char-counter.warn { color: #f7a831; }
    .char-counter.bad { color: #dc3545; }

    /* Switch */
    .switch-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: rgba(0,0,0,0.015);
        border-radius: 10px;
        margin-bottom: 0.75rem;
    }

    .switch-row label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--dark-text);
        margin: 0;
        cursor: pointer;
    }

    .switch-row small {
        color: #999;
        font-size: 0.78rem;
    }

    .form-check-input {
        width: 2.5rem;
        height: 1.3rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    /* JSON Editor */
    .json-editor-wrapper {
        position: relative;
    }

    .json-editor {
        font-family: 'Fira Code', 'Consolas', 'Monaco', monospace;
        font-size: 0.82rem;
        line-height: 1.6;
        min-height: 200px;
        tab-size: 2;
        background: #1e1e2e;
        color: #cdd6f4;
        border: 2px solid #313244;
        border-radius: 10px;
        padding: 1rem;
    }

    .json-editor:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        outline: none;
    }

    .json-toolbar {
        display: flex;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
    }

    .json-btn {
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid rgba(0,0,0,0.08);
        background: var(--white);
        color: #666;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .json-btn:hover {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
    }

    .json-status {
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .json-status.valid { color: #28a745; }
    .json-status.invalid { color: #dc3545; }

    /* Image Picker */
    .image-field-wrapper {
        position: relative;
    }

    .image-input-row {
        display: flex;
        gap: 0.5rem;
        align-items: start;
    }

    .image-input-row .form-control {
        flex: 1;
    }

    .btn-pick-image {
        padding: 0.6rem 0.9rem;
        border-radius: 10px;
        border: 2px solid #e9ecef;
        background: var(--white);
        color: #666;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.82rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-pick-image:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        background: rgba(0,123,255,0.04);
    }

    .image-preview-small {
        margin-top: 0.5rem;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9ecef;
        display: none;
        position: relative;
        max-width: 200px;
    }

    .image-preview-small img {
        width: 100%;
        height: auto;
        display: block;
        max-height: 120px;
        object-fit: cover;
    }

    .image-preview-small .remove-preview {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        font-size: 0.7rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Image Picker Modal */
    .image-picker-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .image-picker-overlay.active {
        display: flex;
    }

    .image-picker-modal {
        background: var(--white);
        border-radius: 16px;
        width: 90%;
        max-width: 700px;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
    }

    .picker-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .picker-header h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dark-text);
    }

    .picker-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: #f1f1f1;
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .picker-close:hover {
        background: #e0e0e0;
        color: #333;
    }

    .picker-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex: 1;
    }

    .picker-empty {
        text-align: center;
        padding: 2rem;
        color: #999;
    }

    .picker-empty i {
        font-size: 2.5rem;
        color: #ddd;
        margin-bottom: 0.75rem;
        display: block;
    }

    .picker-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 0.75rem;
    }

    .picker-item {
        border: 2px solid #eee;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .picker-item:hover {
        border-color: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .picker-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        display: block;
    }

    .picker-item-info {
        padding: 0.4rem 0.6rem;
        background: var(--light-gray);
    }

    .picker-item-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--dark-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .picker-item-source {
        font-size: 0.62rem;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Footer Buttons */
    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 0 0;
        margin-top: 0.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-cancel {
        background: var(--white);
        border: 2px solid #ddd;
        color: #666;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.88rem;
    }

    .btn-cancel:hover {
        background: #f1f1f1;
        color: #333;
        text-decoration: none;
    }

    .btn-save {
        background: var(--gradient-blue);
        border: none;
        color: white;
        padding: 0.7rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 123, 255, 0.4);
        color: white;
    }

    .btn-save:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .seo-container { padding: 1rem; }

        .seo-header {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .field-row, .field-row.triple {
            grid-template-columns: 1fr;
        }

        .form-footer {
            flex-direction: column;
        }

        .btn-cancel, .btn-save {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="seo-container">
    {{-- Header --}}
    <div class="seo-header">
        <div class="seo-header-left">
            <h1>
                <i class="fas fa-search"></i>
                SEO: {{ $page->title }}
            </h1>
            <p>Configura meta tags, Open Graph, Twitter Cards y Schema.org</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Google Preview --}}
    <div class="google-preview">
        <div class="google-preview-label">
            <i class="fab fa-google"></i> Vista previa en Google
        </div>
        <div class="google-preview-box">
            <div class="gp-url">
                <img src="https://www.google.com/favicon.ico" width="16" height="16" alt="">
                <span>mytechsolutionsco.com</span> > {{ $page->slug }}
            </div>
            <div class="gp-title" id="preview-title">{{ $seo->meta_title ?: $page->title ?: 'Titulo de la pagina' }}</div>
            <div class="gp-desc" id="preview-desc">{{ $seo->meta_description ?: 'Agrega una meta descripcion para ver como se mostrara en los resultados de busqueda de Google.' }}</div>
        </div>
    </div>

    <form action="{{ route('admin.seo.update', $page) }}" method="POST" id="seo-form">
        @csrf @method('PUT')

        {{-- META TAGS --}}
        <div class="seo-section" id="section-meta">
            <div class="seo-section-header" onclick="toggleSection('section-meta')">
                <div class="seo-section-header-left">
                    <div class="section-icon blue"><i class="fas fa-tags"></i></div>
                    <h3>Meta Tags Basicos</h3>
                    <span class="badge badge-meta">Meta</span>
                </div>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="seo-section-body">
                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-heading"></i> Titulo SEO</div>
                        <input type="text" name="meta_title" class="form-control" id="meta-title"
                               value="{{ old('meta_title', $seo->meta_title) }}"
                               maxlength="150" placeholder="Titulo optimizado para SEO (50-60 caracteres)">
                        <div class="field-hint">Aparece como titulo principal en Google. <span class="char-counter" id="title-counter"></span></div>
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-align-left"></i> Meta Descripcion</div>
                        <textarea name="meta_description" class="form-control" rows="3" id="meta-desc"
                                  maxlength="500" placeholder="Descripcion para resultados de Google (120-155 caracteres)">{{ old('meta_description', $seo->meta_description) }}</textarea>
                        <div class="field-hint">Resumen atractivo para resultados de busqueda. <span class="char-counter" id="desc-counter"></span></div>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-key"></i> Palabras Clave</div>
                        <input type="text" name="meta_keywords" class="form-control"
                               value="{{ old('meta_keywords', $seo->meta_keywords) }}"
                               placeholder="desarrollo web, software, bogota">
                        <div class="field-hint">Separadas por comas</div>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-link"></i> URL Canonica</div>
                        <input type="url" name="canonical_url" class="form-control"
                               value="{{ old('canonical_url', $seo->canonical_url) }}"
                               placeholder="https://mytechsolutionsco.com/{{ $page->slug }}">
                        <div class="field-hint">URL principal de esta pagina</div>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-robot"></i> Directivas de Robots</div>
                        <select name="robots" class="form-select">
                            <option value="index,follow" {{ old('robots', $seo->robots) == 'index,follow' ? 'selected' : '' }}>index, follow (Recomendado)</option>
                            <option value="noindex,follow" {{ old('robots', $seo->robots) == 'noindex,follow' ? 'selected' : '' }}>noindex, follow</option>
                            <option value="index,nofollow" {{ old('robots', $seo->robots) == 'index,nofollow' ? 'selected' : '' }}>index, nofollow</option>
                            <option value="noindex,nofollow" {{ old('robots', $seo->robots) == 'noindex,nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-bullseye"></i> Palabra Clave Principal</div>
                        <input type="text" name="focus_keyword" class="form-control"
                               value="{{ old('focus_keyword', $seo->focus_keyword) }}"
                               placeholder="desarrollo web bogota">
                        <div class="field-hint">Palabra clave principal para posicionar</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- OPEN GRAPH --}}
        <div class="seo-section" id="section-og">
            <div class="seo-section-header" onclick="toggleSection('section-og')">
                <div class="seo-section-header-left">
                    <div class="section-icon purple"><i class="fab fa-facebook-f"></i></div>
                    <h3>Open Graph</h3>
                    <span class="badge badge-og">Facebook / LinkedIn</span>
                </div>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="seo-section-body">
                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-heading"></i> Titulo OG</div>
                        <input type="text" name="og_title" class="form-control"
                               value="{{ old('og_title', $seo->og_title) }}"
                               maxlength="150" placeholder="Titulo para redes sociales">
                        <div class="field-hint">Si esta vacio, usa el meta title</div>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-image"></i> Imagen OG</div>
                        <div class="image-field-wrapper">
                            <div class="image-input-row">
                                <input type="url" name="og_image" class="form-control" id="og-image-input"
                                       value="{{ old('og_image', $seo->og_image) }}"
                                       placeholder="https://mytechsolutionsco.com/images/og-image.png">
                                @if(count($pageImages) > 0)
                                    <button type="button" class="btn-pick-image" onclick="openImagePicker('og-image-input')">
                                        <i class="fas fa-images"></i> Elegir
                                    </button>
                                @endif
                            </div>
                            <div class="image-preview-small" id="og-image-preview">
                                <img src="" alt="Preview">
                                <button type="button" class="remove-preview" onclick="clearImageField('og-image-input')">&times;</button>
                            </div>
                        </div>
                        <div class="field-hint">1200x630px recomendado</div>
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-align-left"></i> Descripcion OG</div>
                        <textarea name="og_description" class="form-control" rows="2"
                                  placeholder="Descripcion para redes sociales">{{ old('og_description', $seo->og_description) }}</textarea>
                        <div class="field-hint">Si esta vacia, usa la meta descripcion</div>
                    </div>
                </div>

                <div class="field-row triple">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-tag"></i> Tipo OG</div>
                        <select name="og_type" class="form-select">
                            <option value="website" {{ old('og_type', $seo->og_type) == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="article" {{ old('og_type', $seo->og_type) == 'article' ? 'selected' : '' }}>Article</option>
                            <option value="product" {{ old('og_type', $seo->og_type) == 'product' ? 'selected' : '' }}>Product</option>
                            <option value="business.business" {{ old('og_type', $seo->og_type) == 'business.business' ? 'selected' : '' }}>Business</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-link"></i> URL OG</div>
                        <input type="url" name="og_url" class="form-control"
                               value="{{ old('og_url', $seo->og_url) }}"
                               placeholder="URL para compartir">
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-globe"></i> Nombre del Sitio</div>
                        <input type="text" name="og_site_name" class="form-control"
                               value="{{ old('og_site_name', $seo->og_site_name ?: 'MY Tech Solutions') }}"
                               placeholder="MY Tech Solutions">
                    </div>
                </div>
            </div>
        </div>

        {{-- TWITTER CARDS --}}
        <div class="seo-section collapsed" id="section-twitter">
            <div class="seo-section-header" onclick="toggleSection('section-twitter')">
                <div class="seo-section-header-left">
                    <div class="section-icon" style="background: linear-gradient(135deg, #1DA1F2 0%, #0d8ecf 100%);"><i class="fab fa-twitter"></i></div>
                    <h3>Twitter Cards</h3>
                    <span class="badge badge-twitter">X / Twitter</span>
                </div>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="seo-section-body">
                <div class="field-row triple">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-id-card"></i> Tipo de Card</div>
                        <select name="twitter_card" class="form-select">
                            <option value="summary" {{ old('twitter_card', $seo->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                            <option value="summary_large_image" {{ old('twitter_card', $seo->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                            <option value="app" {{ old('twitter_card', $seo->twitter_card) == 'app' ? 'selected' : '' }}>App</option>
                            <option value="player" {{ old('twitter_card', $seo->twitter_card) == 'player' ? 'selected' : '' }}>Player</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-at"></i> Twitter Site</div>
                        <input type="text" name="twitter_site" class="form-control"
                               value="{{ old('twitter_site', $seo->twitter_site) }}"
                               placeholder="@mytechsolutions">
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-user"></i> Twitter Creator</div>
                        <input type="text" name="twitter_creator" class="form-control"
                               value="{{ old('twitter_creator', $seo->twitter_creator) }}"
                               placeholder="@creador">
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-heading"></i> Titulo Twitter</div>
                        <input type="text" name="twitter_title" class="form-control"
                               value="{{ old('twitter_title', $seo->twitter_title) }}"
                               placeholder="Titulo para Twitter">
                        <div class="field-hint">Si esta vacio, usa el OG title</div>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-image"></i> Imagen Twitter</div>
                        <div class="image-field-wrapper">
                            <div class="image-input-row">
                                <input type="url" name="twitter_image" class="form-control" id="twitter-image-input"
                                       value="{{ old('twitter_image', $seo->twitter_image) }}"
                                       placeholder="URL de imagen para Twitter">
                                @if(count($pageImages) > 0)
                                    <button type="button" class="btn-pick-image" onclick="openImagePicker('twitter-image-input')">
                                        <i class="fas fa-images"></i> Elegir
                                    </button>
                                @endif
                            </div>
                            <div class="image-preview-small" id="twitter-image-preview">
                                <img src="" alt="Preview">
                                <button type="button" class="remove-preview" onclick="clearImageField('twitter-image-input')">&times;</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-align-left"></i> Descripcion Twitter</div>
                        <textarea name="twitter_description" class="form-control" rows="2"
                                  placeholder="Descripcion para Twitter">{{ old('twitter_description', $seo->twitter_description) }}</textarea>
                        <div class="field-hint">Si esta vacia, usa la OG description</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SCHEMA.ORG / JSON-LD --}}
        <div class="seo-section collapsed" id="section-schema">
            <div class="seo-section-header" onclick="toggleSection('section-schema')">
                <div class="seo-section-header-left">
                    <div class="section-icon green"><i class="fas fa-code"></i></div>
                    <h3>Schema.org / JSON-LD</h3>
                    <span class="badge badge-schema">Datos Estructurados</span>
                </div>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="seo-section-body">
                <p style="font-size: 0.85rem; color: #777; margin-bottom: 1rem;">
                    Agrega datos estructurados personalizados en formato JSON-LD. Esto ayuda a Google a entender el contenido de la pagina y mostrar rich snippets.
                </p>

                <div class="json-editor-wrapper">
                    <div class="json-toolbar">
                        <button type="button" class="json-btn" onclick="formatJson()">
                            <i class="fas fa-indent"></i> Formatear
                        </button>
                        <button type="button" class="json-btn" onclick="loadTemplate('local-business')">
                            <i class="fas fa-store"></i> Negocio Local
                        </button>
                        <button type="button" class="json-btn" onclick="loadTemplate('service')">
                            <i class="fas fa-cogs"></i> Servicio
                        </button>
                        <button type="button" class="json-btn" onclick="loadTemplate('webpage')">
                            <i class="fas fa-file-alt"></i> WebPage
                        </button>
                        <button type="button" class="json-btn" onclick="loadTemplate('faq')">
                            <i class="fas fa-question-circle"></i> FAQ
                        </button>
                        <span class="json-status" id="json-status"></span>
                    </div>
                    <textarea name="schema_markup" class="form-control json-editor" id="schema-editor" rows="12"
                              placeholder='{ "@context": "https://schema.org", "@type": "WebPage" }'>{{ old('schema_markup', ($seo->schema_markup ? (is_string($seo->schema_markup) ? $seo->schema_markup : json_encode($seo->schema_markup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) : '')) }}</textarea>
                    <div class="field-hint">Deja vacio para usar solo el schema Organization por defecto del sitio.</div>
                </div>
            </div>
        </div>

        {{-- SITEMAP & CONFIG --}}
        <div class="seo-section collapsed" id="section-config">
            <div class="seo-section-header" onclick="toggleSection('section-config')">
                <div class="seo-section-header-left">
                    <div class="section-icon orange"><i class="fas fa-cog"></i></div>
                    <h3>Sitemap y Configuracion</h3>
                    <span class="badge badge-extra">Extra</span>
                </div>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div class="seo-section-body">
                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-map-signs"></i> Titulo Breadcrumb</div>
                        <input type="text" name="breadcrumb_title" class="form-control"
                               value="{{ old('breadcrumb_title', $seo->breadcrumb_title) }}"
                               placeholder="Titulo personalizado para navegacion">
                        <div class="field-hint">Si esta vacio, usa el titulo de la pagina</div>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-sort-numeric-up"></i> Prioridad Sitemap</div>
                        <select name="sitemap_priority" class="form-select">
                            <option value="1.0" {{ old('sitemap_priority', $seo->sitemap_priority) == '1.0' ? 'selected' : '' }}>1.0 (Muy Alta)</option>
                            <option value="0.9" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.9' ? 'selected' : '' }}>0.9 (Alta)</option>
                            <option value="0.8" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.8' ? 'selected' : '' }}>0.8 (Normal)</option>
                            <option value="0.7" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.7' ? 'selected' : '' }}>0.7 (Media)</option>
                            <option value="0.5" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.5' ? 'selected' : '' }}>0.5 (Baja)</option>
                            <option value="0.3" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.3' ? 'selected' : '' }}>0.3 (Muy Baja)</option>
                        </select>
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-clock"></i> Frecuencia de Cambio</div>
                        <select name="sitemap_changefreq" class="form-select">
                            <option value="always" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'always' ? 'selected' : '' }}>Always</option>
                            <option value="hourly" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="never" {{ old('sitemap_changefreq', $seo->sitemap_changefreq) == 'never' ? 'selected' : '' }}>Never</option>
                        </select>
                    </div>
                </div>

                <div class="switch-row">
                    <input class="form-check-input" type="checkbox" name="sitemap_include" value="1" id="sitemap-check"
                           {{ old('sitemap_include', $seo->sitemap_include ?? true) ? 'checked' : '' }}>
                    <div>
                        <label for="sitemap-check">Incluir en Sitemap</label><br>
                        <small>Incluir esta pagina en sitemap.xml</small>
                    </div>
                </div>

                <div class="switch-row">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active-check"
                           {{ old('is_active', $seo->is_active ?? true) ? 'checked' : '' }}>
                    <div>
                        <label for="active-check">SEO Activo</label><br>
                        <small>Aplicar esta configuracion SEO en la pagina</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="form-footer">
            <a href="{{ route('admin.pages.index') }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn-save" id="save-btn">
                <i class="fas fa-save"></i> Guardar Configuracion SEO
            </button>
        </div>
    </form>
</div>

{{-- Image Picker Modal --}}
<div class="image-picker-overlay" id="image-picker-overlay" onclick="closeImagePicker(event)">
    <div class="image-picker-modal" onclick="event.stopPropagation()">
        <div class="picker-header">
            <h3><i class="fas fa-images me-2"></i> Imagenes de la pagina</h3>
            <button class="picker-close" onclick="closeImagePicker()"><i class="fas fa-times"></i></button>
        </div>
        <div class="picker-body">
            @if(count($pageImages) > 0)
                <div class="picker-grid">
                    @foreach($pageImages as $img)
                        <div class="picker-item" onclick="selectImage('{{ $img['url'] }}')">
                            <img src="{{ $img['url'] }}" alt="{{ $img['label'] }}" onerror="this.parentElement.style.display='none'">
                            <div class="picker-item-info">
                                <div class="picker-item-label">{{ $img['label'] }}</div>
                                <div class="picker-item-source">{{ $img['source'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="picker-empty">
                    <i class="fas fa-image"></i>
                    <p>No hay imagenes disponibles en esta pagina.<br>Sube imagenes desde la seccion de edicion de pagina.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Toggle sections
function toggleSection(id) {
    document.getElementById(id).classList.toggle('collapsed');
}

// Character counters + Google preview
const titleInput = document.getElementById('meta-title');
const descInput = document.getElementById('meta-desc');
const titleCounter = document.getElementById('title-counter');
const descCounter = document.getElementById('desc-counter');
const previewTitle = document.getElementById('preview-title');
const previewDesc = document.getElementById('preview-desc');

function updateCounter(input, counter, goodMax, warnMax) {
    const len = input.value.length;
    counter.textContent = len + '/' + input.getAttribute('maxlength');
    counter.className = 'char-counter ' + (len > warnMax ? 'bad' : len > goodMax ? 'warn' : 'good');
}

titleInput.addEventListener('input', function() {
    updateCounter(this, titleCounter, 50, 60);
    previewTitle.textContent = this.value || '{{ $page->title }}';
});

descInput.addEventListener('input', function() {
    updateCounter(this, descCounter, 120, 155);
    previewDesc.textContent = this.value || 'Agrega una meta descripcion para ver como se mostrara en Google.';
});

// Init counters
titleInput.dispatchEvent(new Event('input'));
descInput.dispatchEvent(new Event('input'));

// JSON editor
const schemaEditor = document.getElementById('schema-editor');
const jsonStatus = document.getElementById('json-status');

function validateJson() {
    const val = schemaEditor.value.trim();
    if (!val) {
        jsonStatus.textContent = '';
        jsonStatus.className = 'json-status';
        return true;
    }
    try {
        JSON.parse(val);
        jsonStatus.innerHTML = '<i class="fas fa-check-circle"></i> JSON valido';
        jsonStatus.className = 'json-status valid';
        return true;
    } catch (e) {
        jsonStatus.innerHTML = '<i class="fas fa-times-circle"></i> JSON invalido';
        jsonStatus.className = 'json-status invalid';
        return false;
    }
}

function formatJson() {
    const val = schemaEditor.value.trim();
    if (!val) return;
    try {
        const parsed = JSON.parse(val);
        schemaEditor.value = JSON.stringify(parsed, null, 2);
        validateJson();
    } catch (e) {
        alert('No se puede formatear: el JSON tiene errores de sintaxis.');
    }
}

function loadTemplate(type) {
    const templates = {
        'local-business': {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "MY Tech Solutions",
            "description": "Desarrollo de software y soluciones tecnologicas",
            "url": "https://mytechsolutionsco.com",
            "telephone": "",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Bogota",
                "addressCountry": "CO"
            },
            "geo": {
                "@type": "GeoCoordinates",
                "latitude": "",
                "longitude": ""
            },
            "openingHours": "Mo-Fr 08:00-18:00",
            "priceRange": "$$"
        },
        'service': {
            "@context": "https://schema.org",
            "@type": "Service",
            "name": "{{ $page->title }}",
            "description": "",
            "provider": {
                "@type": "Organization",
                "name": "MY Tech Solutions",
                "url": "https://mytechsolutionsco.com"
            },
            "areaServed": {
                "@type": "Country",
                "name": "Colombia"
            },
            "serviceType": ""
        },
        'webpage': {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "{{ $page->title }}",
            "description": "",
            "url": "https://mytechsolutionsco.com/{{ $page->slug }}",
            "isPartOf": {
                "@type": "WebSite",
                "name": "MY Tech Solutions",
                "url": "https://mytechsolutionsco.com"
            }
        },
        'faq': {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [
                {
                    "@type": "Question",
                    "name": "Pregunta 1?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Respuesta 1."
                    }
                },
                {
                    "@type": "Question",
                    "name": "Pregunta 2?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "Respuesta 2."
                    }
                }
            ]
        }
    };

    if (schemaEditor.value.trim() && !confirm('Esto reemplazara el contenido actual. Continuar?')) return;
    schemaEditor.value = JSON.stringify(templates[type], null, 2);
    validateJson();
}

schemaEditor.addEventListener('input', validateJson);
validateJson();

// Image Picker
let activeImageInput = null;

function openImagePicker(inputId) {
    activeImageInput = inputId;
    document.getElementById('image-picker-overlay').classList.add('active');
}

function closeImagePicker(e) {
    if (e && e.target !== document.getElementById('image-picker-overlay')) return;
    document.getElementById('image-picker-overlay').classList.remove('active');
    activeImageInput = null;
}

function selectImage(url) {
    if (!activeImageInput) return;
    const input = document.getElementById(activeImageInput);
    input.value = url;
    input.dispatchEvent(new Event('input'));
    updateImagePreview(activeImageInput);
    closeImagePicker();
}

function clearImageField(inputId) {
    const input = document.getElementById(inputId);
    input.value = '';
    input.dispatchEvent(new Event('input'));
    updateImagePreview(inputId);
}

function updateImagePreview(inputId) {
    const input = document.getElementById(inputId);
    const previewId = inputId.replace('-input', '-preview');
    const preview = document.getElementById(previewId);
    if (!preview) return;

    if (input.value.trim()) {
        preview.style.display = 'inline-block';
        preview.querySelector('img').src = input.value.trim();
        preview.querySelector('img').onerror = function() { preview.style.display = 'none'; };
    } else {
        preview.style.display = 'none';
    }
}

// Init previews on load
['og-image-input', 'twitter-image-input'].forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
        updateImagePreview(id);
        el.addEventListener('input', function() { updateImagePreview(id); });
    }
});

// Prevent double submit
document.getElementById('seo-form').addEventListener('submit', function(e) {
    const val = schemaEditor.value.trim();
    if (val && !validateJson()) {
        e.preventDefault();
        alert('Corrige el JSON de Schema.org antes de guardar.');
        return;
    }
    const btn = document.getElementById('save-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
});
</script>
@endsection
