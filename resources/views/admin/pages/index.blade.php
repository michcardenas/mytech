{{-- resources/views/admin/pages/index.blade.php --}}
@extends('layouts.app_admin')

@section('content')
<style>
    body, .container { background: #101820 !important; color: #FCFAF1; }
    .main-content { background: #1a252f; padding: 20px; border-radius: 8px; border: 1px solid #00A9E0; }
    .table { background-color: #101820; border-color: #00A9E0; }
    .table th, .table td { color: #FCFAF1 !important; border-color: rgba(0, 169, 224, 0.3) !important; }
    .table-light th { background-color: #1a252f !important; color: #00A9E0 !important; }
    .btn-warning { background-color: #f7a831; border-color: #f7a831; color: #101820; }
    .btn-info { background-color: #00A9E0; border-color: #00A9E0; color: #101820; }
    h2 { color: #00A9E0 !important; }
    .alert-success { background-color: rgba(0, 169, 224, 0.2); color: #FCFAF1; border: 1px solid #00A9E0; }
</style>

<div class="main-content">
    <div class="container py-4">
        <h2 class="mb-4">📄 Gestionar Páginas</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Página</th>
                    <th>Sección</th>
                    <th>Imágenes</th>
                    <th>Videos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>
                            <strong>{{ ucfirst(str_replace('-', ' ', $page->slug)) }}</strong>
                            <br>
                            <small style="color: rgba(252, 250, 241, 0.6);">{{ $page->slug }}</small>
                        </td>
                        <td>{{ $page->section ?: '—' }}</td>
                        <td class="text-center">
                            {{ count($page->getImagesArray()) }}
                        </td>
                        <td class="text-center">
                            {{ count($page->getVideosArray()) }}
                        </td>
                        <td>
                            @switch($page->slug)
                                @case('inicio')
                                    <a href="{{ route('admin.pages.edit-inicio') }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Editar Inicio
                                    </a>
                                    @break
                                
                                @case('quienes-somos')
                                    <a href="{{ route('admin.pages.edit-quienes-somos') }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Editar Quiénes Somos
                                    </a>
                                    @break
                                
                                @case('servicios')
                                    <a href="{{ route('admin.pages.edit-servicios') }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Editar Servicios
                                    </a>
                                    @break
                                
                                @case('contacto')
                                    <a href="{{ route('admin.pages.edit-contacto') }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Editar Contacto
                                    </a>
                                    @break
                                
                                @default
                                    <span class="text-muted">No disponible</span>
                            @endswitch
                            
                            <a href="{{ route('admin.seo.edit', $page->id) }}" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-search"></i> SEO
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay páginas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4 p-3" style="background: rgba(0, 169, 224, 0.1); border-radius: 8px; border: 1px solid rgba(0, 169, 224, 0.3);">
            <h5 style="color: #00A9E0; margin-bottom: 10px;">💡 Información</h5>
            <ul class="mb-0" style="font-size: 0.9rem;">
                <li><strong>Página:</strong> Identificador único (slug)</li>
                <li><strong>Sección:</strong> Campo opcional para identificar secciones específicas</li>
                <li><strong>Cada página tiene su propia vista de edición personalizada</strong></li>
                <li><strong>SEO:</strong> Configura meta tags, Open Graph y Schema.org para cada página</li>
            </ul>
        </div>
    </div>
</div>
@endsection