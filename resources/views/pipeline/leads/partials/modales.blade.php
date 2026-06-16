{{-- ===== Modal: marcar perdido ===== --}}
<div class="modal fade" id="perdidoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;">
            <form method="POST" action="{{ route('pipeline.leads.perdido', $lead) }}">@csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">Marcar como perdido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label small fw-semibold">Motivo (opcional)</label>
                    <input type="text" name="motivo_perdido" class="form-control" placeholder="Ej: presupuesto, no respondió, eligió otra opción">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger">Marcar perdido</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal: propuesta ===== --}}
<div class="modal fade" id="propuestaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;">
            <form method="POST" action="{{ route('pipeline.proposals.store', $lead) }}">@csrf
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fas fa-file-invoice text-primary me-2"></i>Nueva propuesta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12"><label class="form-label small fw-semibold">Título</label>
                            <input name="titulo" class="form-control form-control-sm" placeholder="Propuesta {{ $lead->nombre }}"></div>
                        <div class="col-7"><label class="form-label small fw-semibold">Monto</label>
                            <input type="number" step="0.01" min="0" name="monto" class="form-control form-control-sm" value="{{ $lead->valor_estimado }}"></div>
                        <div class="col-5"><label class="form-label small fw-semibold">Moneda</label>
                            <select name="moneda" class="form-select form-select-sm">
                                <option value="COP" @selected($lead->moneda==='COP')>COP</option>
                                <option value="USD" @selected($lead->moneda==='USD')>USD</option>
                            </select></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Estado</label>
                            <select name="estado" class="form-select form-select-sm">
                                @foreach($estadosPropuesta as $k => $e)<option value="{{ $k }}" @selected($k==='enviada')>{{ $e['label'] }}</option>@endforeach
                            </select></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Fecha de envío</label>
                            <input type="date" name="enviada_at" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}"></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Enlace</label>
                            <input type="url" name="url" class="form-control form-control-sm" placeholder="https://..."></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Notas</label>
                            <textarea name="notas" class="form-control form-control-sm" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar propuesta</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal: reunión ===== --}}
<div class="modal fade" id="reunionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;">
            <form method="POST" action="{{ route('pipeline.meetings.store', $lead) }}">@csrf
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fas fa-calendar-check text-primary me-2"></i>Agendar reunión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12"><label class="form-label small fw-semibold">Título</label>
                            <input name="titulo" class="form-control form-control-sm" placeholder="Reunión con {{ $lead->nombre }}"></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Tipo</label>
                            <select name="tipo" class="form-select form-select-sm">
                                @foreach($tiposReunion as $k => $t)<option value="{{ $k }}">{{ $t['label'] }}</option>@endforeach
                            </select></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Fecha y hora</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control form-control-sm" required></div>
                        <div class="col-12"><label class="form-label small fw-semibold">Notas</label>
                            <textarea name="notas" class="form-control form-control-sm" rows="2"></textarea></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Agendar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== Modal: agendar reunión de cierre (calendario del admin) ===== --}}
<div class="modal fade" id="agendarCierreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none;border-radius:16px;">
            <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fas fa-calendar-plus text-primary me-2"></i>Agendar reunión de cierre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body" style="max-height:65vh;overflow-y:auto">
                <div id="cierreSlotsLoading" class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm me-2"></div> Cargando disponibilidad…
                </div>
                <div id="cierreSlotsContent"></div>
            </div>
        </div>
    </div>
</div>
<form id="cierreBookForm" method="POST" action="{{ route('pipeline.leads.book', $lead) }}" class="d-none">
    @csrf
    <input type="hidden" name="scheduled_at" id="cierreScheduledAt">
</form>
<script>
(function () {
    const modal = document.getElementById('agendarCierreModal');
    if (!modal) return;
    const loading = document.getElementById('cierreSlotsLoading');
    const content = document.getElementById('cierreSlotsContent');
    const form    = document.getElementById('cierreBookForm');
    const input   = document.getElementById('cierreScheduledAt');

    modal.addEventListener('show.bs.modal', function () {
        loading.style.display = 'block';
        content.innerHTML = '';
        fetch("{{ route('pipeline.availability') }}", { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                loading.style.display = 'none';
                if (!data.connected) {
                    content.innerHTML = '<div class="alert alert-warning mb-0">' + (data.message || 'El admin no ha conectado su calendario.') + '</div>';
                    return;
                }
                if (!data.days || !data.days.length) {
                    content.innerHTML = '<div class="alert alert-info mb-0">No hay horarios libres en los próximos días.</div>';
                    return;
                }
                let html = '<p class="text-muted small mb-1">Disponibilidad de <strong>' + (data.host || 'el admin') + '</strong>, cada 15 min. Elige un horario libre y se crea el evento con Google Meet.</p>';
                html += '<div class="small mb-3" style="color:#94A3B8"><span class="badge" style="background:#2563EB">Libre</span> = clic para agendar &nbsp;·&nbsp; <span class="badge" style="background:#E5E7EB;color:#64748B">Ocupado</span> = no disponible</div>';
                data.days.forEach(function (day) {
                    html += '<div class="mb-3"><div class="fw-bold small text-capitalize mb-1">' + day.label + '</div><div class="d-flex flex-wrap gap-1">';
                    day.slots.forEach(function (s) {
                        if (s.free) {
                            html += '<button type="button" class="btn btn-sm btn-outline-primary cierre-slot" data-start="' + s.start + '">' + s.label + '</button>';
                        } else {
                            html += '<span class="btn btn-sm disabled" style="background:#F1F5F9;color:#94A3B8;border:1px solid #E5E7EB;cursor:not-allowed" title="Ocupado">' + s.label + '</span>';
                        }
                    });
                    html += '</div></div>';
                });
                content.innerHTML = html;
                content.querySelectorAll('.cierre-slot').forEach(function (b) {
                    b.addEventListener('click', function () {
                        input.value = b.getAttribute('data-start');
                        content.querySelectorAll('.cierre-slot').forEach(x => x.disabled = true);
                        b.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        form.submit();
                    });
                });
            })
            .catch(function () {
                loading.style.display = 'none';
                content.innerHTML = '<div class="alert alert-danger mb-0">No se pudo cargar la disponibilidad.</div>';
            });
    });
})();
</script>

@if($isAdmin && $lead->estado === 'ganado' && ! $lead->internal_project_id)
{{-- ===== Modal: convertir a proyecto (solo admin) ===== --}}
<div class="modal fade" id="convertirModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:16px;">
            <form method="POST" action="{{ route('pipeline.leads.convert', $lead) }}">@csrf
                <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fas fa-rocket text-primary me-2"></i>Convertir en proyecto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p class="text-muted small">Se creará un Proyecto interno con los datos del lead. La comisión se calcula sobre el precio cerrado.</p>
                    <div class="row g-2">
                        <div class="col-12"><label class="form-label small fw-semibold">Nombre del proyecto</label>
                            <input name="nombre" class="form-control form-control-sm" value="{{ $convDefaults['nombre'] ?? $lead->nombre }}" required></div>
                        <div class="col-7"><label class="form-label small fw-semibold">Precio cerrado</label>
                            <input type="number" step="0.01" min="0" name="precio" class="form-control form-control-sm" value="{{ $convDefaults['precio'] ?? $lead->valor_estimado }}" required></div>
                        <div class="col-5"><label class="form-label small fw-semibold">Moneda</label>
                            <select name="moneda" class="form-select form-select-sm">
                                <option value="COP" @selected(($convDefaults['moneda'] ?? $lead->moneda)==='COP')>COP</option>
                                <option value="USD" @selected(($convDefaults['moneda'] ?? $lead->moneda)==='USD')>USD</option>
                            </select></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Tipo de comisión</label>
                            <select name="comision_tipo" class="form-select form-select-sm">
                                <option value="porcentaje" @selected(($convDefaults['comision_tipo'] ?? 'porcentaje')==='porcentaje')>Porcentaje (%)</option>
                                <option value="monto" @selected(($convDefaults['comision_tipo'] ?? '')==='monto')>Monto fijo</option>
                            </select></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Valor comisión</label>
                            <input type="number" step="0.01" min="0" name="comision_valor" class="form-control form-control-sm" value="{{ $convDefaults['comision_valor'] ?? 10 }}"></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Fecha de inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="{{ now()->format('Y-m-d') }}"></div>
                        <div class="col-6"><label class="form-label small fw-semibold">Estado</label>
                            <select name="estado" class="form-select form-select-sm">
                                <option value="en_progreso" selected>En progreso</option>
                                <option value="cotizado">Cotizado</option>
                                <option value="completado">Completado</option>
                            </select></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary"><i class="fas fa-rocket me-1"></i> Crear proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
