{{-- ═══════════════════════════════════════════════════════════════════
     Proyecto — FAQ section + FAQPage schema
     El schema y el contenido visible DEBEN coincidir (requisito de Google).
     Solo se renderiza si el proyecto tiene FAQs cargadas.
     ═══════════════════════════════════════════════════════════════════ --}}

@php
    $faqs = is_array($proyecto->faqs) ? array_values(array_filter(
        $proyecto->faqs,
        fn ($f) => is_array($f) && ! empty($f['pregunta']) && ! empty($f['respuesta'])
    )) : [];
@endphp

@if(count($faqs) > 0)
<section class="py-24 md:py-32 bg-white border-t border-mt-border" data-pd-faq>
    <div class="mt-container">
        <div class="max-w-2xl mb-16" data-animate>
            <span class="mt-eyebrow-gray">[ Preguntas frecuentes ]</span>
            <h2 class="mt-3 text-section font-display font-bold text-mt-text leading-tight text-balance">
                Lo que suelen preguntar sobre {{ $proyecto->nombre }}.
            </h2>
        </div>

        <div class="max-w-3xl mx-auto md:mx-0 border-t border-mt-border" x-data="{ open: 0 }">
            @foreach($faqs as $i => $faq)
                <article class="border-b border-mt-border">
                    <button type="button"
                            class="w-full flex items-start gap-4 text-left py-6"
                            @click="open = (open === {{ $i }} ? null : {{ $i }})"
                            :aria-expanded="open === {{ $i }}"
                            aria-controls="pd-faq-a-{{ $i }}">
                        <span class="font-mono text-sm text-mt-accent pt-1">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex-1 font-display font-semibold text-lg md:text-xl text-mt-text">{{ $faq['pregunta'] }}</span>
                        <span class="text-mt-accent text-2xl leading-none transition-transform duration-200"
                              :class="{ 'rotate-45': open === {{ $i }} }" aria-hidden="true">+</span>
                    </button>
                    <div class="pl-10 pr-4 pb-6 text-mt-text-2 text-base md:text-lg leading-relaxed"
                         id="pd-faq-a-{{ $i }}"
                         x-show="open === {{ $i }}"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        {!! nl2br(e($faq['respuesta'])) !!}
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── JSON-LD FAQPage (debe coincidir con el contenido visible de arriba) ─── --}}
@push('head_extras')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'FAQPage',
    'mainEntity' => array_map(fn ($f) => [
        '@type'          => 'Question',
        'name'           => $f['pregunta'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => $f['respuesta'],
        ],
    ], $faqs),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush
@endif
