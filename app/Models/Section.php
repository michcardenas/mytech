<?php
// Actualiza tu modelo app/Models/Section.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'page_id',
        'name',
        'title',
        'content',
        'images',
        'videos',
        'custom_data', // Nuevo campo para datos específicos
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'custom_data' => 'array' // Cast automático a array
    ];

    // Relación con Page
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // Métodos existentes para imágenes y videos
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

    // NUEVOS MÉTODOS para datos específicos
    public function getCustomData($key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->custom_data ?? [];
        }
        
        return $this->custom_data[$key] ?? $default;
    }

    public function setCustomData($key, $value)
    {
        $customData = $this->custom_data ?? [];
        $customData[$key] = $value;
        $this->custom_data = $customData;
    }

    public function setCustomDataArray($data)
    {
        $this->custom_data = $data;
    }

    // Scopes existentes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}