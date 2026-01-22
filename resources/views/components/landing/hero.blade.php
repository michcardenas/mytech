{{-- Componente Hero para Landing Pages --}}
@props(['section'])

@php
    $data = $section->custom_data ?? [];
    $title = $section->title ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $ctaText = $data['cta_text'] ?? 'Contactar';
    $ctaUrl = $data['cta_url'] ?? '/contacto';
    $badge = $data['badge'] ?? '';
    $backgroundImage = $data['background_image'] ?? '';
@endphp

<section class="hero-landing position-relative overflow-hidden" style="background: linear-gradient(135deg, #007BFF 0%, #0056b3 100%); color: white; padding: 100px 0;">
    {{-- Background Image --}}
    @if($backgroundImage)
        <div class="position-absolute top-0 start-0 w-100 h-100" style="opacity: 0.2;">
            <img src="{{ $backgroundImage }}" alt="Background" class="w-100 h-100" style="object-fit: cover;">
        </div>
    @endif

    {{-- Content --}}
    <div class="container position-relative" style="z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8 text-center">
                {{-- Badge --}}
                @if($badge)
                    <div class="mb-4">
                        <span class="badge rounded-pill px-4 py-2" style="background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); font-size: 1rem; font-weight: 600;">
                            {{ $badge }}
                        </span>
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="display-3 fw-bold mb-4" style="line-height: 1.2;">
                    {{ $title }}
                </h1>

                {{-- Subtitle --}}
                @if($subtitle)
                    <p class="fs-4 mb-5" style="color: rgba(255, 255, 255, 0.9);">
                        {{ $subtitle }}
                    </p>
                @endif

                {{-- CTA Button --}}
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ $ctaUrl }}" class="btn btn-light btn-lg px-5 py-3 fw-bold" style="border-radius: 10px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
                        {{ $ctaText }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Decorative Wave --}}
    <div class="position-absolute bottom-0 start-0 w-100">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="display: block; width: 100%; height: 60px;">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                  fill="white"></path>
        </svg>
    </div>
</section>
