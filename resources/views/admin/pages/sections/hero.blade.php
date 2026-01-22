{{-- Formulario específico para sección HERO --}}
@php
    $customData = is_array($section->custom_data) ? $section->custom_data : [];
@endphp

<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle"></i>
    <strong>Sección Hero:</strong> La primera impresión de tu landing page. Incluye título llamativo, subtítulo, botón CTA y badge.
</div>

<div class="row">
    <!-- Subtitle -->
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-text-height text-primary"></i> Subtítulo
        </label>
        <input type="text"
               name="custom_data[subtitle]"
               class="form-control"
               value="{{ $customData['subtitle'] ?? '' }}"
               placeholder="Ej: Transformamos tu idea en realidad con tecnología de punta">
        <small class="text-muted">El texto que aparece debajo del título principal</small>
    </div>

    <!-- Badge -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-tag text-primary"></i> Badge (Etiqueta)
        </label>
        <input type="text"
               name="custom_data[badge]"
               class="form-control"
               value="{{ $customData['badge'] ?? '' }}"
               placeholder="Ej: Expertos en Colombia">
        <small class="text-muted">Etiqueta pequeña que aparece arriba del título</small>
    </div>

    <!-- Background Image -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-image text-primary"></i> Imagen de Fondo (URL)
        </label>
        <input type="text"
               name="custom_data[background_image]"
               class="form-control"
               value="{{ $customData['background_image'] ?? '' }}"
               placeholder="/images/landings/hero-background.jpg">
        <small class="text-muted">Ruta de la imagen de fondo (opcional)</small>
    </div>

    <!-- CTA Text -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-mouse-pointer text-primary"></i> Texto del Botón CTA
        </label>
        <input type="text"
               name="custom_data[cta_text]"
               class="form-control"
               value="{{ $customData['cta_text'] ?? '' }}"
               placeholder="Ej: Solicitar Cotización Gratis">
        <small class="text-muted">El texto que aparece en el botón principal</small>
    </div>

    <!-- CTA URL -->
    <div class="col-md-6 mb-3">
        <label class="form-label fw-bold">
            <i class="fas fa-link text-primary"></i> URL del Botón CTA
        </label>
        <input type="text"
               name="custom_data[cta_url]"
               class="form-control"
               value="{{ $customData['cta_url'] ?? '' }}"
               placeholder="/contacto">
        <small class="text-muted">Hacia dónde lleva el botón principal</small>
    </div>
</div>
