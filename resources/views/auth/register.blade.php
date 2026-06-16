@extends('layouts.app-home')

@section('content')
<section class="min-h-screen bg-mt-bg-2 flex items-center justify-center px-5 pt-28 pb-16 md:pt-32">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-mt-strong border border-mt-border overflow-hidden grid lg:grid-cols-2">

        {{-- ===== Panel marca (izquierda) ===== --}}
        <div class="relative hidden lg:flex flex-col justify-between bg-mt-bg-dark text-white p-12 overflow-hidden">
            <div aria-hidden="true" class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-mt-accent/25 blur-3xl"></div>
            <div aria-hidden="true" class="absolute -bottom-28 -left-20 w-72 h-72 rounded-full bg-mt-accent/10 blur-3xl"></div>

            <div class="relative z-10">
                <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions"
                     class="h-11 w-auto mb-10 brightness-0 invert">
                <p class="mt-eyebrow text-mt-accent-on-dark mb-5">Únete a nosotros</p>
                <h2 class="font-display text-4xl xl:text-5xl leading-[1.05] tracking-tight mb-5">
                    Crea tu cuenta<br>en segundos.
                </h2>
                <p class="text-mt-text-on-dark text-[15px] leading-relaxed max-w-sm">
                    Accede a todas las funcionalidades de la plataforma. Gestiona tus
                    proyectos de forma eficiente y colabora con tu equipo.
                </p>
            </div>

            <ul class="relative z-10 flex flex-col gap-4 mt-12">
                @foreach ([
                    'Registro rápido y seguro',
                    'Protección de datos garantizada',
                    'Acceso inmediato a la plataforma',
                    'Colaboración en tiempo real',
                ] as $feature)
                    <li class="flex items-center gap-3 text-[15px] text-mt-text-on-dark">
                        <span class="shrink-0 w-6 h-6 rounded-full bg-mt-accent/20 flex items-center justify-center text-mt-accent-on-dark">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 0 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- ===== Panel formulario (derecha) ===== --}}
        <div class="p-8 sm:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions"
                     class="h-10 w-auto mb-6 lg:hidden">
                <h1 class="font-display text-3xl sm:text-4xl tracking-tight text-mt-text mb-2">
                    Crear cuenta
                </h1>
                <p class="text-mt-text-2 text-[15px]">
                    Completa los siguientes datos para registrarte.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5"
                  x-data="{ show1: false, show2: false }">
                @csrf

                {{-- Nombre + Email --}}
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-mt-text mb-2">Nombre completo</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               placeholder="Tu nombre" required autofocus autocomplete="name"
                               class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('name') border-red-400 ring-1 ring-red-300 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-mt-text mb-2">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               placeholder="correo@empresa.com" required autocomplete="username"
                               class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('email') border-red-400 ring-1 ring-red-300 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Contraseña + Confirmar --}}
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-medium text-mt-text mb-2">Contraseña</label>
                        <div class="relative">
                            <input id="password" :type="show1 ? 'text' : 'password'" name="password"
                                   placeholder="Mínimo 8 caracteres" required autocomplete="new-password"
                                   class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 pr-12 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('password') border-red-400 ring-1 ring-red-300 @enderror">
                            <button type="button" @click="show1 = !show1"
                                    :aria-label="show1 ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-mt-text-3 hover:text-mt-accent transition">
                                <svg x-show="!show1" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="2.8"/>
                                </svg>
                                <svg x-show="show1" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a16 16 0 0 1-3.3 3.9M6.3 7.8A16 16 0 0 0 2.5 12s3.5 6 9.5 6a9.6 9.6 0 0 0 3.6-.7"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-mt-text mb-2">Confirmar contraseña</label>
                        <div class="relative">
                            <input id="password_confirmation" :type="show2 ? 'text' : 'password'" name="password_confirmation"
                                   placeholder="Repite tu contraseña" required autocomplete="new-password"
                                   class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 pr-12 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('password_confirmation') border-red-400 ring-1 ring-red-300 @enderror">
                            <button type="button" @click="show2 = !show2"
                                    :aria-label="show2 ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-mt-text-3 hover:text-mt-accent transition">
                                <svg x-show="!show2" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="2.8"/>
                                </svg>
                                <svg x-show="show2" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a16 16 0 0 1-3.3 3.9M6.3 7.8A16 16 0 0 0 2.5 12s3.5 6 9.5 6a9.6 9.6 0 0 0 3.6-.7"/>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="mt-btn-primary w-full justify-center mt-1">
                    Crear cuenta
                    <span aria-hidden="true">→</span>
                </button>

                <p class="text-center text-sm text-mt-text-2 mt-2">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="font-semibold text-mt-accent hover:text-mt-accent-hover transition">
                        Inicia sesión aquí
                    </a>
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
