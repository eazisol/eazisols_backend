<?php

namespace App\Http\Controllers\Career;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\AppliedJob;
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
        $locations = \App\Models\Location::orderBy('city')->get();
        return view('careers.create', compact('categories', 'locations'));
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
            'title' => 'required|string|max:255|unique:careers,title',
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

        // Generate unique slug from title
        $baseSlug = Str::slug($validated['title']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Check if slug exists and append counter until we find a unique one
        while (Career::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        
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
        $locations = \App\Models\Location::orderBy('city')->get();
        return view('careers.edit', compact('career', 'categories', 'locations'));
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
            'title' => 'required|string|max:255|unique:careers,title,'.$career->id,
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
            $baseSlug = Str::slug($validated['title']);
            $slug = $baseSlug;
            $counter = 1;
            
            // Check if slug exists and append counter until we find a unique one
            while (Career::where('slug', $slug)->where('id', '!=', $career->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
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
        $careerId = $career->id;

        // Get all applied jobs for this career
         // Delete all applied jobs for this career
        AppliedJob::where('career_id', $careerId)->delete();

        // Optional: Debug output
        // echo "<pre>";
        // print_r($appliedJobs);
        // echo "</pre>";
        // exit;
        // $appliedJobs->delete();
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