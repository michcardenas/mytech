@extends('layouts.app_admin')

@section('content')
<style>
    .imp-wrap { padding:1.5rem 1.75rem; max-width:920px; }
    .imp-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .25rem; }
    .imp-sub { color:#64748B; font-size:.9rem; margin:0 0 1.4rem; }
    .imp-step { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; margin-bottom:1.1rem; }
    .imp-step.bolsa { border-color:#C7D2FE; background:#F5F7FF; }
    .imp-step h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0 0 .3rem; display:flex; align-items:center; gap:.5rem; }
    .imp-num { width:26px; height:26px; border-radius:50%; background:#2563EB; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:800; flex-shrink:0; }
    .imp-num.g { background:#7C3AED; }
    .imp-cols { display:flex; flex-wrap:wrap; gap:.4rem; margin:.6rem 0; }
    .imp-col { background:#EEF2F7; color:#475569; border-radius:7px; padding:.2rem .6rem; font-size:.78rem; font-weight:600; }
    .com-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:.6rem; margin-top:.6rem; }
    .com-item { display:flex; align-items:center; gap:.6rem; border:1px solid #E5E7EB; border-radius:10px; padding:.55rem .75rem; cursor:pointer; background:#fff; }
    .com-item:hover { background:#F8FAFC; }
    .com-item .nm { font-weight:600; color:#0F172A; font-size:.88rem; }
    .com-item .ct { font-size:.72rem; color:#94A3B8; }
    .bolsa-num { font-size:2rem; font-weight:800; color:#4F46E5; line-height:1; }
    .prev-table { width:100%; font-size:.82rem; margin-top:.5rem; }
    .prev-table th { font-size:.68rem; text-transform:uppercase; color:#94A3B8; font-weight:700; border-bottom:1px solid #E5E7EB; padding:.35rem .45rem; text-align:left; }
    .prev-table td { padding:.35rem .45rem; border-bottom:1px solid #F1F5F9; }
</style>

<div class="imp-wrap">
    <h1 class="imp-title"><i class="fas fa-users-rectangle text-primary me-1"></i> Importar clientes</h1>
    <p class="imp-sub">Primero <strong>importas</strong> los clientes a una bolsa, y luego los <strong>repartes</strong> al azar entre los comerciales.</p>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-circle-check me-1"></i> {{ session('success') }}
            @if(session('reparto'))
                <hr class="my-2">
                <strong class="small d-block mb-1">Reparto:</strong>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(session('reparto') as $r)
                        <span class="badge bg-primary">{{ $r['name'] }}: {{ $r['n'] }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    @if(session('error'))<div class="alert alert-danger"><i class="fas fa-triangle-exclamation me-1"></i> {{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

    {{-- Paso 1: plantilla --}}
    <div class="imp-step">
        <h3><span class="imp-num">1</span> Descarga la plantilla</h3>
        <p class="text-muted small mb-2">Ábrela en Excel y llena una fila por cliente. La columna <strong>Nombre</strong> es obligatoria; las demás son opcionales.</p>
        <div class="imp-cols">
            <span class="imp-col">Identificación</span>
            <span class="imp-col">Nombre *</span>
            <span class="imp-col">Empresa</span>
            <span class="imp-col">Teléfono 1</span>
            <span class="imp-col">Teléfono 2</span>
            <span class="imp-col">Descripción</span>
        </div>
        <a href="{{ route('pipeline.clientes.plantilla') }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-file-arrow-down me-1"></i> Descargar plantilla (.xlsx)
        </a>
    </div>

    {{-- Paso 2: importar a la bolsa --}}
    <div class="imp-step">
        <h3><span class="imp-num">2</span> Importar clientes</h3>
        <p class="text-muted small mb-2">Sube el archivo lleno. Los clientes entran a la <strong>bolsa</strong> (todavía sin asignar a nadie).</p>
        <form method="POST" action="{{ route('pipeline.clientes.cargar') }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-start flex-wrap">
            @csrf
            <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls,.csv" required style="max-width:340px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Importar a la bolsa</button>
        </form>
    </div>

    {{-- Paso 3: repartir la bolsa --}}
    <div class="imp-step bolsa">
        <h3><span class="imp-num g">3</span> Repartir la bolsa</h3>

        @if($pendientes->isEmpty())
            <p class="text-muted small mb-0"><i class="fas fa-box-open me-1"></i> La bolsa está vacía. Importa un archivo en el paso 2 para poder repartir.</p>
        @else
            <div class="d-flex align-items-center gap-3 mb-2">
                <div><span class="bolsa-num">{{ $pendientes->count() }}</span> <span class="text-muted small">clientes sin repartir</span></div>
            </div>

            {{-- vista previa --}}
            <div class="table-responsive" style="max-height:230px;overflow-y:auto">
                <table class="prev-table">
                    <thead><tr><th>Nombre</th><th>Empresa</th><th>Identificación</th><th>Teléfono</th></tr></thead>
                    <tbody>
                        @foreach($pendientes->take(10) as $c)
                            <tr><td>{{ $c->nombre }}</td><td>{{ $c->empresa ?: '—' }}</td><td>{{ $c->identificacion ?: '—' }}</td><td>{{ $c->telefono ?: '—' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
                @if($pendientes->count() > 10)<p class="text-muted small mt-1 mb-0">… y {{ $pendientes->count() - 10 }} más.</p>@endif
            </div>

            <form method="POST" action="{{ route('pipeline.clientes.repartir') }}" class="mt-3">
                @csrf
                <label class="form-label small fw-semibold mb-1">Repartir entre estos comerciales:</label>
                @if($comerciales->isEmpty())
                    <div class="alert alert-warning py-2 small mb-0">No hay usuarios con rol <strong>comercial</strong>. Crea al menos uno en <a href="{{ route('admin.users.index') }}">Usuarios</a>.</div>
                @else
                    <div class="d-flex gap-3 mb-1">
                        <a href="#" class="small" onclick="document.querySelectorAll('.com-chk').forEach(c=>c.checked=true);return false;">Seleccionar todos</a>
                        <a href="#" class="small text-muted" onclick="document.querySelectorAll('.com-chk').forEach(c=>c.checked=false);return false;">Ninguno</a>
                    </div>
                    <div class="com-grid">
                        @foreach($comerciales as $c)
                            <label class="com-item">
                                <input type="checkbox" class="com-chk" name="comerciales[]" value="{{ $c->id }}" checked>
                                <span><span class="nm d-block">{{ $c->name }}</span><span class="ct">{{ $c->abiertos_count }} prospectos abiertos</span></span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-2 mb-2"><i class="fas fa-shuffle me-1"></i> Se reparten de forma pareja y al azar entre los seleccionados.</p>
                    <button type="submit" class="btn" style="background:#7C3AED;color:#fff">
                        <i class="fas fa-shuffle me-1"></i> Repartir {{ $pendientes->count() }} clientes al azar
                    </button>
                @endif
            </form>
        @endif
    </div>

    @if($totalImportados)<p class="text-muted small">{{ $totalImportados }} clientes importados y repartidos hasta ahora.</p>@endif
</div>
@endsection
