<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'url']; // Fillable attributes for mass assignment
    protected $table = 'product_images'; // Specify the table name if it differs from the

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getUrlAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
        // It's a full URL already, return as is
        return $value;
        }
        // Assuming the URL is stored as a relative path, you can prepend the base URL
        return asset('storage/' . ltrim($value, '/'));
    }
}
