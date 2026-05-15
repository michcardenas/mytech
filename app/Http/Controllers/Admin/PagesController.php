<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $pages = Page::with('sections')->paginate(10);

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'type' => 'required|in:page,landing,blog',
            'content' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $page = Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'type' => $request->type,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active', true),
            'author' => $request->type === 'blog' ? (auth()->user()->name ?? 'Admin') : null,
        ]);

        // For blogs, redirect to edit page to add content
        if ($page->type === 'blog') {
            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Blog creado exitosamente. Ahora puedes agregar el contenido.');
        }

        return redirect()->route('admin.pages.sections', $page)
            ->with('success', 'Página creada exitosamente. Ahora puedes agregar secciones.');
    }

    /**
     * Display the specified page.
     */
    public function show(Page $page)
    {
        $page->load('sections');
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page)
    {
        $page->load('sections');

        // Special handling for home page (Inicio)
        if ($page->slug === 'inicio' || $page->slug === 'home') {
            return view('admin.pages.edit-home', compact('page'));
        }

        // Special handling for blog posts
        if ($page->type === 'blog') {
            return view('admin.pages.edit-blog', compact('page'));
        }

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page)
    {
        // Special handling for home page
        if ($page->slug === 'inicio' || $page->slug === 'home') {
            $request->validate([
                'hero_badge' => 'nullable|string|max:255',
                'hero_title' => 'nullable|string|max:255',
                'hero_description' => 'nullable|string',
                'hero_button_text' => 'nullable|string|max:255',
                'hero_media' => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime|max:20480',
                'remove_hero_media' => 'nullable|in:0,1',
                'benefit_1' => 'nullable|string|max:255',
                'benefit_2' => 'nullable|string|max:255',
                'benefit_3' => 'nullable|string|max:255',
                'hero_stat_1_value' => 'nullable|string|max:16',
                'hero_stat_1_label' => 'nullable|string|max:64',
                'hero_stat_2_value' => 'nullable|string|max:16',
                'hero_stat_2_label' => 'nullable|string|max:64',
                'hero_stat_3_value' => 'nullable|string|max:16',
                'hero_stat_3_label' => 'nullable|string|max:64',
                'clients_title' => 'nullable|string|max:255',
                'clients_subtitle' => 'nullable|string|max:255',
                'clients_button_text' => 'nullable|string|max:255',
                'phone_label' => 'nullable|string|max:255',
                'laptop_label' => 'nullable|string|max:255',
                'success_badge_1' => 'nullable|string|max:255',
                'success_badge_2' => 'nullable|string|max:255',
                'success_badge_3' => 'nullable|string|max:255',
            ]);

            $existing = json_decode($page->content ?? '{}', true) ?: [];
            $heroMediaPath = $existing['hero_media'] ?? ($existing['hero_image'] ?? null);

            if ($request->hasFile('hero_media')) {
                if ($heroMediaPath) {
                    Storage::disk('public')->delete($heroMediaPath);
                }
                $heroMediaPath = $request->file('hero_media')->store('home/hero', 'public');
            } elseif ($request->input('remove_hero_media') == '1') {
                if ($heroMediaPath) {
                    Storage::disk('public')->delete($heroMediaPath);
                }
                $heroMediaPath = null;
            }

            // Prepare the content as JSON for home page
            $homeContent = [
                'hero_badge' => $request->hero_badge,
                'hero_title' => $request->hero_title,
                'hero_description' => $request->hero_description,
                'hero_button_text' => $request->hero_button_text,
                'hero_media' => $heroMediaPath,
                'benefit_1' => $request->benefit_1,
                'benefit_2' => $request->benefit_2,
                'benefit_3' => $request->benefit_3,
                'hero_stat_1_value' => $request->hero_stat_1_value,
                'hero_stat_1_label' => $request->hero_stat_1_label,
                'hero_stat_2_value' => $request->hero_stat_2_value,
                'hero_stat_2_label' => $request->hero_stat_2_label,
                'hero_stat_3_value' => $request->hero_stat_3_value,
                'hero_stat_3_label' => $request->hero_stat_3_label,
                'clients_title' => $request->clients_title,
                'clients_subtitle' => $request->clients_subtitle,
                'clients_button_text' => $request->clients_button_text,
                'phone_label' => $request->phone_label,
                'laptop_label' => $request->laptop_label,
                'success_badge_1' => $request->success_badge_1,
                'success_badge_2' => $request->success_badge_2,
                'success_badge_3' => $request->success_badge_3,
            ];

            $page->update([
                'title' => $request->hero_title ?? $page->title,
                'slug' => $page->slug, // Don't change slug for home page
                'content' => json_encode($homeContent),
            ]);
        } elseif ($page->type === 'blog') {
            // Blog post validation and update
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
                'content' => 'nullable|string',
                'excerpt' => 'nullable|string|max:500',
                'featured_image' => 'nullable|image|max:2048',
                'category' => 'nullable|string|max:100',
                'tags' => 'nullable|string|max:500',
                'author' => 'nullable|string|max:255',
                'published_at' => 'nullable|date',
                'reading_time' => 'nullable|integer|min:1',
                'is_active' => 'sometimes|in:0,1,true,false,on',
            ]);

            $updateData = [
                'title' => $request->title,
                'slug' => Str::slug($request->slug),
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'category' => $request->category,
                'tags' => $request->tags,
                'author' => $request->author,
                'published_at' => $request->published_at,
                'reading_time' => $request->reading_time,
                'is_active' => $request->boolean('is_active'),
            ];

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($page->featured_image) {
                    Storage::disk('public')->delete($page->featured_image);
                }
                $updateData['featured_image'] = $request->file('featured_image')->store('blog/featured', 'public');
            }

            // Handle featured image removal
            if ($request->input('remove_featured_image') == '1') {
                if ($page->featured_image) {
                    Storage::disk('public')->delete($page->featured_image);
                }
                $updateData['featured_image'] = null;
            }

            $page->update($updateData);

            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Artículo actualizado exitosamente.');
        } else {
            // Regular page validation and update
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
                'content' => 'nullable|string',
            ]);

            $page->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'content' => $request->content,
            ]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Página actualizada exitosamente.');
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page)
    {
        // Delete all sections first
        $page->sections()->delete();

        // Delete the page
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Página eliminada exitosamente.');
    }

    /**
     * Show the form for managing sections of a page.
     */
    public function sections(Page $page)
    {
        $sections = $page->sections()->orderBy('order')->get();
        return view('admin.pages.sections', compact('page', 'sections'));
    }

    /**
     * Store a new section for a page.
     */
    public function storeSection(Request $request, Page $page)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $section = $page->sections()->create([
            'name' => $request->name,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order ?? ($page->sections()->max('order') + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pages.sections', $page)
            ->with('success', 'Sección creada exitosamente.');
    }

    /**
     * Update a section.
     */
    public function updateSection(Request $request, Page $page, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes|in:0,1,true,false',
            'images.*' => 'nullable|image|max:10240', // Max 10MB por imagen
            'video_urls' => 'nullable|string',
            'custom_data' => 'nullable|array', // Validar custom_data como array
        ]);

        // Actualizar campos básicos
        $section->update([
            'name' => $request->name,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Manejar custom_data (datos específicos de cada tipo de sección)
        if ($request->has('custom_data')) {
            $customData = $request->custom_data;

            // Limpiar arrays vacíos y valores nulos
            $customData = array_filter($customData, function($value) {
                if (is_array($value)) {
                    return !empty(array_filter($value));
                }
                return !is_null($value) && $value !== '';
            });

            $section->custom_data = $customData;
            $section->save();
        }

        // Manejar subida de nuevas imágenes
        if ($request->hasFile('images')) {
            $existingImages = $section->getImagesArray();

            foreach ($request->file('images') as $image) {
                $path = $image->store('sections/images', 'public');
                $existingImages[] = $path;
            }

            $section->setImagesArray($existingImages);
            $section->save();
        }

        // Manejar URLs de videos
        if ($request->has('video_urls')) {
            $videoUrls = array_filter(explode("\n", $request->video_urls));
            $videoUrls = array_map('trim', $videoUrls);
            $section->setVideosArray($videoUrls);
            $section->save();
        }

        return redirect()->route('admin.pages.sections', $page)
            ->with('success', 'Sección actualizada exitosamente.');
    }

    /**
     * Delete a section.
     */
    public function destroySection(Page $page, Section $section)
    {
        $section->delete();

        return redirect()->route('admin.pages.sections', $page)
            ->with('success', 'Sección eliminada exitosamente.');
    }

    /**
     * Toggle section status.
     */
    public function toggleSection(Page $page, Section $section)
    {
        $section->update([
            'is_active' => !$section->is_active
        ]);

        $status = $section->is_active ? 'activada' : 'desactivada';

        return response()->json([
            'success' => true,
            'message' => "Sección {$status} exitosamente.",
            'is_active' => $section->is_active
        ]);
    }
}