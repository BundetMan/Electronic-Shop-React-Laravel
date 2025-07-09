<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'url']; // Fillable attributes for mass assignment
    protected $table = 'product_images'; // Specify the table name if it differs from the

    public function product(){
        return $this->belongsTo(product::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
