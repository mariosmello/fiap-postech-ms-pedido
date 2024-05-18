<?php

uses(\Illuminate\Foundation\Testing\DatabaseMigrations::class);

it('can update order status', function () {

    \Illuminate\Support\Facades\Queue::fake();
    $order = \App\Models\Order::create([
        'code' => '12345',
        'payment_status' => 'pending',
    ]);
    $invoice = [
        'order' => '12345',
        'status' => 'paid',
    ];

    $job = new \App\Jobs\ProcessWebhookStatus($invoice);
    $job->handle();

    $order->refresh();
    $this->assertEquals('paid', $order->payment_status);

});
