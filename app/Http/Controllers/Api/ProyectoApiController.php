<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProyectoApiController extends Controller
{
    /* ====================================================================
       LISTAR — GET /api/proyectos
       Devuelve todos los proyectos en formato editable (los mismos campos
       que acepta store/update, para que la IA pueda leer → modificar → escribir).
       ==================================================================== */
    public function index(Request $request): JsonResponse
    {
        $query = Proyecto::query()->orderBy('orden');

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->boolean('solo_activos')) {
            $query->where('activo', true);
        }

        $proyectos = $query->get()->map(fn (Proyecto $p) => $this->present($p));

        return response()->json([
            'total' => $proyectos->count(),
            'data' => $proyectos,
        ]);
    }

    /* ====================================================================
       LEER UNO — GET /api/proyectos/{idOrSlug}
       ==================================================================== */
    public function show(string $idOrSlug): JsonResponse
    {
        $proyecto = $this->findProyecto($idOrSlug);

        return response()->json(['data' => $this->present($proyecto)]);
    }

    /* ====================================================================
       CREAR — POST /api/proyectos
       ==================================================================== */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules(true));
        $data = $this->normalize($validated, $request);

        if (empty($data['slug'] ?? null)) {
            $data['slug'] = Str::slug($request->nombre);
        }
        $data['og_type'] = $data['og_type'] ?? 'article';
        $data['schema_type'] = $data['schema_type'] ?? 'CreativeWork';

        $proyecto = Proyecto::create($data);

        return response()->json([
            'message' => 'Proyecto creado.',
            'data' => $this->present($proyecto),
        ], 201);
    }

    /* ====================================================================
       ACTUALIZAR — PUT/PATCH /api/proyectos/{idOrSlug}
       Acepta actualización parcial: solo se tocan los campos enviados.
       ==================================================================== */
    public function update(Request $request, string $idOrSlug): JsonResponse
    {
        $proyecto = $this->findProyecto($idOrSlug);

        $validated = $request->validate($this->rules(false, $proyecto->id));
        $data = $this->normalize($validated, $request);

        $proyecto->update($data);

        return response()->json([
            'message' => 'Proyecto actualizado.',
            'data' => $this->present($proyecto->fresh()),
        ]);
    }

    /* ====================================================================
       BORRAR — DELETE /api/proyectos/{idOrSlug}
       ==================================================================== */
    public function destroy(string $idOrSlug): JsonResponse
    {
        $proyecto = $this->findProyecto($idOrSlug);
        $proyecto->delete();

        return response()->json(['message' => 'Proyecto eliminado.']);
    }

    /* ====================================================================
       REGLAS DE VALIDACIÓN (JSON) — compartidas store/update
       ==================================================================== */
    private function rules(bool $creating, ?int $proyectoId = null): array
    {
        $req = $creating ? 'required' : 'sometimes';

        $slugRule = ['nullable', 'string', 'max:255', 'alpha_dash'];
        $slugRule[] = $proyectoId
            ? Rule::unique('proyectos', 'slug')->ignore($proyectoId)
            : Rule::unique('proyectos', 'slug');

        return [
            // ── Básicos ─────────────────────────────────
            'nombre' => "$req|string|max:255",
            'slug' => $slugRule,
            'pais' => "$req|string|max:100",
            'bandera_emoji' => "$req|string|max:10",
            'categoria' => "$req|string|max:64",
            'badge_text' => "$req|string|max:255",
            'descripcion' => "$req|string",
            'url' => 'nullable|url|max:500',
            'logo' => 'nullable|string|max:500',      // ruta existente (upload = admin)
            'tecnologias' => "$req|array",
            'tecnologias.*' => 'string|max:100',
            'estado' => "$req|in:en_vivo,en_desarrollo,pausado",
            'destacado' => 'nullable|boolean',
            'orden' => 'nullable|integer',
            'activo' => 'nullable|boolean',

            // ── SEO Esencial ────────────────────────────
            'focus_keyword' => 'nullable|string|max:120',
            'secondary_keywords' => 'nullable|array',
            'secondary_keywords.*' => 'string|max:120',
            'excerpt' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'robots' => ['nullable', Rule::in(['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow'])],
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:255',

            // ── Open Graph / Twitter ────────────────────
            'og_image' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:150',
            'og_description' => 'nullable|string|max:300',
            'og_type' => 'nullable|string|max:50',
            'alt_og_image' => 'nullable|string|max:255',
            'twitter_image' => 'nullable|string|max:500',
            'twitter_card' => 'nullable|in:summary,summary_large_image,app,player',
            'twitter_title' => 'nullable|string|max:150',
            'twitter_description' => 'nullable|string|max:300',

            // ── Schema.org ──────────────────────────────
            'schema_type' => 'nullable|string|max:50',
            'schema_markup' => 'nullable|string',

            // ── Metadata avanzada ───────────────────────
            'breadcrumb_title' => 'nullable|string|max:120',
            'author' => 'nullable|string|max:120',
            'reading_time' => 'nullable|integer|min:1|max:120',
            'alt_logo' => 'nullable|string|max:255',
            'publicado_en' => 'nullable|date',
            'industria' => 'nullable|string|max:120',
            'client_size' => 'nullable|in:startup,pyme,empresa,enterprise',

            // ── Recursos externos ───────────────────────
            'case_study_url' => 'nullable|url|max:500',
            'video_url' => 'nullable|url|max:500',

            // ── Contenido extendido (HTML de Quill) ─────
            'descripcion_extendida' => 'nullable|string',
            'desafio' => 'nullable|string',
            'solucion' => 'nullable|string',
            'resultados' => 'nullable|string',
            'galeria_alts' => 'nullable|array',
            'galeria_alts.*' => 'string|max:255',

            // ── FAQ (schema FAQPage) ────────────────────
            'faqs' => 'nullable|array',
            'faqs.*.pregunta' => 'required|string|max:300',
            'faqs.*.respuesta' => 'required|string|max:2000',

            // ── Testimonio ──────────────────────────────
            'testimonio' => 'nullable|string',
            'testimonio_autor' => 'nullable|string|max:255',
            'testimonio_cargo' => 'nullable|string|max:255',

            // ── Métricas ────────────────────────────────
            'duracion_desarrollo' => 'nullable|string|max:100',
            'equipo_size' => 'nullable|integer|min:1',
            'fecha_lanzamiento' => 'nullable|date',
            'visitas_mensuales' => 'nullable|integer|min:0',
        ];
    }

    /* ====================================================================
       NORMALIZE — sanea arrays y booleans que vienen por JSON.
       Solo toca claves presentes (para no pisar campos en updates parciales).
       ==================================================================== */
    private function normalize(array $validated, Request $request): array
    {
        // Tecnologías: acepta array o CSV
        if (array_key_exists('tecnologias', $validated)) {
            $validated['tecnologias'] = $this->toStringArray($validated['tecnologias']);
        }

        // Secondary keywords
        if (array_key_exists('secondary_keywords', $validated)) {
            $validated['secondary_keywords'] = $validated['secondary_keywords']
                ? $this->toStringArray($validated['secondary_keywords'])
                : null;
        }

        // Galería alts
        if (array_key_exists('galeria_alts', $validated)) {
            $validated['galeria_alts'] = $validated['galeria_alts']
                ? array_values(array_map('trim', $validated['galeria_alts']))
                : null;
        }

        // FAQs: solo pares válidos
        if (array_key_exists('faqs', $validated)) {
            $faqs = [];
            foreach ((array) ($validated['faqs'] ?? []) as $faq) {
                $pregunta = trim((string) ($faq['pregunta'] ?? ''));
                $respuesta = trim((string) ($faq['respuesta'] ?? ''));
                if ($pregunta !== '' && $respuesta !== '') {
                    $faqs[] = ['pregunta' => $pregunta, 'respuesta' => $respuesta];
                }
            }
            $validated['faqs'] = $faqs ?: null;
        }

        // Booleans (solo si vinieron en el payload)
        if ($request->has('destacado')) {
            $validated['destacado'] = $request->boolean('destacado');
        }
        if ($request->has('activo')) {
            $validated['activo'] = $request->boolean('activo');
        }

        return $validated;
    }

    /**
     * Convierte array o CSV en un array de strings limpio.
     *
     * @param  array<int, string>|string  $value
     * @return array<int, string>
     */
    private function toStringArray(array|string $value): array
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        return array_values(array_filter(array_map('trim', $value), fn ($v) => $v !== ''));
    }

    /* ====================================================================
       Helpers
       ==================================================================== */
    private function findProyecto(string $idOrSlug): Proyecto
    {
        return Proyecto::where('slug', $idOrSlug)
            ->orWhere('id', is_numeric($idOrSlug) ? (int) $idOrSlug : 0)
            ->firstOrFail();
    }

    /**
     * Presenta el proyecto en el mismo shape que acepta store/update
     * (los casts del modelo ya devuelven arrays para tecnologias/faqs/etc).
     *
     * @return array<string, mixed>
     */
    private function present(Proyecto $proyecto): array
    {
        $data = $proyecto->toArray();
        $data['detalle_url'] = route('proyectos.show', $proyecto->slug);

        return $data;
    }
}
