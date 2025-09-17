<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Seo;

class ServiciosController extends Controller
{
public function index()
{
    $page = Page::with('seo')->where('slug', 'servicios')->first();
    
    // Decodificar el JSON del contenido
    $data = [];
    if ($page && $page->content) {
        $data = json_decode($page->content, true) ?? [];
    }
    
    // Obtener SEO desde la relación
    $seo = $page ? $page->seo : null;
    
    return view('servicios.index', compact('data', 'seo'));
}
    public function indexproyectos()
    {
      
        
        return view('proyectos.index');
    }
    public function indexsobreNosotros()
    {
      
        
        return view('sobre_nosotros.index');
    }
    public function indexcontacto()
    {
      
        
        return view('contacto.index');
    }
}