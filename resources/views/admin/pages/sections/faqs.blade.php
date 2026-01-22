{{-- Formulario específico para sección FAQs --}}
@php
    $customData = is_array($section->custom_data) ? $section->custom_data : [];
    $items = $customData['items'] ?? [];
@endphp

<div class="alert alert-info mb-4">
    <i class="fas fa-question-circle"></i>
    <strong>Sección FAQs:</strong> Preguntas frecuentes que tus clientes necesitan saber. Reduce dudas y aumenta conversiones.
</div>

<div class="row">
    <!-- Contenido principal (opcional) -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-align-left text-primary"></i> Descripción (Opcional)
        </label>
        <textarea name="content"
                  class="form-control"
                  rows="2"
                  placeholder="Texto introductorio antes de las preguntas (opcional)">{{ $section->content }}</textarea>
    </div>

    <!-- Lista de FAQs -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-list text-primary"></i> Preguntas y Respuestas
        </label>
        <div id="faqs-container">
            @foreach($items as $index => $item)
                <div class="faq-group mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>FAQ #{{ $index + 1 }}</strong>
                        <button type="button" class="remove-field-btn" onclick="removeFaq(this)">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                    <div class="mb-2">
                        <input type="text"
                               name="custom_data[items][{{ $index }}][pregunta]"
                               class="form-control"
                               value="{{ $item['pregunta'] ?? '' }}"
                               placeholder="Pregunta: ¿Cuánto cuesta desarrollar un software a medida?"
                               required>
                    </div>
                    <div>
                        <textarea name="custom_data[items][{{ $index }}][respuesta]"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Respuesta: El costo depende del alcance del proyecto..."
                                  required>{{ $item['respuesta'] ?? '' }}</textarea>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="add-field-btn mt-2" onclick="addFaq()">
            <i class="fas fa-plus"></i> Agregar Nueva Pregunta
        </button>
    </div>
</div>

<style>
.faq-group {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}

.faq-group:hover {
    border-color: #007BFF;
}
</style>

<script>
let faqCounter = {{ count($items) }};

function addFaq() {
    const container = document.getElementById('faqs-container');
    const newFaq = document.createElement('div');
    newFaq.className = 'faq-group mb-3';
    newFaq.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>FAQ #${faqCounter + 1}</strong>
            <button type="button" class="remove-field-btn" onclick="removeFaq(this)">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
        <div class="mb-2">
            <input type="text"
                   name="custom_data[items][${faqCounter}][pregunta]"
                   class="form-control"
                   placeholder="Pregunta: ¿Cuánto cuesta desarrollar un software a medida?"
                   required>
        </div>
        <div>
            <textarea name="custom_data[items][${faqCounter}][respuesta]"
                      class="form-control"
                      rows="3"
                      placeholder="Respuesta: El costo depende del alcance del proyecto..."
                      required></textarea>
        </div>
    `;
    container.appendChild(newFaq);
    faqCounter++;
}

function removeFaq(btn) {
    if (confirm('¿Estás seguro de eliminar esta pregunta?')) {
        btn.closest('.faq-group').remove();
    }
}
</script>
