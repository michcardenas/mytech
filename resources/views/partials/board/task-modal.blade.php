{{-- Modal Nueva/Editar tarea (compartido admin + portal). Variables: $project, $equipo, $prioridades, $esAdmin --}}
@php
    $taskStoreUrl = $esAdmin
        ? route('admin.internal-projects.tasks.store', $project)
        : route('portal.developer.tasks.store', $project);
@endphp
<style>
    .tm-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.55); z-index: 9998; align-items: center; justify-content: center; padding: 1rem; }
    .tm-overlay.open { display: flex; }
    .tm-modal { background: #fff; border-radius: 16px; width: 100%; max-width: 480px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); overflow: hidden; }
    .tm-head { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; }
    .tm-head h3 { margin: 0; font-size: 1.05rem; font-weight: 800; color: #0f172a; }
    .tm-close { border: none; background: #f1f5f9; width: 30px; height: 30px; border-radius: 8px; cursor: pointer; color: #64748b; font-size: 0.9rem; }
    .tm-body { padding: 1.25rem; }
    .tm-field { margin-bottom: 0.9rem; }
    .tm-field label { display: block; font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.3px; margin-bottom: 0.3rem; }
    .tm-field input, .tm-field select, .tm-field textarea { width: 100%; padding: 0.55rem 0.7rem; border: 1.5px solid #e2e8f0; border-radius: 9px; font-size: 0.88rem; font-family: inherit; }
    .tm-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
    .tm-foot { display: flex; justify-content: flex-end; gap: 0.5rem; padding: 0.9rem 1.25rem; border-top: 1px solid #f1f5f9; }
    .tm-btn { border: none; border-radius: 9px; padding: 0.55rem 1.1rem; font-weight: 700; font-size: 0.85rem; cursor: pointer; }
    .tm-btn-cancel { background: #f1f5f9; color: #475569; }
    .tm-btn-save { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
</style>
<div class="tm-overlay" id="tmOverlay">
    <div class="tm-modal">
        <form id="tmForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="tmMethod" value="POST">
            <input type="hidden" name="columna" id="tmColumna" value="por_hacer">
            <div class="tm-head">
                <h3 id="tmTitle">Nueva tarea</h3>
                <button type="button" class="tm-close" id="tmClose">&times;</button>
            </div>
            <div class="tm-body">
                <div class="tm-field">
                    <label>Título *</label>
                    <input type="text" name="titulo" id="tmTitulo" required maxlength="255" placeholder="Ej: Maquetar pantalla de login">
                </div>
                <div class="tm-field">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="tmDescripcion" rows="3" placeholder="Detalles, links, criterios..."></textarea>
                </div>
                <div class="tm-row">
                    <div class="tm-field">
                        <label>Prioridad</label>
                        <select name="prioridad" id="tmPrioridad">
                            @foreach($prioridades as $key => $meta)
                                <option value="{{ $key }}" {{ $key === 'media' ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="tm-field">
                        <label>Responsable</label>
                        <select name="developer_id" id="tmDeveloper">
                            <option value="">Sin asignar</option>
                            @foreach($equipo as $d)
                                <option value="{{ $d->id }}">{{ $d->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="tm-field">
                    <label>Fecha límite</label>
                    <input type="date" name="fecha_limite" id="tmFecha">
                </div>
            </div>
            <div class="tm-foot">
                <button type="button" class="tm-btn tm-btn-cancel" id="tmCancel">Cancelar</button>
                <button type="submit" class="tm-btn tm-btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>
<script>
(function () {
    const storeUrl = "{{ $taskStoreUrl }}";
    const overlay = document.getElementById('tmOverlay');
    const form = document.getElementById('tmForm');
    const close = () => overlay.classList.remove('open');

    function reset() {
        form.reset();
        document.getElementById('tmMethod').value = 'POST';
        document.getElementById('tmColumna').value = 'por_hacer';
        document.getElementById('tmPrioridad').value = 'media';
        form.action = storeUrl;
    }

    document.querySelectorAll('[data-nueva-tarea]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            reset();
            document.getElementById('tmColumna').value = btn.getAttribute('data-columna') || 'por_hacer';
            document.getElementById('tmTitle').textContent = 'Nueva tarea';
            overlay.classList.add('open');
        });
    });

    document.querySelectorAll('.js-edit-task').forEach(function (btn) {
        btn.addEventListener('click', function () {
            reset();
            document.getElementById('tmMethod').value = 'PUT';
            form.action = btn.getAttribute('data-update-url');
            document.getElementById('tmTitle').textContent = 'Editar tarea';
            document.getElementById('tmTitulo').value = btn.getAttribute('data-titulo') || '';
            document.getElementById('tmDescripcion').value = btn.getAttribute('data-descripcion') || '';
            document.getElementById('tmPrioridad').value = btn.getAttribute('data-prioridad') || 'media';
            document.getElementById('tmDeveloper').value = btn.getAttribute('data-developer') || '';
            document.getElementById('tmFecha').value = btn.getAttribute('data-fecha') || '';
            overlay.classList.add('open');
        });
    });

    document.getElementById('tmClose').addEventListener('click', close);
    document.getElementById('tmCancel').addEventListener('click', close);
    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
})();
</script>
