<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'adr_id';
    protected $appends = ['city_name'];
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'adr_firstName',
        'adr_lastName',
        'adr_city',
        'adr_address',
        'adr_mobile',
        'lat' ,
        'lng'
    ];

    protected $hidden = ['created_at', 'adr_isDeleted'];

    public function user() {
        return $this->belongsTo('App\User' , 'user_id');
    }
      public function getCityNameAttribute()
    {
        $city=$this->cities()->first();
           return getTranslation( $city->cit_id, lang(), 'city')->trn_text;
       // return $this->getCityName($city->cit_id);
    }/*
    public function getCityName(){
        
       /* if($this->city > 0){
            $this->city_id = $this->city;
            $city = DB::table('translations')->select('trn_text')->where([
                ['trn_foreignKey', '=', $this->city],
                ['lng_id', '=', lang()],
                ['trn_type', '=', 'city'],
            ])->first();

            return $city->trn_text;
        }

        return '';
    }*/
    public function cities()
    {
        return $this->belongsTo(City::class,'adr_city');

    }
    
}
