<?php

namespace App\Http\Controllers\api;

use App\DeliveryDay;
use App\Offer;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ShoppingCart;
use Illuminate\Support\Facades\DB;
use App\Attribute;
use App\ProductVariation;
use App\AppSetting;
use App\ProductTranslation;
use Carbon\Carbon;
use App\Category;

class ShoppingCartController extends Controller
{
     public function addCartItem(Request $request)
    {
        $prd_id = $request->prd_id;
        $pvr_id = 0;
        $serilizdAttribute = serialize($request->attribute);

        $checkProduct = ShoppingCart::where('prd_id', $request->prd_id)
            ->where('customer_id', auth()->user()->id)
            ->where('prd_attribute', $serilizdAttribute)->first();
        //   dd($checkProduct);
        if (isset($checkProduct))//if exist product in shopping chart
        {
            // dd('yes');
            $productrec = Product::find($prd_id);
            {
                $totalQuantity = $checkProduct->quantity + $request->quantity;
                if ($totalQuantity <= $productrec->prd_maxQuantity) {
                    // dd('1111');
                    $checkProduct->quantity = $totalQuantity;
                    $checkProduct->save();
                    return $this->responseJson(true, 'success', $checkProduct);
                } else {
                    // dd('222');
                    $checkProduct->quantity = $productrec->prd_maxQuantity;
                    $checkProduct->save();
                    return $this->responseJson(true, 'successMaxQun', $checkProduct);
                }
            }
        } else {
            $virations = ProductVariation::where('prd_id', $request->prd_id)
                ->where('pvr_attributesValues', $serilizdAttribute)->first();
            if (isset($virations))
                $pvr_id = $virations->pvr_id;
        }

// ***** add Shopping Cart items
        $cart = New ShoppingCart();
        $cart->customer_id = $request->user()->id;
        $cart->prd_id = $request->prd_id;
        $cart->pvr_id = $pvr_id;

        $cart->prd_attribute = $serilizdAttribute;
        $cart->quantity = ($request->quantity<=0)?1:$request->quantity;
        if ($request->has('note'))
            $cart->note = $request->note;
        $cart->save();

        return $this->responseJson(true, 'success', $cart);
    }
    public function editShoppCart(Request $request)
    {

//        dd($request->all());
        $data = $request->get('data');
        foreach ($data as $value){
            $cartRec = ShoppingCart::find($value["id"]);
            $cartRec->quantity = $value["quantity"];
            $cartRec->save();
//            dd($cartRec);
        }

        return response_api(true);
        /*$cart_id = $request->get('cart_id');
        $cartRec = ShoppingCart::find($cart_id);
        $cartRec->quantity = $request->get('quantity');
        $cartRec->save();
        return response_api(true);*/
//        $itr = count($request->shopping_cart_id);
//        for ($i = 0; $i < $itr; $i++) {
//            //dd($request->shopping_cart_id[$i]);
//            $cartRec = ShoppingCart::find($request->shopping_cart_id[$i]);
//            //  dd($cartRec->quantity);
//            $cartRec->quantity = $request->quantity[$i];
//            $cartRec->save();
//            //*////////////
//        }


    }
   public function deleteCartItem(Request $request){
        $cart_id = $request->get('shopping_cart_id');

            $cartRec = ShoppingCart::find($cart_id);

            if(isset($cartRec)) {
                $cartRec->delete();
                return $this->responseJson(true, trans('success'),['id'=>$cart_id]);
               
            }
        return $this->responseJson(false, trans('error'),['id'=>$cart_id]);
    }
    public function getCartItems(Request $request)
    {
   // dd('1111');

//        $lang = $request->header('lang');
//dd($lang);
        if (!$request->has('page')) {
            $request->request->add(['page' => 0]);
        }
        $shoppingCarts = ShoppingCart::with('Product')->where('customer_id', auth()->user()->id)->get();


        $total_price_shopping = 0.0;
        $total_price_shopping_with_discount = 0.0;
        $arr='';
        $i=0;
        foreach ($shoppingCarts as $cart) {
            $arr='';
           // $cart->attributes=$this->getProductAttribute($cart->prd_id);
            //variations
//'prd_name','prd_image',

  unset($cart->prd_attribute);
  
        $cart->cat_id = $cart->product->cat_parent;
            unset($cart->product->cat_parent);
         // dd($cart->product);
       if ($cart->product->prd_image) {

                $arr = $cart->product->prd_image;
           $cart->product->prd_image = preg_filter('/^/', $this->getFullPathProduct(), $arr);
               $cart->prd_thumbnail = url('storage/product/thumb/') . "/".$cart->prd_thumbnail;
       }
            $prd_trn = $this->getProductTranslation($cart->product->prd_id);
            $cart->product->prd_name = $prd_trn->ptr_name;
//******
            $arr='';
             unset($cart->product->prd_gallery);
           /* if ($cart->product->prd_gallery) {
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
         //   $cart->cat_id=$cart->product->cat_id;
           // $cart->product->ofr_discount=$offersvalue['ofr_discount'];
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
            ++$i;

        }
        $total_price_shopping_with_general_discount = $total_price_shopping_with_discount;
        //$total_price_shopping_with_general_discount = $total_price_shopping;
        // general_discount
        $general_discount = AppSetting::find(2);
        if (isset($general_discount)){
            $total_price_shopping_with_general_discount -=($total_price_shopping_with_discount * doubleval($general_discount->value))/100.0;
        }

        $total_price_shopping_with_tax = $total_price_shopping_with_general_discount;

        $general_tax = AppSetting::find(1);

        if (isset($general_tax)){
            $total_price_shopping_with_tax +=($total_price_shopping_with_general_discount * doubleval($general_tax->value))/100.0;

        }
        $total_price_shopping = round($total_price_shopping,2);
        $total_price_shopping_with_discount = round($total_price_shopping_with_discount,2);
        $total_price_shopping_with_tax = round($total_price_shopping_with_tax,2);
        $DeliveryDay= DeliveryDay::with('schedule')->get();
        $another_data = [
            'general_discount'=>$general_discount->value,
            'general_tax'=>$general_tax->value,
            'total_price_shopping' => $total_price_shopping,
            'total_price_shopping_with_discount' => $total_price_shopping_with_discount,
            'total_price_shopping_with_general_discount' => $total_price_shopping_with_general_discount,
            'total_price_shopping_with_tax' => $total_price_shopping_with_tax,
            
        ];

//        $s_carts->total_price_shopping = $total_price_shopping;
//        dd($s_carts);
        //return responseJson(true, 'success', $shoppingCarts);
        // return response_api(true,['items'=> $shoppingCarts], null, null, $another_data);
          return response_api(true,['items'=> $shoppingCarts,'Totals'=>$another_data,'schedule'=>$DeliveryDay], null, null, null);
    }
     public function getCartDetails(Request $request)
    {
        //dd('1111');

//        $lang = $request->header('lang');
//dd($lang);
        if (!$request->has('page')) {
            $request->request->add(['page' => 0]);
        }
         $count =  ShoppingCart::where('customer_id', auth()->user()->id)->count();
        $count_page = ceil($count/ max_pagination());
        $shoppingCarts = ShoppingCart::where('customer_id', auth()->user()->id)->skip($request->page * max_pagination())->take(max_pagination())->get();
     //   $shoppingCarts = ShoppingCart::where('customer_id', auth()->user()->id)->get();
        $cat_ids = $shoppingCarts->pluck('cat_id')->unique()->toArray();
        $cat_ids = array_values($cat_ids);
        $cats = Category::whereIn('cat_id', $cat_ids)->get();
//        $shoppingCarts = ShoppingCart::where('customer_id', auth()->user()->id)->get()->where('cat_id',16);
//        $shoppingCarts = ShoppingCart::where('customer_id', auth()->user()->id)->get()->whereIn('cat_id', $cat_ids);
//        return response_api(true, $shoppingCarts, null, null, null);//, 'Totals' => $another_data]




//        return response_api(true, $cats);
        //dd($shoppingCarts);
        $total_price_shopping = 0.0;
        $total_price_shopping_with_discount = 0.0;
        $arr = '';
        $i = 0;


        foreach ($shoppingCarts as $cart) {

            $total_price_shopping_with_discount += $cart->total_price_with_discount;
            $total_price_shopping += $cart->total_price;
            ++$i;

        }
        $total_price_shopping_with_general_discount = $total_price_shopping_with_discount;

        $general_discount = AppSetting::find(2);
        if (isset($general_discount)) {
            $total_price_shopping_with_general_discount -= ($total_price_shopping_with_discount * doubleval($general_discount->value)) / 100.0;
        }

        $total_price_shopping_with_tax = $total_price_shopping_with_general_discount;

        $general_tax = AppSetting::find(1);

        if (isset($general_tax)) {
            $total_price_shopping_with_tax += ($total_price_shopping_with_general_discount * doubleval($general_tax->value)) / 100.0;

        }
        $total_price_shopping = round($total_price_shopping, 2);
        $total_price_shopping_with_discount = round($total_price_shopping_with_discount, 2);
        $total_price_shopping_with_tax = round($total_price_shopping_with_tax, 2);
        $DeliveryDay= DeliveryDay::with('schedule')->get();
        $another_data = [
             'total_page' => $count_page,
            'general_discount' => $general_discount->value,
            'general_tax' => $general_tax->value,
            'total_price_shopping' => $total_price_shopping,
            'total_price_shopping_with_discount' => $total_price_shopping_with_discount,
            'total_price_shopping_with_general_discount' => $total_price_shopping_with_general_discount,
            'total_price_shopping_with_tax' => $total_price_shopping_with_tax
        ];

        foreach ($cats as $key => $cat) {
            $s = [];
            $sCarts = $shoppingCarts->where('cat_id', $cat->cat_id);

            $cat_total_price = 0;
            $cat_total_discount_price = 0;
            foreach ($sCarts as $cart){
               $prd =  $cart->Product()->first();
               
               $cart->prd_minQuantity = $prd->prd_minQuantity;
               $cart->prd_maxQuantity = $prd->prd_maxQuantity;
               $cart->prd_price = $prd->prd_price;
               $cart->ptr_name = $prd->ptr_name;
               $cart->ptr_name = $prd->ptr_name;
             //  $cart->prd_image=$prd->prd_image;
                   $cart->prd_image= preg_filter('/^/', $this->getFullPathProduct(), $prd->prd_image);
                    $cart->prd_thumbnail = url('storage/product/thumb/') . "/".$cart->prd_thumbnail;
               $cart->offers = $prd->offers;
                $s [] = $cart;
                $cat_total_price+= $cart->total_price;
                $cat_total_discount_price+= $cart->total_price_with_discount;
            }
            $cat->cat_total_price = $cat_total_price;
            $cat->cat_total_discount_price = $cat_total_discount_price;
            $cat->order_summary = $s;
        }
          return response_api(true,['items'=> $cats,'Totals'=>$another_data,'schedule'=>$DeliveryDay], null, null, null);
       // return response_api(true, $cats, null, null, $another_data);//, 'Totals' => $another_data]
    }
//    public function getCartItems(Request $request)
//    {
//
//        if (!$request->has('page')){
//            $request->request->add(['page'=>0]);
//        }
//
//        $customer_id=$request->user()->id;
//
//        $carts_collection = DB::table('shopping_carts')
//            ->join('Products', 'shopping_carts.prd_id', '=', 'Products.prd_id')
//            ->leftjoin('product_variations', 'shopping_carts.pvr_id', '=', 'product_variations.pvr_id')
//          //  ->join('product_attributes', 'shopping_carts.prd_id', '=', 'product_attributes.prd_id')
//            ->select('shopping_carts.*','Products.prd_image','Products.prd_price',
//                'Products.prd_gallery','Products.cat_id','Products.prd_unit','prd_unitValue','product_variations.pvr_price',
//
//              //'product_attributes.atr_id','product_attributes.pat_values')
//            ->skip($request->page * max_pagination())->take(max_pagination())->get();
//        $new_cart_collection=[];
//foreach ($carts_collection as $cart) {
//    //  echo($cart->prd_id);
//
//    $prd_trn = $this->getProductTranslation($cart->prd_id);
//    $cat_parent = Category::where('cat_id', '=', $cart->cat_id)->first()->cat_parent;
//    $cat_parent_name = getTranslation($cat_parent, lang(), category_trans_type())->trn_text;
//  /*  if ($cart->prd_gallery) {
//        $arr = $cart->prd_gallery;
//        $arr = array_prepend($arr, $cart->prd_image);
//        $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
//    } else {
//        $arr = [$cart->prd_image];
//        $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
//    }*/
//
//    /*  if (array_key_exists($carts_collection->prd_id, $offers)) {
//          $isDiscount = 1;
//          $discount = array_get($offers, $product->prd_id);
//      }
//*/
//    $carts_collection->cat_parent = $cat_parent;
//    $carts_collection->cat_parent_name = $cat_parent_name;
//    //dd($carts_collection->cat_parent_name);
// //   $carts_collection->prd_images = $arr;
//   // dd($prd_trn->ptr_name);
//    $carts_collection->prd_name = $prd_trn->ptr_name;
//    $carts_collection->prd_description = $prd_trn->ptr_description;
//
//    //   $carts_collection->prd_isDiscount = $isDiscount;
//    // $carts_collection->prd_discount = $discount;
//  //  dd(getTranslation($cart->prd_unit, lang(), lookup_trans_type())->trn_text);
//    $carts_collection->prd_unit = getTranslation($cart->prd_unit, lang(), lookup_trans_type())->trn_text;
//    //dd(($cart->prd_unitValue . "" . $carts_collection->prd_unit) . " * " . ($cart->prd_price . "/" . $cart->prd_unit) . " = " . ($cart->prd_unitValue * $cart->prd_price));
//    $carts_collection->estimated_price = ($cart->prd_unitValue . "" . $carts_collection->prd_unit) . " * " . ($cart->prd_price . "/" . $cart->prd_unit) . " = " . ($cart->prd_unitValue * $cart->prd_price);
//   // dd($this->getProductAttribute($cart->prd_id));
//    $carts_collection->attribute = $this->getProductAttribute($cart->prd_id);
// //   dd($this->getProductVariationPrice($cart->prd_id));
//    $carts_collection->variation_price = $this->getProductVariationPrice($cart->prd_id);
//  //  dd($carts_collection);
//    $new_cart_collection[]=$carts_collection;
//}
//dd($carts_collection);
//       /* unset($product->prd_gallery);
//        unset($product->prd_image);*/
//        /*   $carts_collection = ShoppingCart::with(['Product'=>function($q){
//            $q->with(['translations'=>function($q){
//                $q->where('lng_id',lang());
//            },'variations','Category']);
//        }])->where('customer_id', $customer_id);
//*/
////dd( $carts_collection);
//        $taxAmount =AppSetting::find(1);
//
//
//        $num_of_objects = $carts_collection->count();
//     //   $carts = $carts_collection->skip($request->page * max_pagination())->take(max_pagination())->get();
//
//        $products=[];
//        $totalNetAmount=0;
//        foreach($carts_collection as $cart) {
//            $products[]=['prd_id'=>$cart->prd_id,'pvr_id'=>$cart->pvr_id,'quantity'=>$cart->quantity];
//            $offers = Offer::where('prd_id',$cart->prd_id)->first();
//
//            $data= $this->orderData($products ,$offers->ofr_discount);
//            $cart->prod_totalAmount = $data['prod_totalAmount'];
//            $cart->prod_Discount = $data['prod_Discount'];
//
//            $cart->prod_netAmount = $cart->prod_totalAmount- ($cart->prod_totalAmount*$cart->prod_Discount /100);
//
//        }
//        foreach ($carts_collection as $cart) {
//            $totalNetAmount +=$cart->prod_netAmount;
//
//        }
//        $generlDiscountAmount =AppSetting::find(2);
//        $totalafterGDiscAmount=  $totalNetAmount - ($totalNetAmount*$generlDiscountAmount->value/100);
//
//
//        $ord_taxAmount =AppSetting::find(1);
//
//        $totalAfterTax =  $totalafterGDiscAmount + ( $totalafterGDiscAmount * $ord_taxAmount->value/100);
//
//
//        return response_api(true,$carts_collection,page_count($num_of_objects),$request->page,$totalNetAmount,$totalafterGDiscAmount,$totalAfterTax);
//    }
    public function getProductAttribute($product_id)
    {
        $product_attribute_table = DB::table('product_attributes');
        $product_attribute = $product_attribute_table->where('prd_id', '=', $product_id)
            ->select('atr_id', 'pat_values', 'pat_isVariation')
            ->get();

        $product_attribute = $product_attribute->map(function ($value) {
            $get_atr_values = unserialize($value->pat_values);
            $atr_value_arr = [];
            foreach ($get_atr_values as $p) {
                array_push($atr_value_arr, [
                    'atv_id' => $p,
                    'atv_name' => getTranslation($p, lang(), attribute_value_trans_type())->trn_text,
                ]);
            }

            $value->atr_name = getTranslation($value->atr_id, lang(), attribute_trans_type())->trn_text;
            $value->atr_isSizeAttribute = Attribute::where('atr_id' , '=' ,$value->atr_id)->first()->atr_isSizeAttribute;
            $value->atr_values = $atr_value_arr;
            unset($value->pat_values);
            return $value;
        });
        return $product_attribute;
    }
    public function fullPathProduct($img)
    {
        return url('storage/product/img/') . "/" . $img;
    }

    public function getFullPathProduct()
    {
        return url('storage/product/img/') . "/";
    }

    public function getProductTranslation($product_id)
    {
        return ProductTranslation::where('prd_id', '=', $product_id)
            ->where('lng_id', '=', lang())
            ->first();
    }

    public function getProductVariationPrice($prd_id,$pvr_id)
    {
        $builder = DB::table('product_variations');
        $variationPrice = $builder->where('prd_id', '=', $prd_id)->where('pvr_id', '=', $pvr_id)
            ->select('pvr_attributesValues', 'pvr_price', 'pvr_isDefault', 'pvr_isDiscount', 'pvr_discount')
            ->get();

        $variationPrice = $variationPrice->map(function ($value) {

            $value->pvr_attributesValues = unserialize($value->pvr_attributesValues);
            return $value;
        });
        return $variationPrice;
    }

    public function orderData($products, $offers)
    {

        $discount = 0;
        $arr_order_details = [];
        foreach ($products as $prod) {

            $prd_id = $prod['prd_id'];
            $pvr_id = $prod['pvr_id'];
            $quantity = $prod['quantity'];

            if (empty($prod['pvr_id']) || $prod['pvr_id'] == null) {

                $getProduct = Product::find($prd_id);

                $price = $getProduct->prd_price;

                $discount = $offers;

            } else {

                $getProductVariation = ProductVariation::find($pvr_id);
                $price = ProductVariation::find($pvr_id)->pvr_price;
                $isDiscount = $getProductVariation->pvr_isDiscount;
                if ($isDiscount == -1)
                    $discount = 0;
                else
                    $discount = $offers;


            }

            $arr_order_details[] = [
                'prd_id' => $prd_id,
                'pvr_id' => $pvr_id,
                'price' => $price,
                'quantity' => $quantity,
                'discount' => $discount
            ];
        }


        $prod_totalAmount = ($quantity * $price);

        $data = ['prod_totalAmount' => $prod_totalAmount, 'prod_Discount' => $discount];
        return $data;
        //return 1;
    }
    public function getCartCount()
    {
        $user_id = auth()->user()->id;
        $user_shop_count = ShoppingCart::where('customer_id', $user_id)
           ->count();

        return $this->responseJson(true, 'success', $user_shop_count);
    }

}
