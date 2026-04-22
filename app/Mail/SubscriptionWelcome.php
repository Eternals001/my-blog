<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '欢迎订阅 ' . config('blog.name') . '！',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription.welcome',
            with: [
                'email' => $this->subscription->email,
                'token' => $this->subscription->token,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
