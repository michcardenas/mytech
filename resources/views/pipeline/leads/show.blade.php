@extends('layouts.app_admin')

@php
    $convDefaults = $isAdmin ? \App\Http\Controllers\Pipeline\ConversionController::defaults($lead) : [];
@endphp

@section('content')
<style>
    .ld-wrap { padding:1.5rem 1.75rem; max-width:1200px; }
    .ld-back { color:#64748B; text-decoration:none; font-size:.85rem; }
    .ld-back:hover { color:#2563EB; }
    .ld-head { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin:.6rem 0 1.3rem; }
    .ld-title { font-size:1.5rem; font-weight:800; color:#0F172A; margin:0; }
    .ld-badges { display:flex; gap:.4rem; flex-wrap:wrap; margin-top:.5rem; }
    .ld-chip { display:inline-flex; align-items:center; gap:.35rem; font-size:.74rem; font-weight:700; padding:.2rem .6rem; border-radius:999px; color:#fff; }
    .ld-chip-light { background:#EEF2F7; color:#475569; }
    .ld-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.15rem 1.25rem; margin-bottom:1.1rem; }
    .ld-card h3 { font-size:.95rem; font-weight:800; color:#0F172A; margin:0 0 .9rem; display:flex; align-items:center; gap:.5rem; }
    .ld-card h3 .count { margin-left:auto; font-size:.72rem; color:#94A3B8; font-weight:700; }
    .ld-field { display:flex; gap:.6rem; font-size:.86rem; padding:.35rem 0; color:#334155; }
    .ld-field i { width:18px; color:#94A3B8; text-align:center; }
    .ld-field a { color:#2563EB; text-decoration:none; word-break:break-all; }

    .tl { list-style:none; margin:0; padding:0; position:relative; }
    .tl:before { content:''; position:absolute; left:13px; top:4px; bottom:4px; width:2px; background:#EEF2F7; }
    .tl-item { display:flex; gap:.75rem; padding:.55rem 0; position:relative; }
    .tl-dot { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:.7rem; flex-shrink:0; z-index:1; }
    .tl-body { flex:1; }
    .tl-body .t { font-size:.86rem; color:#1F2937; }
    .tl-body .m { font-size:.72rem; color:#94A3B8; margin-top:.1rem; }

    .ld-next { background:linear-gradient(135deg,#EFF6FF,#FFFFFF); border:1px solid #DBEAFE; }
    .ld-next.vencido { background:linear-gradient(135deg,#FEF2F2,#FFFFFF); border-color:#FECACA; }
    .prop-row, .meet-row { border:1px solid #EEF2F7; border-radius:10px; padding:.6rem .75rem; margin-bottom:.5rem; }
</style>

<div class="ld-wrap">
    <a href="{{ route('pipeline.index') }}" class="ld-back"><i class="fas fa-arrow-left"></i> Volver al pipeline</a>

    @if(session('success'))<div class="alert alert-success py-2 mt-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger py-2 mt-2">{{ session('error') }}</div>@endif

    <div class="ld-head">
        <div>
            <h1 class="ld-title">{{ $lead->nombre }}</h1>
            <div class="ld-badges">
                <span class="ld-chip" style="background:{{ $lead->fuente_color }}"><i class="{{ $lead->fuente_icon }}"></i> {{ $lead->fuente_label }}</span>
                <span class="ld-chip" style="background:{{ $lead->etapa_color }}">{{ $lead->etapa_label }}</span>
                @if($lead->estado === 'ganado')<span class="ld-chip" style="background:#16A34A"><i class="fas fa-trophy"></i> Ganado</span>@endif
                @if($lead->estado === 'perdido')<span class="ld-chip" style="background:#DC2626"><i class="fas fa-xmark"></i> Perdido</span>@endif
                @if($lead->empresa)<span class="ld-chip ld-chip-light">{{ $lead->empresa }}</span>@endif
                @if($isAdmin)<span class="ld-chip ld-chip-light"><i class="fas fa-user-tie"></i> {{ $lead->user->name }}</span>@endif
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if($isAdmin && $lead->estado !== 'ganado')
                <form method="POST" action="{{ route('pipeline.leads.ganado', $lead) }}">@csrf
                    <button class="btn btn-success btn-sm"><i class="fas fa-trophy me-1"></i> Marcar ganado</button>
                </form>
            @endif
            @if($lead->estado !== 'perdido')
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#perdidoModal"><i class="fas fa-xmark me-1"></i> Perdido</button>
            @endif
            @if($isAdmin && $lead->estado === 'ganado' && ! $lead->internal_project_id)
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#convertirModal"><i class="fas fa-rocket me-1"></i> Convertir a proyecto</button>
            @endif
            @if($lead->internal_project_id)
                <a href="{{ route('admin.internal-projects.show', $lead->internal_project_id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-briefcase me-1"></i> Ver proyecto</a>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- ===== Columna principal: actividad + bitácora ===== --}}
        <div class="col-lg-7">
            {{-- Próxima acción --}}
            <div class="ld-card ld-next {{ $lead->esta_vencido ? 'vencido' : '' }}">
                <h3><i class="fas fa-bell text-warning"></i> Próxima acción</h3>
                @if($lead->proxima_accion_at)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold {{ $lead->esta_vencido ? 'text-danger' : '' }}">
                                {{ $lead->proxima_accion_at->translatedFormat('l d M, H:i') }}
                                @if($lead->esta_vencido)<span class="badge bg-danger ms-1">Vencido</span>
                                @elseif($lead->es_hoy)<span class="badge bg-warning text-dark ms-1">Hoy</span>@endif
                            </div>
                            <div class="text-muted small">{{ $lead->proxima_accion_nota ?: 'Seguimiento programado' }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-muted small mb-0">Sin próxima acción. Regístrala abajo para no perder el seguimiento.</p>
                @endif
            </div>

            {{-- Registrar actividad --}}
            <div class="ld-card">
                <h3><i class="fas fa-pen-to-square text-primary"></i> Registrar seguimiento</h3>
                <form method="POST" action="{{ route('pipeline.activities.store', $lead) }}">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="tipo" class="form-select form-select-sm">
                                @foreach($tiposActividad as $k => $t)
                                    @if(!in_array($k, ['etapa','sistema']))
                                        <option value="{{ $k }}">{{ $t['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="descripcion" class="form-control form-control-sm" required placeholder="¿Qué pasó? Ej: Llamé, quedó de revisar la propuesta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Próxima acción</label>
                            <input type="datetime-local" name="proxima_accion_at" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">¿Qué sigue?</label>
                            <input type="text" name="proxima_accion_nota" class="form-control form-control-sm" placeholder="Ej: enviar propuesta">
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Guardar</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Bitácora --}}
            <div class="ld-card">
                <h3><i class="fas fa-clock-rotate-left text-primary"></i> Bitácora <span class="count">{{ $lead->activities->count() }}</span></h3>
                <ul class="tl">
                    @forelse($lead->activities as $act)
                        <li class="tl-item">
                            <div class="tl-dot" style="background:{{ $act->tipo_color }}"><i class="{{ $act->tipo_icon }}"></i></div>
                            <div class="tl-body">
                                <div class="t">{{ $act->descripcion }}</div>
                                <div class="m">{{ $act->tipo_label }} · {{ $act->created_at->diffForHumans() }} · {{ $act->user->name }}</div>
                            </div>
                        </li>
                    @empty
                        <li class="text-muted small">Aún no hay actividad registrada.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- ===== Columna lateral ===== --}}
        <div class="col-lg-5">
            {{-- Datos --}}
            <div class="ld-card">
                <h3><i class="fas fa-address-card text-primary"></i> Datos del lead
                    <a class="count" data-bs-toggle="collapse" href="#editLead" role="button" style="cursor:pointer"><i class="fas fa-pen"></i> Editar</a>
                </h3>
                @if($lead->valor_estimado)<div class="ld-field"><i class="fas fa-dollar-sign"></i> <strong>{{ $lead->valor_formateado }}</strong> <span class="text-muted">({{ $lead->moneda }})</span></div>@endif
                @if($lead->email)<div class="ld-field"><i class="fas fa-envelope"></i> <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></div>@endif
                @if($lead->telefono)<div class="ld-field"><i class="fab fa-whatsapp"></i> {{ $lead->telefono }}</div>@endif
                @if($lead->fuente_url)<div class="ld-field"><i class="fas fa-link"></i> <a href="{{ $lead->fuente_url }}" target="_blank">{{ \Illuminate\Support\Str::limit($lead->fuente_url, 38) }}</a></div>@endif
                @if($lead->descripcion)<div class="ld-field"><i class="fas fa-align-left"></i> <span>{{ $lead->descripcion }}</span></div>@endif

                <div class="collapse mt-3" id="editLead">
                    <form method="POST" action="{{ route('pipeline.leads.update', $lead) }}">
                        @csrf @method('PUT')
                        <div class="row g-2">
                            <div class="col-12"><input name="nombre" class="form-control form-control-sm" value="{{ $lead->nombre }}" placeholder="Nombre" required></div>
                            <div class="col-12"><input name="empresa" class="form-control form-control-sm" value="{{ $lead->empresa }}" placeholder="Empresa"></div>
                            <div class="col-6">
                                <select name="fuente" class="form-select form-select-sm">
                                    @foreach($fuentes as $k => $f)<option value="{{ $k }}" @selected($lead->fuente===$k)>{{ $f['label'] }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-6"><input name="fuente_url" class="form-control form-control-sm" value="{{ $lead->fuente_url }}" placeholder="Enlace"></div>
                            <div class="col-6"><input name="email" class="form-control form-control-sm" value="{{ $lead->email }}" placeholder="Email"></div>
                            <div class="col-6"><input name="telefono" class="form-control form-control-sm" value="{{ $lead->telefono }}" placeholder="Teléfono"></div>
                            <div class="col-7"><input type="number" step="0.01" name="valor_estimado" class="form-control form-control-sm" value="{{ $lead->valor_estimado }}" placeholder="Valor"></div>
                            <div class="col-5">
                                <select name="moneda" class="form-select form-select-sm">
                                    <option value="COP" @selected($lead->moneda==='COP')>COP</option>
                                    <option value="USD" @selected($lead->moneda==='USD')>USD</option>
                                </select>
                            </div>
                            <div class="col-12"><textarea name="descripcion" class="form-control form-control-sm" rows="2" placeholder="Descripción">{{ $lead->descripcion }}</textarea></div>
                            <input type="hidden" name="moneda_fallback" value="{{ $lead->moneda }}">
                            <div class="col-12 text-end"><button class="btn btn-primary btn-sm">Guardar cambios</button></div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Propuestas --}}
            <div class="ld-card">
                <h3><i class="fas fa-file-invoice text-primary"></i> Propuestas
                    <a class="count" data-bs-toggle="modal" href="#propuestaModal" style="cursor:pointer"><i class="fas fa-plus"></i> Agregar</a>
                </h3>
                @forelse($lead->proposals as $p)
                    <div class="prop-row d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $p->titulo ?: 'Propuesta' }} · {{ $p->monto_formateado }}</div>
                            <div class="text-muted" style="font-size:.72rem">
                                {{ $p->enviada_at?->format('d/m/Y') ?? $p->created_at->format('d/m/Y') }}
                                @if($p->url)· <a href="{{ $p->url }}" target="_blank">ver</a>@endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:{{ $p->estado_color }}">{{ $p->estado_label }}</span>
                            <form method="POST" action="{{ route('pipeline.proposals.destroy', $p) }}" onsubmit="return confirm('¿Eliminar propuesta?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-link text-muted p-0"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Sin propuestas todavía.</p>
                @endforelse
            </div>

            {{-- Reuniones --}}
            <div class="ld-card">
                <h3><i class="fas fa-calendar-check text-primary"></i> Reuniones
                    <a class="count" data-bs-toggle="modal" href="#reunionModal" style="cursor:pointer"><i class="fas fa-plus"></i> Agendar</a>
                </h3>
                <button type="button" class="btn btn-sm w-100 mb-3" style="background:#EFF6FF;color:#1D4ED8;border:1px solid #DBEAFE;font-weight:600"
                        data-bs-toggle="modal" data-bs-target="#agendarCierreModal">
                    <i class="fas fa-calendar-plus me-1"></i> Agendar cierre con el admin
                </button>
                @forelse($lead->meetings as $m)
                    <div class="meet-row d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold small">{{ $m->tipo_label }} · {{ $m->scheduled_at->format('d/m/Y H:i') }}</div>
                            @if($m->resultado)<div class="text-muted" style="font-size:.72rem">{{ \Illuminate\Support\Str::limit($m->resultado, 40) }}</div>@endif
                        </div>
                        <span class="badge" style="background:{{ $m->estado_color }}">{{ $m->estado_label }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Sin reuniones agendadas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@include('pipeline.leads.partials.modales')
@endsection
