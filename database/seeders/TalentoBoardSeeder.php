<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Crea el proyecto interno "Sistema Integral de Gestión del Talento Humano y
 * Desempeño" (ERS-RH-001) y arma su tablero: 8 tareas (una por fase) con
 * subtareas detalladas, todo asignado a Yeniffer Palomino.
 *
 * Idempotente: si el proyecto ya existe (por nombre) no vuelve a crear nada.
 * Ejecutar: php artisan db:seed --class=TalentoBoardSeeder
 */
class TalentoBoardSeeder extends Seeder
{
    public function run(): void
    {
        $nombre = 'Sistema Integral de Gestión del Talento Humano y Desempeño';
        $devNombre = 'Yeniffer Palomino';

        if (InternalProject::where('nombre', $nombre)->exists()) {
            $this->command?->warn("Ya existe el proyecto «{$nombre}». No se crea nada.");

            return;
        }

        $dev = Developer::firstOrCreate(['nombre' => $devNombre]);
        $adminId = User::role('admin')->value('id');

        $project = InternalProject::create([
            'nombre' => $nombre,
            'cliente_nombre' => 'Gestión Humana / Gerencia',
            'fuente' => 'directo',
            'estado' => 'en_progreso',
            'moneda' => 'COP',
            'precio' => 4500000,
            'desarrollador_nombre' => $devNombre,
            'developer_id' => $dev->id,
            'desarrollador_moneda' => 'COP',
            'fecha_inicio' => Carbon::today()->toDateString(),
            'fecha_entrega' => Carbon::today()->addWeekdays(35)->toDateString(),
            'descripcion' => 'App web responsive para gestión integral de talento humano y desempeño. 11 módulos. Ref. ERS-RH-001 v1.0. Equipo: 2 devs + 1 QA, 32–35 días hábiles.',
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
            ['Fase 0 · Levantamiento y arquitectura', 'alta', 3,
                'Levantamiento funcional, definición de arquitectura y validación de la matriz de acceso (RBAC por ámbito), escalas (1–5) y ponderaciones. Base de todo el sistema. (3 días)',
                [
                    'Validar matriz de acceso RBAC por ámbito (Usuario + Rol + Área + Funcionalidad + Acción)',
                    'Definir escalas de evaluación (1–5) y ponderaciones con la contraparte',
                    'Diseñar arquitectura y motor de parametrización (sin tocar código)',
                    'Validación funcional de la Fase 0 con contraparte por área',
                ],
            ],
            ['Fase 1 · Núcleo: Seguridad + Configuración maestra', 'alta', 9,
                'Módulos 01 y 02 + motor de parametrización (seguridad, configuración maestra y reglas). (6 días)',
                [
                    'M01 · Autenticación, gestión de sesiones y contraseñas',
                    'M01 · RBAC por ámbito (Usuario + Rol + Área + Funcionalidad + Acción)',
                    'M01 · Gestión de usuarios y asociación con colaboradores/áreas',
                    'M02 · CRUD de áreas, colaboradores y perfiles de cargo',
                    'M02 · Catálogo de competencias (blandas y técnicas) con nivel esperado',
                    'Motor de parametrización (escalas, ponderaciones, períodos, reglas)',
                ],
            ],
            ['Fase 2 · Colaboradores + Auditoría', 'media', 12,
                'Módulo 03 (ficha de colaborador) y Módulo 11 (auditoría transversal). (3 días)',
                [
                    'M03 · Ficha de colaborador (área, cargo, perfil, jefe inmediato)',
                    'M03 · Estados e historial del colaborador',
                    'M11 · Bitácora de operaciones críticas (fecha/hora, usuario, módulo, acción, registro)',
                    'M11 · Auditoría transversal a todos los módulos',
                ],
            ],
            ['Fase 3 · Evaluación de desempeño (Módulo 04)', 'alta', 19,
                'Módulo 04: el corazón del sistema. Motor de cálculo ponderado, variables objetivas y evaluación de jefes. (7 días)',
                [
                    'M04 · Generación automática de evaluaciones por período',
                    'M04 · Escala configurable (1–5) + carga de competencias del perfil',
                    'M04 · Variables objetivas',
                    'M04 · Motor de cálculo ponderado',
                    'M04 · Evaluación de jefes + control de períodos y estados',
                    'M04 · Reglas de negocio',
                ],
            ],
            ['Fase 4 · Disciplinarios + Permisos', 'media', 24,
                'Módulos 05 y 06 con motor de aprobaciones parametrizable. (5 días)',
                [
                    'M05 · Llamados de atención, descargos y respuesta, sanciones',
                    'M05 · Flujo completo con estados, fechas, responsables y trazabilidad',
                    'M06 · Solicitud y consulta de permisos',
                    'M06 · Motor de aprobaciones parametrizable por niveles',
                    'M06 · Alimentación automática del ausentismo',
                ],
            ],
            ['Fase 5 · Notificaciones + Reportes', 'media', 28,
                'Módulos 09 y 07 (alertas centralizadas y reportes con exportación). (4 días)',
                [
                    'M09 · Motor centralizado de alertas (solicitudes, aprobaciones, evaluaciones, disciplinarios, permisos)',
                    'M09 · Destinatarios y reglas configurables',
                    'M07 · Reportes de desempeño, permisos y disciplina con filtros combinados',
                    'M07 · Exportación a Excel y PDF según rol',
                ],
            ],
            ['Fase 6 · Dashboard gerencial + Autogestión', 'media', 32,
                'Módulos 08 y 10 (capa analítica y acceso a Autogestión). (4 días)',
                [
                    'M08 · Dashboard con indicadores (organización, desempeño, disciplina, ausentismo, gestión)',
                    'M08 · Índice consolidado de desempeño + apoyo a bonificaciones',
                    'M10 · Integración / acceso a Autogestión desde la plataforma',
                ],
            ],
            ['Fase 7 · QA, UAT y despliegue', 'alta', 35,
                'Cierre: QA, pruebas de aceptación, capacitación, despliegue y entrega de código. (3 días)',
                [
                    'QA y pruebas internas',
                    'Pruebas de aceptación (UAT) con el cliente',
                    'Capacitación',
                    'Despliegue a producción',
                    'Documentación + entrega de código fuente',
                ],
            ],
        ];
    }
}
