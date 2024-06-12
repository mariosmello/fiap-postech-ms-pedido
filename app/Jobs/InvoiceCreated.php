<?php

namespace App\Jobs;

use App\Actions\UpdateOrderOnInvoiceCreation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoice;

    public function handle(UpdateOrderOnInvoiceCreation $updateOrderOnInvoiceCreation): void
    {
        $updateOrderOnInvoiceCreation->handle($this->invoice);
    }
}
