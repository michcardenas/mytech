<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Proyecto;
use App\Models\Product;
use App\Models\Category;

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

        // Agregar productos y categorías si existen
        $this->addProducts($sitemap);
        $this->addCategories($sitemap);

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
                Url::create('/proyectos/' . $proyecto->slug)
                    ->setLastModificationDate($proyecto->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.7)
            );
        }

        $this->info('✓ ' . $proyectos->count() . ' proyectos agregados');
    }

    /**
     * Agregar productos al sitemap (si existen)
     */
    protected function addProducts(Sitemap $sitemap): void
    {
        if (!class_exists(Product::class)) {
            return;
        }

        $products = Product::all();

        foreach ($products as $product) {
            $sitemap->add(
                Url::create('/products/' . $product->id)
                    ->setLastModificationDate($product->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        }

        if ($products->count() > 0) {
            $this->info('✓ ' . $products->count() . ' productos agregados');
        }
    }

    /**
     * Agregar categorías al sitemap (si existen)
     */
    protected function addCategories(Sitemap $sitemap): void
    {
        if (!class_exists(Category::class)) {
            return;
        }

        $categories = Category::all();

        foreach ($categories as $category) {
            $sitemap->add(
                Url::create('/categories/' . $category->id)
                    ->setLastModificationDate($category->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.5)
            );
        }

        if ($categories->count() > 0) {
            $this->info('✓ ' . $categories->count() . ' categorías agregadas');
        }
    }
}
