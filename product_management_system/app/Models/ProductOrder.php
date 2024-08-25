<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\Order;
Use App\Models\Product;

class ProductOrder extends Model
{
    use HasFactory;


    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

   
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
