<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API REST de CLIENTES. Protegida con la misma API key (Bearer / X-API-Key).
 * Sirve para listar/crear clientes y obtener su id para asignarlos a un
 * proyecto interno (campo client_id de /api/internal-projects).
 */
class ClientApiController extends Controller
{
    /** GET /api/clients */
    public function index(Request $request): JsonResponse
    {
        $query = Client::query()->orderBy('nombre');

        if ($request->filled('buscar')) {
            $s = $request->buscar;
            $query->where(function ($q) use ($s) {
                $q->where('nombre', 'like', "%{$s}%")
                    ->orWhere('empresa', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $items = $query->get()->map(fn (Client $c) => $this->present($c));

        return response()->json(['total' => $items->count(), 'data' => $items]);
    }

    /** GET /api/clients/{id} */
    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->present(Client::findOrFail($id))]);
    }

    /** POST /api/clients */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules(true));
        $client = Client::create($data);

        return response()->json(['message' => 'Cliente creado.', 'data' => $this->present($client)], 201);
    }

    /** PUT/PATCH /api/clients/{id} */
    public function update(Request $request, string $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->update($request->validate($this->rules(false)));

        return response()->json(['message' => 'Cliente actualizado.', 'data' => $this->present($client->fresh())]);
    }

    /** DELETE /api/clients/{id} */
    public function destroy(string $id): JsonResponse
    {
        Client::findOrFail($id)->delete();

        return response()->json(['message' => 'Cliente eliminado.']);
    }

    /** @return array<string, string> */
    private function rules(bool $creating): array
    {
        $req = $creating ? 'required' : 'sometimes';

        return [
            'nombre' => "{$req}|string|max:255",
            'empresa' => 'sometimes|nullable|string|max:255',
            'identificacion' => 'sometimes|nullable|string|max:100',
            'email' => 'sometimes|nullable|email|max:255',
            'telefono' => 'sometimes|nullable|string|max:50',
        ];
    }

    /** @return array<string, mixed> */
    private function present(Client $c): array
    {
        return [
            'id' => $c->id,
            'nombre' => $c->nombre,
            'empresa' => $c->empresa,
            'identificacion' => $c->identificacion,
            'email' => $c->email,
            'telefono' => $c->telefono,
            'created_at' => optional($c->created_at)->toDateTimeString(),
        ];
    }
}
