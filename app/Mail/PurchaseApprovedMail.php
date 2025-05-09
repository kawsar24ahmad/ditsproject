<?php

namespace App\Mail;

use App\Models\ServicePurchase;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseApprovedMail extends Mailable
{
    use Queueable, SerializesModels;


    public $user;
    public $service;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ServicePurchase $service)
    {
        $this->user = $user;
        $this->service = $service;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Service Purchase Has Been Approved',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.purchase_approved',
            with: [
                'user' => $this->user,
                'service' => $this->service,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
