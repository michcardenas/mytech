@extends('layouts.app_admin')

@section('content')
<style>
    .mt-wrap { padding:1.5rem 1.75rem; max-width:1000px; }
    .mt-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .mt-sub { color:#64748B; font-size:.9rem; margin:0 0 1.4rem; }
    .mt-sec { font-size:.85rem; font-weight:800; text-transform:uppercase; letter-spacing:.05em; color:#334155; margin:1.4rem 0 .7rem; }
    .mt-card { background:#fff; border:1px solid #E5E7EB; border-radius:12px; padding:.9rem 1rem; margin-bottom:.6rem; }
    .mt-date { background:#EFF6FF; color:#1D4ED8; border-radius:9px; padding:.45rem .6rem; text-align:center; min-width:58px; font-weight:800; line-height:1; }
    .mt-date small { display:block; font-size:.62rem; font-weight:700; text-transform:uppercase; opacity:.7; margin-top:.15rem; }
    .mt-lead { font-weight:700; color:#0F172A; text-decoration:none; }
    .mt-lead:hover { color:#2563EB; }
    .mt-past { opacity:.85; }
</style>

<div class="mt-wrap">
    <h1 class="mt-title">Reuniones</h1>
    <p class="mt-sub">Tus reuniones agendadas y su historial. La reunión de cierre la realiza el admin.</p>

    @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif

    <div class="mt-sec"><i class="fas fa-calendar-day text-primary me-1"></i> Próximas ({{ $proximas->count() }})</div>
    @forelse($proximas as $m)
        <div class="mt-card">
            <div class="d-flex align-items-center gap-3">
                <div class="mt-date">
                    {{ $m->scheduled_at->format('d') }}
                    <small>{{ $m->scheduled_at->translatedFormat('M') }}</small>
                </div>
                <div class="flex-grow-1">
                    <a href="{{ $m->lead ? route('pipeline.leads.show', $m->lead) : '#' }}" class="mt-lead">
                        {{ $m->titulo ?: ($m->lead->nombre ?? 'Reunión') }}
                    </a>
                    <div class="text-muted small">
                        <span class="badge" style="background:{{ \App\Models\Meeting::TIPOS[$m->tipo]['color'] ?? '#2563EB' }}">{{ $m->tipo_label }}</span>
                        · {{ $m->scheduled_at->format('H:i') }} · {{ $m->scheduled_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('pipeline.meetings.update', $m) }}" class="row g-2 mt-1 align-items-end">
                @csrf @method('PUT')
                <input type="hidden" name="tipo" value="{{ $m->tipo }}">
                <input type="hidden" name="scheduled_at" value="{{ $m->scheduled_at->format('Y-m-d\TH:i') }}">
                <div class="col-md-3">
                    <select name="estado" class="form-select form-select-sm">
                        @foreach($estadosReunion as $k => $e)<option value="{{ $k }}" @selected($m->estado===$k)>{{ $e['label'] }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-7">
                    <input name="resultado" class="form-control form-control-sm" placeholder="Resultado / notas de la reunión" value="{{ $m->resultado }}">
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-sm btn-outline-primary w-100">Actualizar</button>
                </div>
            </form>
        </div>
    @empty
        <p class="text-muted small">No tienes reuniones próximas.</p>
    @endforelse

    @if($pasadas->count())
        <div class="mt-sec"><i class="fas fa-clock-rotate-left me-1"></i> Historial</div>
        @foreach($pasadas as $m)
            <div class="mt-card mt-past d-flex align-items-center gap-3">
                <div class="mt-date" style="background:#F1F5F9;color:#64748B">
                    {{ $m->scheduled_at->format('d') }}<small>{{ $m->scheduled_at->translatedFormat('M') }}</small>
                </div>
                <div class="flex-grow-1">
                    <a href="{{ $m->lead ? route('pipeline.leads.show', $m->lead) : '#' }}" class="mt-lead">{{ $m->titulo ?: ($m->lead->nombre ?? 'Reunión') }}</a>
                    <div class="text-muted small">{{ $m->tipo_label }} · {{ $m->scheduled_at->format('d/m/Y H:i') }}</div>
                </div>
                <span class="badge" style="background:{{ $m->estado_color }}">{{ $m->estado_label }}</span>
            </div>
        @endforeach
    @endif
</div>
@endsection
