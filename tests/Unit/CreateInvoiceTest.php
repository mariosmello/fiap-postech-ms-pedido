<?php

test('can create invoice', function () {

    \Illuminate\Support\Facades\Http::fake([
       '*' => \Illuminate\Support\Facades\Http::response([]),
    ]);

    $order =  \App\Models\Order::create();
    $createInvoice = new \App\Actions\CreateInvoice();
    $response = $createInvoice->handle($order);

    expect($response)->toBeArray();
});
