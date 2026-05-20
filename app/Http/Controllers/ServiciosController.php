<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Seo;
use App\Models\Proyecto;
use Illuminate\Support\Facades\Mail;

class ServiciosController extends Controller
{
public function index()
{
    $page = Page::with('seo')->where('slug', 'servicios')->first();

    // Decodificar el JSON del contenido (los partials lo leen también)
    $data = [];
    if ($page && $page->content) {
        $data = json_decode($page->content, true) ?? [];
    }

    // Obtener SEO desde la relación
    $seo = $page ? $page->seo : null;

    return view('servicios.index', compact('page', 'data', 'seo'));
}
   public function indexproyectos()
{
    $page = Page::with('seo')->where('slug', 'proyectos')->first();

    // Decodificar el contenido JSON si existe
    $data = [];
    if ($page && $page->content) {
        $data = json_decode($page->content, true) ?? [];
    }

    $seo = $page ? $page->seo : null;

    // Todos los proyectos activos, ordenados
    $proyectos = Proyecto::activos()
        ->orderBy('orden')
        ->orderBy('created_at', 'desc')
        ->get();

    // Destacados para el cinematic showcase
    $destacados = $proyectos->where('destacado', true)->values();

    // Stats agregadas para el hero
    $totalProyectos = $proyectos->count();
    $totalPaises    = $proyectos->pluck('pais')->filter()->unique()->count();
    $totalCategorias = $proyectos->pluck('categoria')->filter()->unique()->count();

    // Categorías únicas (para filtros) con count por cat
    $categoriasConteo = $proyectos
        ->groupBy('categoria')
        ->map(fn($g) => $g->count())
        ->sortDesc();

    // Países con count (para sección países)
    $paisesConteo = $proyectos
        ->groupBy('pais')
        ->map(function ($g) {
            return [
                'count'  => $g->count(),
                'flag'   => $g->first()->bandera_emoji ?? '🌎',
            ];
        })
        ->sortByDesc('count');

    return view('proyectos.index', compact(
        'page', 'data', 'seo', 'proyectos', 'destacados',
        'totalProyectos', 'totalPaises', 'totalCategorias',
        'categoriasConteo', 'paisesConteo'
    ));
}
    public function indexsobreNosotros()
    {
        $page = Page::where('slug', 'sobre-nosotros')->first();

        // Decodificar el contenido JSON si existe
        $data = [];
        if ($page && $page->content) {
            $data = json_decode($page->content, true) ?? [];
        }

        // Obtener datos de SEO si existen
        $seo = null;
        if ($page) {
            $seo = Seo::where('page_id', $page->id)->first();
        }

        return view('sobre_nosotros.index', compact('data', 'seo'));
    }
  public function indexContacto()
{
    $page = Page::where('slug', 'contacto')->first();

    // Decodificar el contenido JSON si existe
    $data = [];
    if ($page && $page->content) {
        $data = json_decode($page->content, true) ?? [];
    }

    // Obtener datos de SEO si existen
    $seo = null;
    if ($page) {
        $seo = Seo::where('page_id', $page->id)->first();
    }

    return view('contacto.index', compact('data', 'seo', 'page'));
}

public function storeContacto(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'whatsapp' => 'required|string|max:20',
        'empresa' => 'required|string|max:255',
        'tipo_proyecto' => 'required|string',
        'presupuesto' => 'required|string',
        'descripcion' => 'required|string|max:2000',
    ]);

    $data = [
        'nombre' => $request->nombre,
        'email' => $request->email,
        'whatsapp' => $request->whatsapp,
        'empresa' => $request->empresa,
        'tipo_proyecto' => $request->tipo_proyecto,
        'presupuesto' => $request->presupuesto,
        'descripcion' => $request->descripcion,
        'fecha' => now()->format('Y-m-d H:i:s'),
    ];

    try {
        Mail::send('emails.contacto', $data, function ($message) use ($data) {
            $message->to('contacto@mytechsolutionsco.com')
                    ->subject('Nuevo Proyecto de Contacto - ' . $data['empresa'])
                    ->replyTo($data['email'], $data['nombre']);
        });

        return redirect()->route('contacto.gracias');
    } catch (\Exception $e) {
        return redirect()->route('contacto.index')->with('error', 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente o contáctanos por WhatsApp.');
    }
}
public function gracias()
{
    return view('contacto.gracias');
}

}