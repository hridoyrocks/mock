<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\MonthlySummary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMonthlySummaryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(): void
    {
        // Get user's monthly stats
        $lastMonth = now()->subMonth();
        
        $stats = [
            'tests_completed' => $this->user->attempts()
                ->where('status', 'completed')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count(),
                
            'average_score' => $this->user->attempts()
                ->where('status', 'completed')
                ->whereNotNull('band_score')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->avg('band_score'),
                
            'improvement' => $this->calculateImprovement(),
            'month' => $lastMonth->format('F Y'),
        ];
        
        // Send notification
        $this->user->notify(new MonthlySummary($stats));
    }
    
    private function calculateImprovement(): float
    {
        $twoMonthsAgo = now()->subMonths(2);
        $lastMonth = now()->subMonth();
        
        $previousAvg = $this->user->attempts()
            ->where('status', 'completed')
            ->whereNotNull('band_score')
            ->whereMonth('created_at', $twoMonthsAgo->month)
            ->whereYear('created_at', $twoMonthsAgo->year)
            ->avg('band_score') ?? 0;
            
        $currentAvg = $this->user->attempts()
            ->where('status', 'completed')
            ->whereNotNull('band_score')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->avg('band_score') ?? 0;
            
        return $currentAvg - $previousAvg;
    }
}