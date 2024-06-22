<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliverySchedule extends Model
{
    protected $appends = ['schedule_day_ar','schedule_day_en'];
       protected $fillable=['day_id','start_time','end_time','isActive'];
    public function days()
    {
        return $this->belongsTo(DeliveryDay::class,'day_id','id');
    }
    public function getScheduleDayEnAttribute ()
    {
        $model=$this->days()->first();
        return $model->day_desc_en;

    }
    public function getScheduleDayArAttribute ()
    {
        $model=$this->days()->first();
        return $model->day_desc_ar;

    }
}
