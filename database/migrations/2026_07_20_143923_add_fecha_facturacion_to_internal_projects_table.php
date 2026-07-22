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
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->date('fecha_facturacion')->nullable()->after('fecha_entrega');
            $table->text('notas_facturacion')->nullable()->after('fecha_facturacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropColumn(['fecha_facturacion', 'notas_facturacion']);
        });
    }
};
