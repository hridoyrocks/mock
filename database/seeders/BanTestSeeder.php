<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BanAppeal;
use Carbon\Carbon;

class BanTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create some banned users
        $bannedUsers = [
            [
                'name' => 'Temporary Banned User',
                'email' => 'temp-banned@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'banned_at' => now(),
                'ban_reason' => 'Violated community guidelines by posting inappropriate content',
                'ban_type' => 'temporary',
                'ban_expires_at' => now()->addDays(7),
                'banned_by' => $admin->id,
            ],
            [
                'name' => 'Permanent Banned User',
                'email' => 'perm-banned@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'banned_at' => now()->subDays(30),
                'ban_reason' => 'Multiple violations including harassment and spam',
                'ban_type' => 'permanent',
                'ban_expires_at' => null,
                'banned_by' => $admin->id,
            ],
            [
                'name' => 'Expired Ban User',
                'email' => 'expired-ban@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'banned_at' => now()->subDays(10),
                'ban_reason' => 'Temporary ban for inappropriate language',
                'ban_type' => 'temporary',
                'ban_expires_at' => now()->subDays(3), // Ban already expired
                'banned_by' => $admin->id,
            ],
        ];

        foreach ($bannedUsers as $userData) {
            $user = User::create($userData);
            
            // Create some ban appeals
            if ($user->email === 'temp-banned@example.com') {
                BanAppeal::create([
                    'user_id' => $user->id,
                    'appeal_reason' => 'I sincerely apologize for my actions. I was not aware that my content violated the guidelines. I have read through the community guidelines thoroughly and promise to follow them strictly from now on. Please give me another chance.',
                    'status' => 'pending',
                ]);
            }
            
            if ($user->email === 'perm-banned@example.com') {
                // Create rejected appeal
                BanAppeal::create([
                    'user_id' => $user->id,
                    'appeal_reason' => 'Please unban me, I need to access my account.',
                    'status' => 'rejected',
                    'admin_response' => 'Your appeal lacks sincerity and does not acknowledge the severity of your violations. Multiple warnings were given before the ban.',
                    'reviewed_by' => $admin->id,
                    'reviewed_at' => now()->subDays(20),
                    'created_at' => now()->subDays(25),
                ]);
                
                // Create pending appeal
                BanAppeal::create([
                    'user_id' => $user->id,
                    'appeal_reason' => 'I understand the severity of my actions and deeply regret them. Over the past weeks, I have reflected on my behavior and understand why it was harmful. I have taken steps to ensure this never happens again, including attending online workshops about respectful communication. I am committed to being a positive member of the community.',
                    'status' => 'pending',
                ]);
            }
        }

        $this->command->info('Ban test data seeded successfully!');
        $this->command->info('Test accounts:');
        $this->command->info('- Admin: admin@example.com / password');
        $this->command->info('- Temporary Banned: temp-banned@example.com / password');
        $this->command->info('- Permanent Banned: perm-banned@example.com / password');
        $this->command->info('- Expired Ban: expired-ban@example.com / password');
    }
}
