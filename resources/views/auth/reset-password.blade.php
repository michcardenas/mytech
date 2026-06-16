@extends('layouts.app-home')

@section('content')
<section class="min-h-screen bg-mt-bg-2 flex items-center justify-center px-5 pt-28 pb-16 md:pt-32">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-mt-strong border border-mt-border p-8 sm:p-10">
        <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="h-10 w-auto mb-7">

        <h1 class="font-display text-3xl tracking-tight text-mt-text mb-2">
            Restablecer contraseña
        </h1>
        <p class="text-mt-text-2 text-[15px] mb-7">
            Elige una nueva contraseña para tu cuenta.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-5"
              x-data="{ show1: false, show2: false }">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-mt-text mb-2">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                       placeholder="tucorreo@empresa.com" required autofocus autocomplete="username"
                       class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('email') border-red-400 ring-1 ring-red-300 @enderror">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nueva contraseña --}}
            <div>
                <label for="password" class="block text-sm font-medium text-mt-text mb-2">Nueva contraseña</label>
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

            {{-- Confirmar --}}
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

            <button type="submit" class="mt-btn-primary w-full justify-center mt-1">
                Restablecer contraseña
                <span aria-hidden="true">→</span>
            </button>
        </form>
    </div>
</section>
@endsection
