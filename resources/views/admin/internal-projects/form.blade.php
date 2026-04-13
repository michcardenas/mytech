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

    .form-container {
        background: var(--light-gray);
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
        min-height: 80vh;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.5rem 2rem;
        background: var(--gradient-blue);
        border-radius: 16px;
        color: white;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .form-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
    }

    .btn-back:hover { background: rgba(255,255,255,0.35); color: white; text-decoration: none; }

    /* Form Section */
    .form-section {
        background: var(--white);
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 14px;
        margin-bottom: 1.25rem;
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }

    .form-section-header {
        padding: 1rem 1.5rem;
        background: rgba(0,0,0,0.015);
        border-bottom: 1px solid rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .form-section-header h3 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dark-text);
    }

    .form-section-header i { color: var(--primary-blue); font-size: 0.9rem; }

    .form-section-body { padding: 1.5rem; }

    .field-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .field-row.single { grid-template-columns: 1fr; }
    .field-row.triple { grid-template-columns: repeat(3, 1fr); }

    .field-group { margin-bottom: 0; }

    .field-label {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--dark-text);
        margin-bottom: 0.35rem;
    }

    .field-label i { color: #aaa; font-size: 0.72rem; }
    .field-label .required { color: #dc3545; }

    .form-control, .form-select {
        background: var(--white);
        border: 2px solid #e9ecef;
        color: var(--dark-text);
        border-radius: 10px;
        padding: 0.6rem 0.9rem;
        font-size: 0.88rem;
        transition: var(--transition);
        width: 100%;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        outline: none;
    }

    textarea.form-control { resize: vertical; min-height: 80px; }

    .field-hint { font-size: 0.72rem; color: #999; margin-top: 0.2rem; }

    .is-invalid { border-color: #dc3545 !important; }
    .invalid-feedback { color: #dc3545; font-size: 0.78rem; margin-top: 0.2rem; display: block; }

    /* Price Group */
    .price-group {
        display: flex;
        gap: 0;
        align-items: stretch;
    }

    .price-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        flex: 1;
    }

    .price-group .form-select {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: none;
        width: 100px;
        flex-shrink: 0;
    }

    /* Footer */
    .form-footer {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .btn-cancel {
        background: var(--white);
        border: 2px solid #ddd;
        color: #666;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.88rem;
    }

    .btn-cancel:hover { background: #f1f1f1; color: #333; text-decoration: none; }

    .btn-save {
        background: var(--gradient-blue);
        border: none;
        color: white;
        padding: 0.7rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,123,255,0.4); }

    @media (max-width: 768px) {
        .form-container { padding: 1rem; }
        .form-header { flex-direction: column; text-align: center; padding: 1.25rem; }
        .field-row, .field-row.triple { grid-template-columns: 1fr; }
        .form-footer { flex-direction: column; }
        .btn-cancel, .btn-save { width: 100%; justify-content: center; }
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1>
            <i class="fas fa-{{ $isEdit ? 'edit' : 'plus-circle' }}"></i>
            {{ $isEdit ? 'Editar Proyecto' : 'Nuevo Proyecto' }}
        </h1>
        <a href="{{ route('admin.internal-projects.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <form action="{{ $isEdit ? route('admin.internal-projects.update', $project) : route('admin.internal-projects.store') }}"
          method="POST">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- Proyecto --}}
        <div class="form-section">
            <div class="form-section-header">
                <i class="fas fa-briefcase"></i>
                <h3>Informacion del Proyecto</h3>
            </div>
            <div class="form-section-body">
                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-tag"></i> Nombre del Proyecto <span class="required">*</span></div>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $project->nombre) }}" required placeholder="Ej: Rediseno web para Restaurante XYZ">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-align-left"></i> Descripcion</div>
                        <textarea name="descripcion" class="form-control" rows="3"
                                  placeholder="Descripcion general del proyecto...">{{ old('descripcion', $project->descripcion) }}</textarea>
                    </div>
                </div>

                <div class="field-row triple">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-flag"></i> Estado <span class="required">*</span></div>
                        <select name="estado" class="form-select" required>
                            <option value="cotizado" {{ old('estado', $project->estado) == 'cotizado' ? 'selected' : '' }}>Cotizado</option>
                            <option value="en_progreso" {{ old('estado', $project->estado) == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                            <option value="pausado" {{ old('estado', $project->estado) == 'pausado' ? 'selected' : '' }}>Pausado</option>
                            <option value="completado" {{ old('estado', $project->estado) == 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelado" {{ old('estado', $project->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-calendar"></i> Fecha Inicio</div>
                        <input type="date" name="fecha_inicio" class="form-control"
                               value="{{ old('fecha_inicio', $project->fecha_inicio?->format('Y-m-d')) }}">
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-flag-checkered"></i> Fecha Entrega</div>
                        <input type="date" name="fecha_entrega" class="form-control"
                               value="{{ old('fecha_entrega', $project->fecha_entrega?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Cliente --}}
        <div class="form-section">
            <div class="form-section-header">
                <i class="fas fa-user"></i>
                <h3>Datos del Cliente</h3>
            </div>
            <div class="form-section-body">
                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-user"></i> Nombre del Cliente <span class="required">*</span></div>
                        <input type="text" name="cliente_nombre" class="form-control @error('cliente_nombre') is-invalid @enderror"
                               value="{{ old('cliente_nombre', $project->cliente_nombre) }}" required placeholder="Nombre completo o empresa">
                        @error('cliente_nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-envelope"></i> Email</div>
                        <input type="email" name="cliente_email" class="form-control"
                               value="{{ old('cliente_email', $project->cliente_email) }}" placeholder="email@ejemplo.com">
                    </div>
                </div>
                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-phone"></i> Contacto</div>
                        <input type="text" name="cliente_contacto" class="form-control"
                               value="{{ old('cliente_contacto', $project->cliente_contacto) }}" placeholder="Telefono o WhatsApp">
                    </div>
                </div>
            </div>
        </div>

        {{-- Precio y Fuente --}}
        <div class="form-section">
            <div class="form-section-header">
                <i class="fas fa-money-bill-wave"></i>
                <h3>Precio y Fuente</h3>
            </div>
            <div class="form-section-body">
                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-dollar-sign"></i> Precio <span class="required">*</span></div>
                        <div class="price-group">
                            <input type="number" name="precio" class="form-control @error('precio') is-invalid @enderror"
                                   value="{{ old('precio', $project->precio) }}" required step="0.01" min="0" placeholder="0.00">
                            <select name="moneda" class="form-select">
                                <option value="COP" {{ old('moneda', $project->moneda) == 'COP' ? 'selected' : '' }}>COP</option>
                                <option value="USD" {{ old('moneda', $project->moneda) == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-handshake"></i> Fuente <span class="required">*</span></div>
                        <select name="fuente" class="form-select" required>
                            <option value="directo" {{ old('fuente', $project->fuente) == 'directo' ? 'selected' : '' }}>Contrato Directo</option>
                            <option value="workana" {{ old('fuente', $project->fuente) == 'workana' ? 'selected' : '' }}>Workana</option>
                        </select>
                    </div>
                </div>
                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-link"></i> URL del proyecto (Workana u otro)</div>
                        <input type="url" name="fuente_url" class="form-control"
                               value="{{ old('fuente_url', $project->fuente_url) }}" placeholder="https://www.workana.com/...">
                        <div class="field-hint">Link al proyecto en la plataforma externa</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desarrollador Asignado --}}
        <div class="form-section">
            <div class="form-section-header">
                <i class="fas fa-laptop-code"></i>
                <h3>Desarrollador Asignado</h3>
            </div>
            <div class="form-section-body">
                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-user-cog"></i> Nombre del Desarrollador</div>
                        <input type="text" name="desarrollador_nombre" class="form-control"
                               value="{{ old('desarrollador_nombre', $project->desarrollador_nombre) }}" placeholder="Nombre completo">
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-envelope"></i> Email del Desarrollador</div>
                        <input type="email" name="desarrollador_email" class="form-control"
                               value="{{ old('desarrollador_email', $project->desarrollador_email) }}" placeholder="dev@ejemplo.com">
                    </div>
                </div>
            </div>
        </div>

        {{-- Notas --}}
        <div class="form-section">
            <div class="form-section-header">
                <i class="fas fa-sticky-note"></i>
                <h3>Notas</h3>
            </div>
            <div class="form-section-body">
                <div class="field-row single">
                    <div class="field-group">
                        <textarea name="notas" class="form-control" rows="4"
                                  placeholder="Notas internas sobre el proyecto, acuerdos, observaciones...">{{ old('notas', $project->notas) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('admin.internal-projects.index') }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> {{ $isEdit ? 'Guardar Cambios' : 'Crear Proyecto' }}
            </button>
        </div>
    </form>
</div>
@endsection
