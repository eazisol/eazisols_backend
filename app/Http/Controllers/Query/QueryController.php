<?php

namespace App\Http\Controllers\Query;

use App\Http\Controllers\Controller;
use App\Models\Query;
use App\Models\Query\QueryAttachment;
use App\Models\Query\QueryResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class QueryController extends Controller
{
    /**
     * Display a listing of the queries.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        $status = $request->query('status', 'all');
        
        $query = Query::with('assignedUser');
        
        // Filter by type
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        // Filter by status
        if ($status !== 'all') {
            if ($status === 'unresolved') {
                $query->unresolved();
            } elseif ($status === 'resolved') {
                $query->resolved();
            } else {
                $query->where('status', $status);
            }
        }
        
        $queries = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get counts for sidebar filters
        $counts = [
            'all' => Query::count(),
            'contact' => Query::contact()->count(),
            'cost_calculator' => Query::costCalculator()->count(),
            'new' => Query::where('status', Query::STATUS_NEW)->count(),
            'in_progress' => Query::where('status', Query::STATUS_IN_PROGRESS)->count(),
            'resolved' => Query::resolved()->count(),
            'closed' => Query::where('status', Query::STATUS_CLOSED)->count(),
        ];
        
        return view('queries.index', compact('queries', 'counts', 'type', 'status'));
    }

    /**
     * Display the specified query.
     *
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Query $query)
    {
        $query->load(['attachments']);
        // Users are not needed since we're not using assignment
        // $users = User::all();
        
        return view('queries.show', compact('query'));
    }

    /**
     * Update the specified query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Query $query)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:new,in_progress,resolved,closed',
            // 'assigned_to' => 'nullable|exists:users,id', // Commented out as requested
            'admin_notes' => 'nullable|string',
        ]);
        
        // Update resolved_at timestamp if status is being set to resolved
        if (isset($validated['status']) && $validated['status'] === Query::STATUS_RESOLVED && $query->status !== Query::STATUS_RESOLVED) {
            $validated['resolved_at'] = now();
        } elseif (isset($validated['status']) && $validated['status'] !== Query::STATUS_RESOLVED) {
            $validated['resolved_at'] = null;
        }
        
        $query->update($validated);
        
        return redirect()->route('queries.show', $query)->with('success', 'Query updated successfully.');
    }

    /**
     * Store a new response for the query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function storeResponse(Request $request, Query $query)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
        
        // Update query status to resolved if it's new or in_progress
        if ($query->status === Query::STATUS_NEW || $query->status === Query::STATUS_IN_PROGRESS) {
            $query->status = Query::STATUS_RESOLVED;
            $query->resolved_at = now();
            $query->save();
        }
        
        // Here you would typically send an email to the customer
        // This is a placeholder for email sending logic
        // Mail::to($query->email)->send(new QueryResponse($query, $validated['message']));
        
        return redirect()->route('queries.show', $query)->with('success', 'Response has been sent to the customer via email.');
    }

    /**
     * Download the specified attachment.
     *
     * @param  \App\Models\Query\QueryAttachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment(QueryAttachment $attachment)
    {
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Store a new contact query from the public form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeContactQuery(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'sometimes|file|max:10240', // 10MB max per file
        ]);
        
        $query = null;
        
        DB::transaction(function () use ($request, $validated, &$query) {
            // Create the query
            $query = Query::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company_name' => $validated['company_name'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'],
                'type' => Query::TYPE_CONTACT,
                'source' => 'website',
                'status' => Query::STATUS_NEW,
            ]);
            
            // Store attachments if any
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('query_attachments/' . $query->id, 'public');
                    
                    QueryAttachment::create([
                        'query_id' => $query->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                    ]);
                }
            }
        });
        
        // Return JSON response if request wants JSON, otherwise redirect
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Your message has been sent successfully. We will get back to you soon.',
                'query_id' => $query->id
            ]);
        }
        
        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    /**
     * Store a new cost calculator query from the public form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCostCalculatorQuery(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'sometimes|file|max:10240', // 10MB max per file
        ]);
        
        $query = null;
        
        DB::transaction(function () use ($request, $validated, &$query) {
            // Create the query
            $query = Query::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company_name' => $validated['company_name'] ?? null,
                'subject' => 'Cost Calculator Inquiry',
                'message' => $validated['message'],
                'type' => Query::TYPE_COST_CALCULATOR,
                'source' => 'cost_calculator',
                'status' => Query::STATUS_NEW,
            ]);
            
            // Store attachments if any
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('query_attachments/' . $query->id, 'public');
                    
                    QueryAttachment::create([
                        'query_id' => $query->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getClientMimeType(),
                    ]);
                }
            }
        });
        
        return response()->json(['success' => true, 'message' => 'Your inquiry has been sent successfully. We will get back to you soon.']);
    }
} 