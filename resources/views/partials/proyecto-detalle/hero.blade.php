@php
    use App\Support\ProjectCardHelper as PCH;

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
    $tint = $catTints[$proyecto->categoria] ?? '#2563EB';

    $estadoMap = [
        'en_vivo'        => ['label' => 'En vivo',       'color' => '#10B981'],
        'en_desarrollo'  => ['label' => 'En desarrollo', 'color' => '#F59E0B'],
        'pausado'        => ['label' => 'Pausado',       'color' => '#9CA3AF'],
    ];
    $estado = $estadoMap[$proyecto->estado] ?? $estadoMap['en_vivo'];

    $yearLaunch = $proyecto->fecha_lanzamiento?->format('Y');
@endphp

<section class="mt-pd-hero relative pt-36 pb-20 md:pb-28 overflow-hidden bg-white"
         data-pd-hero
         style="--pd-tint: {{ $tint }};">

    {{-- Watermark del logo gigante como decoración --}}
    @if($proyecto->logo)
        <div class="mt-pd-hero-watermark" aria-hidden="true" data-pd-watermark>
            <img src="{{ PCH::logoUrl($proyecto->logo) }}" alt="">
        </div>
    @endif

    <div class="mt-container relative z-10">

        {{-- Breadcrumb editorial --}}
        <nav class="mt-pd-breadcrumb" aria-label="Breadcrumb" data-animate>
            <a href="{{ url('/') }}">Inicio</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('proyectos.index') }}">Proyectos</a>
            <span aria-hidden="true">/</span>
            <span class="mt-pd-breadcrumb-current">{{ $proyecto->breadcrumb_title ?: $proyecto->nombre }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 mt-10 lg:mt-14">

            {{-- Columna principal --}}
            <div class="lg:col-span-8">

                {{-- Eyebrow editorial: estado + categoría + país --}}
                <div class="flex flex-wrap items-center gap-3 mb-8" data-animate>
                    <span class="mt-pd-pill" style="background: color-mix(in srgb, var(--pd-tint) 12%, white); color: var(--pd-tint); border-color: color-mix(in srgb, var(--pd-tint) 28%, transparent);">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $estado['color'] }};"></span>
                        {{ $estado['label'] }}
                    </span>
                    <span class="mt-pd-pill-ghost">
                        {{ $proyecto->bandera_emoji }} {{ $proyecto->pais }}
                    </span>
                    <span class="mt-pd-pill-ghost">
                        {{ ucfirst($proyecto->categoria) }}
                    </span>
                    @if($proyecto->badge_text)
                        <span class="mt-pd-pill-ghost">{{ $proyecto->badge_text }}</span>
                    @endif
                </div>

                {{-- Título masivo --}}
                <h1 class="mt-pd-title font-display font-bold text-mt-text leading-[0.95] tracking-tight text-balance
                           text-[clamp(2.5rem,6.5vw,5.5rem)]"
                    data-pd-title>
                    {{ $proyecto->nombre }}
                </h1>

                {{-- Descripción lead --}}
                <p class="mt-8 max-w-2xl text-lg md:text-xl text-mt-text-2 leading-snug font-light" data-animate>
                    {{ $proyecto->excerpt ?: $proyecto->descripcion }}
                </p>

                {{-- CTAs --}}
                <div class="mt-10 flex flex-wrap items-center gap-3.5" data-animate>
                    @if($proyecto->url)
                        <a href="{{ $proyecto->url }}" target="_blank" rel="noopener"
                           class="mt-btn-primary">
                            Visitar sitio
                            <span aria-hidden="true">↗</span>
                        </a>
                    @endif
                    <a href="{{ route('contacto.index') }}?ref={{ urlencode($proyecto->slug) }}"
                       class="{{ $proyecto->url ? 'mt-btn-ghost' : 'mt-btn-primary' }}">
                        Quiero un proyecto similar
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            {{-- Columna lateral: ficha rápida del proyecto --}}
            <aside class="lg:col-span-4 lg:pl-4" data-animate>
                <div class="mt-pd-fact-card">
                    <h2 class="mt-pd-fact-card-title">
                        <span class="mt-pd-fact-line"></span>
                        Ficha del proyecto
                    </h2>

                    <dl class="mt-pd-fact-list">
                        @if($yearLaunch)
                            <div>
                                <dt>Lanzamiento</dt>
                                <dd>{{ $yearLaunch }}</dd>
                            </div>
                        @endif
                        @if($proyecto->duracion_desarrollo)
                            <div>
                                <dt>Duración</dt>
                                <dd>{{ $proyecto->duracion_desarrollo }}</dd>
                            </div>
                        @endif
                        @if($proyecto->equipo_size)
                            <div>
                                <dt>Equipo</dt>
                                <dd>{{ $proyecto->equipo_size }} {{ $proyecto->equipo_size === 1 ? 'dev' : 'devs' }}</dd>
                            </div>
                        @endif
                        @if($proyecto->visitas_mensuales)
                            <div>
                                <dt>Visitas / mes</dt>
                                <dd>{{ number_format($proyecto->visitas_mensuales) }}</dd>
                            </div>
                        @endif
                        @if($proyecto->industria)
                            <div>
                                <dt>Industria</dt>
                                <dd>{{ $proyecto->industria }}</dd>
                            </div>
                        @endif
                        @if($proyecto->client_size)
                            <div>
                                <dt>Cliente</dt>
                                <dd>{{ ucfirst($proyecto->client_size) }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if($proyecto->logo)
                        <div class="mt-pd-fact-card-logo">
                            <img src="{{ PCH::logoUrl($proyecto->logo) }}"
                                 alt="{{ $proyecto->alt_logo ?: 'Logo de '.$proyecto->nombre }}"
                                 loading="eager">
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>
