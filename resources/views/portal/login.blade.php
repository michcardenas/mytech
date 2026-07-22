@extends('layouts.app-home')

@php
    $palette = match ($color ?? 'purple') {
        'green' => [
            'accent' => '#059669',
            'accent_dark' => '#065f46',
            'accent_soft' => '#ECFDF5',
            'accent_line' => '#A7F3D0',
            'accent_on_dark' => '#34D399',
            'grad' => 'linear-gradient(135deg, #34d399 0%, #059669 100%)',
        ],
        'blue' => [
            'accent' => '#2563EB',
            'accent_dark' => '#1E40AF',
            'accent_soft' => '#EFF6FF',
            'accent_line' => '#BFDBFE',
            'accent_on_dark' => '#60A5FA',
            'grad' => 'linear-gradient(135deg, #60A5FA 0%, #2563EB 100%)',
        ],
        default => [
            'accent' => '#7C3AED',
            'accent_dark' => '#5B21B6',
            'accent_soft' => '#F5F3FF',
            'accent_line' => '#DDD6FE',
            'accent_on_dark' => '#A78BFA',
            'grad' => 'linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%)',
        ],
    };

    $features = match ($role ?? 'cliente') {
        'cliente' => [
            'Estado en vivo de tus proyectos',
            'Descarga recibos y facturas',
            'Comunicación directa con tu equipo',
        ],
        'vendedor' => [
            'Comisiones al día',
            'Pagos recibidos y pendientes',
            'Historial completo de gestión',
        ],
        default => [
            'Proyectos asignados',
            'Pagos recibidos',
            'Historial y estados',
        ],
    };
@endphp

@section('content')
<style>
    .portal-input {
        border-width: 1.5px !important;
        padding: 1rem 1.15rem !important;
        font-size: 1rem !important;
        font-weight: 600;
        letter-spacing: 0.01em;
        min-height: 56px;
    }
    .portal-input::placeholder {
        font-weight: 500;
        color: #94A3B8 !important;
        opacity: 1;
        letter-spacing: normal;
    }
    .portal-input:focus {
        border-color: {{ $palette['accent'] }} !important;
        box-shadow: 0 0 0 4px {{ $palette['accent'] }}20 !important;
        background: #fff !important;
    }
    .portal-submit {
        background: {{ $palette['grad'] }};
        box-shadow: 0 10px 24px {{ $palette['accent'] }}45;
        border: none;
        cursor: pointer;
        padding: 1.05rem 1.5rem !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        min-height: 56px;
        letter-spacing: 0.01em;
    }
    .portal-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px {{ $palette['accent'] }}60;
    }
    .portal-submit:active {
        transform: translateY(0);
    }
    .portal-submit svg { width: 18px; height: 18px; }
</style>

<section class="min-h-screen bg-mt-bg-2 flex items-center justify-center px-5 pt-28 pb-16 md:pt-32">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-mt-strong border border-mt-border overflow-hidden grid lg:grid-cols-2">

        {{-- ===== Panel marca (izquierda) ===== --}}
        <div class="relative hidden lg:flex flex-col justify-between bg-mt-bg-dark text-white p-12 overflow-hidden">
            <div aria-hidden="true"
                 class="absolute -top-24 -right-24 w-72 h-72 rounded-full blur-3xl"
                 style="background: {{ $palette['accent'] }}40;"></div>
            <div aria-hidden="true"
                 class="absolute -bottom-28 -left-20 w-72 h-72 rounded-full blur-3xl"
                 style="background: {{ $palette['accent'] }}18;"></div>

            <div class="relative z-10">
                <img src="{{ asset('images/logo.png') }}"
                     alt="MYTECH SOLUTIONS S.A.S"
                     class="h-11 w-auto mb-10 brightness-0 invert">
                <p class="mt-eyebrow mb-5" style="color: {{ $palette['accent_on_dark'] }};">
                    {{ match ($role ?? 'cliente') { 'cliente' => 'Portal de clientes', 'vendedor' => 'Portal de gestores', default => 'Portal de desarrolladores' } }}
                </p>
                <h2 class="font-display text-4xl xl:text-5xl leading-[1.05] tracking-tight mb-5">
                    @if(($role ?? '') === 'cliente')
                        Tu proyecto,<br>bajo control.
                    @elseif(($role ?? '') === 'vendedor')
                        Tus comisiones,<br>siempre claras.
                    @else
                        Tus proyectos,<br>tu progreso.
                    @endif
                </h2>
                <p class="text-mt-text-on-dark text-[15px] leading-relaxed max-w-sm">
                    {{ $subtitulo }}
                </p>
            </div>

            <ul class="relative z-10 flex flex-col gap-4 mt-12">
                @foreach ($features as $feature)
                    <li class="flex items-center gap-3 text-[15px] text-mt-text-on-dark">
                        <span class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center"
                              style="background: {{ $palette['accent'] }}30; color: {{ $palette['accent_on_dark'] }};">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 0 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>

            <div class="relative z-10 mt-10 pt-8 border-t border-white/10 text-xs text-mt-text-on-dark/70">
                <div class="font-semibold text-white/90">MYTECH SOLUTIONS S.A.S</div>
                <div class="mt-1">NIT 901.923.467-5 · Colombia · Latinoamérica · España</div>
            </div>
        </div>

        {{-- ===== Panel formulario (derecha) ===== --}}
        <div class="p-8 sm:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <img src="{{ asset('images/logo.png') }}"
                     alt="MY Tech Solutions"
                     class="h-10 w-auto mb-6 lg:hidden">
                <h1 class="font-display text-3xl sm:text-4xl tracking-tight text-mt-text mb-2">
                    {{ $titulo }}
                </h1>
                <p class="text-mt-text-2 text-[15px]">
                    Ingresa con tu número de teléfono para acceder.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $route_login }}" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="telefono" class="block text-sm font-medium text-mt-text mb-2">
                        Teléfono
                    </label>
                    <input id="telefono"
                           type="tel"
                           name="telefono"
                           placeholder="+57 300 123 4567"
                           required autofocus
                           value="{{ old('telefono') }}"
                           inputmode="tel" autocomplete="tel"
                           class="portal-input w-full rounded-xl bg-mt-bg-2 border border-mt-border text-mt-text placeholder:text-mt-text-3 focus:bg-white focus:outline-none transition">
                    <p class="mt-2 text-xs text-mt-text-3 flex items-start gap-1.5">
                        <i class="fas fa-globe mt-0.5" style="color: {{ $palette['accent'] }};"></i>
                        <span>
                            Aceptamos números de <strong>Colombia (+57), México (+52), España (+34), Australia (+61), EE.UU. (+1)</strong> y toda LATAM. Puedes escribirlo con o sin código de país.
                        </span>
                    </p>
                </div>

                <button type="submit"
                        class="portal-submit w-full justify-center inline-flex items-center gap-2 rounded-xl px-6 py-3.5 text-white font-semibold text-[15px] transition-all duration-200">
                    <span>Ingresar</span>
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/>
                    </svg>
                </button>

                <p class="text-center text-xs text-mt-text-3 mt-1">
                    ¿No tienes acceso? Contacta a tu ejecutivo de cuenta.
                </p>
            </form>

            <div class="mt-8 pt-6 border-t border-mt-border">
                <p class="text-center text-[11px] uppercase tracking-wider text-mt-text-3 font-semibold mb-3">
                    Otros portales
                </p>
                <div class="flex flex-wrap justify-center gap-2 text-sm">
                    @if($role !== 'cliente')
                        <a href="{{ route('portal.cliente.login.show') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-mt-bg-2 hover:bg-white hover:border-mt-accent border border-mt-border text-mt-text-2 font-medium transition">
                            <i class="fas fa-user text-xs"></i> Cliente
                        </a>
                    @endif
                    @if($role !== 'developer')
                        <a href="{{ route('portal.developer.login.show') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-mt-bg-2 hover:bg-white hover:border-mt-accent border border-mt-border text-mt-text-2 font-medium transition">
                            <i class="fas fa-laptop-code text-xs"></i> Desarrollador
                        </a>
                    @endif
                    @if($role !== 'vendedor')
                        <a href="{{ route('portal.vendedor.login.show') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-mt-bg-2 hover:bg-white hover:border-mt-accent border border-mt-border text-mt-text-2 font-medium transition">
                            <i class="fas fa-handshake text-xs"></i> Gestor
                        </a>
                    @endif
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-mt-bg-2 hover:bg-white hover:border-mt-accent border border-mt-border text-mt-text-2 font-medium transition">
                        <i class="fas fa-shield-alt text-xs"></i> Login administrativo
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
