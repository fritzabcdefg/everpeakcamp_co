<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $token)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            to: $this->user->email,
            subject: 'Verify Your EverPeak Camp Email Address',
        );
    }

    public function content(): Content
    {
        $verificationUrl = route('email.verify', ['token' => $this->token]);
        
        return new Content(
            view: 'emails.verify-email',
            with: [
                'user' => $this->user,
                'verificationUrl' => $verificationUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
