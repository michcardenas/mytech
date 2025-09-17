<?php
// app/Models/Page.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title', 
        'section',
        'content',
        'images',
        'videos'
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
}