<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tareas del tablero (Kanban) por proyecto interno.
     */
    public function up(): void
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained('internal_projects')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('columna')->default('por_hacer'); // por_hacer | en_progreso | en_revision | hecho
            $table->string('prioridad')->default('media');   // baja | media | alta
            $table->foreignId('developer_id')->nullable()->constrained('developers')->nullOnDelete();
            $table->date('fecha_limite')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['internal_project_id', 'columna', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};
