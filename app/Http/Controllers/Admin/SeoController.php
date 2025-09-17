<?php
// app/Http/Controllers/Admin/SeoController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SeoController extends Controller
{
    /**
     * Mostrar el formulario de edición de SEO
     */
    public function edit(Page $page)
    {
        Log::info('=== EDIT SEO ===', ['page_id' => $page->id]);
        
        // Obtener o crear SEO para la página
        $seo = $page->seo ?: new Seo(['page_id' => $page->id]);
        
        Log::info('SEO encontrado:', ['exists' => !is_null($page->seo), 'seo_id' => $seo->id ?? 'nuevo']);
        
        return view('admin.seo.edit', compact('page', 'seo'));
    }

    /**
     * Actualizar la configuración SEO - REPLICANDO LO QUE FUNCIONÓ EN TINKER
     */
    public function update(Request $request, Page $page)
    {
        Log::info('=== INICIO SEO UPDATE ===', [
            'page_id' => $page->id,
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        Log::info('REQUEST COMPLETO:', $request->all());

        // VALIDACIÓN MUY SIMPLE - Solo campos que realmente necesitamos validar
        try {
            $request->validate([
                'meta_title' => 'nullable|string|max:150',
                'meta_description' => 'nullable|string',
                'meta_keywords' => 'nullable|string|max:500',
                'canonical_url' => 'nullable|url|max:500',
                'og_title' => 'nullable|string|max:150',
                'og_description' => 'nullable|string',
                'og_image' => 'nullable|url|max:500',
                'og_url' => 'nullable|url|max:500',
                'og_site_name' => 'nullable|string|max:100',
                'twitter_title' => 'nullable|string|max:150',
                'twitter_description' => 'nullable|string',
                'twitter_image' => 'nullable|url|max:500',
                'twitter_site' => 'nullable|string|max:50',
                'twitter_creator' => 'nullable|string|max:50',
                'focus_keyword' => 'nullable|string|max:100',
                'breadcrumb_title' => 'nullable|string'
            ]);

            Log::info('Validación básica pasada');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', ['errors' => $e->errors()]);
            return redirect()->back()
                           ->withInput()
                           ->withErrors($e->errors());
        }

        // PREPARAR DATOS EXACTAMENTE COMO EN TINKER
        $dataToSave = [
            // Campos opcionales
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'canonical_url' => $request->input('canonical_url'),
            'og_title' => $request->input('og_title'),
            'og_description' => $request->input('og_description'),
            'og_image' => $request->input('og_image'),
            'og_url' => $request->input('og_url'),
            'og_site_name' => $request->input('og_site_name'),
            'twitter_title' => $request->input('twitter_title'),
            'twitter_description' => $request->input('twitter_description'),
            'twitter_image' => $request->input('twitter_image'),
            'twitter_site' => $request->input('twitter_site'),
            'twitter_creator' => $request->input('twitter_creator'),
            'focus_keyword' => $request->input('focus_keyword'),
            'breadcrumb_title' => $request->input('breadcrumb_title'),
            
            // Campos con defaults (igual que en tinker)
            'robots' => $request->input('robots', 'index,follow'),
            'og_type' => $request->input('og_type', 'website'),
            'twitter_card' => $request->input('twitter_card', 'summary_large_image'),
            'sitemap_priority' => $request->input('sitemap_priority', 0.8),
            'sitemap_changefreq' => $request->input('sitemap_changefreq', 'weekly'),
            
            // Campos booleanos (como en tinker)
            'sitemap_include' => $request->has('sitemap_include') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        Log::info('Datos preparados para guardar:', $dataToSave);

        try {
            // USAR EXACTAMENTE EL MISMO CÓDIGO QUE FUNCIONÓ EN TINKER
            $seo = Seo::updateOrCreate(
                ['page_id' => $page->id],
                $dataToSave
            );

            Log::info('SEO guardado exitosamente:', [
                'seo_id' => $seo->id,
                'page_id' => $seo->page_id,
                'was_recently_created' => $seo->wasRecentlyCreated,
                'meta_title' => $seo->meta_title
            ]);

            // Verificar que realmente se guardó
            $verification = Seo::find($seo->id);
            Log::info('Verificación en BD:', [
                'found' => !is_null($verification),
                'meta_title' => $verification->meta_title ?? 'NULL',
                'sitemap_include' => $verification->sitemap_include ?? 'NULL'
            ]);

            return redirect()->route('admin.seo.edit', $page)
                             ->with('success', 'Configuración SEO actualizada correctamente');

        } catch (\Exception $e) {
            Log::error('ERROR al guardar SEO:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                             ->withInput()
                             ->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()]);
        }
    }
}