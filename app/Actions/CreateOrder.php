<?php

namespace App\Actions;

use App\Jobs\OrderCreated;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CreateOrder
{

    protected $createInvoice;

    public function __construct(CreateInvoice $createInvoice)
    {
        $this->createInvoice = $createInvoice;
    }

    public function handle(Request $request, array $products) :Order
    {
        $order = new \App\Models\Order();
        $order->status = 'pending';
        $order->payment_status = 'pending';
        $order->code = Str::password(4, false, true, false, false);
        $order->save();

        $customer = new \App\Models\Customer();
        $customer->id = $request->get('auth')['sub'];
        $customer->name = $request->get('auth')['name'];
        $customer->email = $request->get('auth')['email'];
        $order->customer()->save($customer);

        $total = 0;
        foreach ($products['data'] as $item) {
            $product = new \App\Models\Product();
            $product->id = $item['id'];
            $product->name = $item['name'];
            $product->price = $item['price'];
            $product->category = $item['category'];

            $filteredArray = array_filter($request->get('products'), function ($item) use ($product) {
                return $item['id'] === $product->id;
            });
            $product->qty = count($filteredArray) > 0 ? array_values($filteredArray)[0]['qty'] : null;
            $product->total_price = $item['price'] * $product->qty;
            $order->products()->attach($product);
            $total += $product->total_price;
        }

        $order->total = $total;
        $order->save();

        OrderCreated::dispatch([
            'order' => $order->code,
            'total' => $total,
            'customer_id' => $request->get('auth')['sub'],
            'customer_name' => $request->get('auth')['name'],
        ])->onQueue('payment_updates')
            ->delay(5);

        return $order;
    }

}
