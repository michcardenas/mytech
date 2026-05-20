@php
    // Datos de servicios desde BD
    $sc = [];
    if (isset($page) && $page && $page->content) {
        $sc = json_decode($page->content, true) ?? [];
    }

    // Categorías design-system (igual que en home: Build / Grow / Maintain)
    // Mapeo fijo de servicio → categoría (esto NO es editable, es arquitectura)
    $categoryMap = [
        1 => ['key' => 'build',    'label' => 'Construye',  'tint' => '#2563EB'],
        2 => ['key' => 'build',    'label' => 'Construye',  'tint' => '#2563EB'],
        3 => ['key' => 'grow',     'label' => 'Crece',      'tint' => '#10B981'],
        4 => ['key' => 'grow',     'label' => 'Crece',      'tint' => '#10B981'],
        5 => ['key' => 'maintain', 'label' => 'Mantén',     'tint' => '#F59E0B'],
        6 => ['key' => 'maintain', 'label' => 'Mantén',     'tint' => '#F59E0B'],
    ];

    // Construir array de servicios con todos sus datos
    $servicios = [];
    for ($i = 1; $i <= 6; $i++) {
        $features = array_values(array_filter([
            $sc["servicio_{$i}_feature_1"] ?? null,
            $sc["servicio_{$i}_feature_2"] ?? null,
            $sc["servicio_{$i}_feature_3"] ?? null,
            $sc["servicio_{$i}_feature_4"] ?? null,
        ]));
        $tags = array_values(array_filter(array_map('trim',
            explode(',', $sc["servicio_{$i}_tags"] ?? '')
        )));
        $servicios[] = [
            'num'      => str_pad($i, 2, '0', STR_PAD_LEFT),
            'title'    => $sc["servicio_{$i}_title"]       ?? 'Servicio '.$i,
            'lead'     => $sc["servicio_{$i}_lead"]        ?? '',
            'desc'     => $sc["servicio_{$i}_description"] ?? '',
            'image'    => $sc["servicio_{$i}_image"]       ?? '',
            'tags'     => $tags,
            'precio'   => $sc["servicio_{$i}_precio"]      ?? '',
            'features' => $features,
            'category' => $categoryMap[$i],
        ];
    }
    $totalSlides = count($servicios);
@endphp

{{-- ============================================================
     STORYTELLING — Pin scrub vertical cinematográfico (desktop)
     Mobile (<lg): fallback a stack de cards full-height sin pin
     ============================================================ --}}
<section id="storytelling"
         class="mt-services-pin relative bg-mt-bg-dark text-white"
         data-services-pin
         data-total-slides="{{ $totalSlides }}"
         style="height: calc(100vh * {{ $totalSlides + 0.4 }});">

    {{-- Stage que se queda sticky mientras scrolleas la altura del wrapper --}}
    <div class="mt-services-stage" data-services-stage>

        {{-- Progress UI superior: contador 01/06 + barra --}}
        <div class="mt-services-progress" aria-hidden="true">
            <div class="mt-services-progress-bar">
                <span class="mt-services-progress-fill" data-services-progress-fill></span>
            </div>
            <div class="mt-services-progress-meta">
                <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-white/60">
                    <span data-services-progress-num>01</span> / {{ str_pad($totalSlides, 2, '0', STR_PAD_LEFT) }}
                </span>
                <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-white/60"
                      data-services-progress-cat>
                    {{ $servicios[0]['category']['label'] }}
                </span>
            </div>
        </div>

        {{-- Dots verticales (desktop) --}}
        <ul class="mt-services-dots" aria-hidden="true">
            @foreach($servicios as $i => $s)
                <li class="mt-services-dot {{ $i === 0 ? 'is-active' : '' }}"
                    data-services-dot
                    data-index="{{ $i }}"
                    style="--dot-tint: {{ $s['category']['tint'] }};">
                    <span class="mt-services-dot-label">{{ $s['num'] }}</span>
                </li>
            @endforeach
        </ul>

        {{-- Los 6 slides apilados absolute, se cross-fadeann con scrub --}}
        @foreach($servicios as $i => $s)
            <article class="mt-services-slide {{ $i === 0 ? 'is-active' : '' }}"
                     data-services-slide
                     data-index="{{ $i }}"
                     style="--slide-tint: {{ $s['category']['tint'] }};"
                     aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">

                {{-- Background: imagen + overlay para legibilidad --}}
                <div class="mt-services-slide-bg" aria-hidden="true">
                    @if($s['image'])
                        <img src="{{ asset($s['image']) }}"
                             alt=""
                             loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                             decoding="async">
                    @else
                        {{-- Sin imagen: fondo gradiente con tinte de categoría --}}
                        <div class="mt-services-slide-bg-gradient"></div>
                    @endif
                </div>
                <div class="mt-services-slide-overlay" aria-hidden="true"></div>

                {{-- Copy editorial --}}
                <div class="mt-services-slide-copy">
                    <div class="mt-container">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end min-h-[88vh] py-32">

                            <div class="lg:col-span-7">
                                {{-- Eyebrow: número + categoría --}}
                                <div class="flex items-center gap-4 mb-8">
                                    <span class="font-mono text-[11px] uppercase tracking-[0.28em] text-white/70">
                                        {{ $s['num'] }} / {{ str_pad($totalSlides, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full border border-white/20 backdrop-blur-sm font-mono text-[10px] uppercase tracking-[0.18em] text-white"
                                          style="background: color-mix(in srgb, var(--slide-tint) 18%, transparent);">
                                        <span class="w-1 h-1 rounded-full" style="background: var(--slide-tint);"></span>
                                        {{ $s['category']['label'] }}
                                    </span>
                                </div>

                                {{-- Título masivo editorial --}}
                                <h2 class="font-display font-bold text-white leading-[0.95] tracking-tight text-balance
                                           text-[clamp(2.5rem,6.5vw,5.5rem)]">
                                    {{ $s['title'] }}
                                </h2>

                                @if($s['lead'])
                                    <p class="mt-6 max-w-2xl text-xl md:text-2xl text-white/85 leading-snug font-light">
                                        {{ $s['lead'] }}
                                    </p>
                                @endif

                                @if($s['desc'])
                                    <p class="mt-5 max-w-xl text-[15px] md:text-base text-white/65 leading-relaxed">
                                        {{ $s['desc'] }}
                                    </p>
                                @endif
                            </div>

                            <div class="lg:col-span-5 lg:pl-8">
                                @if(count($s['features']))
                                    <ul class="space-y-3">
                                        @foreach($s['features'] as $f)
                                            <li class="flex items-start gap-3 text-white/85 text-[15px]">
                                                <span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 mt-0.5 rounded-full border"
                                                      style="border-color: var(--slide-tint); color: var(--slide-tint);">
                                                    <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </span>
                                                <span>{{ $f }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if(count($s['tags']))
                                    <div class="mt-8 flex flex-wrap gap-2">
                                        @foreach($s['tags'] as $t)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md border border-white/15 bg-white/5 text-white/80 text-[11px] font-mono">
                                                {{ $t }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($s['precio'])
                                    <div class="mt-8 pt-6 border-t border-white/10">
                                        <div class="font-mono text-[11px] uppercase tracking-[0.22em] text-white/55 mb-1">
                                            Inversión
                                        </div>
                                        <div class="font-display text-2xl md:text-3xl text-white font-semibold">
                                            {{ $s['precio'] }}
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('contacto.index') }}"
                                   class="mt-8 inline-flex items-center gap-2 px-6 py-3 rounded-full border-2 text-white text-sm font-medium hover:bg-white hover:text-mt-bg-dark transition-colors"
                                   style="border-color: var(--slide-tint);">
                                    Cotizar este servicio
                                    <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>
