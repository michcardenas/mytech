<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Mostrar una landing page por su slug
     */
    public function show($slug)
    {
        // Buscar la landing page activa por slug
        $landing = Page::where('slug', $slug)
            ->where('type', 'landing')
            ->where('is_active', true)
            ->with(['activeSections', 'seo'])
            ->firstOrFail();

        // Obtener proyectos destacados si existen
        $proyectos = $landing->featuredProyectos();

        // Obtener datos estructurados de secciones
        $hero = $landing->sections()->where('name', 'hero')->first();
        $problema = $landing->sections()->where('name', 'problema')->first();
        $solucion = $landing->sections()->where('name', 'solucion')->first();
        $proyectosSection = $landing->sections()->where('name', 'proyectos_destacados')->first();
        $faqs = $landing->sections()->where('name', 'faqs')->first();
        $ctaFinal = $landing->sections()->where('name', 'cta_final')->first();

        // Pasar el SEO a la vista para que layouts.app lo use
        $seo = $landing->seo;

        return view('landings.show', compact(
            'landing',
            'proyectos',
            'hero',
            'problema',
            'solucion',
            'proyectosSection',
            'faqs',
            'ctaFinal',
            'seo'
        ));
    }

    /**
     * Listar todas las landing pages activas (opcional, para testing)
     */
    public function index()
    {
        $landings = Page::where('type', 'landing')
            ->where('is_active', true)
            ->with('seo')
            ->get();

        return view('landings.index', compact('landings'));
    }
}
