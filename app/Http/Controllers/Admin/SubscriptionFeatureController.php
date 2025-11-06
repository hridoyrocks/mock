<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionFeatureController extends Controller
{
    /**
     * Display a listing of features.
     */
    public function index()
    {
        $features = SubscriptionFeature::with('plans')->get();
        return view('admin.subscription-features.index', compact('features'));
    }

    /**
     * Show the form for creating a new feature.
     */
    public function create()
    {
        // Get all existing features
        $existingFeatures = SubscriptionFeature::orderBy('name')->get();
        
        // Predefined available features (ALL features from your database)
        $availableFeatures = [
            // AI Features
            ['key' => 'ai_speaking_evaluation', 'name' => 'AI Speaking Evaluation', 'description' => 'AI-powered speaking task evaluation', 'icon' => 'fas fa-microphone', 'category' => 'AI Features'],
            ['key' => 'ai_writing_evaluation', 'name' => 'AI Writing Evaluation', 'description' => 'AI-powered writing task evaluation', 'icon' => 'fas fa-robot', 'category' => 'AI Features'],
            
            // Analytics
            ['key' => 'detailed_analytics', 'name' => 'Detailed Analytics', 'description' => 'Advanced performance tracking and insights', 'icon' => 'fas fa-chart-line', 'category' => 'Analytics'],
            
            // Content Access
            ['key' => 'full_test_access', 'name' => 'Full Test Access', 'description' => 'Access to all full-length IELTS tests', 'icon' => 'fas fa-check-circle', 'category' => 'Content Access'],
            ['key' => 'premium_full_tests', 'name' => 'Premium Full Tests', 'description' => 'Access to premium full test sets', 'icon' => 'fas fa-star', 'category' => 'Content Access'],
            
            // Token Features
            ['key' => 'human_evaluation_tokens', 'name' => 'Human Evaluation Tokens', 'description' => 'Tokens for human teacher evaluation', 'icon' => 'fas fa-coins', 'category' => 'Tokens'],
            ['key' => 'evaluation_tokens_per_month', 'name' => 'Monthly Evaluation Tokens', 'description' => 'AI evaluation tokens granted per month', 'icon' => 'fas fa-coins', 'category' => 'Tokens'],
            ['key' => 'human_evaluation_discount', 'name' => 'Token Purchase Discount', 'description' => 'Discount on token purchases', 'icon' => 'fas fa-percentage', 'category' => 'Tokens'],
            
            // Usage Limits
            ['key' => 'mock_tests_per_month', 'name' => 'Mock Tests per Month', 'description' => 'Number of mock tests user can take per month', 'icon' => 'fas fa-clipboard-list', 'category' => 'Usage Limits'],
            
            // Learning
            ['key' => 'study_recommendations', 'name' => 'Study Recommendations', 'description' => 'AI-powered personalized study plan', 'icon' => 'fas fa-graduation-cap', 'category' => 'Learning'],
            
            // Support
            ['key' => 'priority_support', 'name' => 'Priority Support', 'description' => '24/7 priority customer support', 'icon' => 'fas fa-headset', 'category' => 'Support'],
            
            // Certification
            ['key' => 'certificate', 'name' => 'Certificate of Completion', 'description' => 'Official completion certificate', 'icon' => 'fas fa-certificate', 'category' => 'Certification'],
            
            // Additional Learning
            ['key' => 'tutor_sessions', 'name' => '1-on-1 Tutor Sessions', 'description' => 'Personal tutoring sessions with experts', 'icon' => 'fas fa-user-tie', 'category' => 'Learning'],
        ];
        
        // Filter out already created features
        $existingKeys = $existingFeatures->pluck('key')->toArray();
        $availableFeatures = collect($availableFeatures)->reject(function($feature) use ($existingKeys) {
            return in_array($feature['key'], $existingKeys);
        })->groupBy('category')->toArray();
        
        return view('admin.subscription-features.create', compact('existingFeatures', 'availableFeatures'));
    }

    /**
     * Store a newly created feature.
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:subscription_features,key',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        SubscriptionFeature::create([
            'key' => Str::slug($request->key, '_'),
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.subscription-features.index')
            ->with('success', 'Feature created successfully.');
    }

    /**
     * Show the form for editing the specified feature.
     */
    public function edit(SubscriptionFeature $subscriptionFeature)
    {
        return view('admin.subscription-features.edit', compact('subscriptionFeature'));
    }

    /**
     * Update the specified feature.
     */
    public function update(Request $request, SubscriptionFeature $subscriptionFeature)
    {
        $request->validate([
            'key' => 'required|string|unique:subscription_features,key,' . $subscriptionFeature->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $subscriptionFeature->update([
            'key' => Str::slug($request->key, '_'),
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.subscription-features.index')
            ->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified feature.
     */
    public function destroy(SubscriptionFeature $subscriptionFeature)
    {
        // Check if feature is assigned to any plans
        if ($subscriptionFeature->plans()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete feature that is assigned to plans.');
        }

        $subscriptionFeature->delete();

        return redirect()->route('admin.subscription-features.index')
            ->with('success', 'Feature deleted successfully.');
    }
}