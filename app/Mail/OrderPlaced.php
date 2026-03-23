<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public int $tries = 3;
    public int $timeout = 15;

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
        $pdf = Pdf::loadView('emails.receipt_pdf', [
            'order' => $this->order,
        ]);

        // Create a signed URL for downloading the receipt
        $receiptDownloadUrl = \Illuminate\Support\Facades\URL::signedRoute(
            'orders.downloadReceipt',
            ['order' => $this->order->order_id]
        );

        return $this->subject('Order Confirmation #' . $this->order->order_id)
                    ->view('emails.order_summary')
                    ->with([
                        'order' => $this->order,
                        'receiptDownloadUrl' => $receiptDownloadUrl,
                    ])
                    ->attachData(
                        $pdf->output(),
                        'receipt-order-' . $this->order->order_id . '.pdf',
                        ['mime' => 'application/pdf']
                    );
    }
}
