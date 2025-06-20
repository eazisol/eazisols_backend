<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Career::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->ofType($request->type);
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by location
        if ($request->has('location') && $request->location != '') {
            $query->inLocation($request->location);
        }

        // Filter by department removed

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Sort options
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $careers = $query->paginate(10);
        
        // Get unique values for filters
        $types = Career::select('type')->distinct()->pluck('type');
        $categories = Category::where('type', 'career')->where('status', 'active')->pluck('name');
        $locations = Career::select('location')->distinct()->pluck('location');
        $statuses = Career::select('status')->distinct()->pluck('status');

        return view('careers.index', compact('careers', 'types', 'categories', 'locations', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('type', 'career')->where('status', 'active')->pluck('name', 'name');
        return view('careers.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            // 'department' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'status' => 'required|string|in:active,inactive,filled',
            'featured' => 'nullable|boolean',
            'vacancy_count' => 'nullable|integer|min:1',
            // SEO fields removed
        ]);

        // Generate slug from title
        $validated['slug'] = Str::slug($validated['title']);
        
        // Handle featured checkbox
        $validated['featured'] = $request->has('featured');

        Career::create($validated);

        return redirect()->route('careers.index')
            ->with('success', 'Career created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function show(Career $career)
    {
        // Get related careers (same type)
        $relatedCareers = Career::where('id', '!=', $career->id)
            ->where('type', $career->type)
            ->where('status', 'active')
            ->limit(3)
            ->get();
            
        return view('careers.show', compact('career', 'relatedCareers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function edit(Career $career)
    {
        $categories = Category::where('type', 'career')->where('status', 'active')->pluck('name', 'name');
        return view('careers.edit', compact('career', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Career $career)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'responsibilities' => 'nullable|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'location' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            // 'department' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'status' => 'required|string|in:active,inactive,filled',
            'featured' => 'nullable|boolean',
            'vacancy_count' => 'nullable|integer|min:1',
            // SEO fields removed
        ]);

        // Update slug if title changed
        if ($career->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        // Handle featured checkbox
        $validated['featured'] = $request->has('featured');

        $career->update($validated);

        return redirect()->route('careers.index')
            ->with('success', 'Career updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function destroy(Career $career)
    {
        $career->delete();

        return redirect()->route('careers.index')
            ->with('success', 'Career deleted successfully.');
    }
    
    /**
     * Restore a soft-deleted career.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $career = Career::withTrashed()->findOrFail($id);
        $career->restore();
        
        return redirect()->route('careers.index')
            ->with('success', 'Career restored successfully.');
    }
    
    /**
     * Toggle the featured status of a career.
     *
     * @param  \App\Models\Career  $career
     * @return \Illuminate\Http\Response
     */
    public function toggleFeatured(Career $career)
    {
        $career->featured = !$career->featured;
        $career->save();
        
        return redirect()->back()
            ->with('success', 'Career featured status updated successfully.');
    }
} 