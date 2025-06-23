<?php

namespace App\Mail;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class OrderRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $noOrder = $this->order->no_order;
        return $this
        ->subject("Purchase Order #$noOrder")
        ->with([
            'order' => $this->order
        ])
        ->markdown('emails.order_request');
    }
}
