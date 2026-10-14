<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Carga en el tablero del proyecto "Great Baby" SOLO las tareas PENDIENTES del
 * frente de integración con Dropi (las marcadas en progreso o por hacer; se
 * omiten las ya terminadas). Agrupadas por sección, con subtareas, asignadas a
 * Samuel barbosa.
 *
 * Idempotente: si el proyecto ya tiene tareas "Dropi · ..." no vuelve a crearlas.
 * Si el proyecto no existe, lo crea.
 *
 * Ejecutar: php artisan db:seed --class=GreatBabyDropiBoardSeeder
 */
class GreatBabyDropiBoardSeeder extends Seeder
{
    public function run(): void
    {
        $devNombre = 'Samuel barbosa';

        $project = InternalProject::where('nombre', 'like', '%Great Baby%')
            ->orWhere('nombre', 'like', '%GREAT BABY%')
            ->first();

        $dev = Developer::firstOrCreate(['nombre' => $devNombre]);
        $adminId = User::role('admin')->value('id');

        if (! $project) {
            $project = InternalProject::create([
                'nombre' => 'Great Baby ERP (Integración Dropi)',
                'cliente_nombre' => 'Great Baby',
                'fuente' => 'directo',
                'estado' => 'en_progreso',
                'moneda' => 'COP',
                'precio' => 0,
                'desarrollador_nombre' => $devNombre,
                'developer_id' => $dev->id,
                'desarrollador_moneda' => 'COP',
                'descripcion' => 'ERP Great Baby (importadora bebé). Frente: integración con Dropi (pedidos, alistamiento, empaque, devoluciones, wallet, sanciones y facturación a SIIGO).',
            ]);
            $this->command?->info("Proyecto creado: Great Baby ERP (id {$project->id}).");
        } else {
            $this->command?->info("Proyecto encontrado: «{$project->nombre}» (id {$project->id}).");
        }

        if ($project->tasks()->where('titulo', 'like', 'Dropi · %')->exists()) {
            $this->command?->warn('Ya existen las tareas «Dropi · ...» en este proyecto. No se crea nada.');

            return;
        }

        $project->equipo()->syncWithoutDetaching([$dev->id]);

        $orden = (int) $project->tasks()->where('columna', 'por_hacer')->max('orden');

        foreach ($this->tareas() as [$titulo, $prioridad, $descripcion, $subs]) {
            $task = $project->tasks()->create([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'columna' => 'por_hacer',
                'prioridad' => $prioridad,
                'developer_id' => $dev->id,
                'orden' => ++$orden,
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

        $this->command?->info("Tareas Dropi creadas: {$project->tasks()->where('titulo', 'like', 'Dropi · %')->count()} para {$devNombre}.");
    }

    /**
     * Solo lo PENDIENTE. Prefijo [En progreso] = estaba en curso; [Pendiente] = por hacer/bloqueado.
     *
     * @return array<int, array{0:string,1:string,2:string,3:array<int,string>}>
     */
    private function tareas(): array
    {
        return [
            ['Dropi · 1. Conexión / Integración', 'media',
                'Pendiente del frente de conexión con Dropi.',
                [
                    '[Pendiente] 1.5 Conector API/MCP oficial (reemplazar el web driver) — bloqueado (token no self-service)',
                ],
            ],
            ['Dropi · 3. Cortes / Alistamiento', 'media',
                'Pendientes de cortes y alistamiento.',
                [
                    '[En progreso] 3.3 Priorización por criterios (contado/local/aprobado, consecutivo, posición) — validar con la regla nueva de la capacitación',
                    '[En progreso] 3.4 Generación de tareas de alistamiento a la persona asignada',
                ],
            ],
            ['Dropi · 4. Empaque / Escaneo / Despacho', 'alta',
                'Pendientes de empaque, escaneo y despacho.',
                [
                    '[En progreso] 4.3 Descuento de inventario al empacar (mismo hueco de saldo que B2B)',
                    '[Pendiente] 4.4 Causar el costo al empacar/despachar Dropi (quedó fuera del PR #2)',
                    '[En progreso] 4.5 Capas de seguridad: control de usuario + GPS + trazabilidad del envío',
                    '[Pendiente] 4.6 Flujo "escanear guía -> facturar automático" — bloqueado (necesita más API Dropi)',
                ],
            ],
            ['Dropi · 5. Devoluciones', 'media',
                'Pendientes de devoluciones.',
                [
                    '[En progreso] 5.2 Reintegro de stock al recibir la devolución',
                    '[Pendiente] 5.3 Nota crédito por devolución',
                    '[En progreso] 5.4 Re-escaneo para reingreso a bodega',
                ],
            ],
            ['Dropi · 6. Wallet / Conciliación', 'media',
                'Pendiente de conciliación de wallet.',
                [
                    '[En progreso] 6.6 Conciliación 2 capas (guía<->billetera OK · billetera<->banco pendiente)',
                ],
            ],
            ['Dropi · 8. Sanciones / Alertas', 'media',
                'Pendientes de sanciones y alertas (Dropi avanzado).',
                [
                    '[En progreso] 8.1 Categorización de gastos wallet (indemnización / flete garantía / tarjeta)',
                    '[Pendiente] 8.2 "Cancelado por Dropi" alerta 48h (límite 72h)',
                    '[Pendiente] 8.3 Paquetes "por fuera" 15-20 días',
                    '[Pendiente] 8.4 Diferencia de precio / devoluciones esperadas',
                ],
            ],
            ['Dropi · 9. Facturación Dropi -> SIIGO', 'alta',
                'Pendientes de facturación de Dropi hacia SIIGO/DIAN.',
                [
                    '[Pendiente] 9.1 Remisión 1:1 por valor de proveedor',
                    '[Pendiente] 9.2 Consumidor final + comisión/sanciones',
                    '[Pendiente] 9.3 Export de factura Dropi a SIIGO/DIAN',
                ],
            ],
        ];
    }
}
