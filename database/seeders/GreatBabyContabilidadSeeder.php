<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\ProjectArea;
use App\Models\User;
use App\Services\DevNotifier;
use Illuminate\Database\Seeder;

/**
 * Carga el modulo de CONTABILIDAD (requerimientos de Silvia, CONT-1..13) en el
 * proyecto existente "Great Baby" (ERP), asignado a Yeniffer Palomino y bajo el
 * area "Contabilidad".
 *
 * Cada tarea trae subtareas + descripcion (cita de Silvia + ruta/archivo).
 * Fechas repartidas: jue 22-10, vie 23-10 y lun 26-10.
 *
 * NO crea el proyecto: lo resuelve por nombre. Idempotente: una tarea que ya
 * exista (por titulo) no se vuelve a crear; el area se reutiliza.
 *
 * Requiere que la feature de areas este migrada (php artisan migrate).
 * Ejecutar en PROD:  php artisan db:seed --class=GreatBabyContabilidadSeeder --force
 */
class GreatBabyContabilidadSeeder extends Seeder
{
    /** Pon false si NO quieres el correo-resumen de aviso a Yeniffer. */
    private const NOTIFICAR = true;

    private const PROYECTO_LIKE = 'Great Baby%';
    private const DEV = 'Yeniffer Palomino';
    private const AREA = 'Contabilidad';

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

        if ($nuevos && self::NOTIFICAR) {
            app(DevNotifier::class)->tareasAsignadasLote($dev, $project, $nuevos);
            $this->command?->info('Correo-resumen enviado a '.$dev->email.' con '.count($nuevos).' tarea(s).');
        }

        $this->command?->info('Listo. Tareas nuevas: '.count($nuevos).'. Area: '.$area->nombre.' (id '.$area->id.').');
    }

    /** @return array<int, array{0:string,1:string,2:string,3:string,4:array<int,string>}> */
    private function tareas(): array
    {
        return [
            // ===== Jueves 2026-10-22 =====
            [
                'CONT-1 - Autorrenta y autorretencion en ventas', 'alta', '2026-10-22',
                "Silvia: 'Por ser persona juridica hacemos una autorrenta por cada facturacion que se emite... debe ir amarrada ahi tambien, asi como el IVA... va a la 13 como activo y a la 23 como pasivo.' Ruta: /app/cartera/retenciones (RetencionesController.php)",
                [
                    'Agregar tipo autorrenta y autorretencion a las retenciones',
                    'Amarrar el asiento al contabilizar la factura (cuenta 13 activo / 23 pasivo)',
                    'Agregar autorreteica por ciudad (Bucaramanga)',
                    'Activar/desactivar',
                ],
            ],
            [
                'CONT-2 - Retencion automatica en gastos por concepto', 'alta', '2026-10-22',
                "Silvia: 'Si cojo el arriendo que me practique la retencion de arriendos por el 3.5 y me la desglose; si es transporte, la de servicios del 1%... si me facturan antes de IVA un millon le practico, si son cincuenta mil no da la base.' Ruta: /app/gastos (GastosController.php)",
                [
                    'Que el gasto se desglose como una factura (gasto + IVA + retencion + salida de dinero)',
                    'Aplicar retencion segun concepto (arriendo 3.5%, servicios 1%, honorarios, comisiones)',
                    'Respetar base minima',
                    'Todo configurable',
                    'Agregar metodo de pago caja consignacion',
                ],
            ],
            [
                'CONT-3 - Pago/asiento amarrado a la factura', 'alta', '2026-10-22',
                "Silvia: 'Que yo elija el NIT del cliente y me despliegue las facturas que me debe y yo pueda escogerla... me hizo retencion del 2.5, me hizo reteica, y el automaticamente me lo va calculando.' Ruta: /app/contabilidad/asientos-manuales (AsientosManualesController.php) + /app/pagos",
                [
                    'Elegir tercero (NIT)',
                    'Listar sus facturas pendientes',
                    'Seleccionar la(s) que paga',
                    'Autocalcular retenciones (retefuente 2.5, reteica, etc.) y armar el asiento',
                ],
            ],
            [
                'CONT-4 - Estado de cuenta con historico de abonos', 'media', '2026-10-22',
                "Silvia: 'Que me salga el estado de cuenta de la factura, la fecha de generacion y la fecha en que me abono y el desglose de todos los abonos. El sistema actual solo me muestra el saldo vigente.' Ruta: mejorar /app/cartera/estado-cuenta/{contacto} (EstadoCuentaController) - hoy solo PDF de saldo",
                [
                    'Por cliente, listar cada factura con fecha de emision + cada abono (fecha y valor) + saldo',
                    'Exportar PDF/Excel',
                ],
            ],
            [
                'CONT-5 - Combos/kits y producto compuesto', 'media', '2026-10-22',
                "Silvia: 'Esos 100 productos, uno es un producto y son tres items que solo me genera un solo producto... venia la silla pero aparte me mandaron la esponjita para armarla.' Ruta: /app/catalogo/productos (ProductosController.php)",
                [
                    'Crear combo/kit (varios items = 1 producto de venta)',
                    'Manejar producto compuesto (ej. la factura trae 3 items que arman 1 producto)',
                    'Embalaje (cajas) ligado al producto',
                ],
            ],

            // ===== Viernes 2026-10-23 =====
            [
                'CONT-6 - Dropi ID en la ficha de producto', 'media', '2026-10-23',
                "Silvia/dev: 'Tenemos el ID de SIIGO, el ID del sistema... y tambien la de Dropi, que la maneja diferente... nos falta la parte de Dropi que todavia no la hemos puesto aqui en el ERP.' Ruta: /app/catalogo/productos",
                [
                    'Agregar campo ID Dropi a la ficha (junto al ID SIIGO e ID interno)',
                    'Que la trazabilidad caiga al mismo producto venga de donde venga',
                ],
            ],
            [
                'CONT-7 - Cuentas por aclarar (flujo bancario) [crear]', 'alta', '2026-10-23',
                "Silvia: 'Los ingresos entran como cuentas por aclarar... un buscador inteligente, buscar por valor o por banco y asignarlo... si me consignan sin factura, que haga la factura electronica de consumidor final automatica.' Ruta: crear dentro de Conciliacion (hoy existe /app/contabilidad/siigo/conciliacion, falta el modulo de banco/cuentas por aclarar)",
                [
                    'Registrar movimiento bancario diario',
                    'Los ingresos entran como cuenta por aclarar',
                    'Buscador inteligente (por valor/banco) para asignar el pago a cliente/factura',
                    'Consumidor final automatico cuando la consignacion no tiene factura',
                    'Las no identificadas al cierre de mes quedan pendientes / se trasladan a SIIGO',
                ],
            ],
            [
                'CONT-8 - Caja (menor/general/consignacion) + cierre diario [crear]', 'alta', '2026-10-23',
                "Silvia: 'Tenemos caja menor, caja general y una caja consignacion que desgloso para efectos fiscales... hacemos traslados de general a consignacion... y el cierre de caja se hace diario.' Ruta: crear /app/contabilidad/caja (no existe ruta de caja/arqueo)",
                [
                    'Cajas menor, general y consignacion (fiscal)',
                    'Traslados entre cajas',
                    'Cierre de caja diario (gastos + efectivo restante)',
                    'Gastos hormiga (quien autorizo, quien gasto, vale/soporte)',
                ],
            ],
            [
                'CONT-9 - Balance de comprobacion configurable', 'media', '2026-10-23',
                "Silvia: 'Me ayudas con como se hace el balance... que cuentas son activo, pasivo y patrimonio, mirandolas en el PUC, y lo dejamos configurado para que se pinte la informacion.' Ruta: /app/contabilidad/balance-general (ContabilidadExtrasController) + plan de cuentas",
                [
                    'Mapear cada cuenta del PUC a su naturaleza (activo/pasivo/patrimonio/resultado) para que el balance se pinte solo',
                    'Dejarlo configurable',
                ],
            ],

            // ===== Lunes 2026-10-26 =====
            [
                'CONT-10 - Comisiones por vendedor parametrizables', 'media', '2026-10-26',
                "Silvia: 'Uno tiene el 5% dependiendo la regla de negocio... tienen regla tanto en recaudo como en venta... la meta mensual y el bono si cumple tanto porcentaje.' Ruta: /app/cartera/comisiones (ComisionesController.php) (ya tiene /config)",
                [
                    'Parametrizar por vendedor % por venta y/o recaudo, meta mensual y bono si cumple %',
                    'Liquidar por periodo',
                    '(Pendiente reunion con comercial para las reglas exactas)',
                ],
            ],
            [
                'CONT-11 - Multimoneda (dolares) en pagos a proveedor', 'media', '2026-10-26',
                "Silvia: 'Pagos de proveedor del extranjero en dolares... el banco me emite el comprobante de la tasa del dia... a las 10:34 lo compre a 3.100... que sea configurable la moneda y traiga a como esta el dolar.' Ruta: /app/cartera/pagos-proveedor (PagosProveedorController.php)",
                [
                    'Permitir pago en USD con tasa del dia editable',
                    'Guardar el comprobante del banco de la tasa negociada',
                    'Registrar el equivalente en pesos',
                ],
            ],
            [
                'CONT-12 - PUC: traer de SIIGO (sync inbound) + subcuentas internas', 'media', '2026-10-26',
                "Silvia: 'Tu lo creas en SIIGO y le das actualizar el sistema; el sistema dice yo no tengo estas cuentas, me las traigo y las pinta aca... podemos manejar el PUC de SIIGO y anexar las subcuentas adicionales.' Ruta: /app/contabilidad/plan-cuentas (PlanCuentasController.php)",
                [
                    "Boton 'actualizar desde SIIGO' que trae las cuentas que no estan y las pinta en el PUC",
                    'Permitir anexar subcuentas internas',
                    'Que el PUC se refleje en ambos lados',
                ],
            ],
            [
                'CONT-13 - Reportes a la medida de Silvia', 'media', '2026-10-26',
                "Silvia: 'Te paso los formatos de los informes que manejo, con las columnas y dos ejemplitos, y el sistema me los saca asi y guarda ese formato.' Ruta: /app/contabilidad/reportes (ContabilidadController.php). Depende de: formatos que envia Silvia.",
                [
                    'Recibir los formatos que Silvia enviara (3 columnas de titulos + 2 ejemplos por informe)',
                    'Dejarlos configurados para descargar PDF/Excel',
                    'Incluir el estado de cuenta con abonos (CONT-4)',
                ],
            ],
        ];
    }
}
