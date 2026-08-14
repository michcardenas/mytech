<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;

/**
 * Unifica los perfiles sociales oficiales (sameAs) en TODOS los schemas
 * JSON-LD guardados en la tabla `seo` (home + landings + lo que venga).
 *
 * Fuente única de verdad de las redes de MY Tech Solutions. Correr tras
 * cambiar una red. Idempotente: recorre cada nodo Organization del schema y
 * le fija el sameAs canónico.
 */
class SocialLinksSeeder extends Seeder
{
    /**
     * Perfiles sociales oficiales. Orden = el que verá Google.
     *
     * @var array<int, string>
     */
    protected array $socials = [
        'https://www.instagram.com/mytech_solutions',
        'https://www.facebook.com/profile.php?id=61575108256490',
        'https://www.linkedin.com/company/110759244',
        'https://www.tiktok.com/@mytechsolutionsco',
    ];

    public function run(): void
    {
        $updated = 0;

        Seo::whereNotNull('schema_markup')->with('page')->get()->each(function (Seo $seo) use (&$updated) {
            $schema = is_array($seo->schema_markup)
                ? $seo->schema_markup
                : json_decode((string) $seo->schema_markup, true);

            if (! is_array($schema)) {
                return;
            }

            $changed = false;

            if (isset($schema['@graph']) && is_array($schema['@graph'])) {
                foreach ($schema['@graph'] as $i => $node) {
                    if ($this->applySameAs($node)) {
                        $schema['@graph'][$i] = $node;
                        $changed = true;
                    }
                }
            } elseif ($this->applySameAs($schema)) {
                $changed = true;
            }

            if ($changed) {
                $seo->schema_markup = $schema;
                $seo->save();
                $updated++;
                $slug = $seo->page?->slug ?? ('seo#'.$seo->id);
                $this->command?->info("sameAs actualizado en: {$slug}");
            }
        });

        $this->command?->info("Redes unificadas en {$updated} schema(s).");
    }

    /**
     * Si el nodo es una Organization, le fija el sameAs canónico.
     *
     * @param  array<string, mixed>  $node
     */
    protected function applySameAs(array &$node): bool
    {
        if (($node['@type'] ?? null) !== 'Organization') {
            return false;
        }

        if (($node['sameAs'] ?? null) === $this->socials) {
            return false;
        }

        $node['sameAs'] = $this->socials;

        return true;
    }
}
