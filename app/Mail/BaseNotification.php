<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BaseNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $subjectLine;

    public function __construct($subjectLine, $data)
    {
        $this->subjectLine = $subjectLine;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.base');
    }
}

