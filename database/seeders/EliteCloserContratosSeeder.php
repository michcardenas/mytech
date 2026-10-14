<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Proyecto "Contratos Elite Closer Society LLC" (cliente Elite Closer Society
 * LLC, contacto Fede). Directo, 145 USD, asignado a Michael cardenas.
 * Trabajo: cambiar el texto de los contratos a "Elite Closer Society LLC" y
 * eliminar el artículo 5.3.
 *
 * Idempotente: si el proyecto ya existe (por nombre) no vuelve a crear nada.
 * Ejecutar: php artisan db:seed --class=EliteCloserContratosSeeder
 */
class EliteCloserContratosSeeder extends Seeder
{
    public function run(): void
    {
        $nombre = 'Contratos Elite Closer Society LLC';
        $devNombre = 'Michael cardenas';

        if (InternalProject::where('nombre', $nombre)->exists()) {
            $this->command?->warn("Ya existe el proyecto «{$nombre}». No se crea nada.");

            return;
        }

        $dev = Developer::firstOrCreate(['nombre' => $devNombre]);
        $adminId = User::role('admin')->value('id');

        $project = InternalProject::create([
            'nombre' => $nombre,
            'cliente_nombre' => 'Elite Closer Society LLC',
            'cliente_contacto' => 'Fede +54 9 11 6281-8988',
            'fuente' => 'directo',
            'estado' => 'en_progreso',
            'moneda' => 'USD',
            'precio' => 145,
            'desarrollador_nombre' => $devNombre,
            'developer_id' => $dev->id,
            'desarrollador_moneda' => 'USD',
            'descripcion' => 'Ajustes a los contratos: cambiar el texto / razon social a "Elite Closer Society LLC" y eliminar el articulo 5.3.',
        ]);

        $project->equipo()->syncWithoutDetaching([$dev->id]);

        foreach ($this->tareas() as $orden => [$titulo, $prioridad, $descripcion, $subs]) {
            $task = $project->tasks()->create([
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'columna' => 'por_hacer',
                'prioridad' => $prioridad,
                'developer_id' => $dev->id,
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

    /** @return array<int, array{0:string,1:string,2:string,3:array<int,string>}> */
    private function tareas(): array
    {
        return [
            ['Cambiar el texto de los contratos a Elite Closer Society LLC', 'alta',
                'Reemplazar la razon social / el nombre por "Elite Closer Society LLC" en todos los contratos.',
                [
                    'Reemplazar el nombre / razon social por "Elite Closer Society LLC" en todos los contratos',
                    'Revisar encabezados, pie de pagina y clausulas donde aparezca el nombre anterior',
                ],
            ],
            ['Eliminar el articulo 5.3 de los contratos', 'alta',
                'Eliminar el articulo 5.3: "Se pueden ofrecer reembolsos por los servicios ofrecidos en las sesiones de preguntas y respuestas en directo en la medida en que no se haya reservado o utilizado ninguna capacidad de servicio."',
                [
                    'Quitar el articulo 5.3 (reembolsos por sesiones de preguntas y respuestas en directo)',
                    'Renumerar / ajustar la numeracion posterior si aplica',
                ],
            ],
        ];
    }
}
