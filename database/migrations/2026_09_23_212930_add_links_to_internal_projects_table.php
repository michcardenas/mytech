<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enlaces importantes del proyecto (repositorio, producción, pruebas).
     */
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->string('repo_url')->nullable()->after('puntos_acuerdo');
            $table->string('url_produccion')->nullable()->after('repo_url');
            $table->string('url_pruebas')->nullable()->after('url_produccion');
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropColumn(['repo_url', 'url_produccion', 'url_pruebas']);
        });
    }
};
