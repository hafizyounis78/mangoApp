<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryDay extends Model
{
    public function schedule()
    {
        return $this->hasMany(DeliverySchedule::class,'day_id','id');
    }
}
