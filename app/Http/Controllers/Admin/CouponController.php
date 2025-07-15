<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::with(['plan', 'creator', 'redemptions']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('valid_until', '<', now());
            }
        }

        // Filter by plan
        if ($request->filled('plan_id')) {
            $query->where('plan_id', $request->plan_id);
        }

        $coupons = $query->latest()->paginate(20);
        $plans = SubscriptionPlan::all();

        return view('admin.coupons.index', compact('coupons', 'plans'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->where('is_free', false)
            ->get();

        return view('admin.coupons.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|unique:coupons,code',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed,full_access,trial',
            'discount_value' => 'required_unless:discount_type,full_access|numeric|min:0',
            'plan_id' => 'required|exists:subscription_plans,id',
            'duration_days' => 'required_if:discount_type,trial|nullable|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
            'generate_code' => 'boolean',
            'code_prefix' => 'nullable|string|max:10',
            'code_count' => 'required_if:generate_code,true|integer|min:1|max:100',
        ]);

        // Generate codes if requested
        if ($request->boolean('generate_code')) {
            $codes = [];
            $prefix = $request->code_prefix ?: 'CD';
            $count = $request->code_count ?: 1;

            for ($i = 0; $i < $count; $i++) {
                $code = Coupon::generateUniqueCode($prefix);
                $couponData = array_merge($validated, [
                    'code' => $code,
                    'created_by' => auth()->id(),
                ]);
                
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

        $coupon = Coupon::create($validated);

        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'Coupon created successfully!');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['plan', 'creator', 'redemptions.user', 'redemptions.subscription']);
        
        // Get usage statistics
        $stats = [
            'total_redemptions' => $coupon->redemptions->count(),
            'total_discount_given' => $coupon->redemptions->sum('discount_amount'),
            'active_subscriptions' => $coupon->redemptions->filter(function ($redemption) {
                return $redemption->subscription && $redemption->subscription->isActive();
            })->count(),
        ];

        return view('admin.coupons.show', compact('coupon', 'stats'));
    }

    public function edit(Coupon $coupon)
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->where('is_free', false)
            ->get();

        return view('admin.coupons.edit', compact('coupon', 'plans'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed,full_access,trial',
            'discount_value' => 'required_unless:discount_type,full_access|numeric|min:0',
            'plan_id' => 'required|exists:subscription_plans,id',
            'duration_days' => 'required_if:discount_type,trial|nullable|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.show', $coupon)
            ->with('success', 'Coupon updated successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used
        if ($coupon->redemptions()->exists()) {
            return back()->with('error', 'Cannot delete a coupon that has been used. You can deactivate it instead.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return back()->with('success', 'Coupon status updated successfully!');
    }

    public function bulkExport(Request $request)
    {
        $couponIds = $request->input('coupon_ids', []);
        $coupons = Coupon::whereIn('id', $couponIds)->get();

        $csv = "Code,Description,Discount,Plan,Valid Until,Status\n";
        foreach ($coupons as $coupon) {
            $csv .= "{$coupon->code},";
            $csv .= "{$coupon->description},";
            $csv .= "{$coupon->formatted_discount},";
            $csv .= "{$coupon->plan->name},";
            $csv .= $coupon->valid_until ? $coupon->valid_until->format('Y-m-d') : 'No Expiry';
            $csv .= ",{$coupon->is_active}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="coupons-' . date('Y-m-d') . '.csv"');
    }
}