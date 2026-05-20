@php
    use App\Support\ProjectCardHelper as PCH;

    $pc = [];
    if (isset($page) && $page && $page->content) {
        $pc = json_decode($page->content, true) ?? [];
    }
    $eyebrow  = $pc['proy_featured_eyebrow']  ?? 'Destacados';
    $title    = $pc['proy_featured_title']    ?? 'Casos en profundidad.';
    $subtitle = $pc['proy_featured_subtitle'] ?? 'Tres proyectos donde el reto técnico hizo la diferencia.';

    $items = ($destacados ?? collect())->take(3);
    if ($items->count() === 0 && isset($proyectos)) {
        $items = $proyectos->take(3);
    }

    $catTints = [
        'ecommerce'  => '#10B981',
        'automation' => '#8B5CF6',
        'travel'     => '#F59E0B',
        'admin'      => '#2563EB',
        'booking'    => '#EC4899',
        'restaurant' => '#EF4444',
        'legal'      => '#0F766E',
        'tech'       => '#2563EB',
    ];
@endphp

@if($items->count() > 0)
<section class="mt-proy-feat py-24 md:py-32 bg-mt-bg-2 border-t border-mt-border overflow-hidden">
    <div class="mt-container">

        {{-- Header --}}
        <div class="max-w-3xl mb-16 md:mb-24" data-animate>
            <span class="mt-eyebrow-gray">{{ $eyebrow }}</span>
            <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                {{ $title }}
            </h2>
            <p class="mt-5 text-mt-text-2 text-base md:text-lg leading-relaxed">
                {{ $subtitle }}
            </p>
        </div>

        {{-- Alternating split panels — diferente al pin scrub de /servicios --}}
        <div class="mt-proy-feat-list">
            @foreach($items as $i => $p)
                @php
                    $tint = $catTints[$p->categoria] ?? '#2563EB';
                    $isOdd = $i % 2 === 1;  // alterna direccional
                    $tags = is_array($p->tecnologias) ? array_slice($p->tecnologias, 0, 5) : [];
                @endphp

                <article class="mt-proy-feat-row {{ $isOdd ? 'is-reverse' : '' }}"
                         data-proyectos-feat-row
                         data-index="{{ $i }}"
                         style="--feat-tint: {{ $tint }};">

                    {{-- Lado MEDIA --}}
                    <div class="mt-proy-feat-media" data-proyectos-feat-media>
                        @if($p->logo)
                            <div class="mt-proy-feat-media-inner">
                                <img src="{{ PCH::logoUrl($p->logo) }}"
                                     alt="{{ $p->nombre }}"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                        @else
                            <div class="mt-proy-feat-media-inner mt-proy-feat-media-empty">
                                <span class="font-display text-5xl md:text-7xl font-bold opacity-25" aria-hidden="true">
                                    {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        @endif

                        {{-- Decorative pill superior --}}
                        <div class="mt-proy-feat-media-tag">
                            <span class="mt-proy-feat-media-tag-num">
                                {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($items->count(), 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="mt-proy-feat-media-tag-cat">
                                {{ $p->bandera_emoji ?: '🌎' }} {{ $p->pais }}
                            </span>
                        </div>
                    </div>

                    {{-- Lado COPY --}}
                    <div class="mt-proy-feat-copy" data-proyectos-feat-copy>

                        <div class="mt-proy-feat-eyebrow">
                            <span class="mt-proy-feat-eyebrow-dot" style="background: var(--feat-tint);" aria-hidden="true"></span>
                            <span>{{ $p->badge_text }}</span>
                        </div>

                        <h3 class="mt-proy-feat-name">{{ $p->nombre }}</h3>

                        <p class="mt-proy-feat-desc">
                            {{ PCH::shortDesc($p->descripcion, 260) }}
                        </p>

                        {{-- Stats inline editorial --}}
                        @php
                            $stats = collect([
                                ['label' => 'Visitas/mes',  'value' => $p->visitas_mensuales ? number_format($p->visitas_mensuales) : null],
                                ['label' => 'Duración',     'value' => $p->duracion_desarrollo],
                                ['label' => 'Equipo',       'value' => $p->equipo_size ? $p->equipo_size.' '.($p->equipo_size===1?'dev':'devs') : null],
                                ['label' => 'Año',          'value' => $p->fecha_lanzamiento ? \Carbon\Carbon::parse($p->fecha_lanzamiento)->format('Y') : null],
                            ])->filter(fn($s) => $s['value'])->values();
                        @endphp
                        @if($stats->count() > 0)
                            <ul class="mt-proy-feat-stats">
                                @foreach($stats as $s)
                                    <li>
                                        <span class="mt-proy-feat-stat-label">{{ $s['label'] }}</span>
                                        <span class="mt-proy-feat-stat-value">{{ $s['value'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if(count($tags) > 0)
                            <div class="mt-proy-feat-tags">
                                @foreach($tags as $t)
                                    <span>{{ $t }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-proy-feat-ctas">
                            <a href="{{ route('proyectos.show', $p->slug) }}"
                               class="mt-proy-feat-cta-primary">
                                Ver proyecto completo
                                <span aria-hidden="true">→</span>
                            </a>
                            @if($p->url)
                                <a href="{{ $p->url }}" target="_blank" rel="noopener"
                                   class="mt-proy-feat-cta-secondary">
                                    Visitar
                                    <span aria-hidden="true">↗</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
