<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'empresa' => 'nullable|string|max:255',
            'identificacion' => 'nullable|string|max:50',
        ]);

        $client = Client::create($validated);

        return response()->json([
            'ok' => true,
            'client' => $client,
        ]);
    }
}
