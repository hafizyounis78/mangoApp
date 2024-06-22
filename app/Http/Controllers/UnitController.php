<?php

namespace App\Http\Controllers;

use App\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Unit;



class UnitController extends Controller
{
    public function __construct()
    {

        $this->data['menu'] = 'unit';
        $this->data['selected'] = 'unit';
        $this->data['location'] = "unit";
        $this->data['location_title'] = 'الوحدات القياسية';
        $this->data['languages'] = getLanguages();

      //  $this->data['units'] = $this->getLookupUnits();

    }


    public function index()
    {



        $this->data['sub_menu'] = 'unit';
        $this->data['location_title'] ='عرض الوحدات القياسية';

        return view('unit.index', $this->data);
    }
    public function contentListData(Request $request)
    {
        $builder = DB::table('units as a' )
            ->select(DB::raw('a.unit_id,a.unit_isDeleted,(SELECT trn_text FROM translations 
            WHERE  trn_foreignKey=a.unit_id and lng_id=1 and trn_type="unit") as name_en,
            (SELECT trn_text FROM translations WHERE trn_foreignKey=a.unit_id and lng_id=2 and trn_type="unit" ) as name_ar'))
             ->where('a.unit_isDeleted','=',-1)
            ->get();
        //dd($builder);    
        $GLOBALS['index'] = 0;
        $i=0;
        //dd($builder);
        return datatables($builder)

            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                $id = $model->unit_id;
                $name_en = $model->name_en;
                $name_ar = $model->name_ar;

                $active = $model->unit_isDeleted;
                //dd($name_en);
                $icon1 = "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn btn-success btn-sm' data-toggle='modal' 
                          data-target='#unitModal' onclick='setUnitValue($id,\"".$name_en."\",\"".$name_ar."\")' title='تعديل'> 
                           <i class='fa fa-edit '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                $icon2 = "<div class='col-xs-6' style='width: 20%!important;'>
                           <a class='btn red btn-sm'  onclick='delUnit($id)' title='حذف'> 
                           <i class='fa fa-remove '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                if ($active == -1)
                    return $icon1 . $icon2;
                else
                    return $icon1;
            })
            ->rawColumns(['action'])
            ->toJson();

    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // dd($request->all());
        $id=$request->id;
      //  dd($id);
        $success = 1;
        if($id == null || $request->id=='') {


            $new_unit = new Unit();
            $new_unit->unit_isDeleted = -1;
            $new_unit->save();


            $tran = new Translation();
            $tran->trn_foreignKey = $new_unit->unit_id;
            $tran->lng_id = 1;
            $tran->trn_type = 'unit';
            $tran->trn_text = $request->name_en;
            $tran->save();

            $tran = new Translation();
            $tran->trn_foreignKey = $new_unit->unit_id;
            $tran->lng_id = 2;
            $tran->trn_type = 'unit';
            $tran->trn_text = $request->name_ar;
            $tran->save();
        }
        else
        {

            $tran =Translation::where('trn_type','=','unit')
                ->where('lng_id','=',1)
                ->where('trn_foreignKey','=',$id)->first();

            $tran->trn_text = $request->name_en;
            $tran->save();

            $tran =Translation::where('trn_type','=','unit')
                ->where('lng_id','=',2)
                ->where('trn_foreignKey','=',$id)->first();
            $tran->trn_text = $request->name_ar;
            $tran->save();

        }
        return response()->json($success);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        $coupon = Unit::find($id);
        $coupon->unit_isDeleted = 1;
        $coupon->save();
        return response()->json(['success' => true]);
    }
    private function getLookupUnits()
    {
        $translations = Translation::where('trn_type', 'unit')->get();
        $lookups = eloquentToArray($translations, 'trn_foreignKey', 'trn_text', false);
        return $lookups;

        /* return Translation::select('trn_foreignKey', 'trn_text')
             ->where('trn_type', '=', 'attribute_values')
             ->where('lng_id', '=', 2)
             ->get();*/
    }
}
