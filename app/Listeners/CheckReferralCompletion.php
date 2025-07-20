<?php

namespace App\Listeners;

use App\Events\TestCompleted;
use App\Services\Referral\ReferralService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckReferralCompletion implements ShouldQueue
{
    use InteractsWithQueue;
    
    protected $referralService;

    /**
     * Create the event listener.
     */
    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        // Check if this user has a pending referral
        $user = $event->user ?? $event->attempt->user;
        
        if ($user && $user->referred_by) {
            $this->referralService->checkAndCompleteReferral($user);
        }
    }
}
