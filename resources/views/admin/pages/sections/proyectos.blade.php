{{-- Formulario específico para sección PROYECTOS DESTACADOS --}}
@php
    $customData = is_array($section->custom_data) ? $section->custom_data : [];
    $proyectoIds = $customData['proyecto_ids'] ?? [];
    $mostrarTestimonios = $customData['mostrar_testimonios'] ?? true;

    // Obtener todos los proyectos disponibles
    $proyectos = \App\Models\Proyecto::orderBy('nombre')->get();
@endphp

<div class="alert alert-info mb-4">
    <i class="fas fa-briefcase"></i>
    <strong>Sección Proyectos Destacados:</strong> Muestra casos de éxito para generar confianza. Selecciona los proyectos que quieres destacar.
</div>

<div class="row">
    <!-- Contenido principal -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-align-left text-primary"></i> Descripción
        </label>
        <textarea name="content"
                  class="form-control"
                  rows="2"
                  placeholder="Ej: Conoce empresas que ya transformaron su operación">{{ $section->content }}</textarea>
        <small class="text-muted">Texto introductorio para la sección de proyectos</small>
    </div>

    <!-- Selección de Proyectos -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-tasks text-primary"></i> Proyectos a Mostrar
        </label>

        @if($proyectos->count() > 0)
            <div class="proyectos-selector">
                @foreach($proyectos as $proyecto)
                    <div class="form-check mb-2 proyecto-check-item">
                        <input class="form-check-input"
                               type="checkbox"
                               name="custom_data[proyecto_ids][]"
                               value="{{ $proyecto->id }}"
                               id="proyecto_{{ $proyecto->id }}"
                               {{ in_array($proyecto->id, $proyectoIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="proyecto_{{ $proyecto->id }}">
                            <strong>{{ $proyecto->nombre }}</strong>
                            @if($proyecto->cliente)
                                <span class="text-muted">- {{ $proyecto->cliente }}</span>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                No hay proyectos creados aún. <a href="{{ route('admin.proyectos.create') }}" target="_blank">Crear nuevo proyecto</a>
            </div>
        @endif
        <small class="text-muted">Selecciona los proyectos que quieres mostrar en esta landing</small>
    </div>

    <!-- Mostrar Testimonios -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-comment-dots text-primary"></i> Configuración Adicional
        </label>
        <div class="form-check form-switch">
            <input type="hidden" name="custom_data[mostrar_testimonios]" value="0">
            <input class="form-check-input"
                   type="checkbox"
                   name="custom_data[mostrar_testimonios]"
                   value="1"
                   id="mostrar_testimonios"
                   {{ $mostrarTestimonios ? 'checked' : '' }}>
            <label class="form-check-label" for="mostrar_testimonios">
                Mostrar testimonios de clientes (si están disponibles)
            </label>
        </div>
    </div>
</div>

<style>
.proyectos-selector {
    max-height: 300px;
    overflow-y: auto;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    background: #f8fafc;
}

.proyecto-check-item {
    padding: 0.5rem;
    border-radius: 6px;
    transition: background 0.3s ease;
}

.proyecto-check-item:hover {
    background: rgba(0, 123, 255, 0.05);
}

.form-check-input {
    cursor: pointer;
}

.form-check-label {
    cursor: pointer;
}
</style>
