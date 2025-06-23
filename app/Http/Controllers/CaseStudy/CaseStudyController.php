<?php

namespace App\Http\Controllers\CaseStudy;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaseStudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CaseStudy::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('client_name', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by featured
        // if ($request->has('is_featured') && $request->is_featured != '') {
        //     $query->where('is_featured', $request->is_featured);
        // }

        // Sort options
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $caseStudies = $query->paginate(10);
        
        // Get categories from the Categories table
        $categories = Category::where('type', 'case_study')
                     ->where('status', 'active')
                     ->get();
                     
        $statuses = ['active', 'inactive'];
        $featured = [0 => 'No', 1 => 'Yes'];

        return view('case_studies.index', compact('caseStudies', 'categories', 'statuses', 'featured'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get categories from Categories table
        $categories = Category::where('type', 'case_study')
                     ->where('status', 'active')
                     ->get();
        
        return view('case_studies.create', compact('categories'));
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
            'slug' => 'nullable|string|max:255|unique:case_studies',
            'client_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'short_summary' => 'nullable|string|max:500',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'project_url' => 'nullable|url|max:255',
            'status' => 'required|string|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        // Check if slug exists
        $existingSlug = CaseStudy::where('slug', $validated['slug'])->exists();
        
        if ($existingSlug) {
            return back()
                ->withInput()
                ->withErrors(['slug' => 'This slug already exists. Please choose a different slug.']);
        }
        
        // Get category name for backward compatibility
        if (isset($validated['category_id']) && $validated['category_id']) {
            $category = Category::find($validated['category_id']);
            if ($category) {
                $validated['category'] = $category->name;
            }
        }
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/case_studies'), $fileName);
            $validated['thumbnail'] = 'uploads/case_studies/' . $fileName;
        }

        // Handle multiple image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/case_studies'), $fileName);
                $images[] = 'uploads/case_studies/' . $fileName;
            }
            $validated['images'] = $images;
        }
        
        // Handle checkbox for is_featured
        $validated['is_featured'] = $request->has('is_featured');

        CaseStudy::create($validated);

        return redirect()->route('case_studies.index')
            ->with('success', 'Case study created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CaseStudy  $caseStudy
     * @return \Illuminate\Http\Response
     */
    public function show(CaseStudy $caseStudy)
    {
        return view('case_studies.show', compact('caseStudy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CaseStudy  $caseStudy
     * @return \Illuminate\Http\Response
     */
    public function edit(CaseStudy $caseStudy)
    {
        // Get categories from Categories table
        $categories = Category::where('type', 'case_study')
                     ->where('status', 'active')
                     ->get();
        
        return view('case_studies.edit', compact('caseStudy', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CaseStudy  $caseStudy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CaseStudy $caseStudy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:case_studies,slug,' . $caseStudy->id,
            'client_name' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'short_summary' => 'nullable|string|max:500',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'project_url' => 'nullable|url|max:255',
            'status' => 'required|string|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Generate slug from title if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        // Check if slug exists (excluding current case study)
        $existingSlug = CaseStudy::where('slug', $validated['slug'])
            ->where('id', '!=', $caseStudy->id)
            ->exists();
        
        if ($existingSlug) {
            return back()
                ->withInput()
                ->withErrors(['slug' => 'This slug already exists. Please choose a different slug.']);
        }
        
        // Get category name for backward compatibility
        if (isset($validated['category_id']) && $validated['category_id']) {
            $category = Category::find($validated['category_id']);
            if ($category) {
                $validated['category'] = $category->name;
            }
        } else {
            $validated['category'] = null;
        }
        
        // Handle thumbnail upload or removal
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($caseStudy->thumbnail && file_exists(public_path($caseStudy->thumbnail))) {
                unlink(public_path($caseStudy->thumbnail));
            }
            
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/case_studies'), $fileName);
            $validated['thumbnail'] = 'uploads/case_studies/' . $fileName;
        } elseif ($request->has('remove_thumbnail')) {
            // Remove thumbnail if requested
            if ($caseStudy->thumbnail && file_exists(public_path($caseStudy->thumbnail))) {
                unlink(public_path($caseStudy->thumbnail));
            }
            $validated['thumbnail'] = null;
        }

        // Handle multiple image uploads and existing images
        $existingImages = $caseStudy->images ?? [];
        
        // Remove specific images if requested
        if ($request->has('remove_images') && is_array($request->remove_images)) {
            foreach ($request->remove_images as $index) {
                if (isset($existingImages[$index]) && file_exists(public_path($existingImages[$index]))) {
                    unlink(public_path($existingImages[$index]));
                    unset($existingImages[$index]);
                }
            }
            // Re-index array
            $existingImages = array_values($existingImages);
        }
        
        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/case_studies'), $fileName);
                $existingImages[] = 'uploads/case_studies/' . $fileName;
            }
        }
        
        $validated['images'] = $existingImages;
        
        // Handle checkbox for is_featured
        $validated['is_featured'] = $request->has('is_featured');

        $caseStudy->update($validated);

        return redirect()->route('case_studies.index')
            ->with('success', 'Case study updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CaseStudy  $caseStudy
     * @return \Illuminate\Http\Response
     */
    public function destroy(CaseStudy $caseStudy)
    {
        // Delete thumbnail if exists
        if ($caseStudy->thumbnail && file_exists(public_path($caseStudy->thumbnail))) {
            unlink(public_path($caseStudy->thumbnail));
        }
        
        // Delete all associated images
        if (!empty($caseStudy->images) && is_array($caseStudy->images)) {
            foreach ($caseStudy->images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }
        
        $caseStudy->delete();

        return redirect()->route('case_studies.index')
            ->with('success', 'Case study deleted successfully.');
    }
}
