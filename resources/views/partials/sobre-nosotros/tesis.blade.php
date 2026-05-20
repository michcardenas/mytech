{{-- ════════════════ CAPÍTULO 01 — TESIS ════════════════ --}}
{{-- Pin scrub fullscreen: una frase gigantesca se revela palabra por
     palabra mientras scrolleas. Las "palabras clave" se iluminan.
     Versión mobile: render simple, sin pin. --}}

@php
    $tesis = $data['tesis_text'] ?? 'Creemos que el software a medida no debería ser un privilegio de Silicon Valley. Por eso construimos en LATAM, para LATAM, con el rigor de cualquier estudio top del mundo.';
    // Palabras a destacar (split por coma)
    $accent = explode(',', $data['tesis_accent_words'] ?? 'medida,LATAM,rigor,mundo');
    $accent = array_map(fn($w) => strtolower(trim($w)), $accent);

    $words = explode(' ', $tesis);
@endphp

<section id="tesis" class="mt-sn-tesis" data-sn-tesis>
    <div class="mt-sn-tesis-track" data-sn-tesis-track>
        <div class="mt-sn-tesis-stage">

            <header class="mt-sn-cap-head mt-sn-cap-head-light">
                <span class="mt-sn-cap-mono">CAP. 01</span>
                <span class="mt-sn-cap-sep" aria-hidden="true">·</span>
                <span class="mt-sn-cap-name">{{ $data['cap1_label'] ?? 'La tesis' }}</span>
            </header>

            <h2 class="mt-sn-tesis-text" data-sn-tesis-text>
                @foreach($words as $i => $word)
                    @php
                        $clean = strtolower(preg_replace('/[^\p{L}\p{N}]/u', '', $word));
                        $isAccent = in_array($clean, $accent, true);
                    @endphp
                    <span class="mt-sn-tesis-word {{ $isAccent ? 'is-accent' : '' }}"
                          data-sn-tesis-word>{{ $word }}</span>{!! $i < count($words) - 1 ? ' ' : '' !!}
                @endforeach
            </h2>

            <footer class="mt-sn-tesis-foot" data-sn-tesis-foot>
                <span class="mt-sn-tesis-sig">— El equipo, {{ $data['founding_year'] ?? '2022' }}</span>
            </footer>
        </div>
    </div>
</section>
