@php
    use App\Support\ProjectCardHelper as PCH;

    $pc = [];
    if (isset($page) && $page && $page->content) {
        $pc = json_decode($page->content, true) ?? [];
    }
    $eyebrow = $pc['proy_marquee_eyebrow'] ?? 'Clientes';
    $title   = $pc['proy_marquee_title']   ?? 'Empresas que confían en nosotros.';

    // Solo proyectos CON logo — los que no tienen no aportan al marquee
    $conLogo = ($proyectos ?? collect())->filter(fn($p) => ! empty($p->logo))->values();
@endphp

@if($conLogo->count() > 0)
<section class="py-20 md:py-28 bg-mt-bg-2 border-t border-b border-mt-border overflow-hidden">
    <div class="mt-container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12" data-animate>
            <div class="max-w-xl">
                <span class="mt-eyebrow-gray">{{ $eyebrow }}</span>
                <h2 class="mt-3 text-[clamp(1.75rem,3vw,2.5rem)] font-display font-bold text-mt-text leading-tight text-balance">
                    {{ $title }}
                </h2>
            </div>
            <div class="font-mono text-[11px] uppercase tracking-[0.22em] text-mt-text-3">
                {{ $conLogo->count() }} {{ $conLogo->count() === 1 ? 'logo' : 'logos' }} · marca
            </div>
        </div>
    </div>

    {{-- Marquee infinito — mask edges para fade lateral --}}
    <div class="mt-proy-marquee" data-proyectos-marquee>
        <div class="mt-proy-marquee-track" data-proyectos-marquee-track>
            {{-- Duplicamos x2 para loop seamless --}}
            @for($iter = 0; $iter < 2; $iter++)
                @foreach($conLogo as $p)
                    <div class="mt-proy-marquee-item" title="{{ $p->nombre }}">
                        <img src="{{ PCH::logoUrl($p->logo) }}"
                             alt="{{ $p->nombre }}"
                             loading="lazy"
                             decoding="async">
                        <span class="mt-proy-marquee-name">{{ $p->nombre }}</span>
                    </div>
                @endforeach
            @endfor
        </div>
    </div>
</section>
@endif
