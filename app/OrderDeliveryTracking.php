<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDeliveryTracking extends Model
{
    protected $table = 'order_delivery_tracking';
    protected $primaryKey = 'odt_id';
    public $timestamps = false;
    protected $fillable = [
        'odv_id',
        'odt_lat' ,
        'odt_lng' ,
        'odt_datetime'
    ];
}
