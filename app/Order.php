<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'ord_id';
    public $timestamps = false;
    protected $appends = ['coupon','delivery','order_summary','total_price','isRating','order_status_desc', 'order_day_id', 'order_start_time', 'order_end_time'];
protected $hidden = ['coupon_id'];
    protected $fillable = [
        'ord_customer',
        'ord_number',
        'ord_status',
        'ord_createdAt',
        'ord_schdule_date',
        'ord_schedule_period_id',
        'ord_deliveryStartedAt',
        'ord_finishedAt',
        'ord_canceledAt',
        'ord_totalAmount',
        'ord_totalDiscount',
        'ord_netAmount',
        'ord_taxAmount',
        'ord_totalAfterTax',
        'adr_id'
    ];

    public function coupon()
    {
    //return $this->hasOne(Coupon::class, 'id');
       return $this->belongsTo(Coupon::class, 'coupon_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'ord_customer');
    }
    public function getTotalPriceAttribute()
    {
        $orderDetails = $this->orderDetails()->first();

        if(isset($orderDetails)) {
            $new_price = $orderDetails->odt_price;


            $new_price = round($new_price, 2);
        }
        else
            $new_price=1;
        return $new_price ;
    }
     public function getIsRatingAttribute ()
    {
       
       if($this->eval_flag==0)
           return false;
       else
           return true;

    }
    public function getOrderStatusDescAttribute ()
    {
       //if(isset($this->ord_status)!=0)
       //dd(getTranslation('20'.$this->ord_status,lang(),'lookup')->trn_text);
        return getTranslation('20'.$this->ord_status,lang(),'lookup')->trn_text;

    }
    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail', 'ord_id');
    }

    public function deliveries()
    {

        return $this->hasOne(OrderDelivery::class, 'ord_id');
        // return $this->belongsTo(Order::class , 'ord_id');
    }
    public function evaluation()
    {

        return $this->hasMany(OrderEvaluation::class, 'ord_id');
    }

    public function getDeliveryAttribute ()
    {
        $delevery=$this->deliveries()->first();
         if(isset($delevery))
            return $delevery->drivers;
        else
            return null;

    }

    public function getCouponAttribute()
    {
       $coupon=$this->coupon()->first();
      // dd($coupon);
       return $coupon;


    }
   /* public function getOrderSummaryAttribute ()
    {
//        $prd_id = $this->orderDetails()->pluck('prd_id');
        $cat = $this->Products()->pluck('cat_id')->unique();
        //$delevery=$this->deliveries()->first();
//dd($odt_id);
        $cats = Category::whereIn('cat_id',$cat)->get();
//        $prods = Product::whereIn('prd',$prd_id)->get();
//        return $prods;
//dd($cat);

        foreach ($cats as $c){
            $price = 0;
            $discount = 0;
            $orders = $this->Products()->where('cat_id',$c->cat_id)->get();
            foreach ($orders as $order){
                $price +=  $order->pivot->odt_price;
                $discount +=  $order->pivot->odt_discount;
                $order->odt_price =$order->pivot->odt_price;
                $order->odt_discount =$order->pivot->odt_discount;
                $order->odt_quantity =$order->pivot->odt_quantity;
                 $order->odt_no=$order->pivot->odt_id;
            $order->prd_image_path= getFullPathProduct().$order->prd_image;
                unset( $order->prd_gallery);
              //  $order->prd_gallery=['{}'];
            }
            $c->total_price = $price;
            $c->total_discount = $discount;
            $c->orders = $orders;
          //   $c->cat_image_path=url('storage/').$c->cat_image;
        }
        return $cats;
//        return $this->Products()->groupBy('cat_id')->get();
    }*/
 public function getOrderSummaryAttribute ()
    {
        if (request()->segment(1) != 'api')
            return null;
//        $prd_id = $this->orderDetails()->pluck('prd_id');
        $cat = $this->Products()->pluck('cat_id')->unique();
        //8,14
        //$delevery=$this->deliveries()->first();
//dd($odt_id);
        $cats = Category::whereIn('cat_id',$cat)->groupBy('cat_parent')->get();
      // dd(count($cats));
//        $prods = Product::whereIn('prd',$prd_id)->get();
//        return $prods;
//dd($cat);
       // $cats = $cats->groupBy('cat_parent');
        foreach ($cats as $c){
            $price = 0;
            $discount = 0;
          //  dd($c->cat_parent);
            $orders = $this->Products()
                ->join('categories', 'categories.cat_id', '=', 'products.cat_id')
               // ->where('products.cat_id',$c->cat_id)
                ->where('categories.cat_parent',$c->cat_parent)->get();

            foreach ($orders as $order){
               // dd($order->pivot);
              //  if($order->prd_id==5)
               // dd($order);
                $odt_quantity = $order->pivot->odt_quantity;
                $order->prd_price=$order->pivot->odt_price/$odt_quantity;
               // $order->prd_price=$order->pivot->odt_price/$order->pivot->odt_quantity;
                $price +=  $order->pivot->odt_price;
                $discount +=  $order->pivot->odt_discount;
                $order->odt_price =$order->pivot->odt_price;
                $order->odt_discount =$order->pivot->odt_discount;
                $order->odt_quantity =$order->pivot->odt_quantity;
                $order->prd_gallery=$order->prd_image;
                $order->prd_image_path= getFullPathProduct().$order->prd_image;
                $order->odt_no=$order->pivot->odt_id;
            }
            $c->total_price = $price;
            $c->total_discount = $discount;
            $c->orders= $orders;
          //  $c->cat_image_path='dfgdfdfggd';
        }
        return $cats;
//        return $this->Products()->groupBy('cat_id')->get();
    }
public function Products(){
        return $this->belongsToMany(Product::class,'order_details','ord_id','prd_id')->withPivot('odt_id','odt_price','odt_discount','odt_quantity');
}

    public function address()
    {
        return $this->belongsTo(Address::class, 'adr_id');
    }
    public function deliveySchedule()
    {
        return $this->belongsTo(DeliverySchedule::class,'ord_schedule_period_id','id');
    }
       public function getOrderDayIdAttribute()
    {
        $model = $this->deliveySchedule()->first();
        if (isset($model->day_id))
            return $model->day_id;
        return null;

    }
    public function getOrderStartTimeAttribute()
    {
        $model = $this->deliveySchedule()->first();
        if (isset($model->start_time))
            return $model->start_time;
        return null;

    }
    public function getOrderEndTimeAttribute()
    {
        $model = $this->deliveySchedule()->first();
        if (isset($model->end_time))
            return $model->end_time;
        return null;

    }
}