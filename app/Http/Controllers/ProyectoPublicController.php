<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoPublicController extends Controller
{
    /**
     * Display the specified project.
     */
    public function show($slug)
    {
        $proyecto = Proyecto::where('slug', $slug)
            ->where('activo', true)
            ->firstOrFail();

        // Proyectos relacionados (misma categoría, excluyendo el actual)
        $proyectosRelacionados = Proyecto::where('categoria', $proyecto->categoria)
            ->where('id', '!=', $proyecto->id)
            ->where('activo', true)
            ->orderBy('destacado', 'desc')
            ->limit(3)
            ->get();

        return view('proyectos.show', compact('proyecto', 'proyectosRelacionados'));
    }
}
