{{-- resources/views/admin/pages/edit-contacto.blade.php --}}
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
    h2, h4 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
    .form-check-input:checked { background-color: #00A9E0; border-color: #00A9E0; }
    .badge-hero { background-color: #f7a831; }
    .badge-info { background-color: #17a2b8; }
    .badge-services { background-color: #28a745; }
    .badge-contact { background-color: #6f42c1; }
    .badge-form { background-color: #fd7e14; }
    .image-preview { height: 120px; width: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #00A9E0; }
    .field-group { background: rgba(0, 169, 224, 0.05); border: 1px solid rgba(0, 169, 224, 0.2); border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .field-group h6 { color: #00A9E0; margin-bottom: 15px; }
    .service-preview { background: rgba(40, 167, 69, 0.1); border: 1px solid #28a745; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    .contact-preview { background: rgba(111, 66, 193, 0.1); border: 1px solid #6f42c1; border-radius: 8px; padding: 10px; margin-bottom: 10px; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-phone"></i> Editar Página "Contacto"</h2>
                <p class="text-light mb-0">Gestiona toda la información de contacto y servicios</p>
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

            {{-- SECCIÓN HERO - Banner de Contacto --}}
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
                                    <label class="form-label">Título Principal</label>
                                    <input type="text" name="title" class="form-control" 
                                           value="{{ $section->title ?: 'Contáctanos' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Subtítulo</label>
                                    <input type="text" name="content" class="form-control" 
                                           value="{{ $section->content ?: 'Servicio técnico especializado' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen de Fondo</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="file" name="images[]" class="form-control" accept="image/*">
                                    <small class="text-muted">Recomendado: 1920x600px. Imagen de fondo del banner.</small>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview mb-2">
                                        <br>
                                        <button type="button" class="btn btn-danger btn-sm" 
                                                onclick="deleteImage('hero', {{ $section->id }}, 0)">
                                            <i class="fas fa-trash"></i> Cambiar
                                        </button>
                                    @else
                                        <div class="text-center p-3 border rounded" style="border-color: #00A9E0;">
                                            <i class="fas fa-image fa-2x text-muted"></i><br>
                                            <small class="text-muted">Sin imagen</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Banner
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN INFO - Información Principal --}}
            @elseif($section->name === 'info')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-info-circle me-2"></i> Información Principal <span class="badge badge-info ms-2">Info</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título Principal</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: '¿Necesitas ayuda con tus electrodomésticos?' }}" required>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-align-left"></i> Descripción de la Empresa</h6>
                            <textarea name="content" class="form-control" rows="4" 
                                      placeholder="Descripción de los servicios que ofrece la empresa...">{{ $section->content }}</textarea>
                            <small class="text-muted">Esta descripción aparece debajo del título principal</small>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Información
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN SERVICES - Lista de Servicios --}}
            @elseif($section->name === 'services')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-cogs me-2"></i> Servicios Ofrecidos <span class="badge badge-services ms-2">Services</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-list-ul"></i> 4 Servicios Principales</h6>
                            <p class="text-muted mb-4">Configura los servicios que aparecen en la página de contacto</p>
                            
                            <!-- Servicio 1 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Emoji/Icono 1</label>
                                        <input type="text" name="service_1_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_1_icon', '🔧') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Servicio 1</label>
                                        <input type="text" name="service_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_1_title', 'Reparación Especializada') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Servicio 1</label>
                                        <input type="text" name="service_1_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_1_desc', 'Lavadoras, secadoras, refrigeradoras, cocinas, microondas y más') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 2 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Emoji/Icono 2</label>
                                        <input type="text" name="service_2_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_2_icon', '🏠') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Servicio 2</label>
                                        <input type="text" name="service_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_2_title', 'Servicio a Domicilio') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Servicio 2</label>
                                        <input type="text" name="service_2_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_2_desc', 'Atendemos en toda la ciudad con horarios flexibles') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 3 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Emoji/Icono 3</label>
                                        <input type="text" name="service_3_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_3_icon', '⚡') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Servicio 3</label>
                                        <input type="text" name="service_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_3_title', 'Electrodomésticos Oster') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Servicio 3</label>
                                        <input type="text" name="service_3_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_3_desc', 'Venta y reparación de licuadoras, freidoras de aire') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio 4 -->
                            <div class="service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Emoji/Icono 4</label>
                                        <input type="text" name="service_4_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_4_icon', '✅') }}" style="font-size: 1.5rem;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Título Servicio 4</label>
                                        <input type="text" name="service_4_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_4_title', 'Garantía y Calidad') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Servicio 4</label>
                                        <input type="text" name="service_4_desc" class="form-control" 
                                               value="{{ $section->getCustomData('service_4_desc', 'Todos nuestros trabajos incluyen garantía y repuestos originales') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Servicios
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN CONTACT INFO - Información de Contacto --}}
            @elseif($section->name === 'contact_info')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-address-book me-2"></i> Información de Contacto <span class="badge badge-contact ms-2">Contact</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-id-card"></i> Datos de Contacto (4 Tarjetas)</h6>
                            
                            <!-- WhatsApp -->
                            <div class="contact-preview">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"><i class="fab fa-whatsapp"></i> WhatsApp</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="whatsapp_number" class="form-control" 
                                               value="{{ $section->getCustomData('whatsapp_number', '+593 98 765 4321') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Enlace WhatsApp</label>
                                        <input type="text" name="whatsapp_link" class="form-control" 
                                               value="{{ $section->getCustomData('whatsapp_link', 'https://wa.me/593987654321') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="contact-preview">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"><i class="fas fa-phone"></i> Teléfono</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="phone_number" class="form-control" 
                                               value="{{ $section->getCustomData('phone_number', '+593 2 234 5678') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Enlace Tel (tel:)</label>
                                        <input type="text" name="phone_link" class="form-control" 
                                               value="{{ $section->getCustomData('phone_link', 'tel:+59322345678') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="contact-preview">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Correo</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ $section->getCustomData('email', 'info@electrahome.com') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Enlace Mailto</label>
                                        <input type="text" name="email_link" class="form-control" 
                                               value="{{ $section->getCustomData('email_link', 'mailto:info@electrahome.com') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Horarios -->
                            <div class="contact-preview">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label"><i class="fas fa-clock"></i> Horarios</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Lunes a Viernes</label>
                                        <input type="text" name="schedule_weekdays" class="form-control" 
                                               value="{{ $section->getCustomData('schedule_weekdays', 'Lun-Vie: 8:00-18:00') }}">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Sábados</label>
                                        <input type="text" name="schedule_saturday" class="form-control" 
                                               value="{{ $section->getCustomData('schedule_saturday', 'Sáb: 8:00-16:00') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Información de Contacto
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN FORM HEADER - Encabezado del Formulario --}}
            @elseif($section->name === 'form_header')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-edit me-2"></i> Encabezado del Formulario <span class="badge badge-form ms-2">Form</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título del Formulario</h6>
                            <input type="text" name="title" class="form-control mb-3" 
                                   value="{{ $section->title ?: 'Solicita tu Servicio' }}" required>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-align-left"></i> Descripción del Formulario</h6>
                            <textarea name="content" class="form-control" rows="3" 
                                      placeholder="Descripción que aparece debajo del título del formulario...">{{ $section->content }}</textarea>
                            <small class="text-muted">Esta descripción explica a los usuarios qué pueden esperar al completar el formulario</small>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Encabezado del Formulario
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