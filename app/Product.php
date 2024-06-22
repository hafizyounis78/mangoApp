<?php
namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model{
	
    protected $table = 'products';
	protected $primaryKey = 'prd_id';
	public $timestamps = false;
    //protected $appends = [];
    //protected $appends = ['translations','offers','category'];
    protected $appends = ['cat_parent','category','ptr_name','offers'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    fullPathProduct
    protected $fillable = [
		'prd_image',
		'prd_price',
		'prd_isVariable',
		'cat_id',
		'prd_gallery',
	];

    public function getCategoryAttribute()
    {
        $cat =$this->category()->first();
        $cat->cat_name = getTranslation($cat->cat_id , lang() , category_trans_type())->trn_text;
        if($cat->cat_parent!=0)
            $cat->cat_parent_name = getTranslation($cat->cat_parent , lang() , category_trans_type())->trn_text;
        else
            $cat->cat_parent_name ='';
        return $cat;
    }
    public function getPtrNameAttribute()
    {
        return getProductTranslation($this->prd_id)->ptr_name;
    }
    protected function getPrdImageAttribute($value)
    {
        return $value;
      //  return getFullPathProduct().$value;
    }

    protected function getPrdPriceAttribute($prd_price)
    {
        $variation = $this->variations()->where('pvr_id',$this->pvr_id)->first();
        $new_price = $prd_price;
        if (isset($variation)) {
            $new_price = $variation->pvr_price;
        }
        return $new_price;
    }




    public function getCatParentAttribute()
    {
        $cat = $this->category()->first();
        return $cat->cat_parent;
    }
    public function getOffersAttribute()
    {
        $now = Carbon::now();
        $offers=$this->offers()->select('ofr_discount')->whereDate('ofr_start','<=',$now)
            ->whereDate('ofr_end','>=',$now)->orderByDesc('ofr_creation_datetime')->first();
         if(isset($offers))
            return $offers['ofr_discount'];
        else
            return 0;
    }
    public function getAttributesAttribute()
    {
       return $this->attributes()->get();
    }
    public function getTranslationsAttribute()
    {

        return $this->translations()->get();
    }

    public function offers(){
        return $this->hasMany(Offer::class, 'prd_id', 'prd_id')->where('ofr_isDeleted',0);
    }
	public function translations(){
		return $this->hasMany('App\ProductTranslation', 'prd_id', 'prd_id');
	}
    public function variations(){
        return $this->hasMany(ProductVariation::class, 'prd_id', 'prd_id');
    }
    public function shoppingCarts(){
        return $this->hasMany(ShoppingCart::class, 'prd_id', 'prd_id');
    }
    public function attributes(){
        return $this->hasMany(ProductAttribute::class, 'prd_id', 'prd_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'cat_id');
    }
public function getPrdGalleryAttribute($value)
    {
         return $value;
            
    }
    public function orderDetails()
    {
        //  return $this->belongsTo(Product::class, 'prd_id');
        return $this->belongsTo(OrderDetail::class, 'prd_id');
       // return $this->hasMany(Product::class, 'prd_id');
    }
	public static function validationRules(){
		
	}
}