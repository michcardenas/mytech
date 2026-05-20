@php
    $sd = $serviciosData ?? [];

    // Contenido editable del HEADER de servicios (desde /pages/1/edit)
    $homeContent = [];
    if (isset($page) && $page && $page->content) {
        $homeContent = json_decode($page->content, true) ?? [];
    }

    /**
     * Las 6 ranuras de servicio tienen significado estable (definido por el
     * cliente). El TEXTO se edita desde la página de servicios (page id=3).
     * Iconos + categorías + link son design-system, hardcoded.
     *
     * Categoría → label visible
     * - build:    construir desde cero
     * - grow:     crecer (visibilidad, automatización)
     * - maintain: mantener / extender lo que ya está
     */
    $servicios = [
        [
            'cat'   => 'build',
            'title' => $sd['servicio_1_title']       ?? 'Desarrollo Web y Software a Medida',
            'desc'  => $sd['servicio_1_description'] ?? 'Creamos soluciones web y software personalizados para empresas que necesitan vender más, automatizar procesos y contar con plataformas escalables.',
            'icon'  => 'code',
            'href'  => route('servicios.index') . '#desarrollo',
        ],
        [
            'cat'   => 'build',
            'title' => $sd['servicio_2_title']       ?? 'Aplicaciones Web y Plataformas SaaS',
            'desc'  => $sd['servicio_2_description'] ?? 'Convertimos ideas en aplicaciones web robustas y plataformas SaaS listas para crecer, con arquitectura moderna y enfoque en rendimiento.',
            'icon'  => 'layers',
            'href'  => route('servicios.index') . '#saas',
        ],
        [
            'cat'   => 'grow',
            'title' => $sd['servicio_3_title']       ?? 'Automatización e Integraciones Empresariales',
            'desc'  => $sd['servicio_3_description'] ?? 'Automatizamos procesos y conectamos sistemas para que tu empresa opere de forma más eficiente y sin errores manuales.',
            'icon'  => 'flow',
            'href'  => route('servicios.index') . '#automatizacion',
        ],
        [
            'cat'   => 'grow',
            'title' => $sd['servicio_4_title']       ?? 'Marketing Digital y Posicionamiento SEO',
            'desc'  => $sd['servicio_4_description'] ?? 'Optimizamos tu presencia digital para atraer clientes reales, no solo visitas, mediante SEO estratégico y páginas orientadas a conversión.',
            'icon'  => 'compass',
            'href'  => route('servicios.index') . '#seo',
        ],
        [
            'cat'   => 'maintain',
            'title' => $sd['servicio_5_title']       ?? 'Mantenimiento y Soporte de Plataformas Web',
            'desc'  => $sd['servicio_5_description'] ?? 'Brindamos soporte técnico, mantenimiento y mejoras continuas para sitios web y aplicaciones existentes, asegurando estabilidad, seguridad y rendimiento.',
            'icon'  => 'shield',
            'href'  => route('servicios.index') . '#soporte',
        ],
        [
            'cat'   => 'maintain',
            'title' => $sd['servicio_6_title']       ?? 'Bolsas de Horas para Desarrollo y Mejoras',
            'desc'  => $sd['servicio_6_description'] ?? 'Ofrecemos bolsas de horas flexibles para empresas que necesitan ajustes, nuevas funcionalidades o mejoras constantes en sus sistemas digitales.',
            'icon'  => 'clock',
            'href'  => route('servicios.index') . '#horas',
        ],
    ];

    $catLabels = [
        'build'    => 'Construye',
        'grow'     => 'Crece',
        'maintain' => 'Mantén',
    ];

    // Agrupar por categoría preservando orden de aparición de cada cat
    $grouped = [];
    foreach ($servicios as $s) {
        $grouped[$s['cat']][] = $s;
    }
@endphp

<section class="relative py-28 md:py-36 bg-white border-t border-mt-border" data-pin-servicios>
    <div class="mt-container">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-32" data-animate data-pin-servicios-sticky>
                    <span class="mt-eyebrow-gray">{{ $homeContent['servicios_eyebrow'] ?? 'Servicios' }}</span>
                    <h2 class="mt-4 text-section font-display text-mt-text">
                        {{ $homeContent['servicios_title'] ?? 'Lo que hacemos.' }}
                    </h2>
                    <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                        {{ $homeContent['servicios_subtitle'] ?? 'Diseñamos y desarrollamos soluciones digitales a medida para empresas que buscan vender más, automatizar procesos y escalar con tecnología confiable.' }}
                    </p>
                    <a href="{{ route('servicios.index') }}" class="inline-flex items-center gap-2 mt-8 text-mt-accent hover:gap-3 transition-all text-sm font-mono uppercase tracking-wider">
                        {{ $homeContent['servicios_link_text'] ?? 'Ver detalle de servicios' }}
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-8 flex flex-col gap-14">
                @foreach($grouped as $catKey => $items)
                    <div data-animate data-svc-category="{{ $catKey }}">
                        {{-- Encabezado de categoría (sticky en mobile, estático en desktop) --}}
                        <div class="mt-svc-cat-header flex items-baseline gap-4 mb-6">
                            <span class="font-mono text-xs uppercase tracking-[0.18em] text-mt-accent">
                                {{ $catLabels[$catKey] ?? $catKey }}
                            </span>
                            <span class="flex-1 h-px bg-mt-border"></span>
                            <span class="font-mono text-[11px] text-mt-text-3">
                                {{ str_pad(count($items), 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        {{-- Tarjetas de la categoría --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($items as $s)
                                <a href="{{ $s['href'] }}"
                                   data-pin-servicios-card
                                   class="group mt-svc-card relative block transition-colors duration-300">
                                    <div class="flex items-start gap-4 mb-4">
                                        <span class="flex-shrink-0 inline-flex items-center justify-center w-11 h-11 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                                            @switch($s['icon'])
                                                @case('code')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-4 3 4 3M16 9l4 3-4 3M14 5l-4 14"/>
                                                    </svg>
                                                    @break
                                                @case('layers')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4l9 5-9 5-9-5 9-5zM3 14l9 5 9-5"/>
                                                    </svg>
                                                    @break
                                                @case('flow')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                        <circle cx="6" cy="6" r="2.2"/><circle cx="6" cy="18" r="2.2"/><circle cx="18" cy="12" r="2.2"/>
                                                        <path stroke-linecap="round" d="M8 7.2c4 .4 6 1.8 7.6 4M8 16.8c4-.4 6-1.8 7.6-4"/>
                                                    </svg>
                                                    @break
                                                @case('compass')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l-2 5-5 2 2-5 5-2z"/>
                                                    </svg>
                                                    @break
                                                @case('shield')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 5-3.5 8.4-8 9-4.5-.6-8-4-8-9V6l8-3z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12l1.8 1.8L15 10"/>
                                                    </svg>
                                                    @break
                                                @case('clock')
                                                    <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                                    </svg>
                                                    @break
                                            @endswitch
                                        </span>
                                        <h3 class="text-lg md:text-xl font-display font-semibold text-mt-text leading-tight pt-1.5">
                                            {{ $s['title'] }}
                                        </h3>
                                    </div>

                                    <p class="text-mt-text-2 leading-relaxed text-[14.5px]">
                                        {{ $s['desc'] }}
                                    </p>

                                    <span class="inline-flex items-center gap-1.5 mt-5 text-mt-accent text-[12px] font-mono uppercase tracking-[0.14em] transition-all duration-300 group-hover:gap-2.5">
                                        Ver detalle
                                        <span aria-hidden="true">→</span>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
