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
use App\Models\Setting;

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
        
        $oldStatus = $query->status;
        
        // Update resolved_at timestamp if status is being set to resolved
        if (isset($validated['status']) && $validated['status'] === Query::STATUS_RESOLVED && $query->status !== Query::STATUS_RESOLVED) {
            $validated['resolved_at'] = now();
        } elseif (isset($validated['status']) && $validated['status'] !== Query::STATUS_RESOLVED) {
            $validated['resolved_at'] = null;
        }
        
        $query->update($validated);
        
        // Send status update email if status has changed
        if (isset($validated['status']) && $oldStatus !== $validated['status']) {
            try {
                $statusText = ucfirst(str_replace('_', ' ', $query->status));
                
                // Send status update email using MailHelper
                \App\Helpers\MailHelper::send(
                    $query->email,
                    $query->name,
                    'Status Update: ' . $statusText . ' - ' . ($query->subject ?? 'Your Inquiry'),
                    'emails.query-status-update',
                    ['query' => $query]
                );
                
                return redirect()->route('queries.show', $query)->with('success', 'Query updated successfully and status update email sent.');
            } catch (\Exception $e) {
                return redirect()->route('queries.show', $query)->with('success', 'Query updated successfully but failed to send status email: ' . $e->getMessage());
            }
        }
        
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
        
        try {
            // Send email to the customer using MailHelper
            \App\Helpers\MailHelper::send(
                $query->email,
                $query->name,
                'Response to Your Inquiry: ' . ($query->subject ?? 'Contact Form'),
                'emails.query-response',
                ['query' => $query, 'response' => $validated['message']]
            );
            
            // Store the response in the database for record keeping
            DB::transaction(function () use ($query, $validated) {
                QueryResponse::create([
                    'query_id' => $query->id,
                    'user_id' => Auth::id(),
                    'message' => $validated['message'],
                    'is_admin' => true,
                ]);
            });
            
            return redirect()->route('queries.show', $query)->with('success', 'Response has been sent to the customer via email.');
        } catch (\Exception $e) {
            \Log::error('Query response email failed', [
                'query_id' => $query->id,
                'email' => $query->email,
                'error' => $e->getMessage()
            ]);
            
            // Store the response in the database despite email failure
            DB::transaction(function () use ($query, $validated) {
                QueryResponse::create([
                    'query_id' => $query->id,
                    'user_id' => Auth::id(),
                    'message' => $validated['message'],
                    'is_admin' => true,
                ]);
            });
            
            return redirect()->route('queries.show', $query)
                ->with('error', 'Response saved but failed to send email: ' . $e->getMessage())
                ->with('details', 'Check logs for more information. Make sure Mailtrap settings are correct.');
        }
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
            'fullName' => 'required|string|max:255',
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
                'full_name' => $validated['fullName'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company_name'] ?? null,
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'],
                'type' => Query::TYPE_CONTACT,
                'source' => 'website',
                'status' => Query::STATUS_NEW,
                'description' => $validated['message'], // Use message as description because of cost-calculator
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
        'fullName' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'company' => 'nullable|string|max:255',
        'description' => 'required|string',
        'services' => 'nullable|string|max:255',
        'industry' => 'nullable|string|max:255',
        'otherIndustry' => 'nullable|string|max:255',
        'stage' => 'nullable|string|max:255',
        'timeline' => 'nullable|string|max:255',
        'budget' => 'nullable|string|max:255',
        'file' => 'nullable|file|max:10240', // 10MB
    ]);

    $query = null;

    DB::transaction(function () use ($request, $validated, &$query) {
        // Save query record
        $query = Query::create([
            'full_name'      => $validated['fullName'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'] ?? null,
            'company'        => $validated['company'] ?? null,
            'description'    => $validated['description'],
            'services'       => $validated['services'] ?? null,
            'industry'       => $validated['industry'] ?? null,
            'other_industry' => $validated['otherIndustry'] ?? null,
            'stage'          => $validated['stage'] ?? null,
            'timeline'       => $validated['timeline'] ?? null,
            'budget'         => $validated['budget'] ?? null,
            'subject'        => 'Cost Calculator Inquiry',
            'type'           => Query::TYPE_COST_CALCULATOR,
            'source'         => 'cost_calculator',
            'status'         => Query::STATUS_NEW,
        ]);

        // Store file if attached
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('query_attachments/' . $query->id, 'public');

            QueryAttachment::create([
                'query_id'  => $query->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
            ]);
        }
    });

    return response()->json([
        'success' => true,
        'message' => 'Your inquiry has been sent successfully. We will get back to you soon.'
    ]);
}

} 