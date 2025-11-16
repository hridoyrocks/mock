<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSection;
use App\Models\TestSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestSetController extends Controller
{
    /**
     * Display a listing of the test sets.
     */
    public function index(Request $request): View
    {
        $query = TestSet::with('section');
        
        // Filter by section
        if ($request->has('section')) {
            $query->whereHas('section', function ($q) use ($request) {
                $q->where('name', $request->section);
            });
        }
        
        $testSets = $query->latest()->paginate(15);
        
        $sections = TestSection::all();
        
        return view('admin.test-sets.index', compact('testSets', 'sections'));
    }

    /**
     * Show the form for creating a new test set.
     */
    public function create(): View
    {
        $sections = TestSection::all();
        
        return view('admin.test-sets.create', compact('sections'));
    }

    /**
     * Store a newly created test set in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_id' => 'required|exists:test_sections,id',
            'active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        TestSet::create([
            'title' => $request->title,
            'section_id' => $request->section_id,
            'active' => $request->has('active'),
            'is_premium' => $request->has('is_premium'),
        ]);

        return redirect()->route('admin.test-sets.index')
            ->with('success', 'Test set created successfully.');
    }

    /**
     * Display the specified test set.
     */
    public function show(TestSet $testSet): View
    {
        $testSet->load(['section', 'questions' => function ($query) {
            $query->orderBy('order_number');
        }, 'questions.options']);
        
        return view('admin.test-sets.show', compact('testSet'));
    }

    /**
     * Show the form for editing the specified test set.
     */
    public function edit(TestSet $testSet): View
    {
        $sections = TestSection::all();
        
        return view('admin.test-sets.edit', compact('testSet', 'sections'));
    }

    /**
     * Update the specified test set in storage.
     */
    public function update(Request $request, TestSet $testSet): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'section_id' => 'required|exists:test_sections,id',
            'active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $testSet->update([
            'title' => $request->title,
            'section_id' => $request->section_id,
            'active' => $request->has('active'),
            'is_premium' => $request->has('is_premium'),
        ]);

        return redirect()->route('admin.test-sets.index')
            ->with('success', 'Test set updated successfully.');
    }

    /**
     * Remove the specified test set from storage.
     */
    public function destroy(TestSet $testSet): RedirectResponse
    {
        // Check if this test set has associated questions
        if ($testSet->questions()->exists()) {
            return redirect()->route('admin.test-sets.index')
                ->with('error', 'Cannot delete test set with associated questions.');
        }
        
        // Check if this test set has associated student attempts
        if ($testSet->attempts()->exists()) {
            return redirect()->route('admin.test-sets.index')
                ->with('error', 'Cannot delete test set with associated student attempts.');
        }
        
        $testSet->delete();
        
        return redirect()->route('admin.test-sets.index')
            ->with('success', 'Test set deleted successfully.');
    }
}