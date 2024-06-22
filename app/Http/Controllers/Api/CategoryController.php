<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Offer;
use App\Product;
use App\Translation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public $product;
    public function __construct()
    {
        $this->product = new ProductController();
    }

    public function categories()
    {


        $lang = getLang(app()->getLocale());
        return $this->responseJson(true, 'success', $this->getAllCategoriesWithoutSub($lang->lng_id));

    }
    public function categoriesWithSub()
    {


        $lang = getLang(app()->getLocale());
        return $this->responseJson(true, 'success', $this->getAllCategoriesWithSub($lang->lng_id));

    }
    public function subCategory(Request $request)
    {


        $lang = getLang(app()->getLocale());
        $rules = [];
        if($request->exists('category_id') && $request->category_id != 0) {
            /*$rules = [
                'category_id' => 'required|numeric|exists:categories,cat_id',
            ];*/
            $rules['category_id'] = 'required|numeric|exists:categories,cat_id';
        }


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {
            return $this->responseJson(true, 'success', $this->getSubCategory($request->category_id ,$lang->lng_id));
        }



    }


    public function getAllCategoriesWithoutSub($lang)
    {
        $categories = Category::where('cat_isDeleted', '=', -1)
            ->where('cat_parent' , '=' , 0)
            ->select('cat_id', 'cat_image', 'cat_parent')
            ->get();

        $categories = $categories->map(function ($value) use ($lang) {
            $trn = getTranslation($value->cat_id, $lang, category_trans_type());
            $value->cat_image = $this->fullPath($value->cat_image);
            $value->name = $trn->trn_text;
            return $value;
        });

        $categories = $categories->toArray();
        $offer = [
            'cat_id' => 0 ,
            'cat_image' => "",
            'cat_parent' => 0,
            'name' => trans('api.offers'),


        ];
        $categories = array_prepend($categories ,$offer );
        return $categories;
    }
    public function getAllCategoriesWithSub($lang)
    {
        $categories = Category::where('cat_isDeleted', '=', -1)
            ->where('cat_parent' , '=' , 0)
            ->select('cat_id', 'cat_image', 'cat_parent')
            ->get();

        $categories = $categories->map(function ($value) use ($lang) {
            $trn = getTranslation($value->cat_id, $lang, category_trans_type());
           $value->cat_image = $this->fullPath($value->cat_image);
          //    $value->cat_image = $value->cat_image;
            $value->name = $trn->trn_text;
            $value->subCategory = $this->getSubCategory($value->cat_id, $lang);
           /* $value->subCategory->map(function ($value2) use ($lang) {
                $value2->subSubCategory = $this->getSubCategory($value2->cat_id, $lang);
                return $value2;
            });*/
            return $value;
        });
        $categories = $categories->toArray();
       /* $offer = [
            'cat_id' => 0 ,
            'cat_image' => "",
            'cat_parent' => 0,
            'name' => trans('api.offers'),
            'subCategory' => []


        ];*/
      //  $categories = array_prepend($categories ,$offer );
        return $categories;
    }
    public function getSubCategory($parent, $lang)
    {

        if($parent == 0) {

           $offers = Offer::select('*')
                ->whereDate('ofr_end' , '>=' , Carbon::now())
                ->get();

            $offers = $offers->map(function($value) {
                $value->ofr_name = getTranslation($value->ofr_id , lang() , offer_trans_type())->trn_text;
                $value->products = $value->products;
                $value->products->prd_unit = getTranslation($value->products->prd_unit , lang() , lookup_trans_type())->trn_text;
                $value->products->prd_image = $this->product->fullPathProduct($value->products->prd_image);
               // $value->products->prd_name = $this->product->getProductTranslation($value->products->prd_id)->ptr_name;
                 $value->products->prd_image = $value->products->prd_image;
              //  $value->products->after_discount = (Double)(number_format(($value->ofr_discount/100)*$value->products->prd_price , 2));
                unset($value->products->prd_gallery);
                return $value;
            });

           return $offers;

        }else {
            $subCategory = Category::where('cat_parent', '=', $parent)
                ->where('cat_isDeleted', '=', -1)
                ->select('cat_id', 'cat_image' , 'cat_parent')
                ->get();

            $categoryTrans = Translation::where('lng_id' , '=' , lang())
                                         ->where('trn_type' , '=' , category_trans_type())
                                         ->get();
            $subCategory = $subCategory->map(function ($value) use ($lang , $categoryTrans) {
                $trn = getTranslation($value->cat_id, $lang, category_trans_type());
                $value->cat_parent_name = $categoryTrans->where('trn_foreignKey','=',$value->cat_parent)->first()->trn_text;
                $value->cat_image = $this->fullPath($value->cat_image);
                $value->cat_name =  $categoryTrans->where('trn_foreignKey','=',$value->cat_id)->first()->trn_text;
                return $value;
            });
            return $subCategory;
        }



    }

}
