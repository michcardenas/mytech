<?php
// app/Http/Controllers/Admin/SeoController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    /**
     * Mostrar el formulario de edición de SEO
     */
    public function edit(Page $page)
    {
        // Obtener o crear SEO para la página
        $seo = $page->seo ?: new Seo(['page_id' => $page->id]);
        
        return view('admin.seo.edit', compact('page', 'seo'));
    }

    /**
     * Actualizar la configuración SEO
     */
    public function update(Request $request, Page $page)
    {
        $validatedData = $request->validate([
            'meta_title' => 'nullable|string|max:150',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'robots' => 'required|in:index,follow,noindex,follow,index,nofollow,noindex,nofollow',
            'og_title' => 'nullable|string|max:150',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|url|max:500',
            'og_type' => 'required|in:website,article,product,business.business',
            'og_url' => 'nullable|url|max:500',
            'og_site_name' => 'nullable|string|max:100',
            'twitter_card' => 'required|in:summary,summary_large_image,app,player',
            'twitter_title' => 'nullable|string|max:150',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|url|max:500',
            'twitter_site' => 'nullable|string|max:50',
            'twitter_creator' => 'nullable|string|max:50',
            'focus_keyword' => 'nullable|string|max:100',
            'breadcrumb_title' => 'nullable|string',
            'sitemap_include' => 'boolean',
            'sitemap_priority' => 'required|numeric|min:0.1|max:1.0',
            'sitemap_changefreq' => 'required|in:always,hourly,daily,weekly,monthly,yearly,never',
            'is_active' => 'boolean'
        ]);

        // Crear o actualizar SEO
        $page->seo()->updateOrCreate(
            ['page_id' => $page->id],
            $validatedData
        );

        return redirect()->route('admin.seo.edit', $page->id)
                         ->with('success', 'Configuración SEO actualizada correctamente');
    }
}