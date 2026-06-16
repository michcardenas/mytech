@extends('layouts.app-home')

@section('content')
<section class="min-h-screen bg-mt-bg-2 flex items-center justify-center px-5 pt-28 pb-16 md:pt-32">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-mt-strong border border-mt-border p-8 sm:p-10">
        <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="h-10 w-auto mb-7">

        <h1 class="font-display text-3xl tracking-tight text-mt-text mb-2">
            Confirma tu contraseña
        </h1>
        <p class="text-mt-text-2 text-[15px] mb-7">
            Esta es un área segura. Por favor confirma tu contraseña antes de continuar.
        </p>

        <form method="POST" action="{{ route('password.confirm') }}" class="flex flex-col gap-5"
              x-data="{ show: false }">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-mt-text mb-2">Contraseña</label>
                <div class="relative">
                    <input id="password" :type="show ? 'text' : 'password'" name="password"
                           placeholder="••••••••" required autocomplete="current-password"
                           class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 pr-12 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('password') border-red-400 ring-1 ring-red-300 @enderror">
                    <button type="button" @click="show = !show"
                            :aria-label="show ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-mt-text-3 hover:text-mt-accent transition">
                        <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="2.8"/>
                        </svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a16 16 0 0 1-3.3 3.9M6.3 7.8A16 16 0 0 0 2.5 12s3.5 6 9.5 6a9.6 9.6 0 0 0 3.6-.7"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="mt-btn-primary w-full justify-center">
                Confirmar
                <span aria-hidden="true">→</span>
            </button>
        </form>
    </div>
</section>
@endsection
