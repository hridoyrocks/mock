<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Group question support
            $table->string('group_id')->nullable()->after('question_type');
            $table->string('group_instruction')->nullable()->after('group_id');
            $table->integer('group_start_number')->nullable()->after('group_instruction');
            $table->integer('group_end_number')->nullable()->after('group_start_number');
            
            // Drag-drop matching support
            $table->json('matching_options')->nullable()->after('section_specific_data');
            $table->json('drag_drop_config')->nullable()->after('matching_options');
            
            // Display formatting
            $table->boolean('show_question_number')->default(true)->after('is_example');
            $table->string('display_format')->default('default')->after('show_question_number');
            
            // Audio sections
            $table->string('audio_section')->nullable()->after('audio_transcript');
            $table->integer('audio_start_time')->nullable()->after('audio_section');
            $table->integer('audio_end_time')->nullable()->after('audio_start_time');
            
            // Indexes
            $table->index('group_id');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'group_id', 'group_instruction', 'group_start_number', 
                'group_end_number', 'matching_options', 'drag_drop_config',
                'show_question_number', 'display_format', 'audio_section',
                'audio_start_time', 'audio_end_time'
            ]);
        });
    }
};