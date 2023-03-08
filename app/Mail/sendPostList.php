<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mial\Mailable\Content;
use Illuminate\Mail\Mailables\Attachment;
class sendPostList extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $filepath;
    private $mail;
    public function __construct($filepath,$mail)
    {
        $this->filepath = $filepath;
        $this->$mail = $mail;
    }

    public function envelope():Envelope{
        return new Envelope(
            from: new Address('noreply@gmail.com','Bulletin Board'),
            subject:'Post List'
        );
    }
    public function content():Content{
        return new Content(
            text: 'emails.orders.shipped-text'
        );
    }
    public function attachments():array{
        return[
            Attachment:fromPath($this->filepath)
            ->as("postlist.csv")
            ->withMime('text/csv');
        ]
    }
}
