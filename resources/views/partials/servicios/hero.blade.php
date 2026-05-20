@php
    // Contenido editable desde /admin/pages/servicios/edit
    $sc = [];
    if (isset($page) && $page && $page->content) {
        $sc = json_decode($page->content, true) ?? [];
    }

    $heroBadge = $sc['serv_hero_badge'] ?? 'Servicios';
    $heroTitle = $sc['serv_hero_title'] ?? $sc['hero_title'] ?? 'Construimos el software que tu empresa necesita.';
    $heroDesc  = $sc['serv_hero_description'] ?? $sc['hero_description'] ?? 'Desde plataformas web a medida hasta automatización con IA. Diseñamos cada solución alrededor de tu negocio, no al revés.';
    $heroBtn   = $sc['serv_hero_button_text'] ?? 'Cotiza tu proyecto';
@endphp

<section class="relative min-h-[88vh] flex items-center pt-36 pb-24 overflow-hidden bg-white"
         data-servicios-hero>

    {{-- Decoración editorial: número masivo de la sección + grano sutil --}}
    <div class="absolute right-0 bottom-0 pointer-events-none select-none opacity-[0.04] leading-none"
         aria-hidden="true">
        <span class="font-display font-bold text-[28vw] text-mt-text block translate-y-[5%]">06</span>
    </div>

    <div class="mt-container relative z-10">
        <div class="max-w-5xl">

            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                    {{ $heroBadge }}
                </span>
            </div>

            <h1 class="mt-7 text-hero font-display text-mt-text text-balance" data-animate>
                {{ $heroTitle }}
            </h1>

            <p class="mt-7 max-w-2xl text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                {{ $heroDesc }}
            </p>

            <div class="mt-11 flex flex-wrap items-center gap-3.5" data-animate>
                <a href="{{ route('contacto.index') }}" class="mt-btn-primary">
                    {{ $heroBtn }}
                    <span aria-hidden="true">→</span>
                </a>
                <a href="#storytelling" class="inline-flex items-center gap-2 text-mt-text-2 hover:text-mt-accent transition-colors text-sm font-mono uppercase tracking-[0.16em]">
                    <span class="w-8 h-px bg-mt-text-3"></span>
                    Explora cada servicio
                </a>
            </div>

            {{-- Mini-indicador editorial: 06 servicios disponibles --}}
            <div class="mt-16 pt-8 border-t border-mt-border max-w-md flex items-baseline gap-4" data-animate>
                <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">06 servicios</span>
                <span class="flex-1 h-px bg-mt-border"></span>
                <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">Build · Grow · Maintain</span>
            </div>
        </div>
    </div>
</section>
