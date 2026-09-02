<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClienteBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteBannerController extends Controller
{
    public function index()
    {
        return view('admin.cliente-banners.index', [
            'banners' => ClienteBanner::with('client')->orderBy('orden')->orderByDesc('id')->get(),
            'clientes' => Client::orderBy('nombre')->get(['id', 'nombre']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('banners', 'public');
        }

        ClienteBanner::create($data);

        return back()->with('success', 'Banner creado.');
    }

    public function update(Request $request, ClienteBanner $banner)
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen')) {
            if ($banner->imagen) {
                Storage::disk('public')->delete($banner->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('banners', 'public');
        }

        $banner->update($data);

        return back()->with('success', 'Banner actualizado.');
    }

    public function destroy(ClienteBanner $banner)
    {
        if ($banner->imagen) {
            Storage::disk('public')->delete($banner->imagen);
        }
        $banner->delete();

        return back()->with('success', 'Banner eliminado.');
    }

    /** Activa/desactiva rápido desde el listado. */
    public function toggle(ClienteBanner $banner)
    {
        $banner->update(['activo' => ! $banner->activo]);

        return back()->with('success', 'Banner '.($banner->activo ? 'activado' : 'desactivado').'.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        $data = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'titulo' => 'required|string|max:150',
            'mensaje' => 'nullable|string|max:600',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'cta_texto' => 'nullable|string|max:60',
            'cta_url' => 'nullable|url|max:500',
            'color' => 'required|in:'.implode(',', array_keys(ClienteBanner::COLORES)),
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date|after_or_equal:desde',
            'orden' => 'nullable|integer|min:0|max:999',
        ]);

        $data['activo'] = $request->boolean('activo', true);
        $data['orden'] = $data['orden'] ?? 0;
        unset($data['imagen']);

        return $data;
    }
}
