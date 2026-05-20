@php
    use App\Support\ProjectCardHelper as PCH;

    $pc = [];
    if (isset($page) && $page && $page->content) {
        $pc = json_decode($page->content, true) ?? [];
    }
    $eyebrow    = $pc['proy_grid_eyebrow']    ?? 'Todo el portafolio';
    $title      = $pc['proy_grid_title']      ?? 'Explora cada proyecto.';
    $subtitle   = $pc['proy_grid_subtitle']   ?? 'Filtra por categoría para ver lo que más se acerca a tu necesidad.';
    $filterAll  = $pc['proy_grid_filter_all'] ?? 'Todos';

    // Tints por categoría
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

    $estadoMap = [
        'en_vivo'        => ['label' => 'En vivo',       'color' => '#10B981', 'pulse' => true],
        'en_desarrollo'  => ['label' => 'En desarrollo', 'color' => '#F59E0B', 'pulse' => true],
        'pausado'        => ['label' => 'Pausado',       'color' => '#9CA3AF', 'pulse' => false],
    ];

    $items = $proyectos ?? collect();
    $cats  = $categoriasConteo ?? collect();
@endphp

<section class="py-28 md:py-36 bg-white border-t border-mt-border relative" id="grid">
    <div class="mt-container">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14" data-animate>
            <div class="max-w-2xl">
                <span class="mt-eyebrow-gray">{{ $eyebrow }}</span>
                <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                    {{ $title }}
                </h2>
                <p class="mt-4 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    {{ $subtitle }}
                </p>
            </div>
            <div class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">
                {{ $items->count() }} {{ $items->count() === 1 ? 'proyecto' : 'proyectos' }}
            </div>
        </div>

        {{-- Filtros sticky por categoría --}}
        <div class="mt-proy-filters" data-proyectos-filters>
            <div class="mt-proy-filters-track">
                <button type="button"
                        class="mt-proy-filter is-active"
                        data-proyectos-filter="all"
                        data-count="{{ $items->count() }}">
                    {{ $filterAll }}
                    <span class="mt-proy-filter-count">{{ $items->count() }}</span>
                </button>
                @foreach($cats as $cat => $count)
                    <button type="button"
                            class="mt-proy-filter"
                            data-proyectos-filter="{{ $cat }}"
                            data-count="{{ $count }}"
                            style="--filter-tint: {{ $catTints[$cat] ?? '#2563EB' }};">
                        {{ ucfirst($cat) }}
                        <span class="mt-proy-filter-count">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Bento grid editorial — split visual: media panel + content panel --}}
        @if($items->count() > 0)
            <div class="mt-proy-bento" data-proyectos-bento>
                @foreach($items as $i => $p)
                    @php
                        $tint   = $catTints[$p->categoria] ?? '#2563EB';
                        $estado = $estadoMap[$p->estado] ?? $estadoMap['en_vivo'];
                        // Sizing: destacados o cada 7º card son "wide"
                        $size = $p->destacado ? 'lg' : (($i % 7 === 5) ? 'md' : 'sm');
                        // Click SIEMPRE va al detalle interno (no externo).
                        // El CTA de "Visitar sitio" externo vive dentro de la página de detalle.
                        $href = route('proyectos.show', $p->slug);
                        $techs = is_array($p->tecnologias) ? array_slice($p->tecnologias, 0, 4) : [];
                        // Imagen prominente: og_image > logo
                        $heroImg = $p->og_image ? asset('storage/' . $p->og_image) : ($p->logo ? PCH::logoUrl($p->logo) : null);
                        $heroIsLogo = ! $p->og_image && $p->logo;
                    @endphp

                    <a href="{{ $href }}"
                       class="mt-proy-card mt-proy-card-{{ $size }}"
                       data-proyectos-card
                       data-category="{{ $p->categoria }}"
                       style="--card-tint: {{ $tint }}; --estado-color: {{ $estado['color'] }};">

                        {{-- ==== PANEL MEDIA (visible, prominente) ==== --}}
                        <div class="mt-proy-card-media" aria-hidden="true">
                            @if($heroImg)
                                <div class="mt-proy-card-media-bg {{ $heroIsLogo ? 'is-logo' : 'is-image' }}">
                                    <img src="{{ $heroImg }}" alt="" loading="lazy" decoding="async">
                                </div>
                            @else
                                <div class="mt-proy-card-media-bg is-empty">
                                    <span class="font-display font-bold text-[5rem] leading-none">{{ strtoupper(substr($p->nombre, 0, 2)) }}</span>
                                </div>
                            @endif
                            {{-- Overlay sutil con tint --}}
                            <span class="mt-proy-card-media-overlay"></span>

                            {{-- Logo pill flotante arriba a la izquierda (solo si tiene og_image distinto al logo) --}}
                            @if($p->og_image && $p->logo)
                                <span class="mt-proy-card-logo-pill">
                                    <img src="{{ PCH::logoUrl($p->logo) }}" alt="" loading="lazy">
                                </span>
                            @endif

                            {{-- Badge de estado flotante arriba a la derecha --}}
                            <span class="mt-proy-card-estado-pill">
                                <span class="relative inline-flex w-1.5 h-1.5">
                                    @if($estado['pulse'])
                                        <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-[color:var(--estado-color)]"></span>
                                    @endif
                                    <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-[color:var(--estado-color)]"></span>
                                </span>
                                {{ $estado['label'] }}
                            </span>

                            {{-- País abajo a la izquierda --}}
                            <span class="mt-proy-card-country-pill">
                                <span>{{ $p->bandera_emoji ?: '🌎' }}</span>
                                <span>{{ $p->pais }}</span>
                            </span>
                        </div>

                        {{-- ==== PANEL CONTENT (denso) ==== --}}
                        <div class="mt-proy-card-content">
                            <div class="mt-proy-card-head">
                                @if($p->badge_text)
                                    <span class="mt-proy-card-badge">{{ $p->badge_text }}</span>
                                @endif
                                <h3 class="mt-proy-card-name">{{ $p->nombre }}</h3>
                            </div>

                            <p class="mt-proy-card-desc">{{ PCH::shortDesc($p->descripcion, $size === 'sm' ? 90 : 140) }}</p>

                            <div class="mt-proy-card-bottom">
                                @if(count($techs) > 0)
                                    <div class="mt-proy-card-techs">
                                        @foreach($techs as $t)
                                            <span>{{ $t }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <span class="mt-proy-card-cta">
                                    Ver proyecto
                                    <span aria-hidden="true">→</span>
                                </span>
                            </div>
                        </div>

                        {{-- Strip animada en hover --}}
                        <span class="mt-proy-card-strip" aria-hidden="true"></span>
                    </a>
                @endforeach
            </div>

            {{-- Estado vacío cuando filtro no encuentra --}}
            <div class="mt-proy-empty" data-proyectos-empty hidden>
                <p class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3 mb-3">Sin resultados</p>
                <p class="text-mt-text-2 text-lg">No tenemos proyectos en esa categoría todavía.</p>
                <button type="button" class="mt-6 mt-btn-ghost" data-proyectos-filter-reset>
                    Ver todos los proyectos
                    <span aria-hidden="true">→</span>
                </button>
            </div>
        @endif
    </div>
</section>
