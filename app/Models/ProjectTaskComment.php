<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTaskComment extends Model
{
    protected $fillable = [
        'project_task_id',
        'cuerpo',
        'autor_tipo',
        'autor_nombre',
        'user_id',
        'developer_id',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function getInicialesAttribute(): string
    {
        return \Illuminate\Support\Str::of($this->autor_nombre)
            ->explode(' ')->filter()->take(2)
            ->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))
            ->implode('');
    }
}
