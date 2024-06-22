<?php

namespace App\Http\Controllers\api;

use App\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class CouponController extends Controller
{
   /*  public function checkCounponValidity(Request $request)
    {

        $couponNo= $request->coupon_no;
        $coupon=Coupon::select('*')
          ->whereDate('coupon_start', '<=',Carbon::now()->format('Y-m-d'))
          ->whereDate('coupon_end', '>=',Carbon::now()->format('Y-m-d'))
            ->where('used', '=',0)

           ->where('coupon_no', '=',$couponNo)->first();

        if (isset($coupon) && $coupon->count()!=0){
//

           return $this->responseJson(true, trans('successCoupon'),['isValid'=>true,'discount'=>$coupon->value,'id'=>$coupon->coupon_no]);
       //     return $this->responseJson(true, trans('successCoupon'),['isValid'=>true,'coupon'=>$coupon]);
        }
        else
        return $this->responseJson(false, trans('errorCoupon'),['isValid'=>false,'discount'=>'','id'=>$request->coupon_no]);
           // return $this->responseJson(false, trans('errorCoupon'),['coupon_no'=>$request->coupon_no] );


    }*/
     public function checkCounponValidity(Request $request)
    {

        $couponNo= $request->coupon_no;
        $coupon=Coupon::select('*')
            ->where('coupon_isDeleted','!=',1)
          ->whereDate('coupon_start', '<=',Carbon::now()->format('Y-m-d'))
          ->whereDate('coupon_end', '>=',Carbon::now()->format('Y-m-d'))
       //     ->where('used', '=',0)

           ->where('coupon_no', '=',$couponNo)->first();


        if (isset($coupon) && $coupon->count()!=0 && $coupon->used==0 ){
//

            //return $this->responseJson(true, trans('successCoupon'),['isValid'=>true,'id'=>$coupon->id,'coupon_no'=>$coupon->coupon_no,'coupon_end'=>$coupon->coupon_end,'value'=>$coupon->value,'used'=>$coupon->used]);
            //return $this->responseJson(true, trans('successCoupon'),['isValid'=>true,'discount'=>$coupon->value,'id'=>$coupon->id]);
            return $this->responseJson(true, trans('successCoupon'),['id'=>$coupon->id,'coupon_no'=>$coupon->coupon_no,'coupon_end'=>$coupon->coupon_end,'value'=>$coupon->value,'used'=>$coupon->used]);
        }
       elseif (isset($coupon) && $coupon->count()!=0 && $coupon->used==1 ) {
           return $this->responseJson(false, trans('errorCoupon'),['id'=>$coupon->id,'coupon_no'=>$coupon->coupon_no,'coupon_end'=>$coupon->coupon_end,'value'=>$coupon->value,'used'=>$coupon->used]);
       }

        else
            return $this->responseJson(false, trans('errorCoupon'),[]);
            //return $this->responseJson(false, trans('errorCoupon'),['coupon_no'=>$request->coupon_no] );




    }
}
