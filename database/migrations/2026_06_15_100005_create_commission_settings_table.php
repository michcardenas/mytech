<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->default('Tasa por defecto');
            $table->string('tipo')->default('porcentaje'); // porcentaje, monto
            $table->decimal('valor', 12, 2)->default(0);
            $table->string('moneda', 3)->default('COP');   // aplica cuando tipo = monto
            $table->boolean('activo')->default(true);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Fila por defecto editable desde el panel (solo admin)
        \Illuminate\Support\Facades\DB::table('commission_settings')->insert([
            'nombre'     => 'Tasa por defecto',
            'tipo'       => 'porcentaje',
            'valor'      => 10,
            'moneda'     => 'COP',
            'activo'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_settings');
    }
};
