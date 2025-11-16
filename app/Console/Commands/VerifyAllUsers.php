<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyAllUsers extends Command
{
    protected $signature = 'users:verify-all {--force : Skip confirmation}';
    protected $description = 'Verify all unverified user emails';

    public function handle()
    {
        $unverifiedCount = User::whereNull('email_verified_at')->count();

        if ($unverifiedCount === 0) {
            $this->info('No unverified users found.');
            return 0;
        }

        $this->warn("Found {$unverifiedCount} unverified users.");

        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to verify all of them?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $verified = User::whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);

        $this->info("✓ Successfully verified {$verified} users!");

        return 0;
    }
}
