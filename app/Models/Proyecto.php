<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Proyecto extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'pais',
        'bandera_emoji',
        'categoria',
        'badge_text',
        'descripcion',
        'url',
        'logo',
        'tecnologias',
        'estado',
        'destacado',
        'orden',
        'activo',
        // Campos SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        // Campos de contenido extendido
        'descripcion_extendida',
        'desafio',
        'solucion',
        'resultados',
        'galeria',
        // Campos de testimonios
        'testimonio',
        'testimonio_autor',
        'testimonio_cargo',
        // Campos adicionales del proyecto
        'duracion_desarrollo',
        'equipo_size',
        'fecha_lanzamiento',
        'visitas_mensuales',
    ];

    protected $casts = [
        'tecnologias' => 'array',
        'galeria' => 'array',
        'destacado' => 'boolean',
        'activo' => 'boolean',
        'fecha_lanzamiento' => 'date',
        'equipo_size' => 'integer',
        'visitas_mensuales' => 'integer',
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
        return match($this->estado) {
            'en_vivo' => 'En Vivo',
            'en_desarrollo' => 'En Desarrollo',
            'pausado' => 'Pausado',
            default => 'Desconocido'
        };
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'en_vivo' => 'success',
            'en_desarrollo' => 'warning',
            'pausado' => 'secondary',
            default => 'dark'
        };
    }

    public function getCategoriaClassAttribute()
    {
        return 'cat-' . $this->categoria;
    }
}
