@extends('layouts.app_admin')

@section('page_title', 'Tablero · ' . $project->nombre)

@section('content')
<style>
    .bd-container { max-width: 1400px; margin: 0 auto; padding: 1.5rem; }
    .bd-head { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .bd-head h1 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }
    .bd-head .sub { font-size: 0.82rem; color: #94a3b8; }
    .bd-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 0.9rem; border-radius: 10px; font-weight: 700; font-size: 0.82rem; text-decoration: none; border: none; cursor: pointer; }
    .bd-btn-back { background: #f1f5f9; color: #475569; }
    .bd-btn-back:hover { background: #e2e8f0; color: #0f172a; text-decoration: none; }
    .bd-btn-primary { background: linear-gradient(135deg,#2563eb,#1d4ed8); color: #fff; box-shadow: 0 3px 10px rgba(37,99,235,0.25); }
    .bd-btn-primary:hover { color: #fff; }
    .bd-team { background: #fff; border: 1px solid #e5e9f0; border-radius: 12px; padding: 0.85rem 1rem; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; }
    .bd-team-label { font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.4px; }
    .bd-devchip { display: inline-flex; align-items: center; gap: 0.4rem; background: #f1f5f9; border-radius: 999px; padding: 0.25rem 0.35rem 0.25rem 0.7rem; font-size: 0.78rem; font-weight: 700; color: #334155; }
    .bd-devchip .rm { border: none; background: transparent; color: #cbd5e1; cursor: pointer; font-size: 0.72rem; padding: 0 0.2rem; }
    .bd-devchip .rm:hover { color: #dc2626; }
    .bd-team form { display: inline-flex; gap: 0.35rem; margin: 0; }
    .bd-team select { font-size: 0.8rem; padding: 0.35rem 0.5rem; border: 1px solid #e2e8f0; border-radius: 8px; }
    .bd-alert { border-radius: 10px; padding: 0.7rem 1rem; margin-bottom: 1rem; font-size: 0.85rem; font-weight: 600; }
    .bd-alert.ok { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .bd-alert.err { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
    .bd-modal-field { margin-bottom: 0.9rem; }
    .bd-modal-field label { display: block; font-size: 0.72rem; text-transform: uppercase; font-weight: 800; color: #94a3b8; letter-spacing: 0.3px; margin-bottom: 0.3rem; }
    .bd-modal-field input, .bd-modal-field select, .bd-modal-field textarea { width: 100%; padding: 0.55rem 0.7rem; border: 1.5px solid #e2e8f0; border-radius: 9px; font-size: 0.88rem; }
    .bd-modal-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
</style>

<div class="bd-container">
    @if(session('success'))
        <div class="bd-alert ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bd-alert err"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
    @endif

    <div class="bd-head">
        <a href="{{ route('admin.internal-projects.show', $project) }}" class="bd-btn bd-btn-back"><i class="fas fa-arrow-left"></i> Volver al proyecto</a>
        <a href="{{ route('admin.board.global') }}" class="bd-btn bd-btn-back"><i class="fas fa-layer-group"></i> Tablero general</a>
        <div style="flex:1; min-width:0;">
            <h1><i class="fas fa-table-columns" style="color:#2563eb;"></i> {{ $project->nombre }}</h1>
            <div class="sub">Tablero de tareas · cliente {{ $project->cliente_nombre }}</div>
        </div>
    </div>

    {{-- Equipo del proyecto --}}
    <div class="bd-team">
        <span class="bd-team-label"><i class="fas fa-users"></i> Equipo</span>
        @forelse($equipo as $dev)
            <span class="bd-devchip">
                {{ $dev->nombre }}
                <form action="{{ route('admin.internal-projects.equipo.remove', [$project, $dev]) }}" method="POST" onsubmit="return confirm('¿Quitar a {{ $dev->nombre }} del equipo? Sus tareas quedarán sin responsable.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="rm" title="Quitar"><i class="fas fa-times"></i></button>
                </form>
            </span>
        @empty
            <span style="font-size:0.8rem; color:#94a3b8;">Sin desarrolladores aún.</span>
        @endforelse

        @if($devsDisponibles->count() > 0)
            <form action="{{ route('admin.internal-projects.equipo.add', $project) }}" method="POST">
                @csrf
                <select name="developer_id" required>
                    <option value="">+ Agregar dev...</option>
                    @foreach($devsDisponibles as $dev)
                        <option value="{{ $dev->id }}">{{ $dev->nombre }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bd-btn bd-btn-primary"><i class="fas fa-plus"></i></button>
            </form>
        @endif
    </div>

    @include('partials.board.links')

    @include('partials.board.docs')

    @include('partials.board.kanban')
</div>

@include('partials.board.task-modal')
@endsection
