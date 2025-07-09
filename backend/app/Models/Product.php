<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'stock',
        'image',
        'discount',
        'rating',
        'status'
    ];

    public function orders()
    {
        return $this->belongsToMany(OrderItem::class)->withPivot('quantity', 'price')->withTimestamps();
    }
    public function carts()
    {
        return $this->belongsToMany(CartItem::class)->withPivot('quantity');
    }
}
