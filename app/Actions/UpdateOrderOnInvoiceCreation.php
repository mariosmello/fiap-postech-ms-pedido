<?php

namespace App\Actions;

use App\Models\Order;

class UpdateOrderOnInvoiceCreation
{
    public function handle(array $invoice) :void
    {
        $order = Order::where('code', $invoice['order'])->firstOrFail();
        $order->payment_status = 'invoice_created';
        $order->pix = $invoice['pix'];
        $order->invoice = $invoice['id'];
        $order->save();
    }

}
