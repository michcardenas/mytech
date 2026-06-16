<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_calendar_token')->nullable();          // access token (encriptado en el modelo)
            $table->text('google_calendar_refresh_token')->nullable();  // refresh token (encriptado)
            $table->timestamp('google_calendar_expires_at')->nullable();
            $table->string('google_calendar_email')->nullable();        // correo de la cuenta conectada
        });

        Schema::table('meetings', function (Blueprint $table) {
            // Anfitrión de la reunión (el admin cuyo calendario se usa para el cierre)
            $table->foreignId('host_user_id')->nullable()->after('user_id')
                ->constrained('users')->nullOnDelete();
            $table->string('google_event_id')->nullable();
            $table->string('meet_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_calendar_token', 'google_calendar_refresh_token', 'google_calendar_expires_at', 'google_calendar_email']);
        });
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('host_user_id');
            $table->dropColumn(['google_event_id', 'meet_link']);
        });
    }
};
