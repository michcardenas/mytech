@php
    $catTints = [
        'tecnologia'   => '#2563EB',
        'desarrollo'   => '#10B981',
        'diseno'       => '#EC4899',
        'marketing'    => '#F59E0B',
        'negocios'     => '#8B5CF6',
        'tutoriales'   => '#0F766E',
        'noticias'     => '#EF4444',
        'casos-exito'  => '#2563EB',
    ];
    $tint = $catTints[$post->category] ?? '#2563EB';
    $catLabel = $post->category ? ucfirst(str_replace('-', ' ', $post->category)) : 'Artículo';
    $featuredImage = $post->featured_image ? asset('storage/'.$post->featured_image) : null;
@endphp

<section class="mt-bd-hero relative pt-36 pb-12 md:pb-20 overflow-hidden bg-white"
         data-bd-hero
         style="--bd-tint: {{ $tint }};">

    <div class="mt-container relative z-10">

        {{-- Breadcrumb editorial --}}
        <nav class="mt-bd-breadcrumb" aria-label="Breadcrumb" data-animate>
            <a href="{{ url('/') }}">Inicio</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('blog.index') }}">Blog</a>
            @if($post->category)
                <span aria-hidden="true">/</span>
                <a href="{{ route('blog.category', $post->category) }}">{{ $catLabel }}</a>
            @endif
            <span aria-hidden="true">/</span>
            <span class="mt-bd-breadcrumb-current">{{ \Illuminate\Support\Str::limit($post->title, 50) }}</span>
        </nav>

        {{-- Pills meta --}}
        <div class="mt-bd-meta-row" data-animate>
            <span class="mt-bd-pill mt-bd-pill-cat"
                  style="background: color-mix(in srgb, var(--bd-tint) 12%, white); color: var(--bd-tint); border-color: color-mix(in srgb, var(--bd-tint) 28%, transparent);">
                {{ $catLabel }}
            </span>
            @if($post->published_at)
                <span class="mt-bd-pill-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6" stroke-linecap="round"/><line x1="8" y1="2" x2="8" y2="6" stroke-linecap="round"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $post->published_at->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                </span>
            @endif
            @if($post->reading_time)
                <span class="mt-bd-pill-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $post->reading_time }} min de lectura
                </span>
            @endif
            @if($post->author)
                <span class="mt-bd-pill-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ $post->author }}
                </span>
            @endif
        </div>

        {{-- Título masivo --}}
        <h1 class="mt-bd-title font-display font-bold text-mt-text leading-[1.02] tracking-tight text-balance
                   text-[clamp(2rem,5.5vw,4.5rem)] max-w-5xl mt-7"
            data-bd-title>
            {{ $post->title }}
        </h1>

        {{-- Excerpt lead --}}
        @if($post->excerpt)
            <p class="mt-bd-excerpt mt-8 max-w-3xl text-lg md:text-xl text-mt-text-2 leading-snug font-light" data-animate>
                {{ $post->excerpt }}
            </p>
        @endif
    </div>

    {{-- Featured image full-bleed debajo del título --}}
    @if($featuredImage)
        <div class="mt-container relative z-10 mt-12 md:mt-16">
            <div class="mt-bd-featured-image" data-bd-featured-image>
                <img src="{{ $featuredImage }}"
                     alt="{{ $post->title }}"
                     loading="eager" decoding="async">
            </div>
        </div>
    @endif
</section>

{{-- Reading progress bar (sticky top, fuera del hero) --}}
<div class="mt-bd-progress-wrap" aria-hidden="true">
    <span class="mt-bd-progress-fill" data-bd-progress></span>
</div>
