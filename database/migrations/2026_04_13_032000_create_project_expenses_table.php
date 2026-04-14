<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained()->onDelete('cascade');
            $table->string('concepto');
            $table->text('descripcion')->nullable();
            $table->decimal('monto', 12, 2);
            $table->enum('moneda', ['COP', 'USD'])->default('COP');
            $table->date('fecha');
            $table->string('categoria')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_expenses');
    }
};
