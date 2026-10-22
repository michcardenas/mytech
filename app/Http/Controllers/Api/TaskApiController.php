<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\BoardFiles;
use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\ProjectArea;
use App\Models\ProjectSubtask;
use App\Models\ProjectTask;
use App\Models\ProjectTaskFile;
use App\Services\DevNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * API REST de TAREAS del tablero (sobre proyectos internos existentes).
 * Protegida con la misma API key (Bearer / X-API-Key).
 *
 * Optimiza la carga del tablero: permite crear una o VARIAS tareas de golpe
 * (con subtareas), agrega automáticamente el dev al equipo del proyecto y le
 * envía UN solo correo-resumen por llamada (salvo "notificar": false).
 */
class TaskApiController extends Controller
{
    use BoardFiles;

    /** GET /api/internal-projects/{id}/tasks */
    public function index(string $id): JsonResponse
    {
        $project = InternalProject::findOrFail($id);
        $tasks = $project->tasks()->with(['subtasks', 'area'])->orderBy('columna')->orderBy('orden')->get()
            ->map(fn (ProjectTask $t) => $this->present($t));

        return response()->json(['total' => $tasks->count(), 'data' => $tasks]);
    }

    /**
     * POST /api/internal-projects/{id}/tasks
     * Cuerpo: una tarea suelta, o { "tareas": [ {...}, {...} ] }.
     * Cada tarea admite "subtareas": ["texto", ...] o [{"titulo": "...", "developer_id": N}].
     */
    public function store(Request $request, string $id): JsonResponse
    {
        $project = InternalProject::findOrFail($id);
        $notificar = $request->boolean('notificar', true);

        if ($request->has('tareas')) {
            $request->validate(['tareas' => 'required|array|min:1']);
            $lista = $request->input('tareas');
        } else {
            $lista = [$request->all()];
        }

        $creadas = [];
        $avisos = [];   // developer_id => [titulos...]

        foreach ($lista as $raw) {
            $data = Validator::make($raw, $this->taskRules(true))->validate();

            if (! empty($data['developer_id'])) {
                $project->equipo()->syncWithoutDetaching([$data['developer_id']]);
            }

            $columna = $data['columna'] ?? 'por_hacer';
            $task = $project->tasks()->create([
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'] ?? null,
                'columna' => $columna,
                'prioridad' => $data['prioridad'] ?? 'media',
                'developer_id' => $data['developer_id'] ?? null,
                'area_id' => $this->resolverAreaId($project, $raw),
                'fecha_limite' => $data['fecha_limite'] ?? null,
                'orden' => (int) $project->tasks()->where('columna', $columna)->max('orden') + 1,
            ]);

            foreach ($this->normalizarSubtareas($raw['subtareas'] ?? []) as $ordenS => $sub) {
                $task->subtasks()->create([
                    'titulo' => $sub['titulo'],
                    'developer_id' => $sub['developer_id'] ?? $task->developer_id,
                    'hecha' => false,
                    'orden' => $ordenS,
                ]);
            }

            $creadas[] = $this->present($task->load(['subtasks', 'area']));
            if ($task->developer_id) {
                $avisos[$task->developer_id][] = $task->titulo;
            }
        }

        if ($notificar) {
            foreach ($avisos as $devId => $titulos) {
                $dev = Developer::find($devId);
                if ($dev) {
                    app(DevNotifier::class)->tareasAsignadasLote($dev, $project, $titulos);
                }
            }
        }

        return response()->json([
            'message' => count($creadas).' tarea(s) creada(s).',
            'data' => $creadas,
        ], 201);
    }

    /** GET /api/project-tasks/{id} */
    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->present(ProjectTask::with(['subtasks', 'files', 'area'])->findOrFail($id))]);
    }

    /** PUT/PATCH /api/project-tasks/{id} */
    public function update(Request $request, string $id): JsonResponse
    {
        $task = ProjectTask::findOrFail($id);
        $data = $request->validate($this->taskRules(false));
        $notificar = $request->boolean('notificar', true);

        // Resolver area por nombre ("area") o id ("area_id") si vino en la peticion.
        if ($request->has('area') || array_key_exists('area_id', $data)) {
            $data['area_id'] = $this->resolverAreaId($task->project, $request->all());
        }
        unset($data['notificar'], $data['subtareas'], $data['area']);

        if (! empty($data['developer_id'])) {
            $task->project->equipo()->syncWithoutDetaching([$data['developer_id']]);
        }

        $devAnterior = $task->developer_id;
        $task->update($data);

        if ($notificar && $task->developer_id && $task->developer_id !== $devAnterior) {
            app(DevNotifier::class)->tareaAsignada($task);
        }

        return response()->json([
            'message' => 'Tarea actualizada.',
            'data' => $this->present($task->fresh()->load(['subtasks', 'area'])),
        ]);
    }

    /** DELETE /api/project-tasks/{id} */
    public function destroy(string $id): JsonResponse
    {
        ProjectTask::findOrFail($id)->delete();

        return response()->json(['message' => 'Tarea eliminada.']);
    }

    /** POST /api/project-tasks/{id}/subtasks */
    public function storeSubtask(Request $request, string $id): JsonResponse
    {
        $task = ProjectTask::findOrFail($id);
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'developer_id' => 'sometimes|nullable|exists:developers,id',
        ]);

        $subtask = $task->subtasks()->create([
            'titulo' => $data['titulo'],
            'developer_id' => $data['developer_id'] ?? $task->developer_id,
            'hecha' => false,
            'orden' => (int) $task->subtasks()->max('orden') + 1,
        ]);

        return response()->json(['message' => 'Subtarea creada.', 'data' => [
            'id' => $subtask->id, 'titulo' => $subtask->titulo, 'hecha' => (bool) $subtask->hecha,
        ]], 201);
    }

    /** PUT/PATCH /api/project-subtasks/{id} */
    public function updateSubtask(Request $request, string $id): JsonResponse
    {
        $subtask = ProjectSubtask::findOrFail($id);
        $data = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'hecha' => 'sometimes|boolean',
            'developer_id' => 'sometimes|nullable|exists:developers,id',
        ]);
        $subtask->update($data);

        return response()->json(['message' => 'Subtarea actualizada.', 'data' => [
            'id' => $subtask->id, 'titulo' => $subtask->titulo, 'hecha' => (bool) $subtask->hecha,
        ]]);
    }

    /** DELETE /api/project-subtasks/{id} */
    public function destroySubtask(string $id): JsonResponse
    {
        ProjectSubtask::findOrFail($id)->delete();

        return response()->json(['message' => 'Subtarea eliminada.']);
    }

    /** GET /api/project-tasks/{id}/files */
    public function indexFiles(string $id): JsonResponse
    {
        $task = ProjectTask::findOrFail($id);
        $files = $task->files->map(fn (ProjectTaskFile $f) => $this->presentFile($f));

        return response()->json(['total' => $files->count(), 'data' => $files]);
    }

    /**
     * POST /api/project-tasks/{id}/files
     * Multipart: campo "archivo" (requerido, <=20MB), "nombre" y "subido_por" opcionales.
     */
    public function storeFile(Request $request, string $id): JsonResponse
    {
        $task = ProjectTask::findOrFail($id);
        $file = $this->guardarTaskFile($request, $task, $request->input('subido_por'));

        return response()->json(['message' => 'Archivo adjuntado.', 'data' => $this->presentFile($file)], 201);
    }

    /** DELETE /api/project-task-files/{id} */
    public function destroyFile(string $id): JsonResponse
    {
        $this->borrarTaskFile(ProjectTaskFile::findOrFail($id));

        return response()->json(['message' => 'Archivo eliminado.']);
    }

    /** @return array<string, mixed> */
    private function presentFile(ProjectTaskFile $f): array
    {
        return [
            'id' => $f->id,
            'nombre' => $f->nombre,
            'tipo' => $f->tipo,
            'tamano' => (int) $f->tamano,
            'tamano_humano' => $f->tamano_formateado,
            'url' => Storage::disk('public')->url($f->archivo),
            'subido_por' => $f->subido_por,
            'created_at' => optional($f->created_at)->toDateTimeString(),
        ];
    }

    /**
     * Resuelve el area de una tarea a partir del payload: "area_id" (debe
     * pertenecer al proyecto) o "area" por nombre (la crea si no existe).
     * Devuelve null si no se indico area o si se mando vacia.
     */
    private function resolverAreaId(InternalProject $project, array $raw): ?int
    {
        return ProjectArea::resolverParaProyecto($project, $raw);
    }

    /** @return array<string, mixed> */
    private function taskRules(bool $creating): array
    {
        $req = $creating ? 'required' : 'sometimes';

        return [
            'titulo' => "{$req}|string|max:255",
            'descripcion' => 'sometimes|nullable|string',
            'columna' => ['sometimes', 'nullable', Rule::in(array_keys(ProjectTask::COLUMNAS))],
            'prioridad' => ['sometimes', 'nullable', Rule::in(array_keys(ProjectTask::PRIORIDADES))],
            'developer_id' => 'sometimes|nullable|exists:developers,id',
            'area_id' => 'sometimes|nullable|integer|exists:project_areas,id',
            'area' => 'sometimes|nullable|string|max:255',
            'fecha_limite' => 'sometimes|nullable|date',
            'subtareas' => 'sometimes|array',
            'notificar' => 'sometimes|boolean',
        ];
    }

    /**
     * @param  mixed  $subs
     * @return array<int, array{titulo:string, developer_id?:int|null}>
     */
    private function normalizarSubtareas($subs): array
    {
        $out = [];
        foreach ((array) $subs as $s) {
            if (is_string($s)) {
                $t = trim($s);
                if ($t !== '') {
                    $out[] = ['titulo' => $t];
                }
            } elseif (is_array($s) && ! empty($s['titulo'])) {
                $out[] = ['titulo' => (string) $s['titulo'], 'developer_id' => $s['developer_id'] ?? null];
            }
        }

        return array_values($out);
    }

    /** @return array<string, mixed> */
    private function present(ProjectTask $t): array
    {
        return [
            'id' => $t->id,
            'internal_project_id' => $t->internal_project_id,
            'titulo' => $t->titulo,
            'descripcion' => $t->descripcion,
            'columna' => $t->columna,
            'prioridad' => $t->prioridad,
            'developer_id' => $t->developer_id,
            'area_id' => $t->area_id,
            'area' => $t->relationLoaded('area') && $t->area
                ? ['id' => $t->area->id, 'nombre' => $t->area->nombre, 'color' => $t->area->color]
                : null,
            'fecha_limite' => optional($t->fecha_limite)->toDateString(),
            'subtareas' => $t->relationLoaded('subtasks')
                ? $t->subtasks->map(fn (ProjectSubtask $s) => [
                    'id' => $s->id, 'titulo' => $s->titulo, 'hecha' => (bool) $s->hecha, 'developer_id' => $s->developer_id,
                ])->all()
                : [],
            'archivos' => $t->relationLoaded('files')
                ? $t->files->map(fn (ProjectTaskFile $f) => $this->presentFile($f))->all()
                : [],
        ];
    }
}
