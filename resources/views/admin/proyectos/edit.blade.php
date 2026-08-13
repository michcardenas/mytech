@extends('layouts.app_admin')

@section('content')
<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .form-section {
        background: #2a3441;
        border: 1px solid #00A9E0;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 20px;
    }
    .form-section h4 { color: #00A9E0 !important; }
    .form-section.seo-section { border-color: #f7a831; }
    .form-section.seo-section h4 { color: #f7a831 !important; }
    .form-section.social-section { border-color: #25D366; }
    .form-section.social-section h4 { color: #25D366 !important; }
    .form-section.schema-section { border-color: #8B5CF6; }
    .form-section.schema-section h4 { color: #8B5CF6 !important; }
    .form-control, .form-select, textarea { background: #101820; border: 1px solid #00A9E0; color: #FCFAF1; }
    .form-control:focus, .form-select:focus, textarea:focus { background: #101820; border-color: #f7a831; color: #FCFAF1; box-shadow: 0 0 0 0.2rem rgba(247, 168, 49, 0.25); }
    .btn-primary { background-color: #00A9E0; border-color: #00A9E0; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    h2 { color: #00A9E0 !important; }
    label { color: #FCFAF1; font-weight: 600; margin-bottom: 8px; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .invalid-feedback { display: block; }
    .image-preview { max-width: 150px; max-height: 150px; border-radius: 10px; margin-top: 10px; background: white; padding: 5px; }

    /* ===== Subida de imágenes con previsualización ===== */
    .img-uploader, .img-uploader-multi { margin-top: 6px; }
    .img-input { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; }
    .img-drop { position: relative; display: flex; align-items: center; gap: 14px; width: 100%; cursor: pointer; margin: 0; padding: 14px; border: 2px dashed #00A9E0; border-radius: 12px; background: #101820; transition: border-color .15s ease, background .15s ease, box-shadow .15s ease; }
    .img-drop:hover, .img-drop.drag { border-color: #f7a831; background: #13212c; box-shadow: 0 0 0 3px rgba(247,168,49,.15); }
    .img-thumb { width: 78px; height: 78px; flex-shrink: 0; border-radius: 10px; background-color: #0b1116; background-position: center; background-size: cover; background-repeat: no-repeat; border: 1px solid rgba(0,169,224,.4); display: flex; align-items: center; justify-content: center; }
    .img-thumb-icon { color: #00A9E0; font-size: 1.5rem; }
    .img-thumb.has-img .img-thumb-icon { display: none; }
    .img-drop-body { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .img-drop-cta { color: #FCFAF1; font-weight: 600; font-size: .92rem; }
    .img-drop-cta u { color: #00A9E0; text-decoration: none; border-bottom: 1px dashed #00A9E0; }
    .img-drop-hint { color: #9CA3AF; font-size: .78rem; }
    .img-drop-file { color: #f7a831; font-size: .8rem; font-weight: 700; margin-top: 3px; word-break: break-all; }
    .img-clear { margin-top: 8px; background: transparent; border: 1px solid #6c757d; color: #cbd5e1; border-radius: 8px; padding: 4px 12px; font-size: .78rem; cursor: pointer; transition: all .15s ease; }
    .img-clear:hover { border-color: #ef4444; color: #ef4444; }
    .img-uploader.has-new .img-drop { border-style: solid; border-color: #25D366; }
    .img-drop-body-multi { align-items: flex-start; }
    .img-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .img-grid-item { width: 74px; height: 74px; border-radius: 8px; background-color: #0b1116; background-position: center; background-size: cover; background-repeat: no-repeat; border: 1px solid rgba(37,211,102,.45); }
    .hint { color: #9CA3AF; font-size: 0.825rem; margin-top: 4px; display: block; }
    .hint code { background: rgba(255,255,255,0.06); padding: 1px 6px; border-radius: 4px; font-size: 12px; color: #f7a831; }
    .char-counter { color: #6c757d; font-size: 0.8rem; font-family: ui-monospace, "JetBrains Mono", monospace; text-align: right; display: block; margin-top: 4px; }
    .char-counter.good { color: #10b981; }
    .char-counter.warn { color: #f7a831; }
    .char-counter.bad { color: #ef4444; }
    .serp-preview { background: #FFFFFF; color: #202124; padding: 16px 20px; border-radius: 8px; font-family: arial, sans-serif; margin-top: 12px; max-width: 600px; }
    .serp-preview-url { color: #202124; font-size: 14px; }
    .serp-preview-title { color: #1a0dab; font-size: 20px; line-height: 1.3; margin: 4px 0 8px; }
    .serp-preview-desc { color: #4d5156; font-size: 14px; line-height: 1.58; }

    /* Quill Editor Dark Theme */
    .ql-container { background: #101820; border: 1px solid #00A9E0; color: #FCFAF1; min-height: 150px; }
    .ql-editor { color: #FCFAF1; min-height: 120px; }
    .ql-editor.ql-blank::before { color: #6c757d; }
    .ql-toolbar { background: #1a252f; border: 1px solid #00A9E0; border-bottom: none; }
    .ql-stroke { stroke: #FCFAF1; }
    .ql-fill { fill: #FCFAF1; }
    .ql-picker-label { color: #FCFAF1; }
    .ql-picker-options { background: #1a252f; border: 1px solid #00A9E0; }
    .ql-picker-item:hover { color: #00A9E0; }
    .ql-toolbar button:hover, .ql-toolbar button.ql-active { color: #00A9E0; }
    .ql-toolbar button:hover .ql-stroke { stroke: #00A9E0; }
    .ql-toolbar button:hover .ql-fill { fill: #00A9E0; }
    .ql-toolbar button.ql-active .ql-stroke { stroke: #00A9E0; }
    .ql-toolbar button.ql-active .ql-fill { fill: #00A9E0; }

    .seo-badge { display: inline-block; background: #f7a831; color: #101820; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700; margin-left: 8px; vertical-align: middle; text-transform: uppercase; letter-spacing: 0.04em; }
</style>

<div class="main-content">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">✏️ Editar Proyecto</h2>
                <p class="text-light mb-0">{{ $proyecto->nombre }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('proyectos.show', $proyecto->slug) }}" target="_blank" class="btn btn-outline-info">
                    <i class="fas fa-external-link-alt"></i> Ver en vivo
                </a>
                <a href="{{ route('admin.proyectos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Errores:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.proyectos.update', $proyecto) }}" method="POST" enctype="multipart/form-data" id="proyectoForm">
            @csrf
            @method('PUT')

            {{-- 1. INFORMACIÓN BÁSICA --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Información Básica</h4>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nombre">Nombre del Proyecto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               value="{{ old('nombre', $proyecto->nombre) }}" required maxlength="255">
                    </div>
                    <div class="col-md-4">
                        <label for="slug">URL slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               value="{{ old('slug', $proyecto->slug) }}" pattern="[a-z0-9\-]+" maxlength="255">
                        <span class="hint">URL: <code>/proyectos/<span id="slugPreview">{{ $proyecto->slug }}</span></code></span>
                    </div>

                    <div class="col-md-6">
                        <label for="pais">País <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pais" name="pais"
                               value="{{ old('pais', $proyecto->pais) }}" required maxlength="100">
                    </div>
                    <div class="col-md-2">
                        <label for="bandera_emoji">Bandera <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bandera_emoji" name="bandera_emoji"
                               value="{{ old('bandera_emoji', $proyecto->bandera_emoji) }}" required maxlength="10">
                    </div>
                    <div class="col-md-4">
                        <label for="badge_text">Badge / Tagline <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="badge_text" name="badge_text"
                               value="{{ old('badge_text', $proyecto->badge_text) }}" required maxlength="255">
                    </div>

                    <div class="col-12">
                        <label for="descripcion">Descripción Corta <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                  required maxlength="500">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="url">URL del proyecto en vivo</label>
                        <input type="url" class="form-control" id="url" name="url"
                               value="{{ old('url', $proyecto->url) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="tecnologias">Tecnologías (separadas por coma) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tecnologias" name="tecnologias"
                               value="{{ old('tecnologias', is_array($proyecto->tecnologias) ? implode(', ', $proyecto->tecnologias) : $proyecto->tecnologias) }}" required>
                    </div>
                </div>
            </div>

            {{-- 2. CATEGORIZACIÓN & CLIENTE --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-tags me-2"></i>Categorización & Cliente</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="categoria">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            @foreach(['ecommerce','admin','tech','automation','travel','booking','restaurant','legal'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria', $proyecto->categoria) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="industria">Industria / Vertical</label>
                        <input type="text" class="form-control" id="industria" name="industria"
                               value="{{ old('industria', $proyecto->industria) }}" maxlength="120">
                    </div>
                    <div class="col-md-4">
                        <label for="client_size">Tamaño del cliente</label>
                        <select class="form-select" id="client_size" name="client_size">
                            <option value="">— Sin especificar —</option>
                            @foreach(['startup'=>'Startup','pyme'=>'PyME','empresa'=>'Empresa','enterprise'=>'Enterprise'] as $k=>$v)
                                <option value="{{ $k }}" {{ old('client_size', $proyecto->client_size) === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="estado">Estado <span class="text-danger">*</span></label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="en_vivo" {{ old('estado', $proyecto->estado) === 'en_vivo' ? 'selected' : '' }}>🟢 En Vivo</option>
                            <option value="en_desarrollo" {{ old('estado', $proyecto->estado) === 'en_desarrollo' ? 'selected' : '' }}>🟡 En Desarrollo</option>
                            <option value="pausado" {{ old('estado', $proyecto->estado) === 'pausado' ? 'selected' : '' }}>⚪ Pausado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="orden">Orden</label>
                        <input type="number" class="form-control" id="orden" name="orden"
                               value="{{ old('orden', $proyecto->orden) }}" min="0">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="destacado" name="destacado" value="1" {{ old('destacado', $proyecto->destacado) ? 'checked' : '' }}>
                            <label class="form-check-label" for="destacado">⭐ Destacado</label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', $proyecto->activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">✅ Activo</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. LOGO --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-image me-2"></i>Logo del Proyecto</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="logo">Archivo del logo</label>
                        <div class="img-uploader">
                            <label class="img-drop" for="logo">
                                <input type="file" class="img-input" id="logo" name="logo" accept="image/*">
                                <div class="img-thumb @if($proyecto->logo) has-img @endif" @if($proyecto->logo) style="background-image:url('{{ asset('storage/'.$proyecto->logo) }}')" @endif>
                                    <i class="fas fa-cloud-upload-alt img-thumb-icon"></i>
                                </div>
                                <div class="img-drop-body">
                                    <span class="img-drop-cta">Arrastra o <u>haz clic</u> para subir</span>
                                    <span class="img-drop-hint">PNG · JPG · SVG · WEBP — máx 2MB@if($proyecto->logo) · reemplaza el actual@endif</span>
                                    <span class="img-drop-file"></span>
                                </div>
                            </label>
                            <button type="button" class="img-clear" hidden><i class="fas fa-times"></i> Quitar selección</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="alt_logo">Alt text del logo <span class="seo-badge">SEO</span></label>
                        <input type="text" class="form-control" id="alt_logo" name="alt_logo"
                               value="{{ old('alt_logo', $proyecto->alt_logo) }}" maxlength="255">
                    </div>
                </div>
            </div>

            {{-- 4. SEO ESENCIAL --}}
            <div class="form-section seo-section">
                <h4 class="mb-3"><i class="fas fa-search me-2"></i>SEO Esencial <span class="seo-badge">Core</span></h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="focus_keyword">Focus keyword (1 sola)</label>
                        <input type="text" class="form-control" id="focus_keyword" name="focus_keyword"
                               value="{{ old('focus_keyword', $proyecto->focus_keyword) }}" maxlength="120">
                    </div>
                    <div class="col-md-6">
                        <label for="secondary_keywords">Keywords secundarias (CSV)</label>
                        <input type="text" class="form-control" id="secondary_keywords" name="secondary_keywords"
                               value="{{ old('secondary_keywords', is_array($proyecto->secondary_keywords) ? implode(', ', $proyecto->secondary_keywords) : '') }}">
                    </div>

                    <div class="col-12">
                        <label for="meta_title">Meta Title <span class="seo-badge">SEO</span></label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $proyecto->meta_title) }}" maxlength="150">
                        <span class="char-counter" id="counter_meta_title">0 / 60 chars</span>
                    </div>

                    <div class="col-12">
                        <label for="meta_description">Meta Description <span class="seo-badge">SEO</span></label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                  maxlength="300">{{ old('meta_description', $proyecto->meta_description) }}</textarea>
                        <span class="char-counter" id="counter_meta_description">0 / 160 chars</span>
                    </div>

                    <div class="col-12">
                        <label for="excerpt">Excerpt / Resumen corto</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2" maxlength="500">{{ old('excerpt', $proyecto->excerpt) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                               value="{{ old('canonical_url', $proyecto->canonical_url) }}" maxlength="500">
                    </div>
                    <div class="col-md-6">
                        <label for="robots">Robots</label>
                        <select class="form-select" id="robots" name="robots">
                            @foreach([
                                'index,follow' => '✅ index, follow',
                                'noindex,follow' => '🚫 noindex, follow',
                                'index,nofollow' => '⚠️ index, nofollow',
                                'noindex,nofollow' => '❌ noindex, nofollow'
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ old('robots', $proyecto->robots) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="text-warning">📺 Vista previa Google (SERP)</label>
                        <div class="serp-preview">
                            <div class="serp-preview-url">mytechsolutionsco.com › proyectos › <span id="serpSlug">{{ $proyecto->slug }}</span></div>
                            <div class="serp-preview-title" id="serpTitle">{{ $proyecto->meta_title ?: $proyecto->nombre.' — MY Tech Solutions' }}</div>
                            <div class="serp-preview-desc" id="serpDesc">{{ $proyecto->meta_description ?: 'La meta description aparecerá aquí.' }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="meta_keywords">Meta Keywords (legacy)</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                               value="{{ old('meta_keywords', $proyecto->meta_keywords) }}" maxlength="255">
                    </div>
                </div>
            </div>

            {{-- 5. OPEN GRAPH --}}
            <div class="form-section social-section">
                <h4 class="mb-3"><i class="fab fa-facebook me-2"></i>Open Graph <span class="seo-badge" style="background:#1877F2;color:#fff;">Social</span></h4>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="og_title">OG Title</label>
                        <input type="text" class="form-control" id="og_title" name="og_title"
                               value="{{ old('og_title', $proyecto->og_title) }}" maxlength="150">
                    </div>
                    <div class="col-12">
                        <label for="og_description">OG Description</label>
                        <textarea class="form-control" id="og_description" name="og_description" rows="2" maxlength="300">{{ old('og_description', $proyecto->og_description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="og_image">OG Image (1200×630)</label>
                        <div class="img-uploader">
                            <label class="img-drop" for="og_image">
                                <input type="file" class="img-input" id="og_image" name="og_image" accept="image/*">
                                <div class="img-thumb @if($proyecto->og_image) has-img @endif" @if($proyecto->og_image) style="background-image:url('{{ asset('storage/'.$proyecto->og_image) }}')" @endif>
                                    <i class="fas fa-cloud-upload-alt img-thumb-icon"></i>
                                </div>
                                <div class="img-drop-body">
                                    <span class="img-drop-cta">Arrastra o <u>haz clic</u> para subir</span>
                                    <span class="img-drop-hint">Ideal 1200×630 · JPG/PNG/WEBP — máx 5MB@if($proyecto->og_image) · reemplaza el actual@endif</span>
                                    <span class="img-drop-file"></span>
                                </div>
                            </label>
                            <button type="button" class="img-clear" hidden><i class="fas fa-times"></i> Quitar selección</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="alt_og_image">Alt OG Image</label>
                        <input type="text" class="form-control" id="alt_og_image" name="alt_og_image"
                               value="{{ old('alt_og_image', $proyecto->alt_og_image) }}" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label for="og_type">OG Type</label>
                        <select class="form-select" id="og_type" name="og_type">
                            @foreach(['article'=>'article', 'website'=>'website', 'product'=>'product'] as $val => $label)
                                <option value="{{ $val }}" {{ old('og_type', $proyecto->og_type ?: 'article') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 6. TWITTER --}}
            <div class="form-section social-section" style="border-color:#1DA1F2;">
                <h4 class="mb-3" style="color:#1DA1F2 !important;"><i class="fab fa-twitter me-2"></i>Twitter / X Cards</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="twitter_card">Tipo de card</label>
                        <select class="form-select" id="twitter_card" name="twitter_card">
                            @foreach(['summary_large_image'=>'Summary Large Image','summary'=>'Summary','app'=>'App','player'=>'Player'] as $val => $label)
                                <option value="{{ $val }}" {{ old('twitter_card', $proyecto->twitter_card ?: 'summary_large_image') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="twitter_image">Twitter Image (override)</label>
                        <div class="img-uploader">
                            <label class="img-drop" for="twitter_image">
                                <input type="file" class="img-input" id="twitter_image" name="twitter_image" accept="image/*">
                                <div class="img-thumb @if($proyecto->twitter_image) has-img @endif" @if($proyecto->twitter_image) style="background-image:url('{{ asset('storage/'.$proyecto->twitter_image) }}')" @endif>
                                    <i class="fas fa-cloud-upload-alt img-thumb-icon"></i>
                                </div>
                                <div class="img-drop-body">
                                    <span class="img-drop-cta">Arrastra o <u>haz clic</u> para subir</span>
                                    <span class="img-drop-hint">Override de Twitter/X · JPG/PNG/WEBP — máx 2MB@if($proyecto->twitter_image) · reemplaza el actual@endif</span>
                                    <span class="img-drop-file"></span>
                                </div>
                            </label>
                            <button type="button" class="img-clear" hidden><i class="fas fa-times"></i> Quitar selección</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="twitter_title">Twitter Title</label>
                        <input type="text" class="form-control" id="twitter_title" name="twitter_title"
                               value="{{ old('twitter_title', $proyecto->twitter_title) }}" maxlength="150">
                    </div>
                    <div class="col-12">
                        <label for="twitter_description">Twitter Description</label>
                        <textarea class="form-control" id="twitter_description" name="twitter_description" rows="2" maxlength="300">{{ old('twitter_description', $proyecto->twitter_description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 7. SCHEMA.ORG --}}
            <div class="form-section schema-section">
                <h4 class="mb-3"><i class="fas fa-code me-2"></i>Schema.org Structured Data</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="schema_type">Schema Type</label>
                        <select class="form-select" id="schema_type" name="schema_type">
                            @foreach([
                                'CreativeWork' => 'CreativeWork (default)',
                                'SoftwareApplication' => 'SoftwareApplication',
                                'Service' => 'Service',
                                'Product' => 'Product',
                                'WebApplication' => 'WebApplication',
                                'MobileApplication' => 'MobileApplication',
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ old('schema_type', $proyecto->schema_type ?: 'CreativeWork') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="schema_markup">Schema JSON-LD custom (override)</label>
                        <textarea class="form-control" id="schema_markup" name="schema_markup" rows="6"
                                  style="font-family: ui-monospace, monospace; font-size: 0.85rem;">{{ old('schema_markup', $proyecto->schema_markup) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 8. METADATA AVANZADA --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-pen me-2"></i>Metadata Avanzada</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="breadcrumb_title">Breadcrumb title</label>
                        <input type="text" class="form-control" id="breadcrumb_title" name="breadcrumb_title"
                               value="{{ old('breadcrumb_title', $proyecto->breadcrumb_title) }}" maxlength="120">
                    </div>
                    <div class="col-md-4">
                        <label for="author">Autor</label>
                        <input type="text" class="form-control" id="author" name="author"
                               value="{{ old('author', $proyecto->author ?: 'MY Tech Solutions') }}" maxlength="120">
                    </div>
                    <div class="col-md-4">
                        <label for="reading_time">Tiempo de lectura (min)</label>
                        <input type="number" class="form-control" id="reading_time" name="reading_time"
                               value="{{ old('reading_time', $proyecto->reading_time) }}" min="1" max="120">
                    </div>
                    <div class="col-md-4">
                        <label for="publicado_en">Fecha publicación SEO</label>
                        <input type="date" class="form-control" id="publicado_en" name="publicado_en"
                               value="{{ old('publicado_en', $proyecto->publicado_en?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            {{-- 9. RECURSOS EXTERNOS --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-external-link-alt me-2"></i>Recursos externos</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="case_study_url">URL externa del case study</label>
                        <input type="url" class="form-control" id="case_study_url" name="case_study_url"
                               value="{{ old('case_study_url', $proyecto->case_study_url) }}" maxlength="500">
                    </div>
                    <div class="col-md-6">
                        <label for="video_url">URL del video</label>
                        <input type="url" class="form-control" id="video_url" name="video_url"
                               value="{{ old('video_url', $proyecto->video_url) }}" maxlength="500">
                    </div>
                </div>
            </div>

            {{-- 10. CONTENIDO EXTENDIDO --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-book me-2"></i>Contenido Extendido del Caso</h4>
                <div class="mb-3">
                    <label>Descripción Extendida</label>
                    <input type="hidden" name="descripcion_extendida" id="descripcion_extendida" value="{{ old('descripcion_extendida', $proyecto->descripcion_extendida) }}">
                    <div id="editor_descripcion_extendida"></div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>🎯 El Desafío</label>
                        <input type="hidden" name="desafio" id="desafio" value="{{ old('desafio', $proyecto->desafio) }}">
                        <div id="editor_desafio"></div>
                    </div>
                    <div class="col-md-6">
                        <label>💡 La Solución</label>
                        <input type="hidden" name="solucion" id="solucion" value="{{ old('solucion', $proyecto->solucion) }}">
                        <div id="editor_solucion"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <label>📈 Resultados</label>
                    <input type="hidden" name="resultados" id="resultados" value="{{ old('resultados', $proyecto->resultados) }}">
                    <div id="editor_resultados"></div>
                </div>
                <div class="mt-3">
                    <label for="galeria">📸 Galería (reemplaza la actual al subir)</label>
                    @if($proyecto->galeria && is_array($proyecto->galeria) && count($proyecto->galeria))
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($proyecto->galeria as $g)
                                <img src="{{ asset('storage/'.$g) }}" style="max-height:80px; border-radius:6px; border:1px solid rgba(0,169,224,.3);">
                            @endforeach
                        </div>
                    @endif
                    <div class="img-uploader-multi">
                        <label class="img-drop" for="galeria">
                            <input type="file" class="img-input" id="galeria" name="galeria[]" accept="image/*" multiple>
                            <div class="img-thumb"><i class="fas fa-images img-thumb-icon"></i></div>
                            <div class="img-drop-body img-drop-body-multi">
                                <span class="img-drop-cta">Arrastra varias o <u>haz clic</u> para elegir</span>
                                <span class="img-drop-hint">Puedes seleccionar varias · máx 2MB c/u · reemplaza la galería actual</span>
                                <span class="img-drop-file"></span>
                            </div>
                        </label>
                        <div class="img-grid"></div>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="galeria_alts">🔤 Alt text de galería <span class="seo-badge">SEO Imágenes</span></label>
                    <textarea class="form-control" id="galeria_alts" name="galeria_alts" rows="3"
                              placeholder="Un alt por línea, en el mismo orden que las imágenes.">{{ old('galeria_alts', is_array($proyecto->galeria_alts) ? implode("\n", $proyecto->galeria_alts) : '') }}</textarea>
                    <span class="hint">Describe cada imagen (una por línea) para Google Imágenes. El orden debe coincidir con la galería.</span>
                </div>
            </div>

            {{-- 10.5. FAQ (FAQPage schema) --}}
            @include('admin.proyectos._faq_fields', ['faqData' => old('faq_pregunta') ? collect(old('faq_pregunta'))->map(fn($p, $i) => ['pregunta' => $p, 'respuesta' => old('faq_respuesta')[$i] ?? ''])->all() : ($proyecto->faqs ?? [])])

            {{-- 11. TESTIMONIO --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-quote-left me-2"></i>Testimonio del Cliente</h4>
                <div class="mb-3">
                    <label for="testimonio">Quote</label>
                    <textarea class="form-control" id="testimonio" name="testimonio" rows="3">{{ old('testimonio', $proyecto->testimonio) }}</textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="testimonio_autor">Autor</label>
                        <input type="text" class="form-control" id="testimonio_autor" name="testimonio_autor"
                               value="{{ old('testimonio_autor', $proyecto->testimonio_autor) }}" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label for="testimonio_cargo">Cargo</label>
                        <input type="text" class="form-control" id="testimonio_cargo" name="testimonio_cargo"
                               value="{{ old('testimonio_cargo', $proyecto->testimonio_cargo) }}" maxlength="255">
                    </div>
                </div>
            </div>

            {{-- 12. MÉTRICAS --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-chart-line me-2"></i>Métricas del Proyecto</h4>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="duracion_desarrollo">Duración</label>
                        <input type="text" class="form-control" id="duracion_desarrollo" name="duracion_desarrollo"
                               value="{{ old('duracion_desarrollo', $proyecto->duracion_desarrollo) }}" maxlength="100">
                    </div>
                    <div class="col-md-3">
                        <label for="equipo_size">Tamaño equipo</label>
                        <input type="number" class="form-control" id="equipo_size" name="equipo_size"
                               value="{{ old('equipo_size', $proyecto->equipo_size) }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_lanzamiento">Fecha lanzamiento</label>
                        <input type="date" class="form-control" id="fecha_lanzamiento" name="fecha_lanzamiento"
                               value="{{ old('fecha_lanzamiento', $proyecto->fecha_lanzamiento?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="visitas_mensuales">Visitas / mes</label>
                        <input type="number" class="form-control" id="visitas_mensuales" name="visitas_mensuales"
                               value="{{ old('visitas_mensuales', $proyecto->visitas_mensuales) }}" min="0">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mb-5">
                <a href="{{ route('admin.proyectos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Actualizar Proyecto
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
(function () {
    // Quill editors
    ['descripcion_extendida','desafio','solucion','resultados'].forEach(name => {
        const container = document.getElementById('editor_' + name);
        const hidden = document.getElementById(name);
        if (!container || !hidden) return;
        const q = new Quill(container, {
            theme: 'snow',
            modules: { toolbar: [
                [{header:[1,2,3,false]}],
                ['bold','italic','underline','strike'],
                [{list:'ordered'},{list:'bullet'}],
                ['link','blockquote','code-block'],
                ['clean'],
            ]},
            placeholder: 'Escribe aquí…',
        });
        if (hidden.value) q.root.innerHTML = hidden.value;
        q.on('text-change', () => { hidden.value = q.root.innerHTML; });
    });

    // Char counters
    function counter(inputId, counterId, ideal) {
        const inp = document.getElementById(inputId);
        const cnt = document.getElementById(counterId);
        if (!inp || !cnt) return;
        const update = () => {
            const len = inp.value.length;
            cnt.textContent = `${len} / ${ideal} chars (ideal: ${ideal-10}–${ideal})`;
            cnt.className = 'char-counter';
            if (len === 0) cnt.classList.add('');
            else if (len < ideal-15) cnt.classList.add('warn');
            else if (len <= ideal) cnt.classList.add('good');
            else cnt.classList.add('bad');
        };
        inp.addEventListener('input', update); update();
    }
    counter('meta_title', 'counter_meta_title', 60);
    counter('meta_description', 'counter_meta_description', 160);

    // SERP preview
    const serpTitle = document.getElementById('serpTitle');
    const serpDesc  = document.getElementById('serpDesc');
    const serpSlug  = document.getElementById('serpSlug');
    const nombreEl = document.getElementById('nombre');
    const slugEl = document.getElementById('slug');
    const metaTitleEl = document.getElementById('meta_title');
    const metaDescEl  = document.getElementById('meta_description');
    function update() {
        if (serpTitle) serpTitle.textContent = metaTitleEl.value || (nombreEl.value + ' — MY Tech Solutions');
        if (serpDesc)  serpDesc.textContent  = metaDescEl.value || 'La meta description aparecerá aquí.';
        if (serpSlug)  serpSlug.textContent  = slugEl.value || 'slug';
    }
    [metaTitleEl, metaDescEl, nombreEl, slugEl].forEach(el => el && el.addEventListener('input', update));
})();
</script>

<script>
/* Previsualización de imágenes al subir (dropzone + drag & drop) */
(function () {
    function fmtSize(bytes) {
        return bytes < 1048576 ? Math.round(bytes / 1024) + ' KB' : (bytes / 1048576).toFixed(1) + ' MB';
    }

    // Imagen única: logo, og_image, twitter_image
    document.querySelectorAll('.img-uploader').forEach(function (u) {
        var input = u.querySelector('.img-input');
        var drop = u.querySelector('.img-drop');
        var thumb = u.querySelector('.img-thumb');
        var fileLabel = u.querySelector('.img-drop-file');
        var clearBtn = u.querySelector('.img-clear');
        if (!input || !drop || !thumb) { return; }
        var originalBg = thumb.style.backgroundImage || '';
        var originalHasImg = thumb.classList.contains('has-img');

        function preview(files) {
            if (!files || !files.length) { return; }
            var f = files[0];
            if (!f.type || f.type.indexOf('image/') !== 0) { return; }
            thumb.style.backgroundImage = "url('" + URL.createObjectURL(f) + "')";
            thumb.classList.add('has-img');
            fileLabel.textContent = f.name + ' · ' + fmtSize(f.size);
            u.classList.add('has-new');
            if (clearBtn) { clearBtn.hidden = false; }
        }

        input.addEventListener('change', function () { preview(input.files); });
        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('drag'); });
        });
        ['dragleave', 'dragend'].forEach(function (ev) {
            drop.addEventListener(ev, function () { drop.classList.remove('drag'); });
        });
        drop.addEventListener('drop', function (e) {
            e.preventDefault();
            drop.classList.remove('drag');
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                preview(input.files);
            }
        });
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                input.value = '';
                thumb.style.backgroundImage = originalBg;
                thumb.classList.toggle('has-img', originalHasImg);
                fileLabel.textContent = '';
                u.classList.remove('has-new');
                clearBtn.hidden = true;
            });
        }
    });

    // Galería múltiple
    document.querySelectorAll('.img-uploader-multi').forEach(function (u) {
        var input = u.querySelector('.img-input');
        var drop = u.querySelector('.img-drop');
        var grid = u.querySelector('.img-grid');
        var fileLabel = u.querySelector('.img-drop-file');
        if (!input || !drop || !grid) { return; }

        function render(files) {
            grid.innerHTML = '';
            var n = 0;
            [].forEach.call(files, function (f) {
                if (!f.type || f.type.indexOf('image/') !== 0) { return; }
                n++;
                var d = document.createElement('div');
                d.className = 'img-grid-item';
                d.style.backgroundImage = "url('" + URL.createObjectURL(f) + "')";
                grid.appendChild(d);
            });
            fileLabel.textContent = n ? (n + (n === 1 ? ' imagen seleccionada' : ' imágenes seleccionadas')) : '';
        }

        input.addEventListener('change', function () { render(input.files); });
        ['dragenter', 'dragover'].forEach(function (ev) {
            drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('drag'); });
        });
        ['dragleave', 'dragend'].forEach(function (ev) {
            drop.addEventListener(ev, function () { drop.classList.remove('drag'); });
        });
        drop.addEventListener('drop', function (e) {
            e.preventDefault();
            drop.classList.remove('drag');
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                render(input.files);
            }
        });
    });
})();
</script>
@endsection
