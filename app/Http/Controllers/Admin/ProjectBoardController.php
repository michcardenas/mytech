<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\BoardFiles;
use App\Http\Controllers\Concerns\BoardTasks;
use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\InternalProject;
use App\Models\ProjectFile;
use App\Models\ProjectSubtask;
use App\Models\ProjectTask;
use App\Models\ProjectTaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectBoardController extends Controller
{
    use BoardFiles;
    use BoardTasks;

    /** Incluye al dev principal (developer_id) dentro del equipo si aún no está. */
    private function sincronizarEquipoPrincipal(InternalProject $project): void
    {
        if ($project->developer_id && ! $project->equipo()->where('developers.id', $project->developer_id)->exists()) {
            $project->equipo()->attach($project->developer_id);
        }
    }

    /* ===================== Tablero general (multi-proyecto) ===================== */

    public function global(Request $request)
    {
        $devId = $request->integer('dev') ?: null;

        $query = ProjectTask::with(['project:id,nombre,cliente_nombre', 'developer', 'subtasks'])
            ->orderBy('orden')->orderBy('id');

        if ($devId) {
            $query->forDeveloper($devId);
        }

        $tasks = $query->get();

        return view('admin.internal-projects.board-global', [
            'columnas' => ProjectTask::COLUMNAS,
            'prioridades' => ProjectTask::PRIORIDADES,
            'tareasPorColumna' => $tasks->groupBy('columna'),
            'tasks' => $tasks,
            'devs' => Developer::orderBy('nombre')->get(),
            'devId' => $devId,
            'esAdmin' => true,
        ]);
    }

    /* ===================== Tablero ===================== */

    public function show(InternalProject $internal_project)
    {
        $project = $internal_project;
        $this->sincronizarEquipoPrincipal($project);

        $project->load([
            'equipo',
            'files',
            'tasks.developer',
            'tasks.subtasks.developer',
            'tasks.files',
        ]);

        $devsDisponibles = Developer::whereNotIn('id', $project->equipo->pluck('id'))
            ->orderBy('nombre')->get();

        return view('admin.internal-projects.board', [
            'project' => $project,
            'columnas' => ProjectTask::COLUMNAS,
            'prioridades' => ProjectTask::PRIORIDADES,
            'tareasPorColumna' => $project->tasks->groupBy('columna'),
            'equipo' => $project->equipo,
            'devsDisponibles' => $devsDisponibles,
            'documentos' => $project->files,
            'esAdmin' => true,
            'puedeEditar' => true,
        ]);
    }

    /* ===================== Tareas ===================== */

    public function storeTask(Request $request, InternalProject $internal_project)
    {
        $this->guardarTarea($request, $internal_project, Auth::id());

        return redirect()->route('admin.internal-projects.board', $internal_project)->with('success', 'Tarea creada.');
    }

    public function updateTask(Request $request, ProjectTask $task)
    {
        $this->actualizarTarea($request, $task);

        return redirect()->route('admin.internal-projects.board', $task->project)->with('success', 'Tarea actualizada.');
    }

    public function destroyTask(ProjectTask $task)
    {
        $project = $task->project;
        $task->delete();

        return redirect()->route('admin.internal-projects.board', $project)->with('success', 'Tarea eliminada.');
    }

    /** Mover tarea de columna y reordenar. Responde JSON. */
    public function moveTask(Request $request, ProjectTask $task)
    {
        $data = $request->validate([
            'columna' => ['required', Rule::in(array_keys(ProjectTask::COLUMNAS))],
            'ids' => 'sometimes|array',
            'ids.*' => 'integer',
        ]);

        $task->update(['columna' => $data['columna']]);

        if (! empty($data['ids'])) {
            foreach ($data['ids'] as $orden => $id) {
                ProjectTask::where('internal_project_id', $task->internal_project_id)
                    ->where('id', $id)
                    ->update(['orden' => $orden]);
            }
        }

        return response()->json(['ok' => true, 'columna' => $task->columna]);
    }

    /* ===================== Subtareas ===================== */

    public function storeSubtask(Request $request, ProjectTask $task)
    {
        $this->guardarSubtarea($request, $task);

        return back()->with('success', 'Subtarea agregada.');
    }

    public function updateSubtask(Request $request, ProjectSubtask $subtask)
    {
        $this->actualizarSubtarea($request, $subtask);

        return back()->with('success', 'Subtarea actualizada.');
    }

    public function destroySubtask(ProjectSubtask $subtask)
    {
        $subtask->delete();

        return back()->with('success', 'Subtarea eliminada.');
    }

    /** Marcar/desmarcar subtarea. Responde JSON. */
    public function toggleSubtask(ProjectSubtask $subtask)
    {
        $subtask->update(['hecha' => ! $subtask->hecha]);
        $task = $subtask->task->load('subtasks');

        return response()->json(['ok' => true, 'hecha' => $subtask->hecha, 'progreso' => $task->progreso]);
    }

    /* ===================== Equipo ===================== */

    public function addTeam(Request $request, InternalProject $internal_project)
    {
        $data = $request->validate(['developer_id' => 'required|exists:developers,id']);

        $internal_project->equipo()->syncWithoutDetaching([$data['developer_id']]);

        return back()->with('success', 'Desarrollador agregado al equipo.');
    }

    public function removeTeam(InternalProject $internal_project, Developer $developer)
    {
        $internal_project->equipo()->detach($developer->id);
        $internal_project->tasks()->where('developer_id', $developer->id)->update(['developer_id' => null]);

        return back()->with('success', 'Desarrollador quitado del equipo.');
    }

    /* ===================== Enlaces importantes ===================== */

    public function linksUpdate(Request $request, InternalProject $internal_project)
    {
        $data = $request->validate([
            'repo_url' => 'nullable|url|max:255',
            'url_produccion' => 'nullable|url|max:255',
            'url_pruebas' => 'nullable|url|max:255',
        ]);

        $internal_project->update($data);

        return back()->with('success', 'Enlaces del proyecto actualizados.');
    }

    /* ===================== Documentos ===================== */

    public function storeProjectFile(Request $request, InternalProject $internal_project)
    {
        $this->guardarProjectFile($request, $internal_project);

        return back()->with('success', 'Documento del proyecto subido.');
    }

    public function destroyProjectFile(ProjectFile $file)
    {
        $this->borrarProjectFile($file);

        return back()->with('success', 'Documento eliminado.');
    }

    public function storeTaskFile(Request $request, ProjectTask $task)
    {
        $this->guardarTaskFile($request, $task, Auth::user()->name ?? 'Admin');

        return back()->with('success', 'Archivo adjuntado a la tarea.');
    }

    public function destroyTaskFile(ProjectTaskFile $taskFile)
    {
        $this->borrarTaskFile($taskFile);

        return back()->with('success', 'Archivo eliminado.');
    }
}
