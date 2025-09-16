{{-- resources/views/admin/pages/edit-quienes-somos.blade.php --}}
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
    .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    .btn-add { background-color: #28a745; border-color: #28a745; }
    h2, h4 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .badge-hero { background-color: #f7a831; }
    .badge-legacy { background-color: #28a745; }
    .badge-quality { background-color: #17a2b8; }
    .badge-passion { background-color: #fd7e14; }
    .badge-benefits { background-color: #6f42c1; }
    .badge-cta { background-color: #dc3545; }
    .image-preview { height: 120px; width: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #00A9E0; }
    .content-preview { background: rgba(252, 250, 241, 0.05); padding: 15px; border-radius: 6px; border-left: 4px solid #00A9E0; margin: 10px 0; }
    .field-group { background: rgba(0, 169, 224, 0.05); border: 1px solid rgba(0, 169, 224, 0.2); border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .field-group h6 { color: #00A9E0; margin-bottom: 15px; }
    .badge-item { background: rgba(40, 167, 69, 0.2); color: #28a745; padding: 5px 10px; border-radius: 15px; margin: 5px; display: inline-block; border: 1px solid #28a745; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-users"></i> Editar Página "Quiénes Somos"</h2>
                <p class="text-light mb-0">Personaliza cada sección con campos específicos</p>
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

        @foreach($page->sections()->ordered()->get() as $section)

            {{-- SECCIÓN HERO - Banner Principal --}}
            @if($section->name === 'hero')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-flag me-2"></i> Banner Principal <span class="badge badge-hero ms-2">Hero</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Títulos del Banner</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Título Principal (H1)</label>
                                    <input type="text" name="title" class="form-control" 
                                           value="{{ $section->title ?: 'Acerca de ElectraHome' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Subtítulo</label>
                                    <input type="text" name="content" class="form-control" 
                                           value="{{ $section->content ?: 'Tradición en Electrodomésticos de Calidad' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen de Fondo del Banner</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Subir nueva imagen de fondo</label>
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Recomendado: 1920x600px. Será la imagen de fondo del banner.</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <label class="form-label">Imagen actual</label>
                                        <div class="text-center">
                                            <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview mb-2">
                                            <br>
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="deleteImage('hero', {{ $section->id }}, 0)">
                                                <i class="fas fa-trash"></i> Cambiar
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-center p-3 border rounded" style="border-color: #00A9E0;">
                                            <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                            <br>
                                            <small class="text-muted">Sin imagen de fondo</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Banner Principal
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN LEGACY - Tradición --}}
            @elseif($section->name === 'legacy')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-award me-2"></i> Sección: Tradición y Calidad <span class="badge badge-legacy ms-2">Legacy</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título de Sección</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Tradición en Electrodomésticos de Calidad' }}" required>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-align-left"></i> Contenido Principal</h6>
                            <div class="mb-3">
                                <label class="form-label">Primer párrafo</label>
                                <textarea name="paragraph_1" class="form-control" rows="3" 
                                          placeholder="Ej: En ElectraHome, cada electrodoméstico que ofrecemos...">{{ $section->getCustomData('paragraph_1') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Segundo párrafo</label>
                                <textarea name="paragraph_2" class="form-control" rows="3" 
                                          placeholder="Ej: Desde licuadoras de alta potencia...">{{ $section->getCustomData('paragraph_2') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cita destacada (Quote)</label>
                                <textarea name="quote" class="form-control" rows="2" 
                                          placeholder="Ej: Imagínate una cocina donde cada electrodoméstico funciona a la perfección...">{{ $section->getCustomData('quote') }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen Ilustrativa</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Imagen que acompañe esta sección. Recomendado: 600x400px</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview">
                                        <button type="button" class="btn btn-danger btn-sm mt-1" 
                                                onclick="deleteImage('legacy', {{ $section->id }}, 0)">
                                            <i class="fas fa-trash"></i> Cambiar
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Tradición y Calidad
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN QUALITY - Garantía --}}
            @elseif($section->name === 'quality')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-shield-alt me-2"></i> Sección: Garantía y Servicio <span class="badge badge-quality ms-2">Quality</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título de Sección</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Garantía Oficial y Servicio Especializado' }}" required>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-align-left"></i> Descripción</h6>
                            <div class="mb-3">
                                <label class="form-label">Primer párrafo</label>
                                <textarea name="paragraph_1" class="form-control" rows="3" 
                                          placeholder="Sobre garantía oficial y distribución autorizada...">{{ $section->getCustomData('paragraph_1') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Segundo párrafo</label>
                                <textarea name="paragraph_2" class="form-control" rows="3" 
                                          placeholder="Sobre servicio técnico y soporte...">{{ $section->getCustomData('paragraph_2') }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-medal"></i> Badges de Calidad (4 elementos)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="badge_1" class="form-control" 
                                           value="{{ $section->getCustomData('badge_1') ?: 'Garantía Oficial' }}" placeholder="Badge 1">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="badge_2" class="form-control" 
                                           value="{{ $section->getCustomData('badge_2') ?: 'Servicio Técnico' }}" placeholder="Badge 2">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="badge_3" class="form-control" 
                                           value="{{ $section->getCustomData('badge_3') ?: 'Repuestos Originales' }}" placeholder="Badge 3">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="badge_4" class="form-control" 
                                           value="{{ $section->getCustomData('badge_4') ?: 'Soporte en Español' }}" placeholder="Badge 4">
                                </div>
                            </div>
                            <div class="mt-2">
                                <strong>Vista previa:</strong>
                                <span class="badge-item">✓ Garantía Oficial</span>
                                <span class="badge-item">✓ Servicio Técnico</span>
                                <span class="badge-item">✓ Repuestos Originales</span>
                                <span class="badge-item">✓ Soporte en Español</span>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Imagen sobre servicio técnico/garantía</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview">
                                        <button type="button" class="btn btn-danger btn-sm mt-1" 
                                                onclick="deleteImage('quality', {{ $section->id }}, 0)">Cambiar</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Garantía y Servicio
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN PASSION - Pasión del Equipo --}}
            @elseif($section->name === 'passion')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-heart me-2"></i> Sección: Pasión del Equipo <span class="badge badge-passion ms-2">Passion</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'La Pasión Detrás del Servicio' }}" required>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-align-left"></i> Contenido</h6>
                            <div class="mb-3">
                                <label class="form-label">Primer párrafo</label>
                                <textarea name="paragraph_1" class="form-control" rows="3" 
                                          placeholder="Sobre el equipo y su expertise...">{{ $section->getCustomData('paragraph_1') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Segundo párrafo</label>
                                <textarea name="paragraph_2" class="form-control" rows="3" 
                                          placeholder="Sobre el servicio personalizado...">{{ $section->getCustomData('paragraph_2') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cita del Equipo</label>
                                <textarea name="team_quote" class="form-control" rows="2" 
                                          placeholder="Cita motivacional del equipo">{{ $section->getCustomData('team_quote') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Atribución de la cita</label>
                                <input type="text" name="quote_author" class="form-control" 
                                       value="{{ $section->getCustomData('quote_author') ?: '- Equipo ElectraHome, Aragua, Venezuela' }}" 
                                       placeholder="- Equipo ElectraHome, Ciudad, País">
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen del Equipo</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Foto del equipo o ambiente de trabajo</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview">
                                        <button type="button" class="btn btn-danger btn-sm mt-1" 
                                                onclick="deleteImage('passion', {{ $section->id }}, 0)">Cambiar</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Pasión del Equipo
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN BENEFITS - Por Qué Elegirnos --}}
            @elseif($section->name === 'benefits')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-star me-2"></i> Sección: Por Qué Elegirnos <span class="badge badge-benefits ms-2">Benefits</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Encabezado</h6>
                            <div class="mb-3">
                                <label class="form-label">Título principal</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: 'Por Qué Elegir ElectraHome' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Primer párrafo descriptivo</label>
                                <textarea name="paragraph_1" class="form-control" rows="3" 
                                          placeholder="Descripción general de los beneficios...">{{ $section->getCustomData('paragraph_1') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Segundo párrafo descriptivo</label>
                                <textarea name="paragraph_2" class="form-control" rows="3" 
                                          placeholder="Beneficios adicionales como capacitación, recetas, etc...">{{ $section->getCustomData('paragraph_2') }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-list-ul"></i> 3 Beneficios Principales</h6>
                            
                            <!-- Beneficio 1 -->
                            <div class="row mb-3 pb-3" style="border-bottom: 1px solid rgba(0,169,224,0.2);">
                                <div class="col-md-2">
                                    <label class="form-label">Emoji/Icono 1</label>
                                    <input type="text" name="benefit_1_icon" class="form-control text-center" 
                                           value="{{ $section->getCustomData('benefit_1_icon') ?: '⚡' }}" style="font-size: 1.5rem;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Título beneficio 1</label>
                                    <input type="text" name="benefit_1_title" class="form-control" 
                                           value="{{ $section->getCustomData('benefit_1_title') ?: 'Mejor para Ti' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Descripción beneficio 1</label>
                                    <input type="text" name="benefit_1_desc" class="form-control" 
                                           value="{{ $section->getCustomData('benefit_1_desc') ?: 'Productos eficientes, duraderos y fáciles de usar' }}">
                                </div>
                            </div>

                            <!-- Beneficio 2 -->
                            <div class="row mb-3 pb-3" style="border-bottom: 1px solid rgba(0,169,224,0.2);">
                                <div class="col-md-2">
                                    <label class="form-label">Emoji/Icono 2</label>
                                    <input type="text" name="benefit_2_icon" class="form-control text-center" 
                                           value="{{ $section->getCustomData('benefit_2_icon') ?: '🛠️' }}" style="font-size: 1.5rem;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Título beneficio 2</label>
                                    <input type="text" name="benefit_2_title" class="form-control" 
                                           value="{{ $section->getCustomData('benefit_2_title') ?: 'Mejor Servicio' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Descripción beneficio 2</label>
                                    <input type="text" name="benefit_2_desc" class="form-control" 
                                           value="{{ $section->getCustomData('benefit_2_desc') ?: 'Garantía oficial y soporte técnico especializado' }}">
                                </div>
                            </div>

                            <!-- Beneficio 3 -->
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label class="form-label">Emoji/Icono 3</label>
                                    <input type="text" name="benefit_3_icon" class="form-control text-center" 
                                           value="{{ $section->getCustomData('benefit_3_icon') ?: '🏠' }}" style="font-size: 1.5rem;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Título beneficio 3</label>
                                    <input type="text" name="benefit_3_title" class="form-control" 
                                           value="{{ $section->getCustomData('benefit_3_title') ?: 'Mejor Hogar' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Descripción beneficio 3</label>
                                    <input type="text" name="benefit_3_desc" class="form-control" 
                                           value="{{ $section->getCustomData('benefit_3_desc') ?: 'Cocinas más eficientes y momentos familiares especiales' }}">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Beneficios
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN CTA - Llamada a la Acción --}}
            @elseif($section->name === 'cta')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-rocket me-2"></i> Sección: Llamada a la Acción <span class="badge badge-cta ms-2">CTA</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-bullhorn"></i> Llamada a la Acción Final</h6>
                            <div class="mb-3">
                                <label class="form-label">Título de CTA</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: 'Únete a la Familia ElectraHome' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción de CTA</label>
                                <textarea name="content" class="form-control" rows="3" 
                                          placeholder="Invitación para que visiten la tienda...">{{ $section->content }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Texto del botón</label>
                                <input type="text" name="button_text" class="form-control" 
                                       value="{{ $section->getCustomData('button_text') ?: 'Explorar Productos Ahora' }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pregunta final (opcional)</label>
                                <input type="text" name="final_question" class="form-control" 
                                       value="{{ $section->getCustomData('final_question') ?: '¿Cuál es tu razón para elegir electrodomésticos de calidad?' }}" 
                                       placeholder="Pregunta que genere reflexión...">
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Llamada a la Acción
                        </button>
                    </form>
                </div>
            </div>
            @endif

        @endforeach

        @if($page->sections->count() == 0)
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h4 class="text-warning">No hay secciones configuradas</h4>
            <p class="text-light">Las secciones se crearán automáticamente al acceder por primera vez.</p>
        </div>
        @endif

    </div>
</div>

<script>
// Función para eliminar imagen
function deleteImage(sectionName, sectionId, imageIndex) {
    if (confirm(`¿Estás seguro de eliminar esta imagen?`)) {
        fetch(`/admin/pages/{{ $page->id }}/sections/${sectionId}/images`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ image_index: imageIndex })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al eliminar la imagen');
            }
        });
    }
}

// Prevenir submit múltiple
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
    });
});
</script>
@endsection