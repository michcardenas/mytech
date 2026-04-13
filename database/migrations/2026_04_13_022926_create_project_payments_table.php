<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained()->onDelete('cascade');
            $table->decimal('monto', 12, 2);
            $table->date('fecha');
            $table->string('metodo')->nullable();
            $table->string('referencia')->nullable();
            $table->text('nota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_payments');
    }
};
