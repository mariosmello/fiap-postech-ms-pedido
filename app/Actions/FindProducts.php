<?php

namespace App\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class FindProducts
{
    public function handle(array $products) :array
    {
        $products = Arr::pluck($products, '*.id');

        $response = Http::withHeader('Authorization', 'Bearer ' . request()->bearerToken())
            ->withQueryParameters(['id' => $products[0]])
            ->get(env('MS_CATALOGO_URL') . '/api/products/search');

        return $response->json();
    }

}
