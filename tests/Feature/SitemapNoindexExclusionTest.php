<?php

namespace Tests\Feature;

use App\Models\Proyecto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapNoindexExclusionTest extends TestCase
{
    use RefreshDatabase;

    private string $sitemapPath;

    private ?string $sitemapBackup = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sitemapPath = public_path('sitemap.xml');

        if (file_exists($this->sitemapPath)) {
            $this->sitemapBackup = file_get_contents($this->sitemapPath);
        }
    }

    protected function tearDown(): void
    {
        if ($this->sitemapBackup !== null) {
            file_put_contents($this->sitemapPath, $this->sitemapBackup);
        } elseif (file_exists($this->sitemapPath)) {
            unlink($this->sitemapPath);
        }

        parent::tearDown();
    }

    public function test_noindex_proyectos_are_excluded_from_sitemap(): void
    {
        config()->set('seo.noindex_proyecto_slugs', ['proyecto-basura']);

        $base = [
            'pais' => 'Colombia',
            'categoria' => 'tech',
            'badge_text' => 'Caso',
            'descripcion' => 'Descripción de prueba.',
            'tecnologias' => ['Laravel'],
            'activo' => true,
        ];

        Proyecto::create([...$base, 'nombre' => 'Proyecto Bueno', 'slug' => 'proyecto-bueno']);
        Proyecto::create([...$base, 'nombre' => 'Proyecto Basura', 'slug' => 'proyecto-basura']);

        $this->artisan('sitemap:generate')->assertSuccessful();

        $sitemap = file_get_contents($this->sitemapPath);

        $this->assertStringContainsString('/proyectos/proyecto-bueno', $sitemap);
        $this->assertStringNotContainsString('/proyectos/proyecto-basura', $sitemap);
    }
}
