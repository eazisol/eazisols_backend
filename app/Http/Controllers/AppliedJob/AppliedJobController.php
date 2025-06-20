<?php

namespace App\Http\Controllers\AppliedJob;

use App\Http\Controllers\Controller;
use App\Models\AppliedJob;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppliedJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AppliedJob::with('career');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('career', function($query) use ($search) {
                      $query->where('title', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by career
        if ($request->has('career_id') && $request->career_id != '') {
            $query->where('career_id', $request->career_id);
        }

        // Sort options
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $appliedJobs = $query->paginate(15);
        
        // Get all careers for filter dropdown
        $careers = Career::pluck('title', 'id');
        
        // Get status options for filter dropdown
        $statuses = [
            'pending' => 'Pending',
            'viewed' => 'Viewed',
            'approved' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return view('applied_jobs.index', compact('appliedJobs', 'careers', 'statuses'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppliedJob  $appliedJob
     * @return \Illuminate\Http\Response
     */
    public function show(AppliedJob $appliedJob)
    {
        // If status is pending, mark as viewed
        if ($appliedJob->status === 'pending') {
            $appliedJob->status = 'viewed';
            $appliedJob->reviewed_at = now();
            $appliedJob->save();
        }
        
        return view('applied_jobs.show', compact('appliedJob'));
    }

    /**
     * Update the status of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppliedJob  $appliedJob
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, AppliedJob $appliedJob)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,viewed,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $appliedJob->status = $validated['status'];
        
        if (isset($validated['admin_notes'])) {
            $appliedJob->admin_notes = $validated['admin_notes'];
        }
        
        // Update reviewed_at timestamp if not already set
        if (!$appliedJob->reviewed_at) {
            $appliedJob->reviewed_at = now();
        }
        
        $appliedJob->save();

        return redirect()->route('applied-jobs.index', $appliedJob) 
        ->with('success', 'Application status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppliedJob  $appliedJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppliedJob $appliedJob)
    {
        // Delete resume file if it exists
        $resumePath = public_path($appliedJob->resume);
        if (file_exists($resumePath)) {
            unlink($resumePath);
        }
        
        $appliedJob->delete();

        return redirect()->route('applied-jobs.index')
            ->with('success', 'Application deleted successfully.');
    }
    
    /**
     * API endpoint to store a new job application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function applyJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'career_id' => 'required|exists:careers,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'cover_letter' => 'nullable|string',
            'current_company' => 'nullable|string|max:255',
            'current_position' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'expected_salary' => 'nullable|string|max:100',
            'available_from' => 'nullable|date',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'certifications' => 'nullable|string',
            'portfolio_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle resume upload
        if ($request->hasFile('resume')) {
            $resumeFile = $request->file('resume');
            $resumeName = time() . '_' . $resumeFile->getClientOriginalName();
            
            // Create uploads/resume directory if it doesn't exist
            $uploadPath = public_path('uploads/resume');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Move the file to public/uploads/resume directory
            $resumeFile->move($uploadPath, $resumeName);
            $resumePath = 'uploads/resume/' . $resumeName;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Resume file is required'
            ], 422);
        }

        // Create the applied job record
        $appliedJob = new AppliedJob();
        $appliedJob->career_id = $request->career_id;
        $appliedJob->name = $request->name;
        $appliedJob->email = $request->email;
        $appliedJob->phone = $request->phone;
        $appliedJob->resume = $resumePath;
        $appliedJob->cover_letter = $request->cover_letter;
        $appliedJob->current_company = $request->current_company;
        $appliedJob->current_position = $request->current_position;
        $appliedJob->experience_years = $request->experience_years;
        $appliedJob->expected_salary = $request->expected_salary;
        $appliedJob->available_from = $request->available_from;
        $appliedJob->skills = $request->skills;
        $appliedJob->education = $request->education;
        $appliedJob->certifications = $request->certifications;
        $appliedJob->portfolio_url = $request->portfolio_url;
        $appliedJob->linkedin_url = $request->linkedin_url;
        $appliedJob->github_url = $request->github_url;
        $appliedJob->additional_info = $request->additional_info;
        $appliedJob->status = 'pending';
        $appliedJob->save();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully',
            'data' => [
                'id' => $appliedJob->id,
                'status' => $appliedJob->status,
            ]
        ], 201);
    }
} 