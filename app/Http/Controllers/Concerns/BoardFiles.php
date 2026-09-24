<?php

namespace App\Http\Controllers\Concerns;

use App\Models\InternalProject;
use App\Models\ProjectFile;
use App\Models\ProjectTask;
use App\Models\ProjectTaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Lógica compartida (admin y portal del dev) para subir/borrar
 * documentos generales del proyecto y adjuntos por tarea.
 */
trait BoardFiles
{
    protected function guardarProjectFile(Request $request, InternalProject $project): void
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'archivo' => 'required|file|max:20480', // 20 MB
        ]);

        $file = $request->file('archivo');
        $path = $file->store('internal-projects/'.$project->id, 'public');

        $project->files()->create([
            'nombre' => ($data['nombre'] ?? null) ?: $file->getClientOriginalName(),
            'archivo' => $path,
            'tipo' => $file->getMimeType(),
            'tamano' => $file->getSize(),
        ]);
    }

    protected function borrarProjectFile(ProjectFile $file): void
    {
        Storage::disk('public')->delete($file->archivo);
        $file->delete();
    }

    protected function guardarTaskFile(Request $request, ProjectTask $task, ?string $subidoPor): void
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'archivo' => 'required|file|max:20480', // 20 MB
        ]);

        $file = $request->file('archivo');
        $path = $file->store('internal-projects/'.$task->internal_project_id.'/tasks/'.$task->id, 'public');

        $task->files()->create([
            'nombre' => ($data['nombre'] ?? null) ?: $file->getClientOriginalName(),
            'archivo' => $path,
            'tipo' => $file->getMimeType(),
            'tamano' => $file->getSize(),
            'subido_por' => $subidoPor,
        ]);
    }

    protected function borrarTaskFile(ProjectTaskFile $file): void
    {
        Storage::disk('public')->delete($file->archivo);
        $file->delete();
    }
}
