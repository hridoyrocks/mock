<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of subscription plans.
     */
    public function index()
    {
        $plans = SubscriptionPlan::with(['features', 'subscriptions'])->orderBy('sort_order')->get();
        return view('admin.subscription-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new subscription plan.
     */
    public function create()
    {
        $features = SubscriptionFeature::all();
        return view('admin.subscription-plans.create', compact('features'));
    }

    /**
     * Store a newly created subscription plan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'features' => 'required|array',
            'features.*.id' => 'required|exists:subscription_features,id',
            'features.*.value' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'duration_days' => $request->duration_days,
            'description' => $request->description,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        // Attach features
        foreach ($request->features as $featureId => $featureData) {
            // Skip if feature ID is not numeric
            if (!is_numeric($featureId)) {
                continue;
            }
            
            // Check if feature is enabled
            $isEnabled = false;
            $value = null;
            $limit = null;
            
            if (is_array($featureData)) {
                // Check if enabled checkbox is checked
                $isEnabled = isset($featureData['enabled']) && $featureData['enabled'] == '1';
                $value = $featureData['value'] ?? null;
                $limit = $featureData['limit'] ?? null;
            }
            
            if ($isEnabled) {
                $plan->features()->attach($featureId, [
                    'value' => $value ?: null,
                    'limit' => $limit ?: null
                ]);
            }
        }

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        $features = SubscriptionFeature::all();
        
        // Prepare feature values for the form
        $planFeatures = [];
        $loadedFeatures = $subscriptionPlan->features()->get();
        foreach ($loadedFeatures as $feature) {
            $planFeatures[$feature->id] = [
                'enabled' => true,
                'value' => $feature->pivot->value,
                'limit' => $feature->pivot->limit
            ];
        }
        
        return view('admin.subscription-plans.edit', compact('subscriptionPlan', 'features', 'planFeatures'));
    }

    /**
     * Update the specified subscription plan.
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        \Log::info('Update request data:', $request->all());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug,' . $subscriptionPlan->id,
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'duration_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'features' => 'required|array',
        ]);

        $subscriptionPlan->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'duration_days' => $request->duration_days,
            'description' => $request->description,
            'sort_order' => $request->sort_order,
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        // Log features before processing
        \Log::info('Features data:', $request->features);
        
        // Sync features
        $subscriptionPlan->features()->detach();
        
        foreach ($request->features as $featureId => $featureData) {
            // Skip if feature ID is not numeric (might be array key)
            if (!is_numeric($featureId)) {
                continue;
            }
            
            // Check if feature is enabled
            $isEnabled = false;
            $value = null;
            $limit = null;
            
            if (is_array($featureData)) {
                // Check if enabled checkbox is checked
                $isEnabled = isset($featureData['enabled']) && $featureData['enabled'] == '1';
                $value = $featureData['value'] ?? null;
                $limit = $featureData['limit'] ?? null;
            }
            
            if ($isEnabled) {
                \Log::info("Attaching feature {$featureId} with value: {$value}, limit: {$limit}");
                
                $subscriptionPlan->features()->attach($featureId, [
                    'value' => $value ?: null,
                    'limit' => $limit ?: null
                ]);
            }
        }
        
        // Log final attached features
        $attachedFeatures = $subscriptionPlan->features()->get();
        \Log::info('Attached features count: ' . $attachedFeatures->count());

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    /**
     * Toggle plan status.
     */
    public function toggleStatus(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update([
            'is_active' => !$subscriptionPlan->is_active
        ]);

        return redirect()->back()
            ->with('success', 'Plan status updated successfully.');
    }

    /**
     * Remove the specified plan.
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        // Check if plan has active subscriptions
        if ($subscriptionPlan->subscriptions()->active()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    /**
     * Reorder plans.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'plans' => 'required|array',
            'plans.*.id' => 'required|exists:subscription_plans,id',
            'plans.*.order' => 'required|integer|min:0'
        ]);

        foreach ($request->plans as $plan) {
            SubscriptionPlan::where('id', $plan['id'])
                ->update(['sort_order' => $plan['order']]);
        }

        return response()->json(['success' => true]);
    }
}