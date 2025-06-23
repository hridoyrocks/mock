<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    // Create features first
    $features = [
        ['key' => 'mock_tests_per_month', 'name' => 'Mock Tests per Month'],
        ['key' => 'ai_writing_evaluation', 'name' => 'AI Writing Evaluation'],
        ['key' => 'ai_speaking_evaluation', 'name' => 'AI Speaking Evaluation'],
        ['key' => 'detailed_analytics', 'name' => 'Detailed Performance Analytics'],
        ['key' => 'study_recommendations', 'name' => 'Personalized Study Recommendations'],
        ['key' => 'priority_support', 'name' => 'Priority Support'],
        ['key' => 'tutor_sessions', 'name' => '1-on-1 Tutor Sessions'],
        ['key' => 'certificate', 'name' => 'Certificate of Completion'],
    ];

    foreach ($features as $feature) {
        SubscriptionFeature::create($feature);
    }

    // Create plans
    $plans = [
        [
            'name' => 'Free',
            'slug' => 'free',
            'price' => 0,
            'duration_days' => 365,
            'description' => 'Get started with basic features',
            'features' => [
                'mock_tests_per_month' => '3',
                'ai_writing_evaluation' => 'preview_only',
                'ai_speaking_evaluation' => 'false',
                'detailed_analytics' => 'basic',
            ]
        ],
        [
            'name' => 'Premium',
            'slug' => 'premium',
            'price' => 1500, // BDT
            'duration_days' => 30,
            'description' => 'Full access with AI evaluation',
            'is_featured' => true,
            'features' => [
                'mock_tests_per_month' => 'unlimited',
                'ai_writing_evaluation' => 'true',
                'ai_speaking_evaluation' => 'true',
                'detailed_analytics' => 'full',
                'study_recommendations' => 'true',
                'priority_support' => 'true',
            ]
        ],
        [
            'name' => 'Pro',
            'slug' => 'pro',
            'price' => 2500, // BDT
            'duration_days' => 30,
            'description' => 'Everything plus personal tutoring',
            'features' => [
                'mock_tests_per_month' => 'unlimited',
                'ai_writing_evaluation' => 'true',
                'ai_speaking_evaluation' => 'true',
                'detailed_analytics' => 'full',
                'study_recommendations' => 'true',
                'priority_support' => 'true',
                'tutor_sessions' => '2',
                'certificate' => 'true',
            ]
        ]
    ];

    foreach ($plans as $planData) {
        $features = $planData['features'];
        unset($planData['features']);
        
        $plan = SubscriptionPlan::create($planData);
        
        // Attach features
        foreach ($features as $key => $value) {
            $feature = SubscriptionFeature::where('key', $key)->first();
            if ($feature) {
                $plan->features()->attach($feature->id, ['value' => $value]);
            }
        }
    }
}
}
