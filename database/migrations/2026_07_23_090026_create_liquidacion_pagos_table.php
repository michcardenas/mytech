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
        Schema::create('liquidacion_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->constrained('vendedores')->cascadeOnDelete();
            $table->date('periodo')->comment('Primer día del mes trabajado que se liquida');
            $table->date('fecha_pago');
            $table->decimal('monto', 14, 2);
            $table->string('metodo')->nullable();
            $table->string('referencia')->nullable();
            $table->string('comprobante')->nullable()->comment('Ruta del archivo adjunto en disco public');
            $table->text('nota')->nullable();
            $table->timestamps();

            $table->index(['vendedor_id', 'periodo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liquidacion_pagos');
    }
};
