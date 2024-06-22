<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translations';
    protected $fillable = ['trn_foreignKey' , 'lng_id' , 'trn_type' , 'trn_text' , 'trn_desc'];
    protected $primaryKey = 'trn_id';
}
