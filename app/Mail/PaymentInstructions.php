<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentInstructions extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $order;
    public $items;

    public function __construct($customer, $order, $items)
    {
        $this->customer = $customer;
        $this->order = $order;
        $this->items = $items; // Aggiunto
    }

    public function build()
    {
        return $this->subject('Rinnovo Servizi PWS')
                    ->view('emails.payment_instructions')
                    ->with([
                        'customerName' => $this->customer->name,
                        'orderId' => $this->order->id,
                        'amount' => $this->order->amount,
                        'paymentDetails' => 'IBAN: IT60C0301503200000003248252',
                        'items' => $this->items, // Aggiunto
                    ]);
    }
}

