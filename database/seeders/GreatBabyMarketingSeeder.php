<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\ProjectArea;
use App\Models\User;
use App\Services\DevNotifier;
use Illuminate\Database\Seeder;

/**
 * Carga el modulo de MARKETING (requerimientos del cliente, MKT-1..7) en el
 * proyecto existente "Great Baby" (ERP), asignado a Samuel barbosa y bajo el
 * area "Marketing".
 *
 * Cada tarea trae subtareas + descripcion (cita del cliente + ruta/archivo).
 * Fechas repartidas: jue 22-10, vie 23-10 y lun 26-10.
 *
 * NO crea el proyecto: lo resuelve por nombre. Idempotente: una tarea que ya
 * exista (por titulo) no se vuelve a crear; el area se reutiliza.
 *
 * Requiere que la feature de areas este migrada (php artisan migrate).
 * Ejecutar en PROD:  php artisan db:seed --class=GreatBabyMarketingSeeder --force
 */
class GreatBabyMarketingSeeder extends Seeder
{
    /**
     * Samuel no tiene email cargado: por defecto NO se notifica. Si en PROD si
     * tiene email y quieres avisarle, pon true.
     */
    private const NOTIFICAR = false;

    private const PROYECTO_LIKE = 'Great Baby%';
    private const DEV = 'Samuel barbosa';
    private const AREA = 'Marketing';

    public function run(): void
    {
        $project = InternalProject::where('nombre', 'like', self::PROYECTO_LIKE)->first();
        if (! $project) {
            $this->command?->error('No encontre un proyecto que empiece por «Great Baby». Abortado.');

            return;
        }

        $dev = Developer::where('nombre', self::DEV)->first();
        if (! $dev) {
            $this->command?->error('No existe el dev «'.self::DEV.'». Abortado.');

            return;
        }

        $area = $project->areas()->firstOrCreate(
            ['nombre' => self::AREA],
            ['color' => ProjectArea::colorSugerido($project), 'orden' => (int) $project->areas()->max('orden') + 1]
        );

        $adminId = User::role('admin')->value('id');
        $project->equipo()->syncWithoutDetaching([$dev->id]);

        $nuevos = [];

        foreach ($this->tareas() as [$titulo, $prioridad, $fecha, $descripcion, $subs]) {
            if ($project->tasks()->where('titulo', $titulo)->exists()) {
                $this->command?->warn("Ya existe: «{$titulo}». Se omite.");

                continue;
            }

            $task = $project->tasks()->create([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'columna' => 'por_hacer',
                'prioridad' => $prioridad,
                'developer_id' => $dev->id,
                'area_id' => $area->id,
                'fecha_limite' => $fecha,
                'orden' => (int) $project->tasks()->where('columna', 'por_hacer')->max('orden') + 1,
                'created_by' => $adminId,
            ]);

            foreach (array_values($subs) as $orden => $s) {
                $task->subtasks()->create([
                    'titulo' => $s,
                    'developer_id' => $dev->id,
                    'hecha' => false,
                    'orden' => $orden,
                ]);
            }

            $nuevos[] = $task->titulo;
            $this->command?->info("Creada #{$task->id} [{$fecha}] «{$titulo}» ({$task->subtasks()->count()} subt).");
        }

        if ($nuevos && self::NOTIFICAR && $dev->email) {
            app(DevNotifier::class)->tareasAsignadasLote($dev, $project, $nuevos);
            $this->command?->info('Correo-resumen enviado a '.$dev->email.'.');
        } elseif ($nuevos && self::NOTIFICAR) {
            $this->command?->warn('NOTIFICAR=true pero el dev no tiene email; no se envio correo.');
        }

        $this->command?->info('Listo. Tareas nuevas: '.count($nuevos).'. Area: '.$area->nombre.' (id '.$area->id.').');
    }

    /** @return array<int, array{0:string,1:string,2:string,3:string,4:array<int,string>}> */
    private function tareas(): array
    {
        return [
            // ===== Jueves 2026-10-22 =====
            [
                'MKT-1 - Ficha de producto: listo para publicar + publico (completar)', 'media', '2026-10-22',
                "Cliente: 'Siempre manejamos un botoncito de activar o desactivar; si lo desactivas, en el catalogo no se va a ver, pero tu si lo ves... hasta que no lo actives no se le va a ver a nadie.' Ruta: completar /app/marketing/producto/{id}/ficha (MarketingController.php - Ficha.vue)",
                [
                    "Agregar a la ficha publico objetivo y flag 'listo para publicar'",
                    'Que al activarlo el producto aparezca para Ventas (control activar/desactivar visibilidad)',
                ],
            ],
            [
                'MKT-2 - Nube / repositorio de creativos por contenedor (construir)', 'alta', '2026-10-22',
                "Cliente: 'Apenas llegamos al contenedor empezamos a hacer los creativos... hay posibilidad de que esos creativos se suban a alguna nube para que los chicos de dropshipping, como los vendedores, puedan descargarlo? Para que no este en Google Drive.' Ruta: crear, dentro del modulo Marketing (reusar el disco de archivos del ERP)",
                [
                    'Galeria de creativos ligada al producto/contenedor',
                    'Subir video/reel/imagenes (archivo liviano o enlace externo)',
                    'Permiso de descarga para vendedores y dropship',
                ],
            ],
            [
                'MKT-3 - Flujo de produccion de contenido (completar la parrilla)', 'media', '2026-10-22',
                "Cliente: 'Hoy tuvimos jornada de grabacion, manana edicion, el tercer dia entrega de ese contenido en las dos areas, mayorista y dropship... que tu lo subas una sola vez y digas esto lo puede ver esta persona, solo con permisos de ver.' Ruta: completar /app/marketing/parrilla (Parrilla.vue)",
                [
                    'En la parrilla, ligar el post al producto/contenedor y manejar etapas grabacion -> edicion -> entrega',
                    "Subir el material una sola vez con permisos 'solo ver' para mayorista y dropship",
                ],
            ],

            // ===== Viernes 2026-10-23 =====
            [
                'MKT-4 - Visibilidad de etapa / tablero por contenedor (construir vista)', 'alta', '2026-10-23',
                "Cliente: 'Para mi es importante saber en que parte va el proceso, porque a veces cuando ya llega a marketing ya paso mucho tiempo y el producto era valioso... que tu escojas contenedor X y salga la lista de los productos y digas con este le voy a hacer un video, un reel y cinco imagenes.' Ruta: crear (se apoya en Compras/Importaciones - ComprasController.php)",
                [
                    'Vista que muestre en que etapa va cada producto (compra -> contenedor -> publicacion)',
                    'Poder elegir un contenedor y ver la lista de productos que genero bodega',
                    'Por cada producto marcar que piezas lleva (video/reel/N imagenes)',
                ],
            ],
            [
                'MKT-5 - Muestras a marketing (verificar que este completo)', 'baja', '2026-10-23',
                "Cliente: 'Esa parte... donde tu la solicitas, se lista y queda el responsable, quien la recogio y quien la entrego y en que estado.' Ruta: verificar /app/marketing + /app/marketing/prestamo (MarketingPrestamosController.php - Panel.vue)",
                [
                    'Confirmar el flujo solicita -> aprueba -> responsable (quien autorizo / recogio / entrego) -> estado y observaciones',
                    'Notificar a marketing cuando este lista',
                ],
            ],

            // ===== Lunes 2026-10-26 =====
            [
                'MKT-6 - CRM: leads con fuente (verificar/completar)', 'media', '2026-10-26',
                "Cliente: 'Se puede mirar la fuente del lead? ...tiene fuente de WhatsApp, de pagina.' Ruta: verificar /app/crm (CrmController.php)",
                [
                    'Confirmar que el CRM registra la fuente del lead (WhatsApp / pagina / referido)',
                    'Si no, agregar el campo y ligar lead -> cotizacion',
                ],
            ],
            [
                'MKT-7 - Plantilla de factura editable (verificar)', 'baja', '2026-10-26',
                "Cliente: 'No la quiero asi, quiero cambiarle el color... la URL del logo, cuanto quieres de grande el logo, colores, los textos, todo lo disenan ustedes y pueden tener varias versiones.' Ruta: verificar /app/plantillas (PlantillasController.php)",
                [
                    'Confirmar que se puede cambiar logo (URL + tamano), colores, textos y firma',
                    'Dejar una predeterminada y tener varias versiones activables',
                ],
            ],
        ];
    }
}
