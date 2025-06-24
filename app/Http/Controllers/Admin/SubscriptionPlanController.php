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
            'features' => [] // Will be handled by pivot table
        ]);

        // Attach features
        foreach ($request->features as $feature) {
            if (isset($feature['enabled']) && $feature['enabled']) {
                $plan->features()->attach($feature['id'], [
                    'value' => $feature['value'] ?? null,
                    'limit' => $feature['limit'] ?? null
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
        $subscriptionPlan->load('features');
        $features = SubscriptionFeature::all();
        
        // Prepare feature values for the form
        $planFeatures = [];
        foreach ($subscriptionPlan->features as $feature) {
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

        // Sync features
        $subscriptionPlan->features()->detach();
        foreach ($request->features as $feature) {
            if (isset($feature['enabled']) && $feature['enabled']) {
                $subscriptionPlan->features()->attach($feature['id'], [
                    'value' => $feature['value'] ?? null,
                    'limit' => $feature['limit'] ?? null
                ]);
            }
        }

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