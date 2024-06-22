<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\AppSetting;
use App\Category;
use App\Coupon;
use App\FollowingSeller;
use App\Instruction;
use App\DeliveryDay;
use App\Mail\ResetPassword;
use App\Offer;
use App\Order;
use App\OrderDelivery;
use App\OrderDeliveryTracking;
use App\OrderDetail;
use App\OrderEvaluation;
use App\SystemNotifications;
use App\Product;
use App\ProductVariation;
use App\ShoppingCart;
use App\Translation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Validator;
class OrderController extends Controller
{

public function addOrder(Request $request)
    {
        //*******************Edit Quantity Shopping Cart By Json

        /*$data = $request->get('data');
        foreach ($data as $value){
            $cartRec = ShoppingCart::find($value["id"]);
            $cartRec->quantity = $value["quantity"];
            $cartRec->save();

        }*/
        //************** by Array Items
       $itr=count($request->shopping_cart_id);

        for($i = 0;$i<$itr;$i++) {

           // dd ($request->shopping_cart_id[$i]);
            $cartRec = ShoppingCart::find($request->shopping_cart_id[$i]);
            if(isset($cartRec))
            {
                $cartRec->quantity = $request->quantity[$i];
                $cartRec->save();
            }
            else
               return $this->responseJson(false, 'errorShopping', $data);
            
        }

        //*******************end edite quantity shopping cart

        $shoppingCarts = ShoppingCart::with('Product')->whereIn('id',$request->get('shopping_cart_id'))->get();

        $total_price_shopping = 0.0;
        $total_price_shopping_with_discount = 0.0;
        foreach ($shoppingCarts as $cart) {

            $cart->product->prd_attribute=$cart->prd_attribute;
            unset($cart->prd_attribute);
            if ($cart->product->prd_image) {

                $arr = $cart->product->prd_image;
                $cart->product->prd_image = preg_filter('/^/', getFullPathProduct(), $arr);
            }
            $prd_trn = getProductTranslation($cart->product->prd_id);
            $cart->product->prd_name = $prd_trn->ptr_name;
//******
            $arr='';
            unset($cart->product->prd_gallery);
            /*  if ($cart->product->prd_gallery) {
                  $arr = $cart->product->prd_gallery;
                  $arr = array_prepend($arr, $cart->product->prd_image);
                  $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
              } else {
                  $arr = [$cart->product->prd_image];
                  $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
              }*/
            //*****offfers
            $now = Carbon::now();
            $offersvalue=Offer::select('ofr_discount')
                ->where('prd_id','=',$cart->product->prd_id)
                ->whereDate('ofr_start','<=',$now)
                ->whereDate('ofr_end','>=',$now)
                ->orderByDesc('ofr_creation_datetime')->first();
//dd($offersvalue);
            //***********
            $cart->cat_id=$cart->product->cat_id;
            if(isset($offersvalue['ofr_discount']))
                $cart->product->ofr_discount=$offersvalue['ofr_discount'];
            else
                $cart->product->ofr_discount=0;

            $cart->product->pvr_id=$cart->pvr_id;
///  //********
//dd($cart->quantity);
            $cart->product->quantity=$cart->quantity;
            $cart->cat_name = getTranslation($cart->product->cat_id , lang() , category_trans_type())->trn_text;
            //dd($cart->product->prd_unit);
            // $cart->prd_unit = getTranslation($cart->product->prd_unit , lang() , lookup_trans_type())->trn_text;

            ///***************//
            $total_price_shopping_with_discount += $cart->total_price_with_discount;
            $total_price_shopping += $cart->total_price;

        }
        if ($total_price_shopping_with_discount != 0)
            $total_price_shopping_with_general_discount = $total_price_shopping_with_discount;
        else
            $total_price_shopping_with_general_discount = $total_price_shopping;
//        $total_price_shopping_with_general_discount = $total_price_shopping;
// general_discount
        $general_discount = AppSetting::find(2);
        if (isset($general_discount)){
            $total_price_shopping_with_general_discount -=($total_price_shopping_with_discount * doubleval($general_discount->value))/100.0;
        }
        $now = Carbon::now();
//dd($request->coupon_id);
        if(isset($request->coupon_id) && $request->coupon_id !=null)
        {
            $coupon = Coupon::find($request->coupon_id)
                ->where('used','0')
                ->whereDate('coupon_start','<=',$now )
                ->whereDate('coupon_end','>=',$now )->first();
        }
        //dd($coupon->count());
        $total_price_shopping_with_coupon = $total_price_shopping_with_general_discount;
        if (isset($coupon)){
            $total_price_shopping_with_coupon -=($total_price_shopping_with_general_discount * doubleval($coupon->value))/100.0;
            $total_price_shopping_with_tax = $total_price_shopping_with_coupon;
        }
        else
        {
            $total_price_shopping_with_coupon=0;
            $total_price_shopping_with_tax = $total_price_shopping_with_general_discount;
        }


        $general_tax = AppSetting::find(1);
        if (isset($general_tax)){
            $total_price_shopping_with_tax +=($total_price_shopping_with_tax * doubleval($general_tax->value))/100.0;
        }


        $total_price_shopping = round($total_price_shopping,2);
        $total_price_shopping_with_discount = round($total_price_shopping_with_discount,2);
        $total_price_shopping_with_tax = round($total_price_shopping_with_tax,2);
        $another_data = [
            'general_discount'=>$general_discount->value,
            'general_tax'=>$general_tax->value,
            'total_price_shopping' => $total_price_shopping,
            'total_price_shopping_with_discount' => $total_price_shopping_with_discount,
            'total_price_shopping_with_general_discount' => $total_price_shopping_with_general_discount,
            'total_price_shopping_with_coupon' => $total_price_shopping_with_coupon,
            'total_price_shopping_with_tax' => $total_price_shopping_with_tax
        ];


        $get_order_user = $this->getOrderUser(auth()->user());

        $ord_customer = auth()->user()->id;
        $ord_number = $ord_customer . "" . $get_order_user;
        $ord_taxAmount = AppSetting::find(1);
        $ord_status = 1;
        $adr_id = $request->adr_id;
        $note= $request->note;
        $ord_createdAt = date('Y-m-d H:i:s');
        $order = new Order();
        $order->ord_customer = \auth()->user()->id;
        $order->ord_number = $ord_number;
        $order->ord_status = $ord_status;
        $order->ord_createdAt = $ord_createdAt;
        $order->ord_deliveryStartedAt = null;
        $order->ord_finishedAt = null;
        $order->ord_canceledAt = null;
        $order->ord_totalAmount = $total_price_shopping ;
        $order->ord_totalDiscount = $total_price_shopping_with_discount;
        $order->ord_netAmount = $total_price_shopping_with_discount;
        $order->gr_net_discount = $total_price_shopping_with_general_discount;
        $order->coupon_net_discount = $total_price_shopping_with_coupon;
        $order->ord_taxAmount = $ord_taxAmount->value;
        $order->ord_totalAfterTax = $total_price_shopping_with_tax;
        $order->adr_id = $adr_id;
        $order->note = $note;

        if ($order->save()) {

//        `ord_id`, `prd_id`, `pvr_id`, `odt_quantity`, `odt_price`, `odt_discount`
//"customer_id": 8,
//            "prd_id": 4,
//            "pvr_id": 2,
//            "quantity": 2,

            foreach ($shoppingCarts as $shoppingCart) {
                $order_det = new OrderDetail();
                $order_det->ord_id = $order->ord_id;
                $order_det->prd_id = $shoppingCart->prd_id;
                $order_det->pvr_id = $shoppingCart->pvr_id;
                $order_det->odt_quantity = $shoppingCart->quantity;
                $order_det->odt_price = $shoppingCart->total_price;
                $order_det->odt_discount = $shoppingCart->total_price_with_discount;
                $order_det->save();
                $shoppingCart->delete();
            }
            if($request->has('coupon_id')) {
                $couponrec = Coupon::where('coupon_id',$request->get('coupon_id'));
                if(isset($couponrec)) {
                    $couponrec->used = 1;
                    $couponrec->save();
                }
            }
          //  return response_api(true, $shoppingCarts, null, null, $another_data);
            return response_api(true,['items'=> $shoppingCarts,'Totals'=>$another_data], null, null, null);
        }
        return response_api(false);
    }
public function addOrderRaw(Request $request)
    {
        //*******************Edit Quantity Shopping Cart By Json
//dd($request->all());
        $data = $request->get('data');
//        dd($data);
        foreach ($data as $value){
            $cartRec = ShoppingCart::find($value["id"]);
            if(isset($cartRec))
           {
               $cartRec->quantity = $value["quantity"];
               $cartRec->save();
           }
           else
               return $this->responseJson(false, 'errorShopping', $data);

        }

//        dd(1);
        //************** by Array Items
/*        $itr=count($request->shopping_cart_id);

        for($i = 0;$i<$itr;$i++) {

            // dd ($request->shopping_cart_id[$i]);
            $cartRec = ShoppingCart::find($request->shopping_cart_id[$i]);
            if(isset($cartRec));
            {
                $cartRec->quantity = $request->quantity[$i];
                $cartRec->save();
            }
        }
        */

        //*******************end edite quantity shopping cart
        $shoppingCarts = [];

foreach ($data as $value)
{
    $shoppingCart = ShoppingCart::with('Product')->find($value['id']);
    $shoppingCarts[] = $shoppingCart;
}

//dd($shoppingCarts);
        $total_price_shopping = 0.0;
        $total_price_shopping_with_discount = 0.0;
        foreach ($shoppingCarts as $cart) {

            $cart->product->prd_attribute=$cart->prd_attribute;
            unset($cart->prd_attribute);
            if ($cart->product->prd_image) {

                $arr = $cart->product->prd_image;
                $cart->product->prd_image = preg_filter('/^/', getFullPathProduct(), $arr);
            }
            $prd_trn = getProductTranslation($cart->product->prd_id);
            $cart->product->prd_name = $prd_trn->ptr_name;
//******
            $arr='';
            unset($cart->product->prd_gallery);
            /*  if ($cart->product->prd_gallery) {
                  $arr = $cart->product->prd_gallery;
                  $arr = array_prepend($arr, $cart->product->prd_image);
                  $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
              } else {
                  $arr = [$cart->product->prd_image];
                  $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
              }*/



            //*****offfers
            $now = Carbon::now();
            $offersvalue=Offer::select('ofr_discount')
                ->where('prd_id','=',$cart->product->prd_id)
                ->whereDate('ofr_start','<=',$now)
                ->whereDate('ofr_end','>=',$now)
                ->orderByDesc('ofr_creation_datetime')->first();
//dd($offersvalue);
            //***********
            $cart->cat_id=$cart->product->cat_id;
            if(isset($offersvalue['ofr_discount']))
                $cart->product->ofr_discount=$offersvalue['ofr_discount'];
            else
                $cart->product->ofr_discount=0;

            $cart->product->pvr_id=$cart->pvr_id;
///  //********
//dd($cart->quantity);
            $cart->product->quantity=$cart->quantity;
            $cart->cat_name = getTranslation($cart->product->cat_id , lang() , category_trans_type())->trn_text;
            //dd($cart->product->prd_unit);
            // $cart->prd_unit = getTranslation($cart->product->prd_unit , lang() , lookup_trans_type())->trn_text;

            ///***************//
            $total_price_shopping_with_discount += $cart->total_price_with_discount;
            $total_price_shopping += $cart->total_price;

        }


        if ($total_price_shopping_with_discount != 0)
            $total_price_shopping_with_general_discount = $total_price_shopping_with_discount;
        else
            $total_price_shopping_with_general_discount = $total_price_shopping;
//        $total_price_shopping_with_general_discount = $total_price_shopping;
// general_discount
        $general_discount = AppSetting::find(2);
        if (isset($general_discount)){
            $total_price_shopping_with_general_discount -=($total_price_shopping_with_discount * doubleval($general_discount->value))/100.0;
        }
        $now = Carbon::now();
//dd($request->coupon_id);
        $coupon_id='';
        if(isset($request->coupon_id) && $request->coupon_id !=null)
        {
           
            $coupon = Coupon::where('used','0')
                ->where('coupon_isDeleted','!=',1)
                ->whereDate('coupon_start','<=',$now )
                ->whereDate('coupon_end','>=',$now )->where('id',$request->coupon_id)->first();
                if(isset($coupon))
                $coupon_id =$coupon->id;
        }


        //dd($coupon->count());
        $total_price_shopping_with_coupon = $total_price_shopping_with_general_discount;
        if (isset($coupon)){
            $total_price_shopping_with_coupon -=($total_price_shopping_with_general_discount * doubleval($coupon->value))/100.0;
            $total_price_shopping_with_tax = $total_price_shopping_with_coupon;
        }
        else
        {
            $total_price_shopping_with_coupon=0;
            $total_price_shopping_with_tax = $total_price_shopping_with_general_discount;
        }


        $general_tax = AppSetting::find(1);
        if (isset($general_tax)){
            $total_price_shopping_with_tax +=($total_price_shopping_with_tax * doubleval($general_tax->value))/100.0;
        }


        $total_price_shopping = round($total_price_shopping,2);
        $total_price_shopping_with_discount = round($total_price_shopping_with_discount,2);
        $total_price_shopping_with_tax = round($total_price_shopping_with_tax,2);
        $another_data = [
            'general_discount'=>$general_discount->value,
            'general_tax'=>$general_tax->value,
            'total_price_shopping' => $total_price_shopping,
            'total_price_shopping_with_discount' => $total_price_shopping_with_discount,
            'total_price_shopping_with_general_discount' => $total_price_shopping_with_general_discount,
            'total_price_shopping_with_coupon' => $total_price_shopping_with_coupon,
            'total_price_shopping_with_tax' => $total_price_shopping_with_tax
        ];

        $get_order_user = $this->getOrderUser(auth()->user());

        $ord_customer = auth()->user()->id;
        $ord_number = $ord_customer . "" . $get_order_user;
        $ord_taxAmount = AppSetting::find(1);
        $ord_status = 1;
        $adr_id = $request->adr_id;
        $note= $request->note;
        $ord_createdAt = date('Y-m-d H:i:s');
      //  $ord_schdule_date= date('Y-m-d H:i:s');
        $day_id = 0;
       // if ($request->has('day_id'))
      //      $day_id = $request->day_id - 1;
      //  $ord_schdule_date = date('Y-m-d', strtotime($ord_createdAt . ' +' . $day_id . ' days'));
        $order = new Order();
        $order->ord_customer = \auth()->user()->id;
        $order->ord_number = $ord_number;
        $order->ord_status = $ord_status;
        $order->ord_createdAt = $ord_createdAt;
        $order->ord_deliveryStartedAt = null;
        $order->ord_finishedAt = null;
        $order->ord_canceledAt = null;
        $order->ord_totalAmount = $total_price_shopping ;
        $order->ord_totalDiscount = $total_price_shopping_with_discount;
        $order->ord_netAmount = $total_price_shopping_with_discount;
        $order->gr_net_discount = $total_price_shopping_with_general_discount;
        $order->coupon_net_discount = $total_price_shopping_with_coupon;
        $order->ord_taxAmount = $ord_taxAmount->value;
        $order->ord_general_discount = $general_discount->value;
        $order->ord_totalAfterTax = $total_price_shopping_with_tax;
        $order->adr_id = $adr_id;
        $order->note = $note;
        $order->coupon_id = $coupon_id;
       if (isset($request->ord_schdule_date) && $order->ord_schdule_date != null && $order->ord_schdule_date != '')
            $order->ord_schdule_date = $request->ord_schdule_date;
        if (isset($request->ord_schedule_period_id) && $order->ord_schedule_period_id != null && $order->ord_schedule_period_id != '')
            $order->ord_schedule_period_id = $request->ord_schedule_period_id; 

        if ($order->save()) {
//        `ord_id`, `prd_id`, `pvr_id`, `odt_quantity`, `odt_price`, `odt_discount`
//"customer_id": 8,
//            "prd_id": 4,
//            "pvr_id": 2,
//            "quantity": 2,

            foreach ($shoppingCarts as $shoppingCart) {
                $order_det = new OrderDetail();
                $order_det->ord_id = $order->ord_id;
                $order_det->prd_id = $shoppingCart->prd_id;
                $order_det->pvr_id = $shoppingCart->pvr_id;
                $order_det->odt_quantity = $shoppingCart->quantity;
                $order_det->odt_price = $shoppingCart->total_price;
                $order_det->odt_discount = $shoppingCart->total_price_with_discount;
                $order_det->save();
                $shoppingCart->delete();
            }
              $day=[1=>'السبت',2=>'الاحد',3=>'الاثنين',4=>'الثلاثاء',5=>'الاربعاء',6=>'الخميس',7=>'الجمعة'];
             $notification = new SystemNotifications();
            $notification->not_title = '  تم اضافة الطلب  رقم : '.$ord_number;
            $notification->not_ar = ' قام المستخدم  '.$order->ord_customer.'  بإضافة طلب شراء جديد ';
            if(isset($order->order_day_id) && $order->order_day_id !=null && $order->order_day_id!='')
             $notification->not_ar .= ' تاريخ التسليم  ' . $order->ord_schdule_date . '  يوم  ' .$day[$order->order_day_id].' الفترة '.$order->order_start_time.'-'.$order->order_end_time;
          
            $notification->ord_id = $order->ord_id;
           // $notification->user_id = $order->ord_customer;
            $notification->not_date = date('Y-m-d H:i:s');
            $notification->save();
             $couponrec='';
            if($request->has('coupon_id')) {
               // if(isset($request->coupon_id) && $request->coupon_id !=null)
                $couponrec = Coupon::where('id',$request->get('coupon_id'))->first();
                if(isset($couponrec)) {
                    $couponrec->used = 1;
                    $couponrec->save();
                }
            }
            //  return response_api(true, $shoppingCarts, null, null, $another_data);
            return response_api(true,['items'=> $shoppingCarts,'Coupon'=>$couponrec,'Totals'=>$another_data], null, null, null);
        }
        return response_api(false);
    }
      public function updateOrderRaw(Request $request)
    {
        // 1-Get orderdetail record where  odt_id = request odt
        //
        //*******************Edit Quantity Order Details By Json

        $data = $request->get('data');
//        dd($data);
        $adr_id = $request->get('adr_id');
        $note =  $request->get('note');
        $coupon_id = $request->get('coupon_id');
        foreach ($data as $value) {
            $prd_price = 1;
            $ord_id = $value["ord_id"];
            $order = Order::find($ord_id);
           if($order->ord_status!=1) {
            $getOrder = Order::with('address')->where('ord_id', $ord_id)->first();
                return $this->responseJson(true, 'error', $getOrder);
           }
            $orderDetailRec = OrderDetail::find($value["odt_id"]);
            if (isset($orderDetailRec)) {
                //   dd($orderDetailRec->prd_id);
                if ($orderDetailRec->pvr_id == null) {
                    $prod = Product::where('prd_id', '=', $orderDetailRec->prd_id)->first();
                    $prd_price = $prod->prd_price;
                } else {
                    $prod = ProductVariation::where('prd_id', '=', $orderDetailRec->prd_id)
                        ->where('pvr_id', '=', $orderDetailRec->pvr_id)->first();
                    $prd_price = $prod->pvr_price;
                }
                // dd($prd_price);
                $now = Carbon::now();
                $offersvalue = Offer::select('ofr_discount')
                    ->where('prd_id', '=', $orderDetailRec->prd_id)
                    ->whereDate('ofr_start', '<=', $now)
                    ->whereDate('ofr_end', '>=', $now)
                    ->orderByDesc('ofr_creation_datetime')->first();
                $orderDetailRec->odt_quantity = $value["quantity"];
                $odt_price = $value["quantity"] * $prd_price;
                $orderDetailRec->odt_price = $odt_price;
                $odt_discount_price = ($odt_price * $offersvalue["ofr_discount"]) / 100;
                $orderDetailRec->odt_discount = $odt_price - $odt_discount_price;
                $orderDetailRec->save();
            } else
                return $this->responseJson(true, 'error', $data);
        }
        //foreach ($data as $value) {
        //dd($value["ord_id"]);
        $orderDetails = OrderDetail::where('ord_id', '=', $ord_id)->get();
        $total_price_shopping = 0.0;
        $total_price_shopping_with_discount = 0.0;

        //  dd($orderDetails);
        foreach ($orderDetails as $orderDetail) {
            $total_price_shopping += $orderDetail->odt_price;
            $total_price_shopping_with_discount += $orderDetail->odt_discount;


        }


        if ($total_price_shopping_with_discount != 0)
            $total_price_shopping_with_general_discount = $total_price_shopping_with_discount;
        else
            $total_price_shopping_with_general_discount = $total_price_shopping;
//        $total_price_shopping_with_general_discount = $total_price_shopping;
// general_discount
        $general_discount = AppSetting::find(2);
        if (isset($general_discount)) {
            $total_price_shopping_with_general_discount -= ($total_price_shopping_with_discount * doubleval($general_discount->value)) / 100.0;
        }
        $now = Carbon::now();
//dd($request->coupon_id);
        $coupon_id = '';
        if (isset($request->coupon_id) && $request->coupon_id != null) {
            $coupon_id = $request->coupon_id;
            $coupon = Coupon::where('used', '0')
                ->where('coupon_isDeleted','!=',1)
                ->whereDate('coupon_start', '<=', $now)
                ->whereDate('coupon_end', '>=', $now)->where('coupon_no', $coupon_id)->first();
        }


        //dd($coupon->count());
        $total_price_shopping_with_coupon = $total_price_shopping_with_general_discount;
        if (isset($coupon)) {
            $total_price_shopping_with_coupon -= ($total_price_shopping_with_general_discount * doubleval($coupon->value)) / 100.0;
            $total_price_shopping_with_tax = $total_price_shopping_with_coupon;
        } else {
            $total_price_shopping_with_coupon = 0;
            $total_price_shopping_with_tax = $total_price_shopping_with_general_discount;
        }


        $general_tax = AppSetting::find(1);
        if (isset($general_tax)) {
            $total_price_shopping_with_tax += ($total_price_shopping_with_tax * doubleval($general_tax->value)) / 100.0;
        }


        $total_price_shopping = round($total_price_shopping, 2);
        $total_price_shopping_with_discount = round($total_price_shopping_with_discount, 2);
        $total_price_shopping_with_tax = round($total_price_shopping_with_tax, 2);
        $another_data = [
            'general_discount' => $general_discount->value,
            'general_tax' => $general_tax->value,
            'total_price_shopping' => $total_price_shopping,
            'total_price_shopping_with_discount' => $total_price_shopping_with_discount,
            'total_price_shopping_with_general_discount' => $total_price_shopping_with_general_discount,
            'total_price_shopping_with_coupon' => $total_price_shopping_with_coupon,
            'total_price_shopping_with_tax' => $total_price_shopping_with_tax
        ];

        /*$get_order_user = $this->getOrderUser(auth()->user());

        $ord_customer = auth()->user()->id;
        $ord_number = $ord_customer . "" . $get_order_user;

        $ord_status = 1;
        $adr_id = $request->adr_id;
        $note = $request->note;
        $ord_createdAt = date('Y-m-d H:i:s');*/
        $ord_taxAmount = AppSetting::find(1);
        $order = Order::find($ord_id);
       // $ord_schdule_date = $order->ord_schdule_date;
      /*  if ($request->has('day_id')) {
            $day_id = $request->day_id - 1;
            $ord_schdule_date = date('Y-m-d', strtotime($order->ord_createdAt . ' +' . $day_id . ' days'));
        }*/
        $order->ord_totalAmount = $total_price_shopping;
        $order->ord_totalDiscount = $total_price_shopping_with_discount;
        $order->ord_netAmount = $total_price_shopping_with_discount;
        $order->gr_net_discount = $total_price_shopping_with_general_discount;
        $order->coupon_net_discount = $total_price_shopping_with_coupon;
        $order->ord_taxAmount = $ord_taxAmount->value;
        $order->ord_general_discount = $general_discount->value;
        $order->ord_totalAfterTax = $total_price_shopping_with_tax;
        $order->adr_id = $adr_id;
        $order->note = $note;
        $order->coupon_id = $coupon_id;
        if (isset($request->ord_schdule_date) && $order->ord_schdule_date != null && $order->ord_schdule_date != '')
            $order->ord_schdule_date = $request->ord_schdule_date;
        if (isset($request->ord_schedule_period_id) && $order->ord_schedule_period_id != null && $order->ord_schedule_period_id != '')
            $order->ord_schedule_period_id = $request->ord_schedule_period_id;
        $order->save();

        $couponrec = '';
        if ($coupon_id) {
            // if(isset($request->coupon_id) && $request->coupon_id !=null)
            $couponrec = Coupon::where('coupon_no', $coupon_id)->first();
            if (isset($couponrec)) {
                $couponrec->used = 1;
                $couponrec->save();
            }
        }
       // return $this->responseJson(true, 'success', null);
         $getOrder = Order::with('address')->where('ord_id', $ord_id)->first();


        return $this->responseJson(true, 'success', $getOrder);
     //  return $this->responseJson(true, 'success', $order);
       }
    /*
        public function addOrder(Request $request)
        {
            $user = $request->user();
            $products = $request->shoppCartItems;

            $products = $request->products;
            $offers = Offer::select('*')
                ->where('ofr_start', '>=', Carbon::now())
                ->where('ofr_end', '<=', Carbon::now())
                ->where('ofr_isDeleted', '=', -1)
                ->pluck('ofr_discount', 'prd_id')->toArray();

            $get_order_user = $this->getOrderUser($user);

            $ord_customer = $user->id;
            $ord_number = $ord_customer . "" . $get_order_user            $ord_status = 1;
            $adr_id = $request->adr_id;

    /*
            $arr_order_details = [];
            foreach ($products as $prod) {

                $prd_id = $prod['prd_id'];
                $pvr_id = $prod['pvr_id'];
                $odt_quantity = $prod['odt_quantity'];

                if (empty($prod['pvr_id']) || $prod['pvr_id'] == null) {
                    $getProduct = Product::find($prd_id);
                    $price = $getProduct->prd_price;
                    // if product in offer
                    if (array_key_exists($prd_id, $offers)) {
                        $discount = array_get($offers, $prd_id);
                    } else {
                        $discount = 0;
                    }
                } else {

                    $getProductVariation = ProductVariation::find($pvr_id);
                    $price = ProductVariation::find($pvr_id)->pvr_price;
                    $discount = $getProductVariation->pvr_isDiscount;
                    $discount = $discount == -1 ? 0 : $discount;
                }

                $arr_order_details[] = [
                    'prd_id' => $prd_id,
                    'pvr_id' => $pvr_id,
                    'odt_price' => $price,
                    'odt_quantity' => $odt_quantity,
                    'odt_discount' => $discount
                ];
            }


            $ord_totalAmount = 0;
            $ord_totalDiscount = 0;
            foreach ($arr_order_details as $od_detail) {
                $ord_totalAmount += ($od_detail['odt_quantity'] * $od_detail['odt_price']);
                $ord_totalDiscount += $od_detail['odt_discount'];
            }


        $data = $this->orderData($products , $offers);
        $ord_totalAmount = $data['ord_totalAmount'];
        $ord_totalDiscount = $data['ord_totalDiscount'];
        $arr_order_details = $data['order_details'];


        $ord_createdAt = date('Y-m-d H:i:s');

        $ord_netAmount = $ord_totalAmount - $ord_totalDiscount;
       // $ord_taxAmount=15/100;
        $generlDiscountAmount =AppSetting::find(2);
        $totalafterGeneralDiscAmount=  $ord_netAmount - ($ord_netAmount*$generlDiscountAmount->value);
        $Coupon =Coupon::find($request->coupon_no);
       $totalAfterCoupon=  $totalafterGeneralDiscAmount-($totalafterGeneralDiscAmount*($Coupon->value));
        $ord_taxAmount =AppSetting::find(1);
       // dd($ord_taxAmount->value);
//generaldiscount
        //cabon
      //4
          $ord_totalAfterTax =  $totalAfterCoupon - ( $totalAfterCoupon * ($ord_taxAmount->value));


        $order = Order::create([
            'ord_customer' => $ord_customer,
            'ord_number' => $ord_number,
            'ord_status' => $ord_status,
            'ord_createdAt' => $ord_createdAt,
            'ord_deliveryStartedAt' => null,
            'ord_finishedAt' => null,
            'ord_canceledAt' => null,
            'ord_totalAmount' => $ord_totalAmount,
            'ord_totalDiscount' => $ord_totalDiscount,
            'ord_netAmount' => $ord_netAmount,
            'ord_taxAmount' => $ord_taxAmount->value,
            'ord_totalAfterTax' => $ord_totalAfterTax,
            'adr_id' => $adr_id
        ]);

        $order->orderDetails()->createMany($arr_order_details);
        return $this->responseJson(true, 'success', null);

    }
*/
    public function updateOrder(Request $request) {
        $user = $request->user();
        $ord_id = $request->ord_id;
        $order = Order::find($ord_id);
        if($order) {
            if($user->id == $order->ord_customer) {

                $products = $request->products;
                $adr_id = $request->adr_id;


                $offers = Offer::select('*')
                    ->where('ofr_end', '>', Carbon::now())
                    ->where('ofr_isDeleted', '=', -1)
                    ->pluck('ofr_discount', 'prd_id')->toArray();

                $data = $this->orderData($products , $offers);
                $ord_totalAmount = $data['ord_totalAmount'];
                $ord_totalDiscount = $data['ord_totalDiscount'];
                $arr_order_details =$data['order_details'];

                $ord_netAmount = $ord_totalAmount - $ord_totalDiscount;
                $ord_taxAmount = 15 / 100;


                $ord_totalAfterTax = $ord_netAmount - ($ord_netAmount * $ord_taxAmount);

                Order::find($ord_id)->update([
                    'ord_totalAmount' => $ord_totalAmount,
                    'ord_totalDiscount' => $ord_totalDiscount,
                    'ord_netAmount' => $ord_netAmount,
                    'ord_taxAmount' => $ord_taxAmount,
                    'ord_totalAfterTax' => $ord_totalAfterTax,
                    'adr_id' => $adr_id
                ]);
                $order->orderDetails()->delete();
                $order->orderDetails()->createMany($arr_order_details);

                return $this->responseJson(true, 'success', null);

            }else {
                return $this->responseJson(false, 'not_auth', null);
            }
        }else {
            return $this->responseJson(false, 'not_found', null, 'order');
        }
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        $status = $request->status;


        $rules = [
            'status' => 'required',
            'page_number' => 'required|integer',
            'per_page' => 'required|integer|min:1',
        ];

        $attributeNames = array(
            'page_number' => trans('api.page_number'),
            'per_page' => trans('api.per_page'),
        );


        $validator = Validator::make($request->all(), $rules);
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {
            $page_number = $request->page_number;
            $per_page = $request->per_page;
            $arr = [];
            if (empty($status) || $status == 0) {
                $status = [1, 2, 3, 4];
            } else if ($status == 1) {
                $status = [1, 2];
            } else {
                $status = [3, 4];
            }
            $orders = $user->orders()
                ->orderBy('ord_createdAt', 'desc')
                ->whereIn('ord_status', $status);

            $arr['total_page'] = ceil($orders->count() / $per_page);
            $orders = $orders->take($per_page)
                ->skip($page_number * $per_page)
                ->select('orders.*', DB::raw('DATE_FORMAT(orders.ord_createdAt, "%H:%i %p") as ord_createdAt'))
                ->get();

            $arr['orders'] = $orders;

            return $this->responseJson(true, 'success', $arr);
        }


    }
    public function getDriverOrder(Request $request)
    {
        $get_type = $request->get_type;
        $driver_id = auth()->user()->id;
        //dd($driver_id);
        if (!$request->has('page')) {
            $request->request->add(['page' => 0]);
        }
        $driverOrders = OrderDelivery::where('odv_driver', '=', $driver_id)->pluck('ord_id');

        if (isset($driverOrders) && count($driverOrders) != 0) {
            if ($get_type == 1) {
                $count = Order::with('address')->whereIn('ord_id', $driverOrders)
                    ->whereIn('ord_status', [2, 3, 4])
                    ->count();

                $count_page = ceil($count / max_pagination());
                $getOrder = Order::with('address')->whereIn('ord_id', $driverOrders)
                 
                     ->whereIn('ord_status', [2, 3, 4])
                     ->skip($request->page * max_pagination())->take(max_pagination())
                     ->orderBy('ord_createdAt','desc')
                     ->get();

            } else {
                $count =Order::with('address')->whereIn('ord_id', $driverOrders)
                    ->whereIn('ord_status', [5])
                    ->count();
                $count_page = ceil($count / max_pagination());
                $getOrder = Order::with('address')->whereIn('ord_id', $driverOrders)
                    ->whereIn('ord_status', [5])
                    ->skip($request->page * max_pagination())->take(max_pagination())
                   ->orderBy('ord_createdAt','desc')
                    ->get();
            }

            return $this->responseJson(true, trans('success'), ['count_page' => $count_page, 'orders' => $getOrder], 'order');
        } else
            return $this->responseJson(true, trans('success'), null);


    }
public function getUserOrder(Request $request)
    {
        $get_type = $request->get_type;
        $customer_id = auth()->user()->id;
        if (!$request->has('page')) {
            $request->request->add(['page' => 0]);
        }
         $orders = Order::where('ord_customer', '=', $customer_id)->pluck('ord_id');

        if (isset($orders) && count($orders) != 0) {
            if ($get_type == 1) {
                $count = Order::with('address')->where('ord_customer', $customer_id)
                    ->where(function ($query) {$query->where(function ($query) {
                        $query->whereIn('ord_status', [1,2, 3, 4]);

                    })->orWhere(function($query) {
                        $query->where('eval_flag', '=', 0)
                            ->where('ord_status', '!=', 6);
                    });})
                    ->count();

                $count_page = ceil($count / max_pagination());
                $getOrder = Order::with('address')
                    ->where('ord_customer', $customer_id)
                    ->where(function ($query) {$query->where(function ($query) {
                        $query->whereIn('ord_status', [1,2, 3, 4]);

                    })->orWhere(function($query) {
                        $query->where('eval_flag', '=', 0)
                            ->where('ord_status', '!=', 6);
                    });})->skip($request->page * max_pagination())->take(max_pagination())
                   ->orderBy('ord_createdAt','desc')
                    ->get();

            } else {
                $count = Order::with('address')
                    ->where('ord_customer', $customer_id)
                    ->where(function ($query) {$query->where(function ($query) {
                        $query->whereIn('ord_status', [6]);

                    })->orWhere(function($query) {
                        $query->where('eval_flag', '=', 1);

                    });})
                    ->count();
                $count_page = ceil($count / max_pagination());
                $getOrder = Order::with('address')
                    ->where('ord_customer', $customer_id)
                    ->where(function ($query) {$query->where(function ($query) {
                        $query->whereIn('ord_status', [6]);

                    })->orWhere(function($query) {
                        $query->where('eval_flag', '=', 1);

                    });})
                    ->skip($request->page * max_pagination())->take(max_pagination())
                    ->orderBy('ord_createdAt','desc')
                    ->get();
            }

            return $this->responseJson(true, trans('success'), ['count_page' => $count_page, 'orders' => $getOrder], 'order');
        } else
            return $this->responseJson(false, trans('error'), 'order');


    }
    public function getOrder(Request $request)
    {
        $productController = new ProductController();
        $userController = new UserController();
        $path = $productController->getFullPathProduct();


        $order_id = $request->order_id;
        $getOrder = Order::select('orders.*', DB::raw('DATE_FORMAT(orders.ord_createdAt, "%H:%i %p") as ord_createdAt'))
            ->find($order_id);

        $delivery = OrderDelivery::where('ord_id', '=', $getOrder->ord_id)->first();
        if ($delivery) {
            $user_delivery = User::find($delivery->odv_driver);
            $user_delivery = ['name' => $user_delivery->name, 'image' => $this->fullPath($user_delivery->image)];
        } else {
            $user_delivery = ['name' => '', 'image' => ''];
        }


        if ($getOrder) {

            $order_summary = $getOrder->orderDetails()
                ->join('product_translations', 'product_translations.prd_id', '=', 'order_details.prd_id')
                ->where('product_translations.lng_id', '=', lang())
                ->join('products', 'products.prd_id', '=', 'order_details.prd_id')
                ->join('categories', 'categories.cat_id', '=', 'products.cat_id')
                ->select('order_details.*', 'categories.cat_id', 'categories.cat_parent', 'product_translations.ptr_name', DB::raw('(order_details.odt_quantity * order_details.odt_price) as tot_price '), DB::raw("CONCAT('$path', products.prd_image) as prd_image"));


            $category = Category::all()->pluck('cat_parent', 'cat_id')->toArray();
            $category_order = $order_summary->pluck('cat_parent')->toArray();
            $category_order = array_values(array_unique($category_order));

            $order_summary = $order_summary->get()->toArray();
            $arr = [];
            foreach ($category_order as $cat) {
                $arr_category = array_where($order_summary, function ($value) use ($category, $cat) {
                    return $category[$value['cat_id']] == $cat;
                });
                $category_price = array_sum(array_pluck($arr_category, 'tot_price'));
                $arr_category = array_values($arr_category);
                $cat_name = getTranslation($cat, lang(), $this->category_trans_type)->trn_text;
                $arr[] = ['cat_id' => $cat, 'cat_name' => $cat_name, 'price' => $category_price, 'orders' => $arr_category];
            }


            $getOrder->address = $userController->addCityDetails(Address::find($getOrder->adr_id));
            $getOrder->delivery = $user_delivery;
            $getOrder->order_summary = $arr;
            return $this->responseJson(true, 'success', $getOrder);
        } else {
            return $this->responseJson(true, 'not_found', null, 'order');
        }
    }
    public function getOrderDetails(Request $request)
    {
        $order_id = $request->order_id;
        
        $getOrder = Order::with('address')->where('ord_id',$order_id)->first();
         $DeliveryDay= DeliveryDay::with('schedule')->get();
       // dd($getOrder);
       // return $this->responseJson(true, '', $getOrder, 'order');
        return response_api(true,['order'=> $getOrder,'schedule'=>$DeliveryDay], null, null, null);
     
    }

    public function cancelOrder(Request $request)
    {
        $ord_id = $request->ord_id;
        $user = $request->user();
        $order = Order::find($ord_id);
        if ($order) {

            $ifAuthOrder = Order::where('ord_id', '=', $ord_id)
                ->where('ord_customer', '=', $user->id);

            if ($ifAuthOrder->exists()) {
                if ($ifAuthOrder->first()->ord_status == 1) {
                    $ifAuthOrder->update([
                        'ord_status' => 4 ,
                        'ord_canceledAt' => date('Y-m-d H:i:s')
                    ]);
                       $notification = new SystemNotifications();
                   $notification->not_title =  '  تم الغاء الطلب  : '.$ord_id ;
                    $notification->not_ar = 'قام المستخدم بإلغاء طلب الشراء';
                    $notification->ord_id = $ord_id;
                    $notification->user_id = $order->ord_customer;
                    $notification->not_date = date('Y-m-d H:i:s');
                    $notification->save();
                    return $this->responseJson(true, 'success', null);
                } else {
                    return $this->responseJson(true, 'cannot_cause_state', null);
                }
            } else {
                return $this->responseJson(true, 'not_auth', null);
            }
        } else {
            return $this->responseJson(true, 'not_found', null, 'order');
        }
    }
      public function startWithOrder(Request $request)
    {
        if ($request->has('lat')&& $request->has('lng'))
        {
           // dd($request->lat);
            $ord_id = $request->ord_id;
            $order = Order::find($ord_id);
            $ord_number=$order->ord_number;
            $tokens='';
            $driversTokens='';
            if(isset($order))
            {
                $user=User::find($order->ord_customer);
                $deviceType=$user->deviceType;
                $tokens=$user->fcmToken;
                $ord_status = $order->ord_status;   
            }
            else
                return $this->responseJson(false, 'error', null);
            if($ord_status==2)
            {
                $driver_id=Auth::user()->id;
                $orders_deliveries=OrderDelivery::where('odv_driver',$driver_id)
                    ->where('ord_id',$ord_id)->first();
                if(isset($orders_deliveries)) {
                    $odtracking = new OrderDeliveryTracking();
                    $odtracking->odv_id =$orders_deliveries->odv_id;
                    $odtracking->odt_lat = $request->lat;
                    $odtracking->odt_lng = $request->lng;
                     $odtracking->odt_datetime=date('Y-m-d H:i:s');
                    $odtracking->save();
                    
                       //Notification to Driver
                    $notification = new SystemNotifications();
                    $notification->not_title = '  الطلب رقم  : '.$ord_number.'قيد التوصيل ';
                    $notification->not_ar = 'تم قبول الطلب ';
                    $notification->ord_id = $ord_id;
                    $notification->user_id = $driver_id;
                    $notification->not_date = date('Y-m-d H:i:s');
                    $notification->save();

                    $order->ord_status=3;
                    $order->save();
                    //Notifiction to Users
                    $notification = new SystemNotifications();
                    $notification->not_title = '  الطلب قيد التوصيل ,لطلب رقم  : '.$ord_number;
                    $notification->not_ar = 'تم قبول الطلب من السائق';
                    $notification->ord_id = $ord_id;
                    $notification->user_id = $order->ord_customer;
                    $notification->not_date = date('Y-m-d H:i:s');
                    $notification->save();
                    $this->sendOrderFcm($notification->not_title,$notification->not_ar,$ord_id,$tokens,$deviceType);
                    $delorders_deliveries=OrderDelivery::where('ord_id',$ord_id)->where('odv_driver','!=',$driver_id)->get();
                //    dd(count($delorders_deliveries));
                    foreach($delorders_deliveries as $deliveryorder)
                    {
                        $driversUser=User::find($deliveryorder->odv_driver);
                        $driversTokens=$driversUser->fcmToken;
                        $driverDevice=$driversUser->deviceType;
                        $deliveryorder->delete();
                        $not_title = ' تم قبول الطلب رقم  : '.$ord_number.' من سائق اخر ';
                        $not_ar = 'تم قبول الطلب من سائق اخر,شكرا شكرا لك';
                        //Notifiction to Users
                        $notification = new SystemNotifications();
                        $notification->not_title = $not_title;
                        $notification->not_ar = $not_ar;
                        $notification->ord_id = $ord_id;
                        $notification->user_id = $deliveryorder->odv_driver;
                        $notification->not_date = date('Y-m-d H:i:s');
                        $notification->save();
                        $this->sendOrderFcm($not_title,$not_ar,$ord_id,$driversTokens,$driverDevice);
                    }
                    return $this->responseJson(true, 'success', $odtracking);
                }
                else
                    return $this->responseJson(false, 'errorAsigndriver', null);
            }
            else
                return $this->responseJson(false, 'error',['ord_status'=> $ord_status]);


        }
        else
            return $this->responseJson(false, 'error', null);

    }
   public function updateOrderStatus(Request $request)
    {
        // dd($request->all());
        $tokens='';
        $ord_id = $request->ord_id;
        $ord_status = $request->ord_status;
        $order = Order::find($ord_id);
        $ord_number=$order->ord_number;
        $user=User::find($order->ord_customer);
        $deviceType=$user->deviceType;
        $tokens=$user->fcmToken;
        
         $noti_title = '';
        $not_ar = '';
        // dd($order);
        /*        1.	Pending ord_createdAt
                  2.	assign to+ ord_assignedAt
                  3.	in progress+ ord_deliveryStartedAt
                  4.	confirm deliver + ord_confirmdeliveryAt
                  5.	confirm receive+ ord_finishedAt
                  6.	cancel  +ord_canceledAt*/
        if ($order) {
            if ($ord_status == 2){
             
                $order->ord_assignedAt = date('Y-m-d H:i:s');
                $noti_title = ' توجيه الطلب : '.$ord_number.'للسائق ';
                $not_ar = 'تم توجييه الطلب للسائق بأنتظار قبول السائق';
            }
            else if ($ord_status == 3){
                
                $order->ord_deliveryStartedAt = date('Y-m-d H:i:s');
                    $noti_title =' تم قبول الطلب : '.$ord_number.'من السائق';
                $not_ar = 'تم قبول الطلب من السائق';
            }
            else if ($ord_status == 4) {

                $order->ord_confirmdeliveryAt = date('Y-m-d H:i:s');
                $noti_title = ' تم توصيل الطلب : '.$ord_number;
                $not_ar = 'تم توصيل الطلب للزبون';


            }
                else if ($ord_status == 5){
                    $order->ord_finishedAt = date('Y-m-d H:i:s');
                    $noti_title =  ' تم تأكيد استلام الطلب : '.$ord_number; 
                    $not_ar = 'قام الزبون بتأكيد الاستلام للطلب ';
                }
            else if ($ord_status == 6) {
                if ($order->ord_status == 1) {
                    $order->ord_canceledAt = date('Y-m-d H:i:s');

                    $order->ord_status = $ord_status;
                    $order->save();
                     $notification = new SystemNotifications();
                    $notification->not_title = '  تم الغاء الطلب رقم : ' .$ord_number;
                    $notification->not_ar = 'قام المستخدم بإلغاء طلب الشراء';
                    $notification->ord_id = $ord_id;
                   // $notification->user_id = $order->ord_customer;
                    $notification->not_date = date('Y-m-d H:i:s');
                    $notification->save();
                    if($ord_status == 4)
                        $this->sendOrderFcm($notification->not_title,$notification->not_ar,$ord_id,$tokens,$deviceType);//send FCM Notification
                    
                    //return $this->responseJson(true, 'success', null);
                    $getOrder = Order::with('address')->where('ord_id', $ord_id)->first();
                    return $this->responseJson(true, 'success', $getOrder);
                }

            }

            if ($ord_status != 6) {

                if($order->ord_status==5)  {
                    $order->save();
                }
                else {
                    $order->ord_status = $ord_status;
                    $order->save();
                }
                $notification = new SystemNotifications();
                $notification->not_title = $noti_title;
                $notification->not_ar = $not_ar;
                $notification->ord_id = $ord_id;
                 if($order->ord_status==5)  
                    $notification->user_id = '';
                else
                     $notification->user_id = $order->ord_customer;
                
                $notification->not_date = date('Y-m-d H:i:s');
                $notification->save();
                if($ord_status != 5)
                    $this->sendOrderFcm($noti_title,$not_ar,$ord_id,$tokens,$deviceType);//send FCM Notification
                
                //return $this->responseJson(true, 'success', null);
                $getOrder = Order::with('address')->where('ord_id', $ord_id)->first();
                return $this->responseJson(true, 'success', $getOrder);
            } else
            {
             //   return $this->responseJson(false, 'error', null);
                $getOrder = Order::with('address')->where('ord_id', $ord_id)->first();
                return $this->responseJson(true, 'success', $getOrder);
            }
        } else {
            //return $this->responseJson(true, 'not_found', null);
            $getOrder = Order::with('address')->where('ord_id', $ord_id)->first();
            return $this->responseJson(true, 'success', $getOrder);
        }

    }
    public function endOrder(Request $request)
    {
        $ord_id = $request->ord_id;
        $user = $request->user();
        $order = Order::find($ord_id);
        if ($order) {

            $ifAuthOrder = Order::where('ord_id', '=', $ord_id)
                ->where('ord_customer', '=', $user->id);

            if ($ifAuthOrder->exists()) {
                $ifAuthOrder->update([
                    'ord_status' => 3 ,
                    'ord_finishedAt' => date('Y-m-d H:i:s')
                ]);
                return $this->responseJson(true, 'success', null);
            } else {
                return $this->responseJson(true, 'not_auth', null);
            }
        } else {
            return $this->responseJson(true, 'not_found', null, 'order');
        }
    }

    public function getDrivers(Request $request)
    {
        $user = $request->user();
        $driver = OrderDelivery::join('orders', 'orders.ord_id', '=', 'orders_deliveries.ord_id')
            ->where('orders.ord_customer', '=', $user->id)
            ->join('users', 'users.id', '=', 'orders_deliveries.odv_driver')
            ->select('users.name as driver_name','orders.ord_id' , 'orders.ord_number', 'orders.ord_status')
            ->get();

        return $this->responseJson(true, 'success', $driver);
    }

    public function sendDriverLocation(Request $request)
    {
        $rules = [
            'ord_id' => 'required|numeric|exists:orders,ord_id',
            'lat' => 'required',
            'lng' => 'required'
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {

             $user = auth()->user()->id;//$request->user();
            $ord_id = $request->ord_id;
            $lat = $request->lat;
            $lng = $request->lng;
            $order_delivery = OrderDelivery::where('ord_id', '=', $ord_id)->first();
            if(isset($order_delivery))
                if ($order_delivery->odv_driver == $user) {
                    OrderDeliveryTracking::create([
                    'odv_id' => $order_delivery->odv_id,
                    'odt_lat' => $lat,
                    'odt_lng' => $lng,
                    'odt_datetime' => date('Y-m-d H:i:s')
                ]);

                } 
                else {
                    return $this->responseJson(true, 'not_auth', null);
                }


            return $this->responseJson(true, 'success', null);
        }
    }

    public function getDriversLocation(Request $request)
    {
          $user = auth()->user()->id;//$request->user();

        $order_ids = Order::where('ord_customer', '=', $user)
            ->where('ord_status', '=', 2)
            ->pluck('ord_id')
            ->toArray();

        $order_delivery = OrderDelivery::join('orders', 'orders.ord_id', '=', 'orders_deliveries.ord_id')
            ->join('users', 'users.id', '=', 'orders_deliveries.odv_driver')
            ->whereIn('orders.ord_id', $order_ids);


        $odv_ids = array_unique($order_delivery->pluck('odv_id')->toArray());
        $order_delivery = $order_delivery
            ->select('orders.ord_id', 'orders.ord_number', 'orders_deliveries.odv_id', 'users.name as driver_name')
            ->get();

        $order_delivery_tracking = OrderDeliveryTracking::whereIn('odv_id',$odv_ids)->get();
        $order_delivery = $order_delivery->map(function($value) use ($order_delivery_tracking) {
            $driver_location = $order_delivery_tracking->where('odv_id' , '=' , $value->odv_id)->sortByDesc('odt_datetime')->first();
            $value->lat = $driver_location['odt_lat'];
            $value->lng = $driver_location['odt_lng'];
            return $value;
        });

        return $this->responseJson(true, 'success', $order_delivery);


    }

    function getDriverLocation(Request $request)
    {
       // $user = auth()->user()->id;//$request->user();
        $ord_id=$request->ord_id;

        $odv_id = OrderDelivery::select('odv_id')->where('ord_id', '=', $ord_id)->first();

//dd($odv_id->odv_id);
        if(isset($odv_id))
        {   
           $order_delivery_tracking=OrderDeliveryTracking::select('odt_lat','odt_lng')->where('odv_id','=',$odv_id->odv_id)->orderBy('odt_datetime', 'desc')->first();
            return $this->responseJson(true, trans('success'), $order_delivery_tracking);
        }
        else
         return $this->responseJson(false, trans('error'), null);

    }

    /*                     */

    public function orderDetails($order)
    {
        return $order->orderDetails;
    }

    public function getOrderUser($user)
    {
        return $user->orders->count();
    }

    public function getPriceProduct()
    {
        return Product::all()->pluck('prd_price', 'prd_id')->toArray();
    }
    public function orderEvaluation(Request $request)
    {
        $rules = [
            'ord_id' => 'required|numeric|exists:orders,ord_id',
            'eval_value' => 'required',
          
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {
             $order=Order::find($request->ord_id);
            $ord_status=$order->ord_status;
         
            if ($ord_status == 5) {

                $orderEvaluation = new OrderEvaluation();
                $orderEvaluation->ord_id = $request->get('ord_id');
                $orderEvaluation->eval_value = $request->get('eval_value');
                if ($request->has('eval_comment'))
                    $orderEvaluation->eval_comment = $request->get('eval_comment');
                $orderEvaluation->ord_status = $ord_status;
                $orderEvaluation->save();

                $order = Order::find($request->get('ord_id'));
                $order->eval_flag = 1;
                $order->save();
                return $this->responseJson(true, trans('success'), $orderEvaluation);
            }
            else
                 return $this->responseJson(false, trans('error'), null);
            
        }

    }
      public function getDriverOrderDetails(Request $request)
    {
        // $productController = new ProductController();
        // $userController = new UserController();
        //$path = $productController->getFullPathProduct();


        $order_id = $request->order_id;
        $user_id=Auth::user()->id;
        $count=OrderDelivery::where('ord_id','=',$order_id)
        ->where('odv_driver','=',$user_id)->count();
        if ($count==1) {


            $getOrder = Order::with('address')->where('ord_id', $order_id)->first();

            return $this->responseJson(true, '', $getOrder, 'order');
        }
        else
            return $this->responseJson(false, 'errorNotAssign', null);
    }
    public function getDeliverySchedules()
    {
        $model= DeliveryDay::with('schedule')->get();
        return $this->responseJson(true, 'success', $model, 'order');
    }

}
