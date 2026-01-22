<?php
// app/Models/Page.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'type',
        'title',
        'section',
        'content',
        'images',
        'videos',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relación con Sections
    public function sections()
    {
        return $this->hasMany(Section::class)->ordered();
    }

    // Obtener secciones activas
    public function activeSections()
    {
        return $this->hasMany(Section::class)->active()->ordered();
    }

    // Obtener una sección específica por nombre
    public function getSection($name)
    {
        return $this->sections()->where('name', $name)->first();
    }

    // Relación con SEO
  public function seo()
{
    return $this->hasOne(\App\Models\Seo::class);
}

    // Obtener página por slug
    public static function getBySlug($slug)
    {
        return self::where('slug', $slug)->with('activeSections')->first();
    }

    // Métodos existentes para imágenes/videos (mantenidos para retrocompatibilidad)
    public function getImagesArray()
    {
        if (empty($this->images)) return [];
        return explode(',', $this->images);
    }

    public function getVideosArray()
    {
        if (empty($this->videos)) return [];
        return explode(',', $this->videos);
    }

    public function setImagesArray($images)
    {
        $this->images = empty($images) ? null : implode(',', $images);
    }

    public function setVideosArray($videos)
    {
        $this->videos = empty($videos) ? null : implode(',', $videos);
    }

    // Scopes para filtrar por tipo
    public function scopeLandings($query)
    {
        return $query->where('type', 'landing');
    }

    public function scopeBlogs($query)
    {
        return $query->where('type', 'blog');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Verificar si es una landing page
    public function isLanding()
    {
        return $this->type === 'landing';
    }

    // Verificar si es un blog
    public function isBlog()
    {
        return $this->type === 'blog';
    }

    // Obtener sección específica con custom_data
    public function getSectionData($sectionName, $key = null, $default = null)
    {
        $section = $this->sections()->where('name', $sectionName)->first();

        if (!$section) {
            return $default;
        }

        if (is_null($key)) {
            return $section->custom_data ?? $default;
        }

        return $section->custom_data[$key] ?? $default;
    }

    // Obtener proyectos destacados de una landing
    public function featuredProyectos()
    {
        $proyectoIds = $this->getSectionData('proyectos_destacados', 'proyecto_ids', []);

        if (empty($proyectoIds)) {
            return collect([]);
        }

        return \App\Models\Proyecto::whereIn('id', $proyectoIds)
            ->where('activo', true)
            ->orderByRaw('FIELD(id, ' . implode(',', $proyectoIds) . ')')
            ->get();
    }
}