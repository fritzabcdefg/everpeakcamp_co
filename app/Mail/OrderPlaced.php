<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->load('orderItems.product', 'customer');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Order Confirmation #' . $this->order->order_id)
                    ->view('emails.order_summary')
                    ->with(['order' => $this->order]);
    }
}
