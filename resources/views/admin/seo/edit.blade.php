{{-- resources/views/admin/seo/edit.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .section-card { background: #2a3441; border: 1px solid #00A9E0; border-radius: 8px; margin-bottom: 25px; }
    .section-header { background: #1a252f; padding: 15px; border-bottom: 1px solid rgba(0, 169, 224, 0.3); }
    .section-body { padding: 20px; }
    .form-control, .form-select, .form-control:focus { background: #101820; border: 1px solid #00A9E0; color: #FCFAF1; }
    .form-control:focus { border-color: #f7a831; box-shadow: 0 0 0 0.2rem rgba(247, 168, 49, 0.25); }
    .btn-success { background-color: #00A9E0; border-color: #00A9E0; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    h2, h4 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .badge-meta { background-color: #f7a831; }
    .badge-og { background-color: #4267B2; }
    .badge-twitter { background-color: #1DA1F2; }
    .badge-schema { background-color: #28a745; }
    .badge-sitemap { background-color: #6f42c1; }
    .field-group { background: rgba(0, 169, 224, 0.05); border: 1px solid rgba(0, 169, 224, 0.2); border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .field-group h6 { color: #00A9E0; margin-bottom: 15px; }
    .form-text { color: rgba(252, 250, 241, 0.7) !important; }
    .char-counter { font-size: 0.8rem; color: #f7a831; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-search"></i> SEO: {{ ucfirst(str_replace('-', ' ', $page->slug)) }}</h2>
                <p class="text-light mb-0">Configura meta tags, Open Graph y Schema.org para mejorar el posicionamiento</p>
            </div>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.seo.update', $page->id) }}" method="POST">
            @csrf @method('PUT')

            {{-- META TAGS BÁSICOS --}}
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-tags me-2"></i> Meta Tags Básicos <span class="badge badge-meta ms-2">Meta</span></h4>
                </div>
                <div class="section-body">
                    <div class="field-group">
                        <h6><i class="fas fa-heading"></i> Título SEO</h6>
                        <input type="text" name="meta_title" class="form-control" 
                               value="{{ old('meta_title', $seo->meta_title) }}" 
                               maxlength="150" placeholder="Título optimizado para SEO (50-60 caracteres)">
                        <div class="form-text">Aparece como título principal en Google. <span class="char-counter" id="title-counter">0/150</span></div>
                    </div>

                    <div class="field-group">
                        <h6><i class="fas fa-align-left"></i> Meta Descripción</h6>
                        <textarea name="meta_description" class="form-control" rows="3" 
                                  maxlength="500" placeholder="Descripción que aparece en Google (120-155 caracteres)">{{ old('meta_description', $seo->meta_description) }}</textarea>
                        <div class="form-text">Resumen atractivo para resultados de búsqueda. <span class="char-counter" id="desc-counter">0/500</span></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-key"></i> Palabras Clave</h6>
                            <input type="text" name="meta_keywords" class="form-control" 
                                   value="{{ old('meta_keywords', $seo->meta_keywords) }}" 
                                   placeholder="palabra1, palabra2, palabra3">
                            <div class="form-text">Separadas por comas</div>
                        </div>
                        
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-link"></i> URL Canónica</h6>
                            <input type="url" name="canonical_url" class="form-control" 
                                   value="{{ old('canonical_url', $seo->canonical_url) }}" 
                                   placeholder="https://electrahome.com/servicios">
                            <div class="form-text">URL principal de esta página</div>
                        </div>
                    </div>

                    <div class="field-group">
                        <h6><i class="fas fa-robot"></i> Directivas de Robots</h6>
                        <select name="robots" class="form-select">
                            <option value="index,follow" {{ old('robots', $seo->robots) == 'index,follow' ? 'selected' : '' }}>index, follow (Recomendado)</option>
                            <option value="noindex,follow" {{ old('robots', $seo->robots) == 'noindex,follow' ? 'selected' : '' }}>noindex, follow</option>
                            <option value="index,nofollow" {{ old('robots', $seo->robots) == 'index,nofollow' ? 'selected' : '' }}>index, nofollow</option>
                            <option value="noindex,nofollow" {{ old('robots', $seo->robots) == 'noindex,nofollow' ? 'selected' : '' }}>noindex, nofollow</option>
                        </select>
                        <div class="form-text">Controla cómo los buscadores indexan esta página</div>
                    </div>
                </div>
            </div>

            {{-- OPEN GRAPH (FACEBOOK) --}}
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fab fa-facebook me-2"></i> Open Graph (Facebook/LinkedIn) <span class="badge badge-og ms-2">OG</span></h4>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-heading"></i> Título OG</h6>
                            <input type="text" name="og_title" class="form-control" 
                                   value="{{ old('og_title', $seo->og_title) }}" 
                                   maxlength="150" placeholder="Título para redes sociales">
                            <div class="form-text">Si está vacío, usa el meta title</div>
                        </div>
                        
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-image"></i> Imagen OG</h6>
                            <input type="url" name="og_image" class="form-control" 
                                   value="{{ old('og_image', $seo->og_image) }}" 
                                   placeholder="https://electrahome.com/images/og-image.jpg">
                            <div class="form-text">1200x630px recomendado</div>
                        </div>
                    </div>

                    <div class="field-group">
                        <h6><i class="fas fa-align-left"></i> Descripción OG</h6>
                        <textarea name="og_description" class="form-control" rows="2" 
                                  placeholder="Descripción para redes sociales">{{ old('og_description', $seo->og_description) }}</textarea>
                        <div class="form-text">Si está vacía, usa la meta descripción</div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-type"></i> Tipo OG</h6>
                            <select name="og_type" class="form-select">
                                <option value="website" {{ old('og_type', $seo->og_type) == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="article" {{ old('og_type', $seo->og_type) == 'article' ? 'selected' : '' }}>Article</option>
                                <option value="product" {{ old('og_type', $seo->og_type) == 'product' ? 'selected' : '' }}>Product</option>
                                <option value="business.business" {{ old('og_type', $seo->og_type) == 'business.business' ? 'selected' : '' }}>Business</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-link"></i> URL OG</h6>
                            <input type="url" name="og_url" class="form-control" 
                                   value="{{ old('og_url', $seo->og_url) }}" 
                                   placeholder="URL para compartir">
                            <div class="form-text">Si está vacía, usa la canónica</div>
                        </div>
                        
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-globe"></i> Nombre del Sitio</h6>
                            <input type="text" name="og_site_name" class="form-control" 
                                   value="{{ old('og_site_name', $seo->og_site_name ?: 'ElectraHome') }}" 
                                   placeholder="ElectraHome">
                        </div>
                    </div>
                </div>
            </div>

            {{-- TWITTER CARDS --}}
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fab fa-twitter me-2"></i> Twitter Cards <span class="badge badge-twitter ms-2">Twitter</span></h4>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-id-card"></i> Tipo de Card</h6>
                            <select name="twitter_card" class="form-select">
                                <option value="summary" {{ old('twitter_card', $seo->twitter_card) == 'summary' ? 'selected' : '' }}>Summary</option>
                                <option value="summary_large_image" {{ old('twitter_card', $seo->twitter_card) == 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                <option value="app" {{ old('twitter_card', $seo->twitter_card) == 'app' ? 'selected' : '' }}>App</option>
                                <option value="player" {{ old('twitter_card', $seo->twitter_card) == 'player' ? 'selected' : '' }}>Player</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-at"></i> Twitter Site</h6>
                            <input type="text" name="twitter_site" class="form-control" 
                                   value="{{ old('twitter_site', $seo->twitter_site) }}" 
                                   placeholder="@electrahome">
                            <div class="form-text">Usuario Twitter del sitio</div>
                        </div>
                        
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-user"></i> Twitter Creator</h6>
                            <input type="text" name="twitter_creator" class="form-control" 
                                   value="{{ old('twitter_creator', $seo->twitter_creator) }}" 
                                   placeholder="@creador">
                            <div class="form-text">Usuario Twitter del autor</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-heading"></i> Título Twitter</h6>
                            <input type="text" name="twitter_title" class="form-control" 
                                   value="{{ old('twitter_title', $seo->twitter_title) }}" 
                                   placeholder="Título para Twitter">
                            <div class="form-text">Si está vacío, usa el OG title</div>
                        </div>
                        
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-image"></i> Imagen Twitter</h6>
                            <input type="url" name="twitter_image" class="form-control" 
                                   value="{{ old('twitter_image', $seo->twitter_image) }}" 
                                   placeholder="URL de imagen para Twitter">
                        </div>
                    </div>

                    <div class="field-group">
                        <h6><i class="fas fa-align-left"></i> Descripción Twitter</h6>
                        <textarea name="twitter_description" class="form-control" rows="2" 
                                  placeholder="Descripción para Twitter">{{ old('twitter_description', $seo->twitter_description) }}</textarea>
                        <div class="form-text">Si está vacía, usa la OG description</div>
                    </div>
                </div>
            </div>

            {{-- SEO ADICIONAL --}}
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-cog me-2"></i> Configuración Adicional <span class="badge badge-sitemap ms-2">Extra</span></h4>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-bullseye"></i> Palabra Clave Principal</h6>
                            <input type="text" name="focus_keyword" class="form-control" 
                                   value="{{ old('focus_keyword', $seo->focus_keyword) }}" 
                                   placeholder="reparación electrodomésticos quito">
                            <div class="form-text">Palabra clave por la que quieres posicionar</div>
                        </div>
                        
                        <div class="col-md-6 field-group">
                            <h6><i class="fas fa-map-signs"></i> Título Breadcrumb</h6>
                            <input type="text" name="breadcrumb_title" class="form-control" 
                                   value="{{ old('breadcrumb_title', $seo->breadcrumb_title) }}" 
                                   placeholder="Título personalizado para navegación">
                            <div class="form-text">Si está vacío, usa el título de la página</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-sitemap"></i> Incluir en Sitemap</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="sitemap_include" value="1" 
                                       {{ old('sitemap_include', $seo->sitemap_include ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label">Incluir en sitemap.xml</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-sort-numeric-up"></i> Prioridad Sitemap</h6>
                            <select name="sitemap_priority" class="form-select">
                                <option value="1.0" {{ old('sitemap_priority', $seo->sitemap_priority) == '1.0' ? 'selected' : '' }}>1.0 (Muy Alta)</option>
                                <option value="0.9" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.9' ? 'selected' : '' }}>0.9 (Alta)</option>
                                <option value="0.8" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.8' ? 'selected' : '' }}>0.8 (Normal)</option>
                                <option value="0.7" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.7' ? 'selected' : '' }}>0.7 (Media)</option>
                                <option value="0.5" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.5' ? 'selected' : '' }}>0.5 (Baja)</option>
                                <option value="0.3" {{ old('sitemap_priority', $seo->sitemap_priority) == '0.3' ? 'selected' : '' }}>0.3 (Muy Baja)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 field-group">
                            <h6><i class="fas fa-clock"></i> Frecuencia de Cambio</h6>
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

                    <div class="field-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                   {{ old('is_active', $seo->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                <strong>SEO Activo</strong> - Aplicar esta configuración SEO
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save me-2"></i> Guardar Configuración SEO
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Contador de caracteres para meta title
document.querySelector('input[name="meta_title"]').addEventListener('input', function() {
    const counter = document.getElementById('title-counter');
    const length = this.value.length;
    counter.textContent = `${length}/150`;
    counter.style.color = length > 60 ? '#dc3545' : length > 50 ? '#f7a831' : '#28a745';
});

// Contador de caracteres para meta description
document.querySelector('textarea[name="meta_description"]').addEventListener('input', function() {
    const counter = document.getElementById('desc-counter');
    const length = this.value.length;
    counter.textContent = `${length}/500`;
    counter.style.color = length > 155 ? '#dc3545' : length > 120 ? '#f7a831' : '#28a745';
});

// Trigger inicial para contadores
document.querySelector('input[name="meta_title"]').dispatchEvent(new Event('input'));
document.querySelector('textarea[name="meta_description"]').dispatchEvent(new Event('input'));

// Prevenir submit múltiple
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
});
</script>
@endsection