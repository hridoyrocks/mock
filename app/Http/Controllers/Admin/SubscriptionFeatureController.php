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
        return view('admin.subscription-features.create');
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