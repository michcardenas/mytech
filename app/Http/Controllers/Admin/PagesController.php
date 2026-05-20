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
        $page->load('sections', 'seo');

        // Special handling for home page (Inicio)
        if ($page->slug === 'inicio' || $page->slug === 'home') {
            return view('admin.pages.edit-home', compact('page'));
        }

        // Special handling for /sobre-nosotros (manifiesto cinemático)
        if ($page->slug === 'sobre-nosotros' || $page->slug === 'sobre nosotros') {
            return view('admin.pages.edit-sobre-nosotros', compact('page'));
        }

        // Special handling for blog posts
        if ($page->type === 'blog') {
            return view('admin.pages.edit-blog', compact('page'));
        }

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Definición central de todos los campos editables de /sobre-nosotros.
     */
    private function sobreNosotrosContentFields(): array
    {
        return [
            // Labels de capítulos
            'cap0_label'   => ['rule' => 'nullable|string|max:64'],
            'cap1_label'   => ['rule' => 'nullable|string|max:64'],
            'cap2_label'   => ['rule' => 'nullable|string|max:64'],
            'cap3_label'   => ['rule' => 'nullable|string|max:64'],
            'cap4_label'   => ['rule' => 'nullable|string|max:64'],
            'cap5_label'   => ['rule' => 'nullable|string|max:64'],

            // Prólogo
            'prologo_title'   => ['rule' => 'nullable|string|max:255'],
            'prologo_sub'     => ['rule' => 'nullable|string|max:1000'],
            'founding_year'   => ['rule' => 'nullable|string|max:8'],

            // Tesis
            'tesis_text'         => ['rule' => 'nullable|string|max:1000'],
            'tesis_accent_words' => ['rule' => 'nullable|string|max:255'],

            // Números
            'stat_1_num'   => ['rule' => 'nullable|string|max:16'],
            'stat_1_suf'   => ['rule' => 'nullable|string|max:8'],
            'stat_1_label' => ['rule' => 'nullable|string|max:64'],
            'stat_1_desc'  => ['rule' => 'nullable|string|max:255'],
            'stat_1_delta' => ['rule' => 'nullable|string|max:32'],
            'stat_1_pre'   => ['rule' => 'nullable|string|max:255'],
            'stat_1_post'  => ['rule' => 'nullable|string|max:255'],
            'stat_2_num'   => ['rule' => 'nullable|string|max:16'],
            'stat_2_suf'   => ['rule' => 'nullable|string|max:8'],
            'stat_2_label' => ['rule' => 'nullable|string|max:64'],
            'stat_2_desc'  => ['rule' => 'nullable|string|max:255'],
            'stat_2_delta' => ['rule' => 'nullable|string|max:32'],
            'stat_2_pre'   => ['rule' => 'nullable|string|max:255'],
            'stat_2_post'  => ['rule' => 'nullable|string|max:255'],
            'stat_3_num'   => ['rule' => 'nullable|string|max:16'],
            'stat_3_suf'   => ['rule' => 'nullable|string|max:8'],
            'stat_3_label' => ['rule' => 'nullable|string|max:64'],
            'stat_3_desc'  => ['rule' => 'nullable|string|max:255'],
            'stat_3_delta' => ['rule' => 'nullable|string|max:32'],
            'stat_3_pre'   => ['rule' => 'nullable|string|max:255'],
            'stat_3_post'  => ['rule' => 'nullable|string|max:255'],
            'stat_4_num'   => ['rule' => 'nullable|string|max:16'],
            'stat_4_suf'   => ['rule' => 'nullable|string|max:8'],
            'stat_4_label' => ['rule' => 'nullable|string|max:64'],
            'stat_4_desc'  => ['rule' => 'nullable|string|max:255'],
            'stat_4_delta' => ['rule' => 'nullable|string|max:32'],
            'stat_4_pre'   => ['rule' => 'nullable|string|max:255'],
            'stat_4_post'  => ['rule' => 'nullable|string|max:255'],
            'numeros_foot' => ['rule' => 'nullable|string|max:500'],

            // Credo (7 declaraciones)
            'credo_headline' => ['rule' => 'nullable|string|max:255'],
            'credo_1'        => ['rule' => 'nullable|string|max:255'],
            'credo_1_note'   => ['rule' => 'nullable|string|max:500'],
            'credo_2'        => ['rule' => 'nullable|string|max:255'],
            'credo_2_note'   => ['rule' => 'nullable|string|max:500'],
            'credo_3'        => ['rule' => 'nullable|string|max:255'],
            'credo_3_note'   => ['rule' => 'nullable|string|max:500'],
            'credo_4'        => ['rule' => 'nullable|string|max:255'],
            'credo_4_note'   => ['rule' => 'nullable|string|max:500'],
            'credo_5'        => ['rule' => 'nullable|string|max:255'],
            'credo_5_note'   => ['rule' => 'nullable|string|max:500'],
            'credo_6'        => ['rule' => 'nullable|string|max:255'],
            'credo_6_note'   => ['rule' => 'nullable|string|max:500'],
            'credo_7'        => ['rule' => 'nullable|string|max:255'],
            'credo_7_note'   => ['rule' => 'nullable|string|max:500'],

            // Gente (hasta 4 miembros)
            'gente_head'        => ['rule' => 'nullable|string|max:255'],
            'gente_head_accent' => ['rule' => 'nullable|string|max:255'],
            'team_1_name'    => ['rule' => 'nullable|string|max:128'],
            'team_1_role'    => ['rule' => 'nullable|string|max:128'],
            'team_1_bio'     => ['rule' => 'nullable|string|max:500'],
            'team_1_quote'   => ['rule' => 'nullable|string|max:300'],
            'team_1_linkedin' => ['rule' => 'nullable|string|max:500'],
            'team_1_github'   => ['rule' => 'nullable|string|max:500'],
            'team_1_site'     => ['rule' => 'nullable|string|max:500'],
            'team_2_name'    => ['rule' => 'nullable|string|max:128'],
            'team_2_role'    => ['rule' => 'nullable|string|max:128'],
            'team_2_bio'     => ['rule' => 'nullable|string|max:500'],
            'team_2_quote'   => ['rule' => 'nullable|string|max:300'],
            'team_2_linkedin' => ['rule' => 'nullable|string|max:500'],
            'team_2_github'   => ['rule' => 'nullable|string|max:500'],
            'team_2_site'     => ['rule' => 'nullable|string|max:500'],
            'team_3_name'    => ['rule' => 'nullable|string|max:128'],
            'team_3_role'    => ['rule' => 'nullable|string|max:128'],
            'team_3_bio'     => ['rule' => 'nullable|string|max:500'],
            'team_3_quote'   => ['rule' => 'nullable|string|max:300'],
            'team_3_linkedin' => ['rule' => 'nullable|string|max:500'],
            'team_3_github'   => ['rule' => 'nullable|string|max:500'],
            'team_3_site'     => ['rule' => 'nullable|string|max:500'],

            // Créditos (8 bloques de roles)
            'creditos_head' => ['rule' => 'nullable|string|max:255'],
            'cred_1_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_1_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_2_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_2_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_3_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_3_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_4_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_4_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_5_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_5_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_6_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_6_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_7_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_7_lista'  => ['rule' => 'nullable|string|max:1000'],
            'cred_8_rol'    => ['rule' => 'nullable|string|max:128'],
            'cred_8_lista'  => ['rule' => 'nullable|string|max:1000'],

            // CTA final
            'cta_pre'            => ['rule' => 'nullable|string|max:128'],
            'cta_title'          => ['rule' => 'nullable|string|max:255'],
            'cta_title_accent'   => ['rule' => 'nullable|string|max:128'],
            'cta_button_text'    => ['rule' => 'nullable|string|max:128'],
            'cta_secondary_text' => ['rule' => 'nullable|string|max:128'],

            // Sociales
            'social_linkedin'  => ['rule' => 'nullable|string|max:500'],
            'social_instagram' => ['rule' => 'nullable|string|max:500'],
            'social_github'    => ['rule' => 'nullable|string|max:500'],
        ];
    }

    /**
     * Update the specified page in storage.
     */
    /**
     * Definición central de todos los campos editables de la home.
     * Cualquier campo nuevo se agrega aquí y queda automáticamente
     * validado y persistido en pages.content (JSON).
     *
     * Formato: 'campo' => ['rule' => 'reglas Laravel']
     */
    private function homeContentFields(): array
    {
        return [
            // ───── Hero ─────
            'hero_badge'             => ['rule' => 'nullable|string|max:255'],
            'hero_title'             => ['rule' => 'nullable|string|max:255'],
            'hero_description'       => ['rule' => 'nullable|string'],
            'hero_button_text'       => ['rule' => 'nullable|string|max:255'],
            'hero_whatsapp_text'     => ['rule' => 'nullable|string|max:255'],
            'hero_whatsapp_number'   => ['rule' => 'nullable|string|max:32'],
            'hero_whatsapp_message'  => ['rule' => 'nullable|string|max:500'],
            'benefit_1'              => ['rule' => 'nullable|string|max:255'],
            'benefit_2'              => ['rule' => 'nullable|string|max:255'],
            'benefit_3'              => ['rule' => 'nullable|string|max:255'],
            'hero_stat_1_value'      => ['rule' => 'nullable|string|max:16'],
            'hero_stat_1_label'      => ['rule' => 'nullable|string|max:64'],
            'hero_stat_2_value'      => ['rule' => 'nullable|string|max:16'],
            'hero_stat_2_label'      => ['rule' => 'nullable|string|max:64'],
            'hero_stat_3_value'      => ['rule' => 'nullable|string|max:16'],
            'hero_stat_3_label'      => ['rule' => 'nullable|string|max:64'],

            // ───── Casos / proyectos ─────
            'casos_eyebrow'          => ['rule' => 'nullable|string|max:255'],
            'clients_title'          => ['rule' => 'nullable|string|max:255'],
            'clients_subtitle'       => ['rule' => 'nullable|string|max:500'],
            'clients_button_text'    => ['rule' => 'nullable|string|max:255'],
            'casos_empty_message'    => ['rule' => 'nullable|string|max:500'],

            // ───── Servicios — header de sección ─────
            'servicios_eyebrow'      => ['rule' => 'nullable|string|max:255'],
            'servicios_title'        => ['rule' => 'nullable|string|max:255'],
            'servicios_subtitle'     => ['rule' => 'nullable|string|max:500'],
            'servicios_link_text'    => ['rule' => 'nullable|string|max:255'],

            // ───── Proceso "Cómo trabajamos" ─────
            'proceso_eyebrow'        => ['rule' => 'nullable|string|max:255'],
            'proceso_title_main'     => ['rule' => 'nullable|string|max:255'],
            'proceso_title_accent'   => ['rule' => 'nullable|string|max:64'],
            'proceso_subtitle'       => ['rule' => 'nullable|string|max:500'],
            'proceso_swipe_hint'     => ['rule' => 'nullable|string|max:64'],
            'proceso_paso_1_num'     => ['rule' => 'nullable|string|max:8'],
            'proceso_paso_1_title'   => ['rule' => 'nullable|string|max:64'],
            'proceso_paso_1_lead'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_1_desc'    => ['rule' => 'nullable|string|max:500'],
            'proceso_paso_1_tags'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_2_num'     => ['rule' => 'nullable|string|max:8'],
            'proceso_paso_2_title'   => ['rule' => 'nullable|string|max:64'],
            'proceso_paso_2_lead'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_2_desc'    => ['rule' => 'nullable|string|max:500'],
            'proceso_paso_2_tags'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_3_num'     => ['rule' => 'nullable|string|max:8'],
            'proceso_paso_3_title'   => ['rule' => 'nullable|string|max:64'],
            'proceso_paso_3_lead'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_3_desc'    => ['rule' => 'nullable|string|max:500'],
            'proceso_paso_3_tags'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_4_num'     => ['rule' => 'nullable|string|max:8'],
            'proceso_paso_4_title'   => ['rule' => 'nullable|string|max:64'],
            'proceso_paso_4_lead'    => ['rule' => 'nullable|string|max:255'],
            'proceso_paso_4_desc'    => ['rule' => 'nullable|string|max:500'],
            'proceso_paso_4_tags'    => ['rule' => 'nullable|string|max:255'],

            // ───── Stack tecnológico ─────
            'stack_eyebrow'          => ['rule' => 'nullable|string|max:255'],
            'stack_title'            => ['rule' => 'nullable|string|max:255'],
            'stack_subtitle'         => ['rule' => 'nullable|string|max:500'],

            // ───── CTA dark intermedio ─────
            'cta_eyebrow'            => ['rule' => 'nullable|string|max:255'],
            'cta_title_main'         => ['rule' => 'nullable|string|max:255'],
            'cta_title_accent'       => ['rule' => 'nullable|string|max:64'],
            'cta_subtitle'           => ['rule' => 'nullable|string|max:500'],
            'cta_whatsapp_text'      => ['rule' => 'nullable|string|max:255'],
            'cta_whatsapp_number'    => ['rule' => 'nullable|string|max:32'],
            'cta_whatsapp_message'   => ['rule' => 'nullable|string|max:500'],
            'cta_form_button_text'   => ['rule' => 'nullable|string|max:255'],

            // ───── Footer ─────
            'footer_intro'           => ['rule' => 'nullable|string|max:500'],
            'footer_phone'           => ['rule' => 'nullable|string|max:64'],
            'footer_phone_label'     => ['rule' => 'nullable|string|max:128'],
            'footer_facebook_url'    => ['rule' => 'nullable|string|max:500'],
            'footer_instagram_url'   => ['rule' => 'nullable|string|max:500'],
            'footer_whatsapp_url'    => ['rule' => 'nullable|string|max:500'],
            'footer_signature'       => ['rule' => 'nullable|string|max:255'],

            // ───── Otros (legacy, mantener compat) ─────
            'phone_label'            => ['rule' => 'nullable|string|max:255'],
            'laptop_label'           => ['rule' => 'nullable|string|max:255'],
            'success_badge_1'        => ['rule' => 'nullable|string|max:255'],
            'success_badge_2'        => ['rule' => 'nullable|string|max:255'],
            'success_badge_3'        => ['rule' => 'nullable|string|max:255'],
        ];
    }

    public function update(Request $request, Page $page)
    {
        // Special handling for home page
        if ($page->slug === 'inicio' || $page->slug === 'home') {
            $fields = $this->homeContentFields();

            // Validar todos los campos + el archivo del hero
            $rules = collect($fields)->mapWithKeys(fn($v, $k) => [$k => $v['rule']])->toArray();
            $rules['hero_media']        = 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime|max:20480';
            $rules['remove_hero_media'] = 'nullable|in:0,1';
            $request->validate($rules);

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

            // Construir el JSON: copiar TODOS los valores existentes (preserva keys
            // que no estén en homeContentFields), luego sobreescribir con lo que
            // venga del request, y hero_media siempre desde la lógica de upload.
            $homeContent = $existing;
            foreach (array_keys($fields) as $key) {
                $homeContent[$key] = $request->input($key);
            }
            $homeContent['hero_media'] = $heroMediaPath;

            $page->update([
                'title'   => $request->hero_title ?? $page->title,
                'slug'    => $page->slug,
                'content' => json_encode($homeContent, JSON_UNESCAPED_UNICODE),
            ]);
        } elseif ($page->slug === 'sobre-nosotros' || $page->slug === 'sobre nosotros') {
            // ════════════ /sobre-nosotros — manifiesto cinemático ════════════
            $fields = $this->sobreNosotrosContentFields();
            $rules = collect($fields)->mapWithKeys(fn($v, $k) => [$k => $v['rule']])->toArray();

            // SEO fields
            $rules = array_merge($rules, [
                'meta_title'          => 'nullable|string|max:255',
                'meta_description'    => 'nullable|string|max:500',
                'focus_keyword'       => 'nullable|string|max:128',
                'meta_keywords'       => 'nullable|string|max:500',
                'canonical_url'       => 'nullable|string|max:500',
                'robots'              => 'nullable|string|max:64',
                'og_title'            => 'nullable|string|max:255',
                'og_description'     => 'nullable|string|max:500',
                'og_image'            => 'nullable|image|max:4096',
                'twitter_card'        => 'nullable|string|max:64',
                'twitter_title'       => 'nullable|string|max:255',
                'twitter_description' => 'nullable|string|max:500',
                'twitter_image'       => 'nullable|image|max:4096',
                'sitemap_priority'    => 'nullable|string|max:8',
                'sitemap_changefreq'  => 'nullable|string|max:32',
            ]);
            $request->validate($rules);

            // Preservar contenido existente + sobrescribir con request
            $existing = json_decode($page->content ?? '{}', true) ?: [];
            $content  = $existing;
            foreach (array_keys($fields) as $key) {
                $content[$key] = $request->input($key);
            }

            $page->update([
                'title'   => $request->input('prologo_title') ?? $page->title,
                'slug'    => 'sobre-nosotros',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
            ]);

            // SEO — upsert
            $seo = $page->seo ?: new \App\Models\Seo(['page_id' => $page->id]);
            $seo->page_id            = $page->id;
            $seo->meta_title         = $request->input('meta_title');
            $seo->meta_description   = $request->input('meta_description');
            $seo->focus_keyword      = $request->input('focus_keyword');
            $seo->meta_keywords      = $request->input('meta_keywords');
            $seo->canonical_url      = $request->input('canonical_url');
            $seo->robots             = $request->input('robots', 'index,follow');
            $seo->og_title           = $request->input('og_title');
            $seo->og_description     = $request->input('og_description');
            $seo->og_type            = 'website';
            $seo->twitter_card       = $request->input('twitter_card', 'summary_large_image');
            $seo->twitter_title      = $request->input('twitter_title');
            $seo->twitter_description= $request->input('twitter_description');
            $seo->sitemap_priority   = $request->input('sitemap_priority', 0.8);
            $seo->sitemap_changefreq = $request->input('sitemap_changefreq', 'monthly');
            $seo->sitemap_include    = true;
            $seo->is_active          = true;

            if ($request->hasFile('og_image')) {
                if ($seo->og_image) Storage::disk('public')->delete($seo->og_image);
                $seo->og_image = $request->file('og_image')->store('seo/og', 'public');
            }
            if ($request->hasFile('twitter_image')) {
                if ($seo->twitter_image) Storage::disk('public')->delete($seo->twitter_image);
                $seo->twitter_image = $request->file('twitter_image')->store('seo/twitter', 'public');
            }
            $seo->save();

            return redirect()->route('admin.pages.edit', $page)
                ->with('success', 'Sobre nosotros actualizado correctamente.');
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