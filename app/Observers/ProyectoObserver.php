<?php

namespace App\Observers;

use App\Models\Proyecto;
use Illuminate\Support\Facades\Artisan;

class ProyectoObserver
{
    /**
     * Handle the Proyecto "created" event.
     * Regenera el sitemap cuando se crea un nuevo proyecto
     */
    public function created(Proyecto $proyecto): void
    {
        $this->regenerateSitemap();
    }

    /**
     * Handle the Proyecto "updated" event.
     * Regenera el sitemap cuando se actualiza un proyecto (por ejemplo, cambio de slug o estado)
     */
    public function updated(Proyecto $proyecto): void
    {
        // Solo regenerar si cambió el slug o el estado activo
        if ($proyecto->wasChanged(['slug', 'activo'])) {
            $this->regenerateSitemap();
        }
    }

    /**
     * Handle the Proyecto "deleted" event.
     * Regenera el sitemap cuando se elimina un proyecto
     */
    public function deleted(Proyecto $proyecto): void
    {
        $this->regenerateSitemap();
    }

    /**
     * Handle the Proyecto "restored" event.
     * Regenera el sitemap cuando se restaura un proyecto
     */
    public function restored(Proyecto $proyecto): void
    {
        $this->regenerateSitemap();
    }

    /**
     * Regenerar el sitemap de forma asíncrona
     */
    protected function regenerateSitemap(): void
    {
        // Ejecutar en background para no bloquear la petición
        try {
            Artisan::call('sitemap:generate');
        } catch (\Exception $e) {
            // Silenciar errores para no interrumpir el flujo
            // En producción podrías registrar esto en logs
            logger()->warning('Error al regenerar sitemap: ' . $e->getMessage());
        }
    }
}
