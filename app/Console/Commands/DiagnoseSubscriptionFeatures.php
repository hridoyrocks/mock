<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionFeature;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DiagnoseSubscriptionFeatures extends Command
{
    protected $signature = 'subscription:diagnose {--fix : Automatically fix issues}';
    protected $description = 'Diagnose and optionally fix subscription feature issues';

    public function handle()
    {
        $this->info('🔍 Diagnosing Subscription Features...');
        $this->newLine();

        $issues = [];

        // 1. Check if features table has data
        $featureCount = SubscriptionFeature::count();
        $this->info("✓ Features in database: {$featureCount}");
        
        if ($featureCount === 0) {
            $issues[] = 'No features found in database';
            $this->error('✗ No features found!');
        }

        // 2. Check if plans table has data
        $planCount = SubscriptionPlan::count();
        $this->info("✓ Plans in database: {$planCount}");
        
        if ($planCount === 0) {
            $issues[] = 'No plans found in database';
            $this->error('✗ No plans found!');
        }

        // 3. Check pivot table data
        $pivotCount = DB::table('plan_feature')->count();
        $this->info("✓ Feature assignments (pivot): {$pivotCount}");
        
        if ($pivotCount === 0) {
            $issues[] = 'No feature assignments found in pivot table';
            $this->error('✗ No feature assignments found!');
        }

        // 4. Check each plan
        $this->newLine();
        $this->info('📋 Checking Plans:');
        
        $plans = SubscriptionPlan::with('features')->get();
        
        foreach ($plans as $plan) {
            // Reload features relationship to ensure it's a collection
            $plan->load('features');
            $featureCount = $plan->features()->count();
            
            if ($featureCount === 0) {
                $issues[] = "Plan '{$plan->name}' has no features assigned";
                $this->error("  ✗ {$plan->name} ({$plan->slug}): NO FEATURES");
            } else {
                $this->info("  ✓ {$plan->name} ({$plan->slug}): {$featureCount} features");
                
                // Show feature details
                $planFeatures = $plan->features()->get();
                foreach ($planFeatures as $feature) {
                    $value = $feature->pivot->value ?? 'NULL';
                    $this->line("    - {$feature->name} ({$feature->key}): {$value}");
                }
            }
        }

        // 5. Test with a user
        $this->newLine();
        $this->info('👤 Testing with Users:');
        
        $testUsers = User::with('activeSubscriptionRelation.plan.features')
            ->limit(3)
            ->get();
        
        foreach ($testUsers as $user) {
            $subscription = $user->activeSubscription();
            
            if ($subscription && $subscription->plan) {
                $plan = $subscription->plan;
                $featuresCount = $plan->features ? $plan->features->count() : 0;
                
                $this->info("  User: {$user->name} (ID: {$user->id})");
                $this->info("  Plan: {$plan->name}");
                $this->info("  Features loaded: {$featuresCount}");
                
                // Test feature access
                $canAccess = $user->hasFeature('mock_tests_per_month');
                $limit = $user->getFeatureLimit('mock_tests_per_month');
                
                $this->line("  - hasFeature('mock_tests_per_month'): " . ($canAccess ? 'true' : 'false'));
                $this->line("  - getFeatureLimit('mock_tests_per_month'): {$limit}");
                
                if (!$canAccess || !$limit) {
                    $issues[] = "User {$user->id} cannot access features properly";
                }
            } else {
                $this->warn("  User: {$user->name} (ID: {$user->id}) - No active subscription");
            }
            $this->newLine();
        }

        // Summary
        $this->newLine();
        if (empty($issues)) {
            $this->info('✅ All checks passed! Subscription features are working correctly.');
        } else {
            $this->error('❌ Issues found:');
            foreach ($issues as $issue) {
                $this->error("  - {$issue}");
            }
            
            if ($this->option('fix')) {
                $this->newLine();
                $this->warn('🔧 Attempting to fix issues...');
                $this->call('db:seed', ['--class' => 'FixSubscriptionFeaturesSeeder']);
                $this->info('✓ Fix completed. Please run the diagnose command again to verify.');
            } else {
                $this->newLine();
                $this->info('💡 Run with --fix option to automatically fix these issues:');
                $this->comment('  php artisan subscription:diagnose --fix');
            }
        }

        return empty($issues) ? 0 : 1;
    }
}
