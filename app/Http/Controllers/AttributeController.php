<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeValue;
use App\Translation;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Auth;

class AttributeController extends Controller
{

    protected $data;
    public $languages;

    public function __construct()
    {
        $this->middleware('role:admin');
        $this->data['menu'] = 'attribute';
        $this->data['selected'] = 'attribute';
        $this->data['location'] = "attribute";
        $this->data['location_title'] = trans('attributes.attributes');
        $this->data['languages'] = getLanguages();
        $this->data['categories'] = getAllAttributes("all");
    }


    public function index()
    {

        $this->data['sub_menu'] = 'attribute';
        $this->data['location_title'] = trans('attributes.attribute_display');

        return view('attribute.index', $this->data);
    }

    public function index2()
    {

        $this->data['sub_menu'] = 'attribute';
        $this->data['location_title'] = trans('category.display_categories');
        $this->data['attributes'] = getAllAttributes("all");

        return view('test', $this->data);
    }

    public function create()
    {

        $this->data['sub_menu'] = 'category-create';
        $this->data['location_title'] = trans('category.add_categories');

        return view('category.create', $this->data);
    }

    public function store(Request $request)
    {
        $rules = [];

        $langs = getLanguages();
        foreach ($langs as $lang) {
            $rules['name_' . $lang->lng_id] = "required";
        }
        $rules['image'] = 'required';
        $rules['parent'] = 'required';
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {

            $image = Input::file('image');
            $path = $this->storeImage($image, '/category/img/', false);

            $category = Category::create([
                'cat_image' => $path,
                'cat_parent' => $request->parent
            ]);

            foreach ($langs as $lang) {
                $name = "name_" . $lang->lng_id;
                $req_name = $request->$name;
                Translation::create([
                    'trn_foreignKey' => $category->id,
                    'lng_id' => $lang->lng_id,
                    'trn_type' => $this->category_trans_type,
                    'trn_text' => $req_name
                ]);

            }

            return redirect()->route('category.index')->with('status', 'The adding is done');


        }


    }

    public function edit($id)
    {

        $this->data['sub_menu'] = 'category-edit';
        $this->data['location_title'] = trans('category.edit_category');
        $category = getCategory($id);
        $arr_trn_name = [];
        foreach ($category->trans as $cat) {
            $arr_trn_name['name_' . $cat->lng_id] = $cat->trn_text;
        }

        return view('category.edit', $this->data, ['category' => $category, 'arr_trn_name' => $arr_trn_name]);
    }

    public function update(Request $request, $id)
    {


        $rules = [];

        $langs = getLanguages();
        foreach ($langs as $lang) {
            $rules['name_' . $lang->lng_id] = "required";
        }
        $rules['parent'] = 'required';
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {


            $image = Input::file('image');
            $path = Category::where('cat_id', $id)->first()->cat_image;
            if ($request->hasFile('image')) {
                $path = $this->storeImage($image, '/category/img/', false);
            }


            $category = Category::where('cat_id', $id)->update([
                'cat_image' => $path
            ]);

            foreach ($langs as $lang) {
                $name = "name_" . $lang->lng_id;
                $req_name = $request->$name;
                Translation::where('trn_foreignKey', '=', $id)
                    //   ->where('trn_type' , '=' , att)
                    ->where('lng_id', '=', $lang->lng_id)
                    ->update([
                        'trn_text' => $req_name
                    ]);


            }

            return redirect()->route('category.index')->with('status', 'The adding is done');


        }
    }


    public function contentListData(Request $request)
    {

        $attributes = getAllAttributes("1");

        $GLOBALS['index'] = 0;
        return datatables($attributes)
            ->setRowId(function ($model) {
                return "row-" . $model->atr_id;
                // via closure
            })
            ->addColumn('atr_name', function ($model) {
                return $model->atr_name;

            })
            ->addColumn('attr_value_name', function ($model) {
                $attr_value = getAttributeValuesForAttr($model->atr_id);
                $element = "";
                foreach ($attr_value as $v) {
                    $element .= "<span style='margin: 5px;' class='label label-lg label-info'>$v->text </span>";
                }

                return $element;
            })->EditColumn('created_at', function ($model) {
                $date = date('d-m-Y', strtotime($model->created_at));
                return $date;

            })->addColumn('control', function ($model) {
                $id = $model->atr_id;

                return "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='atr_id_hidden' value='$id'>
                         <a class='btn btn-primary btn-sm edit'><i class='fa fa-pencil' title='تعديل'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
                 </div><div class='col-xs-6' style='padding-left: 3px!important;'>
                                <a class='btn btn-danger btn-sm delete' onclick='delAttr($id)' title='حذف'><input type = 'hidden' class='id_hidden' value = '" . $id . "' > <i class='fa fa-remove' ></i ></a >
                            </div>" .
                    "
               
               ";
            })
            ->rawColumns([ 'control','attr_value_name'])
            ->toJson();

    }

    public function contentListData2(Request $request)
    {
        //$category = Category::all();
        // return Datatables::of($category)->make(true);

        $GLOBALS['index'] = 0;
        return Datatables::of(Category::where('name', 'LIKE', "$request->name%")->get())
            ->setRowId(function ($model) {
                return "row-" . $model->id;
                // via closure
            })
            ->addColumn('id', function ($model) {
                $GLOBALS['index'] += 1;
                return $GLOBALS['index'];
            })
            ->addColumn('name', function ($model) {
                return $model->name;

            })->addColumn('image', function ($model) {
                $getPath = $model->image;
                // $getPath = str_replace('public', '', $getPath);
                $getPath = url('storage') . $getPath;
                return "<div><input type='hidden' class='image' value='" . $getPath . "'> <img src='" . $getPath . "' width='50' height='50'></div>";
            })
            ->addColumn('description', function ($model) {
                return $model->description;

            })->addColumn('date', function ($model) {
                $date = date('d-m-Y', strtotime($model->created_at));
                return $date;

            })
            ->addColumn('control', function ($model) {
                $id = $model->id;
                return "
                 <div class='col-xs-6' style='width: 35%!important;'>
                         <a class='btn btn-primary btn-sm' href='" . url("category/" . $id . "/edit") . "' title='حذف'><i class='fa fa-pencil'></i></a>
                 </div>"
                    . "
                <div class='col-xs-6' style='padding-left: 13px!important;'>
                       <a class='btn btn-danger btn-sm delete'>
                       <input type='hidden' class='id_hidden' value='" . $id . " '>
                       <i class='fa fa-remove'></i></a>
                </div>";
            })
            /* ->filter(function ($query) {
                 $user = Auth::user();
                 if (!$user->hasRole('admin')) {
                     $query->where('user_id', "=", Auth::user()->id);
                 }
             })*/
            ->make(true);

    }

    public function getTreeCategories($cat_id)
    {

        $child = Category::where('cat_parent', '=', $cat_id)->get();
        $child = $child->map(function ($value) {
            $value->text = getTranslation($value->cat_id, lang(), category_trans_type())->trn_text;
            $value->subChild = Category::where('cat_parent', '=', $value->cat_id)->get();
            $value->subChild->map(function ($value2) {
                $value2->text = getTranslation($value2->cat_id, lang(), category_trans_type())->trn_text;
                return $value2;
            });
            return $value;
        });

        return $child;
    }

    public function destroy($id)
    {

        $category = Category::find($id);
        if ($category) {
            $category->delete();
            $getPath = $category->image;
            $getPath = public_path() . "/storage" . $getPath;
            File::delete($getPath);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => 'The category not found']);
        }


    }

    public function addAttribute(Request $request)
    {

        $langs = getLanguages();
        $arr_atr_value = json_decode($request->json_arr_atr_value);
        $arr_atr = json_decode($request->json_arr_atr);
        $is_sizeAttribute = $request->is_sizeAttribute;

        $success = 1;
        foreach ($arr_atr_value as $atr_value_check) {
            if (empty($atr_value_check->name)) {
                $success = -1;
                return response()->json($success);
            }
        }
        foreach ($arr_atr as $atr_check) {
            if (empty($atr_check->name)) {
                $success = -1;
                return response()->json($success);
            }
        }
        $arr_atr_collect = collect($arr_atr);


        $atr = Attribute::create([
            'atr_isSizeAttribute' => $is_sizeAttribute
        ]);


        foreach ($langs as $lang) {

            $req_name = $arr_atr_collect->where('lang', '=', $lang->lng_id)
                ->first()
                ->name;

            Translation::create([
                'trn_foreignKey' => $atr->atr_id,
                'lng_id' => $lang->lng_id,
                'trn_type' => attribute_trans_type(),
                'trn_text' => $req_name
            ]);

        }


        if (count($arr_atr_value) > 0) {
            for ($i = 0; $i < count($arr_atr_value); $i += 2) {
                $atr_value = AttributeValue::create([
                    'atr_id' => $atr->atr_id
                ]);

                $req_name1 = $arr_atr_value[$i]->name;
                $req_lang1 = $arr_atr_value[$i]->lang;

                $req_name2 = $arr_atr_value[$i + 1]->name;
                $req_lang2 = $arr_atr_value[$i + 1]->lang;

                Translation::create([
                    'trn_foreignKey' => $atr_value->id,
                    'lng_id' => $req_lang1,
                    'trn_type' => attribute_value_trans_type(),
                    'trn_text' => $req_name1
                ]);

                Translation::create([
                    'trn_foreignKey' => $atr_value->id,
                    'lng_id' => $req_lang2,
                    'trn_type' => attribute_value_trans_type(),
                    'trn_text' => $req_name2
                ]);

            }
        }
        return response()->json($success);

    }

    public function editAttribute(Request $request)
    {

        $langs = getLanguages();
        $arr_atr_value = json_decode($request->json_arr_atr_value);
        $arr_atr = json_decode($request->json_arr_atr);
        $is_sizeAttribute = $request->is_sizeAttribute;

        $success = 1;
        foreach ($arr_atr_value as $atr_value_check) {
            if (empty($atr_value_check->name)) {
                $success = -1;
                return response()->json($success);
            }
        }
        foreach ($arr_atr as $atr_check) {
            if (empty($atr_check->name)) {
                $success = -1;
                return response()->json($success);
            }
        }
        $arr_atr_collect = collect($arr_atr);

        $atr = Attribute::find($request->id);
        $atr->update([
            'atr_isSizeAttribute' => $is_sizeAttribute
        ]);

        AttributeValue::where('atr_id', '=', $request->id)->delete();

        foreach ($langs as $lang) {

            $req_name = $arr_atr_collect->where('lang', '=', $lang->lng_id)
                ->first()
                ->name;

            Translation::where('trn_foreignKey', '=', $atr->atr_id)
                ->where('lng_id', '=', $lang->lng_id)
                ->where('trn_type', '=', attribute_trans_type())
                ->update([
                    'trn_text' => $req_name
                ]);

        }


        if (count($arr_atr_value) > 0) {
            for ($i = 0; $i < count($arr_atr_value); $i += 2) {
                $atr_value = AttributeValue::create([
                    'atr_id' => $atr->atr_id
                ]);

                $req_name1 = $arr_atr_value[$i]->name;
                $req_lang1 = $arr_atr_value[$i]->lang;

                $req_name2 = $arr_atr_value[$i + 1]->name;
                $req_lang2 = $arr_atr_value[$i + 1]->lang;

                Translation::create([
                    'trn_foreignKey' => $atr_value->atv_id,
                    'lng_id' => $req_lang1,
                    'trn_type' => attribute_value_trans_type(),
                    'trn_text' => $req_name1
                ]);

                Translation::create([
                    'trn_foreignKey' => $atr_value->atv_id,
                    'lng_id' => $req_lang2,
                    'trn_type' => attribute_value_trans_type(),
                    'trn_text' => $req_name2
                ]);

            }
        }
        return response()->json($success);
        /*
                $atr_id = $request->id;
                $langs = getLanguages();
                $arr_atr_value = json_decode($request->json_arr_atr_value);
                $arr_atr = json_decode($request->json_arr_atr);

                $arr_atr_collect = collect($arr_atr);

                Translation::where('trn_foreignKey' , '=' , $atr_id)
                    ->where('trn_type' , '=' , attribute_trans_type())->delete();

                foreach ($langs as $lang) {

                    $req_name = $arr_atr_collect->where('lang', '=', $lang->lng_id)
                        ->first()
                        ->name;

                    Translation::create([
                        'trn_foreignKey' => $atr_id,
                        'lng_id' => $lang->lng_id,
                        'trn_type' => attribute_trans_type(),
                        'trn_text' => $req_name
                    ]);
                }

                if(count($arr_atr_value) > 0) {

                    $attribute_values_to_del = AttributeValue::where('atr_id' , '=' ,$atr_id)->pluck('atv_id')->toArray();
                    Translation::whereIn('trn_foreignKey' , '=' , $attribute_values_to_del)
                        ->where('trn_type' , '=' , attribute_value_trans_type())->delete();

                    AttributeValue::where('atr_id' , '=' ,$atr_id)->delete();


                    for ($i = 0; $i < count($arr_atr_value); $i+=2) {

                        $atr_value = AttributeValue::create([
                            'atr_id' => $atr_id
                        ]);


                        $req_name1 = $arr_atr_value[$i]->name;
                        $req_lang1 = $arr_atr_value[$i]->lang;

                        $req_name2 = $arr_atr_value[$i+1]->name;
                        $req_lang2 = $arr_atr_value[$i+1]->lang;



                        Translation::create([
                            'trn_foreignKey' => $atr_value->atv_id,
                            'lng_id' =>$req_lang1,
                            'trn_type' => attribute_value_trans_type(),
                            'trn_text' => $req_name1
                        ]);

                        Translation::create([
                            'trn_foreignKey' => $atr_value->atv_id,
                            'lng_id' => $req_lang2,
                            'trn_type' => attribute_value_trans_type(),
                            'trn_text' => $req_name2
                        ]);

                    }

                }
        */
    }

    public function getAttributeData(Request $request)
    {

        $attribute_values = getAttributeValuesForAttr2($request->id);
        $attribute = getAttribute($request->id);

        $arr = ['atr' => $attribute, 'atv' => $attribute_values, 'atr_isSizeAttribute' => Attribute::find($request->id)->atr_isSizeAttribute];
        return response()->json($arr);
    }

    public function statusCategory(Request $request)
    {
        $cat_id = $request->id;
        $isActive = $request->active;


        if ($isActive == 1) {
            $delete = -1;
        } else {
            $delete = 1;
        }

        $user = Category::where('cat_id', '=', $cat_id);
        $user->update([
            'cat_isDeleted' => $delete,
        ]);
        return response()->json(['data' => 2]);


    }

public function delAttr(Request $request)
    {
       // dd($request->id);
        $id=$request->id;
        $attribute = Attribute::find($id);
        if ($attribute) {
            $attribute->atr_isDeleted=1;
            $attribute->save();
            $attribute_values = AttributeValue::where('atr_id','=',$id)->get();
            if ($attribute_values) {
                foreach ($attribute_values as $attribute_value)
                {
                    //dd($attribute_value->atv_id);
                    $attribute_value->atv_isDeleted=1;
                    $attribute_value->save();
                }
            }
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }


    }
}
