<?php

namespace App\Mail;

use App\Models\Query;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueryStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The query instance.
     *
     * @var \App\Models\Query
     */
    public $query;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Query  $query
     * @return void
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $statusText = ucfirst(str_replace('_', ' ', $this->query->status));
        
        return $this->subject('Status Update: ' . $statusText . ' - ' . ($this->query->subject ?? 'Your Inquiry'))
                    ->view('emails.query-status-update');
    }
} 