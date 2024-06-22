<?php

namespace App\Http\Controllers\Api;

use App\Notifications\LostNotify;
use App\Notifications\WonNotify;
use App\NotificationType;
use App\NotificationUserSetting;
use App\User;
use App\SystemNotifications;
use App\NotificationBroadcast;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;


class NotificationController extends Controller
{

    /*
     state in myNotificationSetting
     1   >> enable
     -1  >> disable

    state in myNotification :-
    -1  >> default unread
    1 >> mark as read
     */
    public function __construct()
    {

    }

    public function getAll()
    {
        dd($this->checkNotifyEnable(25, 'won'));
    }

    public function getNotificationType()
    {
        $allNotificationType = NotificationType::all();
        return $this->responseJson(true, "success", $allNotificationType);
    }

    public function myNotification(Request $request)
    {
        $user = $request->user();
        $rules = [
            'page_number' => 'required|integer',
            'per_page' => 'required|integer|min:1',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            $page_number = $request->page_number;
            $per_page = $request->per_page;

            $user_notify = $user->notifications();

            $notification_user = $user_notify
                ->orderBy('created_at' , 'desc')
                ->take($per_page)
                ->skip($page_number * $per_page);


            $total_page = ceil($user->notifications()->get()->count() / $per_page);

            $notification = $notification_user
                ->select('id', 'data', 'state', 'created_at')
                ->get();

            $notification = $notification->map(function ($value) {
                foreach ($value->data as $key_notify => $data_notify) {
                    $value->$key_notify = $data_notify;
                }

                $value->date = $this->diffInHumanNotificationTime($value->created_at);
                unset($value->created_at);
                unset($value->data);
                return $value;

            });


            $notification2 = $notification_user
                ->select('id', 'data', 'state')
                ->where('state', '=', -1)
                ->get();

            foreach ($notification2 as $p) {
                $p->markAsRead();
                $p->state = 1;
                $p->update();
            }

            $result = [];
            $result['total_page'] = $total_page;
            $result['notifications'] = $notification;

            return $this->responseJson(true, 'success', $result);
        }


    }
public function getMyNotificationold(Request $request)
    {
        $user_id = auth()->user()->id;
      //  dd($user_id);
        $rules = [
            'page_number' => 'required|integer',
            'per_page' => 'required|integer|min:1',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            $page_number = $request->page_number;
            $per_page = $request->per_page;

            $user_notify_count = SystemNotifications::where('user_id',$user_id)
               ->orWhereIn('not_type',[2,3])
                ->count();
            $user_notify = SystemNotifications::where('user_id',$user_id)
               ->orWhereIn('not_type',[2,3])
                ->orderBy('created_at' , 'desc')
                ->take($per_page)
                ->skip($page_number * $per_page)
                ->get();
            $this->seenNoti();
            $total_page = ceil($user_notify_count / $per_page);








            $result = [];
            $result['total_page'] = $total_page;
            $result['notifications'] = $user_notify;

            return $this->responseJson(true, 'success', $result);
        }


    }
    public function getMyNotification(Request $request)
    {
        $user_id = auth()->user()->id;
        $user_type = auth()->user()->type;
        //   dd($user_type);
        $rules = [
            'page_number' => 'required|integer',
            'per_page' => 'required|integer|min:1',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {

            $page_number = $request->page_number;
            $per_page = $request->per_page;
            if ($user_type == 3) {


                $user_notify_count = SystemNotifications::where('user_id', $user_id)
                    ->orWhereIn('not_type', [2, 3])
                    ->count();
                $user_notify = SystemNotifications::where('user_id', $user_id)
                    ->orWhereIn('not_type', [2, 3])
                    ->orderBy('created_at', 'desc')
                    ->take($per_page)
                    ->skip($page_number * $per_page)
                    ->get();
            }
            else if ($user_type == 2)
            {
                $user_notify_count = SystemNotifications::where('user_id', $user_id)
                    ->count();
                $user_notify = SystemNotifications::where('user_id', $user_id)
                    ->orderBy('created_at', 'desc')
                    ->take($per_page)
                    ->skip($page_number * $per_page)
                    ->get();

            }
            $this->seenNoti();
            $total_page = ceil($user_notify_count / $per_page);


            $result = [];
            $result['total_page'] = $total_page;
            $result['notifications'] = $user_notify;

            return $this->responseJson(true, 'success', $result);
        }


    }
      public function getbadge()
    {

        $user_id = auth()->user()->id;

        $user_notify_count = SystemNotifications::where('user_id', $user_id)
            ->where('seen_date', '=', null)->count();


        $general_notify_count = DB::table('system_notifications')
            ->whereNotIn('not_id', DB::table('system_notifications')
                ->select('notification_broadcast.not_id')
                ->join('notification_broadcast', 'notification_broadcast.not_id', '=', 'system_notifications.not_id')
                // ->where('system_notifications.user_id', '=', $user_id)
                ->where('notification_broadcast.user_id', '=', $user_id))
            ->whereIn('not_type', [2, 3])
             ->whereDate('expire_date','>=' ,Carbon::today())
            ->count();
        $bagdgCount = $user_notify_count + $general_notify_count;
        //dd($bagdgCount);
        return $this->responseJson(true, 'success', $bagdgCount);

    }

    public function seenNoti()
    {
        $user_id = auth()->user()->id;

        $user_notifys = SystemNotifications::where('user_id', $user_id)
            ->where('seen_date', '=', null)->get();
        foreach ($user_notifys as $user_notify) {


            $user_notify->seen_date = date('Y-m-d H:i:s');
            $user_notify->save();
        }

        $general_notifys = DB::table('system_notifications')
            ->whereNotIn('not_id', DB::table('system_notifications')
                ->select('notification_broadcast.not_id')
                ->join('notification_broadcast', 'notification_broadcast.not_id', '=', 'system_notifications.not_id')
                ->where('notification_broadcast.user_id', '=', $user_id))
            ->whereIn('not_type', [2, 3])
            ->pluck('system_notifications.not_id');
        for ($i = 0; $i < count($general_notifys); $i++) {
            $NotiBroadcast = new NotificationBroadcast();
            $NotiBroadcast->not_id = $general_notifys[$i];
            $NotiBroadcast->user_id = $user_id;
            $NotiBroadcast->save();
        }
     //   return $this->responseJson(true, 'success', null);

    }
    public function myNotification2(Request $request)
    {
        $user = $request->user();
        $per_page = 10;

        $notification_user = $user->notifications();
        $total_page = ceil($notification_user->get()->count() / $per_page);

        $notification = $notification_user
            ->select('id', 'data', 'state', 'created_at')
            ->get();

        $notification = $notification->map(function ($value) {
            foreach ($value->data as $key_notify => $data_notify) {
                $value->$key_notify = $data_notify;
            }
            $value->date = $this->diffInHumanNotificationTime($value->created_at);
            unset($value->created_at);
            unset($value->data);
            return $value;

        });

        $notification2 = $notification_user
            ->select('id', 'data', 'state')
            ->where('state', '=', -1)
            ->get();

        foreach ($notification2 as $p) {
            $p->markAsRead();
            $p->state = 1;
            $p->update();
        }

        $result = [];
        $result['total_page'] = $total_page;
        $result['notifications'] = $notification;

        return $this->responseJson(true, 'success', $result);


    }

    public function notificationSetting(Request $request)
    {
        $user = $request->user();
        $typeNotify = $request->typeNotify;

        NotificationUserSetting::where('user_id', $user->id)->delete();
        if (in_array(-1, $typeNotify)) {
            $size_arr = 0;
        } else {
            $size_arr = 1;
        }

        /*   foreach ($typeNotify as $p) {
               if (!empty($p)) {
                   $size_arr++;
               }
           }*/

        if ($size_arr > 0) {
            foreach ($typeNotify as $p) {
                NotificationUserSetting::create([
                    'user_id' => $user->id,
                    'notification_type_id' => $p
                ]);
            }
        }


        return $this->responseJson(true, 'success', null);

    }

    public function myNotificationSetting(Request $request)
    {
        $user = $request->user();
        $allNotificationType = NotificationType::all();
        $result = $allNotificationType->map(function ($value) use ($user) {
            $checkEnable = $this->checkNotifyEnable($user->id, $value->name);
            if ($checkEnable) {
                $state = 1;
            } else {
                $state = -1;
            }
            $value->state = $state;
            unset($value->created_at);
            unset($value->updated_at);
            return $value;
        });

        return $this->responseJson(true, "success", $result);
    }


    public function getUnReadNotification(Request $request) {
        $user = $request->user();
        $unReadNotification = $user->unreadNotifications->count();
        return $this->responseJson(true, "success", $unReadNotification);
    }
    public function checkNotifyEnable($user_id, $type)
    {
        $notifySetting = NotificationUserSetting::where('user_id', $user_id)
            ->where('notification_type_id', NotificationType::where('name', $type)->first()->id);

        if ($notifySetting->exists()) {
            return false;
        } else {
            return true;
        }
    }

    public function diffInHumanNotificationTime($date)
    {

        $diffInMinutes = $date->diffInMinutes(Carbon::now());
        $diffInDays = $date->diffInDays(Carbon::now());
        $diffInHours = $date->diffInHours(Carbon::now());

        $data_type1 = $diffInHours == 0 ? "minute" : "hour";
        $data_type2 = $diffInDays == 0 ? $data_type1 : "day";

        $data_time1 = $diffInHours == 0 ? $diffInMinutes : $diffInHours;
        $data_time2 = $diffInDays == 0 ? $data_time1 : $diffInDays;

        return $data_time2 . " " . $data_type2;

    }
}
