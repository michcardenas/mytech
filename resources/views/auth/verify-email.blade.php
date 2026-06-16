@extends('layouts.app-home')

@section('content')
<section class="min-h-screen bg-mt-bg-2 flex items-center justify-center px-5 pt-28 pb-16 md:pt-32">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-mt-strong border border-mt-border p-8 sm:p-10">
        <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="h-10 w-auto mb-7">

        <h1 class="font-display text-3xl tracking-tight text-mt-text mb-2">
            Verifica tu correo
        </h1>
        <p class="text-mt-text-2 text-[15px] mb-7">
            ¡Gracias por registrarte! Antes de empezar, verifica tu correo haciendo clic en
            el enlace que te acabamos de enviar. Si no lo recibiste, con gusto te enviamos otro.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                Se ha enviado un nuevo enlace de verificación al correo que indicaste en el registro.
            </div>
        @endif

        <div class="flex flex-col gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="mt-btn-primary w-full justify-center">
                    Reenviar correo de verificación
                    <span aria-hidden="true">→</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm font-medium text-mt-text-2 hover:text-mt-accent transition">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
