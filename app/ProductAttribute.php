<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table='product_attributes';
    protected $primaryKey='pat_id';
    public function shoppingCart()
    {
        return $this->belongsTo(ShoppingCart::class,'prd_id');
    }
}
