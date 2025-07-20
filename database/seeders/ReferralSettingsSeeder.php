<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReferralSetting;

class ReferralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'referral_reward_amount',
                'value' => '100',
                'description' => 'Amount in BDT per successful referral',
            ],
            [
                'key' => 'referral_completion_condition',
                'value' => 'first_test',
                'description' => 'Condition for referral completion: first_test, first_purchase, first_subscription',
            ],
            [
                'key' => 'tokens_per_taka',
                'value' => '10',
                'description' => 'Number of tokens per 1 BDT',
            ],
            [
                'key' => 'min_redemption_amount',
                'value' => '50',
                'description' => 'Minimum balance required for redemption in BDT',
            ],
            [
                'key' => 'referral_bonus_tokens',
                'value' => '50',
                'description' => 'Bonus tokens for new users who register with referral code',
            ],
            [
                'key' => 'referral_expiry_days',
                'value' => '365',
                'description' => 'Number of days before referral rewards expire',
            ],
        ];

        foreach ($settings as $setting) {
            ReferralSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
