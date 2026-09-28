@extends('layouts.app_admin')

@section('page_title', 'Tablero general')

@section('content')
<style>
    .gg-container { max-width: 1500px; margin: 0 auto; padding: 1.5rem; }
    .gg-head { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.2rem; }
    .gg-head h1 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }
    .gg-head .sub { font-size: 0.82rem; color: #94a3b8; }
    .gg-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.82rem; text-decoration: none; border: none; cursor: pointer; background: #f1f5f9; color: #475569; }
    .gg-filter { display: flex; align-items: flex-end; gap: 0.6rem; margin-left: auto; flex-wrap: wrap; background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; padding: 0.7rem 0.85rem; }
    .gg-f { display: flex; flex-direction: column; gap: 0.25rem; }
    .gg-f label { font-size: 0.66rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.3px; }
    .gg-filter select, .gg-filter input[type=date] { font-size: 0.83rem; padding: 0.42rem 0.6rem; border: 1.5px solid #e2e8f0; border-radius: 9px; background: #fff; }
    .gg-btn-primary { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
</style>

<div class="gg-container">
    <div class="gg-head">
        <a href="{{ route('admin.internal-projects.index') }}" class="gg-btn"><i class="fas fa-arrow-left"></i> Proyectos</a>
        <div>
            <h1><i class="fas fa-layer-group" style="color:#2563eb;"></i> Tablero general</h1>
            <div class="sub">Todas las tareas de todos los proyectos · {{ $tasks->count() }} tarea(s)</div>
        </div>
        <form method="GET" action="{{ route('admin.board.global') }}" class="gg-filter">
            <span class="gg-f"><label><i class="fas fa-user"></i> Dev</label>
                <select name="dev">
                    <option value="">Todos</option>
                    @foreach($devs as $d)
                        <option value="{{ $d->id }}" {{ $devId == $d->id ? 'selected' : '' }}>{{ $d->nombre }}</option>
                    @endforeach
                </select>
            </span>
            <span class="gg-f"><label><i class="fas fa-folder"></i> Proyecto</label>
                <select name="proyecto">
                    <option value="">Todos</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </span>
            <span class="gg-f"><label><i class="fas fa-calendar"></i> Desde</label>
                <input type="date" name="desde" value="{{ $desde }}">
            </span>
            <span class="gg-f"><label><i class="fas fa-calendar-check"></i> Hasta</label>
                <input type="date" name="hasta" value="{{ $hasta }}">
            </span>
            <span class="gg-f"><label><i class="fas fa-table-columns"></i> Estado</label>
                <select name="estado">
                    <option value="">Todos</option>
                    @foreach($columnas as $key => $meta)
                        <option value="{{ $key }}" {{ $estado === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </span>
            <span class="gg-f"><label><i class="fas fa-flag"></i> Prioridad</label>
                <select name="prioridad">
                    <option value="">Todas</option>
                    @foreach($prioridades as $key => $meta)
                        <option value="{{ $key }}" {{ $prioridad === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </span>
            <button type="submit" class="gg-btn gg-btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
            @if($devId || $projectId || $desde || $hasta || $estado || $prioridad)
                <a href="{{ route('admin.board.global') }}" class="gg-btn"><i class="fas fa-times"></i> Limpiar</a>
            @endif
        </form>
    </div>

    @include('partials.board.global')
</div>
@endsection
