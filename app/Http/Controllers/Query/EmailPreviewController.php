<?php

namespace App\Http\Controllers\Query;

use App\Http\Controllers\Controller;
use App\Models\Query;
use App\Mail\QueryResponseMail;
use App\Mail\QueryStatusUpdateMail;
use Illuminate\Http\Request;

class EmailPreviewController extends Controller
{
    /**
     * Preview the query response email template.
     *
     * @return \Illuminate\Http\Response
     */
    public function previewResponseEmail()
    {
        // Create a sample query
        $query = new Query([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Sample Inquiry',
            'message' => 'This is a sample message for testing the email template.',
            'status' => 'new',
        ]);
        
        $response = 'Thank you for your inquiry. We have received your message and will get back to you as soon as possible.';
        
        return new QueryResponseMail($query, $response);
    }
    
    /**
     * Preview the query status update email template.
     *
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    public function previewStatusEmail($status = 'resolved')
    {
        // Validate status
        if (!in_array($status, ['new', 'in_progress', 'resolved', 'closed'])) {
            $status = 'resolved';
        }
        
        // Create a sample query with the specified status
        $query = new Query([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Sample Inquiry',
            'message' => 'This is a sample message for testing the email template.',
            'status' => $status,
        ]);
        
        return new QueryStatusUpdateMail($query);
    }
} 