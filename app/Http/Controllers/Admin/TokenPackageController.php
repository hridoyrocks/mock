<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TokenPackage;
use Illuminate\Http\Request;

class TokenPackageController extends Controller
{
    /**
     * Display a listing of token packages
     */
    public function index()
    {
        $packages = TokenPackage::orderBy('sort_order')
            ->orderBy('tokens_count')
            ->paginate(20);
        
        return view('admin.token-packages.index', compact('packages'));
    }
    
    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.token-packages.create');
    }
    
    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tokens_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'bonus_tokens' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer'
        ]);
        
        TokenPackage::create($request->all());
        
        return redirect()->route('admin.token-packages.index')
            ->with('success', 'Token package created successfully!');
    }
    
    /**
     * Show the form for editing package
     */
    public function edit(TokenPackage $tokenPackage)
    {
        return view('admin.token-packages.edit', compact('tokenPackage'));
    }
    
    /**
     * Update package
     */
    public function update(Request $request, TokenPackage $tokenPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tokens_count' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'bonus_tokens' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer'
        ]);
        
        $tokenPackage->update($request->all());
        
        return redirect()->route('admin.token-packages.index')
            ->with('success', 'Token package updated successfully!');
    }
    
    /**
     * Toggle package status
     */
    public function toggleStatus(TokenPackage $tokenPackage)
    {
        $tokenPackage->update(['is_active' => !$tokenPackage->is_active]);
        
        return back()->with('success', 'Package status updated!');
    }
    
    /**
     * Remove package
     */
    public function destroy(TokenPackage $tokenPackage)
    {
        $tokenPackage->delete();
        
        return redirect()->route('admin.token-packages.index')
            ->with('success', 'Token package deleted successfully!');
    }
}
