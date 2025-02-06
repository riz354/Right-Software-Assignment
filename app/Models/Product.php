<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
    ];

    public function images(){
        return $this->hasMany(ProductImage::class,'product_id');
    }

    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
    public function comments(){
        return $this->hasMany(ProductComment::class,'product_id');
    }
}
