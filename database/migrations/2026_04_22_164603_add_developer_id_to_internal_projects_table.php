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
        if (Schema::hasColumn('internal_projects', 'developer_id')) {
            return;
        }

        Schema::table('internal_projects', function (Blueprint $table) {
            $table->foreignId('developer_id')->nullable()->after('client_id')->constrained('developers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_projects', function (Blueprint $table) {
            $table->dropConstrainedForeignId('developer_id');
        });
    }
};
