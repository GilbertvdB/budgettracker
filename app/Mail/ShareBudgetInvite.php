<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ShareBudgetInvite extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $inviteLink;
    public $userEmail;
    public $budgetTitle;

    /**
     * Create a new message instance.
     */
    public function __construct($inviteLink, $userEmail, $budgetTitle)
    {
        $this->inviteLink = $inviteLink;
        $this->userEmail = strstr($userEmail, '@', true);
        $this->budgetTitle = $budgetTitle;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {   
        $message = $this->userEmail . ' invited you to share '. $this->budgetTitle . ' @ BudgetTracker';
        info('message: ' . $message);
        return new Envelope(
            from: new Address('noreply@budgettracker.com', 'No Reply'),
            subject: $message,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'email.share-invitation',
            with: [
                'inviteLink' => $this->inviteLink,
                'userEmail' => $this->userEmail,
                'budgetTitle' => $this->budgetTitle,
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
