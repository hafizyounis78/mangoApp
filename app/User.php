<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use OwenIt\Auditing\Contracts\Auditable;
use DB;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Notifiable;
    use EntrustUserTrait;
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','mobile','isAdmin', 'user_pass', 'type', 'isVerified','idSocialMedia','typeSocialMedia' , 'fcmToken' , 'deviceType', 'isActive' , 'image' ,'lat' , 'lng'
    ];
    protected $appends = ['address'];
    protected $dates = ['verficationExpire' , 'tokenExpire'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_pass', 'remember_token'
    ];

    public function facebookSocialAccounts()
    {
        return $this->hasMany(FacebookSocialAccount::class,'user_id','id');
    }
    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    public function getAddressAttribute()
    {
        $ads = $this->addresses()->where('adr_isDefault','=',1)->first();
        return $ads;
    }
    public function addresses() {
        return $this->hasMany(Address::class , 'user_id');
    }

    public function orders() {
        return $this->hasMany('App\Order' , 'ord_customer');
    }
     public function roles()
    {
        return $this->belongsToMany('App\Role');
    }


    public function generateToken()
    {
        $this->api_token = str_random(150);
        $this->save();

        return $this->api_token;
    }
    public function deleteToken() {
        $this->api_token = null;
        $this->save();
    }

    public function generateResetToken() {
        return str_random(150);
    }

	public function getCityName(){
		if($this->city > 0){
		//	$this->city_id = $this->city;
			$city = DB::table('translations')->select('trn_text')->where([
				['trn_foreignKey', '=', $this->city],
				['lng_id', '=', lang()],
				['trn_type', '=', 'city'],
			])->first();
			
			return $city->trn_text;
		}
		
		return '';
	}


}
