<?php

namespace App\Jobs;

use App\Actions\UpdateOrderOnInvoiceCreation;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceCreationFailed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $order_code;

    public string $exception;

    public function handle(): void
    {
        $order = Order::where('code', $this->order_code)->firstOrFail();
        $order->status = 'cancelled';
        $order->payment_status = 'cancelled';
        $order->status_message = $this->exception;
        $order->save();
    }
}
