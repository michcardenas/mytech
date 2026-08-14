<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Proyecto;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generar sitemap.xml dinámico con todas las URLs del sitio';

    public function handle()
    {
        $this->info('Generando sitemap...');

        // Crear el sitemap base
        $sitemap = Sitemap::create();

        // Agregar rutas estáticas principales
        $this->addStaticUrls($sitemap);

        // Agregar proyectos dinámicos
        $this->addProyectos($sitemap);

        // Agregar blogs
        $this->addBlogs($sitemap);

        // Agregar landing pages comerciales (type = landing)
        $this->addLandings($sitemap);

        // Agregar productos y categorías si existen (DESACTIVADO - residuos de prueba)
        // $this->addProducts($sitemap);
        // $this->addCategories($sitemap);

        // Guardar el sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generado exitosamente en public/sitemap.xml');

        return Command::SUCCESS;
    }

    /**
     * Agregar URLs estáticas al sitemap
     */
    protected function addStaticUrls(Sitemap $sitemap): void
    {
        $staticUrls = [
            ['url' => '/', 'priority' => 1.0, 'frequency' => Url::CHANGE_FREQUENCY_DAILY],
            ['url' => '/servicios', 'priority' => 0.9, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/proyectos', 'priority' => 0.9, 'frequency' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/sobre-nosotros', 'priority' => 0.7, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/contacto', 'priority' => 0.8, 'frequency' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($staticUrls as $urlData) {
            $sitemap->add(
                Url::create($urlData['url'])
                    ->setLastModificationDate(now())
                    ->setChangeFrequency($urlData['frequency'])
                    ->setPriority($urlData['priority'])
            );
        }

        $this->info('✓ URLs estáticas agregadas');
    }

    /**
     * Agregar proyectos dinámicos al sitemap
     */
    protected function addProyectos(Sitemap $sitemap): void
    {
        $proyectos = Proyecto::activos()->get();

        foreach ($proyectos as $proyecto) {
            $sitemap->add(
                Url::create('/proyectos/'.$proyecto->slug)
                    ->setLastModificationDate($proyecto->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        }

        $this->info('✓ '.$proyectos->count().' proyectos agregados');
    }

    /**
     * Agregar blogs publicados al sitemap
     */
    protected function addBlogs(Sitemap $sitemap): void
    {
        // Agregar página principal del blog
        $sitemap->add(
            Url::create('/blog')
                ->setLastModificationDate(now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8)
        );

        // Agregar cada artículo de blog publicado
        $blogs = Page::published()
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($blogs as $blog) {
            $sitemap->add(
                Url::create('/blog/'.$blog->slug)
                    ->setLastModificationDate($blog->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.7)
            );
        }

        // Agregar páginas de categorías que tengan blogs
        $categoriesWithBlogs = Page::published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        foreach ($categoriesWithBlogs as $category) {
            $sitemap->add(
                Url::create('/blog/categoria/'.$category)
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        }

        $this->info('✓ '.$blogs->count().' blogs agregados');
        $this->info('✓ '.$categoriesWithBlogs->count().' categorías de blog agregadas');
    }

    /**
     * Agregar landing pages comerciales activas al sitemap.
     *
     * Son páginas de servicio a medida (type = landing) servidas por el
     * catch-all /{slug}. Prioridad alta (0.9) porque son páginas de captación.
     * Respeta sitemap_include / sitemap_priority del registro SEO si existen.
     */
    protected function addLandings(Sitemap $sitemap): void
    {
        $landings = Page::where('type', 'landing')
            ->where('is_active', true)
            ->with('seo')
            ->get();

        $count = 0;

        foreach ($landings as $landing) {
            $seo = $landing->seo;

            if ($seo && $seo->sitemap_include === false) {
                continue;
            }

            $priority = $seo && $seo->sitemap_priority ? (float) $seo->sitemap_priority : 0.9;

            $sitemap->add(
                Url::create('/'.$landing->slug)
                    ->setLastModificationDate($landing->updated_at ?? now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority($priority)
            );

            $count++;
        }

        $this->info('✓ '.$count.' landing pages agregadas');
    }

    /**
     * Agregar productos al sitemap (si existen)
     */
    protected function addProducts(Sitemap $sitemap): void
    {
        if (! class_exists(Product::class)) {
            return;
        }

        $products = Product::all();

        foreach ($products as $product) {
            $sitemap->add(
                Url::create('/products/'.$product->id)
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        }

        if ($products->count() > 0) {
            $this->info('✓ '.$products->count().' productos agregados');
        }
    }

    /**
     * Agregar categorías al sitemap (si existen)
     */
    protected function addCategories(Sitemap $sitemap): void
    {
        if (! class_exists(Category::class)) {
            return;
        }

        $categories = Category::all();

        foreach ($categories as $category) {
            $sitemap->add(
                Url::create('/categories/'.$category->id)
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.5)
            );
        }

        if ($categories->count() > 0) {
            $this->info('✓ '.$categories->count().' categorías agregadas');
        }
    }
}
