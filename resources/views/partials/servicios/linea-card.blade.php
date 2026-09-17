{{-- Tarjeta de línea/servicio reutilizable. Espera $l = [url, tag, title, desc, icon]. --}}
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
                @case('users')
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1a4 4 0 00-4-4M9 20H4v-1a4 4 0 014-4h2a4 4 0 014 4v1H9zm3-8a3 3 0 100-6 3 3 0 000 6zm6-1a2.5 2.5 0 100-5"/></svg>
                    @break
                @case('server')
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="3" y="4" width="18" height="7" rx="1.5"/><rect x="3" y="13" width="18" height="7" rx="1.5"/><path stroke-linecap="round" d="M7 7.5h.01M7 16.5h.01"/></svg>
                    @break
                @case('phone')
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="7" y="2.5" width="10" height="19" rx="2.5"/><path stroke-linecap="round" d="M11 18.5h2"/></svg>
                    @break
                @case('invoice')
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2.5h9l3 3V21l-2-1-2 1-2-1-2 1-2-1-2 1V4a1.5 1.5 0 011.5-1.5z"/><path stroke-linecap="round" d="M9 8h6M9 12h6M9 16h3"/></svg>
                    @break
                @case('link')
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 13a5 5 0 007.5.5l2-2a5 5 0 00-7-7l-1 1M14 11a5 5 0 00-7.5-.5l-2 2a5 5 0 007 7l1-1"/></svg>
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
