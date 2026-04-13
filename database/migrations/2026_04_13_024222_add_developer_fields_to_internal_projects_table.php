<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->string('desarrollador_nombre')->nullable()->after('notas');
            $table->string('desarrollador_email')->nullable()->after('desarrollador_nombre');
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropColumn(['desarrollador_nombre', 'desarrollador_email']);
        });
    }
};
