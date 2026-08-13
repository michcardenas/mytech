<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->boolean('es_bolsa_horas')->default(false)->after('es_recurrente');
            $table->decimal('horas_totales', 8, 2)->nullable()->after('es_bolsa_horas');
            $table->decimal('valor_hora', 12, 2)->nullable()->after('horas_totales');
            $table->json('puntos_acuerdo')->nullable()->after('valor_hora');
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropColumn(['es_bolsa_horas', 'horas_totales', 'valor_hora', 'puntos_acuerdo']);
        });
    }
};
