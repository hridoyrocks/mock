<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['marker_id', 'processed_explanation', 'marker_text', 'marker_start_pos', 'marker_end_pos']);
        });
        
        Schema::dropIfExists('passage_explanations');
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('marker_id')->nullable()->after('question_type');
            $table->text('processed_explanation')->nullable()->after('explanation');
            $table->string('marker_text', 500)->nullable()->after('marker_id');
            $table->integer('marker_start_pos')->nullable()->after('marker_text');
            $table->integer('marker_end_pos')->nullable()->after('marker_start_pos');
        });
    }
};