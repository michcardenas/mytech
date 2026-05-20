<?php

namespace Database\Seeders;

use App\Models\Proyecto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder SOLO para entorno local — datos demostrativos para previsualizar home-v2.
 * En producción los proyectos se editan desde /admin-proyectos.
 *
 * Uso:
 *   php artisan db:seed --class=HomeDevSeeder
 */
class HomeDevSeeder extends Seeder
{
    public function run(): void
    {
        $proyectos = [
            ['nombre' => 'Nuvion Glass',    'pais' => 'México',     'flag' => '🇲🇽', 'cat' => 'ecommerce',  'badge' => 'E-commerce',     'url' => 'https://nuvionglass.com.mx',  'desc' => 'Plataforma e-commerce con sistema automático 2x1 para lentes con filtro de luz azul. Pagos integrados y gestión de inventario.', 'techs' => ['Laravel 12', 'Vue.js', 'Tailwind CSS', 'MySQL'], 'estado' => 'en_vivo', 'destacado' => true,  'duracion' => '3 meses', 'visitas' => 18000, 'equipo' => 3, 'logo' => '/images/logo.png'],
            ['nombre' => 'Dicomsa',         'pais' => 'Guatemala',  'flag' => '🇬🇹', 'cat' => 'automation', 'badge' => 'WhatsApp API',   'url' => null,                          'desc' => 'Ecosistema de automatización comercial integrando WhatsApp Business API con Respond.io y CAPI de Meta para campañas conectadas.',         'techs' => ['WhatsApp API', 'Respond.io', 'Meta Ads', 'Laravel'], 'estado' => 'en_vivo', 'destacado' => true,  'duracion' => '2 meses', 'visitas' => null, 'equipo' => 2, 'logo' => null],
            ['nombre' => 'VoyConVos',       'pais' => 'Argentina',  'flag' => '🇦🇷', 'cat' => 'travel',     'badge' => 'Carpooling',     'url' => 'https://voyconvos.com',       'desc' => 'Plataforma de viajes compartidos que conecta pasajeros con conductores. Sistema antifraude con verificación Veriff integrada.',          'techs' => ['Laravel', 'Vue.js', 'Veriff', 'PostgreSQL'], 'estado' => 'en_vivo', 'destacado' => true,  'duracion' => '6 meses', 'visitas' => 45000, 'equipo' => 4, 'logo' => '/images/logo.png'],
            ['nombre' => 'IPvestment',      'pais' => 'Rep. Dom.',  'flag' => '🇩🇴', 'cat' => 'admin',      'badge' => 'PropTech',       'url' => 'https://ipinvestmentsrd.com', 'desc' => 'Plataforma de gestión de condominios con módulos para propietarios, inquilinos y administradores. Pagos y reportes en tiempo real.',     'techs' => ['Laravel', 'Vue.js', 'PostgreSQL'], 'estado' => 'en_vivo', 'destacado' => false, 'duracion' => '4 meses', 'visitas' => 12000, 'equipo' => 3],
            ['nombre' => 'Sur Alpine',      'pais' => 'Colombia',   'flag' => '🇨🇴', 'cat' => 'automation', 'badge' => 'Bot IA',         'url' => null,                          'desc' => 'Bot de WhatsApp con IA que procesa más de 150 conversaciones diarias automatizando el primer contacto comercial de la empresa.',          'techs' => ['WhatsApp API', 'WATI', 'Laravel', 'Anthropic API'], 'estado' => 'en_vivo', 'destacado' => true,  'duracion' => '1 mes', 'visitas' => null, 'equipo' => 2],
            ['nombre' => 'TuMesa',          'pais' => 'Argentina',  'flag' => '🇦🇷', 'cat' => 'booking',    'badge' => 'Marketplace',    'url' => 'https://tumesa.ar',           'desc' => 'Marketplace que conecta comensales con chefs anfitriones para experiencias culinarias únicas. Sistema de reservas y pagos.',              'techs' => ['Laravel', 'Vue.js', 'Stripe', 'MySQL'], 'estado' => 'en_vivo', 'destacado' => false, 'duracion' => '5 meses', 'visitas' => 8500, 'equipo' => 3],
            ['nombre' => 'FlexFood',        'pais' => 'España',     'flag' => '🇪🇸', 'cat' => 'restaurant', 'badge' => 'SaaS',           'url' => 'https://flexfood.es',         'desc' => 'SaaS de gestión integral para restaurantes en España. Comandas, cocina, mesas, pagos y delivery en una sola plataforma.',                'techs' => ['Laravel', 'Vue.js', 'WebSockets', 'MySQL'], 'estado' => 'en_vivo', 'destacado' => false, 'duracion' => '7 meses', 'visitas' => 22000, 'equipo' => 4],
            ['nombre' => 'Calendarix',      'pais' => 'Uruguay',    'flag' => '🇺🇾', 'cat' => 'booking',    'badge' => 'SaaS Reservas',  'url' => 'https://calendarix.uy',       'desc' => 'Sistema SaaS de agenda online para profesionales. Más de 180 negocios activos gestionando citas y cobros integrados.',                    'techs' => ['Laravel', 'Vue.js', 'Stripe'], 'estado' => 'en_vivo', 'destacado' => true,  'duracion' => '4 meses', 'visitas' => 35000, 'equipo' => 3, 'logo' => '/images/logo.png'],
            ['nombre' => 'Montano & Co',    'pais' => 'Rep. Dom.',  'flag' => '🇩🇴', 'cat' => 'legal',      'badge' => 'Plataforma Legal','url' => 'https://montanoandco.net',    'desc' => 'Plataforma corporativa para firma de abogado penalista. Diseño profesional, minimalista, optimizado para conversión de clientes.',       'techs' => ['Laravel', 'Tailwind CSS'], 'estado' => 'en_vivo', 'destacado' => false, 'duracion' => '1 mes', 'visitas' => 4200, 'equipo' => 2],
        ];

        // Resto de proyectos (más simples, sin meta-data extra)
        $extra = [
            ['nombre' => 'Electralhome',    'pais' => 'Ecuador',    'flag' => '🇪🇨', 'cat' => 'ecommerce',  'badge' => 'E-commerce'],
            ['nombre' => 'Offiesco LATAM',  'pais' => 'Colombia',   'flag' => '🇨🇴', 'cat' => 'admin',      'badge' => 'ERP'],
            ['nombre' => 'BeTogether',      'pais' => 'Colombia',   'flag' => '🇨🇴', 'cat' => 'tech',       'badge' => 'SaaS'],
            ['nombre' => 'EliteCloser',     'pais' => 'Argentina',  'flag' => '🇦🇷', 'cat' => 'admin',      'badge' => 'CRM'],
            ['nombre' => 'Esnova',          'pais' => 'Colombia',   'flag' => '🇨🇴', 'cat' => 'ecommerce',  'badge' => 'E-commerce'],
            ['nombre' => 'Academia GVA',    'pais' => 'México',     'flag' => '🇲🇽', 'cat' => 'tech',       'badge' => 'LMS'],
            ['nombre' => 'CleanMe',         'pais' => 'Australia',  'flag' => '🇦🇺', 'cat' => 'booking',    'badge' => 'Servicios'],
            ['nombre' => 'Guillen Cleaning','pais' => 'EE.UU.',     'flag' => '🇺🇸', 'cat' => 'booking',    'badge' => 'Servicios'],
            ['nombre' => 'Innovatech',      'pais' => 'Colombia',   'flag' => '🇨🇴', 'cat' => 'admin',      'badge' => 'Intranet'],
            ['nombre' => 'Bingo Riffy',     'pais' => 'Colombia',   'flag' => '🇨🇴', 'cat' => 'tech',       'badge' => 'Gaming'],
            ['nombre' => 'Navasan',         'pais' => 'México',     'flag' => '🇲🇽', 'cat' => 'admin',      'badge' => 'ERP'],
        ];

        $orden = 1;
        foreach ($proyectos as $p) {
            Proyecto::updateOrCreate(
                ['slug' => Str::slug($p['nombre'])],
                [
                    'nombre'              => $p['nombre'],
                    'pais'                => $p['pais'],
                    'bandera_emoji'       => $p['flag'],
                    'categoria'           => $p['cat'],
                    'badge_text'          => $p['badge'],
                    'descripcion'         => $p['desc'],
                    'url'                 => $p['url'],
                    'logo'                => $p['logo'] ?? null,
                    'tecnologias'         => $p['techs'],
                    'estado'              => $p['estado'],
                    'destacado'           => $p['destacado'],
                    'duracion_desarrollo' => $p['duracion'],
                    'visitas_mensuales'   => $p['visitas'],
                    'equipo_size'         => $p['equipo'],
                    'orden'               => $orden++,
                    'activo'              => true,
                ]
            );
        }

        foreach ($extra as $p) {
            Proyecto::updateOrCreate(
                ['slug' => Str::slug($p['nombre'])],
                [
                    'nombre'        => $p['nombre'],
                    'pais'          => $p['pais'],
                    'bandera_emoji' => $p['flag'],
                    'categoria'     => $p['cat'],
                    'badge_text'    => $p['badge'],
                    'descripcion'   => 'Proyecto en producción para cliente en ' . $p['pais'] . '.',
                    'url'           => null,
                    'logo'          => null,
                    'tecnologias'   => ['Laravel', 'Vue.js', 'MySQL'],
                    'estado'        => 'en_vivo',
                    'destacado'     => false,
                    'orden'         => $orden++,
                    'activo'        => true,
                ]
            );
        }

        $total = count($proyectos) + count($extra);
        $this->command->info("✅ HomeDevSeeder: {$total} proyectos cargados (9 con meta-data completa).");
    }
}
