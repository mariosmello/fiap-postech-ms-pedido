<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class OrderRefunded implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoice;

    public function __construct(array $invoice) {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $order = Order::where("code", $this->invoice['order'])->firstOrFail();
            $order->payment_status = $this->invoice['status'];
            $order->status = 'cancelled';
            $order->save();
        } catch (\Exception $exception) {
            Log::warning($exception->getMessage(),  $this->invoice);
        }
    }
}
