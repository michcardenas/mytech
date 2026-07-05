<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Proyecto extends Model
{
    protected $fillable = [
        // ── Básicos ─────────────────────────────────────
        'nombre', 'slug', 'pais', 'bandera_emoji', 'categoria', 'badge_text',
        'descripcion', 'url', 'logo', 'tecnologias',
        'estado', 'destacado', 'orden', 'activo',

        // ── SEO Esencial ────────────────────────────────
        'focus_keyword', 'secondary_keywords', 'excerpt',
        'canonical_url', 'robots',
        'meta_title', 'meta_description', 'meta_keywords',

        // ── Open Graph ──────────────────────────────────
        'og_image', 'og_title', 'og_description', 'og_type',

        // ── Twitter Cards ───────────────────────────────
        'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',

        // ── Schema.org ──────────────────────────────────
        'schema_type', 'schema_markup',

        // ── Metadata avanzada ───────────────────────────
        'breadcrumb_title', 'author', 'reading_time',
        'alt_logo', 'alt_og_image', 'publicado_en',

        // ── Clasificación cliente ───────────────────────
        'industria', 'client_size',

        // ── Recursos externos ───────────────────────────
        'case_study_url', 'video_url',

        // ── Contenido extendido ─────────────────────────
        'descripcion_extendida', 'desafio', 'solucion', 'resultados', 'galeria', 'galeria_alts', 'faqs',

        // ── Testimonios ─────────────────────────────────
        'testimonio', 'testimonio_autor', 'testimonio_cargo',

        // ── Métricas del proyecto ───────────────────────
        'duracion_desarrollo', 'equipo_size', 'fecha_lanzamiento', 'visitas_mensuales',
    ];

    protected $casts = [
        'tecnologias' => 'array',
        'secondary_keywords' => 'array',
        'galeria' => 'array',
        'galeria_alts' => 'array',
        'faqs' => 'array',
        'destacado' => 'boolean',
        'activo' => 'boolean',
        'fecha_lanzamiento' => 'date',
        'publicado_en' => 'date',
        'equipo_size' => 'integer',
        'visitas_mensuales' => 'integer',
        'reading_time' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($proyecto) {
            if (empty($proyecto->slug)) {
                $proyecto->slug = Str::slug($proyecto->nombre);
            }
            if (empty($proyecto->orden)) {
                $proyecto->orden = static::max('orden') + 1;
            }
        });
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function getEstadoTextAttribute()
    {
        return match ($this->estado) {
            'en_vivo' => 'En Vivo',
            'en_desarrollo' => 'En Desarrollo',
            'pausado' => 'Pausado',
            default => 'Desconocido'
        };
    }

    public function getEstadoColorAttribute()
    {
        return match ($this->estado) {
            'en_vivo' => 'success',
            'en_desarrollo' => 'warning',
            'pausado' => 'secondary',
            default => 'dark'
        };
    }

    public function getCategoriaClassAttribute()
    {
        return 'cat-'.$this->categoria;
    }
}
