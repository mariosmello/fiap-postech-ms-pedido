<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsMany;
use MongoDB\Laravel\Relations\EmbedsOne;

class Order extends Model
{
    use HasFactory;

    protected $collection = 'orders';

    protected $fillable = ['id', 'products', 'customer', 'status', 'payment_status'];

    public function products(): EmbedsMany
    {
        return $this->embedsMany(Product::class);
    }

    public function customer(): EmbedsOne
    {
        return $this->embedsOne(Customer::class);
    }

}
