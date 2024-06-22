<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    protected $table = 'orders_deliveries';
    protected $primaryKey = 'odv_id';
    public $timestamps = false;
    protected $appends = ['drivers'];
    protected $hidden = array('odv_driver','odv_id','ord_id');
    protected  $fillable = [
        'ord_id' ,
        'odv_driver' ,
    ];

    public function order() {

        return $this->hasOne(Order::class, 'ord_id');
       // return $this->belongsTo(Order::class , 'ord_id');
    }
    public function user() {
        return $this->belongsTo(User::class , 'odv_driver');
    }
   /* public function getDriversAttribute()
    {
        $user =$this->user()->select('name','image','mobile')->first();
        return $user;
    }*/
    public function getDriversAttribute()
    {
        $user =$this->user()->select('name','image','mobile')->first();
        if(isset($user->image))
            $user->image_path= preg_filter('/^/', getFullPathUser(), $user->image);
        return $user;
    }
}
