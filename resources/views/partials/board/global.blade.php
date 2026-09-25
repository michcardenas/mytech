{{--
    Tablero general multi-proyecto (kanban + lista). Variables:
      $tareasPorColumna, $tasks, $columnas, $prioridades, $esAdmin
--}}
<style>
    .gb-toggle { display: inline-flex; background: #eef2f7; border-radius: 10px; padding: 0.25rem; gap: 0.25rem; }
    .gb-toggle button { border: none; background: transparent; padding: 0.4rem 0.9rem; border-radius: 8px; font-weight: 700; font-size: 0.8rem; color: #64748b; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; }
    .gb-toggle button.active { background: #fff; color: #2563eb; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
    .gb-view { display: none; }
    .gb-view.active { display: block; }

    /* Kanban */
    .gb-board { display: grid; grid-auto-flow: column; grid-auto-columns: minmax(270px, 1fr); gap: 1rem; overflow-x: auto; padding-bottom: 0.5rem; align-items: start; }
    .gb-col { background: #f1f4f8; border-radius: 14px; padding: 0.6rem; min-height: 100px; }
    .gb-col-head { display: flex; align-items: center; gap: 0.5rem; padding: 0.3rem 0.5rem 0.6rem; font-weight: 800; font-size: 0.82rem; color: #334155; }
    .gb-col-dot { width: 9px; height: 9px; border-radius: 50%; }
    .gb-col-count { margin-left: auto; background: rgba(0,0,0,0.07); color: #475569; font-size: 0.72rem; font-weight: 700; padding: 0.08rem 0.5rem; border-radius: 999px; }
    .gb-cards { display: flex; flex-direction: column; gap: 0.5rem; min-height: 30px; }
    .gb-card { background: #fff; border: 1px solid #e5e9f0; border-left: 4px solid var(--c,#94a3b8); border-radius: 10px; padding: 0.6rem 0.7rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); cursor: grab; }
    .gb-card.is-vencida { border-color: #fca5a5; background: #fff7f7; }
    .gb-proj { display: inline-block; font-size: 0.66rem; font-weight: 800; color: #7c3aed; background: #f3e8ff; padding: 0.08rem 0.5rem; border-radius: 999px; margin-bottom: 0.35rem; }
    .gb-title { font-size: 0.85rem; font-weight: 700; color: #1e293b; line-height: 1.3; word-break: break-word; }
    .gb-meta { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.45rem; }
    .gb-chip { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.67rem; font-weight: 700; padding: 0.12rem 0.5rem; border-radius: 999px; background: #f1f5f9; color: #475569; }
    .gb-chip.prio-alta { background: #fee2e2; color: #b91c1c; }
    .gb-chip.prio-media { background: #dbeafe; color: #1d4ed8; }
    .gb-chip.prio-baja { background: #e2e8f0; color: #475569; }
    .gb-chip.venc { background: #fee2e2; color: #b91c1c; }
    .gb-av { width: 15px; height: 15px; border-radius: 50%; background: #7c3aed; color: #fff; font-size: 0.55rem; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; }
    .gb-foot { display: flex; align-items: center; justify-content: space-between; margin-top: 0.5rem; gap: 0.5rem; }
    .gb-prog { flex: 1; height: 5px; background: #eef2f7; border-radius: 4px; overflow: hidden; }
    .gb-prog > span { display: block; height: 100%; background: #16a34a; }
    .gb-open { font-size: 0.68rem; font-weight: 700; color: #2563eb; text-decoration: none; white-space: nowrap; }

    /* Lista */
    .gb-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .gb-table th { text-align: left; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.3px; color: #94a3b8; font-weight: 800; padding: 0.6rem 0.75rem; border-bottom: 1px solid #eef2f7; background: #fafbfc; }
    .gb-table td { padding: 0.6rem 0.75rem; border-bottom: 1px solid #f1f5f9; font-size: 0.82rem; color: #334155; vertical-align: middle; }
    .gb-table tr:hover td { background: #f8fafc; }
    .gb-status { display: inline-block; font-size: 0.68rem; font-weight: 800; padding: 0.12rem 0.55rem; border-radius: 999px; color: #fff; }
    .gb-empty { text-align: center; color: #94a3b8; padding: 2rem 1rem; font-style: italic; }
    .sortable-ghost { opacity: 0.4; }
    @media (max-width:640px){ .gb-hide-sm { display:none; } }
</style>

<div class="gb-toggle" style="margin-bottom:1rem;">
    <button type="button" class="gb-tab active" data-view="kanban"><i class="fas fa-table-columns"></i> Kanban</button>
    <button type="button" class="gb-tab" data-view="lista"><i class="fas fa-list"></i> Lista</button>
</div>

@if($tasks->count() === 0)
    <div class="gb-empty"><i class="fas fa-inbox" style="font-size:2rem; display:block; margin-bottom:0.5rem; color:#cbd5e1;"></i> No hay tareas para mostrar.</div>
@else
{{-- ===== KANBAN ===== --}}
<div class="gb-view active" id="gb-kanban">
    <div class="gb-board">
        @foreach($columnas as $key => $meta)
            @php $col = $tareasPorColumna[$key] ?? collect(); @endphp
            <div class="gb-col">
                <div class="gb-col-head">
                    <span class="gb-col-dot" style="background:{{ $meta['color'] }}"></span>
                    {{ $meta['label'] }}
                    <span class="gb-col-count">{{ $col->count() }}</span>
                </div>
                <div class="gb-cards" data-columna="{{ $key }}">
                    @foreach($col as $t)
                        @include('partials.board.global-card', ['t' => $t])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ===== LISTA ===== --}}
<div class="gb-view" id="gb-lista">
    <table class="gb-table">
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Tarea</th>
                <th class="gb-hide-sm">Responsable</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th class="gb-hide-sm">Fecha</th>
                <th class="gb-hide-sm">Avance</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $t)
                @php
                    $pm = $prioridades[$t->prioridad] ?? ['label' => $t->prioridad, 'color' => '#94a3b8'];
                    $cm = $columnas[$t->columna] ?? ['label' => $t->columna, 'color' => '#94a3b8'];
                    $st = $t->subtasks; $sd = $st->where('hecha', true)->count(); $stt = $st->count();
                    $boardUrl = $esAdmin
                        ? route('admin.internal-projects.board', $t->internal_project_id)
                        : route('portal.developer.board', $t->internal_project_id);
                @endphp
                <tr>
                    <td><span class="gb-proj">{{ \Illuminate\Support\Str::limit(optional($t->project)->nombre, 24) }}</span></td>
                    <td style="font-weight:700; color:#1e293b;">{{ $t->titulo }}</td>
                    <td class="gb-hide-sm">{{ optional($t->developer)->nombre ?? '—' }}</td>
                    <td><span class="gb-chip prio-{{ $t->prioridad }}">{{ $pm['label'] }}</span></td>
                    <td><span class="gb-status" style="background:{{ $cm['color'] }}">{{ $cm['label'] }}</span></td>
                    <td class="gb-hide-sm" style="{{ $t->vencida ? 'color:#b91c1c; font-weight:700;' : '' }}">{{ $t->fecha_limite ? $t->fecha_limite->format('d/m/Y') : '—' }}</td>
                    <td class="gb-hide-sm">{{ $stt ? ($sd.'/'.$stt) : '—' }}</td>
                    <td><a href="{{ $boardUrl }}" class="gb-open">Abrir <i class="fas fa-arrow-right" style="font-size:0.6rem;"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
(function () {
    // Toggle kanban / lista
    document.querySelectorAll('.gb-tab').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('.gb-tab').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.gb-view').forEach(x => x.classList.remove('active'));
            b.classList.add('active');
            document.getElementById('gb-' + b.dataset.view).classList.add('active');
        });
    });

    // Drag & drop (cambia solo el estado/columna; sin reordenar entre proyectos)
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll('.gb-cards').forEach(function (col) {
        new Sortable(col, {
            group: 'gboard', animation: 150, ghostClass: 'sortable-ghost',
            onEnd: function (evt) {
                const card = evt.item;
                const columna = evt.to.getAttribute('data-columna');
                document.querySelectorAll('.gb-col').forEach(function (c) {
                    const n = c.querySelector('.gb-cards').querySelectorAll('.gb-card').length;
                    c.querySelector('.gb-col-count').textContent = n;
                });
                fetch(card.getAttribute('data-move-url'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ columna: columna })
                }).catch(() => alert('No se pudo mover la tarea. Recarga la página.'));
            }
        });
    });
})();
</script>
