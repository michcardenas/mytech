<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            // Comercial (User) que generó/cerró el proyecto — para comisiones y atribución
            $table->foreignId('comercial_user_id')->nullable()->after('vendedor_id')
                ->constrained('users')->nullOnDelete();
            // Lead de origen (si vino del pipeline comercial)
            $table->foreignId('lead_id')->nullable()->after('comercial_user_id')
                ->constrained('leads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('comercial_user_id');
            $table->dropConstrainedForeignId('lead_id');
        });
    }
};
