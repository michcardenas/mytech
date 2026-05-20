@php
    $blocks = [];

    if (! empty($proyecto->descripcion_extendida)) {
        $blocks[] = [
            'num'   => '00',
            'kicker'=> 'Sobre el proyecto',
            'title' => 'Contexto',
            'html'  => $proyecto->descripcion_extendida,
            'tint'  => '#2563EB',
        ];
    }
    if (! empty($proyecto->desafio)) {
        $blocks[] = [
            'num'   => '01',
            'kicker'=> 'El reto',
            'title' => 'Desafío',
            'html'  => $proyecto->desafio,
            'tint'  => '#EF4444',
        ];
    }
    if (! empty($proyecto->solucion)) {
        $blocks[] = [
            'num'   => '02',
            'kicker'=> 'Cómo lo resolvimos',
            'title' => 'Solución',
            'html'  => $proyecto->solucion,
            'tint'  => '#2563EB',
        ];
    }
    if (! empty($proyecto->resultados)) {
        $blocks[] = [
            'num'   => '03',
            'kicker'=> 'Lo que cambió',
            'title' => 'Resultados',
            'html'  => $proyecto->resultados,
            'tint'  => '#10B981',
        ];
    }
@endphp

@if(count($blocks) > 0)
<section class="mt-pd-case py-24 md:py-32 bg-mt-bg-2 border-t border-mt-border overflow-hidden">
    <div class="mt-container">

        {{-- Header de la sección --}}
        <div class="max-w-3xl mb-20 md:mb-28" data-animate>
            <span class="mt-eyebrow-gray">Caso de estudio</span>
            <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                Cómo construimos
                <span class="text-mt-accent italic font-display">{{ $proyecto->nombre }}</span>.
            </h2>
        </div>

        {{-- Bloques alternating --}}
        <div class="mt-pd-case-list">
            @foreach($blocks as $i => $b)
                @php $isReverse = $i % 2 === 1; @endphp
                <article class="mt-pd-case-row {{ $isReverse ? 'is-reverse' : '' }}"
                         data-pd-case-row
                         style="--case-tint: {{ $b['tint'] }};">

                    {{-- Marca: número masivo + kicker --}}
                    <div class="mt-pd-case-mark" data-pd-case-mark>
                        <div class="mt-pd-case-num">{{ $b['num'] }}</div>
                        <div class="mt-pd-case-kicker">
                            <span class="mt-pd-case-kicker-line"></span>
                            <span>{{ $b['kicker'] }}</span>
                        </div>
                        <h3 class="mt-pd-case-section-title">{{ $b['title'] }}</h3>
                    </div>

                    {{-- Contenido HTML del Quill editor --}}
                    <div class="mt-pd-case-content prose-pd" data-pd-case-content>
                        {!! $b['html'] !!}
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
