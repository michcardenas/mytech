@extends('layouts.app_admin')

@section('content')
<style>
    .us-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .us-head { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.3rem; }
    .us-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0; }
    .us-sub { color:#64748B; font-size:.9rem; margin:.15rem 0 0; }
    .us-card { background:#fff; border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; }
    .us-table { width:100%; font-size:.9rem; }
    .us-table th { font-size:.72rem; text-transform:uppercase; letter-spacing:.03em; color:#94A3B8; font-weight:700; border-bottom:2px solid #EEF2F7; padding:.75rem 1rem; text-align:left; }
    .us-table td { padding:.8rem 1rem; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
    .us-table tr:last-child td { border-bottom:none; }
    .us-avatar { width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#2563EB,#1D4ED8); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; }
    .role-badge { font-size:.74rem; font-weight:700; padding:.2rem .6rem; border-radius:999px; }
    .role-admin { background:#EDE9FE; color:#6D28D9; }
    .role-comercial { background:#DBEAFE; color:#1D4ED8; }
    .pl-btn { display:inline-flex; align-items:center; gap:.45rem; background:#2563EB; color:#fff; border:none; padding:.6rem 1.05rem; border-radius:10px; font-weight:600; font-size:.88rem; text-decoration:none; transition:background .2s; }
    .pl-btn:hover { background:#1D4ED8; color:#fff; }
</style>

<div class="us-wrap">
    <div class="us-head">
        <div>
            <h1 class="us-title">Usuarios del equipo</h1>
            <p class="us-sub">Administra quién entra al panel y con qué rol (administrador o comercial).</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="pl-btn"><i class="fas fa-user-plus"></i> Nuevo usuario</a>
    </div>

    @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif

    <div class="us-card">
        <table class="us-table">
            <thead>
                <tr><th>Usuario</th><th>Correo</th><th>Rol</th><th>Creado</th><th class="text-end">Acciones</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php $role = $user->roles->pluck('name')->first(); @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="us-avatar">{{ strtoupper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}</span>
                                <span class="fw-semibold">{{ $user->name }}
                                    @if($user->id === auth()->id())<span class="text-muted small">(tú)</span>@endif
                                </span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span class="role-badge {{ $role === 'admin' ? 'role-admin' : 'role-comercial' }}">
                                <i class="fas {{ $role === 'admin' ? 'fa-crown' : 'fa-user-tie' }} me-1"></i>{{ ucfirst($role ?? '—') }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $user->created_at?->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-pen"></i></a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('¿Eliminar a {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No hay usuarios todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
