<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSubtask extends Model
{
    protected $fillable = [
        'project_task_id',
        'titulo',
        'hecha',
        'developer_id',
        'orden',
    ];

    protected $casts = [
        'hecha' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }
}
