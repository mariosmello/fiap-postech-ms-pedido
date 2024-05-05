<?php

namespace App\Http\Controllers;

use App\Actions\CreateInvoice;
use App\Actions\CreateOrder;
use App\Actions\FindProducts;
use App\Http\Requests\CreateOrderRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(
        CreateOrderRequest $request, FindProducts $findProducts, CreateOrder $createOrder)
    {
        $products = $findProducts->handle($request->safe()->only('products'));
        $order = $createOrder->handle($request, $products);
        return response()->json($order, 201);
    }
}
