<?php

namespace App\Mail;

use App\Models\Query;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueryResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The query instance.
     *
     * @var \App\Models\Query
     */
    public $query;

    /**
     * The response message.
     *
     * @var string
     */
    public $response;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Query  $query
     * @param  string  $response
     * @return void
     */
    public function __construct(Query $query, string $response)
    {
        $this->query = $query;
        $this->response = $response;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Response to Your Inquiry: ' . ($this->query->subject ?? 'Contact Form'))
                    ->view('emails.query-response');
    }
} 