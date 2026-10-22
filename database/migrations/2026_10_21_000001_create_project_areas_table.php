<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Areas / modulos dentro de un proyecto interno, para agrupar y filtrar
     * tareas por tema (ej. "Gestion Humana", "Integracion Dropi").
     */
    public function up(): void
    {
        Schema::create('project_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained('internal_projects')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('color', 9)->default('#7c3aed');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->unique(['internal_project_id', 'nombre']);
            $table->index('internal_project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_areas');
    }
};
