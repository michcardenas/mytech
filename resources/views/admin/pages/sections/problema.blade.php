{{-- Formulario específico para sección PROBLEMA --}}
@php
    $customData = is_array($section->custom_data) ? $section->custom_data : [];
    $problemas = $customData['problemas'] ?? [];
@endphp

<div class="alert alert-warning mb-4">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Sección Problema:</strong> Identifica los puntos de dolor de tu cliente. Muestra los problemas que tu solución resuelve.
</div>

<div class="row">
    <!-- Contenido principal -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-align-left text-primary"></i> Descripción del Problema
        </label>
        <textarea name="content"
                  class="form-control"
                  rows="3"
                  placeholder="Ej: Las soluciones genéricas no se adaptan a tu negocio. Pierdes tiempo forzando procesos...">{{ $section->content }}</textarea>
        <small class="text-muted">Describe brevemente el problema principal</small>
    </div>

    <!-- Icon -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-icons text-primary"></i> Icono FontAwesome
        </label>
        <input type="text"
               name="custom_data[icon]"
               class="form-control"
               value="{{ $customData['icon'] ?? 'fa-exclamation-triangle' }}"
               placeholder="fa-exclamation-triangle">
        <small class="text-muted">
            Busca iconos en <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a>
        </small>
    </div>

    <!-- Lista de Problemas -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-list-ul text-primary"></i> Lista de Problemas
        </label>
        <div id="problemas-container">
            @foreach($problemas as $index => $problema)
                <div class="dynamic-field-group mb-2">
                    <div class="d-flex gap-2">
                        <input type="text"
                               name="custom_data[problemas][]"
                               class="form-control"
                               value="{{ $problema }}"
                               placeholder="Ej: Software que no se adapta a tus procesos">
                        <button type="button" class="remove-field-btn" onclick="removeField(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="add-field-btn mt-2" onclick="addProblema()">
            <i class="fas fa-plus"></i> Agregar Problema
        </button>
    </div>
</div>

<script>
function addProblema() {
    const container = document.getElementById('problemas-container');
    const newField = document.createElement('div');
    newField.className = 'dynamic-field-group mb-2';
    newField.innerHTML = `
        <div class="d-flex gap-2">
            <input type="text"
                   name="custom_data[problemas][]"
                   class="form-control"
                   placeholder="Ej: Software que no se adapta a tus procesos">
            <button type="button" class="remove-field-btn" onclick="removeField(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newField);
}

function removeField(btn) {
    btn.closest('.dynamic-field-group').remove();
}
</script>
