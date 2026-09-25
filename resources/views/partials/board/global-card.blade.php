@php
    $moveUrl = $esAdmin
        ? route('admin.project-tasks.move', $t)
        : route('portal.developer.tasks.move', $t);
    $boardUrl = $esAdmin
        ? route('admin.internal-projects.board', $t->internal_project_id)
        : route('portal.developer.board', $t->internal_project_id);
    $pm = $prioridades[$t->prioridad] ?? ['label' => $t->prioridad, 'color' => '#94a3b8'];
    $dev = $t->developer;
    $ini = $dev ? \Illuminate\Support\Str::of($dev->nombre)->explode(' ')->filter()->take(2)->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('') : '';
    $st = $t->subtasks; $sd = $st->where('hecha', true)->count(); $stt = $st->count();
    $pct = $stt ? round($sd / $stt * 100) : 0;
@endphp
<div class="gb-card {{ $t->vencida ? 'is-vencida' : '' }}" data-id="{{ $t->id }}" data-move-url="{{ $moveUrl }}" style="--c:{{ $pm['color'] }}">
    <span class="gb-proj">{{ \Illuminate\Support\Str::limit(optional($t->project)->nombre, 26) }}</span>
    <div class="gb-title">{{ $t->titulo }}</div>
    <div class="gb-meta">
        <span class="gb-chip prio-{{ $t->prioridad }}"><i class="fas fa-flag" style="font-size:0.58rem;"></i> {{ $pm['label'] }}</span>
        @if($dev)
            <span class="gb-chip"><span class="gb-av">{{ strtoupper($ini) }}</span> {{ \Illuminate\Support\Str::limit($dev->nombre, 14) }}</span>
        @endif
        @if($t->fecha_limite)
            <span class="gb-chip {{ $t->vencida ? 'venc' : '' }}"><i class="far fa-calendar" style="font-size:0.58rem;"></i> {{ $t->fecha_limite->format('d/m') }}</span>
        @endif
    </div>
    <div class="gb-foot">
        @if($stt > 0)
            <div class="gb-prog"><span style="width:{{ $pct }}%"></span></div>
            <span style="font-size:0.66rem; font-weight:800; color:#16a34a;">{{ $pct }}%</span>
        @else
            <span></span>
        @endif
        <a href="{{ $boardUrl }}" class="gb-open">Abrir</a>
    </div>
</div>
