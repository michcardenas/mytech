<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
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
        // Redirect to index since we don't allow creating new pages
        return redirect()->route('admin.pages.index')
            ->with('info', 'Solo puedes editar páginas existentes.');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request)
    {
        // Redirect to index since we don't allow creating new pages
        return redirect()->route('admin.pages.index')
            ->with('info', 'Solo puedes editar páginas existentes.');
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
                'benefit_1' => 'nullable|string|max:255',
                'benefit_2' => 'nullable|string|max:255',
                'benefit_3' => 'nullable|string|max:255',
                'clients_title' => 'nullable|string|max:255',
                'clients_subtitle' => 'nullable|string|max:255',
                'clients_button_text' => 'nullable|string|max:255',
                'phone_label' => 'nullable|string|max:255',
                'laptop_label' => 'nullable|string|max:255',
                'success_badge_1' => 'nullable|string|max:255',
                'success_badge_2' => 'nullable|string|max:255',
                'success_badge_3' => 'nullable|string|max:255',
            ]);

            // Prepare the content as JSON for home page
            $homeContent = [
                'hero_badge' => $request->hero_badge,
                'hero_title' => $request->hero_title,
                'hero_description' => $request->hero_description,
                'hero_button_text' => $request->hero_button_text,
                'benefit_1' => $request->benefit_1,
                'benefit_2' => $request->benefit_2,
                'benefit_3' => $request->benefit_3,
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
        $page->load('sections');
        return view('admin.pages.sections', compact('page'));
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
            'is_active' => 'boolean',
        ]);

        $section->update([
            'name' => $request->name,
            'title' => $request->title,
            'content' => $request->content,
            'order' => $request->order,
            'is_active' => $request->boolean('is_active'),
        ]);

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