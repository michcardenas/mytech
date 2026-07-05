{{-- ═══════════════════════════════════════════════════════════════════
     Repetidor de FAQ — compartido entre create y edit.
     Espera $faqData = array de ['pregunta' => ..., 'respuesta' => ...].
     Genera pares faq_pregunta[] / faq_respuesta[] alineados por índice.
     ═══════════════════════════════════════════════════════════════════ --}}
@php
    $faqData = $faqData ?? [];
    if (empty($faqData)) {
        $faqData = [['pregunta' => '', 'respuesta' => '']]; // una fila vacía por defecto
    }
@endphp

<div class="form-section">
    <h4 class="mb-3"><i class="fas fa-question-circle me-2"></i>Preguntas Frecuentes <span class="seo-badge">FAQPage Schema</span></h4>
    <p class="hint mb-3">
        Lo #1 para <strong>ser citado por ChatGPT, Perplexity y las AI Overviews de Google</strong> y ganar los "People Also Ask".
        Escribe 3-5 preguntas <em>en lenguaje natural</em>, tal como las googlea tu cliente ideal
        (ej: "¿cuánto cuesta una app de servicios a domicilio?"). Se renderiza schema <code>FAQPage</code> automáticamente.
    </p>

    <div id="faqContainer">
        @foreach($faqData as $faq)
            <div class="faq-row border rounded p-3 mb-3" style="background:#0d1117;border-color:#30363d !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="mb-0"><i class="fas fa-grip-lines me-1 text-muted"></i>Pregunta / Respuesta</label>
                    <button type="button" class="btn btn-sm btn-outline-danger faq-remove" title="Quitar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <input type="text" class="form-control mb-2" name="faq_pregunta[]"
                       maxlength="300" placeholder="¿Pregunta que busca tu cliente?"
                       value="{{ $faq['pregunta'] ?? '' }}">
                <textarea class="form-control" name="faq_respuesta[]" rows="3" maxlength="2000"
                          placeholder="Respuesta clara y honesta. 40-60 palabras funcionan mejor para featured snippets.">{{ $faq['respuesta'] ?? '' }}</textarea>
            </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-outline-primary btn-sm" id="faqAddBtn">
        <i class="fas fa-plus me-1"></i>Agregar pregunta
    </button>
</div>

{{-- Template para clonar (fuera del container, sin name para no enviarse) --}}
<template id="faqRowTemplate">
    <div class="faq-row border rounded p-3 mb-3" style="background:#0d1117;border-color:#30363d;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="mb-0"><i class="fas fa-grip-lines me-1 text-muted"></i>Pregunta / Respuesta</label>
            <button type="button" class="btn btn-sm btn-outline-danger faq-remove" title="Quitar">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <input type="text" class="form-control mb-2" name="faq_pregunta[]" maxlength="300" placeholder="¿Pregunta que busca tu cliente?">
        <textarea class="form-control" name="faq_respuesta[]" rows="3" maxlength="2000" placeholder="Respuesta clara y honesta. 40-60 palabras funcionan mejor para featured snippets."></textarea>
    </div>
</template>

<script>
(function () {
    const container = document.getElementById('faqContainer');
    const addBtn    = document.getElementById('faqAddBtn');
    const template  = document.getElementById('faqRowTemplate');
    if (!container || !addBtn || !template) return;

    addBtn.addEventListener('click', function () {
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
    });

    container.addEventListener('click', function (e) {
        const btn = e.target.closest('.faq-remove');
        if (!btn) return;
        const rows = container.querySelectorAll('.faq-row');
        if (rows.length <= 1) {
            // no borrar la última fila, solo limpiarla
            const row = btn.closest('.faq-row');
            row.querySelectorAll('input, textarea').forEach(el => el.value = '');
        } else {
            btn.closest('.faq-row').remove();
        }
    });
})();
</script>
