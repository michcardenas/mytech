{{-- Sección CTA Final --}}
@props(['section'])

@php
    $title = $section->title ?? '¿Listo para comenzar?';
    $content = $section->content ?? '';
    $data = $section->custom_data ?? [];
    $buttonText = $data['button_text'] ?? 'Contactar';
    $buttonUrl = $data['button_url'] ?? '/contacto';
    $secondaryText = $data['secondary_text'] ?? '';
    $backgroundColor = $data['background_color'] ?? '#007BFF';
@endphp

<section class="cta-final-section py-20" style="background: linear-gradient(135deg, {{ $backgroundColor }} 0%, {{ $backgroundColor }}dd 100%);">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center text-white">
            {{-- Title --}}
            <h2 class="text-3xl md:text-5xl font-bold mb-6">
                {{ $title }}
            </h2>

            {{-- Content --}}
            @if($content)
                <div class="text-xl md:text-2xl mb-8 text-white/90 prose prose-xl prose-invert max-w-none">
                    {!! $content !!}
                </div>
            @endif

            {{-- CTA Button --}}
            <div class="flex flex-col items-center gap-4">
                <a href="{{ $buttonUrl }}"
                   class="bg-white text-gray-900 px-10 py-5 rounded-lg font-bold text-xl hover:bg-gray-100 transition-all transform hover:scale-105 shadow-2xl inline-block">
                    {{ $buttonText }}
                </a>

                {{-- Secondary Text --}}
                @if($secondaryText)
                    <p class="text-white/80 text-lg">
                        {{ $secondaryText }}
                    </p>
                @endif
            </div>

            {{-- Trust Badges (opcional) --}}
            <div class="mt-12 flex justify-center gap-8 text-sm text-white/70">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i>
                    <span>100% Seguro</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-lock"></i>
                    <span>Datos Protegidos</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Sin Compromiso</span>
                </div>
            </div>
        </div>
    </div>
</section>
