<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Seo;
use Illuminate\Support\Facades\Mail;

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