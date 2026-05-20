@php
    $pc = [];
    if (isset($page) && $page && $page->content) {
        $pc = json_decode($page->content, true) ?? [];
    }
    $eyebrow  = $pc['proy_paises_eyebrow']  ?? 'Geografía';
    $title    = $pc['proy_paises_title']    ?? 'Una operación, '.($totalPaises ?? 11).' países.';
    $subtitle = $pc['proy_paises_subtitle'] ?? 'Trabajamos con empresas en toda LATAM y mercados internacionales. El equipo en Bogotá, la operación en todas partes.';

    $paises = $paisesConteo ?? collect();
@endphp

@if($paises->count() > 0)
<section class="mt-proy-paises py-28 md:py-36 bg-mt-bg-2 border-t border-mt-border relative overflow-hidden">

    {{-- Decoración tipográfica de fondo --}}
    <div class="mt-proy-paises-bg-type" aria-hidden="true">
        <span>LATAM</span>
    </div>

    <div class="mt-container relative z-10">

        {{-- Header con layout editorial asymmetric --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-16 md:mb-20" data-animate>
            <div class="lg:col-span-5">
                <span class="mt-eyebrow-gray">{{ $eyebrow }}</span>
                <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                    {{ $title }}
                </h2>
            </div>
            <div class="lg:col-span-6 lg:col-start-7 flex items-end">
                <p class="text-mt-text-2 text-base md:text-lg leading-relaxed max-w-md">
                    {{ $subtitle }}
                </p>
            </div>
        </div>

        {{-- Grid de tiles de país — premium editorial --}}
        <div class="mt-proy-paises-grid" data-proyectos-paises>
            @foreach($paises as $pais => $info)
                @php
                    // Cantidad afecta intensidad visual sutilmente
                    $count = $info['count'];
                    $isPrimary = $count >= 3;  // países con 3+ proyectos = tile destacado
                @endphp
                <article class="mt-proy-paises-tile {{ $isPrimary ? 'is-primary' : '' }}"
                         data-proyectos-paises-tile
                         data-animate>

                    {{-- Bandera masiva como elemento decorativo --}}
                    <div class="mt-proy-paises-tile-flag-wrap" aria-hidden="true">
                        <span class="mt-proy-paises-tile-flag">{{ $info['flag'] }}</span>
                        {{-- Bandera duplicada gigante como watermark --}}
                        <span class="mt-proy-paises-tile-flag-ghost">{{ $info['flag'] }}</span>
                    </div>

                    {{-- Contenido --}}
                    <div class="mt-proy-paises-tile-content">
                        <h3 class="mt-proy-paises-tile-name">{{ $pais }}</h3>
                        <span class="mt-proy-paises-tile-count">
                            {{ str_pad($count, 2, '0', STR_PAD_LEFT) }}
                            <span class="mt-proy-paises-tile-count-label">
                                {{ $count === 1 ? 'proyecto' : 'proyectos' }}
                            </span>
                        </span>
                    </div>

                    {{-- Línea inferior animada en hover --}}
                    <span class="mt-proy-paises-tile-strip" aria-hidden="true"></span>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
