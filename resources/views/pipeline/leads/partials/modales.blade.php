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

{{-- ===== Modal: agendar reunión de cierre (calendario interactivo) ===== --}}
<div class="modal fade" id="agendarCierreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none;border-radius:16px;">
            <div class="modal-header"><h5 class="modal-title fw-bold"><i class="fas fa-calendar-plus text-primary me-2"></i>Agendar reunión de cierre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div id="cierreLoading" class="text-center text-muted py-4">
                    <div class="spinner-border spinner-border-sm me-2"></div> Cargando disponibilidad…
                </div>
                <div id="cierreMsg"></div>
                <div id="cierreWrap" style="display:none">
                    <p class="text-muted small mb-3">Disponibilidad de <strong id="cierreHost">el admin</strong>. Elige un día y un horario; se crea el evento con Google Meet.</p>
                    <div class="cal-grid">
                        <div class="cal-left">
                            <div class="cal-head">
                                <button type="button" id="calPrev" class="cal-nav">‹</button>
                                <span id="calMonth"></span>
                                <button type="button" id="calNext" class="cal-nav">›</button>
                            </div>
                            <div class="cal-dow"><span>L</span><span>M</span><span>X</span><span>J</span><span>V</span><span>S</span><span>D</span></div>
                            <div id="calDays" class="cal-days"></div>
                        </div>
                        <div class="cal-right">
                            <div id="calDayLabel" class="fw-bold mb-2 text-muted">Elige un día</div>
                            <div id="calSlots" class="cal-slots"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="cierreBookForm" method="POST" action="{{ route('pipeline.leads.book', $lead) }}" class="d-none">
    @csrf
    <input type="hidden" name="scheduled_at" id="cierreScheduledAt">
</form>
<style>
    .cal-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
    @media (max-width:600px){ .cal-grid{ grid-template-columns:1fr; } }
    .cal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem; }
    .cal-head span { font-weight:800; color:#0F172A; text-transform:capitalize; }
    .cal-nav { border:1px solid #E5E7EB; background:#fff; border-radius:8px; width:30px; height:30px; cursor:pointer; color:#334155; line-height:1; font-size:1.1rem; }
    .cal-nav:hover:not(:disabled){ background:#F1F5F9; }
    .cal-nav:disabled { opacity:.35; cursor:not-allowed; }
    .cal-dow { display:grid; grid-template-columns:repeat(7,1fr); text-align:center; font-size:.68rem; font-weight:700; color:#94A3B8; margin-bottom:.35rem; }
    .cal-days { display:grid; grid-template-columns:repeat(7,1fr); gap:5px; }
    .cal-day { aspect-ratio:1; display:flex; align-items:center; justify-content:center; border-radius:9px; font-size:.85rem; color:#CBD5E1; }
    .cal-day.av { background:#EFF6FF; color:#1D4ED8; font-weight:700; cursor:pointer; }
    .cal-day.av:hover { background:#DBEAFE; }
    .cal-day.sel { background:#2563EB; color:#fff; }
    .cal-slots { display:flex; flex-wrap:wrap; gap:.4rem; max-height:280px; overflow-y:auto; align-content:flex-start; }
    .cal-slot { border:1px solid #BFDBFE; background:#fff; color:#1D4ED8; border-radius:8px; padding:.35rem .6rem; font-size:.83rem; font-weight:600; cursor:pointer; }
    .cal-slot:hover { background:#2563EB; color:#fff; border-color:#2563EB; }
    .cal-slot.busy { border-color:#E5E7EB; background:#F8FAFC; color:#CBD5E1; cursor:not-allowed; text-decoration:line-through; }
    .cal-empty { color:#94A3B8; font-size:.85rem; padding:1rem 0; }
</style>
<script>
(function () {
    const modal = document.getElementById('agendarCierreModal');
    if (!modal) return;
    const loading = document.getElementById('cierreLoading');
    const msg     = document.getElementById('cierreMsg');
    const wrap    = document.getElementById('cierreWrap');
    const form    = document.getElementById('cierreBookForm');
    const input   = document.getElementById('cierreScheduledAt');
    const calDays = document.getElementById('calDays');
    const calMonth = document.getElementById('calMonth');
    const calSlots = document.getElementById('calSlots');
    const calDayLabel = document.getElementById('calDayLabel');
    const btnPrev = document.getElementById('calPrev');
    const btnNext = document.getElementById('calNext');
    const MESES = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const DIAS  = ['domingo','lunes','martes','miércoles','jueves','viernes','sábado'];

    let availMap = {}, viewYear, viewMonth, minYM, maxYM, selectedDate = null;

    const ym = (y, m) => y + '-' + String(m + 1).padStart(2, '0');
    const hasFree = (date) => (availMap[date] || []).some(s => s.free);

    function renderCalendar() {
        calMonth.textContent = MESES[viewMonth] + ' ' + viewYear;
        const offset = (new Date(viewYear, viewMonth, 1).getDay() + 6) % 7;
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        let html = '';
        for (let i = 0; i < offset; i++) html += '<div class="cal-day"></div>';
        for (let d = 1; d <= daysInMonth; d++) {
            const ds = ym(viewYear, viewMonth) + '-' + String(d).padStart(2, '0');
            const cls = hasFree(ds) ? 'cal-day av' + (ds === selectedDate ? ' sel' : '') : 'cal-day';
            html += '<div class="' + cls + '" data-date="' + ds + '">' + d + '</div>';
        }
        calDays.innerHTML = html;
        calDays.querySelectorAll('.cal-day.av').forEach(c => c.addEventListener('click', () => selectDay(c.getAttribute('data-date'))));
        btnPrev.disabled = ym(viewYear, viewMonth) <= minYM;
        btnNext.disabled = ym(viewYear, viewMonth) >= maxYM;
    }

    function selectDay(date) {
        selectedDate = date;
        renderCalendar();
        const slots = availMap[date] || [];
        const dObj = new Date(date + 'T00:00:00');
        const txt = DIAS[dObj.getDay()] + ' ' + dObj.getDate() + ' de ' + MESES[dObj.getMonth()];
        calDayLabel.textContent = txt.charAt(0).toUpperCase() + txt.slice(1);
        calDayLabel.classList.remove('text-muted');
        if (!slots.length) { calSlots.innerHTML = '<div class="cal-empty">Sin horarios este día.</div>'; return; }
        let html = '';
        slots.forEach(s => {
            html += s.free
                ? '<button type="button" class="cal-slot" data-start="' + s.start + '">' + s.label + '</button>'
                : '<span class="cal-slot busy" title="Ocupado">' + s.label + '</span>';
        });
        calSlots.innerHTML = html;
        calSlots.querySelectorAll('.cal-slot[data-start]').forEach(b => b.addEventListener('click', () => {
            input.value = b.getAttribute('data-start');
            calSlots.querySelectorAll('.cal-slot').forEach(x => x.style.pointerEvents = 'none');
            b.innerHTML = '…';
            form.submit();
        }));
    }

    btnPrev.addEventListener('click', () => { if (viewMonth === 0) { viewMonth = 11; viewYear--; } else viewMonth--; renderCalendar(); });
    btnNext.addEventListener('click', () => { if (viewMonth === 11) { viewMonth = 0; viewYear++; } else viewMonth++; renderCalendar(); });

    modal.addEventListener('show.bs.modal', function () {
        loading.style.display = 'block'; msg.innerHTML = ''; wrap.style.display = 'none'; selectedDate = null;
        fetch("{{ route('pipeline.availability') }}", { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                loading.style.display = 'none';
                if (!data.connected) { msg.innerHTML = '<div class="alert alert-warning mb-0">' + (data.message || 'El admin no ha conectado su calendario.') + '</div>'; return; }
                if (data.reauth) { msg.innerHTML = '<div class="alert alert-warning mb-0">' + (data.message || 'El calendario del administrador necesita reconectarse con Google.') + '</div>'; return; }
                if (!data.days || !data.days.length) { msg.innerHTML = '<div class="alert alert-info mb-0">No hay horarios disponibles en los próximos días.</div>'; return; }
                document.getElementById('cierreHost').textContent = data.host || 'el admin';
                availMap = {};
                data.days.forEach(d => { availMap[d.date] = d.slots; });
                const fechas = data.days.map(d => d.date).sort();
                const firstFree = data.days.find(d => d.slots.some(s => s.free)) || data.days[0];
                const fd = new Date(firstFree.date + 'T00:00:00');
                viewYear = fd.getFullYear(); viewMonth = fd.getMonth();
                minYM = fechas[0].slice(0, 7); maxYM = fechas[fechas.length - 1].slice(0, 7);
                wrap.style.display = 'block';
                renderCalendar();
                selectDay(firstFree.date);
            })
            .catch(() => { loading.style.display = 'none'; msg.innerHTML = '<div class="alert alert-danger mb-0">No se pudo cargar la disponibilidad.</div>'; });
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
