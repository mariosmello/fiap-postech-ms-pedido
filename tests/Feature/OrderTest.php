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

    $this->mock(\App\Actions\CreateInvoice::class, function (\Mockery\MockInterface $mock)  {
        $mock->shouldReceive('handle')->once()
            ->andReturn( [
                "status" => "pending",
                "pix" => [
                    "qrcode_url" => "https://upload.wikimedia.org/wikipedia/commons/4/41/QR_Code_Example.svg",
                    "code" => "00020126330014BR.GOV.BCB.PIX0111343609248795204000053039865406100.005802BR5911Mario Mello6011Santo Andre62060502id6304D612"
                ],
                "total" => 14.69,
                "updated_at" => "2024-05-12T15:43:50.201000Z",
                "created_at" => "2024-05-12T15:43:50.201000Z",
                "_id" => "6640e3b612e399046f03b5c5",
                "customer" => [
                    "id" => "1",
                    "name" => "Unidentified User",
                    "updated_at" => "2024-05-12T15:43:50.206000Z",
                    "created_at" => "2024-05-12T15:43:50.206000Z",
                    "_id" => "6640e3b612e399046f03b5c6"
                ],
                "order" => [
                    "id" => "1234",
                    "updated_at" => "2024-05-12T15:43:50.209000Z",
                    "created_at" => "2024-05-12T15:43:50.209000Z",
                    "_id" => "6640e3b612e399046f03b5c7"
                ]
            ]);
    });

    // 201 http created
    $this->postJson('/api/orders', $data, [
        'Authorization' => 'Bearer ' . $this->token
    ])->assertStatus(201);

});
