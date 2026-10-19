<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\User;
use App\Services\DevNotifier;
use Illuminate\Database\Seeder;

/**
 * Carga el modulo de GESTION HUMANA / seleccion de personal en el proyecto
 * existente "Great Baby" (ERP), asignado a Yeniffer Palomino.
 *
 * 9 tareas (8 secciones del flujo + transversales) con descripcion completa
 * por tarea y sus subtareas. Fechas distribuidas: mar 20-10, mie 21-10 y
 * jue 22-10 (limite final). Los items 🟠 "a negociar" van como subtareas
 * marcadas con "[A negociar]".
 *
 * NO crea el proyecto: lo resuelve por nombre. Idempotente: una tarea que ya
 * exista (por titulo) en el proyecto no se vuelve a crear.
 *
 * Ejecutar en PROD:  php artisan db:seed --class=GreatBabyGestionHumanaSeeder --force
 */
class GreatBabyGestionHumanaSeeder extends Seeder
{
    /** Pon false si NO quieres el correo-resumen de aviso a Yeniffer. */
    private const NOTIFICAR = true;

    private const PROYECTO_LIKE = 'Great Baby%';
    private const DEV = 'Yeniffer Palomino';

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
            $this->command?->info("Creada #{$task->id} [{$fecha}] «{$titulo}» ({$task->subtasks()->count()} subtareas).");
        }

        if ($nuevos && self::NOTIFICAR) {
            app(DevNotifier::class)->tareasAsignadasLote($dev, $project, $nuevos);
            $this->command?->info('Correo-resumen enviado a '.$dev->email.' con '.count($nuevos).' tarea(s).');
        }

        $this->command?->info('Listo. Tareas nuevas: '.count($nuevos).'. Proyecto id '.$project->id.'.');
    }

    /** @return array<int, array{0:string,1:string,2:string,3:string,4:array<int,string>}> */
    private function tareas(): array
    {
        return [
            // ---------- Martes 2026-10-20 (base + arranque del flujo) ----------
            [
                'Transversales (base del modulo de Gestion Humana)', 'alta', '2026-10-20',
                'Capa base que usan todas las etapas del modulo. Define los roles y permisos (Lider/Jefe de area y Gestion Humana; la Junta Directiva queda a negociar), las notificaciones automaticas ante cada cambio de estado, la bitacora de auditoria por candidato/proceso y la exportacion individual a PDF/Excel. Debe quedar lista antes del resto porque todo el flujo depende de ella.',
                [
                    'Roles/permisos: Lider/Jefe de area, Gestion Humana (Junta = a negociar)',
                    'Notificaciones en cambios de estado',
                    'Bitacora por candidato/proceso',
                    'Exportacion PDF/Excel individual',
                ],
            ],
            [
                'Requisicion de personal (solicitud con aprobacion)', 'alta', '2026-10-20',
                'Formulario con el que un Lider/Jefe de area solicita una contratacion: motivo, cargo, numero de vacantes, funciones, competencias, estudio/experiencia, salario, jefe solicitante y fecha. Incluye restriccion por rol (solo el lider puede crear), flujo de aprobacion con cambio de estado y notificacion automatica a Gestion Humana al crear y al aprobar. La segunda aprobacion de Junta Directiva (orden de contratacion) queda a negociar.',
                [
                    'Formulario de requisicion que llena el jefe/lider: motivo, cargo, n de vacantes, funciones, competencias, estudio/experiencia, salario, jefe solicitante, fecha',
                    "Restriccion por rol: solo 'Lider/Jefe de area' puede crear",
                    'Boton de aprobacion + cambio de estado',
                    'Notificacion a Gestion Humana al crear/aprobar',
                    '[A negociar] Segunda aprobacion de Junta Directiva (orden de contratacion)',
                ],
            ],
            [
                'Vacante y registro de fuente', 'media', '2026-10-20',
                'A partir de una requisicion aprobada se genera la vacante. Permite registrar la fuente de reclutamiento como lista/campo (caja de compensacion, redes, correo, referido) sin integraciones externas, y controlar el estado de la vacante (abierta/cerrada).',
                [
                    'Crear vacante desde la requisicion aprobada',
                    'Registro de fuente como lista/campo (caja de compensacion, redes, correo, referido) - sin integracion externa',
                    'Estado de la vacante (abierta/cerrada)',
                ],
            ],

            // ---------- Miercoles 2026-10-21 (seleccion y contratacion) ----------
            [
                'Recepcion de candidatos / HV', 'media', '2026-10-21',
                'Registro de aspirantes con sus datos y adjunto de hoja de vida, asociandolos a la vacante/requisicion correspondiente. Incluye un filtro inicial para descartar o hacer avanzar al candidato.',
                [
                    'Registrar aspirante con datos + adjuntar HV',
                    'Asociar el candidato a la vacante/requisicion',
                    'Filtro inicial (descartar / avanzar)',
                ],
            ],
            [
                'Pipeline de seleccion por etapas', 'alta', '2026-10-21',
                'Tablero de seleccion por etapas (Recibido -> Preseleccionado -> Entrevista jefe -> Entrevista psicologia -> Pruebas -> Validacion) con bitacora de quien movio a cada candidato y cuando. Registra la entrevista del jefe inmediato y la de psicologia (con resultado de pruebas), la valoracion final (apto / apto con restriccion / no apto) y la terna de finalistas que se presenta al jefe. Los formatos exactos de entrevistas/pruebas, la aprobacion por etapa y la vista movil del jefe quedan a negociar y dependen de los formatos que envia Jennifer.',
                [
                    'Etapas: Recibido -> Preseleccionado -> Entrevista jefe -> Entrevista psicologia -> Pruebas -> Validacion',
                    'Mover candidato entre etapas con bitacora (quien, cuando)',
                    'Registro de entrevista del jefe inmediato (campos + resultado)',
                    'Registro de entrevista de psicologia (campos + resultado de pruebas)',
                    'Valoracion final: apto / apto con restriccion / no apto',
                    'Terna: marcar los finalistas que se presentan al jefe',
                    '[A negociar] Formatos exactos de entrevistas/pruebas + aprobacion por etapa + vista movil del jefe (dependen de los formatos de Jennifer)',
                ],
            ],
            [
                'Contratacion: checklist documental + examen ocupacional', 'alta', '2026-10-21',
                'Paso previo a crear el empleado. Checklist de documentos del candidato (diploma, certificaciones, documento, etc.) con marca de verificacion y adjuntos, y registro del examen medico ocupacional con su aval (si/no). Incluye un gate: no se puede crear el empleado si falta documentacion o el examen.',
                [
                    'Checklist de documentos del candidato (diploma, certificaciones, documento...) con check + adjuntos',
                    'Examen medico ocupacional: estado + registro del aval (si/no) antes de crear el empleado',
                    'Gate: no se crea el empleado si falta documentacion/examen',
                ],
            ],

            // ---------- Jueves 2026-10-22 (limite final: alta + induccion + prueba) ----------
            [
                'Creacion del empleado + historia laboral', 'alta', '2026-10-22',
                'Crea el empleado reutilizando los datos del candidato seleccionado, arma su carpeta de historia laboral con documentos digitales adjuntos, genera el usuario del empleado al contratar (para que haga la induccion) y permite exportar la historia a PDF por empleado. La firma digital del contrato (token/correo) queda a negociar.',
                [
                    'Crear empleado reutilizando los datos del candidato seleccionado',
                    'Carpeta de historia laboral (documentos digitales adjuntos)',
                    'Crear usuario del empleado al contratar (para que haga la induccion)',
                    'Exportar historia a PDF (por empleado)',
                    '[A negociar] Firma digital del contrato (token/correo)',
                ],
            ],
            [
                'Induccion (agenda, plan, asistencia, evaluacion)', 'media', '2026-10-22',
                'Modulos de induccion corporativa con contenido en texto/imagen/video que el empleado recorre con su usuario y marca como finalizado. Cada modulo tiene una pregunta de validacion y se registra la asistencia/finalizacion (quien y cuando). Un mini-LMS completo (varios cursos por cargo con banco de preguntas) queda a negociar.',
                [
                    'Modulos de induccion corporativa (contenido: texto/imagen/video)',
                    "El empleado la ve con su usuario -> marca 'finalizado'",
                    'Pregunta de validacion por modulo',
                    'Registro de asistencia/finalizacion (quien, cuando)',
                    '[A negociar] Mini-LMS completo (varios cursos por cargo, banco de preguntas)',
                ],
            ],
            [
                'Periodo de prueba (evaluacion)', 'media', '2026-10-22',
                'Configura el periodo de prueba (2 meses) desde el ingreso y la evaluacion correspondiente mediante un formato con preguntas, con doble validacion del jefe de area y de Gestion Humana, y resultado superado / no superado.',
                [
                    'Configurar periodo de prueba (2 meses) desde el ingreso',
                    'Evaluacion de periodo de prueba (formato con preguntas)',
                    'Doble validacion: jefe de area + Gestion Humana',
                    'Resultado: superado / no superado',
                ],
            ],
        ];
    }
}
