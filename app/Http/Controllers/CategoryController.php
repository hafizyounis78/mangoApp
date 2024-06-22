<?php

namespace App\Http\Controllers;
use App\Product;
use App\Translation;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Auth;

class CategoryController extends Controller
{

    protected $data;
    public $objCategory;
    public $languages;

    public function __construct()
    {
        $this->middleware('role:admin');
        $this->objCategory = new Category();
        $this->data['menu'] = 'category';
        $this->data['selected'] = 'category';
        $this->data['location'] = "category";
        $this->data['location_title'] = trans('category.categories');
        $this->data['languages'] = getLanguages();
        $this->data['categories'] =getAllCategories("0");
        $this->data['parentCategories'] = getAllParentCategories("all");
    }


    public function index()
    {
        $this->data['sub_menu'] = 'category-display';
        $this->data['location_title'] = trans('category.display_categories');

        return view('category.index', $this->data);
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
        //$rules['name'] = 'required';
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {

            $image = Input::file('image');
            $path = $this->storeImage($image, '/category/img/', false);

            $category =new Category();
            $category->cat_image = $path;
            $category->cat_parent = 0;
                //'cat_parent' => $request->parent
            $category->save();
           // dd($category->cat_id);

            foreach ($langs as $lang) {
                $name = "name_" . $lang->lng_id;
                $req_name = $request->$name;
                Translation::create([
                    'trn_foreignKey' => $category->cat_id,
                    'lng_id' => $lang->lng_id,
                    'trn_type' => category_trans_type(),
                    'trn_text' => $req_name
                ]);

            }

            return redirect()->route('category.index')->with('status', trans('category.add_success'));


        }


    }

    public function edit($id)
    {

        $this->data['sub_menu'] = 'category-edit';
        $this->data['location_title'] =trans('category.edit_category');
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
            if($request->hasFile('image')) {
                $path = $this->storeImage($image, '/category/img/', false);
            }



            $category = Category::where('cat_id', $id)->update([
                'cat_image' => $path ,
                'cat_parent' => $request->parent
            ]);

            foreach ($langs as $lang) {
                $name = "name_" . $lang->lng_id;
                $req_name = $request->$name;
                Translation::where('trn_foreignKey', '=', $id)
                    ->where('trn_type' , '=' , category_trans_type())
                    ->where('lng_id', '=', $lang->lng_id)
                    ->update([
                        'trn_text' => $req_name
                    ]);


            }
            
            return redirect()->route('category.index')->with('status',trans('category.update_success'));


        }
    }

    public function contentListData(Request $request)
    {

        $parent = $request->parent;
      //  $status = $request->status;

        if ($parent == "all") {
            $categories = getAllCategories("all");
        }else {
            $categories = getSubCategory($parent ,"all");

        }

       // $categories = getAllCategories(1);
        $GLOBALS['index'] = 0;
        return datatables($categories)
            ->setRowId(function ($model) {
                return "row-" . $model->cat_id;
                // via closure
            })
            ->addColumn('id', function ($model) {
                $GLOBALS['index'] += 1;
                return $GLOBALS['index'];
            })
            ->addColumn('details', function ($model) {
               return "<i class='fa fa-plus details'> </i>";
            })
            ->addColumn('cat_name', function ($model) {
                return $model->cat_name;

            })
            ->addColumn('parent_name', function ($model) {
                return $model->parent_name;

            })
            ->addColumn('image', function ($model) {
                $getPath = $model->cat_image;
                // $getPath = str_replace('public', '', $getPath);

                $getPath = url('storage') . $getPath;
                return "<div><input type='hidden' class='image' value='" . $getPath . "'> <img src='" . $getPath . "' width='50' height='50'></div>";
            })
            ->addColumn('status', function ($model) {


                $activeON = "";
                $activeOff = "";
                $model->cat_isDeleted == -1 ? $activeON = "active" : $activeOff = "active";
                $element = '
                           <div  class="btn-group btnToggle" data-toggle="buttons" style="position: relative;margin:5px;">
                              <i class="fa fa-spinner fa-2x fa-spin loader hidden"></i>
                              <input  type="hidden" class="id_hidden" value="' . $model->cat_id . '">
                              <label  class="stateUser btn btn-default btn-on-1 btn-xs ' . "$activeON" . '">
                              <input  class="stateUser"  type="radio" value="1" name="multifeatured_module[module_id][status]" >ON</label>
                              <label  class="stateUser btn btn-default btn-off-1 btn-xs ' . "$activeOff" . '">
                              <input class="stateUser" type="radio" value="-1" name="multifeatured_module[module_id][status]">OFF</label>
                           </div>
                         ';

                return $element;


            })
            ->EditColumn('created_at', function ($model) {
                $date = date('d-m-Y', strtotime($model->created_at));
                return $date;

            })
            ->addColumn('control', function ($model) {
                $id = $model->cat_id;
                //
                return "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <a class='btn btn-primary btn-sm edit' href='" . url("category/" . $id . "/edit") . "'  title='تعديل' ><i class='fa fa-pencil'></i></a>
                 </div><div class='col-xs-6' style='width: 20%!important;'>
                           <a class='btn red btn-sm'  onclick='delCat($id)' title='حذف'> 
                           <i class='fa fa-remove ' ></i><i class='fa fa-lg fa-spin fa-spinner hidden' ></i>  </a>
                        
                         
                 </div>";
            })

            ->rawColumns(['image', 'control','status','details'])
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
                         <a class='btn btn-primary btn-sm' href='" . url("category/" . $id . "/edit") . "'><i class='fa fa-pencil'></i></a>
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

    public function getTreeCategories($cat_id) {

        $child = Category::where('cat_parent' , '=' , $cat_id)->get();
        $child = $child->map(function($value) {
            $value->text = getTranslation($value->cat_id , lang() , category_trans_type())->trn_text;
            $value->subChild = Category::where('cat_parent' , '=' , $value->cat_id)->get();
            $value->subChild->map(function($value2) {
                $value2->text = getTranslation($value2->cat_id , lang() , category_trans_type())->trn_text;
                return $value2;
            });
            return $value;
        });

        return $child;
    }

    public function destroy($id)
    {

       /* $category = Category::find($id);
        if ($category) {
            $category->delete();
            $getPath = $category->image;
            $getPath = public_path() . "/storage" . $getPath;
            File::delete($getPath);
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => 'The category not found']);
        }
*/
        $category = Category::find($id);
        if($category->cat_parent==0)// if main cat
        {
            $subCat = Category::where('cat_parent',$id)->where('cat_isDeleted','!=',1)->count();
            if($subCat>=1)
                return response()->json(['error' => false,'msg'=>'لايمكن حذف هذه المجموعة ,يوجود مجموعات فرعية تابعه لها']);
        }
        else// if nor main cat
        {
             $products=Product::where('cat_id',$id)->where('prd_isDeleted','!=',1)->count();
            if($products>=1)
                return response()->json(['error' => false,'msg'=>'لايمكن حذف هذه المجموعة ,يوجود منتجات تابعه لها']);
        }
        //$prod = Product::where('cat_id','=',$id);
        $category->cat_isDeleted=1;
        $category->save();
        return response()->json(['success' => true]);

    }

    public function statusCategory(Request $request)
    {
        $cat_id = $request->id;
        $isActive = $request->active;


        if($isActive == 1) {
            $delete= -1;
        }else {
            $delete= 1;
        }

        $user = Category::where('cat_id' , '=' , $cat_id);
        $user->update([
            'cat_isDeleted' => $delete,
        ]);
        return response()->json(['data' => 2]);


    }


}
