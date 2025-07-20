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
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['purchase', 'use', 'refund', 'grant', 'expire']);
            $table->integer('amount'); // Positive for add, negative for deduct
            $table->integer('balance_after');
            $table->string('reason')->nullable();
            $table->foreignId('package_id')->nullable()->constrained('token_packages')->nullOnDelete();
            $table->foreignId('evaluation_request_id')->nullable()->constrained('human_evaluation_requests')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_transactions');
    }
};