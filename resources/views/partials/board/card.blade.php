@php
    $moveUrl = $esAdmin
        ? route('admin.project-tasks.move', $t)
        : route('portal.developer.tasks.move', $t);
    $taskUpdateUrl = $esAdmin
        ? route('admin.project-tasks.update', $t)
        : route('portal.developer.tasks.update', $t);
    $subStoreUrl = $esAdmin
        ? route('admin.project-tasks.subtasks.store', $t)
        : route('portal.developer.subtasks.store', $t);
    $fileStoreUrl = $esAdmin
        ? route('admin.project-tasks.files.store', $t)
        : route('portal.developer.tasks.files.store', $t);
    $prioMeta = $prioridades[$t->prioridad] ?? ['label' => $t->prioridad, 'color' => '#94a3b8'];
    $dev = $t->developer;
    $ini = $dev ? \Illuminate\Support\Str::of($dev->nombre)->explode(' ')->filter()->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('') : '';
    $subs = $t->subtasks;
    $subTotal = $subs->count();
    $subDone = $subs->where('hecha', true)->count();
    $subPct = $subTotal ? round($subDone / $subTotal * 100) : 0;
    $files = $t->files;
@endphp
<div class="kb-card {{ $t->vencida ? 'is-vencida' : '' }}" data-id="{{ $t->id }}" data-move-url="{{ $moveUrl }}" style="--c: {{ $prioMeta['color'] }}">
    <div class="kb-card-top">
        <div class="kb-title">{{ $t->titulo }}</div>
        <div class="kb-actions">
            @if($puedeEditar)
                <button type="button" class="kb-ibtn js-edit-task"
                    data-id="{{ $t->id }}"
                    data-titulo="{{ $t->titulo }}"
                    data-descripcion="{{ $t->descripcion }}"
                    data-prioridad="{{ $t->prioridad }}"
                    data-developer="{{ $t->developer_id }}"
                    data-fecha="{{ optional($t->fecha_limite)->format('Y-m-d') }}"
                    data-update-url="{{ $taskUpdateUrl }}"
                    title="Editar"><i class="fas fa-pen"></i></button>
            @endif
            @if($esAdmin)
                <form action="{{ route('admin.project-tasks.destroy', $t) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea y sus subtareas?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="kb-ibtn del" title="Eliminar"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    @if($t->descripcion)
        <div style="font-size:0.75rem; color:#64748b; margin-top:0.3rem; white-space:pre-line;">{{ \Illuminate\Support\Str::limit($t->descripcion, 140) }}</div>
    @endif

    <div class="kb-meta">
        <span class="kb-chip prio-{{ $t->prioridad }}"><i class="fas fa-flag" style="font-size:0.6rem;"></i> {{ $prioMeta['label'] }}</span>
        @if($dev)
            <span class="kb-chip"><span class="kb-av">{{ strtoupper($ini) }}</span> {{ \Illuminate\Support\Str::limit($dev->nombre, 16) }}</span>
        @endif
        @if($t->fecha_limite)
            <span class="kb-chip {{ $t->vencida ? 'venc' : '' }}"><i class="far fa-calendar" style="font-size:0.6rem;"></i> {{ $t->fecha_limite->format('d/m') }}</span>
        @endif
    </div>

    @if($subTotal > 0)
        <div class="kb-progress-row">
            <div class="kb-progress"><span style="width: {{ $subPct }}%"></span></div>
            <span class="kb-pct">{{ $subPct }}%</span>
        </div>
    @endif

    @if($subTotal > 0 || $puedeEditar)
        <details class="kb-subs" {{ $subTotal > 0 && $subDone < $subTotal ? 'open' : '' }}>
            <summary><i class="fas fa-list-check" style="font-size:0.68rem;"></i> Subtareas <span class="kb-subcount">{{ $subDone }}/{{ $subTotal }}</span></summary>
            @foreach($subs as $s)
                @php
                    $toggleUrl = $esAdmin
                        ? route('admin.project-subtasks.toggle', $s)
                        : route('portal.developer.subtasks.toggle', $s);
                    $subUpdateUrl = $esAdmin
                        ? route('admin.project-subtasks.update', $s)
                        : route('portal.developer.subtasks.update', $s);
                    $subDelUrl = $esAdmin
                        ? route('admin.project-subtasks.destroy', $s)
                        : route('portal.developer.subtasks.destroy', $s);
                @endphp
                <div class="kb-sub {{ $s->hecha ? 'done' : '' }}">
                    <input type="checkbox" class="js-subtoggle" data-toggle-url="{{ $toggleUrl }}" {{ $s->hecha ? 'checked' : '' }}>
                    <span class="t">{{ $s->titulo }}</span>
                    @if($puedeEditar)
                        <form action="{{ $subUpdateUrl }}" method="POST" class="kb-reassign">
                            @csrf @method('PUT')
                            <select name="developer_id" onchange="this.form.submit()" title="Responsable">
                                <option value="">— sin asignar</option>
                                @foreach($equipo as $d)
                                    <option value="{{ $d->id }}" {{ $s->developer_id == $d->id ? 'selected' : '' }}>{{ \Illuminate\Support\Str::limit($d->nombre, 14) }}</option>
                                @endforeach
                            </select>
                        </form>
                        <form action="{{ $subDelUrl }}" method="POST" onsubmit="return confirm('¿Eliminar subtarea?');" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="kb-subdel" title="Eliminar"><i class="fas fa-times"></i></button>
                        </form>
                    @elseif($s->developer)
                        <span class="kb-sub-resp"><span class="kb-av sm">{{ strtoupper(\Illuminate\Support\Str::substr($s->developer->nombre, 0, 1)) }}</span></span>
                    @endif
                </div>
            @endforeach
            @if($puedeEditar)
                <form class="kb-subadd" action="{{ $subStoreUrl }}" method="POST">
                    @csrf
                    <input type="text" name="titulo" placeholder="Nueva subtarea..." required maxlength="255">
                    <select name="developer_id" title="Responsable">
                        <option value="">Responsable</option>
                        @foreach($equipo as $d)
                            <option value="{{ $d->id }}">{{ \Illuminate\Support\Str::limit($d->nombre, 14) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" title="Agregar">+</button>
                </form>
            @endif
        </details>
    @endif

    {{-- Archivos de la tarea (admin y dev pueden subir/borrar) --}}
    <details class="kb-files">
        <summary><i class="fas fa-paperclip" style="font-size:0.68rem;"></i> Archivos <span class="kb-filecount">{{ $files->count() }}</span></summary>
        @foreach($files as $f)
            @php
                $fileDelUrl = $esAdmin
                    ? route('admin.project-task-files.destroy', $f)
                    : route('portal.developer.tasks.files.destroy', $f);
            @endphp
            <div class="kb-file">
                <a href="{{ Storage::url($f->archivo) }}" target="_blank" class="kb-file-link"><i class="fas {{ $f->icono }}"></i> {{ \Illuminate\Support\Str::limit($f->nombre, 22) }}</a>
                <span class="kb-file-size">{{ $f->tamano_formateado }}</span>
                <form action="{{ $fileDelUrl }}" method="POST" onsubmit="return confirm('¿Eliminar archivo?');" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="kb-subdel" title="Eliminar"><i class="fas fa-times"></i></button>
                </form>
            </div>
        @endforeach
        <form class="kb-fileadd" action="{{ $fileStoreUrl }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="archivo" required>
            <button type="submit" title="Subir"><i class="fas fa-upload"></i></button>
        </form>
    </details>
</div>
