<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Offer;
use App\Product;
use App\Translation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class CityController extends Controller{
	
	public function citiesList(){
		$name = getRawLookupSelect('cities', 'cit_id', city_trans_type(), 'name');
		$cities = DB::table('cities')->select(DB::raw('cities.cit_id as id'), $name)->where('cit_isActive', '=', 1)->get();
		return $this->responseJson(true, 'success', $cities->toArray());
	}
}