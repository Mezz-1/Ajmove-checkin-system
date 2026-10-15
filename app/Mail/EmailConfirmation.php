<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class EmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    
    public Registration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Convocation Officielle & Pass QR Code - Forum Ben M\'sik',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        // Render the PDF view in-memory
        $pdf = Pdf::loadView('pdf.convocation', ['registration' => $this->registration]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(), 
                'Convocation_Forum_' . $this->registration->nom . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}