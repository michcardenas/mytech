<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API REST de DESARROLLADORES. Protegida con la misma API key (Bearer / X-API-Key).
 * Sirve para listar/crear devs y obtener su id para asignarlos a un proyecto
 * interno (campo developer_id de /api/internal-projects). El email que se
 * cargue aquí es el que recibe el aviso de asignación.
 */
class DeveloperApiController extends Controller
{
    /** GET /api/developers */
    public function index(Request $request): JsonResponse
    {
        $query = Developer::query()->orderBy('nombre');

        if ($request->filled('buscar')) {
            $s = $request->buscar;
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $items = $query->get()->map(fn (Developer $d) => $this->present($d));

        return response()->json(['total' => $items->count(), 'data' => $items]);
    }

    /** GET /api/developers/{id} */
    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->present(Developer::findOrFail($id))]);
    }

    /** POST /api/developers */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules(true));
        $data['moneda_default'] = $data['moneda_default'] ?? 'COP';
        $developer = Developer::create($data);

        return response()->json(['message' => 'Desarrollador creado.', 'data' => $this->present($developer)], 201);
    }

    /** PUT/PATCH /api/developers/{id} */
    public function update(Request $request, string $id): JsonResponse
    {
        $developer = Developer::findOrFail($id);
        $developer->update($request->validate($this->rules(false)));

        return response()->json(['message' => 'Desarrollador actualizado.', 'data' => $this->present($developer->fresh())]);
    }

    /** DELETE /api/developers/{id} */
    public function destroy(string $id): JsonResponse
    {
        Developer::findOrFail($id)->delete();

        return response()->json(['message' => 'Desarrollador eliminado.']);
    }

    /** @return array<string, string> */
    private function rules(bool $creating): array
    {
        $req = $creating ? 'required' : 'sometimes';

        return [
            'nombre' => "{$req}|string|max:255",
            'telefono' => 'sometimes|nullable|string|max:50',
            'email' => 'sometimes|nullable|email|max:255',
            'pago_default' => 'sometimes|nullable|numeric|min:0',
            'moneda_default' => 'sometimes|nullable|in:COP,USD,EUR',
        ];
    }

    /** @return array<string, mixed> */
    private function present(Developer $d): array
    {
        return [
            'id' => $d->id,
            'nombre' => $d->nombre,
            'email' => $d->email,
            'telefono' => $d->telefono,
            'pago_default' => $d->pago_default,
            'moneda_default' => $d->moneda_default,
            'created_at' => optional($d->created_at)->toDateTimeString(),
        ];
    }
}
