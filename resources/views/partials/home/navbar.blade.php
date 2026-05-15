@php
    // Detecta cuál ruta está activa para resaltar el link
    $isActive = function(...$names) {
        foreach ($names as $n) { if (request()->routeIs($n)) return true; }
        return false;
    };
@endphp

<nav data-home-navbar
     class="fixed top-0 left-0 right-0 z-50 py-5 md:py-6"
     x-data="{ open: false }">
    <div class="mt-container flex items-center justify-between gap-6">

        {{-- Logo --}}
        <a href="{{ route('home.v2') }}" class="flex items-center shrink-0 nav-logo-link" aria-label="MY Tech Solutions">
            <img src="{{ asset('images/logo.png') }}"
                 alt="MY Tech Solutions"
                 class="nav-logo h-11 md:h-12 w-auto transition-all duration-500">
        </a>

        {{-- Desktop nav --}}
        <ul class="hidden lg:flex items-center gap-9 xl:gap-11">
            <li><a href="{{ route('home.v2') }}"               class="nav-link {{ $isActive('home.v2', 'home') ? 'is-active' : '' }}">Inicio</a></li>
            <li><a href="{{ route('servicios.index') }}"       class="nav-link {{ $isActive('servicios.index') ? 'is-active' : '' }}">Servicios</a></li>
            <li><a href="{{ route('proyectos.index') }}"       class="nav-link {{ $isActive('proyectos.index') ? 'is-active' : '' }}">Proyectos</a></li>
            <li><a href="{{ route('blog.index') }}"            class="nav-link {{ $isActive('blog.index') ? 'is-active' : '' }}">Blog</a></li>
            <li><a href="{{ route('sobre_nosotros.index') }}"  class="nav-link {{ $isActive('sobre_nosotros.index') ? 'is-active' : '' }}">Sobre Nosotros</a></li>
        </ul>

        {{-- CTA derecha --}}
        <a href="{{ route('contacto.index') }}" class="hidden lg:inline-flex mt-btn-primary !text-[14px] !px-5 !py-3">
            Hablemos
            <span aria-hidden="true">→</span>
        </a>

        {{-- Mobile toggle --}}
        <button type="button"
                class="lg:hidden inline-flex items-center justify-center w-11 h-11 rounded-full border border-mt-border-2 bg-white/80 backdrop-blur text-mt-text transition hover:border-mt-accent hover:text-mt-accent"
                aria-label="Abrir menú"
                @click="open = !open">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-show="!open">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h10"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-show="open" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden mx-4 mt-3 rounded-2xl bg-white/95 backdrop-blur-xl border border-mt-border shadow-mt-medium overflow-hidden">
        <ul class="flex flex-col py-2">
            <li><a href="{{ route('home.v2') }}"               class="mobile-nav-link {{ $isActive('home.v2', 'home') ? 'is-active' : '' }}">Inicio</a></li>
            <li><a href="{{ route('servicios.index') }}"       class="mobile-nav-link {{ $isActive('servicios.index') ? 'is-active' : '' }}">Servicios</a></li>
            <li><a href="{{ route('proyectos.index') }}"       class="mobile-nav-link {{ $isActive('proyectos.index') ? 'is-active' : '' }}">Proyectos</a></li>
            <li><a href="{{ route('blog.index') }}"            class="mobile-nav-link {{ $isActive('blog.index') ? 'is-active' : '' }}">Blog</a></li>
            <li><a href="{{ route('sobre_nosotros.index') }}"  class="mobile-nav-link {{ $isActive('sobre_nosotros.index') ? 'is-active' : '' }}">Sobre Nosotros</a></li>
        </ul>
        <div class="p-4 border-t border-mt-border bg-mt-bg-2">
            <a href="{{ route('contacto.index') }}" class="mt-btn-primary w-full justify-center">
                Hablemos
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</nav>
