<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyFattura24Report extends Mailable
{
    use Queueable, SerializesModels;

    public $orders;
    public $totalAmount;

    /**
     * Create a new message instance.
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
        $this->totalAmount = $orders->sum('amount');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Report Trimestrale Fattura24 - ' . $this->orders->count() . ' ordini processati',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-fattura24-report',
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
