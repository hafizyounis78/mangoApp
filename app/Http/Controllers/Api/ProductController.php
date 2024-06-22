<?php

namespace App\Http\Controllers\Api;

use App\Attribute;
use App\AttributeValue;
use App\Category;
use App\FollowingSeller;
use App\Instruction;
use App\Mail\ResetPassword;
use App\Offer;
use App\Product;
use App\ProductTranslation;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
	
	
	public function productsList(Request $request){
		$page = $request->input('page_number', 0);
		$length = $request->input('per_page', 10);
		$main_category_id = $request->input('main_category_id', NULL);
		$sub_category_id = $request->input('sub_category_id', NULL);
		$name = $request->input('name', '');
		$price_from = $request->input('price_from', 0);
		$price_to = $request->input('price_to', 0);
		
		$now = new \DateTime();
		$now = $now->format('Y-m-d H:i:s');
		$prd_unit = DB::raw("(SELECT trn_text FROM translations WHERE trn_foreignKey = prd_unit AND lng_id = " . lang() . " AND trn_type = 'unit') AS prd_unit");
		$prd_isDiscount = DB::raw("IFNULL((SELECT 1 FROM offers WHERE offers.prd_id = products.prd_id AND ofr_isDeleted <> 1 AND ofr_start <= '" . $now . "' AND ofr_end >= '" . $now . "'), -1) AS prd_isDiscount");
		$prd_discount = DB::raw("IFNULL((SELECT ofr_discount FROM offers WHERE offers.prd_id = products.prd_id AND ofr_isDeleted <> 1 AND ofr_start <= '" . $now . "' AND ofr_end >= '" . $now . "'), 0) AS prd_discount");
		
		$whereCond = [
			['prd_isDeleted', '<>', 1],
			['product_translations.lng_id', '=', lang()],
		];
		if(!empty($price_from)){
			$whereCond[] = ['prd_price', '>=', $price_from];
		}
		if(!empty($price_to)){
			$whereCond[] = ['prd_price', '<=', $price_to];
		}
		if(!empty($name)){
			$whereCond[] = ['ptr_name', 'LIKE', '%' . $name . '%'];
		}
		
		$builder = DB::table('products')->leftJoin('product_translations', 'product_translations.prd_id', '=', 'products.prd_id');
					
		if(!empty($main_category_id) && empty($sub_category_id)){
			$builder->join('categories', 'categories.cat_id', '=', 'products.cat_id');
			$whereCond[] = ['cat_parent', '=', $main_category_id];
		}
		if(!empty($sub_category_id)){
			$whereCond[] = ['cat_id', '=', $sub_category_id];
		}
		
			
		$builder->where($whereCond);
		
		$builder2 = clone $builder;
		
		
		$total = $builder2->select(DB::raw('COUNT(products.prd_id) AS tot'))->get();
		$total = $total[0]->tot;
	//	dd($builder2->get());
		$productsColl = $builder->select('products.prd_id','prd_thumbnail', 'prd_image', 'prd_price', 'ptr_name', $prd_isDiscount, $prd_discount, $prd_unit, 'prd_unitValue')
	//	$productsColl = $builder->select('products.prd_id', 'prd_image', 'prd_price', 'ptr_name', $prd_unit, 'prd_unitValue')
				//->take($length)->skip($page * $length)->get();
				          ->skip($request->page_number * max_pagination())->take(max_pagination())->get();
		//	dd($productsColl);
		$imagesPath = url('storage/product/img/') . "/";
		$thumbPath=url('storage/product/thumb/') . "/";
		$products = [];
		foreach($productsColl as $p){
			$products[] = [
				'prd_id' => $p->prd_id,
				'prd_image' => $imagesPath . $p->prd_image,
				'prd_thumbnail'=>$thumbPath.$p->prd_thumbnail,
				'prd_price' => $p->prd_price,
				'prd_name' => $p->ptr_name,
				'prd_isDiscount' => $p->prd_isDiscount,
				'prd_discount' => $p->prd_discount,
				'prd_unit' => $p->prd_unit,
				'prd_unitValue' => $p->prd_unitValue,
				
			];
		}
		
		$response = [
			'status' => true,
			'message' => trans('api.success'),
			'data' => [
				'total_page' => ceil($total / $length),
				'products' => $products,
			],
		];
		
		return response()->json($response);
	}
	
	
	public function offeredProductsList(Request $request){
		$page = $request->input('page_number', 0);
		$length = $request->input('per_page', 10);
		$main_category_id = $request->input('main_category_id', NULL);
		$sub_category_id = $request->input('sub_category_id', NULL);
		$name = $request->input('name', '');
		$price_from = $request->input('price_from', 0);
		$price_to = $request->input('price_to', 0);
		
		$now = new \DateTime();
		$now = $now->format('Y-m-d H:i:s');
		$prd_unit = DB::raw("(SELECT trn_text FROM translations WHERE trn_foreignKey = prd_unit AND lng_id = " . lang() . " AND trn_type = 'unit') AS prd_unit");
		
		$whereCond = [
			['prd_isDeleted', '<>', 1],
			['ofr_isDeleted', '<>', 1],
			['ofr_start', '<=', $now],
			['ofr_end', '>=', $now],
			['product_translations.lng_id', '=', lang()],
		];
		if(!empty($price_from)){
			$whereCond[] = ['prd_price', '>=', $price_from];
		}
		if(!empty($price_to)){
			$whereCond[] = ['prd_price', '<=', $price_to];
		}
		if(!empty($name)){
			$whereCond[] = ['ptr_name', 'LIKE', '%' . $name . '%'];
		}
		
		$builder = DB::table('products')->join('offers', 'offers.prd_id', '=', 'products.prd_id')	
					->leftJoin('product_translations', 'product_translations.prd_id', '=', 'products.prd_id');
					
		if(!empty($main_category_id) && empty($sub_category_id)){
			$builder->join('categories', 'categories.cat_id', '=', 'products.cat_id');
			$whereCond[] = ['cat_parent', '=', $main_category_id];
		}
		if(!empty($sub_category_id)){
			$whereCond[] = ['cat_id', '=', $sub_category_id];
		}
		
		$builder->where($whereCond);
		
		$builder2 = clone $builder;
		$total = $builder2->select(DB::raw('COUNT(products.prd_id) AS tot'))->get();
		$total = $total[0]->tot;
		
		$productsColl = $builder->select('products.prd_id','prd_thumbnail', 'prd_image', 'prd_price', 'ptr_name', 'ofr_discount', $prd_unit, 'prd_unitValue')
				->take($length)->skip($page * $length)->get();
				
		$imagesPath = url('storage/product/img/') . "/";
		$thumbPath=url('storage/product/thumb/') . "/";
		$products = [];
		foreach($productsColl as $p){
			$products[] = [
				'prd_id' => $p->prd_id,
				'prd_image' => $imagesPath . $p->prd_image,
					'prd_thumbnail' => $thumbPath . $p->prd_thumbnail,
				'prd_price' => $p->prd_price,
				'prd_name' => $p->ptr_name,
				'prd_discount' => $p->ofr_discount,
				'prd_unit' => $p->prd_unit,
				'prd_unitValue' => $p->prd_unitValue,
			];
		}
		
		$response = [
			'status' => true,
			'message' => trans('api.success'),
			'data' => [
				'total_page' => ceil($total / $length),
				'products' => $products,
			],
		];
		
		return response()->json($response);
	}

	
	
	
    public function products(Request $request)
    {

        $rules = [
            'category_id' => 'required|numeric|exists:categories,cat_id',
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
            $products = Product::select('*')->where('cat_id', '=', $request->category_id);
            $arr['total_page'] = ceil($products->count() / $per_page);
            $products = $products
                ->take($per_page)
                ->skip($page_number * $per_page)
                ->get();


            $products = $this->productLessDetails($products);
            $arr['products'] = $products;
			
			return $this->responseJson(true, 'success', $arr);
        }
    }

    public function searchWithFilter(Request $request)
    {
        $rules = [
            'price_from' => 'numeric|min:0',
            'price_to' => 'numeric|min:0',
            'page_number' => 'required|integer',
            'per_page' => 'required|integer|min:1',


        ];

        $attributeNames = array(
            'page_number' => trans('api.page_number'),
            'per_page' => trans('api.per_page'),
        );

        $validate = Validator::make($request->all(), $rules);
        $validate->setAttributeNames($attributeNames);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {

            $arr = [];

            $price_from = $request->price_from;
            $price_to = $request->price_to;
            $category = $request->category;
            $page_number = $request->page_number;
            $per_page = $request->per_page;

            if (empty($price_from) || $price_from == 0) {
                // $price_from = Product::all()->min('prd_price');
                $price_from = 0;
            }
            if (empty($price_to) || $price_to == 0) {
                $price_to = Product::all()->max('prd_price');
            }

            if (strtolower($category) == "all" || empty($category) || $category == -1) {
                $category = Category::select('*')->pluck('cat_id')->toArray();
            } else {
                $cat_parent = Category::where('cat_parent' , '=' , $category)->pluck('cat_id')->toArray();
                $category = $cat_parent;
               // return $category;
              //  $category = [(int)$category];
            }
            $prd_name = '';
            if ($request->exists('name')) {
                $prd_name = $request->name;
            }


            $products_trn = ProductTranslation::where('ptr_name', 'LIKE', "%$prd_name%")
                ->distinct('prd_id')
                ->pluck('prd_id')
                ->toArray();

            $products = Product::whereBetween('prd_price', [$price_from, $price_to])
                ->whereIn('prd_id', $products_trn)
                ->whereIn('cat_id', $category);
            // ->take($per_page)
            //  ->skip($page_number * $per_page)->get();

            $arr['total_page'] = ceil($products->count() / $per_page);
            $products = $products->take($per_page)
                ->skip($page_number * $per_page)->get();

            $products = $this->productLessDetails($products);
            $arr['products'] = $products;
            return $this->responseJson(true, 'success', $arr);
        }
    }

    public function getProduct(Request $request)
    {
		$now = new \DateTime();
		$now = $now->format('Y-m-d H:i:s');
        $wishListController = new WishListController();
        $offers = Offer::where([
			['ofr_start', '<=', $now],
			['ofr_end', '>=', $now],
		])->select('*')->pluck('ofr_discount', 'prd_id')->toArray();
        $rules = [
            'product_id' => 'required|numeric|exists:products,prd_id',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {

            $product_id = $request->product_id;
            $in_wishList = -1;
            $isDiscount = -1;
            $discount = 0;
			
            if(Auth::guard('api')->check()) {
                if($wishListController->ifInWithList(Auth::guard('api')->user()->id , $product_id)) {
                    $in_wishList = 1;
                }
            }
            $product = Product::find($product_id);
            $prd_trn = $this->getProductTranslation($product_id);
            $cat_parent = Category::where('cat_id' , '=',$product->cat_id)->first()->cat_parent;
            $cat_parent_name = getTranslation($cat_parent , lang() , category_trans_type())->trn_text;
           /* if ($product->prd_gallery) {
                $arr = $product->prd_gallery;
                $arr = array_prepend($arr, $product->prd_image);
                $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
            } else {
                $arr = [$product->prd_image];
                $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
            }*/
            if (isset($product->prd_gallery) && $product->prd_gallery != '') {

                $arr = unserialize($product->prd_gallery);
               // dd($arr);
                $arr = array_prepend($arr, $product->prd_image);
                $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
            } else {
               // dd($product->prd_gallery);
                $arr = [$product->prd_image];
                $arr = preg_filter('/^/',$this->getFullPathProduct(), $arr);
            }

            if (array_key_exists($product->prd_id, $offers)) {
                $isDiscount = 1;
                $discount = array_get($offers, $product->prd_id);
            }
            $product->cat_parent = $cat_parent;
            $product->cat_parent_name = $cat_parent_name;
            $product->prd_images = $arr;
             $product->prd_thumbnail = url('storage/product/thumb/') . "/".$product->prd_thumbnail;
            $product->prd_name = $prd_trn->ptr_name;
            $product->prd_description = $prd_trn->ptr_description;
            $product->in_wishList = $in_wishList;
            $product->prd_isDiscount = $isDiscount;
            $product->prd_discount = $discount;
           // dd($product->prd_unit);
            $product->prd_unit = getTranslation($product->prd_unit , lang() , 'unit')->trn_text;
            $product->estimated_price = ($product->prd_unitValue."".$product->prd_unit)." * ".($product->prd_price."/".$product->prd_unit)." = ". ($product->prd_unitValue*$product->prd_price);
            $product->attribute = $this->getProductAttribute($product_id);
            $product->variation_price = $this->getProductVariationPrice($product_id);
            unset($product->prd_gallery);
            unset($product->prd_image);
            return $this->responseJson(true, 'success', $product);
        }
    }
public function getProductByBarcode(Request $request)
    {

        $rules = [
            'prd_barcode' => 'required|exists:products,prd_barcode',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {

            $prd_barcode = $request->prd_barcode;


            $in_wishList = -1;
            $isDiscount = -1;
            $discount = 0;


            $product = Product::where('prd_barcode',$prd_barcode)->first();
            $prd_id = $product->prd_id;
            $prd_trn = $this->getProductTranslation($prd_id);
            $cat_parent = Category::where('cat_id' , '=',$product->cat_id)->first()->cat_parent;
            $cat_parent_name = getTranslation($cat_parent , lang() , category_trans_type())->trn_text;
//            dd($product->prd_gallery);
            if (isset($product->prd_gallery) && $product->prd_gallery != '') {

                $arr = unserialize($product->prd_gallery);
                // dd($arr);
                $arr = array_prepend($arr, $product->prd_image);
                $arr = preg_filter('/^/', $this->getFullPathProduct(), $arr);
            } else {
                // dd($product->prd_gallery);
                $arr = [$product->prd_image];
                $arr = preg_filter('/^/',$this->getFullPathProduct(), $arr);
            }
            $product->cat_parent = $cat_parent;
            $product->cat_parent_name = $cat_parent_name;
            $product->prd_images = $arr;
             $product->prd_thumbnail = url('storage/product/thumb/') . "/".$product->prd_thumbnail;
            $product->prd_name = $prd_trn->ptr_name;
            $product->prd_description = $prd_trn->ptr_description;
            $product->in_wishList = $in_wishList;
            $product->prd_isDiscount = $isDiscount;
            $product->prd_discount = $discount;
            $product->prd_unit = getTranslation($product->prd_unit , lang() , 'unit')->trn_text;
            $product->estimated_price = ($product->prd_unitValue."".$product->prd_unit)." * ".($product->prd_price."/".$product->prd_unit)." = ". ($product->prd_unitValue*$product->prd_price);
            $product->attribute = $this->getProductAttribute($prd_id);
            // $product->variation_price = $this->getProductVariationPrice($product_id);
            unset($product->prd_gallery);
            // unset($product->prd_image);
            return $this->responseJson(true, 'success', $product);
        }
    }
    /*               */
    public function getProductTranslation($product_id)
    {
        return ProductTranslation::where('prd_id', '=', $product_id)
            ->where('lng_id', '=', lang())
            ->first();
    }

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

    public function getProductVariationPrice($product_id)
    {
        $builder = DB::table('product_variations');
        $variationPrice = $builder->where('prd_id', '=', $product_id)
            ->select('pvr_id','pvr_attributesValues', 'pvr_price' ,'pvr_isDefault' , 'pvr_isDiscount' , 'pvr_discount')
            ->get();

        $variationPrice = $variationPrice->map(function ($value) {

            $value->pvr_attributesValues = unserialize($value->pvr_attributesValues);
            return $value;
        });
        return $variationPrice;
    }

    public function getProductImages($prd_gallery, $prd_image)
    {
        $arr = $prd_gallery;
        $arr = array_push($arr, $prd_image);
        return $arr;
    }


    public function fullPathProduct($img)
    {
        return url('storage/product/img/') . "/" . $img;
    }

    public function getFullPathProduct()
    {
        return url('storage/product/img/') . "/";
    }

    public function productLessDetails($products)
    {
        $offers = Offer::select('*')->pluck('ofr_discount', 'prd_id')->toArray();
        $products = $products->map(function ($value) use ($offers) {
            $isDiscount = -1;
            $discount = 0;
            $value->prd_image = $this->fullPathProduct($value->prd_image);
             $thumbPath=url('storage/product/thumb/') . "/";
            $value->prd_thumbnail =$thumbPath. $value->prd_thumbnail;
            $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;
            $value->prd_name = $this->getProductTranslation($value->prd_id)->ptr_name;
            if (array_key_exists($value->prd_id, $offers)) {
                $isDiscount = 1;
                $discount = array_get($offers, $value->prd_id);
            }
           //  $now = new \DateTime();
          //  $now = $now->format('Y-m-d H:i:s');
              $now = Carbon::now();
            $offersvalue = Offer::select('ofr_discount')
                ->where('prd_id', '=', $value->prd_id)
                ->whereDate('ofr_start', '<=', $now)
                ->whereDate('ofr_end', '>=', $now)
                ->orderByDesc('ofr_creation_datetime')->first();

            $value->prd_unit = getTranslation($value->prd_unit , lang() , 'unit')->trn_text;
             $value->prd_isDiscount = (isset($offersvalue)?1:-1);
            $value->prd_discount = ($offersvalue['ofr_discount'] != null?$offersvalue['ofr_discount']:0);
          //  $value->prd_isDiscount = $isDiscount;
          //  $value->prd_discount = $discount;
            unset($value->prd_gallery);
            return $value;
        });
        return $products;
    }

    public function productDetails($products)
    {
        $products = $products->map(function ($value) {
            $value->prd_image = $this->fullPathProduct($value->prd_image);
            $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;
            $value->prd_name = $this->getProductTranslation($value->prd_id)->ptr_name;
            $value->prd_isDiscount = -1;
            return $value;
        });
        return $products;
    }


    public function test()
    {
        /* $product_attribute = DB::table('product_attributes');
         $product_attribute->insert([
             'prd_id' => 1 ,
             'atr_id' => 93 ,
             'pat_values' => serialize([138 , 139]) ,
             'pat_isVariation' => -1
         ]);
         $product_attribute->insert([
             'prd_id' => 1 ,
             'atr_id' => 109 ,
             'pat_values' => serialize([164 , 165]) ,
             'pat_isVariation' => -1
         ]);
 */
/*
         $product_variations = DB::table('product_variations');
         $product_variations->insert([
             'prd_id' => 1 ,
             'pvr_attributesValues' => serialize([138 , 164]) ,
             'pvr_price' => 10
         ]);
         $product_variations->insert([
             'prd_id' => 1 ,
             'pvr_attributesValues' => serialize([138 , 165]) ,
             'pvr_price' => 12
         ]);
         $product_variations->insert([
             'prd_id' => 1 ,
             'pvr_attributesValues' => serialize([139 , 164]) ,
             'pvr_price' => 14
         ]);
         $product_variations->insert([
             'prd_id' => 20 ,
             'pvr_attributesValues' => serialize([139 , 165]) ,
             'pvr_price' => -1
         ]);
 */


        /* $arr = [];
         $arr[]= "ikTdqIo2DldEi5yAtXpiiBtTIjit9svbB66yrdz71536580045.png";
         $arr[]= "wv2bMEzZjYFfnZmKhwn1LEdQAo0zlmRKjS7ttLSl1536600451.jpg";
         $product = Product::find(1);
         $product->prd_gallery = serialize($arr);
        */



        $arrImg = ['prime-rib-400x225.jpg'];
        $prod = Product::create([
            'prd_image' => 'Strip_Steak_small_345x345@2x.jpg',
            'prd_price' => 30,
            'prd_isVariable' => 1,
            'prd_gallery' => serialize($arrImg),
            'cat_id' => 16,
        ]);

        $product = Product::find($prod->prd_id);
        $product->prd_gallery = serialize($arrImg);
        $product->update();

        ProductTranslation::create([
            'prd_id' => $prod->prd_id,
            'ptr_name' =>'meet' ,
             'ptr_description' => 'meet',
             'lng_id' => 1,
         ]);

        ProductTranslation::create([
            'prd_id' => $prod->prd_id,
            'ptr_name' =>'لحمة' ,
             'ptr_description' => "لحمة",
             'lng_id' => 2,
         ]);

        $arr = [138];
        DB::table('product_attributes')->insert([
            'prd_id' => $prod->prd_id,
            'atr_id' => 93,
            'pat_values' => serialize($arr)
        ]);
        $arr = [165, 166];
        DB::table('product_attributes')->insert([
            'prd_id' => $prod->prd_id,
            'atr_id' => 109,
            'pat_values' => serialize($arr)
        ]);

        $arr = [138, 165];
        DB::table('product_variations')->insert([
            'prd_id' => $prod->prd_id,
            'pvr_attributesValues' => serialize($arr),
            'pvr_price' => 42
        ]);

        $arr = [138, 166];
        DB::table('product_variations')->insert([
            'prd_id' => $prod->prd_id,
            'pvr_attributesValues' => serialize($arr),
            'pvr_price' => 50
        ]);

    }

}
