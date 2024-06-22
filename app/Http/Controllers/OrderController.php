<?php


namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Instruction;
use App\Offer;
use App\Order;
use App\OrderDetail;
use App\OrderDelivery;
use App\Rank;
use App\Role;
use App\Translation;
use App\User;
use Illuminate\Database\QueryException;
use App\SystemNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Route;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Session;
use DataTables;
//use Yajra\DataTables;
use Illuminate\Support\Facades\File;


class OrderController extends Controller
{

    public $data;

    public function __construct()
    {
        $this->data['menu'] = 'orders';
        $this->data['selected'] = 'orders';
        $this->data['location'] = 'orders';
        $this->data['location_title'] = "الطلبيات";
        $this->data['languages'] = getLanguages();


    }

    public function index()
    {

        $this->data['sub_menu'] = 'orders-display';

        $this->data['list_of_drivers'] = DB::table('users')
            ->select(DB::raw('id,name'))
            ->where('type', '=', 2)
            ->get();
        return view('order.index', $this->data);
    }


    public function create()
    {
        $this->data['sub_menu'] = 'instructions-create';
        $this->data['location_title'] = trans('instruction.add_instruction');
        return view('instructions.create', $this->data);
    }

    public function contentListDataold(Request $request)
    {


        $status = [1, 2, 3, 4];
        if ($request->status && $request->status != "all") {
            $status = [$request->status];
        }
        $order = Order::join('users', 'users.id', 'orders.ord_customer')
            ->leftJoin('orders_deliveries', 'orders_deliveries.ord_id', '=', 'orders.ord_id')
            ->join('addresses', 'user_id', '=', 'ord_customer')
            ->whereIn('orders.ord_status', $status)
            ->select('orders.*', 'addresses.lat', 'addresses.lng', 'addresses.adr_address', 'users.name as user_name',
                DB::raw(' (select GROUP_CONCAT(U.name) from orders_deliveries as d,users U where d.ord_id= orders.ord_id and d.odv_driver=U.id)  as driver_names'),
                DB::raw(' (select trn_text from translations  where trn_foreignKey= addresses.adr_city and trn_type="city" and lng_id=2)  as city'),
                DB::raw('GROUP_CONCAT(orders_deliveries.odv_driver) as drivers'))
            ->groupBy('ord_id')
            ->orderBy('ord_createdAt', 'desc')
            ->get();
        /* $order = Order::join('users', 'users.id', 'orders.ord_customer')
             ->leftJoin('orders_deliveries', 'orders_deliveries.ord_id', '=', 'orders.ord_id')
             ->leftJoin('orders_deliveries', 'orders_deliveries.odv_driver', '=', 'users.id')
             // ->whereIn('orders.ord_status', $status)
             ->select('orders.*', 'users.name as user_name',DB::raw( 'GROUP_CONCAT(orders_deliveries.odv_driver) as drivers'))
             ->groupBy('ord_id')
             ->get();*/
        // $user_delivery = User::all()->pluck('name', 'id')->toArray();
        $GLOBALS['index'] = 0;
        return datatables()->of($order)
            ->setRowId(function ($model) {
                return "row-" . $model->ord_id;
                // via closure
            })->addColumn('ord_status_desc', function ($model) {
                $state = $this->showOrderState($model->ord_status);
                return $state;
            })
            /*->addColumn('driver', function ($model) use ($user_delivery) {
                $driver = $model->driver;
                if ($driver) {
                    $driver_name = $user_delivery[$driver];
                } else {
                    $driver_name = "No";
                }
                return $driver_name;

            })*/
            ->addColumn('action', function ($model) {
                $id = $model->ord_id;
                $edit = "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='atr_id_hidden' value='$id'>
                         <a class='btn btn-primary btn-sm edit'><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
                 </div>
                               
                 ";

                $delivery = "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='ord_id_hidden' value='$id'>
                         <a class='btn btn-success btn-sm delivery_order'>توصيل الطلبية</a>
                 </div>
               ";
                if ($model->ord_status == 1) {
                    return $delivery;
                } else {
                    return null;
                }


            })
//            ->addColumn('drivers', function ($model) {
//
//                $edit = "
//                 <div class='col-xs-6' style='width: 20%!important;'>
//                         <input type='hidden' class='atr_id_hidden' value='$id'>
//                         <a class='btn btn-primary btn-sm edit'><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
//                 </div>
//
//                 ";
//
//                $delivery = "
//                <div class='col-xs-6' style='width: 20%!important;'>
//                         <input type='hidden' class='ord_id_hidden' value='$id'>
//                         <a class='btn btn-success btn-sm delivery_order'>توصيل الطلبية</a>
//                 </div>
//               ";
//                if ($model->ord_status == 1) {
//                    return $edit . $delivery;
//                } else {
//                    return $edit;
//                }
//
//
//            })
            ->toJson();

    }

    public function contentListDataold1(Request $request)
    {


        $status = [1, 2, 3, 4];
        if ($request->status && $request->status != "all") {
            $status = [$request->status];
        }
        $order = Order::join('users', 'users.id', 'orders.ord_customer')
            ->leftJoin('orders_deliveries', 'orders_deliveries.ord_id', '=', 'orders.ord_id')
            // ->whereIn('orders.ord_status', $status)
            ->select('orders.*', 'users.name as user_name',
                DB::raw(' (select GROUP_CONCAT(U.name) from orders_deliveries as d,users U where d.ord_id= orders.ord_id and d.odv_driver=U.id)  as driver_names'),
                DB::raw('GROUP_CONCAT(orders_deliveries.odv_driver) as drivers'))
            ->groupBy('ord_id')
            ->orderBy('ord_createdAt', 'desc')
            ->get();

        /* $order = Order::join('users', 'users.id', 'orders.ord_customer')
             ->leftJoin('orders_deliveries', 'orders_deliveries.ord_id', '=', 'orders.ord_id')
             ->leftJoin('orders_deliveries', 'orders_deliveries.odv_driver', '=', 'users.id')
             // ->whereIn('orders.ord_status', $status)
             ->select('orders.*', 'users.name as user_name',DB::raw( 'GROUP_CONCAT(orders_deliveries.odv_driver) as drivers'))
             ->groupBy('ord_id')
             ->get();*/
        // $user_delivery = User::all()->pluck('name', 'id')->toArray();
        $GLOBALS['index'] = 0;
        return Datatables::of($order)
            ->setRowId(function ($model) {
                return "row-" . $model->ord_id;
                // via closure
            })->addColumn('ord_status_desc', function ($model) {
                $state = $this->showOrderState($model->ord_status);
                return $state;
            })
            /*->addColumn('driver', function ($model) use ($user_delivery) {
                $driver = $model->driver;
                if ($driver) {
                    $driver_name = $user_delivery[$driver];
                } else {
                    $driver_name = "No";
                }
                return $driver_name;

            })*/
            ->addColumn('action', function ($model) {
                $id = $model->ord_id;
                $edit = "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='atr_id_hidden' value='$id'>
                         <a class='btn btn-primary btn-sm edit'><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
                 </div>
                               
                 ";

                $delivery = "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='ord_id_hidden' value='$id'>
                         <a class='btn btn-success btn-sm delivery_order'>توصيل الطلبية</a>
                 </div>
               ";
                if ($model->ord_status == 1) {
                    return $delivery;
                } else {
                    return null;
                }


            })
//            ->addColumn('drivers', function ($model) {
//
//                $edit = "
//                 <div class='col-xs-6' style='width: 20%!important;'>
//                         <input type='hidden' class='atr_id_hidden' value='$id'>
//                         <a class='btn btn-primary btn-sm edit'><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
//                 </div>
//
//                 ";
//
//                $delivery = "
//                <div class='col-xs-6' style='width: 20%!important;'>
//                         <input type='hidden' class='ord_id_hidden' value='$id'>
//                         <a class='btn btn-success btn-sm delivery_order'>توصيل الطلبية</a>
//                 </div>
//               ";
//                if ($model->ord_status == 1) {
//                    return $edit . $delivery;
//                } else {
//                    return $edit;
//                }
//
//
//            })
            ->make(true);

    }

    public function contentListData(Request $request)
    {
        $status = [1];
        if ($request->status && $request->status == 1)
            $status = [1];
        else if ($request->status && $request->status == 2)
            $status = [2, 3];
        else if ($request->status && $request->status == 4)
            $status = [4, 5];
        else if ($request->status && $request->status == 6)
            $status = [6];

        $order = Order::with('user', 'address', 'deliveries','deliveySchedule')
            ->leftJoin('orders_deliveries', 'orders_deliveries.ord_id', '=', 'orders.ord_id')
            ->whereIn('orders.ord_status', $status)
            ->select('orders.*')
            ->groupBy('orders.ord_id')
            ->orderBy('ord_id', 'desc');

        return datatables($order)
            ->setRowId(function ($model) {
                return "row-" . $model->ord_id;
                // via closure
            })->addColumn('ord_status_desc', function ($model) {
                $state = $this->showOrderState($model->ord_status);
                return $state;
            })
            ->addColumn('day_name', function ($model) {
                if ($model->ord_schedule_period_id!=null)
                    return $model->deliveySchedule->schedule_day_ar;
                return '';
            })
            ->addColumn('schedule_period', function ($model) {
                if (isset($model->deliveySchedule->start_time))
                    return $model->deliveySchedule->start_time.' - '.$model->deliveySchedule->end_time;
                return '';
            })

        /*    ->addColumn('lat', function ($model) {
                if (isset($model->address->lat))
                    return $model->address->lat;
                return '';
            })
            ->addColumn('lng', function ($model) {
                if (isset($model->address->lng))
                    return $model->address->lng;
                return '';
            })*/
            ->addColumn('adr_address', function ($model) {
                if (isset($model->address->adr_address))
                    return $model->address->adr_address;
                return '';
            })
            ->addColumn('user_name', function ($model) {
                return $model->user->name;
            })
            ->addColumn('city', function ($model) {
                if (isset($model->address->city_name))
                    return $model->address->city_name;
                return '';

            })
            ->addColumn('driver_names', function ($model) {
                if (isset($model->deliveries->driver_name))
                    return $model->deliveries->driver_name;
                return '';
            })
          
            ->addColumn('action', function ($model) {
                $id = $model->ord_id;
                $edit = "
                 <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='atr_id_hidden' value='$id'>
                         <a class='btn btn-primary btn-sm edit'><i class='fa fa-pencil'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i></a>
                 </div>";

                $delivery = "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='ord_id_hidden' value='$id'>
                         <a class='btn btn-success btn-sm delivery_order'>توصيل الطلبية</a>
                 </div>";
                if ($model->ord_status == 1) {
                    return $delivery;
                } else {
                    return null;
                }


            })
            //   ->rawColumns(['action','ord_status_desc'])
            ->toJson();

    }

    public function getDriver()
    {
        $driver_busy = OrderDelivery::join('orders', 'orders.ord_id', '=', 'orders_deliveries.ord_id')
            ->where('orders.ord_status', '=', '2')
            ->pluck('orders_deliveries.odv_driver')
            ->toArray();

        $delivery_not_busy = User::whereNotIn('id', $driver_busy)
            ->where('type', '=', 2)
            ->select('id', 'name')
            ->get();

        return response()->json($delivery_not_busy);
    }

    public function assignDriverToOrderold(Request $request)
    {
        $delivery_id = $request->delivery_id;
        $ord_id = $request->ord_id;
        $tokens = '';
        $success = 1;
        OrderDelivery::create([
            'ord_id' => $ord_id,
            'odv_driver' => $delivery_id,
        ]);

        Order::find($ord_id)->update([
            'ord_status' => 2,
            'ord_deliveryStartedAt' => date('Y-m-d H:i:s')
        ]);

        return response()->json($success);
    }

    public function assignDriverToOrder(Request $request)
    {
        $drivers = $request->delivery_id;
        //  dd($delivery_id);
        $ord_id = $request->ord_id;
        $order = Order::find($ord_id);
        $ord_number = $order->ord_number;
        $success = 1;
        foreach ($drivers as $driver) {
            $tokens = '';
            $driverUser = User::find($driver);
            $driverUserId = $driverUser->id;
            $deviceType = $driverUser->deviceType;
            $tokens = $driverUser->fcmToken;


            OrderDelivery::create([
                'ord_id' => $ord_id,
                'odv_driver' => $driver,
            ]);
            $noti_title = 'تم توجيه الطلب رقم: ' . $ord_number . ' لك ';
            $not_ar = 'تم توجييه الطلب لك بأنتظار القبول ';
            if (isset($tokens) && $tokens != '') {
                $notification = new SystemNotifications();
                $notification->not_title = $noti_title;
                $notification->not_ar = $not_ar;
                $notification->ord_id = $ord_id;
                $notification->user_id = $driverUserId;
                $notification->not_date = date('Y-m-d H:i:s');
                $notification->save();


                $this->sendOrderFcm($noti_title, $not_ar, $ord_id, $tokens, $deviceType);
            }
            Order::find($ord_id)->update([
                'ord_status' => 2,
                'ord_deliveryStartedAt' => date('Y-m-d H:i:s')
            ]);
        }
        return response()->json($success);
    }

    public function details(Request $request)
    {
        $not_id = $request->not_id;
        $ord_id = $request->ord_id;
        $this->data['order'] = Order::find($ord_id);

        //dd($this->data['order']->ord_customer);
        $userData = $this->data['order']->ord_customer;
        $this->data['orderDetails'] = OrderDetail::where('ord_id', '=', $ord_id)->get();

        $this->data['user'] = User::join('addresses', 'user_id', '=', 'id')
            ->where('id', '=', $userData)
            ->first();
        $not = SystemNotifications::find($not_id);
        if (isset($not)) {
            $not->seen_date = date('Y-m-d H:i:s');
            $not->save();
            return view('order.details', $this->data);
        }
        //    return back();
    }

    public function orderDetails(Request $request)
    {

        //  dd($request->all());


        $ord_id = $request->ord_id;
        $this->data['order'] = Order::find($ord_id);
        //dd($this->data['order']->ord_customer);
        $this->data['orderDetails'] = OrderDetail::where('ord_id', '=', $ord_id)->get();
        $userData = $this->data['order']->ord_customer;
        $this->data['user'] = User::join('addresses', 'user_id', '=', 'id')
            ->where('id', '=', $userData)
            ->first();

        return view('order.details', $this->data);


        return back();
    }


}


