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
        Schema::table('questions', function (Blueprint $table) {
            // Add part_number for organizing questions by parts
            $table->integer('part_number')->nullable()->after('order_number');
            
            // Add passage_text for reading passages (can store full passage here)
            $table->longText('passage_text')->nullable()->after('content');
            
            // Add audio_transcript for listening questions
            $table->longText('audio_transcript')->nullable()->after('passage_text');
            
            // Add question_group for grouped questions (e.g., questions 1-5 based on same passage)
            $table->string('question_group')->nullable()->after('part_number');
            
            // Add marks for scoring
            $table->integer('marks')->default(1)->after('time_limit');
            
            // Add is_example flag for sample questions
            $table->boolean('is_example')->default(false)->after('marks');
            
            // Add indexes for better performance
            $table->index('part_number');
            $table->index('question_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'part_number',
                'passage_text', 
                'audio_transcript',
                'question_group',
                'marks',
                'is_example'
            ]);
        });
    }
};