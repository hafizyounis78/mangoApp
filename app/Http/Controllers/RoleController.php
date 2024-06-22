<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddNewRoleRequest;
use App\Http\Requests\AddRoleRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public $data;

    public function __construct()
    {
        //$this->middleware('role:admin');
        $this->data['menu'] = 'system';
        $this->data['selected'] = 'permissions';
        $this->data['location'] = 'roles';
        $this->data['location_title'] = 'الصلاحيات';

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['sub_menu'] = 'permissions';
        $this->data['roles'] = Role::all();
        return view('role.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['sub_menu'] = 'permissions-create';
        $this->data['permissions'] = Permission::all();
        return view('role.create', $this->data);
    }

    public function newRole()
    {
        $this->middleware('permission:role-add');
        $this->data['sub_menu'] = 'role-new';
        return view('role.new', $this->data);
    }

    public function editRole($id)
    {
        $this->data['permission'] = Permission::find($id);
        return view('role.editRole', $this->data);
    }

    public function updateRole(UpdatePermissionRequest $request,$id)
    {
        $permission = Permission::find($id);
        //$permission->name = $request->name;
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        $permission->save();
        return redirect()->route('roles.view')->with('status','تم تعديل الصلاحية بنجاح');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddRoleRequest $request)
    {
        $role = Role::create($request->except(['permission','_token']));
        foreach($request->permission as $key=>$value) {
            $role->attachPermission($value);
        }

        return redirect()->route('roles.index')->with('status','تم إضافة المجموعة بنجاح');
    }

    public function storeRole(AddNewRoleRequest $request)
    {
        Permission::create($request->except(['_token']));
        return back()->with('status','تم إضافة الصلاحية بنجاح');
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
        $this->data['role'] = Role::find($id);
        if($this->data['role']->name == 'admin') return back();
        $this->data['permissions'] = Permission::all();
        $this->data['role_permissions'] = $this->data['role']->perms()->pluck('id','id')->toArray();
        return view('role.edit', $this->data);
    }

    public function view()
    {
        $this->data['sub_menu'] = 'role-view';
        $this->data['permissions'] = Permission::orderBy('name')->get();
        return view('role.view',$this->data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddRoleRequest $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        DB::table('permission_role')->where('role_id', $id)->delete();

        foreach($request->permission as $key=>$value) {
            $role->attachPermission($value);
        }

        return redirect()->route('roles.index')->with('status','تم تعديل المجموعة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if($role->name == 'admin') return back();
        $role->delete();
        return back()->with('status','تم الحذف');
    }

    public function destroyRole($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        return back()->with('status','تم الحذف');
    }
}
