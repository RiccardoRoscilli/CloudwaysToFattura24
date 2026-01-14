<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderSentToFattura24 extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $docId;
    public $docNumber;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $docId, $docNumber)
    {
        $this->order = $order;
        $this->docId = $docId;
        $this->docNumber = $docNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ordine #' . $this->order->id . ' inviato a Fattura24',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-sent-fattura24',
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
