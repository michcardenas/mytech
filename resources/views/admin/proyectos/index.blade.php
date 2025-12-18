@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .proyecto-card { background: #2a3441; border: 1px solid #00A9E0; border-radius: 8px; margin-bottom: 20px; padding: 20px; transition: all 0.3s ease; }
    .proyecto-card:hover { border-color: #f7a831; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 169, 224, 0.2); }
    .btn-primary { background-color: #00A9E0; border-color: #00A9E0; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; }
    .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
    .btn-warning { background-color: #f7a831; border-color: #f7a831; color: #101820; }
    .btn-success { background-color: #28a745; border-color: #28a745; }
    h2 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
    .table { color: #FCFAF1; }
    .table thead { background: #1a252f; border-bottom: 2px solid #00A9E0; }
    .table tbody tr { background: #2a3441; border-bottom: 1px solid rgba(0, 169, 224, 0.2); }
    .table tbody tr:hover { background: #3a4451; }
    .badge-success { background-color: #28a745; }
    .badge-warning { background-color: #f7a831; color: #101820; }
    .badge-secondary { background-color: #6c757d; }
    .badge-primary { background-color: #00A9E0; }
    .badge-danger { background-color: #dc3545; }
    .badge-info { background-color: #17a2b8; }
    .proyecto-logo { width: 80px; height: 80px; object-fit: contain; background: white; padding: 8px; border-radius: 10px; }
    .proyecto-descripcion {
        color: #FCFAF1;
        line-height: 1.6;
        margin-top: 10px;
        max-height: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .proyecto-descripcion p { margin-bottom: 0.5rem; color: #FCFAF1; }
    .proyecto-descripcion strong { color: #00A9E0; font-weight: 600; }
    .proyecto-descripcion em { color: #f7a831; }
    .proyecto-descripcion ul, .proyecto-descripcion ol { margin-left: 20px; color: #FCFAF1; }
    .proyecto-descripcion a { color: #00A9E0; text-decoration: underline; }
    .proyecto-header { display: flex; align-items: start; gap: 20px; margin-bottom: 15px; }
    .proyecto-info { flex: 1; }
    .proyecto-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px; }
    .view-mode-toggle { margin-bottom: 20px; }
    .view-mode-toggle .btn { margin-right: 10px; }
    .card-view .proyecto-card { display: block; }
    .table-view { display: none; }
    .active-view { display: block !important; }
</style>

<div class="main-content">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">🚀 Gestión de Proyectos</h2>
                <p class="text-light mb-0">Administra tu portfolio de proyectos</p>
            </div>
            <a href="{{ route('admin.proyectos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Proyecto
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($proyectos->count() > 0)
        <!-- Vista de Tarjetas -->
        <div class="card-view active-view">
            @foreach($proyectos as $proyecto)
            <div class="proyecto-card">
                <div class="proyecto-header">
                    <div>
                        @if($proyecto->logo)
                            <img src="{{ asset('storage/' . $proyecto->logo) }}" alt="{{ $proyecto->nombre }}" class="proyecto-logo">
                        @else
                            <div class="proyecto-logo d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #00A9E0, #00d4ff); color: white; font-weight: bold; font-size: 1.5rem;">
                                {{ strtoupper(substr($proyecto->nombre, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="proyecto-info">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="mb-1" style="color: #00A9E0;">{{ $proyecto->nombre }}</h4>
                                <p class="text-muted mb-2">
                                    <small><i class="fas fa-link"></i> {{ $proyecto->slug }}</small>
                                </p>
                            </div>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-{{ $proyecto->activo ? 'success' : 'secondary' }} toggle-activo"
                                        data-id="{{ $proyecto->id }}"
                                        title="Click para {{ $proyecto->activo ? 'desactivar' : 'activar' }}">
                                    <i class="fas fa-{{ $proyecto->activo ? 'check' : 'times' }}"></i>
                                </button>
                                <a href="{{ route('admin.proyectos.edit', $proyecto) }}" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.proyectos.destroy', $proyecto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este proyecto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="proyecto-meta">
                            <span class="badge badge-primary">
                                <i class="fas fa-tag"></i> {{ ucfirst($proyecto->categoria) }}
                            </span>
                            <span class="badge badge-{{ $proyecto->estado_color }}">
                                @if($proyecto->estado === 'en_vivo') 🟢 @endif
                                @if($proyecto->estado === 'en_desarrollo') 🟡 @endif
                                @if($proyecto->estado === 'pausado') ⚫ @endif
                                {{ $proyecto->estado_text }}
                            </span>
                            @if($proyecto->destacado)
                                <span class="badge badge-warning">⭐ Destacado</span>
                            @endif
                            <span class="badge badge-info">
                                <i class="fas fa-sort"></i> Orden: {{ $proyecto->orden }}
                            </span>
                            <span class="badge badge-secondary">
                                <span style="font-size: 1.2rem;">{{ $proyecto->bandera_emoji }}</span> {{ $proyecto->pais }}
                            </span>
                        </div>

                        @if($proyecto->descripcion)
                        <div class="proyecto-descripcion">
                            {!! \Illuminate\Support\Str::limit(strip_tags($proyecto->descripcion, '<strong><em><b><i><u><a><ul><ol><li>'), 200) !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $proyectos->links() }}
        </div>
        @else
        <div class="proyecto-card text-center py-5">
            <i class="fas fa-folder-open fa-3x mb-3 text-muted"></i>
            <h4>No hay proyectos aún</h4>
            <p class="text-muted">Crea tu primer proyecto para comenzar a construir tu portfolio</p>
            <a href="{{ route('admin.proyectos.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus"></i> Crear Primer Proyecto
            </a>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-activo').forEach(button => {
        button.addEventListener('click', function() {
            const proyectoId = this.dataset.id;

            fetch(`/proyectos/${proyectoId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar el botón
                    if (data.activo) {
                        this.classList.remove('btn-secondary');
                        this.classList.add('btn-success');
                        this.innerHTML = '<i class="fas fa-check"></i>';
                        this.title = 'Click para desactivar';
                    } else {
                        this.classList.remove('btn-success');
                        this.classList.add('btn-secondary');
                        this.innerHTML = '<i class="fas fa-times"></i>';
                        this.title = 'Click para activar';
                    }

                    // Mostrar mensaje
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);

                    setTimeout(() => alert.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al actualizar el estado');
            });
        });
    });
});
</script>
@endsection
