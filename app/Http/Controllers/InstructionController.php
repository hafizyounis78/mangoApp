<?php


namespace App\Http\Controllers;

    use App\Http\Requests;
    use App\Http\Controllers;
    use App\Http\Requests\AddUserRequest;
    use App\Http\Requests\ChangePasswordRequest;
    use App\Http\Requests\UpdateUserRequest;
    use App\Http\Requests\UpdateUserInfoRequest;
    use App\Instruction;
    use App\Rank;
    use App\Role;
    use App\Translation;
    use App\User;
    use Illuminate\Database\QueryException;

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Route;
    use Auth;
    use Illuminate\Http\Request;
    use Validator;
    use Session;

    use Illuminate\Support\Facades\File;


class InstructionController extends Controller
{

    public $data;

    public function __construct()
    {
        $this->middleware('role:admin')->except(['profile', 'changePassword', 'updateInfo', 'reset', 'showResetForm']);
        $this->data['menu'] = 'instructions';
        $this->data['selected'] = 'instructions';
        $this->data['location'] = 'instructions';
        $this->data['location_title'] = trans('instruction.instructions');
        $this->data['languages'] = getLanguages();
        $this->data['instructions'] = getAllInstructions("all");

    }

    public function index()
    {
        $this->data['sub_menu'] = 'instructions-display';
        return view('instructions.index', $this->data);
    }




    public function create()
    {
        $this->data['sub_menu'] = 'instructions-create';
        $this->data['location_title'] =trans('instruction.add_instruction');
        return view('instructions.create', $this->data);
    }

    public function store(Request $request)
    {
        $rules = [];

        $langs = getLanguages();
        foreach ($langs as $lang) {
            $rules['name_' . $lang->lng_id] = "required";
            $rules['desc_' . $lang->lng_id] = "required";
        }
        $rules['image'] = 'required';
        $rules['orderBy'] = 'required|numeric';
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {

            $image =$request->image;
            $path = $this->storeImage($image, '/instruction/img/', false);

            $instruction = Instruction::create([
                'image'=>$path ,
                'orderBy' => $request->orderBy
            ]);

            foreach ($langs as $lang) {
                $name = "name_" . $lang->lng_id;
                $description = "desc_" . $lang->lng_id;
                $req_name1 = $request->$name;
                $req_name2 = $request->$description;
                Translation::create([
                    'trn_foreignKey' => $instruction->id,
                    'lng_id' => $lang->lng_id,
                    'trn_type' => instruction_trans_type(),
                    'trn_text' => $req_name1,
                    'trn_desc' => $req_name2
                ]);

            }


            return redirect()->route('instructions.index')->with('status', trans('instruction.add_success'));


        }


    }

    public function edit($id)
    {

        $this->data['sub_menu'] = 'instructions-edit';
        $this->data['location_title'] =trans('instruction.edit_instruction');
        $instruction = getInstruction($id);
        $arr_trn_name = [];
        $arr_trn_desc = [];
        foreach ($instruction->trans as $instruction1) {
            $arr_trn_name['name_' . $instruction1->lng_id] = $instruction1->trn_text;
            $arr_trn_desc['desc_' . $instruction1->lng_id] = $instruction1->trn_desc;
        }

        return view('instructions.edit', $this->data , ['instruction' => $instruction, 'arr_trn_name' => $arr_trn_name , 'arr_trn_desc' => $arr_trn_desc]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $rules = [];

        $langs = getLanguages();
        foreach ($langs as $lang) {
            $rules['name_' . $lang->lng_id] = "required";
        }
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate->errors());
        } else {


            $image = $request->image;
            $path = Instruction::find($id)->image;
            if($request->hasFile('image')) {
                $path = $this->storeImage($image, '/instruction/img/', false);
            }



            $instruction = Instruction::find($id)->update([
                'image' => $path ,
            ]);

            foreach ($langs as $lang) {
                $name = "name_" . $lang->lng_id;
                $description = "desc_" . $lang->lng_id;
                $req_name1 = $request->$name;
                $req_name2 = $request->$description;
                Translation::where('trn_foreignKey', '=', $id)
                    ->where('trn_type' , '=' , instruction_trans_type())
                    ->where('lng_id', '=', $lang->lng_id)
                    ->update([
                        'trn_text' => $req_name1 ,
                        'trn_desc' => $req_name2
                    ]);


            }

            return redirect()->route('instructions.index')->with('status', trans('instruction.update_success'));


        }
    }


    public function contentListData(Request $request)
    {

        $instructions = getAllInstructions("all");

        $GLOBALS['index'] = 0;
        return datatables($instructions)
            ->setRowId(function ($model) {
                return "row-" . $model->cat_id;
                // via closure
            })
            ->addColumn('id', function ($model) {
                $GLOBALS['index'] += 1;
                return $GLOBALS['index'];
            })

            ->addColumn('image', function ($model) {
                $getPath = $model->image;
                // $getPath = str_replace('public', '', $getPath);

                $getPath = url('storage') . $getPath;
                return "<div><input type='hidden' class='image' value='" . $getPath . "'> <img src='" . $getPath . "' width='50' height='50'></div>";
            })
            ->addColumn('status', function ($model) {


                $activeON = "";
                $activeOff = "";
                $model->isActive == 1 ? $activeON = "active" : $activeOff = "active";
                $element = '
                           <div  class="btn-group btnToggle" data-toggle="buttons" style="position: relative;margin:5px;">
                              <i class="fa fa-spinner fa-2x fa-spin loader hidden"></i>
                              <input  type="hidden" class="id_hidden" value="' . $model->id . '">
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
                $id = $model->id;
                //
                return "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <a class='btn btn-primary btn-sm edit' href='" . url("instructions/" . $id . "/edit") . "' ><i class='fa fa-pencil'></i></a>
                 </div>".
                    "
               
               ";
            })

            ->rawColumns(['status', 'control','image'])
            ->toJson();

    }

    public function statusInstruction(Request $request)
    {
        $id = $request->id;
        $isActive = $request->active;


        $instruction = Instruction::find($id);
        $instruction->isActive = $isActive;
        $instruction->update();

        return response()->json(['data' => 2]);


    }

    public function destroy($id)
    {

        $user = User::whereId($id);
        try {
            $user->delete();
            return response()->json(['status' => true]);
        } catch (QueryException $e) {
            if ($e->getCode() == "2292") {
                return response()->json(['status', "You can't delete this user"]);
            }
        }

        //  return back()->with('status', 'تم الحذف');


        /*
                 $user = User::whereId($id);
                $user->delete();
                return response()->json(['status' => true]);
                if ($user) {
                    $user->delete();
                    return response()->json(['status' => true]);
                } else {
                    return response()->json(['status' => 'The category not found']);
                }
                */
    }


    public function test3(Request $request) {
        $a = json_decode($request->arr);
        return response()->json($a[1]->arr[1]);
    }

}


