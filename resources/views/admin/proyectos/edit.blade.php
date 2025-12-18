@extends('layouts.app_admin')

@section('content')
<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .form-section { background: #2a3441; border: 1px solid #00A9E0; border-radius: 8px; padding: 25px; margin-bottom: 20px; }
    .form-control, .form-select, textarea { background: #101820; border: 1px solid #00A9E0; color: #FCFAF1; }
    .form-control:focus, .form-select:focus, textarea:focus { background: #101820; border-color: #f7a831; color: #FCFAF1; box-shadow: 0 0 0 0.2rem rgba(247, 168, 49, 0.25); }
    .btn-primary { background-color: #00A9E0; border-color: #00A9E0; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    h2, h4 { color: #00A9E0 !important; }
    label { color: #FCFAF1; font-weight: 600; margin-bottom: 8px; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .invalid-feedback { display: block; }
    .image-preview { max-width: 150px; max-height: 150px; border-radius: 10px; margin-top: 10px; background: white; padding: 5px; }

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
</style>

<div class="main-content">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">✏️ Editar Proyecto</h2>
                <p class="text-light mb-0">{{ $proyecto->nombre }}</p>
            </div>
            <a href="{{ route('admin.proyectos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <form action="{{ route('admin.proyectos.update', $proyecto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Información Básica -->
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-info-circle me-2"></i>Información Básica</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre del Proyecto *</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre" name="nombre" value="{{ old('nombre', $proyecto->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="pais" class="form-label">País *</label>
                        <input type="text" class="form-control @error('pais') is-invalid @enderror"
                               id="pais" name="pais" value="{{ old('pais', $proyecto->pais) }}" required>
                        @error('pais')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="bandera_emoji" class="form-label">Bandera Emoji *</label>
                        <input type="text" class="form-control @error('bandera_emoji') is-invalid @enderror"
                               id="bandera_emoji" name="bandera_emoji" value="{{ old('bandera_emoji', $proyecto->bandera_emoji) }}" required>
                        @error('bandera_emoji')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="categoria" class="form-label">Categoría *</label>
                        <select class="form-select @error('categoria') is-invalid @enderror"
                                id="categoria" name="categoria" required>
                            <option value="">Selecciona una categoría</option>
                            <option value="travel" {{ old('categoria', $proyecto->categoria) == 'travel' ? 'selected' : '' }}>🌍 Viajes & Movilidad</option>
                            <option value="booking" {{ old('categoria', $proyecto->categoria) == 'booking' ? 'selected' : '' }}>🏨 Reservas & Booking</option>
                            <option value="restaurant" {{ old('categoria', $proyecto->categoria) == 'restaurant' ? 'selected' : '' }}>🍽️ Gastronomía</option>
                            <option value="admin" {{ old('categoria', $proyecto->categoria) == 'admin' ? 'selected' : '' }}>⚙️ Gestión & Admin</option>
                            <option value="legal" {{ old('categoria', $proyecto->categoria) == 'legal' ? 'selected' : '' }}>⚖️ Legal & Corporativo</option>
                            <option value="tech" {{ old('categoria', $proyecto->categoria) == 'tech' ? 'selected' : '' }}>💻 Tecnología</option>
                            <option value="ecommerce" {{ old('categoria', $proyecto->categoria) == 'ecommerce' ? 'selected' : '' }}>🛒 E-commerce a medida / Plataforma Web Personalizada</option>
                        </select>
                        @error('categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="badge_text" class="form-label">Texto del Badge *</label>
                        <input type="text" class="form-control @error('badge_text') is-invalid @enderror"
                               id="badge_text" name="badge_text" value="{{ old('badge_text', $proyecto->badge_text) }}" required>
                        @error('badge_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción *</label>
                    <div id="descripcion-editor" style="min-height: 150px;"></div>
                    <textarea class="form-control d-none @error('descripcion') is-invalid @enderror"
                              id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion', $proyecto->descripcion) }}</textarea>
                    @error('descripcion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Breve descripción del proyecto - Usa el editor para formato</small>
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label">URL del Proyecto</label>
                    <input type="url" class="form-control @error('url') is-invalid @enderror"
                           id="url" name="url" value="{{ old('url', $proyecto->url) }}">
                    @error('url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Logo y Tecnologías -->
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-image me-2"></i>Logo y Tecnologías</h4>

                <div class="mb-3">
                    <label for="logo" class="form-label">Logo del Proyecto</label>

                    @if($proyecto->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $proyecto->logo) }}" alt="{{ $proyecto->nombre }}" class="image-preview">
                            <p class="text-muted small mt-1">Logo actual</p>
                        </div>
                    @endif

                    <input type="file" class="form-control @error('logo') is-invalid @enderror"
                           id="logo" name="logo" accept="image/*" onchange="previewImage(event)">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Sube un nuevo logo solo si quieres cambiarlo</small>
                    <div id="preview-container"></div>
                </div>

                <div class="mb-3">
                    <label for="tecnologias" class="form-label">Tecnologías *</label>
                    <input type="text" class="form-control @error('tecnologias') is-invalid @enderror"
                           id="tecnologias" name="tecnologias"
                           value="{{ old('tecnologias', is_array($proyecto->tecnologias) ? implode(', ', $proyecto->tecnologias) : $proyecto->tecnologias) }}" required>
                    @error('tecnologias')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Separa las tecnologías con comas</small>
                </div>
            </div>

            <!-- Estado y Opciones -->
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-cog me-2"></i>Estado y Opciones</h4>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="estado" class="form-label">Estado del Proyecto *</label>
                        <select class="form-select @error('estado') is-invalid @enderror"
                                id="estado" name="estado" required>
                            <option value="en_vivo" {{ old('estado', $proyecto->estado) == 'en_vivo' ? 'selected' : '' }}>🟢 En Vivo</option>
                            <option value="en_desarrollo" {{ old('estado', $proyecto->estado) == 'en_desarrollo' ? 'selected' : '' }}>🟡 En Desarrollo</option>
                            <option value="pausado" {{ old('estado', $proyecto->estado) == 'pausado' ? 'selected' : '' }}>⚫ Pausado</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="orden" class="form-label">Orden de Visualización</label>
                        <input type="number" class="form-control @error('orden') is-invalid @enderror"
                               id="orden" name="orden" value="{{ old('orden', $proyecto->orden) }}" min="0">
                        @error('orden')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Menor número = aparece primero</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label d-block">Opciones</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="destacado" name="destacado"
                                   value="1" {{ old('destacado', $proyecto->destacado) ? 'checked' : '' }}>
                            <label class="form-check-label" for="destacado">
                                ⭐ Destacado
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="activo" name="activo"
                                   value="1" {{ old('activo', $proyecto->activo) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activo">
                                ✅ Activo
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Extendido -->
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-file-alt me-2"></i>Contenido Extendido (Para página individual)</h4>
                <p class="text-muted small mb-4">Este contenido se mostrará en la página individual del proyecto</p>

                <div class="mb-3">
                    <label for="descripcion_extendida" class="form-label">Descripción Extendida</label>
                    <div id="descripcion_extendida-editor" style="min-height: 200px;"></div>
                    <textarea class="form-control d-none @error('descripcion_extendida') is-invalid @enderror"
                              id="descripcion_extendida" name="descripcion_extendida" rows="4">{{ old('descripcion_extendida', $proyecto->descripcion_extendida) }}</textarea>
                    @error('descripcion_extendida')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Descripción completa y detallada del proyecto - Usa el editor para formato</small>
                </div>

                <div class="mb-3">
                    <label for="desafio" class="form-label">El Desafío</label>
                    <div id="desafio-editor" style="min-height: 150px;"></div>
                    <textarea class="form-control d-none @error('desafio') is-invalid @enderror"
                              id="desafio" name="desafio" rows="3">{{ old('desafio', $proyecto->desafio) }}</textarea>
                    @error('desafio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">¿Qué problema resolvió este proyecto? - Usa el editor para formato</small>
                </div>

                <div class="mb-3">
                    <label for="solucion" class="form-label">La Solución</label>
                    <div id="solucion-editor" style="min-height: 150px;"></div>
                    <textarea class="form-control d-none @error('solucion') is-invalid @enderror"
                              id="solucion" name="solucion" rows="3">{{ old('solucion', $proyecto->solucion) }}</textarea>
                    @error('solucion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">¿Cómo se resolvió el problema? - Usa el editor para formato</small>
                </div>

                <div class="mb-3">
                    <label for="resultados" class="form-label">Resultados</label>
                    <div id="resultados-editor" style="min-height: 150px;"></div>
                    <textarea class="form-control d-none @error('resultados') is-invalid @enderror"
                              id="resultados" name="resultados" rows="3">{{ old('resultados', $proyecto->resultados) }}</textarea>
                    @error('resultados')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Métricas, logros o impacto del proyecto - Usa el editor para formato</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="duracion_desarrollo" class="form-label">Duración del Desarrollo</label>
                        <input type="text" class="form-control @error('duracion_desarrollo') is-invalid @enderror"
                               id="duracion_desarrollo" name="duracion_desarrollo" value="{{ old('duracion_desarrollo', $proyecto->duracion_desarrollo) }}"
                               placeholder="Ej: 3 meses">
                        @error('duracion_desarrollo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="equipo_size" class="form-label">Tamaño del Equipo</label>
                        <input type="number" class="form-control @error('equipo_size') is-invalid @enderror"
                               id="equipo_size" name="equipo_size" value="{{ old('equipo_size', $proyecto->equipo_size) }}"
                               placeholder="Ej: 3" min="1">
                        @error('equipo_size')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_lanzamiento" class="form-label">Fecha de Lanzamiento</label>
                        <input type="date" class="form-control @error('fecha_lanzamiento') is-invalid @enderror"
                               id="fecha_lanzamiento" name="fecha_lanzamiento"
                               value="{{ old('fecha_lanzamiento', $proyecto->fecha_lanzamiento ? $proyecto->fecha_lanzamiento->format('Y-m-d') : '') }}">
                        @error('fecha_lanzamiento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="visitas_mensuales" class="form-label">Visitas Mensuales</label>
                        <input type="number" class="form-control @error('visitas_mensuales') is-invalid @enderror"
                               id="visitas_mensuales" name="visitas_mensuales" value="{{ old('visitas_mensuales', $proyecto->visitas_mensuales) }}"
                               placeholder="Ej: 50000" min="0">
                        @error('visitas_mensuales')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="galeria" class="form-label">Galería de Imágenes</label>

                    @if($proyecto->galeria && count($proyecto->galeria) > 0)
                        <div class="mb-2 d-flex flex-wrap gap-2">
                            @foreach($proyecto->galeria as $imagen)
                                <img src="{{ asset('storage/' . $imagen) }}" alt="Galería" class="image-preview" style="max-width: 100px; max-height: 100px;">
                            @endforeach
                        </div>
                        <p class="text-muted small">Imágenes actuales de la galería</p>
                    @endif

                    <input type="file" class="form-control @error('galeria.*') is-invalid @enderror"
                           id="galeria" name="galeria[]" accept="image/*" multiple onchange="previewGallery(event)">
                    @error('galeria.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Selecciona nuevas imágenes para reemplazar la galería completa (máx 2MB c/u)</small>
                    <div id="gallery-preview-container" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>
            </div>

            <!-- Testimonio -->
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-quote-right me-2"></i>Testimonio del Cliente</h4>

                <div class="mb-3">
                    <label for="testimonio" class="form-label">Testimonio</label>
                    <textarea class="form-control @error('testimonio') is-invalid @enderror"
                              id="testimonio" name="testimonio" rows="3">{{ old('testimonio', $proyecto->testimonio) }}</textarea>
                    @error('testimonio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Quote o comentario del cliente sobre el proyecto</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="testimonio_autor" class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control @error('testimonio_autor') is-invalid @enderror"
                               id="testimonio_autor" name="testimonio_autor" value="{{ old('testimonio_autor', $proyecto->testimonio_autor) }}"
                               placeholder="Ej: Juan Pérez">
                        @error('testimonio_autor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="testimonio_cargo" class="form-label">Cargo del Cliente</label>
                        <input type="text" class="form-control @error('testimonio_cargo') is-invalid @enderror"
                               id="testimonio_cargo" name="testimonio_cargo" value="{{ old('testimonio_cargo', $proyecto->testimonio_cargo) }}"
                               placeholder="Ej: CEO de Empresa XYZ">
                        @error('testimonio_cargo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO COMPLETO -->
            <div class="form-section">
                <h4 class="mb-3"><i class="fas fa-search me-2"></i>Optimización SEO Completa</h4>
                <p class="text-muted small mb-4">🎯 Optimiza la visibilidad del proyecto en Google y redes sociales</p>

                <div class="mb-4">
                    <label for="meta_title" class="form-label">
                        Meta Título SEO *
                        <span class="badge bg-info ms-2">Recomendado: 50-60 caracteres</span>
                    </label>
                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                           id="meta_title" name="meta_title" value="{{ old('meta_title', $proyecto->meta_title) }}"
                           placeholder="Ej: {{ $proyecto->nombre }} - Desarrollo Web Profesional Laravel | MY Tech"
                           maxlength="255"
                           oninput="countChars(this, 'title-count', 60)">
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">Título optimizado que aparecerá en Google</small>
                        <small id="title-count" class="text-info">0 caracteres</small>
                    </div>
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="meta_description" class="form-label">
                        Meta Descripción SEO *
                        <span class="badge bg-info ms-2">Recomendado: 150-160 caracteres</span>
                    </label>
                    <textarea class="form-control @error('meta_description') is-invalid @enderror"
                              id="meta_description" name="meta_description" rows="3"
                              maxlength="500"
                              placeholder="Descripción atractiva que incluya palabras clave y llame a la acción. Ej: Plataforma web desarrollada con Laravel para {{ $proyecto->pais }}. Sistema completo de {{ $proyecto->categoria }} con arquitectura moderna y diseño responsive."
                              oninput="countChars(this, 'desc-count', 160)">{{ old('meta_description', $proyecto->meta_description) }}</textarea>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">Descripción que aparecerá en los resultados de búsqueda</small>
                        <small id="desc-count" class="text-info">0 caracteres</small>
                    </div>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="meta_keywords" class="form-label">
                        Palabras Clave (Keywords)
                        <span class="badge bg-secondary ms-2">5-10 palabras clave</span>
                    </label>
                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                           id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $proyecto->meta_keywords) }}"
                           placeholder="desarrollo web, laravel, {{ strtolower($proyecto->pais) }}, {{ $proyecto->categoria }}, plataforma online, sistema web">
                    <small class="text-muted">Separa las palabras clave con comas. Incluye términos relevantes para SEO</small>
                    @error('meta_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4" style="border-color: #00A9E0;">
                <h5 class="mb-3 text-info"><i class="fab fa-facebook me-2"></i>Open Graph (Facebook, LinkedIn, WhatsApp)</h5>

                <div class="mb-3">
                    <label for="og_image" class="form-label">
                        Imagen para Redes Sociales (OG Image)
                        <span class="badge bg-warning text-dark ms-2">Tamaño ideal: 1200x630px</span>
                    </label>

                    @if($proyecto->og_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $proyecto->og_image) }}" alt="OG Image actual" class="image-preview" style="max-width: 300px;">
                            <p class="text-muted small mt-1">Imagen actual para redes sociales</p>
                        </div>
                    @endif

                    <input type="file" class="form-control @error('og_image') is-invalid @enderror"
                           id="og_image" name="og_image" accept="image/*" onchange="previewOgImage(event)">
                    @error('og_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        📱 Esta imagen aparece cuando compartes el proyecto en Facebook, LinkedIn, WhatsApp, etc.
                        <br>💡 Consejo: Usa texto grande, colores llamativos y el logo del proyecto
                    </small>
                    <div id="og-preview-container"></div>
                </div>

                <div class="alert alert-info mt-4" style="background: rgba(0, 169, 224, 0.1); border-color: #00A9E0;">
                    <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Tips para un SEO perfecto:</h6>
                    <ul class="mb-0 small">
                        <li><strong>Meta Título:</strong> Incluye el nombre del proyecto + beneficio clave + tu marca</li>
                        <li><strong>Meta Descripción:</strong> Responde "qué hace" y "por qué es único" + llamado a la acción</li>
                        <li><strong>Keywords:</strong> Usa términos que tus clientes buscarían en Google</li>
                        <li><strong>OG Image:</strong> Imagen atractiva que represente el proyecto visualmente</li>
                    </ul>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.proyectos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quill.js Library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
// Configuración de Quill para cada editor
const quillOptions = {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            ['link'],
            ['clean']
        ]
    },
    placeholder: 'Escribe aquí...'
};

// Inicializar editores Quill
const descripcionQuill = new Quill('#descripcion-editor', quillOptions);
const descripcionExtendidaQuill = new Quill('#descripcion_extendida-editor', quillOptions);
const desafioQuill = new Quill('#desafio-editor', quillOptions);
const solucionQuill = new Quill('#solucion-editor', quillOptions);
const resultadosQuill = new Quill('#resultados-editor', quillOptions);

// Cargar contenido existente
const descripcionContent = document.getElementById('descripcion').value;
const descripcionExtendidaContent = document.getElementById('descripcion_extendida').value;
const desafioContent = document.getElementById('desafio').value;
const solucionContent = document.getElementById('solucion').value;
const resultadosContent = document.getElementById('resultados').value;

if (descripcionContent) descripcionQuill.root.innerHTML = descripcionContent;
if (descripcionExtendidaContent) descripcionExtendidaQuill.root.innerHTML = descripcionExtendidaContent;
if (desafioContent) desafioQuill.root.innerHTML = desafioContent;
if (solucionContent) solucionQuill.root.innerHTML = solucionContent;
if (resultadosContent) resultadosQuill.root.innerHTML = resultadosContent;

// Sincronizar contenido con textareas ocultos
descripcionQuill.on('text-change', function() {
    document.getElementById('descripcion').value = descripcionQuill.root.innerHTML;
});

descripcionExtendidaQuill.on('text-change', function() {
    document.getElementById('descripcion_extendida').value = descripcionExtendidaQuill.root.innerHTML;
});

desafioQuill.on('text-change', function() {
    document.getElementById('desafio').value = desafioQuill.root.innerHTML;
});

solucionQuill.on('text-change', function() {
    document.getElementById('solucion').value = solucionQuill.root.innerHTML;
});

resultadosQuill.on('text-change', function() {
    document.getElementById('resultados').value = resultadosQuill.root.innerHTML;
});

function previewImage(event) {
    const container = document.getElementById('preview-container');
    container.innerHTML = '';

    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'image-preview';
            container.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}

function previewGallery(event) {
    const container = document.getElementById('gallery-preview-container');
    container.innerHTML = '';

    const files = event.target.files;
    if (files.length > 0) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'image-preview';
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}

function previewOgImage(event) {
    const container = document.getElementById('og-preview-container');
    container.innerHTML = '';

    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'image-preview';
            img.style.maxWidth = '300px';
            container.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}

function countChars(element, counterId, recommended) {
    const count = element.value.length;
    const counter = document.getElementById(counterId);
    counter.textContent = count + ' caracteres';

    if (count >= recommended - 10 && count <= recommended + 10) {
        counter.className = 'text-success fw-bold';
    } else if (count > recommended + 10) {
        counter.className = 'text-warning fw-bold';
    } else {
        counter.className = 'text-info';
    }
}

// Inicializar contadores al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('meta_title');
    const descInput = document.getElementById('meta_description');

    if (titleInput && titleInput.value) {
        countChars(titleInput, 'title-count', 60);
    }

    if (descInput && descInput.value) {
        countChars(descInput, 'desc-count', 160);
    }
});
</script>
@endsection
