<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\ProjectArea;
use App\Models\User;
use App\Services\DevNotifier;
use Illuminate\Database\Seeder;

/**
 * Carga el modulo de COMPRAS / IMPORTACIONES (requerimientos del cliente,
 * COMP-1..11) en el proyecto existente "Great Baby" (ERP), asignado a Samuel
 * barbosa y bajo el area "Compras".
 *
 * Cada tarea trae subtareas + descripcion (objetivo + cita del cliente + ruta +
 * criterio de aceptacion). Fechas repartidas: jue 22-10, vie 23-10 y lun 26-10.
 *
 * NO crea el proyecto: lo resuelve por nombre. Idempotente: una tarea que ya
 * exista (por titulo) no se vuelve a crear; el area se reutiliza.
 *
 * Requiere que la feature de areas este migrada (php artisan migrate).
 * Ejecutar en PROD:  php artisan db:seed --class=GreatBabyComprasSeeder --force
 */
class GreatBabyComprasSeeder extends Seeder
{
    /** Samuel no tiene email cargado: por defecto NO se notifica. */
    private const NOTIFICAR = false;

    private const PROYECTO_LIKE = 'Great Baby%';
    private const DEV = 'Samuel barbosa';
    private const AREA = 'Compras';

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
        }

        $this->command?->info('Listo. Tareas nuevas: '.count($nuevos).'. Area: '.$area->nombre.' (id '.$area->id.').');
    }

    /** @return array<int, array{0:string,1:string,2:string,3:string,4:array<int,string>}> */
    private function tareas(): array
    {
        return [
            // ===== Jueves 2026-10-22 =====
            [
                'COMP-1 - Armar el contenedor en el sistema con calculo de cubicaje', 'alta', '2026-10-22',
                "Objetivo: que Alejandro arme el contenedor dentro del ERP y el sistema le calcule automaticamente el metraje/cubicaje (tope 68 m3) a medida que agrega productos. Cliente: 'El tiene que armar su contenedor... que el sistema nos ayude a armarlo y de una vez mirar el cubicaje, la aduana, el metraje. El contenedor se hace en base a 68 metros, se escoge mercancia que solo ocupe 68.' Aceptacion: al armar un pedido, el sistema muestra el cubicaje acumulado y no deja cerrar por encima de 68 m3 sin confirmacion. Ruta: /app/compras/oc/nueva (ComprasController.php)",
                [
                    'En la orden de pedido, al agregar un item capturar cajas, piezas por caja y cubicaje de la caja',
                    'Mostrar un acumulado en vivo: metros usados vs 68 m3 y valor total',
                    'Avisar cuando se pasa del cubo o del valor minimo interno del contenedor',
                ],
            ],
            [
                'COMP-2 - Dashboard de sugerido de pedido (rotacion + alertas de stock)', 'alta', '2026-10-22',
                "Objetivo: que compras decida que reforzar con data real: productos mas movidos, baja rotacion y minimos/maximos de stock. Cliente: 'De acuerdo a los movimientos de cada referencia, que el sistema nos ofrezca un sugerido de pedido... un dashboard con los productos mas movidos y alertas de stock minimo y maximo para no pedir lo que todavia tiene stock.' Aceptacion: compras ve el top de rotacion + sugerido y recibe alerta cuando un producto baja de minimo. Ruta: crear (se apoya en /app/inventario/kardex)",
                [
                    'Panel con rotacion por producto (apoyado en el kardex) de los ultimos 3-4 contenedores',
                    'Sugerido de pedido (que reforzar) segun rotacion',
                    'Alertas de minimo/maximo para no pedir lo que aun tiene stock alto',
                ],
            ],
            [
                'COMP-3 - Pre-liquidacion del contenedor (antes de pedir)', 'alta', '2026-10-22',
                "Objetivo: estimar el costo en destino y la rentabilidad de un producto antes de comprarlo, con los gastos promedio. Cliente: 'Se hace una pre-liquidacion con unos promedios de gastos y se mira si el producto es factible en Colombia... yo pongo el precio, la cantidad por caja y el cubicaje y el me hace un promedio.' Aceptacion: con precio+cubicaje, el sistema devuelve el costo estimado en destino y si da el margen. Ruta: crear dentro de Importaciones (/app/compras/importacion)",
                [
                    'Capturar precio China + cantidad por caja + cubicaje',
                    'Aplicar gastos promedio (flete, aduana, comision) parametrizables',
                    'Arrojar costo estimado en Colombia + margen y exportar PDF/Excel',
                ],
            ],
            [
                'COMP-4 - Division Great Baby vs e-commerce (Dropi) en la orden', 'media', '2026-10-22',
                "Objetivo: marcar en la orden que cantidad va para Great Baby y cuanta para e-commerce/Dropi, para que al recibir Don Jorge sepa que separar. Cliente: 'Nosotros haciamos el Excel con dos hojas, una de Great Baby y otra de Dropi... pongamos una columna de ubicacion, solo van a ver dos: Great Baby y e-commerce... ya le aparece a Don Jorge: me llegaron 30 cajas pero 15 son para e-commerce, lo dejo aparte.' Aceptacion: una orden dividida 500/500 llega a recepcion marcada por destino y dispara el traslado a Aracely. Ruta: /app/compras/oc/nueva + /app/compras/recepcion/nueva",
                [
                    "Columna 'destino' (Great Baby / e-commerce) por item en la orden de compra",
                    'Al recibir, que a Don Jorge le aparezca la anotacion de cuantas cajas son de e-commerce',
                    'Generar alerta/traslado a Aracely para la mercancia de e-commerce',
                ],
            ],

            // ===== Viernes 2026-10-23 =====
            [
                'COMP-5 - Trazabilidad de los pagos del contenedor', 'media', '2026-10-23',
                "Objetivo: registrar y ver todos los costos de un contenedor (flete, transporte, zona franca, aduana) con sus soportes. Cliente: 'Se generan los pagos: flete maritimo, la OTM, el bodegaje en zona franca, el pago a la aduana, el transporte a Bucaramanga... tenemos carpetas, voy a buscar todos los gastos que genera un contenedor para ver las trazabilidades.' Aceptacion: cada contenedor muestra sus 5 costos con soporte y su costo total. Ruta: /app/compras/importacion (+ soportes)",
                [
                    'Por contenedor, registrar flete maritimo, OTM, bodegaje zona franca, aduana, transporte a bodega',
                    'Adjuntar soporte (PDF) de cada pago',
                    'Totalizar el costo real del contenedor (alimenta la liquidacion)',
                ],
            ],
            [
                'COMP-6 - Rastreo del contenedor por estados + alertas de llegada', 'alta', '2026-10-23',
                "Objetivo: seguir el contenedor por etapas (agua -> puerto -> zona franca -> bodega) con fechas y avisar a Don Jorge para preparar el descargue. Cliente: 'Saber data de cuanto lleva en agua, en puerto, en bodega... cuando ya esta en zona franca, que se diga ya esta en zona franca, y si no se cumple el tiempo que mande una alerta... le llega una alerta a Don Jorge para que se prepare.' Aceptacion: un contenedor avanza de estado con fecha y Don Jorge recibe el aviso de llegada estimada. Ruta: crear dentro de Importaciones",
                [
                    'Estados del contenedor con fecha de cada cambio (actualizacion manual por Liz/compras)',
                    'Fechas estimadas de llegada segun promedios por etapa',
                    'Alerta a Don Jorge con fecha/ventana estimada y cantidad de cajas para que prepare personal',
                ],
            ],
            [
                'COMP-7 - Alertas de vencimiento de pago a China (credito 90 dias)', 'alta', '2026-10-23',
                "Objetivo: avisar cuando se vence el pago a China antes de que empiece a generar interes. Cliente: 'Necesitamos esas alertas de cuando esta por vencerse tal factura... 10 dias antes alerta naranja, 3 dias antes roja... siempre son 90 dias despues de que parte el buque, luego cobra interes.' Aceptacion: una factura a 90 dias dispara naranja a 10 y roja a 3 dias del vencimiento. Ruta: /app/cartera/pagos-proveedor (+ panel de pendientes)",
                [
                    'Guardar la fecha de zarpe del buque y el plazo (90 dias)',
                    'Alerta naranja a 10 dias, roja a 3 dias del vencimiento',
                    "Que la alerta aparezca en 'Pendientes del dia' + la campanita",
                ],
            ],
            [
                'COMP-8 - Reglas de negociacion con China configurables (FOB/EXW/CIF, 30/70)', 'media', '2026-10-23',
                "Objetivo: parametrizar las condiciones de pago/negociacion con cada proveedor para que el sistema las aplique. Cliente: 'Con la fabrica se paga 30% antes de fabricar y 70% cuando esta fabricado... las negociaciones FOB, EXW y CIF... me mandas el proceso y el sistema lo corre, tu solo revisas y alimentas.' Aceptacion: al elegir un proveedor FOB 30/70, el sistema genera los hitos de pago correctos. Ruta: completar en proveedor/orden de compra (/app/compras)",
                [
                    'Config por proveedor: tipo (FOB / EXW / CIF) y esquema de pago (ej. 30% al fabricar, 70% al terminar; o 90 dias desde zarpe)',
                    'Que la orden calcule los hitos de pago segun la regla',
                    'Dejarlo editable por si cambia el proveedor/condicion',
                ],
            ],

            // ===== Lunes 2026-10-26 =====
            [
                'COMP-9 - Multimoneda (dolares) y FOB variable', 'media', '2026-10-26',
                "Objetivo: manejar ordenes y pagos en USD con tasa editable, porque el FOB fluctua. Cliente: 'El FOB es muy, muy variante, de acuerdo a como este la moneda... pagos de proveedor del extranjero en dolares, el banco me emite el comprobante de la tasa del dia.' Aceptacion: una OC/pago en USD guarda tasa + comprobante y muestra el valor en pesos. Ruta: /app/cartera/pagos-proveedor + ordenes de compra",
                [
                    'Moneda USD en orden/pago de proveedor con tasa del dia editable',
                    'Guardar el comprobante del banco de la tasa negociada',
                    'Registrar equivalente en pesos para contabilidad',
                ],
            ],
            [
                'COMP-10 - Etiquetas de importacion ancladas al producto', 'media', '2026-10-26',
                "Objetivo: que la etiqueta de importacion quede guardada en el producto y se traiga sola cuando se repite la referencia (sin buscarla a mano). Cliente: 'Subir la etiqueta anclada al producto y cuando se suba una orden y reconoce que es ese mismo producto, me traiga la etiqueta y me la ponga en el archivo... serian dos stickers, uno con la etiqueta y otro con el codigo de barras.' Aceptacion: al repetir una referencia, el sistema adjunta solo la etiqueta guardada. Ruta: /app/catalogo/productos + modulo de compras",
                [
                    'Subir y anclar la etiqueta (archivo) a la ficha del producto',
                    'Al subir una orden con misma referencia, traer la etiqueta automaticamente',
                    'Mostrar los dos stickers (etiqueta + codigo de barras) en el modulo de compras, con opcion de imprimir/nota a bodega',
                ],
            ],
            [
                'COMP-11 - Lectura de la factura final (costos e IVA)', 'alta', '2026-10-26',
                "Objetivo: cargar la factura/packing list final y que el sistema extraiga costos e IVA sin cruzar a mano. Cliente: 'Hariamos un lector de la factura para que saque los costos y los IVA, que todo sea minucioso con los valores reales de la factura... y no estar cruzando a mano.' Aceptacion: al cargar la factura final, el sistema arma la liquidacion con costos e IVA correctos. Ruta: crear/completar en Importaciones (recepcion/liquidacion)",
                [
                    'Importar la factura final (Excel/PDF) del contenedor',
                    'Extraer costos, cantidades e IVA por item con los valores reales',
                    'Dejar los soportes en la carpeta del contenedor para que contabilidad los tome',
                ],
            ],
        ];
    }
}
