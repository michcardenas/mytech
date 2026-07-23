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
        Schema::table('vendedores', function (Blueprint $table) {
            $table->boolean('escalonada_activa')->default(false)->after('sueldo_moneda');
            $table->json('escalones')->nullable()->after('escalonada_activa')
                ->comment('Tramos [{desde:int, pct:float}] ordenados asc por cierres del ciclo');
        });
    }

    public function down(): void
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->dropColumn(['escalonada_activa', 'escalones']);
        });
    }
};
