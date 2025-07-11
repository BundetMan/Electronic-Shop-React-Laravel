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
        'discount',
        'rating',
        'status'
    ];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'discount' => 'float',
        'rating' => 'float',
        'status' => 'string'
    ];

    public function orders()
    {
        return $this->belongsToMany(OrderItem::class)->withPivot('quantity', 'price')->withTimestamps();
    }
    public function carts()
    {
        return $this->belongsToMany(CartItem::class)->withPivot('quantity');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
