<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\NotificationController;
use App\SystemNotifications;
use Illuminate\Http\Request;
use DB;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\User;
use Validator;
use Redirect;

class SystemNotificationsController extends Controller
{
    public function __construct()
    {
        $this->data['menu'] = 'notification';
        $this->data['selected'] = 'notification';
        $this->data['location'] = 'notification';
        $this->data['location_title'] = "الإشعارات";
        $this->data['languages'] = getLanguages();


    }

    public function index()
    {

        $this->data['sub_menu'] = 'noti_display';
        $this->data['noti'] = DB::table('system_notifications')
            ->join('orders', 'orders.ord_id', '=', 'system_notifications.ord_id')
            ->join('users', 'users.id', '=', 'user_id')->get();
        return view('notifications.index', $this->data);
    }

    public function contentListData(Request $request)
    {

        $noti =null;
      //  dd($request->type);
        if ($request->type && $request->type == 1) {

            $noti = DB::table('system_notifications')
                ->join('orders', 'orders.ord_id', '=', 'system_notifications.ord_id')
                ->leftJoin('users', 'users.id', '=', 'user_id')
                ->where('seen_date', '=', null)
                ->orderBy('ord_createdAt', 'desc')
                ->get();
                

        }
        else if ($request->type == 2)
        {
            $noti = DB::table('system_notifications')
                ->join('orders', 'orders.ord_id', '=', 'system_notifications.ord_id')
                ->leftJoin('users', 'users.id', '=', 'user_id')
                ->orderBy('ord_createdAt', 'desc')
                ->get();

        }
        else if ($request->type == 3)
        {
            $noti = SystemNotifications::where('not_type',2)
                ->orderBy('created_at', 'desc')
                ->get();

        }
        
  else if ($request->type == 4)
        {
            $noti = SystemNotifications::where('not_type',3)
                ->orderBy('created_at', 'desc')
                ->get();

        }
//dd();

            $GLOBALS['index'] = 0;
        if($request->type == 3 ||$request->type==4)
      
        {
            return datatables()->of($noti)
                ->setRowId(function ($model) {
                    return "row-" . $model->not_id;
                    // via closure
                })
//
               ->make(true);
        }
        else {

        return datatables($noti)
            ->setRowId(function ($model) {
                return "row-" . $model->not_id;
                // via closure
            })
             ->addColumn('notification_title', function ($model) {
                $id = $model->not_id;
                $title= $model->not_title;
                $view = "
                
                         <a href='".url('details?not_id='.$model->not_id.'&user_id='.$model->user_id.'&ord_id='.$model->ord_id)."' class='btn btn-primary btn-sm btnSeen' data-id='$id'>$title</a>
                 
                               
                 ";
                return $view;
            })
            ->addColumn('action', function ($model) {
                $id = $model->not_id;
                $view = "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='noti_id_hidden' value='$id'>
                          <a href='".url('details?not_id='.$model->not_id.'&user_id='.$model->user_id.'&ord_id='.$model->ord_id)."' class='btn btn-primary btn-sm btnSeen' data-id='$id'><i class='fa fa-eye'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
                 </div>
                               
                 ";
                return $view;
            })
            ->rawColumns(['notification_title', 'action'])
             ->toJson();
           // ->toJson();

           }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function seenNoti(Request $request)
    {
        $noti=SystemNotifications::find($request->noti_id);
        $noti->seen_date=date('Y-m-d H:i:s');
        $noti->save();

        return 'success';
    }
      public function get_noti()
    {
        $notis = DB::table('system_notifications')
            ->join('orders', 'orders.ord_id', '=', 'system_notifications.ord_id')
            ->join('users', 'users.id', '=', 'user_id')
            ->orderBy('ord_createdAt', 'desc')
            ->latest('system_notifications.created_at')
            ->take(10) ->get();

       // $notis=SystemNotifications::latest()
        //    ->take(10)->get();
         $notiCount=SystemNotifications::where('seen_date','=',null)
            ->where('not_type','=',1)
            ->count();
        $return="0╩".$notiCount;




         $return.="╩1╩";
        foreach($notis as $noti)
        {
            if($noti->seen_date ==null)
                $color_clicked = "style='background-color:#eff2f6'";
            else
                $color_clicked = "";
            $return.='<li '.$color_clicked.'><a href="'.url('details?not_id='.$noti->not_id.'&user_id='.$noti->user_id.'&ord_id='.$noti->ord_id).'" class="note_row" data-order="'.$noti->ord_id.'" data-user="'.$noti->user_id.'" data-id="'.$noti->not_id.'">
				<span class="time">'.$noti->not_date.'</span>
				<span class="details">
				<span class="label label-sm label-icon label-info">
				<i class="fa fa-bullhorn"></i>
				</span>
				'.$noti->not_title.' </span>
				</a>
			</li>';

        }
        return $return;
    }
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
        //
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
    }
     public function sendMultipleFcm(Request $request)
    {
      $rules = [
            'title' => 'required',
            'notification' => 'required',
            'expire_date' => 'required'
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($request->title);
        $notificationBuilder->setBody($request->notification)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['title' => $request->title,
                'messages' => $request->notification,
                'id' => '',
                'type' => 2]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
         //****************************//
            $AndroidUsers = User::where('deviceType', '=', 1)->where('type', 3)->where('fcmToken', '!=', null)
                ->pluck('fcmToken')->toArray();
              
            if ($AndroidUsers != null) 
                $downstreamResponse = FCM::sendTo($AndroidUsers, $option, null, $data);
            
       //  dd($downstreamResponse);
         
            $IoUsers = User::where('deviceType', '!=', 1)->where('type', 3)->where('fcmToken', '!=', null)
                ->pluck('fcmToken')->toArray();
            if ($IoUsers != null)
                $downstreamResponse = FCM::sendTo($IoUsers, $option, $notification, $data);

            //insert new general notification
        //insert new general notification
        $noti = new SystemNotifications();
        $noti->not_title = $request->title;
        $noti->not_ar = $request->notification;
        $noti->not_type =  $request->not_type;
        $noti->expire_date=$request->expire_date;
        $noti->ord_id = '';
        $noti->user_id ='';
        $noti->not_date = date('Y-m-d H:i:s');
        $noti->save();
      
        $data = [
            'title'=>$request->title,
            'messages'=>$request->notification,
            'id'=>'',
            'type'=> $request->not_type,
            'numberSuccess'=>$downstreamResponse->numberSuccess(),
            'numberFailure'=>$downstreamResponse->numberFailure(),

        ];
        
        //return response()->json(['success' => true, 'msg' => $downstreamResponse]);
        return Redirect::back()->with(['data', $data]);
        }
    }
}
