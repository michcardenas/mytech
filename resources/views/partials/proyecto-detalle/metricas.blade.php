@php
    $metricas = collect([
        ['label' => 'Visitas mensuales',  'value' => $proyecto->visitas_mensuales ? number_format($proyecto->visitas_mensuales) : null,           'sub' => 'Tráfico orgánico promedio'],
        ['label' => 'Duración del proyecto', 'value' => $proyecto->duracion_desarrollo,                                                            'sub' => 'De kickoff a producción'],
        ['label' => 'Tamaño del equipo',    'value' => $proyecto->equipo_size ? $proyecto->equipo_size.' '.($proyecto->equipo_size === 1 ? 'dev' : 'devs') : null, 'sub' => 'Especialistas dedicados'],
        ['label' => 'Año de lanzamiento',   'value' => $proyecto->fecha_lanzamiento?->format('Y'),                                                  'sub' => 'En producción desde'],
    ])->filter(fn($m) => ! empty($m['value']))->values();
@endphp

@if($metricas->count() > 0)
<section class="mt-pd-metricas py-24 md:py-32 bg-mt-bg-dark text-white border-t border-white/5">
    <div class="mt-container">

        <div class="max-w-2xl mb-16" data-animate>
            <span class="font-mono text-[11px] uppercase tracking-[0.22em] text-white/55">Métricas</span>
            <h2 class="mt-3 text-section font-display font-bold text-white leading-tight text-balance">
                Datos reales del
                <span class="text-mt-accent-on-dark italic">proyecto</span>.
            </h2>
        </div>

        <div class="mt-pd-metricas-grid" data-animate-children>
            @foreach($metricas as $m)
                <div class="mt-pd-metrica">
                    <div class="mt-pd-metrica-label">
                        <span class="mt-pd-metrica-dot"></span>
                        {{ $m['label'] }}
                    </div>
                    <div class="mt-pd-metrica-value">{{ $m['value'] }}</div>
                    <div class="mt-pd-metrica-sub">{{ $m['sub'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
