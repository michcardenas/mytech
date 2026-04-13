@extends('layouts.app_admin')

@section('content')
<style>
    :root {
        --primary-blue: #007BFF;
        --primary-dark: #0056b3;
        --dark-text: #2c3e50;
        --light-gray: #f8f9fa;
        --white: #ffffff;
        --gradient-blue: linear-gradient(135deg, #007BFF 0%, #0056b3 100%);
        --shadow-soft: 0 4px 15px rgba(0, 0, 0, 0.06);
        --shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .ip-container {
        background: var(--light-gray);
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    .ip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.75rem 2rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .ip-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .ip-header p { margin: 0; opacity: 0.85; font-size: 0.88rem; }

    .btn-new {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        padding: 0.65rem 1.3rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.88rem;
    }

    .btn-new:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; transform: translateY(-2px); }

    /* Stats */
    .ip-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .ip-stat {
        background: var(--white);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: var(--shadow-soft);
        text-align: center;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .ip-stat-num { font-size: 1.5rem; font-weight: 800; color: var(--dark-text); }
    .ip-stat-label { font-size: 0.72rem; color: #888; text-transform: uppercase; font-weight: 600; letter-spacing: 0.3px; margin: 0; }

    /* Filters */
    .ip-filters {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .ip-filters select, .ip-filters input {
        padding: 0.5rem 0.85rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        font-size: 0.82rem;
        background: var(--white);
        color: var(--dark-text);
        transition: var(--transition);
    }

    .ip-filters select:focus, .ip-filters input:focus { border-color: var(--primary-blue); outline: none; }
    .ip-filters input { flex: 1; min-width: 200px; }

    .btn-filter {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        border: none;
        background: var(--gradient-blue);
        color: white;
        font-weight: 600;
        font-size: 0.82rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-filter:hover { transform: translateY(-1px); }

    .btn-clear {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        border: 1px solid #ddd;
        background: var(--white);
        color: #666;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-clear:hover { background: #f1f1f1; color: #333; text-decoration: none; }

    /* Alert */
    .alert-success {
        background: var(--white);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.2);
        border-left: 4px solid #28a745;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
        box-shadow: var(--shadow-soft);
    }

    /* Project Cards */
    .ip-list { display: grid; gap: 0.85rem; }

    .ip-card {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-soft);
        transition: var(--transition);
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        align-items: center;
        text-decoration: none;
        color: var(--dark-text);
    }

    .ip-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(0,123,255,0.12);
        text-decoration: none;
        color: var(--dark-text);
    }

    .ip-card-body { min-width: 0; }

    .ip-card-top {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.35rem;
        flex-wrap: wrap;
    }

    .ip-card-name { font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--dark-text); }

    .estado-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 8px;
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .fuente-badge {
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(0,0,0,0.06);
        color: #666;
    }

    .ip-card-client {
        font-size: 0.85rem;
        color: #777;
        margin: 0.2rem 0 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ip-card-client i { font-size: 0.75rem; color: #aaa; }

    .ip-card-meta {
        display: flex;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    .ip-meta-item {
        font-size: 0.78rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .ip-meta-item i { font-size: 0.72rem; color: #aaa; }
    .ip-meta-item strong { color: var(--dark-text); }

    /* Progress bar */
    .ip-progress {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.3rem;
        min-width: 120px;
    }

    .ip-progress-label {
        font-size: 0.72rem;
        color: #888;
        font-weight: 600;
    }

    .ip-progress-bar {
        width: 100%;
        height: 6px;
        background: #eee;
        border-radius: 3px;
        overflow: hidden;
    }

    .ip-progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.5s ease;
    }

    .ip-price {
        font-size: 1rem;
        font-weight: 800;
        color: var(--dark-text);
        text-align: right;
    }

    .ip-price small { font-size: 0.7rem; color: #999; font-weight: 600; }

    /* Empty */
    .ip-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: 14px;
        box-shadow: var(--shadow-soft);
    }

    .ip-empty i { font-size: 3rem; color: #ddd; margin-bottom: 0.75rem; display: block; }
    .ip-empty h3 { color: #888; font-weight: 600; margin-bottom: 0.5rem; }
    .ip-empty p { color: #aaa; }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .ip-container { padding: 1rem; }
        .ip-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .ip-card { grid-template-columns: 1fr; }
        .ip-progress { align-items: flex-start; min-width: auto; }
        .ip-price { text-align: left; }
        .ip-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="ip-container">
    <div class="ip-header">
        <div>
            <h1><i class="fas fa-briefcase"></i> Mis Proyectos</h1>
            <p>Gestion interna de proyectos, clientes y pagos</p>
        </div>
        <a href="{{ route('admin.internal-projects.create') }}" class="btn-new">
            <i class="fas fa-plus-circle"></i> Nuevo Proyecto
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="ip-stats">
        <div class="ip-stat">
            <div class="ip-stat-num">{{ $stats['total'] }}</div>
            <p class="ip-stat-label">Total</p>
        </div>
        <div class="ip-stat">
            <div class="ip-stat-num" style="color: #007BFF;">{{ $stats['en_progreso'] }}</div>
            <p class="ip-stat-label">En Progreso</p>
        </div>
        <div class="ip-stat">
            <div class="ip-stat-num" style="color: #28a745;">{{ $stats['completados'] }}</div>
            <p class="ip-stat-label">Completados</p>
        </div>
        <div class="ip-stat">
            <div class="ip-stat-num" style="color: #f7a831;">{{ $stats['cotizados'] }}</div>
            <p class="ip-stat-label">Cotizados</p>
        </div>
    </div>

    <form method="GET" class="ip-filters">
        <select name="estado">
            <option value="">Todos los estados</option>
            <option value="cotizado" {{ request('estado') == 'cotizado' ? 'selected' : '' }}>Cotizado</option>
            <option value="en_progreso" {{ request('estado') == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
            <option value="pausado" {{ request('estado') == 'pausado' ? 'selected' : '' }}>Pausado</option>
            <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
        <select name="fuente">
            <option value="">Todas las fuentes</option>
            <option value="directo" {{ request('fuente') == 'directo' ? 'selected' : '' }}>Directo</option>
            <option value="workana" {{ request('fuente') == 'workana' ? 'selected' : '' }}>Workana</option>
        </select>
        <input type="text" name="buscar" placeholder="Buscar proyecto o cliente..." value="{{ request('buscar') }}">
        <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filtrar</button>
        @if(request()->hasAny(['estado', 'fuente', 'buscar']))
            <a href="{{ route('admin.internal-projects.index') }}" class="btn-clear">Limpiar</a>
        @endif
    </form>

    <div class="ip-list">
        @forelse($projects as $project)
            @php
                $totalPagado = $project->payments_sum_monto ?? 0;
                $porcentaje = $project->precio > 0 ? round(($totalPagado / $project->precio) * 100) : 0;
                $porcentaje = min($porcentaje, 100);
                $progressColor = $porcentaje >= 100 ? '#28a745' : ($porcentaje >= 50 ? '#007BFF' : '#f7a831');
            @endphp
            <a href="{{ route('admin.internal-projects.show', $project) }}" class="ip-card">
                <div class="ip-card-body">
                    <div class="ip-card-top">
                        <h3 class="ip-card-name">{{ $project->nombre }}</h3>
                        <span class="estado-badge" style="background: {{ $project->estado_color }}15; color: {{ $project->estado_color }};">
                            <i class="fas fa-circle" style="font-size: 0.4rem;"></i>
                            {{ $project->estado_label }}
                        </span>
                        <span class="fuente-badge">{{ $project->fuente == 'workana' ? 'Workana' : 'Directo' }}</span>
                    </div>
                    <p class="ip-card-client">
                        <i class="fas fa-user"></i> {{ $project->cliente_nombre }}
                        @if($project->cliente_contacto)
                            <span style="color:#ccc;">|</span>
                            <i class="fas fa-phone"></i> {{ $project->cliente_contacto }}
                        @endif
                    </p>
                    <div class="ip-card-meta">
                        @if($project->desarrollador_nombre)
                            <span class="ip-meta-item"><i class="fas fa-laptop-code"></i> <strong>{{ $project->desarrollador_nombre }}</strong></span>
                        @endif
                        @if($project->fecha_inicio)
                            <span class="ip-meta-item"><i class="fas fa-calendar"></i> {{ $project->fecha_inicio->format('d/m/Y') }}</span>
                        @endif
                        @if($project->fecha_entrega)
                            <span class="ip-meta-item"><i class="fas fa-flag-checkered"></i> {{ $project->fecha_entrega->format('d/m/Y') }}</span>
                        @endif
                        <span class="ip-meta-item"><i class="fas fa-file-alt"></i> {{ $project->files_count }} archivos</span>
                        <span class="ip-meta-item"><i class="fas fa-money-bill-wave"></i> {{ $project->payments_count }} pagos</span>
                    </div>
                </div>
                <div class="ip-progress">
                    <div class="ip-price">
                        {{ $project->moneda == 'COP' ? '$' : 'US$' }}{{ number_format($project->precio, 0, ',', '.') }}
                        <small>{{ $project->moneda }}</small>
                    </div>
                    <div class="ip-progress-label">{{ $porcentaje }}% pagado</div>
                    <div class="ip-progress-bar">
                        <div class="ip-progress-fill" style="width: {{ $porcentaje }}%; background: {{ $progressColor }};"></div>
                    </div>
                </div>
            </a>
        @empty
            <div class="ip-empty">
                <i class="fas fa-briefcase"></i>
                <h3>No hay proyectos registrados</h3>
                <p>Crea tu primer proyecto para empezar a gestionar tus clientes y pagos.</p>
                <a href="{{ route('admin.internal-projects.create') }}" class="btn-new" style="background: var(--gradient-blue); border: none; margin-top: 1rem;">
                    <i class="fas fa-plus-circle"></i> Crear Proyecto
                </a>
            </div>
        @endforelse
    </div>

    @if($projects->hasPages())
        <div class="pagination-wrapper">
            {{ $projects->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
