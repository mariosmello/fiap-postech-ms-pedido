<?php

test('can find products mock', function () {

    \Illuminate\Support\Facades\Http::fake([
       '*' => \Illuminate\Support\Facades\Http::response([]),
    ]);

    $findProducts = new \App\Actions\FindProducts();
    $response = $findProducts->handle([
        'products' => [
            'id' => 1
        ]
    ]);

    expect($response)->toBeArray();
});
