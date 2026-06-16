@extends('layouts.app_admin')

@section('content')
<style>
    .cal-wrap { padding:1.5rem 1.75rem; max-width:760px; }
    .cal-title { font-size:1.4rem; font-weight:800; color:#0F172A; margin:0 0 .15rem; }
    .cal-sub { color:#64748B; font-size:.9rem; margin:0 0 1.4rem; }
    .cal-card { background:#fff; border:1px solid #E5E7EB; border-radius:16px; padding:1.6rem 1.7rem; }
    .cal-status { display:flex; align-items:center; gap:1rem; padding:1rem 1.1rem; border-radius:12px; margin-bottom:1.3rem; }
    .cal-status.on { background:#ECFDF5; border:1px solid #A7F3D0; }
    .cal-status.off { background:#F8FAFC; border:1px solid #E5E7EB; }
    .cal-ic { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; color:#fff; flex-shrink:0; }
    .gbtn { display:inline-flex; align-items:center; gap:.6rem; background:#fff; border:1px solid #DADCE0; border-radius:10px; padding:.7rem 1.2rem; font-weight:600; color:#3c4043; text-decoration:none; transition:box-shadow .2s; }
    .gbtn:hover { box-shadow:0 2px 8px rgba(0,0,0,.12); color:#3c4043; }
    .cal-step { display:flex; gap:.75rem; padding:.5rem 0; font-size:.88rem; color:#334155; }
    .cal-step .n { width:22px; height:22px; border-radius:50%; background:#EFF6FF; color:#2563EB; font-weight:700; font-size:.78rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    code { background:#F1F5F9; padding:.1rem .4rem; border-radius:5px; color:#0F172A; }
</style>

<div class="cal-wrap">
    <h1 class="cal-title">Mi calendario</h1>
    <p class="cal-sub">Conecta tu Google Calendar para que tu comercial vea tu disponibilidad y te agende las reuniones de cierre.</p>

    @if(session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif

    <div class="cal-card">
        @if($connected)
            <div class="cal-status on">
                <div class="cal-ic" style="background:#16A34A"><i class="fas fa-circle-check"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-bold text-success">Conectado</div>
                    <div class="text-muted small">{{ $email ?: 'Cuenta de Google vinculada' }}</div>
                </div>
                <form method="POST" action="{{ route('pipeline.calendar.disconnect') }}" onsubmit="return confirm('¿Desconectar tu Google Calendar?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">Desconectar</button>
                </form>
            </div>
            <p class="text-muted small mb-0"><i class="fas fa-circle-info me-1"></i> Cuando la comercial agende una reunión de cierre, se creará el evento en este calendario con un enlace de Google Meet, y te llegará la invitación.</p>
        @elseif(! $configured)
            <div class="cal-status off">
                <div class="cal-ic" style="background:#94A3B8"><i class="fas fa-triangle-exclamation"></i></div>
                <div>
                    <div class="fw-bold">Falta configurar las credenciales</div>
                    <div class="text-muted small">Agrega <code>GOOGLE_CALENDAR_CLIENT_ID</code> y <code>GOOGLE_CALENDAR_CLIENT_SECRET</code> en el archivo <code>.env</code>.</div>
                </div>
            </div>
            <h6 class="fw-bold mt-3 mb-2">Cómo obtener las credenciales (una sola vez):</h6>
            <div class="cal-step"><span class="n">1</span> Entra a <code>console.cloud.google.com</code> → crea un proyecto.</div>
            <div class="cal-step"><span class="n">2</span> APIs y servicios → Biblioteca → activa <strong>Google Calendar API</strong>.</div>
            <div class="cal-step"><span class="n">3</span> Pantalla de consentimiento OAuth → tipo Externo → agrégate como <strong>usuario de prueba</strong>.</div>
            <div class="cal-step"><span class="n">4</span> Credenciales → Crear → <strong>ID de cliente OAuth</strong> → tipo <strong>Aplicación web</strong>.</div>
            <div class="cal-step"><span class="n">5</span> En “URI de redireccionamiento autorizados” agrega: <code>{{ $redirect }}</code></div>
            <div class="cal-step"><span class="n">6</span> Copia el <strong>Client ID</strong> y <strong>Client Secret</strong> al <code>.env</code> y listo.</div>
        @else
            <div class="cal-status off">
                <div class="cal-ic" style="background:#4285F4"><i class="fab fa-google"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Sin conectar</div>
                    <div class="text-muted small">Vincula tu Google Calendar para habilitar el agendamiento.</div>
                </div>
            </div>
            <a href="{{ route('pipeline.calendar.connect') }}" class="gbtn">
                <i class="fab fa-google" style="color:#4285F4"></i> Conectar con Google
            </a>
            <p class="text-muted small mt-3 mb-0">Solo verás bloques de “ocupado/libre”; tus eventos privados no se comparten con la comercial.</p>
        @endif
    </div>
</div>
@endsection
