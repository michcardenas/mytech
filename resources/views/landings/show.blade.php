@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
@if($hero && $hero->is_active)
    <x-landing.hero :section="$hero" />
@endif

{{-- Problema Section --}}
@if($problema && $problema->is_active)
    <x-landing.problema :section="$problema" />
@endif

{{-- Solución Section --}}
@if($solucion && $solucion->is_active)
    <x-landing.solucion :section="$solucion" />
@endif

{{-- Proyectos Destacados Section --}}
@if($proyectosSection && $proyectosSection->is_active)
    <x-landing.proyectos :section="$proyectosSection" :proyectos="$proyectos" />
@endif

{{-- FAQs Section --}}
@if($faqs && $faqs->is_active)
    <x-landing.faqs :section="$faqs" />
@endif

{{-- CTA Final Section --}}
@if($ctaFinal && $ctaFinal->is_active)
    <x-landing.cta-final :section="$ctaFinal" />
@endif
@endsection
