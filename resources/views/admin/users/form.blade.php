@extends('layouts.app_admin')

@php $editing = $user->exists; $currentRole = $user->roles->pluck('name')->first() ?? 'comercial'; @endphp

@section('content')
<style>
    .uf-wrap { padding:1.5rem 1.75rem; max-width:640px; }
    .uf-back { color:#64748B; text-decoration:none; font-size:.85rem; }
    .uf-back:hover { color:#2563EB; }
    .uf-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:.5rem 0 1.2rem; }
    .uf-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; padding:1.5rem 1.6rem; }
    .role-pick { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
    .role-opt { border:2px solid #E5E7EB; border-radius:12px; padding:.9rem 1rem; cursor:pointer; transition:all .15s; }
    .role-opt:hover { border-color:#C7D2FE; }
    .role-opt input { display:none; }
    .role-opt .ic { font-size:1.1rem; margin-bottom:.35rem; }
    .role-opt .t { font-weight:700; color:#0F172A; font-size:.95rem; }
    .role-opt .d { font-size:.76rem; color:#94A3B8; }
    .role-opt.sel-admin.is-sel { border-color:#8B5CF6; background:#F5F3FF; }
    .role-opt.sel-comercial.is-sel { border-color:#2563EB; background:#EFF6FF; }
</style>

<div class="uf-wrap">
    <a href="{{ route('admin.users.index') }}" class="uf-back"><i class="fas fa-arrow-left"></i> Volver a usuarios</a>
    <h1 class="uf-title">{{ $editing ? 'Editar usuario' : 'Nuevo usuario' }}</h1>

    @if(session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif
    @if($errors->any())
        <div class="alert alert-danger py-2"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <div class="uf-card">
        <form method="POST" action="{{ $editing ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if($editing) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Correo electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ $editing ? 'Nueva contraseña' : 'Contraseña' }}</label>
                    <input type="password" name="password" class="form-control" autocomplete="new-password" {{ $editing ? '' : 'required' }}>
                    @if($editing)<small class="text-muted">Déjalo vacío para no cambiarla.</small>@endif
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" {{ $editing ? '' : 'required' }}>
                </div>
            </div>

            <label class="form-label fw-semibold">Rol</label>
            <div class="role-pick mb-4">
                <label class="role-opt sel-comercial {{ old('role', $currentRole) === 'comercial' ? 'is-sel' : '' }}">
                    <input type="radio" name="role" value="comercial" @checked(old('role', $currentRole) === 'comercial')>
                    <div class="ic text-primary"><i class="fas fa-user-tie"></i></div>
                    <div class="t">Comercial</div>
                    <div class="d">Solo su propio pipeline (leads, propuestas, reuniones).</div>
                </label>
                <label class="role-opt sel-admin {{ old('role', $currentRole) === 'admin' ? 'is-sel' : '' }}">
                    <input type="radio" name="role" value="admin" @checked(old('role', $currentRole) === 'admin')>
                    <div class="ic" style="color:#8B5CF6"><i class="fas fa-crown"></i></div>
                    <div class="t">Administrador</div>
                    <div class="d">Control y visibilidad total del panel.</div>
                </label>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary"><i class="fas fa-floppy-disk me-1"></i> {{ $editing ? 'Guardar cambios' : 'Crear usuario' }}</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.role-opt input').forEach(function (inp) {
        inp.addEventListener('change', function () {
            document.querySelectorAll('.role-opt').forEach(o => o.classList.remove('is-sel'));
            if (inp.checked) inp.closest('.role-opt').classList.add('is-sel');
        });
    });
</script>
@endsection
