<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['purchase', 'usage', 'refund', 'admin_grant', 'admin_deduct', 'admin_set', 'subscription_bonus']);
            $table->integer('amount'); // Can be negative for deductions
            $table->integer('balance_after');
            $table->string('reason')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('package_id')->nullable()->constrained('token_packages')->onDelete('set null');
            $table->foreignId('evaluation_request_id')->nullable()->constrained('human_evaluation_requests')->onDelete('set null');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_transactions');
    }
};
