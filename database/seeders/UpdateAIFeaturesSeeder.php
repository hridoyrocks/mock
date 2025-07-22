<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionFeature;

class UpdateAIFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure AI features exist
        $aiWritingFeature = SubscriptionFeature::firstOrCreate(
            ['key' => 'ai_writing_evaluation'],
            [
                'name' => 'AI Writing Evaluation',
                'description' => 'Get instant AI-powered evaluation for your writing tasks',
                'type' => 'boolean',
                'is_active' => true
            ]
        );

        $aiSpeakingFeature = SubscriptionFeature::firstOrCreate(
            ['key' => 'ai_speaking_evaluation'],
            [
                'name' => 'AI Speaking Evaluation', 
                'description' => 'Get instant AI-powered evaluation for your speaking tests',
                'type' => 'boolean',
                'is_active' => true
            ]
        );

        // Update plan features based on your business model
        $plans = SubscriptionPlan::all();
        
        foreach ($plans as $plan) {
            switch ($plan->slug) {
                case 'free':
                    // Free plan - NO AI features (change to true if you want)
                    $this->attachFeature($plan, $aiWritingFeature, 'false');
                    $this->attachFeature($plan, $aiSpeakingFeature, 'false');
                    break;
                    
                case 'premium':
                case 'pro':
                    // Premium and Pro plans - YES AI features
                    $this->attachFeature($plan, $aiWritingFeature, 'true');
                    $this->attachFeature($plan, $aiSpeakingFeature, 'true');
                    break;
            }
        }
        
        $this->command->info('AI features updated for all subscription plans!');
    }
    
    /**
     * Attach feature to plan if not already attached
     */
    private function attachFeature($plan, $feature, $value)
    {
        if (!$plan->features->contains($feature->id)) {
            $plan->features()->attach($feature->id, ['value' => $value]);
            $this->command->info("Added {$feature->name} to {$plan->name} plan with value: {$value}");
        } else {
            // Update existing value
            $plan->features()->updateExistingPivot($feature->id, ['value' => $value]);
            $this->command->info("Updated {$feature->name} for {$plan->name} plan with value: {$value}");
        }
    }
}
