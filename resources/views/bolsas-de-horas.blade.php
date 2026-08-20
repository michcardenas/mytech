@extends('layouts.app-home')

{{--
    Página pública: Bolsas de horas prepagadas.
    SEO (title/meta/OG) se inyecta desde el $seo que pasa la ruta (routes/web.php,
    Route::view '/bolsas-de-horas'). Aquí solo va el CONTENIDO.
--}}

@php
    $waNumber = '573337246403';
    $waBase   = 'https://wa.me/' . $waNumber . '?text=';

    // Bolsas — el precio/hora se descuenta por tiempo real trabajado, vigencia 6 meses.
    $bolsas = [
        ['horas' => 5,  'precio' => '270.000',   'hora' => '54.000', 'ahorro' => null,  'nota' => null,                                  'destacada' => false],
        ['horas' => 10, 'precio' => '480.000',   'hora' => '48.000', 'ahorro' => '11%', 'nota' => null,                                  'destacada' => false],
        ['horas' => 20, 'precio' => '900.000',   'hora' => '45.000', 'ahorro' => '17%', 'nota' => null,                                  'destacada' => false],
        ['horas' => 40, 'precio' => '1.680.000', 'hora' => '42.000', 'ahorro' => '22%', 'nota' => 'Equivale a una semana de dedicación', 'destacada' => true],
        ['horas' => 80, 'precio' => '3.040.000', 'hora' => '38.000', 'ahorro' => '30%', 'nota' => 'Equivale a dos semanas de dedicación','destacada' => false],
    ];

    $waCta = $waBase . rawurlencode('Hola, me interesa una bolsa de horas prepagadas para mi proyecto.');
@endphp

@section('content')

{{-- ============================================================= --}}
{{-- HERO                                                          --}}
{{-- ============================================================= --}}
<section class="relative overflow-hidden bg-white pt-36 pb-20 md:pb-24">
    <div class="mt-container relative z-10">
        <div class="max-w-3xl">
            <div data-animate>
                <span class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full border border-mt-accent-line bg-mt-accent-soft text-mt-accent font-mono text-[11px] uppercase tracking-[0.18em]">
                    <span class="w-1.5 h-1.5 rounded-full bg-mt-accent animate-pulse-soft"></span>
                    Bolsas de horas · Prepago
                </span>
            </div>

            <h1 class="mt-7 text-hero font-display text-mt-text" data-animate>
                Horas prepagadas para <span class="text-mt-accent">lo que venga después</span>.
            </h1>

            <p class="mt-7 text-base md:text-lg text-mt-text-2 leading-relaxed" data-animate>
                Para los próximos cambios y mejoras de tu proyecto te sale mejor dejar horas prepagadas: reservas por adelantado, pagas menos por hora y sacamos los ajustes más rápido. Mientras más horas reservas, más bajo es el precio por hora.
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3" data-animate>
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-mt-border bg-mt-bg-2 text-mt-text-2 text-[13.5px]">
                    <svg class="w-4 h-4 text-mt-accent flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Se descuentan por tiempo real trabajado
                </span>
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-mt-border bg-mt-bg-2 text-mt-text-2 text-[13.5px]">
                    <svg class="w-4 h-4 text-mt-accent flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                    Vigencia de 6 meses
                </span>
                <span class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-amber-200 bg-amber-50 text-amber-700 text-[13.5px]">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Aplica solo a implementaciones nuevas, no a la garantía
                </span>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- PLANES / BOLSAS                                               --}}
{{-- ============================================================= --}}
<section class="relative py-20 md:py-28 bg-mt-bg-2 border-y border-mt-border">
    <div class="mt-container">
        <div class="max-w-2xl" data-animate>
            <span class="mt-eyebrow-gray">Planes</span>
            <h2 class="mt-4 text-section font-display text-mt-text">Elige tu bolsa de horas.</h2>
            <p class="mt-5 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Cinco tamaños. El precio por hora baja de $54.000 a $38.000 según el volumen que reserves.
            </p>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($bolsas as $b)
                @php
                    $waPlan = $waBase . rawurlencode('Hola, me interesa la bolsa de ' . $b['horas'] . ' horas ($' . $b['precio'] . ' COP). ¿Cómo la contrato?');
                @endphp
                <div data-animate
                     class="relative flex flex-col rounded-2xl border p-6 transition-transform duration-300 hover:-translate-y-1
                            {{ $b['destacada']
                                ? 'border-mt-accent bg-white ring-1 ring-mt-accent shadow-mt-strong lg:-mt-3 lg:mb-3'
                                : 'border-mt-border bg-white shadow-mt-soft hover:shadow-mt-medium' }}">

                    @if ($b['destacada'])
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 whitespace-nowrap px-3 py-1 rounded-full bg-mt-accent text-white font-mono text-[10px] uppercase tracking-[0.14em] shadow-mt-btn">
                            Más elegido
                        </span>
                    @endif

                    <div class="flex items-baseline justify-between">
                        <span class="font-mono text-[11px] uppercase tracking-[0.16em] text-mt-text-3">Bolsa</span>
                        @if ($b['ahorro'])
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-mt-accent-soft border border-mt-accent-line text-mt-accent text-[11px] font-semibold">
                                −{{ $b['ahorro'] }}
                            </span>
                        @endif
                    </div>

                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-5xl font-display font-semibold text-mt-text tracking-tight">{{ $b['horas'] }}</span>
                        <span class="text-mt-text-2 text-lg">horas</span>
                    </div>

                    <div class="mt-5 pt-5 border-t border-mt-border">
                        <div class="flex items-baseline gap-1">
                            <span class="text-mt-text-3 text-sm">$</span>
                            <span class="text-2xl md:text-[1.65rem] font-display font-semibold text-mt-text tracking-tight">{{ $b['precio'] }}</span>
                            <span class="text-mt-text-3 font-mono text-xs ml-0.5">COP</span>
                        </div>
                        <div class="mt-1.5 text-[13.5px] font-medium text-mt-accent">
                            ${{ $b['hora'] }} <span class="text-mt-text-3 font-normal">/ hora</span>
                        </div>
                    </div>

                    <div class="mt-4 min-h-[2.5rem]">
                        @if ($b['nota'])
                            <p class="flex items-start gap-1.5 text-[12.5px] text-mt-text-2 leading-snug">
                                <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-mt-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $b['nota'] }}
                            </p>
                        @endif
                    </div>

                    <a href="{{ $waPlan }}" target="_blank" rel="noopener"
                       class="mt-5 w-full justify-center {{ $b['destacada'] ? 'mt-btn-primary' : 'mt-btn-ghost' }}">
                        Elegir esta bolsa
                    </a>
                </div>
            @endforeach
        </div>

        <p class="mt-8 text-center text-mt-text-3 text-[13px] max-w-2xl mx-auto leading-relaxed" data-animate>
            Con las bolsas de <strong class="text-mt-text-2 font-semibold">40 y 80 horas</strong> puedo reservarte días completos de dedicación para sacar los cambios más rápido.
        </p>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CÓMO FUNCIONA                                                 --}}
{{-- ============================================================= --}}
<section class="relative py-24 md:py-32 bg-white">
    <div class="mt-container">
        <div class="max-w-2xl" data-animate>
            <span class="mt-eyebrow-gray">Cómo funciona</span>
            <h2 class="mt-4 text-section font-display text-mt-text">Simple, claro y sin sorpresas.</h2>
        </div>

        <div class="mt-14 grid md:grid-cols-3 gap-5">
            @foreach ([
                ['t' => 'Reservas por adelantado', 'd' => 'Contratas la bolsa que necesitas y queda disponible para los cambios y mejoras que vayan surgiendo en tu proyecto.', 'icon' => 'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['t' => 'Descuento por tiempo real', 'd' => 'Cada ajuste consume solo las horas efectivamente trabajadas. Siempre sabes cuántas horas te quedan disponibles.', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['t' => 'Vigencia de 6 meses', 'd' => 'Tienes medio año para usar tus horas. Aplica únicamente a implementaciones nuevas, no a la garantía del proyecto.', 'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
            ] as $paso)
                <div class="rounded-2xl border border-mt-border bg-mt-bg-2 p-6" data-animate>
                    <div class="w-11 h-11 rounded-xl bg-white border border-mt-border flex items-center justify-center text-mt-accent mb-4">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $paso['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-lg font-display font-semibold text-mt-text leading-tight">{{ $paso['t'] }}</h3>
                    <p class="mt-2 text-mt-text-2 text-[14.5px] leading-relaxed">{{ $paso['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================= --}}
{{-- CTA FINAL                                                     --}}
{{-- ============================================================= --}}
<section class="relative py-24 md:py-32 bg-mt-bg-dark overflow-hidden">
    <div class="mt-container relative z-10 text-center">
        <h2 class="text-section font-display text-white max-w-3xl mx-auto" data-animate>
            ¿Te interesa alguno de los planes?
        </h2>
        <p class="mt-6 text-mt-text-on-dark text-base md:text-lg max-w-2xl mx-auto leading-relaxed" data-animate>
            Cuéntame cuál se ajusta a lo que necesitas y coordinamos para dejar tus horas listas. Quedo pendiente de tu revisión.
        </p>
        <div class="mt-10 flex flex-wrap gap-4 justify-center" data-animate>
            <a href="{{ $waCta }}" target="_blank" rel="noopener" class="mt-btn-primary">
                Hablar por WhatsApp
                <span aria-hidden="true">&rarr;</span>
            </a>
            <a href="{{ route('contacto.index') }}" class="mt-btn-ghost mt-btn-ghost-on-dark">
                Escribir por el formulario
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    /* Red de seguridad: si el observer de reveals de la home no corre en esta
       ruta, revelamos el contenido igual para no dejar nada invisible. */
    window.addEventListener('load', function () {
        setTimeout(function () {
            if (!document.querySelector('[data-animate].is-visible')) {
                document.querySelectorAll('[data-animate]').forEach(function (el) {
                    el.classList.add('is-visible');
                });
            }
        }, 700);
    });
</script>
@endpush
