<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Page;
use App\Models\Proyecto;
use App\Models\InternalProject;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Estadísticas de páginas (excluye blogs)
            $totalPages = Page::where('type', '!=', 'blog')->count();
            $activePages = Page::where('type', '!=', 'blog')->where('is_active', true)->count();
            $draftPages = $totalPages - $activePages;

            // Estadísticas de proyectos
            $totalProyectos = Proyecto::count();
            $activeProyectos = Proyecto::where('activo', true)->count();
            $featuredProyectos = Proyecto::where('destacado', true)->count();

            // Estadísticas de blog
            $totalBlog = Page::where('type', 'blog')->count();
            $publishedBlog = Page::published()->count();

            // Estadísticas de proyectos internos
            $totalInternal = InternalProject::count();
            $inProgressInternal = InternalProject::where('estado', 'en_progreso')->count();

            return view('dashboard.admin', compact(
                'totalPages', 'activePages', 'draftPages',
                'totalProyectos', 'activeProyectos', 'featuredProyectos',
                'totalBlog', 'publishedBlog',
                'totalInternal', 'inProgressInternal'
            ));
        }

        // Si es comprador, buscamos sus órdenes
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('dashboard.comprador', compact('user', 'orders'));
    }
}
