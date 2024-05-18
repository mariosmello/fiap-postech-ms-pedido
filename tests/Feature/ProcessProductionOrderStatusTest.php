<?php

uses(\Illuminate\Foundation\Testing\DatabaseMigrations::class);

it('can update order status', function () {

    \Illuminate\Support\Facades\Queue::fake();
    $order = \App\Models\Order::create([
        'code' => '12345',
        'status' => 'pending',
    ]);

    $job = new \App\Jobs\ProcessProductionOrderStatus([
        'code' => '12345',
        'status' => 'ready',
    ]);
    $job->handle();

    $order->refresh();
    $this->assertEquals('ready', $order->status);

});
