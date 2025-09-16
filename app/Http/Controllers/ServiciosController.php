<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index()
    {
        return view('servicios.index');
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