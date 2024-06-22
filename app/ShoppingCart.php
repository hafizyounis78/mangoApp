<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    //

    protected $appends = ['total_price', 'total_price_with_discount','cat_name','cat_id'];//, 'variations'];
    //protected $appends = ['prd_price','total_price','prd_discount', 'total_price_with_discount', 'prd_unit','estimated_price','cat_name'];//, 'variations'];
    protected $hidden = array('created_at', 'updated_at');//,'prd_attribute');
    public function Product()
    {
        return $this->belongsTo(Product::class, 'prd_id');
    }

    public function variations()
    {
        return $this->belongsTo(ProductVariation::class, 'pvr_id');
    }

    public function getVariationsAttribute()
    {
        return $this->variations()->first();
    }


    public function getPrdUnitAttribute()
    {
        $prd = $this->product()->first();
        return getTranslation($prd->prd_unit , lang() , lookup_trans_type())->trn_text;
    }
    public function getPrdNameAttribute()
    {
        $prd = $this->product()->first();
//        ptr_name
//    dd($prd->translations);
        return getProductTranslation($prd->prd_id)->ptr_name;
    }
    public function getCatNameAttribute()
    {
        $prd = $this->product()->first();
//        ptr_name
//    dd();
        $cat = Category::find($prd->cat_id);
        return getTranslation($cat->cat_parent , lang() , category_trans_type())->trn_text;
    }

    public function getCatIdAttribute()
    {
        $prd = $this->product()->first();
        return $prd->cat_id;
    }

    public function getEstimatedPriceAttribute()
    {
        $prd = $this->product()->first();
//        ptr_name
//    dd();
    return ($prd->prd_unitValue."".$this->prd_unit)." * ".($this->prd_price."/".$this->prd_unit)." = ". ($prd->prd_unitValue * $this->prd_price);

    }

    public function getTotalPriceAttribute()
    {
        $product = $this->Product()->first();
        $variation = $this->variations()->first();
        $new_price = $product->prd_price;
        if (isset($variation)) {
            $new_price = $variation->pvr_price;
        }
        $new_price = round($new_price, 2);

        return $new_price * $this->quantity;
    }

    public function getPrdPriceAttribute()
    {
        $product = $this->Product()->first();
        $variation = $this->variations()->first();
        $new_price = $product->prd_price;
        if (isset($variation)) {
            $new_price = $variation->pvr_price;
        }
        $new_price = round($new_price, 2);

        return $new_price;
    }

    public function getTotalPriceWithDiscountAttribute()
    {
        $product = $this->Product()->first();
        $variation = $this->variations()->first();
        $new_price = $product->prd_price;
        if (isset($variation)) {
            $new_price = $variation->pvr_price;
            /*if ($variation->pvr_isDiscount == 1){
                $new_price -= ($variation->pvr_price * $variation->pvr_discount)/100.0;
            }*/
        }
//ofr_discount
        $offer = $product->offers;
        if (isset($offer)) {
            $new_price -= ($new_price * $offer) / 100.0;
        }
//dd($new_price);
        $new_price = round($new_price, 2);

        return $new_price * $this->quantity;
    }

    public function getPrdDiscountAttribute()
    {
        $product = $this->Product()->first();
        $offer = $product->offers->first();
        if (isset($offer))
        return $offer->ofr_discount;
        return 0;
    }


}
