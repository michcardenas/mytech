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
    $page = Page::where('slug', 'proyectos')->first();
    
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
    
    return view('proyectos.index', compact('data', 'seo'));
}
    public function indexsobreNosotros()
    {
      
        
        return view('sobre_nosotros.index');
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
    
    return view('contacto.index', compact('data', 'seo'));
}
}