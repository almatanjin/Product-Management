<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2', // Cast price to decimal with 2 decimal places
    ];
}
