<?php

namespace App\Http\Controllers\Concerns;

use App\Models\InternalProject;
use App\Models\ProjectSubtask;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Lógica compartida (admin y portal del dev) para crear/editar
 * tareas y subtareas del tablero.
 */
trait BoardTasks
{
    protected function guardarTarea(Request $request, InternalProject $project, ?int $createdBy = null): void
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'columna' => ['nullable', Rule::in(array_keys(ProjectTask::COLUMNAS))],
            'prioridad' => ['nullable', Rule::in(array_keys(ProjectTask::PRIORIDADES))],
            'developer_id' => ['nullable', Rule::exists('internal_project_developer', 'developer_id')->where('internal_project_id', $project->id)],
            'fecha_limite' => 'nullable|date',
        ]);

        $columna = $data['columna'] ?? 'por_hacer';

        $project->tasks()->create([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'] ?? null,
            'columna' => $columna,
            'prioridad' => $data['prioridad'] ?? 'media',
            'developer_id' => $data['developer_id'] ?? null,
            'fecha_limite' => $data['fecha_limite'] ?? null,
            'orden' => (int) $project->tasks()->where('columna', $columna)->max('orden') + 1,
            'created_by' => $createdBy,
        ]);
    }

    protected function actualizarTarea(Request $request, ProjectTask $task): void
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'prioridad' => ['nullable', Rule::in(array_keys(ProjectTask::PRIORIDADES))],
            'developer_id' => ['nullable', Rule::exists('internal_project_developer', 'developer_id')->where('internal_project_id', $task->internal_project_id)],
            'fecha_limite' => 'nullable|date',
        ]);

        $task->update([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'] ?? null,
            'prioridad' => $data['prioridad'] ?? 'media',
            'developer_id' => $data['developer_id'] ?? null,
            'fecha_limite' => $data['fecha_limite'] ?? null,
        ]);
    }

    protected function guardarSubtarea(Request $request, ProjectTask $task): void
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:255',
            'developer_id' => ['nullable', Rule::exists('internal_project_developer', 'developer_id')->where('internal_project_id', $task->internal_project_id)],
        ]);

        $task->subtasks()->create([
            'titulo' => $data['titulo'],
            'developer_id' => $data['developer_id'] ?? null,
            'orden' => (int) $task->subtasks()->max('orden') + 1,
        ]);
    }

    protected function actualizarSubtarea(Request $request, ProjectSubtask $subtask): void
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'developer_id' => ['nullable', Rule::exists('internal_project_developer', 'developer_id')->where('internal_project_id', $subtask->task->internal_project_id)],
        ]);

        $subtask->update([
            'titulo' => ($data['titulo'] ?? null) ?: $subtask->titulo,
            'developer_id' => $data['developer_id'] ?? null,
        ]);
    }
}
