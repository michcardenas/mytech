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
                    <div class="field-group" id="fecha-entrega-group">
                        <div class="field-label"><i class="fas fa-flag-checkered"></i> Fecha Entrega</div>
                        <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control"
                               value="{{ old('fecha_entrega', $project->fecha_entrega?->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.6rem 0.9rem; background: #f8f9fa; border: 2px dashed #e9ecef; border-radius: 10px; font-size: 0.85rem; font-weight: 600; color: var(--dark-text); width: 100%;">
                            <input type="checkbox" name="es_recurrente" id="es_recurrente" value="1"
                                   {{ old('es_recurrente', $project->es_recurrente) ? 'checked' : '' }}
                                   style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary-blue);">
                            <i class="fas fa-sync-alt" style="color: var(--primary-blue);"></i>
                            Proyecto recurrente (sin fecha de finalizacion)
                        </label>
                        <div class="field-hint">Marca esta opcion si el proyecto es continuo (mantenimiento, mensualidad, soporte, etc.)</div>
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
                        <div class="field-label"><i class="fas fa-address-book"></i> Cliente <span class="required">*</span></div>
                        <div style="display:flex; gap:0.5rem; align-items:stretch;">
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror" style="flex:1;">
                                <option value="">— Seleccionar cliente —</option>
                                @foreach($clients as $c)
                                    <option value="{{ $c->id }}"
                                            data-telefono="{{ $c->telefono }}"
                                            data-empresa="{{ $c->empresa }}"
                                            data-identificacion="{{ $c->identificacion }}"
                                            {{ old('client_id', $project->client_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}@if($c->empresa) · {{ $c->empresa }}@endif
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="btnNuevoCliente" class="btn btn-primary"
                                    style="padding: 0 1rem; border-radius: 10px; border:none; background: var(--gradient-blue); color:white; font-weight:600; white-space:nowrap; display:inline-flex; align-items:center; gap:0.4rem;">
                                <i class="fas fa-plus"></i> Nuevo
                            </button>
                        </div>
                        @error('client_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <input type="hidden" name="cliente_nombre" id="cliente_nombre_hidden" value="{{ old('cliente_nombre', $project->cliente_nombre) }}">
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
                        <input type="text" name="cliente_contacto" id="cliente_contacto" class="form-control"
                               value="{{ old('cliente_contacto', $project->cliente_contacto) }}" placeholder="Teléfono o WhatsApp (se completa con el del cliente si se deja vacío)">
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Nuevo Cliente --}}
        <div id="modalNuevoCliente" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(3px); z-index:10000; align-items:center; justify-content:center; padding:1rem;">
            <div style="background:white; border-radius:16px; max-width:480px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.3); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; background:var(--gradient-blue); color:white; display:flex; justify-content:space-between; align-items:center;">
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <i class="fas fa-user-plus"></i>
                        <strong>Nuevo cliente</strong>
                    </div>
                    <button type="button" id="cerrarModalCliente" style="background:none; border:none; color:white; font-size:1.2rem; cursor:pointer; opacity:0.8;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div style="padding:1.5rem;">
                    <div id="modalErrorCliente" style="display:none; padding:0.75rem 1rem; background:#fef2f2; color:#c53030; border-radius:10px; border-left:4px solid #dc3545; margin-bottom:1rem; font-size:0.85rem;"></div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">
                            Nombre <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="text" id="nuevoClienteNombre" placeholder="Nombre completo"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Teléfono</label>
                        <input type="text" id="nuevoClienteTelefono" placeholder="+57 300 000 0000"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Empresa</label>
                        <input type="text" id="nuevoClienteEmpresa" placeholder="Nombre de la empresa"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Identificación</label>
                        <input type="text" id="nuevoClienteIdentificacion" placeholder="NIT / Cédula / RUT"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                        <button type="button" id="cancelarModalCliente" style="padding:0.65rem 1.2rem; border:1px solid #ddd; background:white; color:#666; font-weight:600; border-radius:10px; cursor:pointer;">
                            Cancelar
                        </button>
                        <button type="button" id="guardarNuevoCliente" style="padding:0.65rem 1.4rem; border:none; background:var(--gradient-blue); color:white; font-weight:600; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function () {
                const modal = document.getElementById('modalNuevoCliente');
                const select = document.getElementById('client_id');
                const hiddenNombre = document.getElementById('cliente_nombre_hidden');
                const telefonoInput = document.getElementById('cliente_contacto');
                const nombreField = document.getElementById('nuevoClienteNombre');
                const telField = document.getElementById('nuevoClienteTelefono');
                const empField = document.getElementById('nuevoClienteEmpresa');
                const idField = document.getElementById('nuevoClienteIdentificacion');
                const errorBox = document.getElementById('modalErrorCliente');

                function openModal() {
                    modal.style.display = 'flex';
                    errorBox.style.display = 'none';
                    nombreField.value = telField.value = empField.value = idField.value = '';
                    setTimeout(() => nombreField.focus(), 50);
                }
                function closeModal() { modal.style.display = 'none'; }

                document.getElementById('btnNuevoCliente').addEventListener('click', openModal);
                document.getElementById('cerrarModalCliente').addEventListener('click', closeModal);
                document.getElementById('cancelarModalCliente').addEventListener('click', closeModal);
                modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
                document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.style.display === 'flex') closeModal(); });

                // Sync hidden cliente_nombre from select selection (legacy fallback)
                function syncHiddenFromSelect() {
                    const opt = select.options[select.selectedIndex];
                    if (opt && opt.value) {
                        hiddenNombre.value = opt.textContent.split(' · ')[0].trim();
                        // Si el campo teléfono está vacío y el cliente tiene teléfono → sugerirlo
                        const tel = opt.dataset.telefono;
                        if (tel && !telefonoInput.value) telefonoInput.value = tel;
                    } else {
                        hiddenNombre.value = '';
                    }
                }
                select.addEventListener('change', syncHiddenFromSelect);
                syncHiddenFromSelect();

                document.getElementById('guardarNuevoCliente').addEventListener('click', async () => {
                    errorBox.style.display = 'none';
                    const nombre = nombreField.value.trim();
                    if (!nombre) {
                        errorBox.textContent = 'El nombre es obligatorio.';
                        errorBox.style.display = 'block';
                        nombreField.focus();
                        return;
                    }

                    const btn = document.getElementById('guardarNuevoCliente');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                    try {
                        const res = await fetch('{{ route('admin.clients.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    || document.querySelector('input[name="_token"]').value,
                            },
                            body: JSON.stringify({
                                nombre,
                                telefono: telField.value.trim() || null,
                                empresa: empField.value.trim() || null,
                                identificacion: idField.value.trim() || null,
                            }),
                        });
                        const data = await res.json();
                        if (!res.ok || !data.ok) {
                            const msg = data?.errors
                                ? Object.values(data.errors).flat().join(' · ')
                                : (data?.message || 'No se pudo crear el cliente.');
                            throw new Error(msg);
                        }
                        const c = data.client;
                        const opt = new Option(
                            c.nombre + (c.empresa ? ' · ' + c.empresa : ''),
                            c.id, true, true
                        );
                        opt.dataset.telefono = c.telefono || '';
                        opt.dataset.empresa = c.empresa || '';
                        opt.dataset.identificacion = c.identificacion || '';
                        select.add(opt);
                        select.value = c.id;
                        syncHiddenFromSelect();
                        closeModal();
                    } catch (err) {
                        errorBox.textContent = err.message;
                        errorBox.style.display = 'block';
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Guardar';
                    }
                });
            })();
        </script>

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
                            <input type="number" id="precio" name="precio" class="form-control @error('precio') is-invalid @enderror"
                                   value="{{ old('precio', $project->precio) }}" required step="0.01" min="0" placeholder="0.00">
                            <select name="moneda" id="moneda" class="form-select">
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
                        <div class="field-label"><i class="fas fa-user-cog"></i> Desarrollador</div>
                        <div style="display:flex; gap:0.5rem; align-items:stretch;">
                            <select name="developer_id" id="developer_id" class="form-select @error('developer_id') is-invalid @enderror" style="flex:1;">
                                <option value="">— Sin desarrollador —</option>
                                @foreach($developers as $d)
                                    <option value="{{ $d->id }}"
                                            data-email="{{ $d->email }}"
                                            data-telefono="{{ $d->telefono }}"
                                            data-pago="{{ $d->pago_default }}"
                                            data-moneda="{{ $d->moneda_default }}"
                                            {{ old('developer_id', $project->developer_id) == $d->id ? 'selected' : '' }}>
                                        {{ $d->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="btnNuevoDev" class="btn btn-primary"
                                    style="padding:0 1rem; border-radius:10px; border:none; background:var(--gradient-blue); color:white; font-weight:600; white-space:nowrap; display:inline-flex; align-items:center; gap:0.4rem;">
                                <i class="fas fa-plus"></i> Nuevo
                            </button>
                        </div>
                        @error('developer_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <input type="hidden" name="desarrollador_nombre" id="desarrollador_nombre_hidden" value="{{ old('desarrollador_nombre', $project->desarrollador_nombre) }}">
                    </div>
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-envelope"></i> Email del Desarrollador</div>
                        <input type="email" name="desarrollador_email" id="desarrollador_email" class="form-control"
                               value="{{ old('desarrollador_email', $project->desarrollador_email) }}" placeholder="dev@ejemplo.com">
                    </div>
                </div>
                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-hand-holding-usd"></i> Pago al Desarrollador</div>
                        <div class="price-group">
                            <input type="number" name="desarrollador_pago" id="desarrollador_pago" class="form-control"
                                   value="{{ old('desarrollador_pago', $project->desarrollador_pago) }}" step="0.01" min="0" placeholder="0.00">
                            <select name="desarrollador_moneda" id="desarrollador_moneda" class="form-select">
                                <option value="COP" {{ old('desarrollador_moneda', $project->desarrollador_moneda) == 'COP' ? 'selected' : '' }}>COP</option>
                                <option value="USD" {{ old('desarrollador_moneda', $project->desarrollador_moneda) == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        <div class="field-hint">Monto que se le pagará al desarrollador por este proyecto (auto-sugerido si el dev tiene tarifa).</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Nuevo Dev --}}
        <div id="modalNuevoDev" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(3px); z-index:10000; align-items:center; justify-content:center; padding:1rem;">
            <div style="background:white; border-radius:16px; max-width:480px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.3); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; background:linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); color:white; display:flex; justify-content:space-between; align-items:center;">
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <i class="fas fa-user-plus"></i>
                        <strong>Nuevo desarrollador</strong>
                    </div>
                    <button type="button" id="cerrarModalDev" style="background:none; border:none; color:white; font-size:1.2rem; cursor:pointer; opacity:0.8;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div style="padding:1.5rem;">
                    <div id="modalErrorDev" style="display:none; padding:0.75rem 1rem; background:#fef2f2; color:#c53030; border-radius:10px; border-left:4px solid #dc3545; margin-bottom:1rem; font-size:0.85rem;"></div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">
                            Nombre <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="text" id="nuevoDevNombre" placeholder="Nombre completo"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Teléfono</label>
                        <input type="text" id="nuevoDevTelefono" placeholder="+57 300 000 0000"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Email</label>
                        <input type="email" id="nuevoDevEmail" placeholder="dev@ejemplo.com"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1.5rem; display:grid; grid-template-columns: 2fr 1fr; gap:0.6rem;">
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Tarifa por defecto</label>
                            <input type="number" id="nuevoDevPago" placeholder="0.00" step="0.01" min="0"
                                   style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                        </div>
                        <div>
                            <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Moneda</label>
                            <select id="nuevoDevMoneda" style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                                <option value="COP">COP</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                        <button type="button" id="cancelarModalDev" style="padding:0.65rem 1.2rem; border:1px solid #ddd; background:white; color:#666; font-weight:600; border-radius:10px; cursor:pointer;">
                            Cancelar
                        </button>
                        <button type="button" id="guardarNuevoDev" style="padding:0.65rem 1.4rem; border:none; background:linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%); color:white; font-weight:600; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function () {
                const modal = document.getElementById('modalNuevoDev');
                const select = document.getElementById('developer_id');
                const hiddenNombre = document.getElementById('desarrollador_nombre_hidden');
                const emailInput = document.getElementById('desarrollador_email');
                const pagoInput = document.getElementById('desarrollador_pago');
                const monedaSelect = document.getElementById('desarrollador_moneda');

                const f = {
                    nombre: document.getElementById('nuevoDevNombre'),
                    telefono: document.getElementById('nuevoDevTelefono'),
                    email: document.getElementById('nuevoDevEmail'),
                    pago: document.getElementById('nuevoDevPago'),
                    moneda: document.getElementById('nuevoDevMoneda'),
                };
                const errorBox = document.getElementById('modalErrorDev');

                function openModal() {
                    modal.style.display = 'flex';
                    errorBox.style.display = 'none';
                    Object.values(f).forEach(el => { if (el.tagName === 'INPUT') el.value = ''; });
                    f.moneda.value = 'COP';
                    setTimeout(() => f.nombre.focus(), 50);
                }
                function closeModal() { modal.style.display = 'none'; }

                document.getElementById('btnNuevoDev').addEventListener('click', openModal);
                document.getElementById('cerrarModalDev').addEventListener('click', closeModal);
                document.getElementById('cancelarModalDev').addEventListener('click', closeModal);
                modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
                document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.style.display === 'flex') closeModal(); });

                function syncFromDev(userChange) {
                    const opt = select.options[select.selectedIndex];
                    if (!opt || !opt.value) {
                        // Solo limpiar si fue un cambio manual del usuario (preservar legacy en load inicial)
                        if (userChange) hiddenNombre.value = '';
                        return;
                    }
                    hiddenNombre.value = opt.textContent.trim();
                    if (opt.dataset.email && !emailInput.value) emailInput.value = opt.dataset.email;
                    if (opt.dataset.pago && (!pagoInput.value || pagoInput.value === '0' || pagoInput.value === '0.00')) {
                        pagoInput.value = opt.dataset.pago;
                    }
                    if (opt.dataset.moneda) monedaSelect.value = opt.dataset.moneda;
                }
                select.addEventListener('change', () => syncFromDev(true));
                syncFromDev(false);

                document.getElementById('guardarNuevoDev').addEventListener('click', async () => {
                    errorBox.style.display = 'none';
                    const nombre = f.nombre.value.trim();
                    if (!nombre) {
                        errorBox.textContent = 'El nombre es obligatorio.';
                        errorBox.style.display = 'block';
                        f.nombre.focus();
                        return;
                    }
                    const btn = document.getElementById('guardarNuevoDev');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                    try {
                        const res = await fetch('{{ route('admin.developers.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    || document.querySelector('input[name="_token"]').value,
                            },
                            body: JSON.stringify({
                                nombre,
                                telefono: f.telefono.value.trim() || null,
                                email: f.email.value.trim() || null,
                                pago_default: f.pago.value ? parseFloat(f.pago.value) : null,
                                moneda_default: f.moneda.value,
                            }),
                        });
                        const data = await res.json();
                        if (!res.ok || !data.ok) {
                            const msg = data?.errors
                                ? Object.values(data.errors).flat().join(' · ')
                                : (data?.message || 'No se pudo crear el desarrollador.');
                            throw new Error(msg);
                        }
                        const d = data.developer;
                        const opt = new Option(d.nombre, d.id, true, true);
                        opt.dataset.email = d.email || '';
                        opt.dataset.telefono = d.telefono || '';
                        opt.dataset.pago = d.pago_default || '';
                        opt.dataset.moneda = d.moneda_default || 'COP';
                        select.add(opt);
                        select.value = d.id;
                        syncFromDev();
                        closeModal();
                    } catch (err) {
                        errorBox.textContent = err.message;
                        errorBox.style.display = 'block';
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Guardar';
                    }
                });
            })();
        </script>

        {{-- Comisión / Vendedor (gestión) --}}
        <div class="form-section">
            <div class="form-section-header">
                <i class="fas fa-handshake"></i>
                <h3>Comisión de gestión (vendedor)</h3>
            </div>
            <div class="form-section-body">
                <div class="field-row">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-user-tie"></i> Vendedor / gestor</div>
                        <div style="display:flex; gap:0.5rem; align-items:stretch;">
                            <select name="vendedor_id" id="vendedor_id" class="form-select @error('vendedor_id') is-invalid @enderror" style="flex:1;">
                                <option value="">— Sin vendedor —</option>
                                @foreach($vendedores as $v)
                                    <option value="{{ $v->id }}"
                                            data-comision-default="{{ $v->comision_porcentaje_default }}"
                                            {{ old('vendedor_id', $project->vendedor_id) == $v->id ? 'selected' : '' }}>
                                        {{ $v->nombre }}@if($v->comision_porcentaje_default) · {{ $v->comision_porcentaje_default }}%@endif
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="btnNuevoVendedor" class="btn btn-primary"
                                    style="padding:0 1rem; border-radius:10px; border:none; background:linear-gradient(135deg, #34d399 0%, #059669 100%); color:white; font-weight:600; white-space:nowrap; display:inline-flex; align-items:center; gap:0.4rem;">
                                <i class="fas fa-plus"></i> Nuevo
                            </button>
                        </div>
                        @error('vendedor_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-calculator"></i> Tipo de comisión</div>
                        <div style="display:flex; gap:0.5rem;">
                            <label style="flex:1; display:flex; align-items:center; gap:0.5rem; padding:0.55rem 0.8rem; border:2px solid #e9ecef; border-radius:10px; cursor:pointer; font-weight:600; font-size:0.88rem;">
                                <input type="radio" name="comision_tipo" value="porcentaje"
                                    {{ old('comision_tipo', $project->comision_tipo ?? 'porcentaje') === 'porcentaje' ? 'checked' : '' }}
                                    style="accent-color:#059669;">
                                <i class="fas fa-percent"></i> Porcentaje
                            </label>
                            <label style="flex:1; display:flex; align-items:center; gap:0.5rem; padding:0.55rem 0.8rem; border:2px solid #e9ecef; border-radius:10px; cursor:pointer; font-weight:600; font-size:0.88rem;">
                                <input type="radio" name="comision_tipo" value="monto"
                                    {{ old('comision_tipo', $project->comision_tipo) === 'monto' ? 'checked' : '' }}
                                    style="accent-color:#059669;">
                                <i class="fas fa-dollar-sign"></i> Monto fijo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="field-row single">
                    <div class="field-group">
                        <div class="field-label"><i class="fas fa-money-bill"></i> Valor de la comisión</div>
                        <div style="position:relative;">
                            <input type="number" name="comision_valor" id="comision_valor" class="form-control"
                                   value="{{ old('comision_valor', $project->comision_valor) }}" step="0.01" min="0" placeholder="Ej: 25">
                            <span id="comisionUnidad" style="position:absolute; right:1rem; top:50%; transform:translateY(-50%); color:#666; font-weight:600; pointer-events:none;">%</span>
                        </div>
                        <div class="field-hint">
                            Porcentaje → se aplica sobre (precio − pago dev). Monto fijo → se paga tal cual.
                        </div>
                    </div>
                </div>

                <div id="comisionPreview" style="margin-top:0.75rem; padding:1rem 1.25rem; border-radius:12px; background:linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border:1px solid #a7f3d0; display:none;">
                    <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; justify-content:space-between;">
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; font-weight:700; letter-spacing:0.3px; color:#047857; margin-bottom:0.2rem;">
                                <i class="fas fa-coins"></i> Comisión a pagar al vendedor
                            </div>
                            <div style="font-size:1.35rem; font-weight:800; color:#065f46; line-height:1.1;" id="comisionMontoTxt">$0</div>
                        </div>
                        <div style="font-size:0.78rem; color:#065f46; line-height:1.4; text-align:right;" id="comisionDetalle"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Nuevo Vendedor --}}
        <div id="modalNuevoVendedor" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(3px); z-index:10000; align-items:center; justify-content:center; padding:1rem;">
            <div style="background:white; border-radius:16px; max-width:480px; width:100%; box-shadow:0 25px 60px rgba(0,0,0,0.3); overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; background:linear-gradient(135deg, #34d399 0%, #059669 100%); color:white; display:flex; justify-content:space-between; align-items:center;">
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <i class="fas fa-user-plus"></i>
                        <strong>Nuevo vendedor</strong>
                    </div>
                    <button type="button" id="cerrarModalVendedor" style="background:none; border:none; color:white; font-size:1.2rem; cursor:pointer; opacity:0.8;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div style="padding:1.5rem;">
                    <div id="modalErrorVendedor" style="display:none; padding:0.75rem 1rem; background:#fef2f2; color:#c53030; border-radius:10px; border-left:4px solid #dc3545; margin-bottom:1rem; font-size:0.85rem;"></div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">
                            Nombre <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="text" id="nuevoVendedorNombre" placeholder="Nombre completo"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Teléfono</label>
                        <input type="text" id="nuevoVendedorTelefono" placeholder="+57 300 000 0000"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">Email</label>
                        <input type="email" id="nuevoVendedorEmail" placeholder="vendedor@ejemplo.com"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-weight:600; font-size:0.85rem; color:var(--dark-text); margin-bottom:0.35rem;">% de comisión por defecto</label>
                        <input type="number" id="nuevoVendedorComision" placeholder="25" step="0.01" min="0" max="100"
                               style="width:100%; padding:0.65rem 0.9rem; border:2px solid #e9ecef; border-radius:10px; font-size:0.92rem;">
                        <div style="font-size:0.78rem; color:#888; margin-top:0.3rem;">Se sugerirá al asignarlo a un proyecto. Opcional.</div>
                    </div>

                    <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                        <button type="button" id="cancelarModalVendedor" style="padding:0.65rem 1.2rem; border:1px solid #ddd; background:white; color:#666; font-weight:600; border-radius:10px; cursor:pointer;">
                            Cancelar
                        </button>
                        <button type="button" id="guardarNuevoVendedor" style="padding:0.65rem 1.4rem; border:none; background:linear-gradient(135deg, #34d399 0%, #059669 100%); color:white; font-weight:600; border-radius:10px; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem;">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                const modal = document.getElementById('modalNuevoVendedor');
                const select = document.getElementById('vendedor_id');
                const valor = document.getElementById('comision_valor');
                const unidad = document.getElementById('comisionUnidad');
                const preview = document.getElementById('comisionPreview');
                const montoTxt = document.getElementById('comisionMontoTxt');
                const detalle = document.getElementById('comisionDetalle');
                const precioInput = document.getElementById('precio');
                const monedaInput = document.getElementById('moneda');
                const pagoDevInput = document.getElementById('desarrollador_pago');
                const monedaDevInput = document.getElementById('desarrollador_moneda');

                const f = {
                    nombre: document.getElementById('nuevoVendedorNombre'),
                    telefono: document.getElementById('nuevoVendedorTelefono'),
                    email: document.getElementById('nuevoVendedorEmail'),
                    comision: document.getElementById('nuevoVendedorComision'),
                };
                const errorBox = document.getElementById('modalErrorVendedor');

                function openModal() {
                    modal.style.display = 'flex';
                    errorBox.style.display = 'none';
                    Object.values(f).forEach(el => el.value = '');
                    setTimeout(() => f.nombre.focus(), 50);
                }
                function closeModal() { modal.style.display = 'none'; }

                document.getElementById('btnNuevoVendedor').addEventListener('click', openModal);
                document.getElementById('cerrarModalVendedor').addEventListener('click', closeModal);
                document.getElementById('cancelarModalVendedor').addEventListener('click', closeModal);
                modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
                document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.style.display === 'flex') closeModal(); });

                const USD_COP = {{ (float) config('services.usd_cop', env('USD_COP_RATE', 4000)) }};
                const fmt = (n, m) => (m === 'USD' ? 'US$' : '$') + Number(n).toLocaleString('es-CO', { maximumFractionDigits: 0 }) + (m === 'USD' ? ' USD' : '');

                function getTipo() {
                    const r = document.querySelector('input[name="comision_tipo"]:checked');
                    return r ? r.value : 'porcentaje';
                }

                function syncUnidad() {
                    unidad.textContent = getTipo() === 'porcentaje' ? '%' : (monedaInput.value === 'USD' ? 'USD' : 'COP');
                }

                function calcular() {
                    syncUnidad();
                    const val = parseFloat(valor.value) || 0;
                    if (val <= 0) { preview.style.display = 'none'; return; }

                    if (getTipo() === 'monto') {
                        montoTxt.textContent = fmt(val, monedaInput.value);
                        detalle.textContent = 'Monto fijo pactado';
                        preview.style.display = 'block';
                        return;
                    }

                    const precio = parseFloat(precioInput.value) || 0;
                    const pagoDev = parseFloat(pagoDevInput?.value) || 0;
                    if (precio <= 0) { preview.style.display = 'none'; return; }

                    // Normalizar pago dev a moneda del proyecto
                    let pagoDevEnMoneda = pagoDev;
                    const devM = monedaDevInput?.value || 'COP';
                    const projM = monedaInput.value;
                    if (devM !== projM) {
                        if (devM === 'USD' && projM === 'COP') pagoDevEnMoneda = pagoDev * USD_COP;
                        else if (devM === 'COP' && projM === 'USD') pagoDevEnMoneda = pagoDev / USD_COP;
                    }
                    const base = Math.max(precio - pagoDevEnMoneda, 0);
                    const comision = base * (val / 100);

                    montoTxt.textContent = fmt(comision, projM);
                    detalle.innerHTML = '<strong>' + val + '%</strong> de ' + fmt(base, projM)
                        + '<br><span style="opacity:0.8;">(precio ' + fmt(precio, projM)
                        + ' − pago dev ' + fmt(pagoDevEnMoneda, projM) + ')</span>';
                    preview.style.display = 'block';
                }

                document.querySelectorAll('input[name="comision_tipo"]').forEach(r => r.addEventListener('change', calcular));
                valor.addEventListener('input', calcular);
                precioInput?.addEventListener('input', calcular);
                monedaInput?.addEventListener('change', calcular);
                pagoDevInput?.addEventListener('input', calcular);
                monedaDevInput?.addEventListener('change', calcular);

                // Sugerir % default al elegir vendedor
                select.addEventListener('change', () => {
                    const opt = select.options[select.selectedIndex];
                    const def = opt?.dataset?.comisionDefault;
                    if (def && !valor.value) {
                        document.querySelector('input[name="comision_tipo"][value="porcentaje"]').checked = true;
                        valor.value = def;
                    }
                    calcular();
                });

                calcular();

                document.getElementById('guardarNuevoVendedor').addEventListener('click', async () => {
                    errorBox.style.display = 'none';
                    const nombre = f.nombre.value.trim();
                    if (!nombre) {
                        errorBox.textContent = 'El nombre es obligatorio.';
                        errorBox.style.display = 'block';
                        f.nombre.focus();
                        return;
                    }
                    const btn = document.getElementById('guardarNuevoVendedor');
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                    try {
                        const res = await fetch('{{ route('admin.vendedores.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    || document.querySelector('input[name="_token"]').value,
                            },
                            body: JSON.stringify({
                                nombre,
                                telefono: f.telefono.value.trim() || null,
                                email: f.email.value.trim() || null,
                                comision_porcentaje_default: f.comision.value ? parseFloat(f.comision.value) : null,
                            }),
                        });
                        const data = await res.json();
                        if (!res.ok || !data.ok) {
                            const msg = data?.errors
                                ? Object.values(data.errors).flat().join(' · ')
                                : (data?.message || 'No se pudo crear el vendedor.');
                            throw new Error(msg);
                        }
                        const v = data.vendedor;
                        const label = v.nombre + (v.comision_porcentaje_default ? ' · ' + v.comision_porcentaje_default + '%' : '');
                        const opt = new Option(label, v.id, true, true);
                        opt.dataset.comisionDefault = v.comision_porcentaje_default || '';
                        select.add(opt);
                        select.value = v.id;
                        if (v.comision_porcentaje_default && !valor.value) {
                            document.querySelector('input[name="comision_tipo"][value="porcentaje"]').checked = true;
                            valor.value = v.comision_porcentaje_default;
                        }
                        calcular();
                        closeModal();
                    } catch (err) {
                        errorBox.textContent = err.message;
                        errorBox.style.display = 'block';
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Guardar';
                    }
                });
            })();
        </script>

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

<script>
    (function () {
        const checkbox = document.getElementById('es_recurrente');
        const fechaGroup = document.getElementById('fecha-entrega-group');
        const fechaInput = document.getElementById('fecha_entrega');

        function sync() {
            if (checkbox.checked) {
                fechaGroup.style.opacity = '0.4';
                fechaGroup.style.pointerEvents = 'none';
                fechaInput.value = '';
                fechaInput.disabled = true;
            } else {
                fechaGroup.style.opacity = '1';
                fechaGroup.style.pointerEvents = 'auto';
                fechaInput.disabled = false;
            }
        }

        checkbox.addEventListener('change', sync);
        sync();
    })();
</script>
@endsection
