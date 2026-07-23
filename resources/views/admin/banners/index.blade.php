@extends('layouts.app_admin')

@section('content')
<style>
    .bn-wrap { max-width:1320px; margin:0 auto; padding:1.5rem 1.75rem 3rem; background:#F6F7F9; }
    .bn-hero {
        background: linear-gradient(135deg,#1E293B 0%,#0F172A 100%);
        color:#fff; border-radius:16px; padding:1.5rem 1.75rem; margin-bottom:1.25rem;
        display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;
    }
    .bn-hero h1 { font-size:1.35rem; font-weight:800; margin:0; display:flex; align-items:center; gap:.55rem; color:#fff; letter-spacing:-.02em; }
    .bn-hero p { font-size:.82rem; opacity:.75; margin:.2rem 0 0; }
    .bn-hero .icon { display:inline-flex; width:36px; height:36px; border-radius:10px; background:rgba(59,130,246,.25); align-items:center; justify-content:center; color:#93C5FD; }
    .bn-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.55rem 1rem; border-radius:10px; font-weight:600; font-size:.83rem; text-decoration:none; border:1px solid rgba(255,255,255,.14); background:rgba(255,255,255,.08); color:#E2E8F0; }
    .bn-btn:hover { background:rgba(255,255,255,.14); color:#fff; }

    .bn-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.25rem 1.4rem; margin-bottom:1.25rem; }
    .bn-card h3 { font-size:1rem; font-weight:800; color:#0F172A; margin:0 0 1rem; display:flex; align-items:center; gap:.5rem; }

    .bn-form { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:.85rem; align-items:end; }
    .bn-fld { display:flex; flex-direction:column; gap:.25rem; }
    .bn-fld.full { grid-column:1/-1; }
    .bn-fld label { font-size:.68rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; }
    .bn-fld input, .bn-fld select, .bn-fld textarea { padding:.55rem .75rem; border:1px solid #E2E8F0; border-radius:8px; font-size:.87rem; font-family:inherit; }
    .bn-fld input:focus, .bn-fld select:focus, .bn-fld textarea:focus { outline:none; border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
    .bn-fld textarea { resize:vertical; min-height:64px; }
    .bn-submit { padding:.6rem 1.3rem; border:none; background:#2563EB; color:#fff; border-radius:9px; font-size:.87rem; font-weight:700; cursor:pointer; }
    .bn-submit:hover { background:#1D4ED8; }

    /* Preview / listado */
    .bn-item { border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; margin-bottom:1rem; background:#fff; }
    .bn-preview { padding:1.25rem 1.5rem; color:#fff; display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap; }
    .bn-preview img { height:72px; width:auto; border-radius:10px; object-fit:cover; }
    .bn-preview .txt { flex:1; min-width:200px; }
    .bn-preview h4 { font-size:1.15rem; font-weight:800; margin:0 0 .25rem; letter-spacing:-.01em; }
    .bn-preview p { font-size:.87rem; opacity:.92; margin:0; line-height:1.5; }
    .bn-preview .cta { display:inline-flex; align-items:center; gap:.4rem; margin-top:.6rem; padding:.45rem .95rem; background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.35); border-radius:9px; color:#fff; text-decoration:none; font-size:.82rem; font-weight:700; }
    .bn-meta { padding:.7rem 1.25rem; background:#FAFBFC; border-top:1px solid #F1F5F9; display:flex; align-items:center; gap:1rem; flex-wrap:wrap; font-size:.79rem; color:#64748B; }
    .bn-meta .pill { padding:.18rem .6rem; border-radius:999px; font-size:.72rem; font-weight:700; }
    .bn-meta .pill.on { background:#DCFCE7; color:#166534; }
    .bn-meta .pill.off { background:#F1F5F9; color:#64748B; }
    .bn-meta .pill.dest { background:#DBEAFE; color:#1D4ED8; }
    .bn-meta form { display:inline; }
    .bn-meta .act { border:none; background:none; cursor:pointer; font-size:.79rem; font-weight:700; padding:.2rem .5rem; border-radius:6px; }
    .bn-meta .act.edit { color:#2563EB; }
    .bn-meta .act.del { color:#DC2626; }
    .bn-meta .act:hover { background:#F1F5F9; }
    .bn-meta .spacer { margin-left:auto; display:flex; gap:.3rem; }

    .bn-edit-form { padding:1rem 1.25rem; border-top:1px solid #F1F5F9; background:#F8FAFC; }
</style>

<div class="bn-wrap">
    <div class="bn-hero">
        <div>
            <h1><span class="icon"><i class="fas fa-bullhorn"></i></span> Banners para comerciales</h1>
            <p>Mensajes e imágenes motivacionales que verán en su panel "Mis resultados".</p>
        </div>
        <a href="{{ route('admin.internal-projects.liquidacion') }}" class="bn-btn"><i class="fas fa-file-invoice-dollar"></i> Liquidación</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
    @endif

    {{-- Crear --}}
    <div class="bn-card">
        <h3><i class="fas fa-plus-circle" style="color:#2563EB;"></i> Nuevo banner</h3>
        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data" class="bn-form">
            @csrf
            <div class="bn-fld full">
                <label>Título *</label>
                <input type="text" name="titulo" required maxlength="150" placeholder="¡Meta de julio: 5 cierres = 7% de comisión!">
            </div>
            <div class="bn-fld full">
                <label>Mensaje</label>
                <textarea name="mensaje" maxlength="600" placeholder="Cierra 3 proyectos y tu comisión sube a 6% sobre TODO el ciclo. Con 5 llegas al 7%."></textarea>
            </div>
            <div class="bn-fld">
                <label>Imagen (opcional)</label>
                <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp,.gif">
            </div>
            <div class="bn-fld">
                <label>Color</label>
                <select name="color">
                    @foreach(\App\Models\ComercialBanner::COLORES as $k => $c)
                        <option value="{{ $k }}">{{ $c['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bn-fld">
                <label>Dirigido a</label>
                <select name="user_id">
                    <option value="">Todos los comerciales</option>
                    @foreach($comerciales as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bn-fld">
                <label>Texto del botón</label>
                <input type="text" name="cta_texto" maxlength="60" placeholder="Ver mi pipeline">
            </div>
            <div class="bn-fld">
                <label>URL del botón</label>
                <input type="url" name="cta_url" maxlength="500" placeholder="https://...">
            </div>
            <div class="bn-fld">
                <label>Desde</label>
                <input type="date" name="desde">
            </div>
            <div class="bn-fld">
                <label>Hasta</label>
                <input type="date" name="hasta">
            </div>
            <div class="bn-fld">
                <label>Orden</label>
                <input type="number" name="orden" value="0" min="0" max="999">
            </div>
            <div class="bn-fld">
                <button type="submit" class="bn-submit"><i class="fas fa-save"></i> Crear banner</button>
            </div>
        </form>
    </div>

    {{-- Listado --}}
    <div class="bn-card">
        <h3><i class="fas fa-images" style="color:#2563EB;"></i> Banners ({{ $banners->count() }})</h3>

        @forelse($banners as $b)
            <div class="bn-item">
                <div class="bn-preview" style="background: {{ $b->gradiente }};">
                    @if($b->imagen)
                        <img src="{{ asset('storage/'.$b->imagen) }}" alt="">
                    @endif
                    <div class="txt">
                        <h4>{{ $b->titulo }}</h4>
                        @if($b->mensaje)<p>{{ $b->mensaje }}</p>@endif
                        @if($b->cta_texto && $b->cta_url)
                            <span class="cta">{{ $b->cta_texto }} <i class="fas fa-arrow-right"></i></span>
                        @endif
                    </div>
                </div>
                <div class="bn-meta">
                    <span class="pill {{ $b->activo ? 'on' : 'off' }}">{{ $b->activo ? 'ACTIVO' : 'INACTIVO' }}</span>
                    <span class="pill dest">{{ $b->user?->name ?? 'Todos' }}</span>
                    <span>
                        {{ $b->desde?->format('d/m/Y') ?? 'sin inicio' }} → {{ $b->hasta?->format('d/m/Y') ?? 'sin fin' }}
                        · orden {{ $b->orden }}
                    </span>
                    <span class="spacer">
                        <form method="POST" action="{{ route('admin.banners.toggle', $b) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="act edit">{{ $b->activo ? 'Desactivar' : 'Activar' }}</button>
                        </form>
                        <button type="button" class="act edit" onclick="document.getElementById('edit-{{ $b->id }}').classList.toggle('d-none')">Editar</button>
                        <form method="POST" action="{{ route('admin.banners.destroy', $b) }}" onsubmit="return confirm('¿Eliminar este banner?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="act del">Eliminar</button>
                        </form>
                    </span>
                </div>
                <div id="edit-{{ $b->id }}" class="bn-edit-form d-none">
                    <form method="POST" action="{{ route('admin.banners.update', $b) }}" enctype="multipart/form-data" class="bn-form">
                        @csrf @method('PUT')
                        <div class="bn-fld full">
                            <label>Título *</label>
                            <input type="text" name="titulo" required maxlength="150" value="{{ $b->titulo }}">
                        </div>
                        <div class="bn-fld full">
                            <label>Mensaje</label>
                            <textarea name="mensaje" maxlength="600">{{ $b->mensaje }}</textarea>
                        </div>
                        <div class="bn-fld">
                            <label>Cambiar imagen</label>
                            <input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp,.gif">
                        </div>
                        <div class="bn-fld">
                            <label>Color</label>
                            <select name="color">
                                @foreach(\App\Models\ComercialBanner::COLORES as $k => $c)
                                    <option value="{{ $k }}" {{ $b->color === $k ? 'selected' : '' }}>{{ $c['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bn-fld">
                            <label>Dirigido a</label>
                            <select name="user_id">
                                <option value="">Todos los comerciales</option>
                                @foreach($comerciales as $c)
                                    <option value="{{ $c->id }}" {{ $b->user_id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bn-fld">
                            <label>Texto del botón</label>
                            <input type="text" name="cta_texto" maxlength="60" value="{{ $b->cta_texto }}">
                        </div>
                        <div class="bn-fld">
                            <label>URL del botón</label>
                            <input type="url" name="cta_url" maxlength="500" value="{{ $b->cta_url }}">
                        </div>
                        <div class="bn-fld">
                            <label>Desde</label>
                            <input type="date" name="desde" value="{{ $b->desde?->format('Y-m-d') }}">
                        </div>
                        <div class="bn-fld">
                            <label>Hasta</label>
                            <input type="date" name="hasta" value="{{ $b->hasta?->format('Y-m-d') }}">
                        </div>
                        <div class="bn-fld">
                            <label>Orden</label>
                            <input type="number" name="orden" value="{{ $b->orden }}" min="0" max="999">
                        </div>
                        <div class="bn-fld">
                            <label style="display:flex; align-items:center; gap:.4rem; text-transform:none; font-size:.8rem; color:#334155;">
                                <input type="checkbox" name="activo" value="1" {{ $b->activo ? 'checked' : '' }} style="width:16px; height:16px; accent-color:#2563EB;">
                                Activo
                            </label>
                            <button type="submit" class="bn-submit"><i class="fas fa-save"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div style="text-align:center; padding:2.5rem; color:#94A3B8;">
                <i class="fas fa-bullhorn" style="font-size:2rem; opacity:.4;"></i>
                <p style="margin-top:.5rem;">Aún no hay banners. Crea el primero arriba.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
