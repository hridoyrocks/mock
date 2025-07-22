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
        // Add storage_disk and media_url to questions table
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'storage_disk')) {
                $table->string('storage_disk')->default('public')->after('media_path');
            }
            if (!Schema::hasColumn('questions', 'media_url')) {
                $table->text('media_url')->nullable()->after('storage_disk');
            }
            if (!Schema::hasColumn('questions', 'use_part_audio')) {
                $table->boolean('use_part_audio')->default(false)->after('media_url');
            }
        });
        
        // Add storage_disk and audio_url to test_part_audios table
        Schema::table('test_part_audios', function (Blueprint $table) {
            if (!Schema::hasColumn('test_part_audios', 'storage_disk')) {
                $table->string('storage_disk')->default('public')->after('audio_path');
            }
            if (!Schema::hasColumn('test_part_audios', 'audio_url')) {
                $table->text('audio_url')->nullable()->after('storage_disk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['storage_disk', 'media_url']);
        });
        
        Schema::table('test_part_audios', function (Blueprint $table) {
            $table->dropColumn(['storage_disk', 'audio_url']);
        });
    }
};
