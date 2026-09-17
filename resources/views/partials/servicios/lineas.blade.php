{{--
    Líneas destacadas — enlaza las landings comerciales desde /servicios
    (enlace interno topical + descubrimiento + link equity).
    Bloque 1: 4 especialidades amplias. Bloque 2: 5 soluciones específicas.
--}}
@php
    $lineasAmplias = [
        ['url' => url('/chatbots-ia-whatsapp'),       'tag' => 'IA · WhatsApp',      'title' => 'Chatbots con IA para WhatsApp', 'desc' => 'Asistentes que atienden, cobran y agendan por ti, las 24 horas.', 'icon' => 'chat'],
        ['url' => url('/automatizacion-ia-empresas'), 'tag' => 'IA · Automatización', 'title' => 'Automatización con IA',        'desc' => 'IA que lee correos, redacta documentos e interpreta contratos.', 'icon' => 'bolt'],
        ['url' => url('/desarrollo-ecommerce'),       'tag' => 'E-commerce',          'title' => 'Tiendas online a la medida',    'desc' => 'Vende sin comisiones ni límites de plantilla, con SEO de fábrica.', 'icon' => 'cart'],
        ['url' => url('/software-a-la-medida'),        'tag' => 'SaaS · Plataformas',  'title' => 'Software a la medida',          'desc' => 'Plataformas que se ajustan a tu operación y escalan contigo.', 'icon' => 'code'],
    ];

    $lineasEspecificas = [
        ['url' => url('/software-crm-a-la-medida'),              'tag' => 'CRM · Ventas',        'title' => 'CRM a la medida',              'desc' => 'Pipeline, leads, seguimiento y reportes, como vende tu equipo.', 'icon' => 'users'],
        ['url' => url('/software-erp-a-la-medida'),              'tag' => 'ERP · Gestión',       'title' => 'ERP a la medida',              'desc' => 'Inventario, ventas, compras, cartera y producción en un solo lugar.', 'icon' => 'server'],
        ['url' => url('/desarrollo-de-apps-moviles'),            'tag' => 'iOS · Android',       'title' => 'Apps móviles',                 'desc' => 'Una sola base de código para iPhone y Android, en las tiendas.', 'icon' => 'phone'],
        ['url' => url('/software-facturacion-electronica-dian'), 'tag' => 'DIAN · SIIGO',        'title' => 'Facturación electrónica DIAN', 'desc' => 'Homologada e integrada a tu operación, sin volver a digitar.', 'icon' => 'invoice'],
        ['url' => url('/integracion-de-sistemas'),               'tag' => 'APIs · Integración',  'title' => 'Integración de sistemas',      'desc' => 'Que tus plataformas hablen entre sí, sin copiar y pegar.', 'icon' => 'link'],
    ];
@endphp

<section class="relative py-28 md:py-36 bg-white border-t border-mt-border">
    <div class="mt-container">
        <div class="max-w-3xl mb-14" data-animate>
            <span class="mt-eyebrow-gray">Especialidades</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                Formas de hacer crecer tu negocio con software.
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Son las soluciones que más nos piden. Cada una tiene su propia página con casos reales, precios y detalles.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($lineasAmplias as $l)
                @include('partials.servicios.linea-card', ['l' => $l])
            @endforeach
        </div>

        {{-- Bloque 2: soluciones específicas --}}
        <div class="max-w-3xl mt-24 mb-14" data-animate>
            <span class="mt-eyebrow-gray">Soluciones específicas</span>
            <h2 class="mt-4 text-section font-display text-mt-text">
                ¿Ya sabes qué necesitas?
            </h2>
            <p class="mt-6 text-mt-text-2 text-base md:text-lg leading-relaxed">
                Si buscas algo puntual, entra directo a la página de esa solución. Todo se desarrolla a la medida y se integra con lo que ya usas.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($lineasEspecificas as $l)
                @include('partials.servicios.linea-card', ['l' => $l])
            @endforeach
        </div>
    </div>
</section>
