<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'comision_porcentaje_default' => 'nullable|numeric|min:0|max:100',
        ]);

        $vendedor = Vendedor::create($validated);

        return response()->json([
            'ok' => true,
            'vendedor' => $vendedor,
        ]);
    }
}
