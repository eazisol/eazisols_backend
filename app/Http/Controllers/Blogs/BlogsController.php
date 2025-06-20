<?php

namespace App\Http\Controllers\Blogs;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Blog::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Sort options
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $blogs = $query->paginate(10);
        
        // Get unique values for filters
        $categories = Blog::select('category')->distinct()->pluck('category');
        $statuses = ['draft', 'published'];

        return view('blogs.index', compact('blogs', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
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
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug from title
        $slug = Str::slug($validated['title']);
        
        // Check if the slug exists among non-deleted records
        $existingActive = Blog::where('slug', $slug)->exists();
        
        if ($existingActive) {
            return back()
                ->withInput()
                ->withErrors(['title' => 'A blog with this title already exists.']);
        }
        
        // Check if it exists among soft-deleted records
        $existingTrashed = Blog::withTrashed()->where('slug', $slug)->whereNotNull('deleted_at')->exists();
        
        // If it exists in trash, just proceed - the unique constraint won't be triggered now
        $validated['slug'] = $slug;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blogs'), $fileName);
            $validated['thumbnail'] = 'uploads/blogs/' . $fileName;
        }

        Blog::create($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        // Get related blogs (same category)
        $relatedBlogs = Blog::where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->where('status', 'published')
            ->limit(3)
            ->get();
            
        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update slug if title changed
        if ($blog->title !== $validated['title']) {
            $newSlug = Str::slug($validated['title']);
            
            // Check if the slug exists among active records (except the current blog)
            $existingActive = Blog::where('slug', $newSlug)
                ->where('id', '!=', $blog->id)
                ->exists();
                
            if ($existingActive) {
                return back()
                    ->withInput()
                    ->withErrors(['title' => 'A blog with this title already exists.']);
            }
            
            // No need to check trashed items since unique constraint was removed
            $validated['slug'] = $newSlug;
        }
        
        // Handle thumbnail upload or removal
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
                unlink(public_path($blog->thumbnail));
            }
            
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/blogs'), $fileName);
            $validated['thumbnail'] = 'uploads/blogs/' . $fileName;
        } else if ($request->has('thumbnail_remove') && $request->thumbnail_remove == '1') {
            // Remove thumbnail if requested
            if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
                unlink(public_path($blog->thumbnail));
            }
            $validated['thumbnail'] = null;
        }

        $blog->update($validated);

        return redirect()->route('blogs.index')
            ->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        // Delete thumbnail if exists
        if ($blog->thumbnail && file_exists(public_path($blog->thumbnail))) {
            unlink(public_path($blog->thumbnail));
        }
        
        $blog->delete();

        return redirect()->route('blogs.index')
            ->with('success', 'Blog deleted successfully.');
    }
    
    /**
     * Restore a soft-deleted blog.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();
        
        return redirect()->route('blogs.index')
            ->with('success', 'Blog restored successfully.');
    }
}
