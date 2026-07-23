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
            $table->decimal('sueldo_basico', 14, 2)->nullable()->after('comision_porcentaje_default');
            $table->enum('sueldo_moneda', ['COP', 'USD', 'EUR'])->default('COP')->after('sueldo_basico');
        });
    }

    public function down(): void
    {
        Schema::table('vendedores', function (Blueprint $table) {
            $table->dropColumn(['sueldo_basico', 'sueldo_moneda']);
        });
    }
};
