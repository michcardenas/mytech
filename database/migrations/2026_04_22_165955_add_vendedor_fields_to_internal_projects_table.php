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
            if (!Schema::hasColumn('internal_projects', 'vendedor_id')) {
                $table->foreignId('vendedor_id')->nullable()->after('developer_id')->constrained('vendedores')->nullOnDelete();
            }
            if (!Schema::hasColumn('internal_projects', 'comision_tipo')) {
                $table->enum('comision_tipo', ['porcentaje', 'monto'])->nullable()->after('vendedor_id');
            }
            if (!Schema::hasColumn('internal_projects', 'comision_valor')) {
                $table->decimal('comision_valor', 12, 2)->nullable()->after('comision_tipo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            if (Schema::hasColumn('internal_projects', 'comision_valor')) {
                $table->dropColumn('comision_valor');
            }
            if (Schema::hasColumn('internal_projects', 'comision_tipo')) {
                $table->dropColumn('comision_tipo');
            }
            if (Schema::hasColumn('internal_projects', 'vendedor_id')) {
                $table->dropConstrainedForeignId('vendedor_id');
            }
        });
    }
};
