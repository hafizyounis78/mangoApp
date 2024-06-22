<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $primaryKey = 'atr_id';
    protected $fillable = [
        'atr_isSizeAttribute'
    ];
}
