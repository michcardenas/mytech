<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'pago_default' => 'nullable|numeric|min:0',
            'moneda_default' => 'nullable|in:COP,USD',
        ]);

        $validated['moneda_default'] = $validated['moneda_default'] ?? 'COP';

        $developer = Developer::create($validated);

        return response()->json([
            'ok' => true,
            'developer' => $developer,
        ]);
    }
}
