@php
    $stack = is_array($proyecto->tecnologias) ? $proyecto->tecnologias : [];
@endphp

@if(count($stack) > 0)
<section class="mt-pd-stack py-20 md:py-28 bg-mt-bg-2 border-t border-mt-border">
    <div class="mt-container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-end">
            <div class="lg:col-span-5" data-animate>
                <span class="mt-eyebrow-gray">Stack técnico</span>
                <h2 class="mt-3 text-[clamp(1.75rem,3.5vw,2.75rem)] font-display font-bold text-mt-text leading-tight text-balance">
                    Construido con tecnología
                    <span class="text-mt-accent italic">probada</span>.
                </h2>
                <p class="mt-4 text-mt-text-2 text-base leading-relaxed max-w-md">
                    Las mismas herramientas que escalan a millones de requests. Sin frameworks de moda, sin atajos.
                </p>
            </div>

            <div class="lg:col-span-7" data-animate-children>
                <ul class="mt-pd-stack-list">
                    @foreach($stack as $i => $tech)
                        <li class="mt-pd-stack-chip" style="--chip-idx: {{ $i }}">
                            <span class="mt-pd-stack-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="mt-pd-stack-name">{{ $tech }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
@endif
