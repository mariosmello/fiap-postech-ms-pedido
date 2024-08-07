<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InvoicePaymentFailed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoice;

    public function __construct($invoice) {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::where('code', $this->invoice['order'])->firstOrFail();
        $order->payment_status = $this->invoice['status'];
        $order->status = 'cancelled';
        $order->save();
    }
}
