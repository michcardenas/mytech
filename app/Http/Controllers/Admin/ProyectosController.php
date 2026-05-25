<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProyectosController extends Controller
{
    /* ====================================================================
       INDEX / SHOW / EDIT (vistas)
       ==================================================================== */

    public function index()
    {
        $proyectos = Proyecto::orderBy('orden')->paginate(15);
        return view('admin.proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        return view('admin.proyectos.create');
    }

    public function show(Proyecto $proyecto)
    {
        return view('admin.proyectos.show', compact('proyecto'));
    }

    public function edit(Proyecto $proyecto)
    {
        return view('admin.proyectos.edit', compact('proyecto'));
    }

    /* ====================================================================
       VALIDATION RULES — Centralizado.
       Cualquier campo nuevo se agrega aquí.
       ==================================================================== */

    private function validationRules(?int $proyectoId = null): array
    {
        $slugRule = 'nullable|string|max:255|alpha_dash|unique:proyectos,slug';
        if ($proyectoId) {
            $slugRule .= ','.$proyectoId;
        }

        return [
            // ── Básicos ─────────────────────────────────
            'nombre'          => 'required|string|max:255',
            'slug'            => $slugRule,
            'pais'            => 'required|string|max:100',
            'bandera_emoji'   => 'required|string|max:10',
            'categoria'       => 'required|string|max:64',
            'badge_text'      => 'required|string|max:255',
            'descripcion'     => 'required|string',
            'url'             => 'nullable|url|max:500',
            'logo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'tecnologias'     => 'required|string',
            'estado'          => 'required|in:en_vivo,en_desarrollo,pausado',
            'destacado'       => 'nullable|boolean',
            'orden'           => 'nullable|integer',
            'activo'          => 'nullable|boolean',

            // ── SEO Esencial ────────────────────────────
            'focus_keyword'      => 'nullable|string|max:120',
            'secondary_keywords' => 'nullable|string', // input como CSV, lo convertimos a array
            'excerpt'            => 'nullable|string|max:500',
            'canonical_url'      => 'nullable|url|max:500',
            'robots'             => ['nullable', Rule::in(['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow'])],
            'meta_title'         => 'nullable|string|max:150',
            'meta_description'   => 'nullable|string|max:300',
            'meta_keywords'      => 'nullable|string|max:255',

            // ── Open Graph ──────────────────────────────
            'og_image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'og_title'       => 'nullable|string|max:150',
            'og_description' => 'nullable|string|max:300',
            'og_type'        => 'nullable|string|max:50',

            // ── Twitter Cards ───────────────────────────
            'twitter_card'        => 'nullable|in:summary,summary_large_image,app,player',
            'twitter_title'       => 'nullable|string|max:150',
            'twitter_description' => 'nullable|string|max:300',
            'twitter_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',

            // ── Schema.org ──────────────────────────────
            'schema_type'    => 'nullable|string|max:50',
            'schema_markup'  => 'nullable|string',

            // ── Metadata avanzada ───────────────────────
            'breadcrumb_title' => 'nullable|string|max:120',
            'author'           => 'nullable|string|max:120',
            'reading_time'     => 'nullable|integer|min:1|max:120',
            'alt_logo'         => 'nullable|string|max:255',
            'alt_og_image'     => 'nullable|string|max:255',
            'publicado_en'     => 'nullable|date',

            // ── Clasificación cliente ───────────────────
            'industria'   => 'nullable|string|max:120',
            'client_size' => 'nullable|in:startup,pyme,empresa,enterprise',

            // ── Recursos externos ───────────────────────
            'case_study_url' => 'nullable|url|max:500',
            'video_url'      => 'nullable|url|max:500',

            // ── Contenido extendido ─────────────────────
            'descripcion_extendida' => 'nullable|string',
            'desafio'               => 'nullable|string',
            'solucion'              => 'nullable|string',
            'resultados'            => 'nullable|string',
            'galeria.*'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            // ── Testimonios ─────────────────────────────
            'testimonio'        => 'nullable|string',
            'testimonio_autor'  => 'nullable|string|max:255',
            'testimonio_cargo'  => 'nullable|string|max:255',

            // ── Métricas del proyecto ───────────────────
            'duracion_desarrollo' => 'nullable|string|max:100',
            'equipo_size'         => 'nullable|integer|min:1',
            'fecha_lanzamiento'   => 'nullable|date',
            'visitas_mensuales'   => 'nullable|integer|min:0',
        ];
    }

    /* ====================================================================
       NORMALIZE — convierte CSVs, sanea, etc. Inputs comunes a create+update.
       ==================================================================== */

    private function normalize(array $validated, Request $request): array
    {
        // Tecnologías: CSV → array
        if ($request->filled('tecnologias')) {
            $validated['tecnologias'] = array_values(array_filter(
                array_map('trim', explode(',', $request->tecnologias))
            ));
        }

        // Secondary keywords: CSV → array
        if ($request->filled('secondary_keywords')) {
            $validated['secondary_keywords'] = array_values(array_filter(
                array_map('trim', explode(',', $request->secondary_keywords))
            ));
        } else {
            $validated['secondary_keywords'] = null;
        }

        // Booleans
        $validated['destacado'] = $request->boolean('destacado');
        $validated['activo']    = $request->boolean('activo');

        return $validated;
    }

    /* ====================================================================
       HANDLE FILES — logo, og_image, twitter_image, galeria.
       Retorna $validated con paths actualizados, eliminando archivos viejos.
       ==================================================================== */

    private function handleFiles(array $validated, Request $request, ?Proyecto $proyecto = null): array
    {
        // ── Logo ────────────────────────────────────────
        if ($request->hasFile('logo')) {
            if ($proyecto && $proyecto->logo && Storage::disk('public')->exists($proyecto->logo)) {
                Storage::disk('public')->delete($proyecto->logo);
            }
            $validated['logo'] = $request->file('logo')->store('proyectos/logos', 'public');
        }

        // ── OG image ────────────────────────────────────
        if ($request->hasFile('og_image')) {
            if ($proyecto && $proyecto->og_image && Storage::disk('public')->exists($proyecto->og_image)) {
                Storage::disk('public')->delete($proyecto->og_image);
            }
            $validated['og_image'] = $request->file('og_image')->store('proyectos/og-images', 'public');
        }

        // ── Twitter image ───────────────────────────────
        if ($request->hasFile('twitter_image')) {
            if ($proyecto && $proyecto->twitter_image && Storage::disk('public')->exists($proyecto->twitter_image)) {
                Storage::disk('public')->delete($proyecto->twitter_image);
            }
            $validated['twitter_image'] = $request->file('twitter_image')->store('proyectos/twitter', 'public');
        }

        // ── Galería ─────────────────────────────────────
        if ($request->hasFile('galeria')) {
            if ($proyecto && $proyecto->galeria) {
                foreach ($proyecto->galeria as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $galeriaImages = [];
            foreach ($request->file('galeria') as $image) {
                $galeriaImages[] = $image->store('proyectos/galeria', 'public');
            }
            $validated['galeria'] = $galeriaImages;
        }

        return $validated;
    }

    /* ====================================================================
       STORE / UPDATE
       ==================================================================== */

    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());
        $validated = $this->normalize($validated, $request);
        $validated = $this->handleFiles($validated, $request);

        // Slug: si no vino, lo genera del nombre (model boot también lo hace, pero por claridad)
        if (empty($validated['slug'] ?? null)) {
            $validated['slug'] = Str::slug($request->nombre);
        }

        // Default og_type sensato
        if (empty($validated['og_type'] ?? null)) {
            $validated['og_type'] = 'article';
        }

        // Default schema_type sensato
        if (empty($validated['schema_type'] ?? null)) {
            $validated['schema_type'] = 'CreativeWork';
        }

        Proyecto::create($validated);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto creado exitosamente con SEO optimizado.');
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $validated = $request->validate($this->validationRules($proyecto->id));
        $validated = $this->normalize($validated, $request);
        $validated = $this->handleFiles($validated, $request, $proyecto);

        // Slug: si vino vacío y cambió el nombre, regenerar
        if (empty($validated['slug'] ?? null) && $request->nombre !== $proyecto->nombre) {
            $validated['slug'] = Str::slug($request->nombre);
        }

        $proyecto->update($validated);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    /* ====================================================================
       DESTROY / TOGGLE
       ==================================================================== */

    public function destroy(Proyecto $proyecto)
    {
        foreach (['logo', 'og_image', 'twitter_image'] as $field) {
            if ($proyecto->$field && Storage::disk('public')->exists($proyecto->$field)) {
                Storage::disk('public')->delete($proyecto->$field);
            }
        }
        if ($proyecto->galeria) {
            foreach ($proyecto->galeria as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente.');
    }

    public function toggleActivo(Proyecto $proyecto)
    {
        $proyecto->update(['activo' => ! $proyecto->activo]);
        $status = $proyecto->activo ? 'activado' : 'desactivado';

        return response()->json([
            'success' => true,
            'message' => "Proyecto {$status} exitosamente.",
            'activo'  => $proyecto->activo,
        ]);
    }
}
