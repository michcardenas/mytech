{{-- Formulario específico para sección SOLUCIÓN --}}
@php
    $customData = is_array($section->custom_data) ? $section->custom_data : [];
    $beneficios = $customData['beneficios'] ?? [];
    $modulos = $customData['modulos'] ?? [];
@endphp

<div class="alert alert-success mb-4">
    <i class="fas fa-check-circle"></i>
    <strong>Sección Solución:</strong> Presenta tu solución y los beneficios que ofrece. Muestra por qué tu producto/servicio es la respuesta.
</div>

<div class="row">
    <!-- Contenido principal -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-align-left text-primary"></i> Descripción de la Solución
        </label>
        <textarea name="content"
                  class="form-control"
                  rows="3"
                  placeholder="Ej: Desarrollamos soluciones 100% personalizadas que se adaptan a tus procesos...">{{ $section->content }}</textarea>
        <small class="text-muted">Describe brevemente tu solución</small>
    </div>

    <!-- Icon -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-icons text-primary"></i> Icono FontAwesome
        </label>
        <input type="text"
               name="custom_data[icon]"
               class="form-control"
               value="{{ $customData['icon'] ?? 'fa-check-circle' }}"
               placeholder="fa-check-circle">
        <small class="text-muted">
            Busca iconos en <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a>
        </small>
    </div>

    <!-- Lista de Beneficios -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-star text-primary"></i> Beneficios
        </label>
        <div id="beneficios-container">
            @foreach($beneficios as $index => $beneficio)
                <div class="dynamic-field-group mb-2">
                    <div class="d-flex gap-2">
                        <input type="text"
                               name="custom_data[beneficios][]"
                               class="form-control"
                               value="{{ $beneficio }}"
                               placeholder="Ej: 100% adaptado a tus procesos">
                        <button type="button" class="remove-field-btn" onclick="removeField(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="add-field-btn mt-2" onclick="addBeneficio()">
            <i class="fas fa-plus"></i> Agregar Beneficio
        </button>
    </div>

    <!-- Lista de Módulos (Opcional - para ERP, sistemas complejos) -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-cubes text-primary"></i> Módulos (Opcional)
        </label>
        <div id="modulos-container">
            @foreach($modulos as $index => $modulo)
                <div class="dynamic-field-group mb-2">
                    <div class="d-flex gap-2">
                        <input type="text"
                               name="custom_data[modulos][]"
                               class="form-control"
                               value="{{ $modulo }}"
                               placeholder="Ej: Ventas y CRM">
                        <button type="button" class="remove-field-btn" onclick="removeField(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="add-field-btn mt-2" onclick="addModulo()">
            <i class="fas fa-plus"></i> Agregar Módulo
        </button>
        <small class="text-muted d-block mt-1">Solo para sistemas complejos (ERP, plataformas, etc.)</small>
    </div>
</div>

<script>
function addBeneficio() {
    const container = document.getElementById('beneficios-container');
    const newField = document.createElement('div');
    newField.className = 'dynamic-field-group mb-2';
    newField.innerHTML = `
        <div class="d-flex gap-2">
            <input type="text"
                   name="custom_data[beneficios][]"
                   class="form-control"
                   placeholder="Ej: 100% adaptado a tus procesos">
            <button type="button" class="remove-field-btn" onclick="removeField(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newField);
}

function addModulo() {
    const container = document.getElementById('modulos-container');
    const newField = document.createElement('div');
    newField.className = 'dynamic-field-group mb-2';
    newField.innerHTML = `
        <div class="d-flex gap-2">
            <input type="text"
                   name="custom_data[modulos][]"
                   class="form-control"
                   placeholder="Ej: Ventas y CRM">
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
