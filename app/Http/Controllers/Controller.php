<?php

namespace App\Http\Controllers;

use App\Category;
use App\Events\FcmEvent;
use App\FollowingProduct;
use App\Http\Controllers\Api\NotificationController;
use App\Language;
use App\Notifications\CloseNotify;
use App\Notifications\FollowProductNotify;
use App\Notifications\LostNotify;
use App\Notifications\MyPostsNotify;
use App\Notifications\OptionNotify;
use App\Notifications\WonNotify;
use App\Product;
use App\Ticket;
use App\Translation;
use App\User;
use App\Winner;
use Carbon\Carbon;
use Cartalyst\Stripe\Exception\MissingParameterException;
use Cartalyst\Stripe\Exception\NotFoundException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use URL;
use Session;
use Redirect;
use Input;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public $category_trans_type = "categories";
    public $lang = 1;
	
	public function __construct(){
		
	}


    public function responseJson($status, $message, $data, $var = null)
    {
        $arr = [];
        $arr['status'] = $status;
         $statusCode = ($status)? 200:422;
        $arr['statusCode'] = $statusCode;
        $arr['message'] = $var == null ? trans('api.' . $message) : trans('api.' . $message, ['var' => $var]);;
        $arr['data'] = $data;

        return response()->json($arr);
    }

    public function responseJson2($status, $message, $data, $lang = null, $var = null)
    {
        $arr = [];
        $message_var = $var == null ? trans('api.' . $message) : trans('api.' . $message, ['var' => $var]);
        $arr['status'] = $status;
          $statusCode = ($status)? 200:422;
        $arr['statusCode'] = $statusCode;
        $arr['message'] = $lang == null ? $message : $message_var;
        $arr['data'] = $data;

        return response()->json($arr);
    }

    public function addFullPath($product)
    {
        $product = $product->map(function ($value) {
            $value['image'] = $this->fullPath($value['image']);
            return $value;
        });
        return $product;
    }

    public function fullPath($path)
    {

        $url = '';
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $url = $path;
        } else if (empty($path)) {
            $url = '';
        } else {
            $url = url('storage') . $path;
        }

        return $url;
    }

    public function storeImage($image, $pathImg, $api = true)
    {
        // /user/img/
        if ($api == true) {
            $arr = explode(",", $image);
            $ext = $arr[0];
            $imgContent = base64_decode($arr[1]);
        } else {
            $ext = $image->getClientOriginalExtension();
            $imgContent = File::get($image);
        }

        $file_name = str_random(40) . time() . "." . $ext;
        $fullPath = public_path() . "/storage" . $pathImg . $file_name;

        $path = $pathImg . $file_name;
        File::put($fullPath, $imgContent);
        return $path;
    }

    public function storeImage2($image, $pathImg)
    {


        $imgContent = base64_decode($image);
        $file_name = str_random(40) . time() . ".png";
        $fullPath = public_path() . "/storage" . $pathImg . $file_name;

        $path = $pathImg . $file_name;
        File::put($fullPath, $imgContent);
        return $path;


    }
      public function storeImage3($image, $pathImg, $api = true)
    {
        // /user/img/
        if ($api == true) {
            $arr = explode(",", $image);
            $ext = $arr[0];
            $imgContent = base64_decode($arr[1]);
        } else {
            $ext = $image->getClientOriginalExtension();
            $imgContent = File::get($image);
        }

        $file_name = str_random(40) . time() . "." . $ext;
        $fullPath = public_path() . "/storage" . $pathImg . $file_name;

        $path =  $file_name;
        File::put($fullPath, $imgContent);
        return $path;
    }

    public function validatorErrorMsg($rules, $errors)
    {

        $errorMsg = '';

        foreach ($rules as $key => $msg) {
            if (isset($errors->get($key)[0])) {
                $errorMsg = $errors->get($key)[0];
                break;
            }
        }
        return $errorMsg;
    }

    public function showState($state_id)
    {

        /*
         1 >>> active
         2 >>> closed
         3 >>> sold
         */
        $state_text = "";
        if ($state_id == 1) {
            $state_text = $this->productStateIsActive;
        } else if ($state_id == -1) {
            $state_text = $this->productStateIsClosed;
        } else {
            $state_text = $this->productStateIsSold;
        }

        return $state_text;
    }

    public function showOrderState($status) {
        $arr = [
            1 => 'Pending' ,
            2 => 'Assigned' ,
            3 => 'Inprogress' ,
            4 => 'Confirm Delivery' ,
            5 => 'Confirm Receive',
            6 => 'Cancel',

        ];

        return $arr[$status];
    }
    public function toString2($string)
    {
        $name = json_decode($string);
        if (empty($name)) {
            $name = $string;
        }
        return $name;
    }
  public function sendOrderFcm($title, $body, $obj, $tokens, $deviceType)
    {
        // dd($request->all());

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();

        $dataBuilder->addData(['id' => $obj, 'title' => $title,
                'messages' => $body,
                'type' => 1]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
//        $tokens = User::pluck('fcmToken')->toArray();
        if ($deviceType == 1)
            $downstreamResponse = FCM::sendTo($tokens, $option, null, $data);
        else
            $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
    }
  public function sendGeneralFcm($title, $body, $obj, $tokens,$type ,$deviceType)
    {
        // dd($request->all());

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();

        $dataBuilder->addData(['id' => $obj, 'title' => $title,
                'messages' => $body,
                'type' => $type]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
//        $tokens = User::pluck('fcmToken')->toArray();
        if ($deviceType == 1)
            $downstreamResponse = FCM::sendTo($tokens, $option, null, $data);
        else
            $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
          $data = [
              'numberSuccess'=>$downstreamResponse->numberSuccess(),
              'numberFailure'=>$downstreamResponse->numberFailure(),
              'numberModification'=>$downstreamResponse->numberModification(),
              'tokensToDelete'=>$downstreamResponse->tokensToDelete(),
              'tokensToModify'=>$downstreamResponse->tokensToModify(),
              'tokensToRetry'=>$downstreamResponse->tokensToRetry(),
              'tokensWithError'=>$downstreamResponse->tokensWithError()
          ];
         // dd($data);
      /* $data = [
            'title' => $title,
            'messages' => $body,
            'id' => $obj,
            'type' => 1,
            'numberSuccess' => $downstreamResponse->numberSuccess(),
            'numberFailure' => $downstreamResponse->numberFailure(),
        ];*/

        return response()->json(['success' => true, 'data' => $data]);
    }
   public function sendOrderFcmold($title,$body,$obj,$tokens)
    {
        // dd($request->all());

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

$notiObj[]='';
$notiObj=[
    'title'=>$title,
            'messages'=>$body,
            'ord_id'=>$obj,
            'type'=>1
    ];
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['id' => $obj, 'title'=>$title,
            'messages'=>$body,
            'type'=>1]);
       // $dataBuilder->addData(['data' => $notiObj]);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
//        $tokens = User::pluck('fcmToken')->toArray();

        //$downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
         $downstreamResponse = FCM::sendTo($tokens, $option, null, $data);
      /*  $data = [
            'numberSuccess'=>$downstreamResponse->numberSuccess(),
            'numberFailure'=>$downstreamResponse->numberFailure(),
            'numberModification'=>$downstreamResponse->numberModification(),
            'tokensToDelete'=>$downstreamResponse->tokensToDelete(),
            'tokensToModify'=>$downstreamResponse->tokensToModify(),
            'tokensToRetry'=>$downstreamResponse->tokensToRetry(),
            'tokensWithError'=>$downstreamResponse->tokensWithError()
        ];*/
        $data = [
            'title'=>$title,
            'messages'=>$body,
            'ord_id'=>$obj,
            'type'=>1
        ];

        return response()->json(['success' => true, 'data' => $data]);
    }
    public function sendfcm($title,$body,$obj,$tokens)
    {
        // dd($request->all());

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $obj]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

// You must change it to get your tokens
//        $tokens = User::pluck('fcmToken')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
      //  dd($downstreamResponse);
        $data = [
            'numberSuccess'=>$downstreamResponse->numberSuccess(),
            'numberFailure'=>$downstreamResponse->numberFailure(),
            'numberModification'=>$downstreamResponse->numberModification(),
            'tokensToDelete'=>$downstreamResponse->tokensToDelete(),
            'tokensToModify'=>$downstreamResponse->tokensToModify(),
            'tokensToRetry'=>$downstreamResponse->tokensToRetry(),
            'tokensWithError'=>$downstreamResponse->tokensWithError()
        ];
        
        return response()->json(['success' => true, 'msg' => $data]);
    }
    ///////////////////////////////////////////
     /*
    public function getLanguages()
    {
        return Language::all();
    }

    public function getAllCategories($status)
    {


        if ($status == "all") {
            $category = Category::all();
        } else {
            if ($status == 1) {
                $delete = -1;
            } else {
                $delete = 1;
            }
            $category = Category::where('cat_isDeleted', '=', $delete)->get();

        }

        $trans_category = Translation::select('*')
            ->where('trn_type', '=', $this->category_trans_type)
            ->where('lng_id', '=', $this->lang);


        $category = $category->map(function ($value) use ($trans_category) {
            $trn = Translation::where('trn_type', '=', $this->category_trans_type);

            $value->cat_name = $trn->where('trn_foreignKey', '=', $value->cat_id)
                ->first()->trn_text;

            if ($value->cat_parent == 0) {
                $value->parent_name = "No parent";
            } else {
                $value->parent_name = Translation::select('*')
                    ->where('trn_type', '=', $this->category_trans_type)
                    ->where('lng_id', '=', $this->lang)
                    ->where('trn_foreignKey', '=', $value->cat_parent)
                    ->first()->trn_text;
            }


            return $value;
        });

        return $category;
    }

    public function getCategory($id)
    {
        $category = Category::where('cat_id', '=', $id)->first();
        $category->trans = Translation::where('trn_type', '=', $this->category_trans_type)
            ->where('trn_foreignKey', '=', $id)
            ->get();
        return $category;

    }

    public function getSubCategory($parent, $status)
    {
        if ($parent == -1) {
            $parent = 0;
        }

        if($status == "all") {
            $category = Category::where('cat_parent', '=', $parent)
                ->get();
        }else {
            if ($status == 1) {
                $delete = -1;
            } else {
                $delete = 1;
            }
            $category = Category::where('cat_parent', '=', $parent)
                ->where('cat_isDeleted', '=', $delete)
                ->get();
        }

        $trans_category = Translation::select('*')
            ->where('trn_type', '=', $this->category_trans_type)
            ->where('lng_id', '=', $this->lang);


        $category = $category->map(function ($value) use ($trans_category) {
            $trn = Translation::where('trn_type', '=', $this->category_trans_type);

            $value->cat_name = $trn->where('trn_foreignKey', '=', $value->cat_id)
                ->first()->trn_text;

            if ($value->cat_parent == 0) {
                $value->parent_name = "No parent";
            } else {
                $value->parent_name = Translation::select('*')
                    ->where('trn_type', '=', $this->category_trans_type)
                    ->where('lng_id', '=', $this->lang)
                    ->where('trn_foreignKey', '=', $value->cat_parent)
                    ->first()->trn_text;
            }


            return $value;
        });

        return $category;
    }
*/
}
