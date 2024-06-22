<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'odt_id';
    public $timestamps = false;
    protected $appends = ['cat_parent','orders','cat_name'];
    protected  $fillable = [
        'ord_id' ,
        'prd_id' ,
        'pvr_id' ,
        'odt_quantity',
        'odt_price' ,
        'odt_discount' ,
    ];

    public function order() {
        return $this->belongsTo('App\Order' , 'ord_id');
    }
    public function Product()
    {
        //  return $this->belongsTo(Product::class, 'prd_id');

        return $this->hasMany(Product::class, 'prd_id','prd_id');

    }
    public function getCatParentAttribute()
    {
        $cat =$this->Product()->select('cat_id')->first();

     //   $category->cat_name = getTranslation($category->cat_id , lang() , category_trans_type())->trn_text;
        //$category->cat_parent_name = getTranslation($category->cat_parent , lang() , category_trans_type())->trn_text;

//       $cat =  Category::find( $cat_id->cat_parent);

//        $cat->cat_name = getTranslation($cat->cat_id , lang() , category_trans_type())->trn_text;
        $cat->cat_parent_name = getTranslation($cat->cat_parent , lang() , category_trans_type())->trn_text;
        return $cat->cat_parent_name;

    }
    public function getCatNameAttribute()
    {
        $prd = $this->product()->first();
        $cat = Category::find($prd->cat_id);
         return getTranslation($cat->cat_parent, lang(), category_trans_type())->trn_text;
    }
    public function getOrdersAttribute()
    {

        $prd=$this->Product()->get();
        $prd->groupBy('cat_parent');
       // dd($prd);
       // $cat = Category::find($prd->cat_id)->groupBy('cat_parent');
        //   $category->cat_name = getTranslation($category->cat_id , lang() , category_trans_type())->trn_text;
        //$category->cat_parent_name = getTranslation($category->cat_parent , lang() , category_trans_type())->trn_text;

        return  $prd;
    }


}
