<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuenta_cobros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_project_id')->constrained('internal_projects')->cascadeOnDelete();
            $table->string('numero_doc');
            $table->string('tipo', 20)->default('saldo'); // saldo | total | porcentaje | valor
            $table->decimal('valor_param', 14, 2)->nullable(); // % o valor exacto según tipo
            $table->decimal('monto', 14, 2);
            $table->string('moneda', 3)->default('COP');
            $table->date('periodo')->nullable(); // para recurrentes
            $table->boolean('visible_cliente')->default(false);
            $table->timestamps();

            $table->index(['internal_project_id', 'visible_cliente']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_cobros');
    }
};
