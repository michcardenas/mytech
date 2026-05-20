@php
    // Detecta cuál ruta está activa para resaltar el link
    $isActive = function (...$names) {
        foreach ($names as $n) { if (request()->routeIs($n)) return true; }
        return false;
    };

    // Items del menú — fuente única para desktop y mobile
    $navItems = [
        ['route' => 'home',                 'label' => 'Inicio'],
        ['route' => 'servicios.index',      'label' => 'Servicios'],
        ['route' => 'proyectos.index',      'label' => 'Proyectos'],
        ['route' => 'blog.index',           'label' => 'Blog'],
        ['route' => 'sobre_nosotros.index', 'label' => 'Sobre nosotros'],
    ];
@endphp

<nav data-home-navbar
     class="fixed top-0 left-0 right-0 z-50 py-5 md:py-6"
     x-data="{ open: false }"
     x-effect="document.documentElement.style.overflow = open ? 'hidden' : ''"
     @keydown.escape.window="open = false">

    <div class="mt-container flex items-center justify-between gap-6">

        {{-- Logo --}}
        <a href="{{ route('home') }}"
           class="flex items-center shrink-0 nav-logo-link relative z-[60]"
           aria-label="MY Tech Solutions">
            <img src="{{ asset('images/logo.png') }}"
                 alt="MY Tech Solutions"
                 class="nav-logo h-11 md:h-12 w-auto transition-all duration-500">
        </a>

        {{-- Desktop nav --}}
        <ul class="hidden lg:flex items-center gap-9 xl:gap-11">
            @foreach($navItems as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                       class="nav-link {{ $isActive($item['route']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                </li>
            @endforeach
        </ul>

        {{-- CTA derecha --}}
        <a href="{{ route('contacto.index') }}" class="hidden lg:inline-flex mt-btn-primary !text-[14px] !px-5 !py-3">
            Hablemos
            <span aria-hidden="true">→</span>
        </a>

        {{-- Mobile toggle — barras que morphean a X --}}
        <button type="button"
                class="mt-mobile-toggle lg:hidden relative z-[60]"
                :class="{ 'is-open': open }"
                :aria-label="open ? 'Cerrar menú' : 'Abrir menú'"
                :aria-expanded="open"
                @click="open = !open">
            <span class="mt-mobile-toggle-bar" aria-hidden="true"></span>
            <span class="mt-mobile-toggle-bar" aria-hidden="true"></span>
        </button>
    </div>

    {{-- ===== Mobile menu — full screen editorial ===== --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="mt-mobile-menu lg:hidden">

        {{-- Eyebrow superior --}}
        <div class="mt-mobile-menu-eyebrow">
            <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">Menú</span>
            <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">Esc para cerrar</span>
        </div>

        {{-- Nav editorial --}}
        <ul class="mt-mobile-menu-list">
            @foreach($navItems as $i => $item)
                <li class="mt-mobile-menu-item" style="--idx: {{ $i }}">
                    <a href="{{ route($item['route']) }}"
                       class="mt-mobile-menu-link {{ $isActive($item['route']) ? 'is-active' : '' }}">
                        <span class="mt-mobile-menu-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="mt-mobile-menu-label">{{ $item['label'] }}</span>
                        <span class="mt-mobile-menu-arrow" aria-hidden="true">→</span>
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Footer del menú --}}
        <div class="mt-mobile-menu-foot" style="--idx: {{ count($navItems) }}">
            <div class="mt-mobile-menu-contact">
                <a href="https://wa.me/573337246403"
                   target="_blank" rel="noopener"
                   class="block text-mt-text font-display font-semibold text-lg">
                    +57 333 724 6403
                </a>
                <span class="block mt-1 font-mono text-[11px] uppercase tracking-wider text-mt-text-3">
                    WhatsApp · Respuesta en 24h
                </span>
            </div>
            <a href="{{ route('contacto.index') }}" class="mt-btn-primary w-full justify-center">
                Cotiza tu proyecto
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</nav>
