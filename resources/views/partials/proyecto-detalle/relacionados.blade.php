@php
    use App\Support\ProjectCardHelper as PCH;

    $items = $proyectosRelacionados ?? collect();

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
@endphp

@if($items->count() > 0)
<section class="mt-pd-relacionados py-24 md:py-32 bg-white border-t border-mt-border">
    <div class="mt-container">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-14" data-animate>
            <div class="max-w-2xl">
                <span class="mt-eyebrow-gray">Más casos como este</span>
                <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                    Proyectos
                    <span class="text-mt-accent italic">relacionados</span>.
                </h2>
                <p class="mt-4 text-mt-text-2 text-base md:text-lg leading-relaxed">
                    Otros proyectos en la categoría {{ ucfirst($proyecto->categoria) }} construidos por el mismo equipo.
                </p>
            </div>
            <a href="{{ route('proyectos.index') }}" class="mt-btn-ghost self-start md:self-end">
                Ver todos los proyectos
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-animate-children>
            @foreach($items as $r)
                @php
                    $rTint   = $catTints[$r->categoria] ?? '#2563EB';
                    $rEst    = $estadoMap[$r->estado] ?? $estadoMap['en_vivo'];
                    $rTechs  = is_array($r->tecnologias) ? array_slice($r->tecnologias, 0, 3) : [];
                @endphp
                <a href="{{ route('proyectos.show', $r->slug) }}"
                   class="mt-pd-rel-card"
                   style="--card-tint: {{ $rTint }}; --estado-color: {{ $rEst['color'] }};">

                    @if($r->logo)
                        <div class="mt-pd-rel-card-bg" aria-hidden="true">
                            <img src="{{ PCH::logoUrl($r->logo) }}" alt="" loading="lazy">
                        </div>
                    @endif

                    <div class="mt-pd-rel-card-content">
                        <div class="mt-pd-rel-card-top">
                            <span>{{ $r->bandera_emoji }}</span>
                            <span class="text-mt-text-3">{{ $r->pais }}</span>
                            <span class="flex-1"></span>
                            <span class="mt-pd-rel-card-estado">
                                <span class="relative inline-flex w-1.5 h-1.5">
                                    @if($rEst['pulse'])
                                        <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-[color:var(--estado-color)]"></span>
                                    @endif
                                    <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-[color:var(--estado-color)]"></span>
                                </span>
                                {{ $rEst['label'] }}
                            </span>
                        </div>

                        <h3 class="mt-pd-rel-card-name">{{ $r->nombre }}</h3>
                        <p class="mt-pd-rel-card-desc">{{ PCH::shortDesc($r->descripcion, 120) }}</p>

                        <div class="mt-pd-rel-card-bottom">
                            @if(count($rTechs) > 0)
                                <div class="mt-pd-rel-card-techs">
                                    @foreach($rTechs as $t)
                                        <span>{{ $t }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <span class="mt-pd-rel-card-cta">
                                Ver proyecto
                                <span aria-hidden="true">→</span>
                            </span>
                        </div>
                    </div>
                    <span class="mt-pd-rel-card-strip" aria-hidden="true"></span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
