<?php

namespace App\Http\Controllers;

use App\DeliveryDay;
use App\DeliverySchedule;
use Illuminate\Http\Request;

class DevliveryScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

        $this->data['menu'] = 'delivery';
        $this->data['selected'] = 'delivery';
        $this->data['location'] = "delivery";
        $this->data['location_title'] = 'فترات العمل';
          $this->data['languages'] = getLanguages();

        //  $this->data['units'] = $this->getLookupUnits();

    }

    public function index()
    {

        $this->data['sub_menu'] = 'delivery';
        $this->data['location_title'] = 'عرض فترات العمل';

        return view('delivery.index', $this->data);
    }

    public function contentListData(Request $request)
    {
        $builder = DeliverySchedule::all();
        $i = 0;
        //dd($builder);
        return datatables($builder)
            ->addIndexColumn()

            ->addColumn('day_name_ar', function ($model) {
                return $model->schedule_day_ar;
            })
            ->addColumn('day_name_en', function ($model) {
                return $model->schedule_day_en;
            })
            ->addColumn('active', function ($model) {
                $activeON = "";
                $activeOff = "";
                $model->isActive != 0 ? $activeON = "active" : $activeOff = "active";
                $a = 0;
                $element = '
                           <div id="dv'.$model->id.'" class="btn-group btnToggle" data-toggle="buttons" style="position: relative;margin:5px;" 
                           onclick="activeSchedule('.$model->id.')">
                              <i class="fa fa-spinner fa-2x fa-spin loader hidden"></i>
                              <input type="hidden" class="id_hidden" value="' . $model->id . '">
                              <label class="stateUser btn btn-default btn-on-1 btn-xs ' . "$activeON" . '">
                              <input type="radio" value="1" name="isActive" >ON</label>
                              <label class="btn btn-default btn-off-1 btn-xs ' . "$activeOff" . '">
                              <input type="radio" value="0" name="isActive">OFF</label>
                           </div>
                         ';
                if ($model->isAdmin == 1) {
                    $element = "";
                }


                return $element;


            })
            ->addColumn('action', function ($model) {
                $id = $model->id;

                return '<div class="col-xs-6" style="width: 20%!important;">
                         <input type="hidden" class="prd_id_hidden" value="$id">
                          <a class="btn btn-success btn-sm" data-toggle="modal" 
                          data-target="#scheduleModal"
                          onclick="setSheduleValue('.$id.',\''.$model->day_id.'\',\''.$model->start_time.'\',\''.$model->end_time.'\')" title="تعديل">
                           <i class="fa fa-edit"></i><i class="fa fa-lg fa-spin fa-spinner hidden"></i>  </a></div>';
            })
            ->rawColumns(['action','active','day_name_ar','day_name_en'])
            ->toJson();

    }
    public function activeSchedule(Request $request)
    {
        $id = $request->id;
        $model = DeliverySchedule::find($id);
        if($model->isActive==1)
            $isActive=0;
        else
            $isActive=1;
        $model->update([
            'isActive' => $isActive,
        ]);

        return response()->json(['success' => true,'active'=>$isActive]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $id = $request->id;

        if ($id == null || $request->id == '') {
            $new = new DeliverySchedule();
            $new->day_id = $request->day_id;
            $new->start_time = $request->start_time;
            $new->end_time = $request->end_time;
            $new->save();
        } else {

            $new = DeliverySchedule::find($id);
            $new->day_id = $request->day_id;
            $new->start_time = $request->start_time;
            $new->end_time = $request->end_time;
            $new->save();

        }
        return response()->json(['success' => true]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
      /*  $coupon = Unit::find($id);
        $coupon->unit_isDeleted = 1;
        $coupon->save();
        return response()->json(['success' => true]);*/
    }
}
