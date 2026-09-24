<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Subtareas (checklist) de una tarea del tablero.
     */
    public function up(): void
    {
        Schema::create('project_subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_task_id')->constrained('project_tasks')->cascadeOnDelete();
            $table->string('titulo');
            $table->boolean('hecha')->default(false);
            $table->foreignId('developer_id')->nullable()->constrained('developers')->nullOnDelete();
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['project_task_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_subtasks');
    }
};
