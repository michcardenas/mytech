{{-- Formulario específico para sección CTA FINAL --}}
@php
    $customData = is_array($section->custom_data) ? $section->custom_data : [];
@endphp

<div class="alert alert-success mb-4">
    <i class="fas fa-bullhorn"></i>
    <strong>Sección CTA Final:</strong> El último empujón para convertir visitantes en clientes. Call-to-Action final de tu landing page.
</div>

<div class="row">
    <!-- Contenido principal -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-align-left text-primary"></i> Texto Principal
        </label>
        <textarea name="content"
                  class="form-control"
                  rows="2"
                  placeholder="Ej: Agenda una consultoría gratuita de 30 minutos y descubre cómo podemos ayudarte">{{ $section->content }}</textarea>
        <small class="text-muted">El mensaje que acompaña al botón de acción</small>
    </div>

    <!-- Button Text -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-mouse-pointer text-primary"></i> Texto del Botón
        </label>
        <input type="text"
               name="custom_data[button_text]"
               class="form-control"
               value="{{ $customData['button_text'] ?? '' }}"
               placeholder="Ej: Agendar Consultoría Gratis">
        <small class="text-muted">El texto que aparece en el botón principal</small>
    </div>

    <!-- Button URL -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-link text-primary"></i> URL del Botón
        </label>
        <input type="text"
               name="custom_data[button_url]"
               class="form-control"
               value="{{ $customData['button_url'] ?? '' }}"
               placeholder="/contacto">
        <small class="text-muted">Hacia dónde lleva el botón (puede ser una ruta interna o URL externa)</small>
    </div>

    <!-- Secondary Text -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-comment-dots text-primary"></i> Texto Secundario (Opcional)
        </label>
        <input type="text"
               name="custom_data[secondary_text]"
               class="form-control"
               value="{{ $customData['secondary_text'] ?? '' }}"
               placeholder="Ej: O llámanos al +57 300 123 4567">
        <small class="text-muted">Texto adicional debajo del botón (opcional, puede ser teléfono, horarios, etc.)</small>
    </div>

    <!-- Background Color -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-palette text-primary"></i> Color de Fondo
        </label>
        <div class="input-group">
            <input type="color"
                   name="custom_data[background_color]"
                   class="form-control form-control-color"
                   value="{{ $customData['background_color'] ?? '#007BFF' }}"
                   title="Selecciona un color">
            <input type="text"
                   class="form-control"
                   value="{{ $customData['background_color'] ?? '#007BFF' }}"
                   readonly>
        </div>
        <small class="text-muted">Color de fondo para la sección CTA</small>
    </div>
</div>

<script>
// Sincronizar color picker con input de texto
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.querySelector('input[type="color"][name="custom_data[background_color]"]');
    const colorText = colorPicker?.nextElementSibling;

    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
        });
    }
});
</script>
