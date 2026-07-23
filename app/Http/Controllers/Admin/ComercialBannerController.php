<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComercialBanner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComercialBannerController extends Controller
{
    public function index()
    {
        return view('admin.banners.index', [
            'banners' => ComercialBanner::with('user')->orderBy('orden')->orderByDesc('id')->get(),
            'comerciales' => User::role('comercial')->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('banners', 'public');
        }

        ComercialBanner::create($data);

        return back()->with('success', 'Banner creado.');
    }

    public function update(Request $request, ComercialBanner $banner)
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

    public function destroy(ComercialBanner $banner)
    {
        if ($banner->imagen) {
            Storage::disk('public')->delete($banner->imagen);
        }
        $banner->delete();

        return back()->with('success', 'Banner eliminado.');
    }

    /** Activa/desactiva rápido desde el listado. */
    public function toggle(ComercialBanner $banner)
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
            'user_id' => 'nullable|exists:users,id',
            'titulo' => 'required|string|max:150',
            'mensaje' => 'nullable|string|max:600',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'cta_texto' => 'nullable|string|max:60',
            'cta_url' => 'nullable|url|max:500',
            'color' => 'required|in:'.implode(',', array_keys(ComercialBanner::COLORES)),
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
