{{-- Modal de captura rápida de lead --}}
<div class="modal fade" id="nuevoLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none; border-radius:16px;">
            <form method="POST" action="{{ route('pipeline.leads.store') }}">
                @csrf
                <div class="modal-header" style="border-bottom:1px solid #EEF2F7;">
                    <h5 class="modal-title fw-bold"><i class="fas fa-bolt text-primary me-2"></i>Nuevo lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nombre / contacto *</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Empresa</label>
                            <input type="text" name="empresa" class="form-control" placeholder="Opcional">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Fuente *</label>
                            <select name="fuente" class="form-select" required>
                                @foreach($fuentes as $key => $f)
                                    <option value="{{ $key }}" @selected($key==='workana')>{{ $f['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Enlace (Workana / post / perfil)</label>
                            <input type="url" name="fuente_url" class="form-control" placeholder="https://www.workana.com/job/...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Descripción corta</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="De qué se trata el proyecto / necesidad del cliente"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="cliente@correo.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">WhatsApp / teléfono</label>
                            <input type="text" name="telefono" class="form-control" placeholder="+57 ...">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">Valor estimado</label>
                            <input type="text" inputmode="numeric" name="valor_estimado" class="form-control js-miles" placeholder="0" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Moneda</label>
                            <select name="moneda" class="form-select">
                                <option value="COP">COP</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Etapa</label>
                            <select name="etapa" class="form-select">
                                @foreach(\App\Models\Lead::ETAPAS as $key => $meta)
                                    @if($key !== 'ganado')
                                        <option value="{{ $key }}" @selected($key==='prospecto')>{{ $meta['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small"><i class="fas fa-bell text-warning me-1"></i>Próxima acción (fecha)</label>
                            <input type="datetime-local" name="proxima_accion_at" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">¿Qué sigue?</label>
                            <input type="text" name="proxima_accion_nota" class="form-control" placeholder="Ej: Enviar propuesta / llamar">
                        </div>

                        @if(auth()->user()->hasRole('admin'))
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Asignar a comercial</label>
                                <select name="user_id" class="form-select">
                                    <option value="">— Yo (admin) —</option>
                                    @foreach(\App\Models\User::role('comercial')->orderBy('name')->get() as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #EEF2F7;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Crear lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Formato de miles (3.500.000) para campos .js-miles: muestra con puntos,
     envía el número limpio al guardar. --}}
<script>
(function () {
    function fmt(v) {
        const d = (v || '').toString().replace(/\D/g, '');
        return d ? Number(d).toLocaleString('es-CO') : '';
    }
    document.querySelectorAll('input.js-miles').forEach(function (inp) {
        inp.value = fmt(inp.value);
        inp.addEventListener('input', function () { inp.value = fmt(inp.value); });
        if (inp.form) {
            inp.form.addEventListener('submit', function () {
                inp.value = inp.value.replace(/\D/g, '');
            });
        }
    });
})();
</script>
