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
        // Create test_categories table
        Schema::create('test_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // For displaying icons
            $table->string('color')->default('#3B82F6'); // For UI theming
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
        
        // Create pivot table for test_sets and categories
        Schema::create('test_category_test_set', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_set_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['test_category_id', 'test_set_id']);
        });
        
        // Insert default categories
        DB::table('test_categories')->insert([
            [
                'name' => 'Academic',
                'slug' => 'academic',
                'description' => 'Academic IELTS tests for university admission',
                'icon' => 'academic-cap',
                'color' => '#3B82F6',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'General Training',
                'slug' => 'general-training',
                'description' => 'General Training IELTS tests for work and migration',
                'icon' => 'briefcase',
                'color' => '#10B981',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Practice Tests',
                'slug' => 'practice-tests',
                'description' => 'Practice tests for skill improvement',
                'icon' => 'clipboard-list',
                'color' => '#F59E0B',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_category_test_set');
        Schema::dropIfExists('test_categories');
    }
};
