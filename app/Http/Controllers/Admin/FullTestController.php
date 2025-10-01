<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FullTest;
use App\Models\TestSet;
use App\Models\TestSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FullTestController extends Controller
{
    /**
     * Display a listing of full tests.
     */
    public function index()
    {
        $fullTests = FullTest::with('testSets')
            ->orderBy('order_number')
            ->paginate(10);
        
        return view('admin.full-tests.index', compact('fullTests'));
    }

    /**
     * Show the form for creating a new full test.
     */
    public function create()
    {
        $testSets = TestSet::with('section')
            ->where('active', true)
            ->get()
            ->groupBy('section.name');
        
        return view('admin.full-tests.create', compact('testSets'));
    }

    /**
     * Store a newly created full test.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_premium' => 'boolean',
            'active' => 'boolean',
            'listening_test_set_id' => 'nullable|exists:test_sets,id',
            'reading_test_set_id' => 'nullable|exists:test_sets,id',
            'writing_test_set_id' => 'nullable|exists:test_sets,id',
            'speaking_test_set_id' => 'nullable|exists:test_sets,id',
        ]);
        
        // Validate minimum 3 sections
        $selectedSections = array_filter([
            $validated['listening_test_set_id'] ?? null,
            $validated['reading_test_set_id'] ?? null,
            $validated['writing_test_set_id'] ?? null,
            $validated['speaking_test_set_id'] ?? null,
        ]);
        
        if (count($selectedSections) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select at least 3 sections to create a full test.');
        }

        DB::beginTransaction();
        
        try {
            // Create full test
            $fullTest = FullTest::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_premium' => $validated['is_premium'] ?? false,
                'active' => $validated['active'] ?? true,
                'order_number' => FullTest::max('order_number') + 1
            ]);
            
            // Attach test sets (only non-null ones)
            $sections = [
                'listening' => $validated['listening_test_set_id'] ?? null,
                'reading' => $validated['reading_test_set_id'] ?? null,
                'writing' => $validated['writing_test_set_id'] ?? null,
                'speaking' => $validated['speaking_test_set_id'] ?? null,
            ];
            
            $order = 1;
            foreach ($sections as $type => $testSetId) {
                if ($testSetId) {
                    $fullTest->testSets()->attach($testSetId, [
                        'section_type' => $type,
                        'order_number' => $order++
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.full-tests.index')
                ->with('success', 'Full test created successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create full test. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified full test.
     */
    public function show(FullTest $fullTest)
    {
        $fullTest->load('testSets.section', 'attempts.user');
        
        return view('admin.full-tests.show', compact('fullTest'));
    }

    /**
     * Show the form for editing the specified full test.
     */
    public function edit(FullTest $fullTest)
    {
        $testSets = TestSet::with('section')
            ->where('active', true)
            ->get()
            ->groupBy('section.name');
        
        $fullTest->load('testSets');
        
        return view('admin.full-tests.edit', compact('fullTest', 'testSets'));
    }

    /**
     * Update the specified full test.
     */
    public function update(Request $request, FullTest $fullTest)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_premium' => 'boolean',
            'active' => 'boolean',
            'listening_test_set_id' => 'nullable|exists:test_sets,id',
            'reading_test_set_id' => 'nullable|exists:test_sets,id',
            'writing_test_set_id' => 'nullable|exists:test_sets,id',
            'speaking_test_set_id' => 'nullable|exists:test_sets,id',
        ]);
        
        // Validate minimum 3 sections
        $selectedSections = array_filter([
            $validated['listening_test_set_id'] ?? null,
            $validated['reading_test_set_id'] ?? null,
            $validated['writing_test_set_id'] ?? null,
            $validated['speaking_test_set_id'] ?? null,
        ]);
        
        if (count($selectedSections) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select at least 3 sections to update the full test.');
        }

        DB::beginTransaction();
        
        try {
            // Update full test
            $fullTest->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'is_premium' => $validated['is_premium'] ?? false,
                'active' => $validated['active'] ?? true,
            ]);
            
            // Sync test sets
            $fullTest->testSets()->detach();
            
            $sections = [
                'listening' => $validated['listening_test_set_id'] ?? null,
                'reading' => $validated['reading_test_set_id'] ?? null,
                'writing' => $validated['writing_test_set_id'] ?? null,
                'speaking' => $validated['speaking_test_set_id'] ?? null,
            ];
            
            $order = 1;
            foreach ($sections as $type => $testSetId) {
                if ($testSetId) {
                    $fullTest->testSets()->attach($testSetId, [
                        'section_type' => $type,
                        'order_number' => $order++
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.full-tests.index')
                ->with('success', 'Full test updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update full test. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified full test.
     */
    public function destroy(FullTest $fullTest)
    {
        // Check if there are any attempts
        if ($fullTest->attempts()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete full test with existing attempts.');
        }
        
        $fullTest->delete();
        
        return redirect()->route('admin.full-tests.index')
            ->with('success', 'Full test deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(FullTest $fullTest)
    {
        $fullTest->update([
            'active' => !$fullTest->active
        ]);
        
        return redirect()->back()
            ->with('success', 'Full test status updated successfully.');
    }

    /**
     * Reorder full tests.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:full_tests,id'
        ]);
        
        foreach ($validated['ids'] as $order => $id) {
            FullTest::where('id', $id)->update(['order_number' => $order + 1]);
        }
        
        return response()->json(['success' => true]);
    }
}
