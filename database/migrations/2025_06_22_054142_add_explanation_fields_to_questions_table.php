<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->text('explanation')->nullable()->after('section_specific_data');
            $table->string('passage_reference')->nullable()->after('explanation');
            $table->text('common_mistakes')->nullable()->after('passage_reference');
            $table->text('tips')->nullable()->after('common_mistakes');
            $table->string('difficulty_level')->nullable()->after('tips');
            $table->json('related_topics')->nullable()->after('difficulty_level');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'explanation',
                'passage_reference', 
                'common_mistakes',
                'tips',
                'difficulty_level',
                'related_topics'
            ]);
        });
    }
};