@php
    $pc = [];
    if (isset($page) && $page && $page->content) {
        $pc = json_decode($page->content, true) ?? [];
    }

    $eyebrow       = $pc['proy_hero_eyebrow']      ?? 'Portafolio';
    $titleMain     = $pc['proy_hero_title_main']   ?? 'Plataformas que';
    $titleAccent   = $pc['proy_hero_title_accent'] ?? 'ya operan';
    $description   = $pc['proy_hero_description']  ?? 'Desde marketplaces en producción hasta SaaS multi-tenant, automatizaciones con IA y herramientas internas.';
    $watermark     = $pc['proy_hero_watermark']    ?? 'BUILT';
@endphp

<section class="mt-proy-hero relative min-h-[88vh] flex items-center pt-36 pb-24 overflow-hidden bg-white"
         data-proyectos-hero>

    {{-- Watermark gigante tipográfico — parallaxed (inverso) --}}
    <div class="mt-proy-hero-watermark" aria-hidden="true" data-proyectos-watermark>
        <span>{{ $watermark }}</span>
    </div>

    <div class="mt-container relative z-10">
        <div class="max-w-5xl">

            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                    {{ $eyebrow }}
                </span>
            </div>

            <h1 class="mt-7 font-display font-bold text-mt-text leading-[0.95] tracking-tight text-balance
                       text-[clamp(3rem,9vw,7rem)]"
                data-proyectos-hero-title>
                <span class="inline-block">{{ $titleMain }}</span>
                <span class="inline-block text-mt-accent italic font-display">{{ $titleAccent }}</span>.
            </h1>

            <p class="mt-8 max-w-2xl text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                {{ $description }}
            </p>

            {{-- CTAs --}}
            <div class="mt-10 flex flex-wrap items-center gap-4" data-animate>
                <a href="#grid" class="mt-btn-primary">
                    Ver portafolio completo
                    <span aria-hidden="true">→</span>
                </a>
                <a href="{{ route('contacto.index') }}" class="mt-btn-ghost">
                    Iniciar mi proyecto
                </a>
            </div>

        </div>
    </div>
</section>
