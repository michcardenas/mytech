@php
    // Detecta cuál ruta está activa para resaltar el link
    $isActive = function (...$names) {
        foreach ($names as $n) { if (request()->routeIs($n)) return true; }
        return false;
    };

    // Items del menú — fuente única para desktop y mobile.
    // 'children' convierte el item en dropdown (desktop) / sub-lista (mobile).
    $navItems = [
        ['route' => 'home',                 'label' => 'Inicio'],
        ['route' => 'servicios.index',      'label' => 'Servicios', 'children' => [
            ['url' => url('/chatbots-ia-whatsapp'), 'label' => 'Chatbots con IA para WhatsApp', 'desc' => 'Atienden, cobran y agendan 24/7'],
            ['url' => url('/desarrollo-ecommerce'), 'label' => 'Tiendas online / E-commerce',    'desc' => 'A la medida, sin comisiones'],
            ['url' => url('/software-a-la-medida'), 'label' => 'Software a la medida',            'desc' => 'SaaS, ERP, CRM y plataformas'],
            ['url' => route('servicios.index'),     'label' => 'Todos los servicios',             'desc' => 'Ver el panorama completo', 'foot' => true],
        ]],
        ['route' => 'proyectos.index',      'label' => 'Proyectos'],
        ['route' => 'blog.index',           'label' => 'Blog'],
        ['route' => 'sobre_nosotros.index', 'label' => 'Sobre nosotros'],
    ];
@endphp

{{-- Wrapper Alpine: navbar y mobile menu son SIBLINGS para evitar que el
     backdrop-filter del navbar.is-scrolled cree un containing block que
     rompa el position:fixed del menu. Cuando open=true, también marcamos
     <html> con clase para ocultar overlays (WhatsApp, etc.) via CSS. --}}
<div x-data="{ open: false }"
     x-effect="
        document.documentElement.style.overflow = open ? 'hidden' : '';
        document.documentElement.classList.toggle('has-mobile-menu-open', open);
     "
     @keydown.escape.window="open = false">

    <nav data-home-navbar class="fixed top-0 left-0 right-0 z-50 py-5 md:py-6">
        <div class="mt-container flex items-center justify-between gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="flex items-center shrink-0 nav-logo-link relative z-[70]"
               aria-label="MY Tech Solutions">
                <img src="{{ asset('images/logo.png') }}"
                     alt="MY Tech Solutions"
                     width="500" height="251"
                     fetchpriority="high"
                     class="nav-logo h-11 md:h-12 w-auto transition-all duration-500">
            </a>

            {{-- Desktop nav --}}
            <ul class="hidden lg:flex items-center gap-9 xl:gap-11">
                @foreach($navItems as $item)
                    @if(!empty($item['children']))
                        <li class="relative" x-data="{ o: false }" @mouseenter="o = true" @mouseleave="o = false">
                            <a href="{{ route($item['route']) }}"
                               class="nav-link {{ $isActive($item['route']) ? 'is-active' : '' }} inline-flex items-center gap-1.5"
                               @focus="o = true" :aria-expanded="o">
                                {{ $item['label'] }}
                                <svg class="w-3 h-3 transition-transform duration-300" :class="o && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                            </a>
                            <div x-show="o" x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute top-full left-1/2 -translate-x-1/2 pt-4 z-[60]">
                                <div class="w-[330px] rounded-2xl border border-mt-border bg-white shadow-mt-strong p-2">
                                    @foreach($item['children'] as $child)
                                        <a href="{{ $child['url'] }}"
                                           class="group/c flex items-center justify-between gap-4 px-3 py-2.5 rounded-xl hover:bg-mt-bg-2 transition-colors {{ !empty($child['foot']) ? 'mt-1 border-t border-mt-border rounded-t-none pt-3' : '' }}">
                                            <span class="min-w-0">
                                                <span class="block font-display font-semibold text-mt-text text-[14px] leading-tight">{{ $child['label'] }}</span>
                                                <span class="block text-mt-text-3 text-[12px] mt-0.5 leading-snug">{{ $child['desc'] }}</span>
                                            </span>
                                            <span class="shrink-0 text-mt-accent opacity-0 -translate-x-1 group-hover/c:opacity-100 group-hover/c:translate-x-0 transition-all" aria-hidden="true">→</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="nav-link {{ $isActive($item['route']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- CTA derecha --}}
            <a href="{{ route('contacto.index') }}" class="hidden lg:inline-flex mt-btn-primary !text-[14px] !px-5 !py-3">
                Hablemos
                <span aria-hidden="true">→</span>
            </a>

            {{-- Mobile toggle — barras que morphean a X --}}
            <button type="button"
                    class="mt-mobile-toggle lg:hidden relative z-[70]"
                    :class="{ 'is-open': open }"
                    :aria-label="open ? 'Cerrar menú' : 'Abrir menú'"
                    :aria-expanded="open"
                    @click="open = !open">
                <span class="mt-mobile-toggle-bar" aria-hidden="true"></span>
                <span class="mt-mobile-toggle-bar" aria-hidden="true"></span>
            </button>
        </div>
    </nav>

    {{-- ===== Mobile menu — fuera del <nav> para evitar containing-block
         issues con backdrop-filter, transform, etc. ===== --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="mt-mobile-menu lg:hidden"
         role="dialog"
         aria-modal="true"
         aria-label="Menú de navegación">

        {{-- Eyebrow superior --}}
        <div class="mt-mobile-menu-eyebrow">
            <span class="mt-mobile-menu-eyebrow-label">Menú</span>
            <button type="button"
                    @click="open = false"
                    class="mt-mobile-menu-eyebrow-close"
                    aria-label="Cerrar menú">
                Cerrar
                <span aria-hidden="true">×</span>
            </button>
        </div>

        {{-- Nav editorial — cierra el menu antes de que el browser navegue
             para evitar conflictos entre la transición Alpine y la nav --}}
        <ul class="mt-mobile-menu-list">
            @foreach($navItems as $i => $item)
                <li class="mt-mobile-menu-item" style="--idx: {{ $i }}">
                    <a href="{{ route($item['route']) }}"
                       @click="open = false"
                       class="mt-mobile-menu-link {{ $isActive($item['route']) ? 'is-active' : '' }}">
                        <span class="mt-mobile-menu-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="mt-mobile-menu-label">{{ $item['label'] }}</span>
                        <span class="mt-mobile-menu-arrow" aria-hidden="true">→</span>
                    </a>
                    @if(!empty($item['children']))
                        <ul class="pl-11 pr-2 pb-3 space-y-0.5">
                            @foreach($item['children'] as $child)
                                @continue(!empty($child['foot']))
                                <li>
                                    <a href="{{ $child['url'] }}" @click="open = false"
                                       class="flex items-center justify-between gap-3 py-2 text-mt-text-2 hover:text-mt-accent transition-colors text-[15px] font-medium">
                                        {{ $child['label'] }}
                                        <span class="text-mt-text-3" aria-hidden="true">→</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>

        {{-- Footer del menú --}}
        <div class="mt-mobile-menu-foot" style="--idx: {{ count($navItems) }}">
            <div class="mt-mobile-menu-contact">
                <span class="mt-mobile-menu-contact-eyebrow">WhatsApp · Respuesta en 24h</span>
                <a href="https://wa.me/573337246403?text=Hola%2C%20me%20interesa%20conocer%20m%C3%A1s%20sobre%20sus%20servicios"
                   target="_blank" rel="noopener"
                   class="mt-mobile-menu-contact-phone">
                    +57 333 724 6403
                </a>
            </div>
            <a href="{{ route('contacto.index') }}"
               @click="open = false"
               class="mt-btn-primary w-full justify-center">
                Cotiza tu proyecto
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</div>
