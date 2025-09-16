{{-- resources/views/admin/pages/edit-servicios.blade.php --}}
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
    .badge-intro { background-color: #28a745; }
    .badge-services { background-color: #17a2b8; }
    .badge-process { background-color: #fd7e14; }
    .badge-why { background-color: #6f42c1; }
    .badge-cta { background-color: #dc3545; }
    .image-preview { height: 120px; width: 120px; object-fit: cover; border-radius: 8px; border: 2px solid #00A9E0; }
    .field-group { background: rgba(0, 169, 224, 0.05); border: 1px solid rgba(0, 169, 224, 0.2); border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .field-group h6 { color: #00A9E0; margin-bottom: 15px; }
    .service-preview { background: rgba(23, 162, 184, 0.1); border: 1px solid #17a2b8; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    .process-preview { background: rgba(253, 126, 20, 0.1); border: 1px solid #fd7e14; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    .reason-preview { background: rgba(111, 66, 193, 0.1); border: 1px solid #6f42c1; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
</style>

<div class="main-content">
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="fas fa-tools"></i> Editar Página "Servicios"</h2>
                <p class="text-light mb-0">Gestiona toda la información de servicios y reparaciones - 100% Editable</p>
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

            {{-- SECCIÓN HERO - Banner de Servicios --}}
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
                                           value="{{ $section->title ?: 'Nuestros Servicios' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Subtítulo</label>
                                    <input type="text" name="content" class="form-control" 
                                           value="{{ $section->content ?: 'Servicio técnico especializado en línea blanca y electrodomésticos Oster en Quito' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen de Fondo</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Subir Imagen</label>
                                        <input type="file" name="images[]" class="form-control" accept="image/*">
                                        <small class="text-muted">Recomendado: 1920x600px. Imagen relacionada con servicios técnicos.</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Texto Alternativo de Imagen (SEO)</label>
                                        <input type="text" name="image_alt" class="form-control" 
                                               value="{{ $section->getCustomData('image_alt', 'Servicios ElectraHome') }}">
                                    </div>
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

            {{-- SECCIÓN INTRO - Introducción + Servicios Principales --}}
            @elseif($section->name === 'intro')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-info-circle me-2"></i> Introducción + Servicios Principales <span class="badge badge-intro ms-2">Intro</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Sección Principal</h6>
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: '¿Qué Hacemos?' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="4" 
                                          placeholder="Descripción introductoria sobre tus servicios, experiencia y compromiso...">{{ $section->content ?: 'Somos especialistas en reparación, mantenimiento e instalación de electrodomésticos. Con más de 10 años de experiencia, brindamos servicio técnico certificado en toda la ciudad de Quito.' }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-cogs"></i> Servicio 1: Reparación Especializada</h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Icono Principal</label>
                                    <input type="text" name="repair_icon" class="form-control" 
                                           value="{{ $section->getCustomData('repair_icon', 'fas fa-wrench') }}"
                                           placeholder="fas fa-wrench">
                                    <small class="text-muted">Ej: fas fa-wrench</small>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Título del Servicio</label>
                                    <input type="text" name="repair_title" class="form-control" 
                                           value="{{ $section->getCustomData('repair_title', 'Reparación Especializada') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción del Servicio</label>
                                <textarea name="repair_description" class="form-control" rows="2">{{ $section->getCustomData('repair_description', 'Diagnóstico y reparación de fallas en todos los tipos de electrodomésticos con repuestos originales y garantía.') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Check</label>
                                    <input type="text" name="repair_check_icon" class="form-control" 
                                           value="{{ $section->getCustomData('repair_check_icon', 'fas fa-check') }}">
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 1</label>
                                            <input type="text" name="repair_feature_1" class="form-control" 
                                                   value="{{ $section->getCustomData('repair_feature_1', 'Diagnóstico gratuito') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 2</label>
                                            <input type="text" name="repair_feature_2" class="form-control" 
                                                   value="{{ $section->getCustomData('repair_feature_2', 'Repuestos originales') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 3</label>
                                            <input type="text" name="repair_feature_3" class="form-control" 
                                                   value="{{ $section->getCustomData('repair_feature_3', 'Garantía incluida') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-cogs"></i> Servicio 2: Mantenimiento Preventivo</h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Icono Principal</label>
                                    <input type="text" name="maintenance_icon" class="form-control" 
                                           value="{{ $section->getCustomData('maintenance_icon', 'fas fa-cogs') }}"
                                           placeholder="fas fa-cogs">
                                    <small class="text-muted">Ej: fas fa-cogs</small>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Título del Servicio</label>
                                    <input type="text" name="maintenance_title" class="form-control" 
                                           value="{{ $section->getCustomData('maintenance_title', 'Mantenimiento Preventivo') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción del Servicio</label>
                                <textarea name="maintenance_description" class="form-control" rows="2">{{ $section->getCustomData('maintenance_description', 'Servicios de limpieza y mantenimiento programado para prolongar la vida útil de tus electrodomésticos.') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Check</label>
                                    <input type="text" name="maintenance_check_icon" class="form-control" 
                                           value="{{ $section->getCustomData('maintenance_check_icon', 'fas fa-check') }}">
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 1</label>
                                            <input type="text" name="maintenance_feature_1" class="form-control" 
                                                   value="{{ $section->getCustomData('maintenance_feature_1', 'Limpieza profunda') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 2</label>
                                            <input type="text" name="maintenance_feature_2" class="form-control" 
                                                   value="{{ $section->getCustomData('maintenance_feature_2', 'Revisión completa') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 3</label>
                                            <input type="text" name="maintenance_feature_3" class="form-control" 
                                                   value="{{ $section->getCustomData('maintenance_feature_3', 'Planes de mantenimiento') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-tools"></i> Servicio 3: Instalación Profesional</h6>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Icono Principal</label>
                                    <input type="text" name="installation_icon" class="form-control" 
                                           value="{{ $section->getCustomData('installation_icon', 'fas fa-tools') }}"
                                           placeholder="fas fa-tools">
                                    <small class="text-muted">Ej: fas fa-tools</small>
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Título del Servicio</label>
                                    <input type="text" name="installation_title" class="form-control" 
                                           value="{{ $section->getCustomData('installation_title', 'Instalación Profesional') }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción del Servicio</label>
                                <textarea name="installation_description" class="form-control" rows="2">{{ $section->getCustomData('installation_description', 'Instalación segura y correcta de electrodomésticos nuevos con conexiones eléctricas y de agua certificadas.') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Check</label>
                                    <input type="text" name="installation_check_icon" class="form-control" 
                                           value="{{ $section->getCustomData('installation_check_icon', 'fas fa-check') }}">
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 1</label>
                                            <input type="text" name="installation_feature_1" class="form-control" 
                                                   value="{{ $section->getCustomData('installation_feature_1', 'Instalación certificada') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 2</label>
                                            <input type="text" name="installation_feature_2" class="form-control" 
                                                   value="{{ $section->getCustomData('installation_feature_2', 'Pruebas de funcionamiento') }}">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Característica 3</label>
                                            <input type="text" name="installation_feature_3" class="form-control" 
                                                   value="{{ $section->getCustomData('installation_feature_3', 'Capacitación de uso') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Introducción y Servicios
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN SERVICES LIST - Electrodomésticos que Reparamos --}}
            @elseif($section->name === 'services_list')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-wrench me-2"></i> Electrodomésticos que Reparamos <span class="badge badge-services ms-2">Appliances</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título de Sección</h6>
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: 'Electrodomésticos que Reparamos' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="3" 
                                          placeholder="Descripción de los electrodomésticos que reparan">{{ $section->content ?: 'Trabajamos con todas las marcas y modelos de línea blanca. Nuestros técnicos están capacitados para reparar cualquier electrodoméstico del hogar.' }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-list"></i> 3 Categorías de Electrodomésticos</h6>
                            
                            <!-- Categoría 1: Línea Blanca -->
                            <div class="appliance-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 1</label>
                                        <input type="text" name="service_1_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_1_icon', '🏠') }}" style="font-size: 1.5rem;">
                                        <small class="text-muted">Emoji o icono</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Categoría 1</label>
                                        <input type="text" name="service_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_1_title', 'Línea Blanca') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Productos 1</label>
                                        <textarea name="service_1_desc" class="form-control" rows="2">{{ $section->getCustomData('service_1_desc', 'Lavadoras, secadoras, refrigeradoras, cocinas, microondas, calefones, lavavajillas, aspiradoras') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Categoría 2: Electrodomésticos Oster -->
                            <div class="appliance-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 2</label>
                                        <input type="text" name="service_2_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_2_icon', '⚡') }}" style="font-size: 1.5rem;">
                                        <small class="text-muted">Emoji o icono</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Categoría 2</label>
                                        <input type="text" name="service_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_2_title', 'Electrodomésticos Oster') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Productos 2</label>
                                        <textarea name="service_2_desc" class="form-control" rows="2">{{ $section->getCustomData('service_2_desc', 'Licuadoras, freidoras de aire, extractores, sanducheras, procesadores de alimentos') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Categoría 3: Todas las Marcas -->
                            <div class="appliance-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 3</label>
                                        <input type="text" name="service_3_icon" class="form-control text-center" 
                                               value="{{ $section->getCustomData('service_3_icon', '🔧') }}" style="font-size: 1.5rem;">
                                        <small class="text-muted">Emoji o icono</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Categoría 3</label>
                                        <input type="text" name="service_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('service_3_title', 'Todas las Marcas') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Marcas 3</label>
                                        <textarea name="service_3_desc" class="form-control" rows="2">{{ $section->getCustomData('service_3_desc', 'LG, Samsung, Whirlpool, Electrolux, Mabe, Indurama, Oster y más') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen de Electrodomésticos</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Subir Imagen</label>
                                        <input type="file" name="images[]" class="form-control" accept="image/*">
                                        <small class="text-muted">Imagen de electrodomésticos o técnico trabajando</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Texto Alternativo de Imagen (SEO)</label>
                                        <input type="text" name="image_alt" class="form-control" 
                                               value="{{ $section->getCustomData('image_alt', 'Reparación de Electrodomésticos') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview">
                                        <button type="button" class="btn btn-danger btn-sm mt-1" 
                                                onclick="deleteImage('services_list', {{ $section->id }}, 0)">Cambiar</button>
                                    @else
                                        <div class="text-center p-3 border rounded">
                                            <i class="fas fa-image fa-2x text-muted"></i><br>
                                            <small class="text-muted">Sin imagen</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Electrodomésticos
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN PROCESS - Proceso de Trabajo --}}
            @elseif($section->name === 'process')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-tasks me-2"></i> Proceso de Trabajo <span class="badge badge-process ms-2">Process</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título</h6>
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: '¿Cómo Trabajamos?' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="2" 
                                          placeholder="Descripción del proceso">{{ $section->content ?: 'Nuestro proceso es simple, rápido y transparente. Te acompañamos desde el primer contacto hasta que tu electrodoméstico quede funcionando perfectamente.' }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-list-ol"></i> 4 Pasos del Proceso</h6>
                            
                            <!-- Paso 1 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="step_1_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_1_number', '1') }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Icono</label>
                                        <input type="text" name="step_1_icon" class="form-control" 
                                               value="{{ $section->getCustomData('step_1_icon', 'fas fa-phone') }}"
                                               placeholder="fas fa-phone">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Título Paso 1</label>
                                        <input type="text" name="step_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_1_title', 'Contacto') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Paso 1</label>
                                        <textarea name="step_1_desc" class="form-control" rows="2">{{ $section->getCustomData('step_1_desc', 'Llámanos o escríbenos por WhatsApp. Te atendemos inmediatamente y agendamos tu cita.') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 2 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="step_2_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_2_number', '2') }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Icono</label>
                                        <input type="text" name="step_2_icon" class="form-control" 
                                               value="{{ $section->getCustomData('step_2_icon', 'fas fa-search') }}"
                                               placeholder="fas fa-search">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Título Paso 2</label>
                                        <input type="text" name="step_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_2_title', 'Diagnóstico') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Paso 2</label>
                                        <textarea name="step_2_desc" class="form-control" rows="2">{{ $section->getCustomData('step_2_desc', 'Nuestro técnico visita tu hogar, revisa el electrodoméstico y te da un diagnóstico gratuito.') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 3 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="step_3_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_3_number', '3') }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Icono</label>
                                        <input type="text" name="step_3_icon" class="form-control" 
                                               value="{{ $section->getCustomData('step_3_icon', 'fas fa-hammer') }}"
                                               placeholder="fas fa-hammer">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Título Paso 3</label>
                                        <input type="text" name="step_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_3_title', 'Reparación') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Paso 3</label>
                                        <textarea name="step_3_desc" class="form-control" rows="2">{{ $section->getCustomData('step_3_desc', 'Una vez aprobado el presupuesto, realizamos la reparación con repuestos originales.') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Paso 4 -->
                            <div class="process-preview">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="form-label">Número</label>
                                        <input type="text" name="step_4_number" class="form-control text-center" 
                                               value="{{ $section->getCustomData('step_4_number', '4') }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Icono</label>
                                        <input type="text" name="step_4_icon" class="form-control" 
                                               value="{{ $section->getCustomData('step_4_icon', 'fas fa-shield-check') }}"
                                               placeholder="fas fa-shield-check">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Título Paso 4</label>
                                        <input type="text" name="step_4_title" class="form-control" 
                                               value="{{ $section->getCustomData('step_4_title', 'Garantía') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción Paso 4</label>
                                        <textarea name="step_4_desc" class="form-control" rows="2">{{ $section->getCustomData('step_4_desc', 'Tu electrodoméstico queda funcionando perfecto y con garantía por nuestro trabajo.') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Proceso
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN OSTER - Especialistas en Oster --}}
            @elseif($section->name === 'oster_section')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-star me-2"></i> Sección Oster <span class="badge badge-oster ms-2">Oster</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título y Descripción</h6>
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: 'Especialistas en Oster' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="3">{{ $section->content ?: 'Además de nuestro servicio técnico, también vendemos y reparamos la línea completa de electrodomésticos Oster. Somos distribuidores autorizados con repuestos originales.' }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-list"></i> 3 Servicios Oster</h6>
                            
                            <!-- Servicio Oster 1 -->
                            <div class="oster-service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 1</label>
                                        <input type="text" name="oster_service_1_icon" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_1_icon', 'fas fa-shopping-cart') }}"
                                               placeholder="fas fa-shopping-cart">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 1</label>
                                        <input type="text" name="oster_service_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_1_title', 'Venta de Productos Oster') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 1</label>
                                        <input type="text" name="oster_service_1_desc" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_1_desc', 'Licuadoras, freidoras de aire, extractores, sanducheras y más') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio Oster 2 -->
                            <div class="oster-service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 2</label>
                                        <input type="text" name="oster_service_2_icon" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_2_icon', 'fas fa-wrench') }}"
                                               placeholder="fas fa-wrench">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 2</label>
                                        <input type="text" name="oster_service_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_2_title', 'Reparación Especializada Oster') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 2</label>
                                        <input type="text" name="oster_service_2_desc" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_2_desc', 'Servicio técnico autorizado con repuestos originales') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio Oster 3 -->
                            <div class="oster-service-preview">
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="form-label">Icono 3</label>
                                        <input type="text" name="oster_service_3_icon" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_3_icon', 'fas fa-medal') }}"
                                               placeholder="fas fa-medal">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Servicio 3</label>
                                        <input type="text" name="oster_service_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_3_title', 'Garantía Oficial') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Descripción 3</label>
                                        <input type="text" name="oster_service_3_desc" class="form-control" 
                                               value="{{ $section->getCustomData('oster_service_3_desc', 'Respaldamos nuestros productos y servicios con garantía completa') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-link"></i> Botón de Acción</h6>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Botón</label>
                                    <input type="text" name="button_icon" class="form-control" 
                                           value="{{ $section->getCustomData('button_icon', 'fas fa-eye') }}"
                                           placeholder="fas fa-eye">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Texto del Botón</label>
                                    <input type="text" name="button_text" class="form-control" 
                                           value="{{ $section->getCustomData('button_text', 'Ver Productos Oster') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL del Botón</label>
                                    <input type="text" name="button_url" class="form-control" 
                                           value="{{ $section->getCustomData('button_url', '#') }}"
                                           placeholder="https://ejemplo.com/shop">
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-image"></i> Imagen de Productos Oster</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Subir Imagen</label>
                                        <input type="file" name="images[]" class="form-control" accept="image/*">
                                        <small class="text-muted">Imagen de productos Oster</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Texto Alternativo de Imagen (SEO)</label>
                                        <input type="text" name="image_alt" class="form-control" 
                                               value="{{ $section->getCustomData('image_alt', 'Productos Oster') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @if($section->getImagesArray())
                                        <img src="{{ Storage::url($section->getImagesArray()[0]) }}" class="image-preview">
                                        <button type="button" class="btn btn-danger btn-sm mt-1">Cambiar</button>
                                    @else
                                        <div class="text-center p-3 border rounded">
                                            <i class="fas fa-image fa-2x text-muted"></i><br>
                                            <small class="text-muted">Sin imagen</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Sección Oster
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN COVERAGE - Zona de Cobertura --}}
            @elseif($section->name === 'coverage_section')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-map-marker-alt me-2"></i> Zona de Cobertura <span class="badge badge-coverage ms-2">Coverage</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-heading"></i> Título y Descripción</h6>
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: 'Zona de Cobertura' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="2">{{ $section->content ?: 'Brindamos servicio técnico a domicilio en toda la ciudad de Quito y sus valles. No importa dónde estés, llegamos hasta ti.' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Icono de Zonas</label>
                                <input type="text" name="zone_icon" class="form-control" 
                                       value="{{ $section->getCustomData('zone_icon', 'fas fa-map-marker-alt') }}"
                                       placeholder="fas fa-map-marker-alt">
                                <small class="text-muted">Este icono se usará en todas las zonas</small>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-map"></i> 6 Zonas de Cobertura</h6>
                            
                            <!-- Zona 1 -->
                            <div class="coverage-preview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Zona 1</label>
                                        <input type="text" name="zone_1_title" class="form-control" 
                                               value="{{ $section->getCustomData('zone_1_title', 'Norte de Quito') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Sectores 1</label>
                                        <input type="text" name="zone_1_areas" class="form-control" 
                                               value="{{ $section->getCustomData('zone_1_areas', 'Carcelén, La Delicia, Comité del Pueblo, Carapungo, Calderón') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Zona 2 -->
                            <div class="coverage-preview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Zona 2</label>
                                        <input type="text" name="zone_2_title" class="form-control" 
                                               value="{{ $section->getCustomData('zone_2_title', 'Centro de Quito') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Sectores 2</label>
                                        <input type="text" name="zone_2_areas" class="form-control" 
                                               value="{{ $section->getCustomData('zone_2_areas', 'Centro Histórico, La Mariscal, La Carolina, González Suárez') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Zona 3 -->
                            <div class="coverage-preview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Zona 3</label>
                                        <input type="text" name="zone_3_title" class="form-control" 
                                               value="{{ $section->getCustomData('zone_3_title', 'Sur de Quito') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Sectores 3</label>
                                        <input type="text" name="zone_3_areas" class="form-control" 
                                               value="{{ $section->getCustomData('zone_3_areas', 'Quitumbe, Solanda, La Magdalena, Chillogallo, Guamaní') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Zona 4 -->
                            <div class="coverage-preview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Zona 4</label>
                                        <input type="text" name="zone_4_title" class="form-control" 
                                               value="{{ $section->getCustomData('zone_4_title', 'Valles') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Sectores 4</label>
                                        <input type="text" name="zone_4_areas" class="form-control" 
                                               value="{{ $section->getCustomData('zone_4_areas', 'Cumbayá, Tumbaco, Conocoto, San Rafael, Sangolquí') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Zona 5 -->
                            <div class="coverage-preview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Zona 5</label>
                                        <input type="text" name="zone_5_title" class="form-control" 
                                               value="{{ $section->getCustomData('zone_5_title', 'Oeste de Quito') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Sectores 5</label>
                                        <input type="text" name="zone_5_areas" class="form-control" 
                                               value="{{ $section->getCustomData('zone_5_areas', 'La Mitad del Mundo, Pomasqui, San Antonio, Nayón') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Zona 6 -->
                            <div class="coverage-preview">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Zona 6</label>
                                        <input type="text" name="zone_6_title" class="form-control" 
                                               value="{{ $section->getCustomData('zone_6_title', 'Sectores Especiales') }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Sectores 6</label>
                                        <input type="text" name="zone_6_areas" class="form-control" 
                                               value="{{ $section->getCustomData('zone_6_areas', 'Consulta disponibilidad para otras zonas metropolitanas') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar Zona de Cobertura
                        </button>
                    </form>
                </div>
            </div>

            {{-- SECCIÓN CTA - Llamada a la Acción --}}
            @elseif($section->name === 'cta')
            <div class="section-card">
                <div class="section-header">
                    <h4><i class="fas fa-rocket me-2"></i> Llamada a la Acción <span class="badge badge-cta ms-2">CTA</span></h4>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.pages.sections.update', [$page->id, $section->id]) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="field-group">
                            <h6><i class="fas fa-bullhorn"></i> CTA Final</h6>
                            <div class="mb-3">
                                <label class="form-label">Título de CTA</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ $section->title ?: '¿Necesitas Ayuda con tus Electrodomésticos?' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="content" class="form-control" rows="3">{{ $section->content ?: 'No esperes más. Contacta a nuestros expertos y recibe atención inmediata. Diagnóstico gratuito y presupuesto sin compromiso.' }}</textarea>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-mouse-pointer"></i> Botones de Acción</h6>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono WhatsApp</label>
                                    <input type="text" name="whatsapp_icon" class="form-control" 
                                           value="{{ $section->getCustomData('whatsapp_icon', 'fab fa-whatsapp') }}"
                                           placeholder="fab fa-whatsapp">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Texto Botón WhatsApp</label>
                                    <input type="text" name="button_primary_text" class="form-control" 
                                           value="{{ $section->getCustomData('button_primary_text', 'WhatsApp') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL WhatsApp</label>
                                    <input type="text" name="whatsapp_url" class="form-control" 
                                           value="{{ $section->getCustomData('whatsapp_url', 'https://wa.me/593987654321') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Contacto</label>
                                    <input type="text" name="contact_icon" class="form-control" 
                                           value="{{ $section->getCustomData('contact_icon', 'fas fa-envelope') }}"
                                           placeholder="fas fa-envelope">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Texto Botón Secundario</label>
                                    <input type="text" name="button_secondary_text" class="form-control" 
                                           value="{{ $section->getCustomData('button_secondary_text', 'Contactar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">URL Contacto</label>
                                    <input type="text" name="contact_url" class="form-control" 
                                           value="{{ $section->getCustomData('contact_url', '#') }}"
                                           placeholder="https://ejemplo.com/contacto">
                                </div>
                            </div>
                        </div>

                        <div class="field-group">
                            <h6><i class="fas fa-info-circle"></i> Información de Contacto</h6>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Horario</label>
                                    <input type="text" name="schedule_icon" class="form-control" 
                                           value="{{ $section->getCustomData('schedule_icon', 'fas fa-clock') }}"
                                           placeholder="fas fa-clock">
                                </div>
                                <div class="col-md-10 mb-3">
                                    <label class="form-label">Horarios de Atención</label>
                                    <input type="text" name="business_hours" class="form-control" 
                                           value="{{ $section->getCustomData('business_hours', 'Lunes a Viernes: 8:00 AM - 6:00 PM | Sábados: 8:00 AM - 4:00 PM') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Icono Teléfono</label>
                                    <input type="text" name="phone_icon" class="form-control" 
                                           value="{{ $section->getCustomData('phone_icon', 'fas fa-phone') }}"
                                           placeholder="fas fa-phone">
                                </div>
                                <div class="col-md-10 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" name="phone_number" class="form-control" 
                                           value="{{ $section->getCustomData('phone_number', '+593 2 234 5678') }}">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Guardar CTA
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
            
            <!-- Botón para crear secciones automáticamente -->
            <form action="{{ route('admin.pages.sections.create-default', $page->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg mt-3">
                    <i class="fas fa-plus me-2"></i> Crear Secciones por Defecto
                </button>
            </form>
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