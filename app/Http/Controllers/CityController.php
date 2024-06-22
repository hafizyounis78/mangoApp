<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeValue;
use App\City;
use App\Translation;
use Illuminate\Http\Request;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Datatables;
use Auth;

class CityController extends Controller
{

    protected $data;
    public $languages;

    public function __construct()
    {
        $this->middleware('role:admin');
        $this->data['menu'] = 'city';
        $this->data['selected'] = 'city';
        $this->data['location'] = "city";
        $this->data['location_title'] = trans('city.cities');
        $this->data['languages'] = getLanguages();
        $this->data['categories'] = getAllAttributes("all");
        $this->data['cities'] = getAllCity("all");
    }


    public function index()
    {

        $this->data['sub_menu'] = 'city';
        $this->data['location_title'] =trans('city.display_city');

        return view('city.index', $this->data);
    }


    public function create()
    {

        $this->data['sub_menu'] = 'category-create';
        $this->data['location_title'] = trans('category.add_categories');

        return view('category.create', $this->data);
    }

    public function store(Request $request)
    {
        $success = 1;
        $arr_city = json_decode($request->json_arr_city);
        $new_city = City::create([
            'cit_isActive' => 1
        ]);

        foreach ($arr_city as $city) {
            Translation::create([
                'trn_foreignKey' => $new_city->cit_id,
                'lng_id' => $city->lang,
                'trn_type' => city_trans_type(),
                'trn_text' => $city->name
            ]);
        }

        return response()->json($success);


    }


    public function contentListData(Request $request)
    {

        if($request->status) {
            $cities = getAllCity($request->status);
        }else {
            $cities = getAllCity("all");
        }


        $GLOBALS['index'] = 0;
        return datatables($cities)
            ->setRowId(function ($model) {
                return "row-" . $model->atr_id;
                // via closure
                
            })
            ->addColumn('city_status', function ($model) {



                $model->cit_isActive != -1 ? $active="active" : $active="activeOff";


                return $active;


            })
            ->addColumn('status', function ($model) {


                $activeON = "";
                $activeOff = "";
                $model->cit_isActive != -1 ? $activeON = "active" : $activeOff = "active";
                $a = 0;
                $element = '
                           <div  class="btn-group btnToggle" data-toggle="buttons" style="position: relative;margin:5px;">
                              <i class="fa fa-spinner fa-2x fa-spin loader hidden"></i>
                              <input  type="hidden" class="id_hidden" value="' . $model->cit_id . '">
                              <label  class="stateUser btn btn-default btn-on-1 btn-xs ' . "$activeON" . '">
                              <input  class="stateUser"  type="radio" value="1" name="multifeatured_module[module_id][status]" >ON</label>
                              <label  class="stateUser btn btn-default btn-off-1 btn-xs ' . "$activeOff" . '">
                              <input class="stateUser" type="radio" value="-1" name="multifeatured_module[module_id][status]">OFF</label>
                           </div>
                         ';

                return $element;


            })
            ->addColumn('control', function ($model) {
                $id = $model->cit_id;

                return "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='city_id_hidden' value='$id'>
                         <a class='btn btn-primary btn-sm edit' title='تعديل'><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
                 </div>" .
                    "
               
               ";
            })
            ->rawColumns(['status', 'control','type'])
            ->toJson();

    }

    public function editCity(Request $request)
    {
        $success = 1;
        $arr_city = json_decode($request->json_arr_city);

        $getCity = City::find($request->id);
        Translation::select('*')->where('trn_foreignKey', '=', $getCity->cit_id)
            ->where('trn_type', '=', city_trans_type())
            ->delete();

        foreach ($arr_city as $city) {

            Translation::create([
                'trn_foreignKey' => $getCity->cit_id,
                'lng_id' => $city->lang,
                'trn_type' => city_trans_type(),
                'trn_text' => $city->name
            ]);


        }

        return response()->json($success);
    }

    public function getCity(Request $request)
    {
        return response()->json(getCityData($request->id));

    }


    public function statusCity(Request $request)
    {
        $city_id = $request->id;
        $isActive = $request->active;


        $user = City::where('cit_id' , '=' , $city_id);
        $user->update([
            'cit_isActive' => $isActive,
        ]);
        return response()->json(['data' => 1]);


    }


}
