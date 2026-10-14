<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Crea el proyecto interno "GALAVIA — Plataforma de Pedagogía Musical" (cliente
 * Luis Gásser, Workana) y arma su tablero: 5 tareas (una por fase) con subtareas
 * detalladas, todo asignado a Samuel barbosa.
 *
 * Idempotente: si el proyecto ya existe (por nombre) no vuelve a crear nada.
 * Ejecutar: php artisan db:seed --class=GalaviaBoardSeeder
 */
class GalaviaBoardSeeder extends Seeder
{
    public function run(): void
    {
        $nombre = 'GALAVIA — Plataforma de Pedagogía Musical';
        $devNombre = 'Samuel barbosa';

        if (InternalProject::where('nombre', $nombre)->exists()) {
            $this->command?->warn("Ya existe el proyecto «{$nombre}». No se crea nada.");

            return;
        }

        $dev = Developer::firstOrCreate(['nombre' => $devNombre]);
        $adminId = User::role('admin')->value('id');

        $project = InternalProject::create([
            'nombre' => $nombre,
            'cliente_nombre' => 'Luis Gásser',
            'fuente' => 'workana',
            'estado' => 'en_progreso',
            'moneda' => 'EUR',
            'precio' => 2400,
            'desarrollador_nombre' => $devNombre,
            'developer_id' => $dev->id,
            'desarrollador_moneda' => 'COP',
            'fecha_inicio' => Carbon::today()->toDateString(),
            'fecha_entrega' => Carbon::today()->addWeekdays(34)->toDateString(),
            'url_produccion' => 'https://galavia.es',
            'descripcion' => 'Web de pedagogía musical a la medida en Laravel (Blade + Vue + Filament + Cashier/Stripe + spatie). Repositorio de ~1.000 obras (PDF/MIDI/MP3) articulado por el libro de Armonía (166 págs). Suscripción anual Stripe. 5 fases, ~28-34 días. Ref. propuesta Workana aceptada.',
        ]);

        $project->equipo()->syncWithoutDetaching([$dev->id]);

        foreach ($this->tareas() as $orden => [$titulo, $prioridad, $dias, $descripcion, $subs]) {
            $task = $project->tasks()->create([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'columna' => 'por_hacer',
                'prioridad' => $prioridad,
                'developer_id' => $dev->id,
                'fecha_limite' => Carbon::today()->addWeekdays($dias)->toDateString(),
                'orden' => $orden,
                'created_by' => $adminId,
            ]);

            foreach (array_values($subs) as $ordenS => $s) {
                $task->subtasks()->create([
                    'titulo' => $s,
                    'developer_id' => $dev->id,
                    'hecha' => false,
                    'orden' => $ordenS,
                ]);
            }
        }

        $this->command?->info("Proyecto id {$project->id} creado con {$project->tasks()->count()} tareas para {$devNombre}.");
    }

    /** @return array<int, array{0:string,1:string,2:int,3:string,4:array<int,string>}> */
    private function tareas(): array
    {
        return [
            ['Fase 1 · Base, datos y panel admin (Hito 1)', 'alta', 5,
                'Infraestructura, modelo de datos y panel administrable. (4-5 días). DoD: admin crea una obra y aparece en su sección; sitio navegable con dominio + SSL; seeders corren limpio.',
                [
                    'Bootstrap Laravel 12 en Hostinger + GALAVIA.es + SSL + staging + Git + Vite',
                    'Auth + roles spatie (invitado / suscriptor / admin)',
                    'Migraciones + seeders (secciones, posiciones, roles, usuario admin)',
                    'Filament: CRUD Secciones, Posiciones y Obras (subida PDF/MID/MP3, código, flag suscriptor)',
                    'Filament: gestión de ajustes/textos (Acerca de mí, intros, cuota)',
                    'Frontend: layout base (tipografía Tinos, colores), menús + submenús, plantillas sección/obra',
                    'Cargar 3-5 obras reales del Dropbox como prueba del modelo (T.0.5, M.10.2, M.9.2)',
                ],
            ],
            ['Fase 2 · Libro «Armonía» a la web (núcleo)', 'alta', 15,
                'Conversión fiel del libro de Armonía y su navegación interna. (8-10 días). DoD: libro completo navegable; clic en cita abre la obra; clic en § navega y permite volver; formato fiel al Word.',
                [
                    'Conversión Word→HTML de las 166 págs (encabezados, sangrías, notas al pie) a libro_paginas',
                    'Integrar ~170 jpeg de ejemplos con estilo homogéneo (ref. Ej.17); tablas como imagen fiel',
                    'Fuente de notación para sostenidos/bemoles y acordes (Tabla nº5)',
                    'Citas a obras enlazadas (pivot obra_cita)',
                    '§ como enlaces con ida y vuelta (ancla + "volver al punto de lectura")',
                    'Índice del libro navegable',
                    'Filament: editor de páginas del libro (gestionar HTML, § y citas)',
                ],
            ],
            ['Fase 3 · Repositorio, visor y reproductores', 'alta', 23,
                'Servir obras protegidas + importador masivo + visor PDF y reproductores. (7-8 días). DoD: una obra abre partitura + zoom, MIDI (mezclador) y MP3; el importador carga un lote real y Luis puede repetirlo con la guía.',
                [
                    'Backend: endpoints de obra + servir archivos protegidos (stream con gate de rol)',
                    'Importador masivo CSV + ZIP mapeado por código (idempotente + validación + log)',
                    'Vue: visor PDF con zoom',
                    'Vue: reproductor MIDI (velocidad + mezclador volumen/silencio/instrumento por pista + percusión, balance CC7)',
                    'Vue: reproductor MP3 (velocidad + bucles)',
                    'Acceso a los archivos desde índices, libro y ficha de obra',
                ],
            ],
            ['Fase 4 · Suscriptores y pagos', 'media', 29,
                'Membresía anual Stripe + gate de contenido para suscriptores. (5-6 días). DoD: suscriptor de prueba paga (Stripe test), accede y descarga; usuario libre no puede; renovación/caducidad cambian el rol.',
                [
                    'Registro / login',
                    'Cashier + Stripe (suscripción anual ~25€) + webhooks con verificación de firma',
                    'Rol suscriptor según estado + gate en contenido y descargas solo_suscriptores (signed URLs temporales)',
                    'Frontend: planes/checkout + "mi cuenta/suscripción" + secciones "Contenido para suscriptores"',
                    'Admin: gestión de usuarios y suscripciones',
                ],
            ],
            ['Fase 5 · Forum, ajustes y publicación', 'media', 34,
                'Foro de comunidad, rendimiento, SEO, pruebas y publicación. (4-5 días). DoD: foro operativo, sitio responsive y veloz, documentación entregada, GALAVIA.es en producción.',
                [
                    'Forum (hilos/mensajes + moderación en admin)',
                    'Responsive + rendimiento (caché, compresión, lazy-load de los 3,2GB, headers de caché en media)',
                    'SEO básico (meta, sitemap, URLs limpias)',
                    'Pruebas E2E',
                    'Guía de autogestión (importar obras, editar libro, gestionar suscriptores)',
                    'Publicación en producción (GALAVIA.es)',
                ],
            ],
        ];
    }
}
