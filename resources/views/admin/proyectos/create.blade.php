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
    .image-preview { max-width: 150px; max-height: 150px; border-radius: 10px; margin-top: 10px; }
    .hint { color: #9CA3AF; font-size: 0.825rem; margin-top: 4px; display: block; }
    .hint code { background: rgba(255,255,255,0.06); padding: 1px 6px; border-radius: 4px; font-size: 12px; color: #f7a831; }
    .char-counter { color: #6c757d; font-size: 0.8rem; font-family: ui-monospace, "JetBrains Mono", monospace; text-align: right; display: block; margin-top: 4px; }
    .char-counter.good { color: #10b981; }
    .char-counter.warn { color: #f7a831; }
    .char-counter.bad { color: #ef4444; }

    /* SERP Preview */
    .serp-preview {
        background: #FFFFFF;
        color: #202124;
        padding: 16px 20px;
        border-radius: 8px;
        font-family: arial, sans-serif;
        margin-top: 12px;
        max-width: 600px;
    }
    .serp-preview-url { color: #202124; font-size: 14px; }
    .serp-preview-title { color: #1a0dab; font-size: 20px; line-height: 1.3; margin: 4px 0 8px; cursor: pointer; }
    .serp-preview-title:hover { text-decoration: underline; }
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
                <h2 class="mb-1">➕ Crear Nuevo Proyecto</h2>
                <p class="text-light mb-0">Optimizado para SEO senior: title, schema, OG, Twitter, autoría — todo cubierto.</p>
            </div>
            <a href="{{ route('admin.proyectos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
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

        <form action="{{ route('admin.proyectos.store') }}" method="POST" enctype="multipart/form-data" id="proyectoForm">
            @csrf

            {{-- ===========================================================
                 1. INFORMACIÓN BÁSICA
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Información Básica</h4>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nombre">Nombre del Proyecto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               value="{{ old('nombre') }}" required maxlength="255">
                        <span class="hint">Nombre comercial. Aparece en cards, header del case y schema. Ej: <code>Nuvion Glass</code></span>
                    </div>
                    <div class="col-md-4">
                        <label for="slug">URL slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                               value="{{ old('slug') }}" placeholder="se-genera-automaticamente"
                               pattern="[a-z0-9\-]+" maxlength="255">
                        <span class="hint">URL final: <code>/proyectos/<span id="slugPreview">tu-slug</span></code></span>
                    </div>

                    <div class="col-md-6">
                        <label for="pais">País <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pais" name="pais"
                               value="{{ old('pais') }}" required maxlength="100"
                               placeholder="Colombia">
                    </div>
                    <div class="col-md-2">
                        <label for="bandera_emoji">Bandera <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bandera_emoji" name="bandera_emoji"
                               value="{{ old('bandera_emoji', '🇨🇴') }}" required maxlength="10">
                    </div>
                    <div class="col-md-4">
                        <label for="badge_text">Badge / Tagline <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="badge_text" name="badge_text"
                               value="{{ old('badge_text') }}" required maxlength="255"
                               placeholder="E-commerce SaaS">
                        <span class="hint">Se muestra como pill en la card.</span>
                    </div>

                    <div class="col-12">
                        <label for="descripcion">Descripción Corta <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                  required maxlength="500"
                                  placeholder="Plataforma e-commerce a medida en Laravel con pagos Stripe y SEO técnico para mercado mexicano.">{{ old('descripcion') }}</textarea>
                        <span class="hint">Texto mostrado en las cards del portafolio. Máx 500 chars.</span>
                    </div>

                    <div class="col-md-6">
                        <label for="url">URL del proyecto en vivo</label>
                        <input type="url" class="form-control" id="url" name="url"
                               value="{{ old('url') }}" placeholder="https://nuvionglass.com">
                    </div>
                    <div class="col-md-6">
                        <label for="tecnologias">Tecnologías (separadas por coma) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="tecnologias" name="tecnologias"
                               value="{{ old('tecnologias') }}" required
                               placeholder="Laravel, PHP, MySQL, Stripe, Tailwind">
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 2. CATEGORIZACIÓN & CLIENTE
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-tags me-2"></i>Categorización & Cliente</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="categoria">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">— Selecciona —</option>
                            @foreach(['ecommerce','admin','tech','automation','travel','booking','restaurant','legal'] as $cat)
                                <option value="{{ $cat }}" {{ old('categoria') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="industria">Industria / Vertical</label>
                        <input type="text" class="form-control" id="industria" name="industria"
                               value="{{ old('industria') }}" maxlength="120"
                               placeholder="Retail, Salud, Logística…">
                        <span class="hint">Para rich snippets y AI search.</span>
                    </div>
                    <div class="col-md-4">
                        <label for="client_size">Tamaño del cliente</label>
                        <select class="form-select" id="client_size" name="client_size">
                            <option value="">— Sin especificar —</option>
                            @foreach(['startup'=>'Startup','pyme'=>'PyME','empresa'=>'Empresa','enterprise'=>'Enterprise'] as $k=>$v)
                                <option value="{{ $k }}" {{ old('client_size') === $k ? 'selected' : '' }}>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="estado">Estado <span class="text-danger">*</span></label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="en_vivo" {{ old('estado', 'en_vivo') === 'en_vivo' ? 'selected' : '' }}>🟢 En Vivo</option>
                            <option value="en_desarrollo" {{ old('estado') === 'en_desarrollo' ? 'selected' : '' }}>🟡 En Desarrollo</option>
                            <option value="pausado" {{ old('estado') === 'pausado' ? 'selected' : '' }}>⚪ Pausado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="orden">Orden</label>
                        <input type="number" class="form-control" id="orden" name="orden"
                               value="{{ old('orden', 0) }}" min="0">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="destacado" name="destacado" value="1" {{ old('destacado') ? 'checked' : '' }}>
                            <label class="form-check-label" for="destacado">⭐ Destacado</label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">✅ Activo</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 3. LOGO
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-image me-2"></i>Logo del Proyecto</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="logo">Archivo del logo</label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        <span class="hint">JPG, PNG, SVG, WEBP. Máx 2MB. Idealmente 400×400 px.</span>
                    </div>
                    <div class="col-md-6">
                        <label for="alt_logo">Alt text del logo <span class="seo-badge">SEO</span></label>
                        <input type="text" class="form-control" id="alt_logo" name="alt_logo"
                               value="{{ old('alt_logo') }}" maxlength="255"
                               placeholder="Logo de Nuvion Glass — e-commerce">
                        <span class="hint">Accesibilidad + SEO de imágenes. Ej: "Logo de [marca] — [descriptor]".</span>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 4. SEO ESENCIAL
                 =========================================================== --}}
            <div class="form-section seo-section">
                <h4 class="mb-3"><i class="fas fa-search me-2"></i>SEO Esencial <span class="seo-badge">Core</span></h4>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="focus_keyword">Focus keyword (1 sola)</label>
                        <input type="text" class="form-control" id="focus_keyword" name="focus_keyword"
                               value="{{ old('focus_keyword') }}" maxlength="120"
                               placeholder="e-commerce a medida mexico">
                        <span class="hint">Keyword principal donde quieres rankear. Específica, no genérica.</span>
                    </div>
                    <div class="col-md-6">
                        <label for="secondary_keywords">Keywords secundarias (separadas por coma)</label>
                        <input type="text" class="form-control" id="secondary_keywords" name="secondary_keywords"
                               value="{{ old('secondary_keywords') }}"
                               placeholder="tienda online laravel, stripe colombia, ecommerce custom">
                        <span class="hint">Long-tail variations. Se guardan como array.</span>
                    </div>

                    <div class="col-12">
                        <label for="meta_title">Meta Title <span class="seo-badge">SEO</span></label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                               value="{{ old('meta_title') }}" maxlength="150"
                               placeholder="Nuvion Glass — E-commerce a medida en Laravel | MY Tech">
                        <span class="char-counter" id="counter_meta_title">0 / 60 chars (ideal: 50–60)</span>
                    </div>

                    <div class="col-12">
                        <label for="meta_description">Meta Description <span class="seo-badge">SEO</span></label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="2"
                                  maxlength="300"
                                  placeholder="Plataforma e-commerce a medida con Stripe, SEO técnico y panel de admin. Construida para [cliente] en México. Cotiza tu proyecto.">{{ old('meta_description') }}</textarea>
                        <span class="char-counter" id="counter_meta_description">0 / 160 chars (ideal: 150–160)</span>
                    </div>

                    <div class="col-12">
                        <label for="excerpt">Excerpt / Resumen corto</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2"
                                  maxlength="500"
                                  placeholder="Resumen 1-2 líneas para listings, redes y AI search.">{{ old('excerpt') }}</textarea>
                        <span class="hint">Fallback de meta_description si está vacío. Útil para AI search citation.</span>
                    </div>

                    <div class="col-md-6">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                               value="{{ old('canonical_url') }}" maxlength="500"
                               placeholder="https://mytechsolutionsco.com/proyectos/nuvion-glass">
                        <span class="hint">Si está vacío, se usa la URL del proyecto detalle automáticamente.</span>
                    </div>
                    <div class="col-md-6">
                        <label for="robots">Robots</label>
                        <select class="form-select" id="robots" name="robots">
                            @foreach([
                                'index,follow' => '✅ index, follow (default)',
                                'noindex,follow' => '🚫 noindex, follow',
                                'index,nofollow' => '⚠️ index, nofollow',
                                'noindex,nofollow' => '❌ noindex, nofollow'
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ old('robots', 'index,follow') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="text-warning">📺 Vista previa en Google (SERP)</label>
                        <div class="serp-preview">
                            <div class="serp-preview-url" id="serpUrl">mytechsolutionsco.com › proyectos › <span id="serpSlug">tu-slug</span></div>
                            <div class="serp-preview-title" id="serpTitle">Nombre del proyecto — MY Tech Solutions</div>
                            <div class="serp-preview-desc" id="serpDesc">La meta description aparecerá aquí. Mantén entre 150–160 caracteres.</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label for="meta_keywords">Meta Keywords (legacy)</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                               value="{{ old('meta_keywords') }}" maxlength="255"
                               placeholder="ecommerce, laravel, stripe, mexico">
                        <span class="hint">Google ya no lo usa, pero Bing/Yandex sí. Lo mantenemos por completitud.</span>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 5. OPEN GRAPH (Facebook / LinkedIn / WhatsApp)
                 =========================================================== --}}
            <div class="form-section social-section">
                <h4 class="mb-3"><i class="fab fa-facebook me-2"></i>Open Graph <span class="seo-badge" style="background:#1877F2;color:#fff;">Social</span></h4>
                <p class="hint mb-3">Lo que ven cuando alguien comparte la URL en Facebook, LinkedIn o WhatsApp.</p>

                <div class="row g-3">
                    <div class="col-12">
                        <label for="og_title">OG Title</label>
                        <input type="text" class="form-control" id="og_title" name="og_title"
                               value="{{ old('og_title') }}" maxlength="150"
                               placeholder="Si está vacío, usa el meta_title">
                    </div>
                    <div class="col-12">
                        <label for="og_description">OG Description</label>
                        <textarea class="form-control" id="og_description" name="og_description" rows="2"
                                  maxlength="300"
                                  placeholder="Si está vacío, usa la meta_description">{{ old('og_description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="og_image">OG Image (1200×630 ideal)</label>
                        <input type="file" class="form-control" id="og_image" name="og_image" accept="image/*">
                        <span class="hint">Imagen para social shares. Si no subes, usa el logo.</span>
                    </div>
                    <div class="col-md-6">
                        <label for="alt_og_image">Alt text del OG Image</label>
                        <input type="text" class="form-control" id="alt_og_image" name="alt_og_image"
                               value="{{ old('alt_og_image') }}" maxlength="255"
                               placeholder="Captura del dashboard de Nuvion Glass">
                    </div>

                    <div class="col-md-6">
                        <label for="og_type">OG Type</label>
                        <select class="form-select" id="og_type" name="og_type">
                            @foreach(['article' => 'article (caso de estudio)', 'website' => 'website', 'product' => 'product'] as $val => $label)
                                <option value="{{ $val }}" {{ old('og_type', 'article') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 6. TWITTER CARDS
                 =========================================================== --}}
            <div class="form-section social-section" style="border-color:#1DA1F2;">
                <h4 class="mb-3" style="color:#1DA1F2 !important;"><i class="fab fa-twitter me-2"></i>Twitter / X Cards <span class="seo-badge" style="background:#1DA1F2;color:#fff;">Social</span></h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="twitter_card">Tipo de card</label>
                        <select class="form-select" id="twitter_card" name="twitter_card">
                            @foreach(['summary_large_image'=>'Summary Large Image (recomendado)', 'summary'=>'Summary', 'app'=>'App', 'player'=>'Player'] as $val => $label)
                                <option value="{{ $val }}" {{ old('twitter_card', 'summary_large_image') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="twitter_image">Twitter Image (override)</label>
                        <input type="file" class="form-control" id="twitter_image" name="twitter_image" accept="image/*">
                        <span class="hint">Si está vacío, usa la OG Image.</span>
                    </div>

                    <div class="col-12">
                        <label for="twitter_title">Twitter Title</label>
                        <input type="text" class="form-control" id="twitter_title" name="twitter_title"
                               value="{{ old('twitter_title') }}" maxlength="150"
                               placeholder="Si está vacío, usa OG Title">
                    </div>
                    <div class="col-12">
                        <label for="twitter_description">Twitter Description</label>
                        <textarea class="form-control" id="twitter_description" name="twitter_description" rows="2"
                                  maxlength="300"
                                  placeholder="Si está vacío, usa OG Description">{{ old('twitter_description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 7. SCHEMA.ORG / Structured Data
                 =========================================================== --}}
            <div class="form-section schema-section">
                <h4 class="mb-3"><i class="fas fa-code me-2"></i>Schema.org Structured Data <span class="seo-badge" style="background:#8B5CF6;color:#fff;">Rich Results</span></h4>
                <p class="hint mb-3">JSON-LD para rich snippets en Google. Si dejas <code>schema_markup</code> vacío, se genera automático con el <code>schema_type</code>.</p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="schema_type">Schema Type</label>
                        <select class="form-select" id="schema_type" name="schema_type">
                            @foreach([
                                'CreativeWork' => 'CreativeWork (caso de estudio — default)',
                                'SoftwareApplication' => 'SoftwareApplication (SaaS / app)',
                                'Service' => 'Service (servicio)',
                                'Product' => 'Product (producto digital)',
                                'WebApplication' => 'WebApplication',
                                'MobileApplication' => 'MobileApplication',
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ old('schema_type', 'CreativeWork') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="schema_markup">Schema JSON-LD custom (override avanzado)</label>
                        <textarea class="form-control" id="schema_markup" name="schema_markup" rows="6"
                                  placeholder='Deja vacío para auto-generar. O pega tu JSON-LD custom:&#10;{"@context":"https://schema.org","@type":"SoftwareApplication","name":"..."}'
                                  style="font-family: ui-monospace, monospace; font-size: 0.85rem;">{{ old('schema_markup') }}</textarea>
                        <span class="hint">Si lo dejas vacío, la app genera schema automáticamente desde los otros campos.</span>
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 8. METADATA AVANZADA (Autoría, Fechas, Tiempo)
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-pen me-2"></i>Metadata Avanzada</h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="breadcrumb_title">Breadcrumb title (corto)</label>
                        <input type="text" class="form-control" id="breadcrumb_title" name="breadcrumb_title"
                               value="{{ old('breadcrumb_title') }}" maxlength="120"
                               placeholder="Si vacío usa el nombre">
                    </div>
                    <div class="col-md-4">
                        <label for="author">Autor del caso</label>
                        <input type="text" class="form-control" id="author" name="author"
                               value="{{ old('author', 'MY Tech Solutions') }}" maxlength="120">
                    </div>
                    <div class="col-md-4">
                        <label for="reading_time">Tiempo de lectura (min)</label>
                        <input type="number" class="form-control" id="reading_time" name="reading_time"
                               value="{{ old('reading_time') }}" min="1" max="120"
                               placeholder="5">
                    </div>

                    <div class="col-md-4">
                        <label for="publicado_en">Fecha publicación SEO</label>
                        <input type="date" class="form-control" id="publicado_en" name="publicado_en"
                               value="{{ old('publicado_en', date('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 9. RECURSOS EXTERNOS
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-external-link-alt me-2"></i>Recursos externos</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="case_study_url">URL externa del case study</label>
                        <input type="url" class="form-control" id="case_study_url" name="case_study_url"
                               value="{{ old('case_study_url') }}" maxlength="500"
                               placeholder="https://medium.com/...">
                    </div>
                    <div class="col-md-6">
                        <label for="video_url">URL del video del caso</label>
                        <input type="url" class="form-control" id="video_url" name="video_url"
                               value="{{ old('video_url') }}" maxlength="500"
                               placeholder="https://youtube.com/watch?v=...">
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 10. CONTENIDO EXTENDIDO (Case study completo)
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-book me-2"></i>Contenido Extendido del Caso</h4>
                <p class="hint mb-3">Estructura tipo case study — Google ama este patrón.</p>

                <div class="mb-3">
                    <label for="descripcion_extendida">Descripción Extendida</label>
                    <input type="hidden" name="descripcion_extendida" id="descripcion_extendida" value="{{ old('descripcion_extendida') }}">
                    <div id="editor_descripcion_extendida"></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="desafio">🎯 El Desafío</label>
                        <input type="hidden" name="desafio" id="desafio" value="{{ old('desafio') }}">
                        <div id="editor_desafio"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="solucion">💡 La Solución</label>
                        <input type="hidden" name="solucion" id="solucion" value="{{ old('solucion') }}">
                        <div id="editor_solucion"></div>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="resultados">📈 Resultados</label>
                    <input type="hidden" name="resultados" id="resultados" value="{{ old('resultados') }}">
                    <div id="editor_resultados"></div>
                </div>

                <div class="mt-3">
                    <label for="galeria">📸 Galería de imágenes</label>
                    <input type="file" class="form-control" id="galeria" name="galeria[]" accept="image/*" multiple>
                    <span class="hint">Selecciona varias imágenes para mostrar capturas/mockups del proyecto.</span>
                </div>

                <div class="mt-3">
                    <label for="galeria_alts">🔤 Alt text de galería <span class="seo-badge">SEO Imágenes</span></label>
                    <textarea class="form-control" id="galeria_alts" name="galeria_alts" rows="3"
                              placeholder="Un alt por línea, EN EL MISMO ORDEN que subiste las imágenes.&#10;Ej línea 1: Dashboard de Vinko mostrando el mapa de profesionales&#10;Ej línea 2: Pantalla de perfil del profesional en la app">{{ old('galeria_alts') }}</textarea>
                    <span class="hint">Describe cada imagen para posicionar en Google Imágenes. Si dejas una línea vacía, usa un alt genérico.</span>
                </div>
            </div>

            {{-- ===========================================================
                 10.5. FAQ (FAQPage schema)
                 =========================================================== --}}
            @include('admin.proyectos._faq_fields', ['faqData' => old('faq_pregunta') ? collect(old('faq_pregunta'))->map(fn($p, $i) => ['pregunta' => $p, 'respuesta' => old('faq_respuesta')[$i] ?? ''])->all() : []])

            {{-- ===========================================================
                 11. TESTIMONIO
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-quote-left me-2"></i>Testimonio del Cliente <span class="seo-badge">Schema</span></h4>
                <p class="hint mb-3">Eligible para schema <code>Review</code> — refuerza confianza y rankings.</p>

                <div class="mb-3">
                    <label for="testimonio">Quote del cliente</label>
                    <textarea class="form-control" id="testimonio" name="testimonio" rows="3"
                              placeholder="Trabajar con MY Tech transformó nuestra operación...">{{ old('testimonio') }}</textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="testimonio_autor">Autor del testimonio</label>
                        <input type="text" class="form-control" id="testimonio_autor" name="testimonio_autor"
                               value="{{ old('testimonio_autor') }}" maxlength="255"
                               placeholder="Juan Pérez">
                    </div>
                    <div class="col-md-6">
                        <label for="testimonio_cargo">Cargo / empresa</label>
                        <input type="text" class="form-control" id="testimonio_cargo" name="testimonio_cargo"
                               value="{{ old('testimonio_cargo') }}" maxlength="255"
                               placeholder="CEO en Nuvion Glass">
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 12. MÉTRICAS DEL PROYECTO
                 =========================================================== --}}
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-chart-line me-2"></i>Métricas del Proyecto</h4>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="duracion_desarrollo">Duración</label>
                        <input type="text" class="form-control" id="duracion_desarrollo" name="duracion_desarrollo"
                               value="{{ old('duracion_desarrollo') }}" maxlength="100"
                               placeholder="3 meses">
                    </div>
                    <div class="col-md-3">
                        <label for="equipo_size">Tamaño equipo</label>
                        <input type="number" class="form-control" id="equipo_size" name="equipo_size"
                               value="{{ old('equipo_size') }}" min="1">
                    </div>
                    <div class="col-md-3">
                        <label for="fecha_lanzamiento">Fecha lanzamiento</label>
                        <input type="date" class="form-control" id="fecha_lanzamiento" name="fecha_lanzamiento"
                               value="{{ old('fecha_lanzamiento') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="visitas_mensuales">Visitas / mes</label>
                        <input type="number" class="form-control" id="visitas_mensuales" name="visitas_mensuales"
                               value="{{ old('visitas_mensuales') }}" min="0"
                               placeholder="15000">
                    </div>
                </div>
            </div>

            {{-- ===========================================================
                 SUBMIT
                 =========================================================== --}}
            <div class="d-flex gap-2 justify-content-end mb-5">
                <a href="{{ route('admin.proyectos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Crear Proyecto
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quill.js JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
(function () {
    // ── Quill editors ─────────────────────────────────────────────
    const editorTargets = ['descripcion_extendida','desafio','solucion','resultados'];
    const editors = {};
    editorTargets.forEach(name => {
        const container = document.getElementById('editor_' + name);
        const hidden = document.getElementById(name);
        if (!container || !hidden) return;
        const q = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{header: [1,2,3,false]}],
                    ['bold','italic','underline','strike'],
                    [{list:'ordered'}, {list:'bullet'}],
                    ['link','blockquote','code-block'],
                    ['clean'],
                ],
            },
            placeholder: 'Escribe aquí…',
        });
        if (hidden.value) q.root.innerHTML = hidden.value;
        q.on('text-change', () => { hidden.value = q.root.innerHTML; });
        editors[name] = q;
    });

    // ── Auto-slug desde nombre ────────────────────────────────────
    const nombreEl = document.getElementById('nombre');
    const slugEl   = document.getElementById('slug');
    const slugPrev = document.getElementById('slugPreview');
    const serpSlug = document.getElementById('serpSlug');

    function slugify(text) {
        return (text || '').toString()
            .normalize('NFD').replace(/[̀-ͯ]/g, '')
            .toLowerCase()
            .replace(/[^a-z0-9\s\-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
    let slugTouched = false;
    slugEl.addEventListener('input', () => { slugTouched = true; updateSlugPreview(); });
    nombreEl.addEventListener('input', () => {
        if (!slugTouched) {
            slugEl.value = slugify(nombreEl.value);
            updateSlugPreview();
        }
        updateSerpPreview();
    });
    function updateSlugPreview() {
        const s = slugEl.value || slugify(nombreEl.value) || 'tu-slug';
        if (slugPrev) slugPrev.textContent = s;
        if (serpSlug) serpSlug.textContent = s;
    }

    // ── Character counters ────────────────────────────────────────
    function counter(inputId, counterId, ideal, max) {
        const inp = document.getElementById(inputId);
        const cnt = document.getElementById(counterId);
        if (!inp || !cnt) return;
        const update = () => {
            const len = inp.value.length;
            cnt.textContent = `${len} / ${ideal} chars (ideal: ${ideal-10}–${ideal})`;
            cnt.className = 'char-counter';
            if (len === 0)          cnt.classList.add('');
            else if (len < ideal-15) cnt.classList.add('warn');
            else if (len <= ideal)   cnt.classList.add('good');
            else                     cnt.classList.add('bad');
        };
        inp.addEventListener('input', update);
        update();
    }
    counter('meta_title', 'counter_meta_title', 60, 150);
    counter('meta_description', 'counter_meta_description', 160, 300);

    // ── SERP Preview en vivo ──────────────────────────────────────
    const serpTitle = document.getElementById('serpTitle');
    const serpDesc  = document.getElementById('serpDesc');
    const metaTitleEl = document.getElementById('meta_title');
    const metaDescEl  = document.getElementById('meta_description');
    function updateSerpPreview() {
        if (serpTitle) serpTitle.textContent = metaTitleEl.value || (nombreEl.value ? nombreEl.value + ' — MY Tech Solutions' : 'Nombre del proyecto — MY Tech Solutions');
        if (serpDesc)  serpDesc.textContent  = metaDescEl.value  || 'La meta description aparecerá aquí. Mantén entre 150–160 caracteres.';
    }
    [metaTitleEl, metaDescEl, nombreEl].forEach(el => el && el.addEventListener('input', updateSerpPreview));
    updateSerpPreview();
    updateSlugPreview();

    // ── Auto-rellenado social (al perder foco si están vacíos) ────
    function bindAutoFill(srcId, ...targetIds) {
        const src = document.getElementById(srcId);
        if (!src) return;
        src.addEventListener('blur', () => {
            targetIds.forEach(tid => {
                const tgt = document.getElementById(tid);
                if (tgt && !tgt.value.trim()) tgt.value = src.value;
            });
        });
    }
    bindAutoFill('meta_title', 'og_title', 'twitter_title');
    bindAutoFill('meta_description', 'og_description', 'twitter_description');
    bindAutoFill('descripcion', 'excerpt');
})();
</script>
@endsection
