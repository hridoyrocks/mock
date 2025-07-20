<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Referral;
use App\Services\Referral\ReferralService;

class ProcessPendingReferrals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referrals:process-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending referrals and check for completion';

    protected $referralService;

    /**
     * Create a new command instance.
     */
    public function __construct(ReferralService $referralService)
    {
        parent::__construct();
        $this->referralService = $referralService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing pending referrals...');

        $pendingReferrals = Referral::where('status', 'pending')
            ->with(['referred', 'referrer'])
            ->get();

        $processedCount = 0;
        $completedCount = 0;

        foreach ($pendingReferrals as $referral) {
            $processedCount++;
            
            if ($referral->isEligibleForReward()) {
                try {
                    $this->referralService->checkAndCompleteReferral($referral->referred);
                    $completedCount++;
                    $this->info("Completed referral: {$referral->id} - Referrer: {$referral->referrer->email}");
                } catch (\Exception $e) {
                    $this->error("Failed to complete referral {$referral->id}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Processed {$processedCount} pending referrals, completed {$completedCount}");

        return Command::SUCCESS;
    }
}
