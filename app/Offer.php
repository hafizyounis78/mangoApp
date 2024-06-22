<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offers';
    protected $primaryKey = 'ofr_id';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prd_id',
        'ofr_discount',
        'ofr_start',
        'ofr_end',
    ];
    public function products() {
        return $this->hasOne('App\Product' , 'prd_id' );
    }

    protected $hidden = ['ofr_creation_datetime'];
}