<?php

uses(\Illuminate\Foundation\Testing\DatabaseMigrations::class);

beforeEach(function () {
    $this->token = \Firebase\JWT\JWT::encode([
        'sub' => '1',
        'name' => fake()->name(),
        'email' => fake()->email(),
    ], env('JWT_SECRET'), 'HS256');
});

it('can create a order', function () {

    \Illuminate\Support\Facades\Queue::fake();

    $data = [
        'products' => [
            [
                'id' => 1,
                'qty' => 1,
            ],
            [
                'id' => 2,
                'qty' => 2,
            ]
        ]
    ];

    $this->mock(\App\Actions\FindProducts::class, function (\Mockery\MockInterface $mock) use ($data) {
        $mock->shouldReceive('handle')->once()
            ->with($data)
            ->andReturn([
                'data' => [
                    [
                        'id' => 1,
                        'name' => 'Product 1',
                        'price' => 10.10,
                        'category' => [
                            'id' => 1,
                            'name' => 'Category 1',
                        ]
                    ],
                    [
                        'id' => 2,
                        'name' => 'Product 2',
                        'price' => 10.00,
                        'category' => [
                            'id' => 2,
                            'name' => 'Category 2',
                        ]
                    ]
                ]
            ]);
    });

    // 201 http created
    $this->postJson('/api/orders', $data, [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertStatus(201);

    \Illuminate\Support\Facades\Queue::assertPushedOn('payment_updates', \App\Jobs\OrderCreated::class);

});

it('can list orders', function () {
    $this->getJson(
        route('orders.index'),
        ['Authorization' => 'Bearer ' . $this->token]
    )->assertStatus(200);
});
