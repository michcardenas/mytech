@extends('layouts.app_admin')

@section('page_title', 'Tablero general')

@section('content')
<style>
    .gg-container { max-width: 1500px; margin: 0 auto; padding: 1.5rem; }
    .gg-head { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.2rem; }
    .gg-head h1 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }
    .gg-head .sub { font-size: 0.82rem; color: #94a3b8; }
    .gg-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.82rem; text-decoration: none; border: none; cursor: pointer; background: #f1f5f9; color: #475569; }
    .gg-filter { display: flex; align-items: center; gap: 0.5rem; margin-left: auto; }
    .gg-filter select { font-size: 0.85rem; padding: 0.45rem 0.7rem; border: 1.5px solid #e2e8f0; border-radius: 9px; }
    .gg-filter button { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; }
</style>

<div class="gg-container">
    <div class="gg-head">
        <a href="{{ route('admin.internal-projects.index') }}" class="gg-btn"><i class="fas fa-arrow-left"></i> Proyectos</a>
        <div>
            <h1><i class="fas fa-layer-group" style="color:#2563eb;"></i> Tablero general</h1>
            <div class="sub">Todas las tareas de todos los proyectos · {{ $tasks->count() }} tarea(s)</div>
        </div>
        <form method="GET" action="{{ route('admin.board.global') }}" class="gg-filter">
            <label style="font-size:0.75rem; font-weight:700; color:#64748b;"><i class="fas fa-filter"></i> Dev:</label>
            <select name="dev" onchange="this.form.submit()">
                <option value="">Todos</option>
                @foreach($devs as $d)
                    <option value="{{ $d->id }}" {{ $devId == $d->id ? 'selected' : '' }}>{{ $d->nombre }}</option>
                @endforeach
            </select>
            <noscript><button type="submit" class="gg-btn">Filtrar</button></noscript>
        </form>
    </div>

    @include('partials.board.global')
</div>
@endsection
