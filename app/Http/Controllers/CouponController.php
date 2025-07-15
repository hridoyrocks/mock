<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'plan_id' => 'required|exists:subscription_plans,id'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.'
            ], 404);
        }

        $user = Auth::user();
        $plan = SubscriptionPlan::find($request->plan_id);

        // Check if coupon is for the selected plan
        if ($coupon->plan_id !== $plan->id) {
            return response()->json([
                'valid' => false,
                'message' => 'This coupon is not valid for the selected plan.'
            ], 400);
        }

        // Check if coupon can be used by user
        if (!$coupon->canBeUsedByUser($user)) {
            $message = 'This coupon cannot be used.';
            
            if (!$coupon->isValid()) {
                if ($coupon->valid_until && $coupon->valid_until->isPast()) {
                    $message = 'This coupon has expired.';
                } elseif ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                    $message = 'This coupon has reached its usage limit.';
                } else {
                    $message = 'This coupon is not active.';
                }
            } elseif ($coupon->hasBeenUsedByUser($user)) {
                $message = 'You have already used this coupon.';
            }

            return response()->json([
                'valid' => false,
                'message' => $message
            ], 400);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($plan->current_price);

        return response()->json([
            'valid' => true,
            'coupon' => [
                'code' => $coupon->code,
                'description' => $coupon->description,
                'formatted_discount' => $coupon->formatted_discount,
                'discount_type' => $coupon->discount_type,
                'duration_days' => $coupon->duration_days,
            ],
            'pricing' => $discount,
            'message' => 'Coupon applied successfully!'
        ]);
    }


public function store(Request $request)
{
    try {
        // Debug - Check what's coming
        \Log::info('Coupon creation attempt', $request->all());
        
        $validated = $request->validate([
            'code' => 'nullable|string|unique:coupons,code|max:50',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed,full_access,trial',
            'discount_value' => 'required_unless:discount_type,full_access,trial|nullable|numeric|min:0',
            'plan_id' => 'required|exists:subscription_plans,id',
            'duration_days' => 'required_if:discount_type,trial|nullable|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'generate_code' => 'boolean',
            'code_prefix' => 'nullable|string|max:10',
            'code_count' => 'required_if:generate_code,true|nullable|integer|min:1|max:100',
        ]);

        // Handle checkbox - if not sent, set to false
        $validated['is_active'] = $request->has('is_active');
        $validated['generate_code'] = $request->has('generate_code');

        // Generate codes if requested
        if ($validated['generate_code']) {
            $codes = [];
            $prefix = $validated['code_prefix'] ?? 'CD';
            $count = $validated['code_count'] ?? 1;

            for ($i = 0; $i < $count; $i++) {
                $code = Coupon::generateUniqueCode($prefix);
                $couponData = array_merge($validated, [
                    'code' => $code,
                    'created_by' => auth()->id(),
                    'discount_value' => $validated['discount_value'] ?? 0,
                ]);
                
                // Remove non-fillable fields
                unset($couponData['generate_code'], $couponData['code_prefix'], $couponData['code_count']);
                
                Coupon::create($couponData);
                $codes[] = $code;
            }

            return redirect()->route('admin.coupons.index')
                ->with('success', "Generated {$count} coupon(s) successfully!")
                ->with('generated_codes', $codes);
        }

        // Single coupon creation
        $validated['code'] = $validated['code'] ?: Coupon::generateUniqueCode();
        $validated['created_by'] = auth()->id();
        $validated['discount_value'] = $validated['discount_value'] ?? 0;
        
        // Remove non-fillable fields
        unset($validated['generate_code'], $validated['code_prefix'], $validated['code_count']);

        $coupon = Coupon::create($validated);

        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'Coupon created successfully!');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed', ['errors' => $e->errors()]);
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        \Log::error('Coupon creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Failed to create coupon: ' . $e->getMessage())->withInput();
    }
}

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();
        
        if (!$coupon || !$coupon->canBeUsedByUser(Auth::user())) {
            return back()->with('error', 'Invalid or expired coupon code.');
        }

        // Store coupon in session for checkout
        session(['applied_coupon' => $coupon->code]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function remove()
    {
        session()->forget('applied_coupon');
        
        return back()->with('success', 'Coupon removed.');
    }
}