@extends('layouts.app_admin')

@section('content')
<style>
    /* Fondo principal para evitar el blanco */
    body, .container, .container-fluid {
        background: #101820 !important;
        color: #FCFAF1;
    }

    /* Contenedor principal */
    .main-content {
        background: #1a252f;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #00A9E0;
        min-height: 80vh;
    }

    /* Texto blanco en general */
    body, .container, .table, .form-control, .form-select, .alert, .btn {
        color: #FCFAF1 !important;
    }

    /* Inputs y selects oscuros */
    .form-control, .form-select {
        background-color: #101820 !important;
        border: 1px solid #00A9E0;
        color: #FCFAF1 !important;
    }

    .form-control:focus, .form-select:focus {
        background-color: #101820 !important;
        border-color: #00CFB4 !important;
        box-shadow: 0 0 0 2px rgba(0, 207, 180, 0.2) !important;
        color: #FCFAF1 !important;
    }

    /* Placeholder blanco */
    ::placeholder {
        color: rgba(252, 250, 241, 0.6) !important;
        opacity: 1;
    }
    :-ms-input-placeholder { /* IE 10-11 */
        color: rgba(252, 250, 241, 0.6) !important;
    }
    ::-ms-input-placeholder { /* Edge */
        color: rgba(252, 250, 241, 0.6) !important;
    }

    /* Tabla fondo oscuro */
    .table {
        background-color: #101820;
        border-color: #00A9E0;
    }

    .table th, .table td {
        color: #FCFAF1 !important;
        border-color: rgba(0, 169, 224, 0.3) !important;
    }

    .table-light th {
        background-color: #1a252f !important;
        color: #00A9E0 !important;
        border-color: #00A9E0 !important;
    }

    /* Alertas */
    .alert-success {
        background-color: rgba(0, 169, 224, 0.2);
        color: #FCFAF1;
        border: 1px solid #00A9E0;
    }

    /* Botones personalizados */
    .btn-primary {
        background-color: #00A9E0;
        border-color: #00A9E0;
        color: #FCFAF1;
    }

    .btn-primary:hover {
        background-color: #00CFB4;
        border-color: #00CFB4;
        color: #FCFAF1;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #FCFAF1;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
        color: #FCFAF1;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #FCFAF1;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
        color: #FCFAF1;
    }

    /* Título */
    h2 {
        color: #00A9E0 !important;
    }

    /* Formulario mejorado */
    .row.g-2 > * {
        margin-bottom: 10px;
    }
</style>

<div class="main-content">
    <div class="container py-4">
        <h2 class="mb-4">🏙️ Gestionar Ciudades</h2>

        <!-- Create City -->
        <form action="{{ route('admin.cities.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Nombre de la ciudad..." required>
                </div>
                <div class="col-md-4">
                    <select name="country_id" class="form-select" required>
                        <option value="">Seleccionar país</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Agregar Ciudad</button>
                </div>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Cities Table -->
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->country->name }}</td>
                        <td>
                            {{-- Future edit button --}}
                            <button class="btn btn-sm btn-secondary" disabled>Editar</button>

                            <!-- Delete -->
                            <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar esta ciudad?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No se encontraron ciudades.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection