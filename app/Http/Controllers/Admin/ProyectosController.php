<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proyectos = Proyecto::orderBy('orden')->paginate(15);
        return view('admin.proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.proyectos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:100',
            'bandera_emoji' => 'required|string|max:10',
            'categoria' => 'required|in:travel,booking,restaurant,admin,legal,tech,ecommerce',
            'badge_text' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'url' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'tecnologias' => 'required|string',
            'estado' => 'required|in:en_vivo,en_desarrollo,pausado',
            'destacado' => 'boolean',
            'orden' => 'nullable|integer',
            'activo' => 'boolean',
            // Campos SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Campos de contenido extendido
            'descripcion_extendida' => 'nullable|string',
            'desafio' => 'nullable|string',
            'solucion' => 'nullable|string',
            'resultados' => 'nullable|string',
            'galeria.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Campos de testimonios
            'testimonio' => 'nullable|string',
            'testimonio_autor' => 'nullable|string|max:255',
            'testimonio_cargo' => 'nullable|string|max:255',
            // Campos adicionales del proyecto
            'duracion_desarrollo' => 'nullable|string|max:100',
            'equipo_size' => 'nullable|integer|min:1',
            'fecha_lanzamiento' => 'nullable|date',
            'visitas_mensuales' => 'nullable|integer|min:0',
        ]);

        // Procesar tecnologías (convertir string separado por comas a array)
        $tecnologias = array_map('trim', explode(',', $request->tecnologias));
        $validated['tecnologias'] = $tecnologias;

        // Generar slug
        $validated['slug'] = Str::slug($request->nombre);

        // Procesar logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('proyectos/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Procesar OG Image (imagen para redes sociales)
        if ($request->hasFile('og_image')) {
            $ogImagePath = $request->file('og_image')->store('proyectos/og-images', 'public');
            $validated['og_image'] = $ogImagePath;
        }

        // Procesar galería de imágenes
        if ($request->hasFile('galeria')) {
            $galeriaImages = [];
            foreach ($request->file('galeria') as $image) {
                $imagePath = $image->store('proyectos/galeria', 'public');
                $galeriaImages[] = $imagePath;
            }
            $validated['galeria'] = $galeriaImages;
        }

        // Valores booleanos
        $validated['destacado'] = $request->boolean('destacado');
        $validated['activo'] = $request->boolean('activo');

        Proyecto::create($validated);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto)
    {
        return view('admin.proyectos.show', compact('proyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proyecto $proyecto)
    {
        return view('admin.proyectos.edit', compact('proyecto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'pais' => 'required|string|max:100',
            'bandera_emoji' => 'required|string|max:10',
            'categoria' => 'required|in:travel,booking,restaurant,admin,legal,tech,ecommerce',
            'badge_text' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'url' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'tecnologias' => 'required|string',
            'estado' => 'required|in:en_vivo,en_desarrollo,pausado',
            'destacado' => 'boolean',
            'orden' => 'nullable|integer',
            'activo' => 'boolean',
            // Campos SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Campos de contenido extendido
            'descripcion_extendida' => 'nullable|string',
            'desafio' => 'nullable|string',
            'solucion' => 'nullable|string',
            'resultados' => 'nullable|string',
            'galeria.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Campos de testimonios
            'testimonio' => 'nullable|string',
            'testimonio_autor' => 'nullable|string|max:255',
            'testimonio_cargo' => 'nullable|string|max:255',
            // Campos adicionales del proyecto
            'duracion_desarrollo' => 'nullable|string|max:100',
            'equipo_size' => 'nullable|integer|min:1',
            'fecha_lanzamiento' => 'nullable|date',
            'visitas_mensuales' => 'nullable|integer|min:0',
        ]);

        // Procesar tecnologías
        $tecnologias = array_map('trim', explode(',', $request->tecnologias));
        $validated['tecnologias'] = $tecnologias;

        // Actualizar slug solo si cambió el nombre
        if ($request->nombre !== $proyecto->nombre) {
            $validated['slug'] = Str::slug($request->nombre);
        }

        // Procesar logo
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($proyecto->logo && Storage::disk('public')->exists($proyecto->logo)) {
                Storage::disk('public')->delete($proyecto->logo);
            }

            $logoPath = $request->file('logo')->store('proyectos/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Procesar OG Image (imagen para redes sociales)
        if ($request->hasFile('og_image')) {
            // Eliminar imagen anterior si existe
            if ($proyecto->og_image && Storage::disk('public')->exists($proyecto->og_image)) {
                Storage::disk('public')->delete($proyecto->og_image);
            }

            $ogImagePath = $request->file('og_image')->store('proyectos/og-images', 'public');
            $validated['og_image'] = $ogImagePath;
        }

        // Procesar galería de imágenes
        if ($request->hasFile('galeria')) {
            // Eliminar imágenes antiguas de la galería
            if ($proyecto->galeria) {
                foreach ($proyecto->galeria as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $galeriaImages = [];
            foreach ($request->file('galeria') as $image) {
                $imagePath = $image->store('proyectos/galeria', 'public');
                $galeriaImages[] = $imagePath;
            }
            $validated['galeria'] = $galeriaImages;
        }

        // Valores booleanos
        $validated['destacado'] = $request->boolean('destacado');
        $validated['activo'] = $request->boolean('activo');

        $proyecto->update($validated);

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto)
    {
        // Eliminar logo si existe
        if ($proyecto->logo && Storage::disk('public')->exists($proyecto->logo)) {
            Storage::disk('public')->delete($proyecto->logo);
        }

        $proyecto->delete();

        return redirect()->route('admin.proyectos.index')
            ->with('success', 'Proyecto eliminado exitosamente.');
    }

    /**
     * Toggle active status
     */
    public function toggleActivo(Proyecto $proyecto)
    {
        $proyecto->update(['activo' => !$proyecto->activo]);

        $status = $proyecto->activo ? 'activado' : 'desactivado';

        return response()->json([
            'success' => true,
            'message' => "Proyecto {$status} exitosamente.",
            'activo' => $proyecto->activo
        ]);
    }
}
