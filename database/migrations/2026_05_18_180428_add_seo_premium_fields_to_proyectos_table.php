<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // ── SEO ESENCIAL ─────────────────────────────────────
            $table->string('focus_keyword', 120)->nullable()->after('descripcion');
            $table->json('secondary_keywords')->nullable()->after('focus_keyword');
            $table->text('excerpt')->nullable()->after('secondary_keywords');
            $table->string('canonical_url', 500)->nullable()->after('excerpt');
            $table->enum('robots', [
                'index,follow',
                'noindex,follow',
                'index,nofollow',
                'noindex,nofollow',
            ])->default('index,follow')->after('canonical_url');

            // ── OPEN GRAPH ───────────────────────────────────────
            $table->string('og_title', 150)->nullable()->after('og_image');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_type', 50)->default('article')->after('og_description');

            // ── TWITTER CARDS ────────────────────────────────────
            $table->enum('twitter_card', [
                'summary',
                'summary_large_image',
                'app',
                'player',
            ])->default('summary_large_image')->after('og_type');
            $table->string('twitter_title', 150)->nullable()->after('twitter_card');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->string('twitter_image', 500)->nullable()->after('twitter_description');

            // ── SCHEMA.ORG ───────────────────────────────────────
            $table->string('schema_type', 50)->default('CreativeWork')->after('twitter_image');
            $table->longText('schema_markup')->nullable()->after('schema_type');

            // ── METADATA AVANZADA ────────────────────────────────
            $table->string('breadcrumb_title', 120)->nullable()->after('schema_markup');
            $table->string('author', 120)->nullable()->after('breadcrumb_title');
            $table->unsignedSmallInteger('reading_time')->nullable()->after('author'); // minutos
            $table->string('alt_logo', 255)->nullable()->after('reading_time');
            $table->string('alt_og_image', 255)->nullable()->after('alt_logo');
            $table->date('publicado_en')->nullable()->after('alt_og_image');

            // ── CLASIFICACIÓN DEL CLIENTE ────────────────────────
            $table->string('industria', 120)->nullable()->after('publicado_en');
            $table->enum('client_size', ['startup', 'pyme', 'empresa', 'enterprise'])
                  ->nullable()->after('industria');

            // ── RECURSOS EXTERNOS ────────────────────────────────
            $table->string('case_study_url', 500)->nullable()->after('client_size');
            $table->string('video_url', 500)->nullable()->after('case_study_url');

            // Índices para queries SEO comunes
            $table->index('focus_keyword');
            $table->index('industria');
            $table->index('publicado_en');
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropIndex(['focus_keyword']);
            $table->dropIndex(['industria']);
            $table->dropIndex(['publicado_en']);

            $table->dropColumn([
                'focus_keyword', 'secondary_keywords', 'excerpt', 'canonical_url', 'robots',
                'og_title', 'og_description', 'og_type',
                'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
                'schema_type', 'schema_markup',
                'breadcrumb_title', 'author', 'reading_time', 'alt_logo', 'alt_og_image', 'publicado_en',
                'industria', 'client_size',
                'case_study_url', 'video_url',
            ]);
        });
    }
};
