<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateReferralCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referrals:generate-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate referral codes for existing users who don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating referral codes for existing users...');

        $users = User::whereNull('referral_code')->orWhere('referral_code', '')->get();
        $count = 0;

        foreach ($users as $user) {
            $user->referral_code = $user->generateUniqueReferralCode();
            $user->saveQuietly();
            $count++;
            $this->info("Generated code for user: {$user->email} - Code: {$user->referral_code}");
        }

        $this->info("Successfully generated {$count} referral codes!");

        return Command::SUCCESS;
    }
}
