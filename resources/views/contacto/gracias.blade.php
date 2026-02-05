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

    {{-- 🚀 Etiqueta de conversión Google Ads (reemplaza con tu ID real) --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-XXXXXXXXXX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'AW-XXXXXXXXXX');

      // Evento de conversión
      gtag('event', 'conversion', {'send_to': 'AW-XXXXXXXXXX/abcdEFGHijk'});
    </script>
</div>
@endsection
