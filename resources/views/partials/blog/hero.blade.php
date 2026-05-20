@php
    // Contenido editable desde /pages/{id-blog}/edit
    $bc = [];
    if (isset($page) && $page && $page->content) {
        $bc = json_decode($page->content, true) ?? [];
    }

    $totalPosts   = $totalPosts ?? ($posts->total() ?? 0);
    $totalCats    = count($categories ?? []);
    $totalTags    = count($allTags ?? []);

    $heroEyebrow     = $bc['blog_hero_eyebrow']      ?? 'Insights · MY Tech';
    $heroTitleMain   = $bc['blog_hero_title_main']   ?? 'Blog de desarrollo, SaaS';
    $heroTitleAccent = $bc['blog_hero_title_accent'] ?? 'y automatización';
    $heroDescription = $bc['blog_hero_description']  ?? 'Guías técnicas, casos reales y análisis sobre desarrollo a medida, SaaS, automatizaciones con IA y SEO para empresas en Colombia y LATAM.';
    $heroWatermark   = $bc['blog_hero_watermark']    ?? 'INSIGHTS';
@endphp

<section class="mt-blog-hero relative pt-36 pb-20 md:pb-28 overflow-hidden bg-white" data-blog-hero>

    {{-- Watermark tipográfico --}}
    <div class="mt-blog-hero-watermark" aria-hidden="true" data-blog-watermark>
        <span>{{ $heroWatermark }}</span>
    </div>

    <div class="mt-container relative z-10">
        <div class="max-w-5xl">

            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                    {{ $heroEyebrow }}
                </span>
            </div>

            <h1 class="mt-7 font-display font-bold text-mt-text leading-[0.95] tracking-tight text-balance
                       text-[clamp(3rem,9vw,7rem)]"
                data-blog-hero-title>
                <span class="inline-block">{{ $heroTitleMain }}</span>
                <span class="inline-block text-mt-accent italic font-display">{{ $heroTitleAccent }}</span>.
            </h1>

            <p class="mt-8 max-w-2xl text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                {{ $heroDescription }}
            </p>

            {{-- Metadata inline editorial (sin counters animados) --}}
            <div class="mt-10 flex flex-wrap items-center gap-x-7 gap-y-3 font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3"
                 data-animate>
                <span class="inline-flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-mt-accent"></span>
                    <strong class="text-mt-text font-display text-[14px] font-semibold">{{ $totalPosts }}</strong>
                    {{ $totalPosts === 1 ? 'artículo publicado' : 'artículos publicados' }}
                </span>
                @if($totalCats > 0)
                    <span class="inline-flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-mt-accent"></span>
                        <strong class="text-mt-text font-display text-[14px] font-semibold">{{ $totalCats }}</strong>
                        {{ $totalCats === 1 ? 'categoría' : 'categorías' }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-2">
                    <span class="w-1 h-1 rounded-full bg-mt-accent"></span>
                    Publicación
                    <strong class="text-mt-text font-display text-[14px] font-semibold">semanal</strong>
                </span>
            </div>

        </div>
    </div>
</section>
