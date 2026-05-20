{{-- ════════════════ CAPÍTULO 00 — PRÓLOGO ════════════════ --}}
{{-- Fullscreen dark, title con shutter-reveal vertical letra por letra,
     cursor glow que sigue al mouse, indicator de scroll. --}}

<section class="mt-sn-prologo" data-sn-prologo>
    <div class="mt-sn-prologo-grid" aria-hidden="true"></div>
    <div class="mt-sn-cursor-glow" aria-hidden="true" data-sn-cursor></div>

    <div class="mt-sn-prologo-inner">
        <header class="mt-sn-cap-head">
            <span class="mt-sn-cap-mono">CAP. 00</span>
            <span class="mt-sn-cap-sep" aria-hidden="true">·</span>
            <span class="mt-sn-cap-name">{{ $data['cap0_label'] ?? 'Prólogo' }}</span>
        </header>

        <h1 class="mt-sn-prologo-title" data-sn-shutter>
            {{ $data['prologo_title'] ?? 'No somos una agencia.' }}
        </h1>

        <p class="mt-sn-prologo-sub" data-sn-sub>
            {!! $data['prologo_sub'] ?? 'Somos el estudio que el software a medida en LATAM necesitaba — y que no encontrábamos. Así que lo construimos.' !!}
        </p>

        <div class="mt-sn-prologo-meta" data-sn-meta>
            <span class="mt-sn-meta-pill">
                <span class="mt-sn-meta-dot"></span>
                <span>EST. {{ $data['founding_year'] ?? '2022' }}</span>
            </span>
            <span class="mt-sn-meta-pill">
                <span>BOGOTÁ · COLOMBIA</span>
            </span>
            <span class="mt-sn-meta-pill">
                <span>LATAM &amp; EU</span>
            </span>
        </div>
    </div>

    <a href="#tesis" class="mt-sn-scroll-cue" data-sn-scroll-cue aria-label="Empezar a leer">
        <span>Empieza la historia</span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </a>
</section>
