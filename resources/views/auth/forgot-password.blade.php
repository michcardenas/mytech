@extends('layouts.app-home')

@section('content')
<section class="min-h-screen bg-mt-bg-2 flex items-center justify-center px-5 pt-28 pb-16 md:pt-32">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-mt-strong border border-mt-border p-8 sm:p-10">
        <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="h-10 w-auto mb-7">

        <h1 class="font-display text-3xl tracking-tight text-mt-text mb-2">
            ¿Olvidaste tu contraseña?
        </h1>
        <p class="text-mt-text-2 text-[15px] mb-7">
            No hay problema. Dinos tu correo y te enviaremos un enlace para restablecerla.
        </p>

        @if (session('status'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-mt-text mb-2">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       placeholder="tucorreo@empresa.com" required autofocus autocomplete="username"
                       class="w-full rounded-xl bg-mt-bg-2 border-mt-border px-4 py-3 text-mt-text placeholder:text-mt-text-3 focus:border-mt-accent focus:ring-2 focus:ring-mt-accent/30 focus:bg-white transition @error('email') border-red-400 ring-1 ring-red-300 @enderror">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="mt-btn-primary w-full justify-center">
                Enviar enlace de recuperación
                <span aria-hidden="true">→</span>
            </button>

            <p class="text-center text-sm text-mt-text-2 mt-1">
                <a href="{{ route('login') }}" class="font-semibold text-mt-accent hover:text-mt-accent-hover transition">
                    ← Volver a iniciar sesión
                </a>
            </p>
        </form>
    </div>
</section>
@endsection
