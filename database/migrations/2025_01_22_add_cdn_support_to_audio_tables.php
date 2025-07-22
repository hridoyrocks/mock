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
        // Update test_part_audios table
        Schema::table('test_part_audios', function (Blueprint $table) {
            $table->string('audio_url')->nullable()->after('audio_path');
            $table->string('storage_disk')->default('public')->after('audio_url');
        });

        // Update speaking_recordings table
        Schema::table('speaking_recordings', function (Blueprint $table) {
            $table->string('file_url')->nullable()->after('file_path');
            $table->string('storage_disk')->default('public')->after('file_url');
            $table->unsignedBigInteger('file_size')->nullable()->after('storage_disk');
            $table->string('mime_type')->nullable()->after('file_size');
        });

        // Update questions table for audio if needed
        if (Schema::hasColumn('questions', 'media_path')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->string('media_url')->nullable()->after('media_path');
                $table->string('media_storage_disk')->default('public')->after('media_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_part_audios', function (Blueprint $table) {
            $table->dropColumn(['audio_url', 'storage_disk']);
        });

        Schema::table('speaking_recordings', function (Blueprint $table) {
            $table->dropColumn(['file_url', 'storage_disk', 'file_size', 'mime_type']);
        });

        if (Schema::hasColumn('questions', 'media_url')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn(['media_url', 'media_storage_disk']);
            });
        }
    }
};
