<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTaskFile extends Model
{
    protected $fillable = [
        'project_task_id',
        'nombre',
        'archivo',
        'tipo',
        'tamano',
        'subido_por',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function getTamanoFormateadoAttribute(): string
    {
        $bytes = (int) $this->tamano;
        if ($bytes < 1024) {
            return $bytes.' B';
        }
        if ($bytes < 1048576) {
            return round($bytes / 1024, 1).' KB';
        }

        return round($bytes / 1048576, 1).' MB';
    }

    public function getIconoAttribute(): string
    {
        return match (true) {
            str_contains((string) $this->tipo, 'pdf') => 'fa-file-pdf',
            str_contains((string) $this->tipo, 'word') || str_contains((string) $this->tipo, 'doc') => 'fa-file-word',
            str_contains((string) $this->tipo, 'excel') || str_contains((string) $this->tipo, 'sheet') => 'fa-file-excel',
            str_contains((string) $this->tipo, 'image') => 'fa-file-image',
            str_contains((string) $this->tipo, 'zip') || str_contains((string) $this->tipo, 'rar') => 'fa-file-archive',
            default => 'fa-file',
        };
    }
}
