<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Rank;
use App\Role;
use App\User;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Route;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Session;

use Illuminate\Support\Facades\File;

class UserController extends Controller
{

    public $data;

    public function __construct()
    {
        $this->middleware('role:admin')->except(['profile', 'changePassword', 'updateInfo', 'reset', 'showResetForm']);
        $this->data['menu'] = 'users';
        $this->data['selected'] = 'users';
        $this->data['location'] = 'users';
        $this->data['location_title'] = trans('users.users');

    }

    public function index()
    {
        $this->data['sub_menu'] = 'users-show';
        $this->data['users'] = User::orderBy('name')->get();
        return view('user.index', $this->data);
    }

    public function profile()
    {
        if (Auth::user()->id) {
            $this->data['sub_menu'] = '';
            $this->data['location_title'] = trans('users.my_profile');
            $this->data['location'] = 'users/profile';
            $this->data['user'] = User::find(Auth::user()->id);
            return view('user.profile', $this->data);
        } else return redirect('/');
    }

    public function updateInfo(UpdateUserInfoRequest $request, $id)
    {
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->update();

        return redirect()->route('users.profile')->with('status', trans('users.update_success'));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        if (Auth::user()->id) {
            // check if the old password is correct
            $user = User::where('id', Auth::user()->id)->first();
            if (Hash::check($request->old_password, $user->user_pass)) {
                $user->update(['user_pass' => Hash::make($request->new_password)]);
                return back()->with('status', 'Change password is done successfully');
            } else return back()->with('error', 'The old password is error');
            //$this->data['user'] = User::find(Auth::user()->id);

        } else return redirect('/');
    }

    public function create()
    {
        $this->data['sub_menu'] = 'users-create';
        $this->data['location_title'] = trans('users.add_users');
        $this->data['roles'] = Role::all();
        return view('user.create', $this->data);
    }

    public function store(AddUserRequest $request)
    {
        $password = bcrypt($request->password);
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $image = $request->image;
        $user_type = $request->user_type;
        // $username = $request->username;
        $path = $this->storeImage($image, '/user/img/', false);

        $data = array(
            'name' => $name,
            'user_pass' => $password,
            'email' => $email,
            'mobile' => $mobile,
            // 'username' => $username
            'image' => $path,
            'isAdmin' => -1,
            'type' => $user_type
        );

        $user = User::create($data);
        $user->attachRole(8);
        return redirect()->route('users.index')->with('status', trans('users.add_success'));
    }

    public function edit($id)
    {
        $this->data['sub_menu'] = 'users-edit';
        $this->data['location_title'] = trans('users.edit_user');
        $this->data['user'] = User::find($id);
        $this->data['roles'] = Role::all();
        //   $this->data['user_roles'] = $this->data['user']->roles->pluck('id', 'id')->toArray();
        return view('user.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->type = $request->user_type;
        //  if ($request->password != '') $user->user_pass = bcrypt($request->password);
        //$user->username = $request->username;

        $role = $user->roles()->get();

        /*DB::table('role_user')->where('user_id', $id)->delete();
        if($request->exists('role') && !empty($request->role)) {
            foreach ($request->role as $key => $value) {
                $user->attachRole($value);
            }

        }else {
            $user->attachRole(8);
        }
*/
        if ($request->exists('image') && $request->image != '') {
            $image = $request->image;
            $getPath = $user->image;
            $getPath = public_path() . "/storage" . $getPath;
            File::delete($getPath);


            $path = $this->storeImage($image, '/user/img/', false);
            $user->image = $path;

        }
        $user->update();

        return redirect()->route('users.index')->with('status', trans('users.update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
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


    public function contentListData(Request $request)
    {

        if ($request->status && $request->status != 'all') {
            //$user = User::where('isActive', '=', $request->status)->get();

            $user = User::select('*')
                ->where('users.isActive', '=', $request->status)
                ->where('users.type', '=', 3)
                ->get();
        } else {
            $user = User::select('*')
                ->where('users.type', '=', 3)
                ->groupBy('users.id')
                ->get();
        }
        return datatables($user)
            ->setRowId(function ($model) {
                return "row-" . $model->id;
            })// via closure
            ->addColumn('user_status', function ($model) {


                $model->isActive != -1 ? $active = "active" : $active = "activeOff";


                return $active;


            })
            ->EditColumn('type', function ($model) {

                if ($model->type == 3) {
                    $type = trans('users.user_normal');
                }
                return "<span class='label label-info'> $type</span>";
            })
            ->addColumn('active', function ($model) {


                $activeON = "";
                $activeOff = "";
                $model->isActive != -1 ? $activeON = "active" : $activeOff = "active";
                $a = 0;
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
                if ($model->isAdmin == 1) {
                    $element = "";
                }


                return $element;


            })->EditColumn('created_at', function ($model) {
                $date = date('d-m-Y', strtotime($model->created_at));
                return $date;

            })->addColumn('control', function ($model) {
                $id = $model->id;
                $element = "
                            <div class='col-xs-6' style='width: 35%!important;'>
                                <a  class='btn btn-primary btn-sm' href = '" . url("users/" . $id . "/edit") . "'><i class='fa fa-pencil' ></i ></a>
                             </div> 
                                <div class='col-xs-6' style='padding-left: 3px!important;'>
                                <a class='btn btn-danger btn-sm delete' ><input type = 'hidden' class='id_hidden' value = '" . $id . "' > <i class='fa fa-remove' ></i ></a >
                            </div>  
                        ";

                if ($model->username == "admin") {
                    $element = "
                            <div class='col-xs-6' style='width: 35%!important;'>
                                <a  class='btn btn-primary btn-sm' href = '" . url("users/" . $id . "/edit") . "'><i class='fa fa-pencil' ></i ></a>
                             </div> 
                               
                        ";


                }

                return $element;


            })
            ->rawColumns(['active', 'control', 'type'])
            ->toJson();
        /*
        return \Yajra\Datatables\Facades\Datatables::of($user)
            ->setRowId(function ($model) {
                return "row-" . $model->id;
            })// via closure
            ->addColumn('name', function ($model) {
                return $model->name;


            })
            ->addColumn('username', function ($model) {
                return $model->username;
            })
            ->addColumn('email', function ($model) {
                return $model->email;

            })->addColumn('active', function ($model) {


                $activeON = "";
                $activeOff = "";
                $model->isActive != -1 ? $activeON = "active" : $activeOff = "active";
                return '<div class="btn-group btnToggle" data-toggle="buttons" style="margin:5px;">
                              <input type="hidden" class="id_hidden" value="' . $model->id . '">
                              <label class="btn btn-default btn-on-1 btn-xs ' . "$activeON" . '">
                              <input   type="radio" value="1" name="multifeatured_module[module_id][status]" >ON</label>
                              <label class="btn btn-default btn-off-1 btn-xs ' . "$activeOff" . '">
                              <input  type="radio" value="-1" name="multifeatured_module[module_id][status]">OFF</label>
                           </div>';


            })->addColumn('rank', function ($model) {
                $rank = Rank::where('user_ranked_id', $model->id)->get();
                if (!($rank->count() > 0)) {
                    return (integer)1;
                } else {
                    return (integer)$rank->avg('rank');
                }


            })->addColumn('date', function ($model) {
                $date = date('d-m-Y', strtotime($model->created_at));
                return $date;

            })
            ->addColumn('control', function ($model) {
                $id = $model->id;
                return "<a class='btn btn-primary btn-sm' href = '" . url("users/" . $id . "/edit") . "'><i class='fa fa-pencil' ></i ></a> "
                    . "<a class='btn btn-danger btn-sm delete' ><input type = 'hidden' class='id_hidden' value = '" . $id . "' > <i class='fa fa-remove' ></i ></a > ";

            })->make(true);
            */


    }

    public function activeUser(Request $request)
    {
        $user_id = $request->id;
        $isActive = $request->active;

        $user = User::find($user_id);
        $user->update([
            'isActive' => $isActive,
        ]);
        $user->deleteToken();
        return response()->json(['data' => 2]);


    }

    public function showResetForm($token)
    {

        $tokenReset = DB::table('password_resets')
            ->where('token', $token)->exists();

        if ($tokenReset) {
            $email = DB::table('password_resets')
                ->where('token', $token)
                ->first()->email;

            return view('auth.passwords.reset')->with(['token' => $token, 'email' => $email]);
        } else {
            abort(404);
        }


        //return $email;
        /*   DB::table('password_resets')->where('email', $email)->update([
               'token' => null,
           ]);*/

    }

    public function reset(Request $request)
    {

        $rules = [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:6',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        } else {

            $token = $request->token;
            $email = $request->email;
            $password = bcrypt($request->password);


            $tokenReset = DB::table('password_resets')
                ->where('token', $token)->exists();

            if (!$tokenReset) {
                return redirect()->back()->with('error', 'Error in reset password ');
            } else {

                /* $email = DB::table('password_resets')
                     ->where('token', $token)
                     ->orderBy('created_at', 'DESC')
                     ->first()->email;*/

                DB::table('password_resets')
                    ->where('token', $token)
                    ->update([
                        'token' => null
                    ]);

                $user = User::where('email', $email)->first();
                $user->update([
                    'user_pass' => $password
                ]);

                return view('auth.passwords.email')->with('status', 'The reset password done successfully');
            }

        }

    }

    public function getUserInfo($id)
    {
        /*$user=User::where ('id',$id)
        ->join('addresses','user_id','=','id')
            ->select('users.*')->first();*/
        /*
                $user = DB::table('users')
                    ->leftJoin('addresses', 'addresses.user_id', '=', 'users.id')
                    ->where('users.id','=',$id)
                    ->where('addresses.adr_isDefault','=',1)
                    ->select('users.*','addresses.*',
                        DB::raw(' (select trn_text from translations  where trn_foreignKey= addresses.adr_city and trn_type="city" and lng_id=2)  as city'))
                   ->get();
                return $user;*/
        $user = DB::table('users')
            ->where('users.id', '=', $id)
            ->select('users.*',
                DB::raw(' (select trn_text from translations  where trn_foreignKey= users.city and trn_type="city" and lng_id=2)  as city'))
            ->get();
        return $user;
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->back();
    }






    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
    _ Obtain the user information from provider.  Check if the user already exists in our
    _ database by looking up their provider_id in the database.
    _ If the user exists, log them in. Otherwise, create a new user then log them in. After that
    _ redirect them to the authenticated users homepage.
    _
    _ @return Response
    _/
    public function handleProviderCallback($provider)
    {
    $user = Socialite::driver($provider)->user();

    $authUser = $this->findOrCreateUser($user, $provider);
    Auth::login($authUser, true);
    return redirect($this->redirectTo);
    }

    /**
    _ If a user has registered before using social auth, return the user
    _ else, create a new user object.
    _ @param  $user Socialite user object
    _ @param $provider Social auth provider
    _ @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'typeSocialMedia' => $provider,
            'idSocialMedia' => $user->id,
        ]);
    }

}
