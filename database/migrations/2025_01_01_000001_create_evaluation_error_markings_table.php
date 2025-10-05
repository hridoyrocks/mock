<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_error_markings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('human_evaluation_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_answer_id')->constrained()->onDelete('cascade');
            $table->integer('task_number'); // 1 or 2
            $table->text('marked_text'); // The selected text with error
            $table->integer('start_position'); // Start position in the text
            $table->integer('end_position'); // End position in the text
            $table->enum('error_type', ['task_achievement', 'coherence_cohesion', 'lexical_resource', 'grammar']);
            $table->text('comment')->nullable(); // Optional comment for the error
            $table->timestamps();
            
            $table->index(['human_evaluation_id', 'task_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_error_markings');
    }
};
