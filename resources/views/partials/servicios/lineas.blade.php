{{--
    Líneas destacadas — enlaza las 3 landings comerciales desde /servicios
    (enlace interno topical + descubrimiento + link equity).
--}}
<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-14" data-animate>
            <span class="mt-eyebrow-gray">Especialidades</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                Cuatro formas de hacer crecer tu negocio con software.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Son las soluciones que más nos piden. Cada una tiene su propia página con casos reales, precios y detalles.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ([
                ['url' => url('/chatbots-ia-whatsapp'), 'tag' => 'IA · WhatsApp', 'title' => 'Chatbots con IA para WhatsApp', 'desc' => 'Asistentes que atienden, cobran y agendan por ti, las 24 horas.', 'icon' => 'chat'],
                ['url' => url('/automatizacion-ia-empresas'), 'tag' => 'IA · Automatización', 'title' => 'Automatización con IA', 'desc' => 'IA que lee correos, redacta documentos e interpreta contratos.', 'icon' => 'bolt'],
                ['url' => url('/desarrollo-ecommerce'), 'tag' => 'E-commerce', 'title' => 'Tiendas online a la medida', 'desc' => 'Vende sin comisiones ni límites de plantilla, con SEO de fábrica.', 'icon' => 'cart'],
                ['url' => url('/software-a-la-medida'), 'tag' => 'SaaS · ERP · CRM', 'title' => 'Software a la medida', 'desc' => 'Plataformas que se ajustan a tu operación y escalan contigo.', 'icon' => 'code'],
            ] as $l)
                <a href="{{ $l['url'] }}"
                   class="group flex flex-col rounded-2xl border border-mt-border bg-white p-7 transition-all duration-300 hover:border-mt-accent hover:shadow-mt-medium" data-animate>
                    <div class="flex items-center justify-between gap-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl border border-mt-border bg-white text-mt-text transition-colors duration-300 group-hover:border-mt-accent group-hover:text-mt-accent">
                            @switch($l['icon'])
                                @case('chat')
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8 8 0 01-11.6 7.1L3 21l1.9-6.4A8 8 0 1121 12z"/></svg>
                                    @break
                                @case('bolt')
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L4.5 13.5H11l-1 8.5L19.5 10H13l0-8z"/></svg>
                                    @break
                                @case('cart')
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2.3 2.3M17 13l1.3 2.3M9 20a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                    @break
                                @default
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-4 3 4 3M16 9l4 3-4 3M14 5l-4 14"/></svg>
                            @endswitch
                        </span>
                        <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-mt-text-3">{{ $l['tag'] }}</span>
                    </div>
                    <h3 class="mt-5 text-xl font-display font-semibold text-mt-text leading-tight">{{ $l['title'] }}</h3>
                    <p class="mt-2 text-mt-text-2 text-[14.5px] leading-relaxed">{{ $l['desc'] }}</p>
                    <div class="mt-auto pt-7 flex items-center justify-between gap-3">
                        <span class="font-mono text-[11px] uppercase tracking-[0.18em] text-mt-text-2 transition-colors duration-300 group-hover:text-mt-accent">Ver más</span>
                        <span class="relative inline-flex items-center justify-center w-10 h-10 rounded-full border border-mt-border text-mt-text overflow-hidden transition-all duration-300 group-hover:border-mt-accent group-hover:text-white group-hover:shadow-mt-btn" aria-hidden="true">
                            <span class="absolute inset-0 bg-mt-accent scale-0 rounded-full transition-transform duration-300 ease-out group-hover:scale-100"></span>
                            <svg class="relative w-[18px] h-[18px] transition-transform duration-300 group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
