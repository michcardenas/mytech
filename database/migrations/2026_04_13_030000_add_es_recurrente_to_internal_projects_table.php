<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->boolean('es_recurrente')->default(false)->after('fecha_entrega');
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropColumn('es_recurrente');
        });
    }
};
