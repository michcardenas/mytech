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
    .reassign-summary { background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:.75rem 1rem; margin-bottom:1rem; font-size:.88rem; }
    .reassign-summary strong { color:#0F172A; }
    .reassign-option { border:1px solid #E5E7EB; border-radius:10px; padding:.75rem 1rem; margin-bottom:.6rem; cursor:pointer; transition:border-color .15s, background .15s; }
    .reassign-option:hover { border-color:#93C5FD; background:#F8FAFC; }
    .reassign-option input[type=radio] { margin-right:.55rem; }
    .reassign-option.is-active { border-color:#2563EB; background:#EFF6FF; }
    .reassign-option .opt-title { font-weight:600; color:#0F172A; }
    .reassign-option .opt-sub { font-size:.8rem; color:#64748B; margin-top:.15rem; }
    .reassign-option select { margin-top:.5rem; }
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
                    @php
                        $role = $user->roles->pluck('name')->first();
                        $stats = $leadStats[$user->id] ?? null;
                        $total = (int) ($stats->total ?? 0);
                        $abiertos = (int) ($stats->abiertos ?? 0);
                        $importados = (int) ($stats->importados ?? 0);
                    @endphp
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
                                @if($role === 'comercial' && $total > 0)
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger js-open-reassign"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-total="{{ $total }}"
                                        data-abiertos="{{ $abiertos }}"
                                        data-importados="{{ $importados }}"
                                        data-action="{{ route('admin.users.destroy', $user) }}"
                                    ><i class="fas fa-trash"></i></button>
                                @else
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('¿Eliminar a {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endif
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

{{-- Modal reasignación --}}
<div class="modal fade" id="reassignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="reassignForm" class="modal-content">
            @csrf @method('DELETE')

            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-triangle-exclamation text-warning me-2"></i>Eliminar comercial y reasignar leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="reassign-summary">
                    Vas a eliminar a <strong id="rmName">—</strong>. Tiene
                    <strong id="rmTotal">0</strong> leads (<strong id="rmAbiertos">0</strong> abiertos,
                    <strong id="rmImportados">0</strong> importados). Elige a dónde pasan antes de borrarla.
                </div>

                @if($comerciales->count() <= 1)
                    <div class="alert alert-danger py-2 mb-0">
                        No hay otros comerciales que puedan recibir los leads. Crea uno antes de eliminar a este.
                    </div>
                @else
                    <label class="reassign-option d-block" data-mode="to_user">
                        <input type="radio" name="reassign_mode" value="to_user" checked>
                        <span class="opt-title">Reasignar a un comercial</span>
                        <div class="opt-sub">Todos los leads (y su historial) pasan a la persona que elijas.</div>
                        <select name="reassign_to" class="form-select form-select-sm mt-2">
                            @foreach($comerciales as $c)
                                <option value="{{ $c->id }}" data-comercial-id="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="reassign-option d-block" data-mode="random">
                        <input type="radio" name="reassign_mode" value="random">
                        <span class="opt-title">Distribuir al azar entre los demás comerciales</span>
                        <div class="opt-sub">Se reparte lead por lead entre {{ $comerciales->count() - 1 }} comerciales disponibles (excluyendo al que eliminas).</div>
                    </label>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                @if($comerciales->count() > 1)
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Eliminar y reasignar</button>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const modalEl = document.getElementById('reassignModal');
    if (!modalEl || typeof bootstrap === 'undefined') { return; }

    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('reassignForm');
    const nameEl = document.getElementById('rmName');
    const totalEl = document.getElementById('rmTotal');
    const abiertosEl = document.getElementById('rmAbiertos');
    const importadosEl = document.getElementById('rmImportados');
    const opciones = modalEl.querySelectorAll('.reassign-option');
    const select = modalEl.querySelector('select[name="reassign_to"]');

    document.querySelectorAll('.js-open-reassign').forEach(btn => {
        btn.addEventListener('click', () => {
            const userId = btn.dataset.userId;
            form.action = btn.dataset.action;
            nameEl.textContent = btn.dataset.userName;
            totalEl.textContent = btn.dataset.total;
            abiertosEl.textContent = btn.dataset.abiertos;
            importadosEl.textContent = btn.dataset.importados;

            // Excluir del select al comercial que se va a eliminar.
            if (select) {
                Array.from(select.options).forEach(opt => {
                    opt.hidden = opt.dataset.comercialId === userId;
                    opt.disabled = opt.dataset.comercialId === userId;
                });
                const firstVisible = Array.from(select.options).find(opt => !opt.hidden);
                if (firstVisible) { select.value = firstVisible.value; }
            }

            marcarActiva();
            modal.show();
        });
    });

    opciones.forEach(opt => {
        opt.addEventListener('change', marcarActiva);
        opt.addEventListener('click', () => {
            const radio = opt.querySelector('input[type=radio]');
            if (radio) { radio.checked = true; marcarActiva(); }
        });
    });

    function marcarActiva() {
        opciones.forEach(opt => {
            const radio = opt.querySelector('input[type=radio]');
            opt.classList.toggle('is-active', !!(radio && radio.checked));
        });
    }
})();
</script>
@endsection
