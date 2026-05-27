@php
    // Contenido editable desde /pages/1/edit
    $hc = [];
    if (isset($page) && $page && $page->content) {
        $hc = json_decode($page->content, true) ?? [];
    }

    $facebookUrl  = $hc['footer_facebook_url']  ?? 'https://www.facebook.com/profile.php?id=61575108256490';
    $instagramUrl = $hc['footer_instagram_url'] ?? 'https://www.instagram.com/mytech_solutions';
    $whatsappUrl  = $hc['footer_whatsapp_url']  ?? 'https://wa.me/573337246403';
    $phone        = $hc['footer_phone']         ?? '+57 333 724 6403';
    $phoneLabel   = $hc['footer_phone_label']   ?? 'WhatsApp comercial';
    $intro        = $hc['footer_intro']         ?? 'Desarrollo web profesional que impulsa tu negocio.';
    $signature    = $hc['footer_signature']     ?? 'Hecho en Bogotá · Colombia';
@endphp

<footer class="bg-white border-t border-mt-border pt-24 md:pt-32 pb-10 relative overflow-hidden">
    <div class="mt-container">

        <div class="mt-footer-display text-footer-display mb-14 md:mb-20 break-words" aria-hidden="true">
            MY TECH<br>SOLUTIONS
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-mt-border">

            <div>
                <img src="{{ asset('images/logo.png') }}" alt="MY Tech Solutions" class="h-9 w-auto mb-4">
                <p class="text-mt-text-2 text-sm leading-relaxed">
                    {{ $intro }}
                </p>
            </div>

            <div>
                <h4 class="font-mono text-[11px] uppercase tracking-wider text-mt-text-3 mb-5">Navegación</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('home') }}"                  class="text-mt-text-2 hover:text-mt-accent transition text-sm">Inicio</a></li>
                    <li><a href="{{ route('servicios.index') }}"       class="text-mt-text-2 hover:text-mt-accent transition text-sm">Servicios</a></li>
                    <li><a href="{{ route('proyectos.index') }}"       class="text-mt-text-2 hover:text-mt-accent transition text-sm">Proyectos</a></li>
                    <li><a href="{{ route('blog.index') }}"            class="text-mt-text-2 hover:text-mt-accent transition text-sm">Blog</a></li>
                    <li><a href="{{ route('sobre_nosotros.index') }}"  class="text-mt-text-2 hover:text-mt-accent transition text-sm">Sobre Nosotros</a></li>
                </ul>
            </div>

            {{-- Guías — internal linking SEO hacia landings de blog estratégicas --}}
            <div>
                <h4 class="font-mono text-[11px] uppercase tracking-wider text-mt-text-3 mb-5">Guías 2026</h4>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('blog.show', 'software-a-medida-bogota') }}"
                           class="text-mt-text-2 hover:text-mt-accent transition text-sm leading-snug block">
                            Software a medida<br>en Bogotá
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.show', 'cuanto-cuesta-desarrollar-software-colombia-2026') }}"
                           class="text-mt-text-2 hover:text-mt-accent transition text-sm leading-snug block">
                            ¿Cuánto cuesta desarrollar software en Colombia?
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('blog.show', 'cuanto-cuesta-contratar-agencia-desarrollo-software-colombia-2026') }}"
                           class="text-mt-text-2 hover:text-mt-accent transition text-sm leading-snug block">
                            Precios reales: contratar agencia en Colombia
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-mono text-[11px] uppercase tracking-wider text-mt-text-3 mb-5">Redes</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="text-mt-text-2 hover:text-mt-accent transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z"/></svg>
                        Facebook
                    </a></li>
                    <li><a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="text-mt-text-2 hover:text-mt-accent transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                        Instagram
                    </a></li>
                    <li><a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-mt-text-2 hover:text-mt-accent transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.966-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"/></svg>
                        WhatsApp
                    </a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-mono text-[11px] uppercase tracking-wider text-mt-text-3 mb-5">Comercial</h4>
                <a href="{{ $whatsappUrl }}?text=Hola%2C%20me%20interesa%20cotizar%20un%20proyecto"
                   target="_blank" rel="noopener"
                   class="block text-mt-text hover:text-mt-accent transition text-base font-semibold">
                    {{ $phone }}
                </a>
                <p class="mt-1 text-xs text-mt-text-3">{{ $phoneLabel }}</p>
                <a href="{{ route('contacto.index') }}" class="inline-flex items-center gap-2 mt-5 text-mt-accent hover:gap-3 transition-all text-xs font-mono uppercase tracking-wider">
                    Cotizar proyecto
                    <span aria-hidden="true">→</span>
                </a>
            </div>

        </div>

        <div class="pt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <p class="text-xs text-mt-text-3 font-mono">
                © {{ date('Y') }} MY Tech Solutions. Todos los derechos reservados.
            </p>
            <p class="text-xs text-mt-text-3 font-mono">
                {{ $signature }}
            </p>
        </div>
    </div>
</footer>
