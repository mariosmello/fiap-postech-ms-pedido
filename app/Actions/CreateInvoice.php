<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class CreateInvoice
{
    public function handle(Order $order) :array
    {

        $response = Http::withHeaders([
            'Authorization' =>  'Bearer ' . request()->bearerToken()
        ])->acceptJson()->post(env('MS_PAGAMENTO_URL') . '/api/invoices', [
            'order' => $order->code,
            'total' => $order->total,
        ]);

        return $response->json();
    }

}
