<?php

namespace App\Http\Controllers;

use App\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function __construct()
    {
        $this->data['menu'] = 'setting';
        $this->data['selected'] = 'appsetting';
        $this->data['location'] = 'appsetting';
        $this->data['location_title'] = "توابت النظام";
        $this->data['languages'] = getLanguages();


    }
    public function index()
    {

        $this->data['sub_menu'] = 'appsetting_display';
        $this->data['appsetting'] = AppSetting::all();
        return view('setting.index', $this->data);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //dd('update'.$request);
        $appsetting=AppSetting::find($id);
        $appsetting->value=$request->value;
        $appsetting->save();
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
