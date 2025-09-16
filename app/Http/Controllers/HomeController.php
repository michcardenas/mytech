<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use App\Models\Section;



class HomeController extends Controller
{
public function index()
{
    // Obtener la página de inicio y TODAS sus secciones (activas e inactivas)
    $page = Page::where('slug', 'inicio')->with(['sections' => function($query) {
        $query->orderBy('order'); // Quitamos el filtro is_active para mostrar todas
    }])->first();

    // Si no existe la página, crear datos por defecto
    if (!$page) {
        $sectionsData = [
            'hero' => null,
            'featured' => null, 
            'cta' => null,
            'categories' => null
        ];
    } else {
        // Convertir las secciones en un array asociativo para fácil acceso
        $sectionsData = [];
        foreach($page->sections as $section) {
            $sectionsData[$section->name] = $section;
        }
    }

    // Obtener productos destacados
    $featuredProducts = Product::with(['category', 'images'])
        ->where('stock', '>', 0)
        ->limit(8)
        ->get();
             
    // Obtener categorías para navegación
    $categories = Category::with('products')->get();

    return view('welcome', compact('featuredProducts', 'categories', 'sectionsData', 'page'));
}

    public function about()
{
    // Obtener la página de quienes-somos con sus secciones activas y ordenadas
    $page = Page::where('slug', 'quienes-somos')->with(['sections' => function($query) {
        $query->where('is_active', true)->orderBy('order');
    }])->first();

    // Si no existe la página, usar datos por defecto
    if (!$page) {
        $sectionsData = [
            'hero' => null,
            'legacy' => null, 
            'quality' => null,
            'passion' => null,
            'benefits' => null,
            'cta' => null
        ];
    } else {
        // Convertir las secciones en un array asociativo para fácil acceso
        $sectionsData = [];
        foreach($page->sections as $section) {
            $sectionsData[$section->name] = $section;
        }
    }

    return view('about', compact('sectionsData', 'page'));
}

 public function partnerChefs()
{
    // Obtener la página de contacto con sus secciones activas y ordenadas
    $page = Page::where('slug', 'contacto')->with(['sections' => function($query) {
        $query->where('is_active', true)->orderBy('order');
    }])->first();

    // Si no existe la página, usar datos por defecto
    if (!$page) {
        $sectionsData = [
            'hero' => null,
            'info' => null, 
            'services' => null,
            'contact_info' => null,
            'form_header' => null
        ];
    } else {
        // Convertir las secciones en un array asociativo para fácil acceso
        $sectionsData = [];
        foreach($page->sections as $section) {
            $sectionsData[$section->name] = $section;
        }
    }

    return view('partner-chefs', compact('sectionsData', 'page'));
}

public function submitPartnerChefs(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255', 
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'company_name' => 'required|string|max:255',
        'company_website' => 'nullable|url|max:255',
        'company_address' => 'required|string|max:500',
        'years_in_business' => 'required|integer|min:0',
    ]);
    
    // Aquí puedes guardar en base de datos o enviar email
    // Por ejemplo, enviar notificación por email
    
    return redirect()->back()->with('success', 'Thank you for your interest! We will contact you within 24 business hours.');
}
}