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
            // Add blank_count column if it doesn't exist
            if (!Schema::hasColumn('questions', 'blank_count')) {
                $table->integer('blank_count')->default(0)->after('section_specific_data');
            }
            
            // Add sub-question related columns if they don't exist
            if (!Schema::hasColumn('questions', 'is_sub_question')) {
                $table->boolean('is_sub_question')->default(false)->after('blank_count');
            }
            
            if (!Schema::hasColumn('questions', 'parent_question_id')) {
                $table->unsignedBigInteger('parent_question_id')->nullable()->after('is_sub_question');
                $table->foreign('parent_question_id')->references('id')->on('questions')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('questions', 'sub_question_index')) {
                $table->integer('sub_question_index')->nullable()->after('parent_question_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['parent_question_id']);
            $table->dropColumn(['blank_count', 'is_sub_question', 'parent_question_id', 'sub_question_index']);
        });
    }
};