<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use App\Models\InternalProject;
use App\Services\DevNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * API REST de PROYECTOS INTERNOS (gestión interna, los de /internal-projects).
 * Protegida con API key (header Authorization: Bearer <key> o X-API-Key),
 * la misma de la API de portafolio (config proyectos.api_key).
 *
 * Al asignar/cambiar el desarrollador se le envía el correo de aviso, salvo
 * que se pase "notificar": false en el cuerpo.
 */
class InternalProjectApiController extends Controller
{
    /** LISTAR — GET /api/internal-projects */
    public function index(Request $request): JsonResponse
    {
        $query = InternalProject::query()->orderByDesc('id');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('desarrollador')) {
            $query->where('desarrollador_nombre', $request->desarrollador);
        }
        if ($request->filled('cliente')) {
            $query->where('cliente_nombre', $request->cliente);
        }

        $items = $query->get()->map(fn (InternalProject $p) => $this->present($p));

        return response()->json(['total' => $items->count(), 'data' => $items]);
    }

    /** LEER UNO — GET /api/internal-projects/{id} */
    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->present(InternalProject::findOrFail($id))]);
    }

    /** CREAR — POST /api/internal-projects */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules(true));
        $notificar = (bool) ($data['notificar'] ?? true);
        unset($data['notificar']);

        $this->resolverDev($data);

        $data['estado'] = $data['estado'] ?? 'en_progreso';
        $data['moneda'] = $data['moneda'] ?? 'COP';
        $data['fuente'] = $data['fuente'] ?? 'directo';
        $data['desarrollador_moneda'] = $data['desarrollador_moneda'] ?? 'COP';
        if (! array_key_exists('precio', $data) || $data['precio'] === null) {
            $data['precio'] = 0;
        }

        $project = InternalProject::create($data);

        if ($notificar && $project->developer_id) {
            app(DevNotifier::class)->proyectoAsignado($project);
        }

        return response()->json([
            'message' => 'Proyecto interno creado.',
            'data' => $this->present($project),
        ], 201);
    }

    /** ACTUALIZAR (parcial) — PUT/PATCH /api/internal-projects/{id} */
    public function update(Request $request, string $id): JsonResponse
    {
        $project = InternalProject::findOrFail($id);

        $data = $request->validate($this->rules(false));
        $notificar = (bool) ($data['notificar'] ?? true);
        unset($data['notificar']);

        $this->resolverDev($data);

        $devAnterior = $project->developer_id;
        $project->update($data);

        if ($notificar && $project->developer_id && $project->developer_id !== $devAnterior) {
            app(DevNotifier::class)->proyectoAsignado($project);
        }

        return response()->json([
            'message' => 'Proyecto interno actualizado.',
            'data' => $this->present($project->fresh()),
        ]);
    }

    /** ELIMINAR — DELETE /api/internal-projects/{id} */
    public function destroy(string $id): JsonResponse
    {
        InternalProject::findOrFail($id)->delete();

        return response()->json(['message' => 'Proyecto interno eliminado.']);
    }

    /**
     * Si viene developer_id, completa el nombre y el email del desarrollador
     * desde su ficha (igual que el formulario del admin).
     *
     * @param  array<string, mixed>  $data
     */
    private function resolverDev(array &$data): void
    {
        if (empty($data['developer_id'])) {
            return;
        }

        $dev = Developer::find($data['developer_id']);
        if (! $dev) {
            return;
        }

        $data['desarrollador_nombre'] = $data['desarrollador_nombre'] ?? $dev->nombre;
        if (empty($data['desarrollador_email']) && $dev->email) {
            $data['desarrollador_email'] = $dev->email;
        }
    }

    /**
     * @return array<string, string>
     */
    private function rules(bool $creating): array
    {
        $req = $creating ? 'required' : 'sometimes';

        return [
            'nombre' => "{$req}|string|max:255",
            'cliente_nombre' => 'sometimes|nullable|string|max:255',
            'cliente_email' => 'sometimes|nullable|email|max:255',
            'cliente_contacto' => 'sometimes|nullable|string|max:255',
            'fuente' => 'sometimes|nullable|in:directo,workana',
            'fuente_url' => 'sometimes|nullable|url|max:500',
            'precio' => 'sometimes|nullable|numeric|min:0',
            'moneda' => 'sometimes|nullable|in:COP,USD,EUR',
            'tasa_cambio_estimada' => 'sometimes|nullable|numeric|min:0',
            'estado' => 'sometimes|nullable|in:en_progreso,pausado,completado,cancelado',
            'fecha_inicio' => 'sometimes|nullable|date',
            'fecha_entrega' => 'sometimes|nullable|date',
            'fecha_facturacion' => 'sometimes|nullable|date',
            'descripcion' => 'sometimes|nullable|string',
            'notas' => 'sometimes|nullable|string',
            'developer_id' => 'sometimes|nullable|exists:developers,id',
            'desarrollador_nombre' => 'sometimes|nullable|string|max:255',
            'desarrollador_email' => 'sometimes|nullable|email|max:255',
            'desarrollador_pago' => 'sometimes|nullable|numeric|min:0',
            'desarrollador_moneda' => 'sometimes|nullable|in:COP,USD,EUR',
            'es_recurrente' => 'sometimes|boolean',
            'es_bolsa_horas' => 'sometimes|boolean',
            'horas_totales' => 'sometimes|nullable|numeric|min:0',
            'valor_hora' => 'sometimes|nullable|numeric|min:0',
            'repo_url' => 'sometimes|nullable|string|max:500',
            'url_produccion' => 'sometimes|nullable|string|max:500',
            'url_pruebas' => 'sometimes|nullable|string|max:500',
            'notificar' => 'sometimes|boolean',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function present(InternalProject $p): array
    {
        return [
            'id' => $p->id,
            'nombre' => $p->nombre,
            'cliente_nombre' => $p->cliente_nombre,
            'cliente_email' => $p->cliente_email,
            'cliente_contacto' => $p->cliente_contacto,
            'fuente' => $p->fuente,
            'estado' => $p->estado,
            'precio' => $p->precio,
            'moneda' => $p->moneda,
            'developer_id' => $p->developer_id,
            'desarrollador_nombre' => $p->desarrollador_nombre,
            'desarrollador_email' => $p->desarrollador_email,
            'desarrollador_pago' => $p->desarrollador_pago,
            'desarrollador_moneda' => $p->desarrollador_moneda,
            'fecha_inicio' => optional($p->fecha_inicio)->toDateString(),
            'fecha_entrega' => optional($p->fecha_entrega)->toDateString(),
            'es_recurrente' => (bool) $p->es_recurrente,
            'es_bolsa_horas' => (bool) $p->es_bolsa_horas,
            'horas_totales' => $p->horas_totales,
            'valor_hora' => $p->valor_hora,
            'repo_url' => $p->repo_url,
            'url_produccion' => $p->url_produccion,
            'url_pruebas' => $p->url_pruebas,
            'descripcion' => $p->descripcion,
            'created_at' => optional($p->created_at)->toDateTimeString(),
        ];
    }
}
