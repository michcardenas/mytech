@php
    use App\Support\ProjectCardHelper as PCH;

    $showcase         = isset($proyectos) ? $proyectos->take(9) : collect();
    $totalDisponibles = isset($proyectos) ? $proyectos->count() : 0;

    // Contenido editable desde /pages/1/edit (page slug='inicio')
    $homeContent = [];
    if (isset($page) && $page && $page->content) {
        $homeContent = json_decode($page->content, true) ?? [];
    }

    /**
     * Mapeo categoría → tinte de color (puro estilo, NO data).
     * El label visible viene de $proyecto->badge_text (BD).
     */
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

    /**
     * Mapeo estado → label y color (estados son enum fijo del modelo).
     */
    $estadoMap = [
        'en_vivo'        => ['label' => 'En vivo',        'color' => '#10B981', 'pulse' => true],
        'en_desarrollo'  => ['label' => 'En desarrollo',  'color' => '#F59E0B', 'pulse' => true],
        'pausado'        => ['label' => 'Pausado',        'color' => '#9CA3AF', 'pulse' => false],
    ];
@endphp

<section class="py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border relative overflow-hidden">

    <div class="mt-container relative">

        {{-- Header --}}
        @php
            $hc                = $homeContent ?? [];
            $clientsTitle      = $hc['clients_title']       ?? null;
            $clientsSubtitle   = $hc['clients_subtitle']    ?? null;
            $clientsButtonText = $hc['clients_button_text'] ?? null;
        @endphp
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-16" data-animate>
            <div class="max-w-2xl">
                <span class="mt-eyebrow-gray">{{ $homeContent['casos_eyebrow'] ?? 'Casos en producción' }}</span>
                <h2 class="mt-4 text-section font-display text-mt-text text-balance max-w-[14ch]">
                    @if($clientsTitle)
                        {{ $clientsTitle }}
                    @else
                        Plataformas que ya están <span class="text-mt-accent">funcionando</span>.
                    @endif
                </h2>
                <p class="mt-5 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    {{ $clientsSubtitle ?? $totalDisponibles . ' proyectos construidos a medida para empresas reales. E-commerces, CRMs, automatizaciones con WhatsApp, marketplaces y SaaS.' }}
                </p>
            </div>
            <a href="{{ route('proyectos.index') }}" class="mt-btn-ghost self-start md:self-end">
                @if($clientsButtonText)
                    {{ $clientsButtonText }}
                @elseif($totalDisponibles > 9)
                    Ver los {{ $totalDisponibles }} proyectos
                @else
                    Ver todos los proyectos
                @endif
                <span aria-hidden="true">→</span>
            </a>
        </div>

        @if($showcase->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-casos-grid>
                @foreach($showcase as $p)
                    @php
                        $tint     = $catTints[$p->categoria] ?? '#2563EB';
                        $estado   = $estadoMap[$p->estado] ?? $estadoMap['en_vivo'];
                        // El click siempre va al detalle interno del proyecto.
                        // El CTA "Visitar sitio" externo vive dentro del detalle.
                        $href     = route('proyectos.show', $p->slug);
                        $domain   = PCH::domain($p->url);
                        $techs    = is_array($p->tecnologias) ? array_slice($p->tecnologias, 0, 4) : [];
                        $hasLogo  = !empty($p->logo);
                        $isPublic = !empty($p->url);  // sigue usándose para mostrar la URL bar
                    @endphp

                    <a href="{{ $href }}"
                       class="mt-browser-card group relative block rounded-2xl border border-mt-border bg-white overflow-hidden"
                       style="--card-tint: {{ $tint }}; --estado-color: {{ $estado['color'] }};">

                        {{-- ====== HEADER tipo ventana de navegador ====== --}}
                        <div class="flex items-center gap-3 px-4 py-3 bg-mt-bg-3 border-b border-mt-border">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#FF5F57]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[#FEBC2E]"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-[#28C840]"></span>
                            </div>
                            @if($isPublic)
                                <div class="flex-1 flex items-center gap-2 px-3 py-1 rounded-md bg-white border border-mt-border min-w-0">
                                    {{-- Favicon: logo de BD si existe, candado por defecto --}}
                                    @if($hasLogo)
                                        <span class="flex-shrink-0 w-4 h-4 rounded-[3px] bg-white border border-mt-border overflow-hidden flex items-center justify-center">
                                            <img src="{{ PCH::logoUrl($p->logo) }}"
                                                 alt=""
                                                 class="w-full h-full object-contain"
                                                 loading="lazy">
                                        </span>
                                    @else
                                        <svg class="w-3 h-3 text-mt-text-3 flex-shrink-0" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3zm0 0v4a3 3 0 003 3h0a3 3 0 003-3V8a6 6 0 10-12 0"/>
                                        </svg>
                                    @endif
                                    <span class="text-[11px] font-mono text-mt-text-2 truncate">{{ $domain }}</span>
                                </div>
                            @else
                                <div class="flex-1 flex items-center gap-2 px-3 py-1 rounded-md bg-mt-bg-3 border border-mt-border min-w-0">
                                    <svg class="w-3 h-3 text-mt-text-3 flex-shrink-0" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <span class="text-[11px] font-mono uppercase tracking-wider text-mt-text-3">Privado · NDA</span>
                                </div>
                            @endif
                        </div>

                        {{-- ====== CUERPO ====== --}}
                        <div class="p-7 relative">

                            {{-- Strip top que se activa en hover --}}
                            <span class="absolute top-0 left-0 right-0 h-[2px] origin-left transition-transform duration-500 ease-out scale-x-0 group-hover:scale-x-100 bg-[color:var(--card-tint)]"></span>

                            {{-- País + categoría --}}
                            <div class="flex items-center gap-2 mb-4 text-[11px] font-mono uppercase tracking-wider">
                                <span class="text-sm leading-none">{{ $p->bandera_emoji ?: '🌎' }}</span>
                                <span class="text-mt-text-3">{{ $p->pais }}</span>
                                <span class="text-mt-border-2">·</span>
                                <span class="font-semibold text-[color:var(--card-tint)]">{{ $p->badge_text }}</span>
                            </div>

                            {{-- Nombre + logo elegante al lado (si existe) --}}
                            <div class="mb-4 flex items-center gap-3">
                                @if($hasLogo)
                                    <span class="flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-xl bg-white border border-mt-border overflow-hidden shadow-sm transition-all duration-500 group-hover:border-[color-mix(in_srgb,var(--card-tint)_35%,transparent)] group-hover:shadow-md">
                                        <img src="{{ PCH::logoUrl($p->logo) }}"
                                             alt="{{ $p->nombre }} logo"
                                             class="w-full h-full object-contain p-1.5"
                                             loading="lazy">
                                    </span>
                                @endif
                                <h3 class="text-[22px] md:text-2xl font-display font-bold text-mt-text leading-tight">
                                    {{ $p->nombre }}
                                </h3>
                            </div>

                            {{-- Descripción --}}
                            <p class="text-[14px] text-mt-text-2 leading-relaxed min-h-[4rem]">
                                {{ PCH::shortDesc($p->descripcion, 130) }}
                            </p>

                            {{-- Stack tecnologías --}}
                            @if(count($techs) > 0)
                                <div class="flex flex-wrap gap-1.5 mt-5">
                                    @foreach($techs as $tech)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-mt-bg-3 text-mt-text-2 text-[11px] font-mono">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Footer: estado + CTA --}}
                            <div class="mt-6 pt-5 border-t border-mt-border flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-mono uppercase tracking-wider text-[color:var(--estado-color)]">
                                    <span class="relative flex w-1.5 h-1.5">
                                        @if($estado['pulse'])
                                            <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-[color:var(--estado-color)]"></span>
                                        @endif
                                        <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-[color:var(--estado-color)]"></span>
                                    </span>
                                    {{ $estado['label'] }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-mono uppercase tracking-wider transition-all duration-300 group-hover:gap-2.5 text-[color:var(--card-tint)]">
                                    Ver proyecto
                                    <span aria-hidden="true">→</span>
                                </span>
                            </div>

                        </div>
                    </a>
                @endforeach
            </div>

            {{-- CTA bottom --}}
            <div class="mt-14 flex flex-col sm:flex-row items-center justify-center gap-4 text-center" data-animate>
                <p class="text-mt-text-2 text-sm">
                    Hay <span class="font-semibold text-mt-text">{{ $totalDisponibles }} proyectos</span> en nuestro portafolio
                </p>
                <a href="{{ route('proyectos.index') }}" class="inline-flex items-center gap-2 text-mt-accent hover:gap-3 transition-all text-sm font-mono uppercase tracking-wider">
                    Ver el portafolio completo
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        @else
            <div class="text-center text-mt-text-2 py-12">
                <p>{{ $homeContent['casos_empty_message'] ?? 'Pronto: nuestro portafolio completo aquí.' }}</p>
            </div>
        @endif

    </div>
</section>
