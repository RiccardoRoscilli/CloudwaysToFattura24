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

    public function __construct($customer, $order)
    {
        $this->customer = $customer;
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Istruzioni per il pagamento')
                    ->view('emails.payment_instructions')
                    ->with([
                        'customerName' => $this->customer->name,
                        'orderId' => $this->order->id,
                        'orderDate' => $this->order->created_at, // Data dell'ordine
                        'amount' => $this->order->amount,
                        'paymentDetails' => 'IBAN: IT60C0301503200000003248252',
                    ]);
    }
    
    
}
