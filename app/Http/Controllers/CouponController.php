<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Carbon\Carbon;
class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
        $this->data['menu'] = 'coupon';
        $this->data['selected'] = 'coupon';
        $this->data['location'] = "coupon";
        $this->data['location_title'] = 'الكابونات';
        $this->data['languages'] = getLanguages();
    }

    public function index()
    {

        $this->data['sub_menu'] = 'coupon-display';
        $this->data['location_title'] = 'عرض الكابونات';
        //session()->pull('errors');
        return view('coupon.index', $this->data);
    }


    public function CouponListOld(Request $request)
    {

        $coupon = Coupon::all()->where('coupon_isDeleted','!=',1);
        //  dd($coupon);

        return datatables($coupon)
            ->addColumn('action', function ($model) {
                $id = $model->id;
                $active = $model->coupon_isDeleted;

                $icon1 = "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn btn-success btn-sm' data-toggle='modal' data-target='#couponModal' data-id='$id'
                           onclick='setCouponValue($model)' title='تعديل' > 
                           <i class='fa fa-edit '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                $icon2 = "<div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn red btn-sm'  onclick='delCoupon($id)' title='حذف'> 
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
    public function CouponList($no)
    {

        if ($no == 1) {
            $coupon = Coupon::where('coupon_isDeleted', '!=', 1)
                ->where('used', '!=', 1)
                ->whereDate('coupon_end','>=', Carbon::today());
        }
        else if($no == 2)
        {
            $coupon = Coupon::where('coupon_isDeleted', '!=', 1)
                ->where('used', '=', 1)
                ->orwhereDate('coupon_end','<=', Carbon::today());
        }

        //  dd($coupon);


          return datatables($coupon)
            ->addColumn('action', function ($model) {
                $id = $model->id;
                $active = $model->used;

                $icon1 = "
                <div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn btn-success btn-sm' data-toggle='modal' data-target='#couponModal' data-id='$id'
                           onclick='setCouponValue($model)'> 
                           <i class='fa fa-edit '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                $icon2 = "<div class='col-xs-6' style='width: 20%!important;'>
                         <input type='hidden' class='prd_id_hidden' value='$id'>
                          <a class='btn red btn-sm'  onclick='delCoupon($id)'> 
                           <i class='fa fa-remove '></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>  </a>
                        
                         
                 </div>";
                if ($active == 0)
                    return $icon1 . $icon2;
                else
                    return '';
            })
              ->rawColumns(['action'])
              ->toJson();

    }
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->coupon_isDeleted = 1;
        $coupon->save();
        return response()->json(['success' => true]);
    }

    public function updateCoupon(Request $request)
    {

        $coupon_id = $request->id;
        $coupon_no = $request->coupon_no;
        $coupon_start = $request->coupon_start;
        $coupon_end = $request->coupon_end;
        $value = $request->value;

        $coupon = Coupon::find($coupon_id);
        $coupon->coupon_no = $coupon_no;
        $coupon->coupon_start = $coupon_start;
        $coupon->coupon_end = $coupon_end;
        $coupon->value = $value;
        $coupon->save();
        return back();

    }

    public function saveCoupon(Request $request)
    {

        $coupon_id = $request->id;
        $coupon_no = $request->coupon_no;
        $coupon_start = $request->from;
        $coupon_end = $request->to;
        $value = $request->value;
        if ($coupon_id != null) {
            $coupon = Coupon::find($coupon_id);
            //  $coupon->coupon_no = $coupon_no;
            $coupon->coupon_start = $coupon_start;
            $coupon->coupon_end = $coupon_end;
            $coupon->value = $value;
            $coupon->save();

        }
//        $coupon = new Coupon();
//        $coupon->coupon_no = $coupon_no;
//        $coupon->coupon_start = $coupon_start;
//        $coupon->coupon_end = $coupon_end;
//        $coupon->value = $value;
//        $coupon->save();

        return response()->json(['success' => true]);

    }

    public function addCouponsOld(Request $request)
    {

        $auto_no = Coupon::max('auto_no');
        $num_of_coupon = $request->num_of_coupon;
        $value = $request ->value;
        $coupon_start = $request->from;
        $coupon_end = $request->to;
        for ($i = 1; $i <= $num_of_coupon; $i++) {
            $rand_no = mt_rand(100, 999);
            $coupon_no=($auto_no + $i) . $rand_no;
        //    echo $coupon_no . '<br/>';

            $newcoup = new Coupon();
            $newcoup=new Coupon();
            $newcoup->auto_no=$auto_no+$i;
            $newcoup->coupon_no=$coupon_no;
            $newcoup->coupon_start = $coupon_start;
            $newcoup->coupon_end = $coupon_end;
            $newcoup->value = $value;
            $newcoup->save();

        }
        return response()->json(['success' => true]);

        //dd($request->all());

        /*$micro_date = microtime();
        $date_array = explode(" ",$micro_date);
        $date_mic = explode(".",$date_array[0]);
        $datetime = date('ymdHmis');


        $datetime = $datetime.$date_mic[1]/100;
        echo $datetime.'<br/>';
        $all.= strtotime($datetime).'- ';*/

        //return Redirect::back();
    }
    public function addCoupons(Request $request)
    {

        //$auto_no = Coupon::max('auto_no');
        $num_of_coupon = $request->num_of_coupon;
        $value = $request->value;
        $coupon_start = $request->from;
        $coupon_end = $request->to;
        for ($i = 1; $i <= $num_of_coupon; $i++) {
            //$rand_no = mt_rand(100, 999);
            $coupon_no = $this->generate(); // 835710//($auto_no + $i) . $rand_no;
            //    echo $coupon_no . '<br/>';


            $newcoup = new Coupon();

            $newcoup->coupon_no = $coupon_no;
            $newcoup->coupon_start = $coupon_start;
            $newcoup->coupon_end = $coupon_end;
            $newcoup->value = $value;
            $newcoup->save();

        }
        return response()->json(['success' => true]);

        //dd($request->all());

        /*$micro_date = microtime();
        $date_array = explode(" ",$micro_date);
        $date_mic = explode(".",$date_array[0]);
        $datetime = date('ymdHmis');


        $datetime = $datetime.$date_mic[1]/100;
        echo $datetime.'<br/>';
        $all.= strtotime($datetime).'- ';*/

        //return Redirect::back();
    }
    public function generate() {

        $length         =10;// (isset($options['length']) ? filter_var($options['length'], FILTER_VALIDATE_INT, ['options' => ['default' => self::MIN_LENGTH, 'min_range' => 1]]) : self::MIN_LENGTH );
       // $prefix         = (isset($options['prefix']) ? self::cleanString(filter_var($options['prefix'], FILTER_SANITIZE_STRING)) : '' );
       // $suffix         = (isset($options['suffix']) ? self::cleanString(filter_var($options['suffix'], FILTER_SANITIZE_STRING)) : '' );
        $useLetters     = true;//(isset($options['letters']) ? filter_var($options['letters'], FILTER_VALIDATE_BOOLEAN) : true );
        $useNumbers     = true;//(isset($options['numbers']) ? filter_var($options['numbers'], FILTER_VALIDATE_BOOLEAN) : false );
        $useSymbols     = false;//(isset($options['symbols']) ? filter_var($options['symbols'], FILTER_VALIDATE_BOOLEAN) : false );
        $useMixedCase   = true;//(isset($options['mixed_case']) ? filter_var($options['mixed_case'], FILTER_VALIDATE_BOOLEAN) : false );
        $mask           = false;//(isset($options['mask']) ? filter_var($options['mask'], FILTER_SANITIZE_STRING) : false );

        $uppercase    = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
        $lowercase    = ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm'];
        $numbers      = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $symbols      = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?'];

        $characters   = [];
        $coupon = '';
         //echo $useLetters.'-'.$useNumbers.'-'.$useSymbols.'-'.$useMixedCase;
        if ($useLetters) {
            if ($useMixedCase) {
                $characters = array_merge($characters, $lowercase, $uppercase);
            } else {
                $characters = array_merge($characters, $uppercase);
            }
        }

        if ($useNumbers) {
            $characters = array_merge($characters, $numbers);
        }

        if ($useSymbols) {
            $characters = array_merge($characters, $symbols);
        }

        if ($mask) {
            for ($i = 0; $i < strlen($mask); $i++) {
                if ($mask[$i] === 'X') {
                    $coupon .= $characters[mt_rand(0, count($characters) - 1)];
                } else {
                    $coupon .= $mask[$i];
                }
            }
        } else {
            for ($i = 0; $i < $length; $i++) {
                $coupon .= $characters[mt_rand(0, count($characters) - 1)];
            }
        }

        return  $coupon;
    }

}
