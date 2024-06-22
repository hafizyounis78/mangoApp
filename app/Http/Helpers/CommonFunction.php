<?php

use App\Category;
use App\Translation;
use App\Attribute;
use App\AttributeValue;
use App\Instruction;
use Illuminate\Support\Facades\DB;
use App\City;

function offer_trans_type()
{
    return "offers";
}

function category_trans_type()
{
    return "categories";
}

function attribute_trans_type()
{
    return "attributes";
}

function attribute_value_trans_type()
{
    return "attribute_values";
}

function instruction_trans_type()
{
    return "instructions";
}

function city_trans_type()
{
    return 'city';
}

function lookup_trans_type()
{
    return 'lookup';
}

function lang()
{
    // return 1;
    $lang = request()->header('lang');

    if (request()->hasHeader('lang'))
        return \App\Language::where('lng_slug', '=', $lang)->first()->lng_id;
    return \App\Language::where('lng_slug', '=', app()->getLocale())->first()->lng_id;
    // return 1;
    //return \App\Language::where('lng_slug', '=', app()->getLocale())->first()->lng_id;
}

function getLang($lang)
{
    return \App\Language::where('lng_slug', '=', $lang)->first();
}

function getLanguages()
{
    return \App\Language::all();
}

/*  Category */
function getAllCategories($status)
{

    if ($status == "all") {
        $category = Category::all()
            ->where('cat_isDeleted', '=', -1);
    } else if ($status == 0) {
        $category = Category::where('cat_parent', '=', 0)
            ->where('cat_isDeleted', '=', -1)
            ->get();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $category = Category::where('cat_isDeleted', '=', $delete)
            ->get();

    }

    $category = $category->map(function ($value) {
        $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;

        if ($value->cat_parent == 0) {

            $value->parent_name = trans('category.no_parent');
        } else {

            $value->parent_name = Translation::select('*')
                ->where('trn_type', '=', category_trans_type())
                ->where('lng_id', '=', lang())
                ->where('trn_foreignKey', '=', $value->cat_parent)
                ->first()->trn_text;

        }

        //dd($value);
        return $value;
    });

    return $category;
}

function getAllCategoriesold($status)
{

    if ($status == "all") {
        $category = Category::all();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $category = Category::where('cat_isDeleted', '=', $delete)
            ->get();

    }

    $category = $category->map(function ($value) {
        $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;

        if ($value->cat_parent == 0) {
            $value->parent_name = trans('category.no_parent');
        } else {
            $value->parent_name = Translation::select('*')
                ->where('trn_type', '=', category_trans_type())
                ->where('lng_id', '=', lang())
                ->where('trn_foreignKey', '=', $value->cat_parent)
                ->first()->trn_text;
        }


        return $value;
    });

    return $category;
}

function getAllParentCategories($status)
{

    if ($status == "all") {
        $category = Category::where('cat_parent', '=', 0)->get();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $category = Category::where('cat_isDeleted', '=', $delete)
            ->where('cat_parent', '=', 0)
            ->get();

    }

    $category = $category->map(function ($value) {
        $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;

        if ($value->cat_parent == 0) {
            $value->parent_name = trans('category.no_parent');
        } else {
            $value->parent_name = Translation::select('*')
                ->where('trn_type', '=', category_trans_type())
                ->where('lng_id', '=', lang())
                ->where('trn_foreignKey', '=', $value->cat_parent)
                ->first()->trn_text;
        }


        return $value;
    });

    return $category;
}

function getAllSubCategories($status)
{

    if ($status == "all") {
        $category = Category::where('cat_parent', '!=', 0)->get();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $category = Category::where('cat_isDeleted', '=', $delete)
            ->where('cat_parent', '!=', 0)
            ->get();

    }

    $category = $category->map(function ($value) {
        $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;

        if ($value->cat_parent == 0) {
            $value->parent_name = trans('category.no_parent');
        } else {
            $value->parent_name = Translation::select('*')
                ->where('trn_type', '=', category_trans_type())
                ->where('lng_id', '=', lang())
                ->where('trn_foreignKey', '=', $value->cat_parent)
                ->first()->trn_text;
        }


        return $value;
    });

    return $category;
}

function getCategory($id)
{
    $category = Category::where('cat_id', '=', $id)->first();
    $category->trans = Translation::where('trn_type', '=', category_trans_type())
        ->where('trn_foreignKey', '=', $id)
        ->get();
    return $category;

}

function getSubCategory($parent, $status)
{
    if ($parent == -1) {
        $parent = 0;
    }

    if ($status == "all") {
        $category = Category::where('cat_parent', '=', $parent)
            ->where('cat_isDeleted', '=', -1)
            ->get();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $category = Category::where('cat_parent', '=', $parent)
            ->where('cat_isDeleted', '=', $delete)
            ->get();
    }


    $category = $category->map(function ($value) {
        $value->cat_name = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;

        if ($value->cat_parent == 0) {
            $value->parent_name = trans('category.no_parent');
        } else {
            $value->parent_name = Translation::select('*')
                ->where('trn_type', '=', category_trans_type())
                ->where('lng_id', '=', lang())
                ->where('trn_foreignKey', '=', $value->cat_parent)
                ->first()->trn_text;
        }


        return $value;
    });

    return $category;
}

/*   */

/*       Attribute        */
function getAllAttributes($status)
{


    if ($status == "all") {
        $attribute = Attribute::all();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $attribute = Attribute::where('atr_isDeleted', '=', $delete)->get();

    }

    $attribute = $attribute->map(function ($value) {
        $trans = getTranslation($value->atr_id, lang(), attribute_trans_type());
        $value->atr_name =(isset($trans)&& $trans!=null )?$trans->trn_text:'';
        return $value;
    });

    return $attribute;
}

function getAttribute($atr_id)
{
    return Translation::select('lng_id', 'trn_text')
        ->where('trn_type', '=', attribute_trans_type())
        ->where('trn_foreignKey', '=', $atr_id)
        ->get();
}

function getAttributeValuesForAttr($atr_id)
{
    $attribute_values = AttributeValue::where('atr_id', '=', $atr_id)->get();
    $attribute_values = $attribute_values->map(function ($value) {
        $trans =Translation::select('*')
            ->where('trn_type', '=', attribute_value_trans_type())
            ->where('lng_id', '=', lang())
            ->where('trn_foreignKey', '=', $value->atv_id)
            ->first();
        $value->text =(isset($trans)&& $trans!=null )?$trans->trn_text:'';

        return $value;
    });

    return $attribute_values;
}

function getAttributeValuesForAttr2($atr_id)
{
    $attribute_values = AttributeValue::where('atr_id', '=', $atr_id)->get();

    $attribute_values = $attribute_values->map(function ($value) {
        $value->text = Translation::select('lng_id', 'trn_text')
            ->where('trn_type', '=', attribute_value_trans_type())
            ->where('trn_foreignKey', '=', $value->atv_id)
            ->get();

        return $value;
    });

    return $attribute_values;
}

function getAllAttributeValues($status)
{


    if ($status == "all") {
        $attribute = AttributeValue::all();
    } else {
        if ($status == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }
        $attribute = Attribute::where('atv_isDeleted', '=', $delete)->get();

    }

    $trans_attribute = Translation::select('*')
        ->where('trn_type', '=', attribute_value_trans_type())
        ->where('lng_id', '=', lang());


    $attribute = $attribute->map(function ($value) use ($trans_attribute) {
        $trn = Translation::where('trn_type', '=', attribute_value_trans_type());

        $value->cat_name = $trn->where('trn_foreignKey', '=', $value->atv_id)
            ->first()->trn_text;

        if ($value->cat_parent == 0) {
            $value->parent_name = "No parent";
        } else {
            $value->parent_name = Translation::select('*')
                ->where('trn_type', '=', attribute_value_trans_type())
                ->where('lng_id', '=', lang())
                ->where('trn_foreignKey', '=', $value->atv_id)
                ->first()->trn_text;
        }


        return $value;
    });

    return $attribute;
}

/*       */


/*      instructions   */
function getAllInstructions($status)
{


    if ($status == "all") {
        $instructions = Instruction::all();
    } else if ($status == 1) {
        $instructions = Instruction::where('isActive', '=', 1)->get();
    } else {
        $instructions = Instruction::where('isActive', '=', 1)->get();
    }


    $instructions = $instructions->map(function ($value) {
        $trn = Translation::where('trn_type', '=', instruction_trans_type())
            ->where('lng_id', '=', lang());

        $value->title = $trn->where('trn_foreignKey', '=', $value->id)
            ->first()->trn_text;

        $value->description = $trn->where('trn_foreignKey', '=', $value->id)
            ->first()->trn_desc;


        return $value;
    });

    return $instructions;
}

function getInstruction($id)
{
    $category = Instruction::find($id);
    $category->trans = Translation::where('trn_type', '=', instruction_trans_type())
        ->where('trn_foreignKey', '=', $id)
        ->get();
    return $category;

}

/*         */


/*      city    */
function getAllCity($status)
{

    if ($status == "all") {
        $cities = City::all();
    } else {
        $cities = City::select('*')->where('cit_isActive', '=', $status)->get();
    }


    $cities = $cities->map(function ($value) {
        $value->city_name = getTranslation($value->cit_id, lang(), city_trans_type())->trn_text;
        return $value;
    });
    return $cities;

}

function getCityData($id)
{
    return Translation::select('lng_id', 'trn_text')
        ->where('trn_type', '=', city_trans_type())
        ->where('trn_foreignKey', '=', $id)
        ->get();
}

/*          */
function getTranslation($trn_foreignKey, $lang_id, $trn_type)
{

    $trans = Translation::where('trn_foreignKey', '=', $trn_foreignKey)
        ->where('lng_id', '=', $lang_id)
        ->where('trn_type', '=', $trn_type)
        ->first();
    if (isset($trans))
        return $trans;
    return null;
}


function getRawLookupSelect($table, $column, $type, $as = NULL, $lang = NULL)
{
    $lang = (empty($lang)) ? lang() : $lang;

    $as = (empty($as)) ? $column : $as;

    $where = "translations.trn_foreignKey = " . $table . "." . $column . " AND translations.lng_id = " . $lang .
        " AND translations.trn_type = '" . $type . "'";

    $exp = DB::table('translations')->select('trn_text')->whereRaw($where);

    return DB::raw("(" . $exp->toSql() . ") AS " . $as);
}

function getLookupType($table, $column, $type, $lang = NULL, $toArray = false, $withNullOption = false, $whereColumn = NULL, $whereValue = NULL)
{
    $lang = (empty($lang)) ? lang() : $lang;

    $builder = DB::table($table)
        ->select($column, 'trn_text')
        ->join('translations', $column, '=', 'trn_foreignKey')
        ->where('trn_type', '=', $type)
        ->where('lng_id', '=', $lang);

    if (!empty($whereColumn) && !empty($whereValue)) {
        $builder->where($whereColumn, '=', $whereValue);
    }

    $collec = $builder->get();

    return ($toArray) ? eloquentToArray($collec, $column, 'trn_text', $withNullOption) : $collec;
}

function eloquentToArray($models, $keyProp, $valueProp, $nullOption = false)
{
    $arr = array();
    if ($nullOption) {
        $arr[NULL] = '...';
    }
    foreach ($models as $m) {
        $arr[$m->$keyProp] = $m->$valueProp;
    }
    return $arr;
}

function indexedArrayOfEloquent($models, $indexProp)
{
    $arr = array();
    foreach ($models as $m) {
        $arr[$m->$indexProp] = $m;
    }
    return $arr;
}

//

// helper
function max_pagination()
{
    return 10.0;
}

function page_count($num_object)
{
    return ceil($num_object / max_pagination());
}

function response_api($status, $object = null, $page_count = null, $page = null, $another_data = null)
{
    //   dd($errors);
    $error = ['status' => false, 'message' => trans('api.error')];
    $success = ['status' => true, 'message' => trans('api.success')];
    if ($status && isset($object))
        $success['data'] = $object;
    elseif (!$status && isset($errors))
        $error['errors'] = $errors;

    if (isset($page_count) && isset($page)) {
        $success['page_count'] = $page_count;
        $success['page'] = $page;
    }
    if (isset($another_data))
        foreach ($another_data as $key => $value)
            $success [$key] = $value;

    $response = ($status) ? $success : $error;
//dd($totalNetAmount);
    return response()->json($response);
}

function responseJson($status, $message, $data, $status_code = 200, $var = null)
{
    $arr = [];
    $arr['status'] = $status;
    $arr['message'] = $var == null ? trans('api.' . $message) : trans('api.' . $message, ['var' => $var]);;

    $arr['status_code'] = $status_code;
    $arr['data'] = $data;

    return response()->json($arr);
}

function getFullPathProduct()
{
    return url('storage/product/img/') . "/";
}

function getFullPathUser()
{
    return url('storage');
}

function getProductTranslation($product_id)
{
    return \App\ProductTranslation::where('prd_id', '=', $product_id)
        ->where('lng_id', '=', lang())
        ->first();
}

