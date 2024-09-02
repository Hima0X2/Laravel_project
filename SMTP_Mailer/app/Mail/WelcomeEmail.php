<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

   public $request;
    public $fileName;

    public function __construct($request, $fileName)
    {
        $this->request = $request;
        $this->fileName = $fileName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contact form',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.welcomemail',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
    
        $filePath = public_path('/uploads/' . $this->fileName);
        if ($this->fileName && file_exists($filePath)) {
            $attachments[] = Attachment::fromPath($filePath);
        }
        return $attachments;
    }
    
}
