<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InternalProject;
use App\Models\ProjectArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API REST de AREAS / modulos de un proyecto interno, para agrupar y filtrar
 * las tareas del tablero por tema. Protegida con la misma API key.
 */
class ProjectAreaApiController extends Controller
{
    /** GET /api/internal-projects/{id}/areas */
    public function index(string $id): JsonResponse
    {
        $project = InternalProject::findOrFail($id);
        $areas = $project->areas()->withCount('tasks')->get()
            ->map(fn (ProjectArea $a) => $this->present($a));

        return response()->json(['total' => $areas->count(), 'data' => $areas]);
    }

    /** POST /api/internal-projects/{id}/areas */
    public function store(Request $request, string $id): JsonResponse
    {
        $project = InternalProject::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'sometimes|nullable|string|max:9',
            'orden' => 'sometimes|nullable|integer',
        ]);

        $area = $project->areas()->firstOrCreate(
            ['nombre' => $data['nombre']],
            [
                'color' => $data['color'] ?? ProjectArea::colorSugerido($project),
                'orden' => $data['orden'] ?? (int) $project->areas()->max('orden') + 1,
            ]
        );

        return response()->json(['message' => 'Area lista.', 'data' => $this->present($area)], 201);
    }

    /** PUT/PATCH /api/project-areas/{id} */
    public function update(Request $request, string $id): JsonResponse
    {
        $area = ProjectArea::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|nullable|string|max:9',
            'orden' => 'sometimes|nullable|integer',
        ]);
        $area->update($data);

        return response()->json(['message' => 'Area actualizada.', 'data' => $this->present($area)]);
    }

    /** DELETE /api/project-areas/{id} — las tareas quedan sin area (area_id = null). */
    public function destroy(string $id): JsonResponse
    {
        ProjectArea::findOrFail($id)->delete();

        return response()->json(['message' => 'Area eliminada. Las tareas quedaron sin area.']);
    }

    /** @return array<string, mixed> */
    private function present(ProjectArea $a): array
    {
        return [
            'id' => $a->id,
            'internal_project_id' => $a->internal_project_id,
            'nombre' => $a->nombre,
            'color' => $a->color,
            'orden' => $a->orden,
            'tareas' => $a->tasks_count ?? $a->tasks()->count(),
        ];
    }
}
