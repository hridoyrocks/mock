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
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Add bKash specific fields if they don't exist
            if (!Schema::hasColumn('payment_transactions', 'bkash_payment_id')) {
                $table->string('bkash_payment_id')->nullable()->after('transaction_id');
            }
            
            if (!Schema::hasColumn('payment_transactions', 'bkash_trx_id')) {
                $table->string('bkash_trx_id')->nullable()->after('bkash_payment_id');
            }
            
            if (!Schema::hasColumn('payment_transactions', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('bkash_trx_id');
            }
            
            if (!Schema::hasColumn('payment_transactions', 'customer_msisdn')) {
                $table->string('customer_msisdn')->nullable()->after('invoice_number');
            }
            
            // Add indexes for faster queries
            $table->index('bkash_payment_id');
            $table->index('bkash_trx_id');
            $table->index('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['bkash_payment_id']);
            $table->dropIndex(['bkash_trx_id']);
            $table->dropIndex(['invoice_number']);
            
            // Drop columns
            $table->dropColumn([
                'bkash_payment_id',
                'bkash_trx_id',
                'invoice_number',
                'customer_msisdn'
            ]);
        });
    }
};
