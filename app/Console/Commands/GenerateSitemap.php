<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generar sitemap.xml dinámico';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')
                ->setLastModificationDate(now())
                ->setChangeFrequency('weekly')
                ->setPriority(1.0))
            ->add(Url::create('/servicios')
                ->setChangeFrequency('monthly')
                ->setPriority(0.9))
            ->add(Url::create('/proyectos')
                ->setChangeFrequency('weekly')
                ->setPriority(0.9))
            ->add(Url::create('/sobre-nosotros')
                ->setChangeFrequency('yearly')
                ->setPriority(0.7))
            ->add(Url::create('/contacto')
                ->setChangeFrequency('monthly')
                ->setPriority(0.7));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generado en public/sitemap.xml');
    }
}
