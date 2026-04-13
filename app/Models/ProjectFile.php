<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = [
        'internal_project_id',
        'nombre',
        'archivo',
        'tipo',
        'tamano',
    ];

    public function project()
    {
        return $this->belongsTo(InternalProject::class, 'internal_project_id');
    }

    public function getTamanoFormateadoAttribute()
    {
        $bytes = $this->tamano;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getIconoAttribute()
    {
        return match (true) {
            str_contains($this->tipo, 'pdf') => 'fa-file-pdf',
            str_contains($this->tipo, 'word') || str_contains($this->tipo, 'doc') => 'fa-file-word',
            str_contains($this->tipo, 'excel') || str_contains($this->tipo, 'sheet') => 'fa-file-excel',
            str_contains($this->tipo, 'image') => 'fa-file-image',
            str_contains($this->tipo, 'zip') || str_contains($this->tipo, 'rar') => 'fa-file-archive',
            default => 'fa-file',
        };
    }
}
