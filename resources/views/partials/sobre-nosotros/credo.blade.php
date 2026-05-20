{{-- ════════════════ CAPÍTULO 03 — EL CREDO (horizontal pinned) ════════════════ --}}
{{-- Pin horizontal scroll. 7 declaraciones, cada una fullscreen.
     Mobile: stack vertical normal sin pin. --}}

@php
    $credo = [
        [
            'idx' => '01',
            'text' => $data['credo_1'] ?? 'Cada feature tiene que ganar su lugar.',
            'note' => $data['credo_1_note'] ?? 'Lo que no se usa, se vuelve deuda. Lo borramos antes de enviarlo.',
        ],
        [
            'idx' => '02',
            'text' => $data['credo_2'] ?? 'Lo aburrido y estable le gana a lo brillante y frágil.',
            'note' => $data['credo_2_note'] ?? 'Stack maduro, decisiones reversibles, código que cualquier dev entiende.',
        ],
        [
            'idx' => '03',
            'text' => $data['credo_3'] ?? 'La performance es un feature, no una optimización.',
            'note' => $data['credo_3_note'] ?? 'Si carga lento, no existe. Medimos en milisegundos, no en buenas intenciones.',
        ],
        [
            'idx' => '04',
            'text' => $data['credo_4'] ?? 'No vendemos diseño. Vendemos decisiones de producto.',
            'note' => $data['credo_4_note'] ?? 'Lo bonito no salva un negocio. La claridad, sí.',
        ],
        [
            'idx' => '05',
            'text' => $data['credo_5'] ?? 'Si no podemos explicar el costo, no podemos cobrarlo.',
            'note' => $data['credo_5_note'] ?? 'Alcance, tiempos y precio antes de empezar. Sin sorpresas en factura.',
        ],
        [
            'idx' => '06',
            'text' => $data['credo_6'] ?? 'El cliente que entiende su software lo defiende.',
            'note' => $data['credo_6_note'] ?? 'Documentamos para que duermas tranquilo cuando nosotros no estemos.',
        ],
        [
            'idx' => '07',
            'text' => $data['credo_7'] ?? 'LATAM no es un descuento. Es un estándar.',
            'note' => $data['credo_7_note'] ?? 'Mismo nivel técnico que un equipo de SF. Sin el costo absurdo.',
        ],
    ];
@endphp

<section class="mt-sn-credo" data-sn-credo>
    <header class="mt-sn-credo-head">
        <div class="mt-container">
            <div class="mt-sn-cap-head">
                <span class="mt-sn-cap-mono">CAP. 03</span>
                <span class="mt-sn-cap-sep" aria-hidden="true">·</span>
                <span class="mt-sn-cap-name">{{ $data['cap3_label'] ?? 'El credo' }}</span>
            </div>
            <h2 class="mt-sn-credo-headline">
                {{ $data['credo_headline'] ?? 'Siete cosas que no negociamos.' }}
                <span class="mt-sn-credo-headline-arrow" aria-hidden="true">→</span>
            </h2>
            <p class="mt-sn-credo-hint mono">
                Desliza para leerlas.
            </p>
        </div>
    </header>

    <div class="mt-sn-credo-track" data-sn-credo-track>
        <div class="mt-sn-credo-row" data-sn-credo-row>
            @foreach($credo as $c)
                <article class="mt-sn-credo-slide" data-sn-credo-slide>
                    <span class="mt-sn-credo-idx">{{ $c['idx'] }}</span>
                    <h3 class="mt-sn-credo-text text-balance">{{ $c['text'] }}</h3>
                    <p class="mt-sn-credo-note">{{ $c['note'] }}</p>
                </article>
            @endforeach
        </div>
    </div>

    <div class="mt-sn-credo-progress" data-sn-credo-progress aria-hidden="true">
        <div class="mt-sn-credo-progress-fill" data-sn-credo-progress-fill></div>
    </div>
</section>
