<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "categories";
    protected $primaryKey = 'cat_id';
    protected $fillable = ["cat_image" ];
    protected $appends = ['parent_cat_name','cat_img'];


    public function getParentCatNameAttribute()
    {
       if(isset($this->cat_parent ) and $this->cat_parent!=0 )
        return getTranslation($this->cat_parent , lang() , category_trans_type())->trn_text;
        else
         return null;
    }

    public function getCatImgAttribute()
    {
        return  url('storage/').$this->cat_image;
         // return $value;
    }
//    public function getParentCatNameAttribute()
//    {
//        return $this->prodcut()->where('cat_id','=','cat_id')->get();
//    }

    public function prodcut()
    {
        return  $this->hasMany(Product::class,'cat_id','cat_id');

    }
}
