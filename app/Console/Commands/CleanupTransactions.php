<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaymentTransaction;
use Carbon\Carbon;

class CleanupTransactions extends Command
{
    protected $signature = 'payments:cleanup {--days=90 : Number of days to keep transactions}';
    protected $description = 'Clean up old payment transactions';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Cleaning up transactions older than {$days} days...");
        
        // Only delete failed transactions and completed transactions without important data
        $deleted = PaymentTransaction::where('created_at', '<', $cutoffDate)
            ->where(function ($query) {
                $query->where('status', 'failed')
                    ->orWhere(function ($q) {
                        $q->where('status', 'completed')
                          ->whereNull('subscription_id');
                    });
            })
            ->delete();
        
        $this->info("Deleted {$deleted} old transactions.");
        
        // Archive important transactions instead of deleting
        $archived = PaymentTransaction::where('created_at', '<', $cutoffDate)
            ->where('status', 'completed')
            ->whereNotNull('subscription_id')
            ->update(['archived' => true]);
        
        $this->info("Archived {$archived} important transactions.");
        
        return Command::SUCCESS;
    }
}