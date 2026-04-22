<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionUnsubscribeNotice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '退订确认 - ' . config('blog.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription.unsubscribe',
            with: [
                'subscription' => $this->subscription,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
