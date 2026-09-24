<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Equipo de desarrolladores por proyecto interno (un proyecto puede tener varios devs).
     */
    public function up(): void
    {
        Schema::create('internal_project_developer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained('internal_projects')->cascadeOnDelete();
            $table->foreignId('developer_id')->constrained('developers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['internal_project_id', 'developer_id'], 'ipd_project_developer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_project_developer');
    }
};
