<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    public function products(){
        return $this->belongsTo(Product::class,'id','category_id');
    }
}
