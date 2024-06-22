<?php

namespace App\Http\Controllers;

use App\Translation;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Datatables;
use Auth;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
        $this->objCategory = new Category();
        $this->data['menu'] = 'category';
        $this->data['selected'] = 'category';
        $this->data['location'] = "category";
        $this->data['location_title'] = trans('category.categories');
        $this->data['languages'] = getLanguages();

        $this->data['categories'] = getAllCategories("0");
        $this->data['parentCategories'] = getAllParentCategories("all");
    }


    public function create()
    {

        $this->data['sub_menu'] = 'subcategory-create';
        $this->data['location_title'] = trans('category.add_subCategories');

        return view('subcategory.create', $this->data);
    }

    public function store(Request $request)
    {
       // dd('subcategory');
        $rules = [];

        $langs = getLanguages();
        foreach ($langs as $lang) {
            $rules['name_' . $lang->lng_id] = "required";
        }
        $rules['image'] = 'required';
        $rules['parent'] = 'required';
       // $rules['name'] = 'required';
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {

            $image = Input::file('image');
            $path = $this->storeImage($image, '/category/img/', false);

            $category =new Category();
            $category->cat_image = $path;
           // $category->cat_parent = 0;
            $category->cat_parent = $request->parent;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
