<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return view('dashboard.admin');
        }

        // Si es comprador, buscamos sus órdenes
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('dashboard.comprador', compact('user', 'orders'));
    }
}
