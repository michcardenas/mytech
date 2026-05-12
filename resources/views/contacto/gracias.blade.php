@extends('layouts.app')

@section('title', 'Gracias por contactarnos - MyTech Solutions')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 text-center px-6">
    <div class="bg-white p-10 rounded-2xl shadow-lg max-w-lg">
        <h1 class="text-3xl font-bold text-green-600 mb-4">¡Gracias por contactarnos! 🎉</h1>
        <p class="text-gray-700 mb-6">
            Hemos recibido tu solicitud y uno de nuestros especialistas se comunicará contigo muy pronto.
        </p>

        <a href="{{ url('/') }}" 
           class="inline-block bg-teal-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
           Volver al inicio
        </a>

        <div class="mt-5">
            <a href="https://wa.me/573337246403?text=Hola%20MyTech%20Solutions,%20quiero%20más%20información%20sobre%20sus%20servicios." 
               target="_blank" 
               class="text-green-600 underline font-medium">
               📱 Escríbenos por WhatsApp
            </a>
        </div>
    </div>

    {{-- dataLayer push para GTM (siempre activo) --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            'event': 'form_submit_contacto',
            'form_name': 'contacto_principal',
            'form_destination': '/gracias'
        });
    </script>

    {{-- Meta Pixel: evento Lead --}}
    @if(config('services.meta.pixel_id'))
    <script>
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Lead', {
                content_name: 'Formulario de contacto',
                content_category: 'Lead generation'
            });
        }
    </script>
    @endif

    {{-- Google Ads: conversión (solo si está configurado en .env) --}}
    @if(config('services.google_ads.conversion_id') && config('services.google_ads.conversion_label'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_ads.conversion_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google_ads.conversion_id') }}');
        gtag('event', 'conversion', {
            'send_to': '{{ config('services.google_ads.conversion_id') }}/{{ config('services.google_ads.conversion_label') }}'
        });
    </script>
    @endif
</div>
@endsection
