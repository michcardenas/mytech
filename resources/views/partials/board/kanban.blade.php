{{--
    Tablero Kanban compartido (admin y portal del dev).
    Variables esperadas:
      $project, $columnas, $prioridades, $tareasPorColumna, $equipo, $esAdmin
    Rutas de mover/toggle se resuelven según $esAdmin.
--}}
<style>
    .kb-wrap { --kb-line:#e5e9f0; }
    .kb-board { display: grid; grid-auto-flow: column; grid-auto-columns: minmax(270px, 1fr); gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem; align-items: start; }
    .kb-col { background: #f1f4f8; border-radius: 14px; padding: 0.6rem; min-height: 120px; display: flex; flex-direction: column; }
    .kb-col-head { display: flex; align-items: center; gap: 0.5rem; padding: 0.3rem 0.5rem 0.6rem; font-weight: 800; font-size: 0.82rem; color: #334155; }
    .kb-col-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .kb-col-count { margin-left: auto; background: rgba(0,0,0,0.07); color: #475569; font-size: 0.72rem; font-weight: 700; padding: 0.08rem 0.5rem; border-radius: 999px; }
    .kb-cards { display: flex; flex-direction: column; gap: 0.5rem; min-height: 40px; flex: 1; }
    .kb-card { background: #fff; border: 1px solid var(--kb-line); border-left: 4px solid var(--c, #94a3b8); border-radius: 10px; padding: 0.65rem 0.7rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); cursor: grab; }
    .kb-card:active { cursor: grabbing; }
    .kb-card.is-vencida { border-color: #fca5a5; background: #fff7f7; }
    .kb-card-top { display: flex; align-items: flex-start; gap: 0.45rem; }
    .kb-title { font-size: 0.86rem; font-weight: 700; color: #1e293b; line-height: 1.3; flex: 1; word-break: break-word; }
    .kb-actions { display: flex; gap: 0.25rem; flex-shrink: 0; }
    .kb-ibtn { border: none; background: #f1f5f9; color: #64748b; width: 24px; height: 24px; border-radius: 7px; font-size: 0.7rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all .15s; }
    .kb-ibtn:hover { background: #e2e8f0; color: #0f172a; }
    .kb-ibtn.del:hover { background: #fee2e2; color: #dc2626; }
    .kb-meta { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.5rem; }
    .kb-chip { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.68rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 999px; background: #f1f5f9; color: #475569; }
    .kb-chip.prio-alta { background: #fee2e2; color: #b91c1c; }
    .kb-chip.prio-media { background: #dbeafe; color: #1d4ed8; }
    .kb-chip.prio-baja { background: #e2e8f0; color: #475569; }
    .kb-chip.venc { background: #fee2e2; color: #b91c1c; }
    .kb-chip .kb-av { width: 15px; height: 15px; border-radius: 50%; background: #7c3aed; color: #fff; font-size: 0.55rem; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; }
    .kb-progress-row { display: flex; align-items: center; gap: 0.5rem; margin-top: 0.55rem; }
    .kb-progress { flex: 1; height: 6px; background: #eef2f7; border-radius: 4px; overflow: hidden; }
    .kb-progress > span { display: block; height: 100%; background: #16a34a; border-radius: 4px; transition: width .3s; }
    .kb-pct { font-size: 0.7rem; font-weight: 800; color: #16a34a; min-width: 30px; text-align: right; }
    .kb-subs { margin-top: 0.5rem; }
    .kb-subs > summary { list-style: none; cursor: pointer; font-size: 0.72rem; font-weight: 700; color: #64748b; padding: 0.2rem 0; user-select: none; }
    .kb-subs > summary::-webkit-details-marker { display: none; }
    .kb-subs > summary::before { content: '\25B8'; margin-right: 0.35rem; display: inline-block; transition: transform .2s; }
    .kb-subs[open] > summary::before { transform: rotate(90deg); }
    .kb-sub { display: flex; align-items: center; gap: 0.45rem; font-size: 0.78rem; color: #334155; padding: 0.25rem 0.1rem; }
    .kb-sub input[type=checkbox] { width: 15px; height: 15px; accent-color: #16a34a; cursor: pointer; flex-shrink: 0; }
    .kb-sub.done span.t { text-decoration: line-through; color: #94a3b8; }
    .kb-sub .t { flex: 1; word-break: break-word; }
    .kb-sub .kb-subdel { border: none; background: transparent; color: #cbd5e1; cursor: pointer; font-size: 0.7rem; }
    .kb-sub .kb-subdel:hover { color: #dc2626; }
    .kb-subadd { display: flex; gap: 0.3rem; margin-top: 0.35rem; }
    .kb-subadd input { flex: 1; font-size: 0.75rem; padding: 0.3rem 0.5rem; border: 1px solid var(--kb-line); border-radius: 7px; }
    .kb-subadd select { font-size: 0.72rem; padding: 0.3rem 0.35rem; border: 1px solid var(--kb-line); border-radius: 7px; max-width: 95px; }
    .kb-subadd button { border: none; background: #e0e7ff; color: #4338ca; border-radius: 7px; padding: 0 0.6rem; font-weight: 800; cursor: pointer; }
    .kb-reassign { margin: 0; }
    .kb-reassign select { font-size: 0.68rem; padding: 0.15rem 0.25rem; border: 1px solid var(--kb-line); border-radius: 6px; max-width: 88px; color: #64748b; background: #fff; }
    .kb-sub-resp .kb-av.sm { width: 16px; height: 16px; font-size: 0.55rem; }
    .kb-av.sm { width: 16px; height: 16px; border-radius: 50%; background: #7c3aed; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; }
    /* Archivos por tarea */
    .kb-files { margin-top: 0.45rem; }
    .kb-files > summary { list-style: none; cursor: pointer; font-size: 0.72rem; font-weight: 700; color: #64748b; padding: 0.2rem 0; user-select: none; }
    .kb-files > summary::-webkit-details-marker { display: none; }
    .kb-files > summary::before { content: '\25B8'; margin-right: 0.35rem; display: inline-block; transition: transform .2s; }
    .kb-files[open] > summary::before { transform: rotate(90deg); }
    .kb-filecount, .kb-subcount { background: #eef2f7; color: #475569; font-size: 0.65rem; font-weight: 800; padding: 0.02rem 0.4rem; border-radius: 999px; }
    .kb-file { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; padding: 0.2rem 0.1rem; }
    .kb-file-link { flex: 1; color: #2563eb; text-decoration: none; word-break: break-word; }
    .kb-file-link:hover { text-decoration: underline; }
    .kb-file-size { font-size: 0.65rem; color: #94a3b8; }
    .kb-fileadd { display: flex; gap: 0.3rem; margin-top: 0.35rem; align-items: center; }
    .kb-fileadd input[type=file] { flex: 1; font-size: 0.68rem; min-width: 0; }
    .kb-fileadd button { border: none; background: #dcfce7; color: #15803d; border-radius: 7px; padding: 0.25rem 0.55rem; cursor: pointer; }
    .kb-addcard { margin-top: 0.5rem; border: 1px dashed #cbd5e1; background: transparent; color: #64748b; border-radius: 9px; padding: 0.5rem; font-size: 0.78rem; font-weight: 700; cursor: pointer; width: 100%; transition: all .15s; }
    .kb-addcard:hover { border-color: #7c3aed; color: #7c3aed; background: rgba(124,58,237,0.04); }
    .kb-empty { font-size: 0.75rem; color: #b6c0cf; text-align: center; padding: 0.8rem 0; font-style: italic; }
    .sortable-ghost { opacity: 0.4; }
    .sortable-drag { transform: rotate(1.5deg); }
    .kb-toast { position: fixed; top: 20px; right: 20px; z-index: 9999; padding: 0.75rem 1.1rem; border-radius: 10px; color: #fff; font-weight: 700; font-size: 0.82rem; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
</style>

<div class="kb-wrap">
    <div class="kb-board">
        @foreach($columnas as $key => $meta)
            @php $tareas = $tareasPorColumna[$key] ?? collect(); @endphp
            <div class="kb-col">
                <div class="kb-col-head">
                    <span class="kb-col-dot" style="background: {{ $meta['color'] }}"></span>
                    {{ $meta['label'] }}
                    <span class="kb-col-count">{{ $tareas->count() }}</span>
                </div>
                <div class="kb-cards" data-columna="{{ $key }}">
                    @foreach($tareas as $t)
                        @include('partials.board.card', ['t' => $t])
                    @endforeach
                </div>
                @if($puedeEditar)
                    <button type="button" class="kb-addcard" data-nueva-tarea data-columna="{{ $key }}">
                        <i class="fas fa-plus"></i> Agregar tarea
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function toast(msg, ok) {
        const d = document.createElement('div');
        d.className = 'kb-toast';
        d.style.background = ok ? '#16a34a' : '#dc2626';
        d.textContent = msg;
        document.body.appendChild(d);
        setTimeout(() => d.remove(), 2600);
    }

    function refreshCounts() {
        document.querySelectorAll('.kb-col').forEach(function (col) {
            const cards = col.querySelector('.kb-cards');
            const count = col.querySelector('.kb-col-count');
            if (cards && count) count.textContent = cards.querySelectorAll('.kb-card').length;
        });
    }

    // Drag & drop entre columnas
    document.querySelectorAll('.kb-cards').forEach(function (col) {
        new Sortable(col, {
            group: 'board',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function (evt) {
                const card = evt.item;
                const dest = evt.to;
                const columna = dest.getAttribute('data-columna');
                const moveUrl = card.getAttribute('data-move-url');
                const ids = Array.from(dest.querySelectorAll('.kb-card')).map(c => c.getAttribute('data-id'));
                refreshCounts();

                fetch(moveUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ columna: columna, ids: ids })
                }).then(r => { if (!r.ok) throw new Error('move'); return r.json(); })
                  .catch(() => toast('No se pudo mover la tarea. Recarga la página.', false));
            }
        });
    });

    // Marcar / desmarcar subtareas
    document.addEventListener('change', function (e) {
        const cb = e.target;
        if (!cb.classList.contains('js-subtoggle')) return;
        const url = cb.getAttribute('data-toggle-url');
        const row = cb.closest('.kb-sub');

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        }).then(r => { if (!r.ok) throw new Error('toggle'); return r.json(); })
          .then(data => {
              row.classList.toggle('done', data.hecha);
              const card = cb.closest('.kb-card');
              const bar = card.querySelector('.kb-progress > span');
              const pct = card.querySelector('.kb-pct');
              const sum = card.querySelector('.kb-subs > summary .kb-subcount');
              if (bar && data.progreso !== null) bar.style.width = data.progreso + '%';
              if (pct && data.progreso !== null) pct.textContent = data.progreso + '%';
              if (sum) {
                  const total = card.querySelectorAll('.kb-sub').length;
                  const done = card.querySelectorAll('.kb-sub input:checked').length;
                  sum.textContent = done + '/' + total;
              }
          })
          .catch(() => { cb.checked = !cb.checked; toast('No se pudo actualizar la subtarea.', false); });
    });
})();
</script>
