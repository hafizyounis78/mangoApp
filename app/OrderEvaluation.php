<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderEvaluation extends Model
{
    protected $table = 'order_evaluation';
    protected $primaryKey = 'eval_id';

    public function order()
    {
        return $this->belongsTo(Order::class, 'ord_id', 'eval_id');
    }


}
