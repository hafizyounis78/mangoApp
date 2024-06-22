<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'cit_id';
    public $timestamps = false;

    protected $fillable = [
        'cit_isActive'
    ];
    public function address()
    {
        return $this->hasMany(Address::class,'adr_city','cit_id');

    }
}
