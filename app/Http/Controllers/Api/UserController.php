<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\FollowingSeller;
use App\Mail\ResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use Lcobucci\JWT\Parser;

class UserController extends Controller
{

    /*
     * Type of user
     * 1 >> user
     * 2 >> driver
     */

    /*
     * Type of idSocialMedia
     * 1 >> facebook
     * 2 >> google
     * 3 >> twitter
     *
     */

    public function login_social_medial_old(Request $request)
    {

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
        return Route::dispatch($proxy);
    }
    public function login_social_medial(Request $request)
    {
        $request->request->add(['email' => $request->get('username')]);
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
        $token = Route::dispatch($proxy);
        //return $token;// $token ;/*
        // dd($token);
        $StatusCode = json_decode($token->getStatusCode());
        // dd($StatusCode);
        if ($StatusCode==200) {

            $user = User::where('email', $request->get('email'))->first();
            if (isset($user)) {
                $data = json_decode($token->getContent());
                $statusCode = json_decode($token->getStatusCode());
                $user->generateToken();
                if (isset($data->error)) {
                    return [
                        'status' => false,
                        'statusCode' => $statusCode,
                        'message' => $data->message,
                        'data' => []
                    ];
                }
                $userdata = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'verficationCode' => $user->verficationCode,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                if ($user->isActive == -1) {

                    return $this->responseJson(true, 'not_active', ['token' =>$data,'user' => $userdata], trans('users.user'));
                } /*else if ($user->isVerified == -1) {

                    return $this->responseJson(true, 'not_verified', ['token' =>$data,'user' => $userdata] ,trans('users.user'));

                }*/


                // $response = $this->access_token($request);

                $data = json_decode($token->getContent());
                $statusCode = json_decode($token->getStatusCode());

                if (isset($data->error)) {
                    return [
                        'status' => false,
                        'statusCode' => $statusCode,
                        'message' => $data->message,
                        'data' => []
                    ];
                }
                $user->fcmToken = $request->fcmToken;
                $user->deviceType = $request->deviceType;
                $user->isVerified =1;
                $user->lat = $request->get('lat');
                $user->lng = $request->get('lng');
//                $user->generateToken();
                //       $user->api_token=$this->access_token($request->all());
//                $user->tokenExpire = Carbon::now()->addDays(60);
                $user->save();
                $userdata = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'verficationCode' => $user->verficationCode,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                return [
                    'status' => true,
                    'statusCode' => 200,
                    'message' => 'Success',
                    'data' => [
                        'token' => $data,
                        'user' => $userdata
                    ]
                ];
            }
        }
        return response()->json(['status' => false, 'status_code' => 401, 'message' => 'The user credentials were incorrect.', 'data' => []]);
    }

    public function access_token(Request $request)
    {


        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return Route::dispatch($proxy);
    }

    public function refresh_token(Request $request)
    {
        $proxy = Request::create(
            'oauth/token',
            'POST'
        );
        $response = Route::dispatch($proxy);


        $data = json_decode($response->getContent());
        $statusCode = json_decode($response->getStatusCode());

        if (isset($data->error)) {
            return [
                'status' => false,
                'statusCode' => $statusCode,
                'message' => $data->message,
                'items' => []
            ];
        }


        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'Success',
            'items' => [
                'token' => $data
            ]
        ];
    }

    public function login(Request $request)
    {


        $request->request->add(['email' => $request->get('username')]);
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string',
            'fcmToken' => 'required',
            'deviceType' => 'required',
            'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $messages = $validator->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson(false, $errors,null, null);

        } else {

            $user = User::where('email', $request->get('email'))->first();
            if (isset($user)) {
                $user->generateToken();
                $userdata = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'verficationCode' => $user->verficationCode,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                if ($user->isActive == -1) {

                    return $this->responseJson(true, 'not_active', ['token' =>null,'user' => $userdata] ,trans('users.user'));
                }
                /*else if ($user->isVerified == -1) {

                    return $this->responseJson(true, 'not_verified', ['token' =>null,'user' => $userdata] ,trans('users.user'));

                }*/


                $response = $this->access_token($request);

                $data = json_decode($response->getContent());
                $statusCode = json_decode($response->getStatusCode());

                if (isset($data->error)) {
                    return [
                        'status' => false,
                        'statusCode' => $statusCode,
                        'message' => $data->message,
                        'data' => []
                    ];
                }
                $user->fcmToken = $request->fcmToken;
                $user->deviceType = $request->deviceType;
                $user->lat = $request->get('lat');
                $user->lng = $request->get('lng');
                $user->isVerified =1;
//                $user->generateToken();
                //       $user->api_token=$this->access_token($request->all());
//                $user->tokenExpire = Carbon::now()->addDays(60);
                $user->save();
                $userdata = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'verficationCode' => $user->verficationCode,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                return [
                    'status' => true,
                    'statusCode' => 200,
                    'message' => 'Success',
                    'data' => [
                        'token' => $data,
                        'user' => $userdata
                    ]
                ];
            }
            /**
             *
             * if ($user->isActive == -1) {
             * return $this->responseJson(false, 'not_active', null, trans('users.user'));
             * } else if ($user->isVerified == -1) {
             *
             *
             * $user = Auth::user();
             *
             * $data = [
             * 'id' => $user->id,
             * 'name' => $user->name,
             * 'email' => $user->email,
             * 'mobile' => $user->mobile,
             * 'type' => $user->type,
             * 'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
             * 'apiToken' => $user->api_token,
             * 'isVerified' => $user->isVerified,
             * 'verficationCode' => $user->verficationCode,
             * 'lat' => $user->lat,
             * 'lng' => $user->lng,
             * 'city_id' => $user->city,
             * 'city_name' => $user->getCityName(),
             * 'address' => $user->address,
             * ];
             *
             * return $this->responseJson(true, trans('api.not_verified'), json_decode(json_encode($data), false), NULL);
             *
             * } else {
             *
             *
             * $lat = $user->lat;
             * $lng = $user->lng;
             *
             * if ($request->has('lat')) {
             * $lat = $request->lat;
             * }
             * if ($request->has('lng')) {
             * $lng = $request->lng;
             * }
             *
             * $user->fcmToken = $request->fcmToken;
             * $user->deviceType = $request->deviceType;
             * $user->lat = $lat;
             * $user->lng = $lng;
             * $user->generateToken();
             * //       $user->api_token=$this->access_token($request->all());
             * $user->tokenExpire = Carbon::now()->addDays(60);
             * $user->save();
             *
             * $user = $this->userDetails(User::find($user->id));
             *
             *
             * $user = Auth::user();
             *
             * $data = [
             * 'id' => $user->id,
             * 'name' => $user->name,
             * 'email' => $user->email,
             * 'mobile' => $user->mobile,
             * 'type' => $user->type,
             * 'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
             * 'apiToken' => $user->api_token,
             * 'isVerified' => $user->isVerified,
             * 'verficationCode' => $user->verficationCode,
             * 'lat' => $user->lat,
             * 'lng' => $user->lng,
             * 'city_id' => $user->city,
             * 'city_name' => $user->getCityName(),
             * 'address' => $user->address,
             * ];
             *
             * return $this->responseJson2(true, trans('api.success'), json_decode(json_encode($data), false));
             * }
             * }
             */

//            return $this->sendFailedLoginResponse($request);
        }

        return response()->json(['status' => false, 'status_code' => 401, 'message' => 'The user credentials were incorrect.', 'data' => []]);

    }
    public function login_registeration(Request $request)
    {


        $request->request->add(['username' => $request->get('email')]);
       // $rules = [
           // 'email' => 'required|email',
           // 'password' => 'required|string',
           // 'fcmToken' => 'required',
           // 'deviceType' => 'required',
           // 'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
          //  'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],

     //   ];

      /*  $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $messages = $validator->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson(false, $errors,null, null);

        } else 9*/{

            $user = User::where('email', $request->get('email'))->first();
            if (isset($user)) {
                $user->generateToken();
                $userdata = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'verficationCode' => $user->verficationCode,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                if ($user->isActive == -1) {

                    return $this->responseJson(true, 'not_active', ['token' =>null,'user' => $userdata] ,trans('users.user'));
                }
                /*else if ($user->isVerified == -1) {

                    return $this->responseJson(true, 'not_verified', ['token' =>null,'user' => $userdata] ,trans('users.user'));

                }*/


                $response = $this->access_token($request);

                $data = json_decode($response->getContent());
                $statusCode = json_decode($response->getStatusCode());

                if (isset($data->error)) {
                    return [
                        'status' => false,
                        'statusCode' => $statusCode,
                        'message' => $data->message,
                        'data' => []
                    ];
                }
                $user->fcmToken = $request->fcmToken;
                $user->deviceType = $request->deviceType;
                $user->lat = $request->get('lat');
                $user->lng = $request->get('lng');
                $user->isVerified =1;
//                $user->generateToken();
                //       $user->api_token=$this->access_token($request->all());
//                $user->tokenExpire = Carbon::now()->addDays(60);
                $user->save();
                $userdata = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'verficationCode' => $user->verficationCode,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                return [
                    'status' => true,
                    'statusCode' => 200,
                    'message' => 'Success',
                    'data' => [
                        'token' => $data,
                        'user' => $userdata
                    ]
                ];
            }
            /**
             *
             * if ($user->isActive == -1) {
             * return $this->responseJson(false, 'not_active', null, trans('users.user'));
             * } else if ($user->isVerified == -1) {
             *
             *
             * $user = Auth::user();
             *
             * $data = [
             * 'id' => $user->id,
             * 'name' => $user->name,
             * 'email' => $user->email,
             * 'mobile' => $user->mobile,
             * 'type' => $user->type,
             * 'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
             * 'apiToken' => $user->api_token,
             * 'isVerified' => $user->isVerified,
             * 'verficationCode' => $user->verficationCode,
             * 'lat' => $user->lat,
             * 'lng' => $user->lng,
             * 'city_id' => $user->city,
             * 'city_name' => $user->getCityName(),
             * 'address' => $user->address,
             * ];
             *
             * return $this->responseJson(true, trans('api.not_verified'), json_decode(json_encode($data), false), NULL);
             *
             * } else {
             *
             *
             * $lat = $user->lat;
             * $lng = $user->lng;
             *
             * if ($request->has('lat')) {
             * $lat = $request->lat;
             * }
             * if ($request->has('lng')) {
             * $lng = $request->lng;
             * }
             *
             * $user->fcmToken = $request->fcmToken;
             * $user->deviceType = $request->deviceType;
             * $user->lat = $lat;
             * $user->lng = $lng;
             * $user->generateToken();
             * //       $user->api_token=$this->access_token($request->all());
             * $user->tokenExpire = Carbon::now()->addDays(60);
             * $user->save();
             *
             * $user = $this->userDetails(User::find($user->id));
             *
             *
             * $user = Auth::user();
             *
             * $data = [
             * 'id' => $user->id,
             * 'name' => $user->name,
             * 'email' => $user->email,
             * 'mobile' => $user->mobile,
             * 'type' => $user->type,
             * 'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
             * 'apiToken' => $user->api_token,
             * 'isVerified' => $user->isVerified,
             * 'verficationCode' => $user->verficationCode,
             * 'lat' => $user->lat,
             * 'lng' => $user->lng,
             * 'city_id' => $user->city,
             * 'city_name' => $user->getCityName(),
             * 'address' => $user->address,
             * ];
             *
             * return $this->responseJson2(true, trans('api.success'), json_decode(json_encode($data), false));
             * }
             * }
             */

//            return $this->sendFailedLoginResponse($request);
        }

        return response()->json(['status' => false, 'status_code' => 401, 'message' => 'The user credentials were incorrect.', 'data' => []]);

    }
    public function loginGoogle(Request $request)
    {

        $request->request->add(['email' => $request->get('username')]);
        $request->request->add(['password' => $request->get('idSocialMedia')]);
        $rules = [
            'email' => 'email',
            //  'mobile' => 'numeric|unique:users,mobile',
            'name' => 'required',
            'idSocialMedia' => 'required',
            'typeSocialMedia' => 'required',
            'fcmToken' => 'required',
            'deviceType' => 'required',
            'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ];
        //dd('password'.$request->password);
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        }


        $lat = 0.0;
        $lng = 0.0;
        $path = '';
        $mobile = null;
        if ($request->has('image')) {
            // $path = $this->storeImage2($request->image, '/user/img/');
            $path = $request->image;
        }
        if ($request->has('lat')) {
            $lat = $request->lat;
        }
        if ($request->has('lng')) {
            $lng = $request->lng;
        }
        if ($request->has('mobile') && $request->exists('mobile') && !empty($request->mobile)) {
            $mobile = $request->mobile;
        }

        $user = User::where('email', $request->email)
            ->where('idSocialMedia', '=', $request->idSocialMedia)->first();

        if (isset($user)) {
            //   dd(' found');
            $user->update([
                'deviceType' => $request->deviceType,
                'lat' => $lat,
                'lng' => $lng,
                'fcmToken' => $request->fcmToken,
            ]);
            //  dd($user);
            $response = $this->access_token($request);
            $data = json_decode($response->getContent());
            $statusCode = json_decode($response->getStatusCode());
            // dd($response);
            if (isset($data->error)) {
                return [
                    'status' => false,
                    'statusCode' => $statusCode,
                    'message' => $data->message,
                    'items' => []
                ];
            }
            $userdata = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'verficationCode' => $user->verficationCode,
                'typeSocialMedia' => $user->typeSocialMedia,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,

            ];
            if ($user->isActive == -1) {

                return $this->responseJson(true, 'not_active', ['token' => $data, 'user' => $userdata], trans('users.user'));
            }
           /* else if ($user->isVerified == -1) {
                return $this->responseJson(true, 'not_verified', ['token' => $data, 'user' => $userdata], trans('users.user'));
            }*/
            return [
                'status' => true,
                'statusCode' => 200,
                'message' => 'Success',
                'data' => [
                    'token' => $data,
                    'user' => $userdata
                ]
            ];
        }
        else {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_pass' => bcrypt($request->password),
                'mobile' => $mobile,
                'image' => $path,
                'idSocialMedia' => $request->idSocialMedia,
                'fcmToken' => $request->fcmToken,
                'typeSocialMedia' => 2,
                'deviceType' => $request->deviceType,
                'isVerified' => 1,
                'city' => -1,
                'lat' => $lat,
                'lng' => $lng,
                'type' => 3

            ]);
            $user->generateToken();
            $user->facebookSocialAccounts()->create([
                'provider_id' => $request->idSocialMedia,// $providerUser->getId(),
                'provider_name' => 'google',
            ]);
            // dd($user);
            $response = $this->access_token($request);
            $data = json_decode($response->getContent());
            $statusCode = json_decode($response->getStatusCode());
            //  dd($statusCode);
            if (isset($data->error)) {
                return [
                    'status' => false,
                    'statusCode' => $statusCode,
                    'message' => $data->message,
                    'items' => []
                ];
            }
            $userdata = [
                'id' => $user->id,
                'name' => $user->name,
                'password'=> bcrypt($request->password),
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'typeSocialMedia' => $user->typeSocialMedia,
                'verficationCode' => $user->verficationCode,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,
            ];
            return [
                'status' => true,
                'statusCode' => 200,
                'message' => 'Success',
                'data' => [
                    'token' => $data,
                    'user' => $userdata
                ]
            ];
            return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
        }


    }
    public function loginSocial(Request $request)
    {

        //|unique:users,email
        $rules = [
            'email' => 'email',
            //  'mobile' => 'numeric|unique:users,mobile',
            'name' => 'required',
            'idSocialMedia' => 'required',
            'typeSocialMedia' => 'required',
            'fcmToken' => 'required',
            'deviceType' => 'required',
            'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']
        ];
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {


            $response = $this->access_token($request);


            $data = json_decode($response->getContent());
            $statusCode = json_decode($response->getStatusCode());

            if (isset($data->error)) {
                return [
                    'status' => false,
                    'statusCode' => $statusCode,
                    'message' => $data->message,
                    'items' => []
                ];
            }

            $deviceType = $request->deviceType;

            // $user = User::where('email', $request->email);
            $user = User::where('email', $request->email)
                ->where('idSocialMedia', '=', $request->idSocialMedia);
            $path = '';
            if ($request->has('image')) {
                // $path = $this->storeImage2($request->image, '/user/img/');
                $path = $request->image;
            }

            $lat = 0.0;
            $lng = 0.0;

            if ($request->has('lat')) {
                $lat = $request->lat;
            }
            if ($request->has('lng')) {
                $lng = $request->lng;
            }

            if ($user->exists()) {
                /*  if ($user->type == 1) {
                     return $this->responseJson(false, 'admin_user', null, trans('users.user'));
                 }
                 else */
                if ($user->first()->isActive == -1) {
                    return $this->responseJson(false, 'not_active', null, 'user');
                } else {

                    $lat2 = $user->first()->lat;
                    $lng2 = $user->first()->lng;

                    if ($request->has('lat')) {
                        $lat2 = $request->lat;
                    }
                    if ($request->has('lng')) {
                        $lng2 = $request->lng;
                    }

                    $user->update([
                        'deviceType' => $deviceType,
                        'lat' => $lat2,
                        'lng' => $lng2,
                        'fcmToken' => $request->fcmToken,
                    ]);
                    $user = $user->first();
                }

            } else {
                $name = $request->name;
                $email = "";
                if ($request->has('email')) {
                    $email = $request->email;
                }

                $mobile = null;
                if ($request->has('mobile') && $request->exists('mobile') && !empty($request->mobile)) {
                    $mobile = $request->mobile;
                }

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'mobile' => $mobile,
                    'image' => $path,
                    'idSocialMedia' => $request->idSocialMedia,
                    'typeSocialMedia' => $request->typeSocialMedia,
                    'fcmToken' => $request->fcmToken,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'deviceType' => $request->deviceType,
                    'lat' => $lat,
                    'lng' => $lng,
                    'type' => 3

                ]);
                $user->isVerified = 1;
                $user->city = -1;
                $user->tokenExpire = Carbon::now()->addDays(60);
                $user->save();

            }

            $user->generateToken();
            /*$user = $this->userDetails($user);
            return $this->responseJson(true, 'success', $user);*/

            //$user = Auth::user();

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'typeSocialMedia'=>$user->typeSocialMedia,
                'verficationCode' => $user->verficationCode,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,
            ];

            return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
        }
    }

    public function register(Request $request)
    {

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|numeric|unique:users,mobile',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
            'fcmToken' => 'required',
            'deviceType' => 'required',
            'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/']

        ];
        $validator = Validator::make($request->all(), $rules);

        $attributeNames = array(
            'mobile' => trans('users.mobile'),

        );
        $validator->setAttributeNames($attributeNames);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else {

            $name = $request->name;
            $email = $request->email;
            $mobile = $request->mobile;
            $password = bcrypt($request->password);
            /*
            if($request->exists('image') && !empty($request->image)) {
                $image = $request->image;
            }
            */

            $fcmToken = $request->fcmToken;
            $deviceType = $request->deviceType;
            $lat = 0.0;
            $lng = 0.0;
            if ($request->has('lat')) {
                $lat = $request->lat;
            }
            if ($request->has('lng')) {
                $lng = $request->lng;
            }


            $path = '';
            if ($request->exists('image') && !empty($request->image)) {
                $image = $request->image;
                // $path = $this->storeImage($image, '/user/img/');
                $path = $this->storeImage($image, '/user/img/', false);
            }

            $data = array(
                'name' => $name,
                'user_pass' => $password,
                'email' => $email,
                'mobile' => $mobile,
                'image' => $path,
                'isActive' => 1,
                'isAdmin' => -1,
                'fcmToken' => $fcmToken,
                'typeSocialMedia'=>0,
                'deviceType' => $deviceType,
                'lat' => $lat,
                'lng' => $lng,
                'type' => 3


            );
            $user = User::create($data);
            $user->verficationCode = "1234";
            $user->verficationExpire = Carbon::now()->addHours(6);
            $user->tokenExpire = Carbon::now()->addDays(60);
            $user->isVerified=1;
            $user->save();
            $user->generateToken();
            $user = User::find($user->id);

            if ($request->exists('image') && !empty($request->image)) {
                $user['image'] = $this->fullPath($user->image);
            }
            /* $user->city_id = $user->city;
             $user->city = getTranslation($user->city, lang(), city_trans_type())['trn_text'];*/
            /*$user = $this->userDetails($user);
            return $this->responseJson(true, 'success', $user);*/


            //$user = Auth::user();

       /*     $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'verficationCode' => $user->verficationCode,
                'typeSocialMedia'=>$user->typeSocialMedia,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,
            ];*/
            $LoginData = $this->login_registeration($request);
            //  return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
            return response()->json($LoginData);

            //return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
        }


    }

    public function verificationMobile(Request $request)
    {
        /*$user = $request->user();
        $rules = [
            'mobile' => 'required|numeric|unique:users,mobile,' . $user->id,

        ];*/

        $authorization = $request->header('Authorization', '');
        $authorization = explode(' ', $authorization);
        $authorization = (array_key_exists(1, $authorization)) ? $authorization[1] : ((array_key_exists(0, $authorization)) ? $authorization[0] : '');
        $user = User::where([
            ['api_token', '=', $authorization],
            ['mobile', '=', $request->input('mobile', '')],
        ])->first();

        if ($user) {
            /* $validator = Validator::make($request->all(), $rules);
             if ($validator->fails()) {
                 $messages = $validator->errors();
                 $errors = $this->validatorErrorMsg($rules, $messages);
                 return $this->responseJson2(false, $errors, null);

             } else {*/
            $user->mobile = $request->mobile;
            // $user->isVerified = -1;
            $user->verficationCode = "1234";
            $user->verficationExpire = Carbon::now()->addHours(6);
            $user->save();
            return $this->responseJson(true, 'send_verify', null);
            // }
        } else {
            return $this->responseJson(false, 'not_found', null, trans('users.user'));

        }


    }

    public function activateMobile(Request $request)
    {
        //$user = $request->user();

        //if(!$user){
        $authorization = $request->header('Authorization', '');
        $authorization = explode(' ', $authorization);
        $authorization = (array_key_exists(1, $authorization)) ? $authorization[1] : ((array_key_exists(0, $authorization)) ? $authorization[0] : '');
        $user = User::where([
            ['verficationCode', '=', $request->input('verificationCode', '')],
            ['api_token', '=', $authorization],
        ])->first();
        //}

        if ($user) {
            $rules = [
                'mobile' => 'required|numeric|unique:users,mobile' . (($user) ? ',' . $user->id : ''),
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->responseJson(false, 'mobile_exists', null);
            }

            /*$validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $messages = $validator->errors();
                $errors = $this->validatorErrorMsg($rules, $messages);
                return $this->responseJson2(false, $errors, null);

            }*/ /*else if ($user->isVerified == 1) {
                return $this->responseJson(false, 'already_verified', null, null);
            }*//* else if ($user->verficationExpire < Carbon::now()) {
                return $this->responseJson(false, 'verificationExpire', null, null);
            } else {*/
            $verficationCode = $request->verificationCode;
            if ($verficationCode == $user->verficationCode) {

                $user->isVerified = 1;
                $user->mobile = $request->mobile;
                $user->save();
                /*$user = $this->userDetails($user);
                return $this->responseJson(true, 'verified', $user, null);*/


                //$user = Auth::user();

                $data = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'type' => $user->type,
                    'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                    'apiToken' => $user->api_token,
                    'isVerified' => $user->isVerified,
                    'typeSocialMedia'=>$user->typeSocialMedia,
                    'verficationCode' => $user->verficationCode,
                    'lat' => $user->lat,
                    'lng' => $user->lng,
                    'city_id' => $user->city,
                    'city_name' => $user->getCityName(),
                    'address' => $user->address,

                ];
                //$data= $this->login($request);
                if ($request->login_type == 'social')
                {
                    //dd('ddsss');
                    $data = $this->login_social_medial($request);
                    // dd($data);
                }
                else
                    $data = $this->login($request);
                //  return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
                return response()->json($data);
            } else {
                return $this->responseJson(false, 'incorrect_activation_code', null);
            }
            //}
        } else {
            return $this->responseJson(false, 'incorrect_activation_code', null);
        }

    }

    public function verifiedCode(Request $request)
    {
        $user = $request->user();
        $rules = [
            'verificationCode' => 'required',

        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);

        } else if ($user) {

            if ($user->isVerified == 1) {
                return $this->responseJson(false, 'already_verified', null, null);
            } else {
                $verficationCode = $request->verificationCode;
                if ($verficationCode == $user->verficationCode) {

                    //   $user->generateToken();
                    //  $user->tokenExpire = Carbon::now()->addDays(4);
                    $user->isVerified = 1;
                    $user->save();
                    $user = $this->userDetails($user);
                    return $this->responseJson(true, 'verified', $user, null);
                } else {
                    /*  $user->verficationCode = "1234";
                      $user->verficationExpire = Carbon::now()->addHours(6);
                      $user->update();
                    */
                    return $this->responseJson(false, 'error_verified', ["isVerified" => -1], null);
                }
            }

        } else {
            return $this->responseJson(false, 'not_found', ["isVerified" => -1], trans('users.user'));
        }


    }

    public function reSendVerificationCode(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->verficationCode = "1234";
            $user->verficationExpire = Carbon::now()->addHours(6);
            $user->save();
            return $this->responseJson(true, 'send_verify', null);

        } else {
            return $this->responseJson(false, 'not_found', ["isVerified" => -1], trans('users.user'));

        }
    }


    public function updateInfo(Request $request)
    {
        $user = $request->user();

        $rules = [
            // 'name' => 'required|min:3',
            // 'city' => 'required|exists:cities,cit_id',
            /* 'email' => [
                 'email' ,
                  'unique:users,email,'.$user->id
             ]*/
            //'mobile' => 'required|numeric|unique:users,mobile,'.$user->id ,
        ];


        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {


            $user->name = $request->name;
            if ($request->has('image')) {

                $getPath = $user->image;
                $getPath = public_path() . "/storage" . $getPath;
                File::delete($getPath);

                $image = $request->image;
                $path = $this->storeImage2($image, '/user/img/');
                $user->image = $path;
            }

            if ($request->exists('email') && $request->has('email') && !empty($request->email)) {
                $rules = [];
                $rules['email'] = 'email|unique:users,email,' . $user->id;

                $validate = Validator::make($request->all(), $rules);

                if ($validate->fails()) {

                    $messages = $validate->errors();
                    $errors = $this->validatorErrorMsg($rules, $messages);
                    return $this->responseJson2(false, $errors, null);
                } else {
                    $user->email = $request->email;
                }
            }

            if ($request->city != -1) {
                $user->city = $request->city;
            }


            /*
            if ($request->exists('cardNo') && !empty($request->cardNo)) {
                $user->cardNo = $request->cardNo;
            }

            if ($request->exists('expireYear') && !empty($request->expireYear)) {
                if ($request->expireYear < Carbon::now()->year) {
                    return $this->responseJson2(false, "expireYear", null, 'en');
                } else {
                    $user->expireYear = $request->expireYear;
                    $expireYear = $request->expireYear;
                }

            }
            if ($request->exists('expireMonth') && !empty($request->expireMonth)) {
                if ($request->expireMonth < 1 || $request->expireMonth > 12) {
                    return $this->responseJson2(false, "validMonth", null, 'en');
                } else if (($expireYear >= Carbon::now()->year) && ($request->expireMonth <= Carbon::now()->month)) {

                    return $this->responseJson2(false, "expireMonth", null, 'en');
                } else {
                    $user->expireMonth = $request->expireMonth;
                }
            }

            if ($request->exists('ccv') && !empty($request->ccv)) {
                $user->ccv = $request->ccv;
            } else {
                $user->ccv = 0;
            }
            if ($request->exists('cardHolderName') && !empty($request->cardHolderName)) {
                $user->cardHolderName = $request->cardHolderName;
            }*/

            $user->save();
            /*$user['image'] = $this->fullPath($user->image);
            $user->city_id = $user->city;
            $user->city = getTranslation($user->city, lang(), city_trans_type())['trn_text'];*/
            /*$user = $this->userDetails($user);

            return $this->responseJson(true, 'success', $user);*/

            $user = Auth::user();

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'typeSocialMedia'=>$user->typeSocialMedia,
                'verficationCode' => $user->verficationCode,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,
            ];

            return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
        }


    }

    public function changeImage(Request $request)
    {
        $user = $request->user();
        $rules = [
            'image' => 'required',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            $image = $request->image;
            $path = $this->storeImage($image, '/user/img/', false);
            $user->image = $path;
            $user->save();
            /*$user->image = $this->fullPath($user->image);
            return $this->responseJson(true, 'success', $user);*/

            $user = Auth::user();

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'typeSocialMedia'=>$user->typeSocialMedia,
                'verficationCode' => $user->verficationCode,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,
            ];

            return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
        }
    }

    public function changeMobile(Request $request)
    {
        //$user = $request->user();
        //dd($authorization);
        $authorization = $request->header('Authorization', '');
        $authorization = explode(' ', $authorization);
        $authorization = (array_key_exists(1, $authorization)) ? $authorization[1] : ((array_key_exists(0, $authorization)) ? $authorization[0] : '');

        $user = User::where('api_token', '=', $authorization)->first();

        $rules = [
            'mobile' => 'required|numeric|unique:users,mobile,' . $user->id,
        ];


        $validate = Validator::make($request->all(), $rules);
        $attributeNames = array(
            'mobile' => trans('users.mobile'),
        );
        $validate->setAttributeNames($attributeNames);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            //$user->mobile = $request->mobile;
            //  $user->isVerified = -1;
            if ($request->has('isSocial'))
                if ($request->isSocial)
                {
                    $user->mobile = $request->mobile;
                    $user->isVerified = -1;

                }
            $user->verficationCode = "1234";
            $user->verficationExpire = Carbon::now()->addHours(6);
            $user->save();
            //return $this->responseJson(true, 'send_verify', null);

            //    $user = Auth::user();

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'type' => $user->type,
                'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
                'apiToken' => $user->api_token,
                'isVerified' => $user->isVerified,
                'typeSocialMedia'=>$user->typeSocialMedia,
                'verficationCode' => $user->verficationCode,
                'lat' => $user->lat,
                'lng' => $user->lng,
                'city_id' => $user->city,
                'city_name' => $user->getCityName(),
                'address' => $user->address,
            ];

            return $this->responseJson(true, 'success', json_decode(json_encode($data), false));
        }

    }


    public function addNewAddressOld(Request $request)
    {
        $user = $request->user();
        $rules = [
            //  'first_name' => 'required',
            //'last_name' => 'required',
            'city' => 'required|exists:cities,cit_id',
            'address' => 'required',
            'mobile' => 'required|numeric',
            'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];


        $validate = Validator::make($request->all(), $rules);
        $attributeNames = array(
            'mobile' => trans('users.mobile'),
        );
        $validate->setAttributeNames($attributeNames);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {

            $user_id = $user->id;
            $first_name = $request->first_name;
            if (empty($request->last_name)) {
                $last_name = "";
            } else {
                $last_name = $request->last_name;
            }

            $city = $request->city;
            $address = $request->address;
            $mobile = $request->mobile;
            $lat = $request->lat;
            $lng = $request->lng;

            $new_address = Address::create([
                'user_id' => $user_id,
                'adr_firstName' => $first_name,
                'adr_lastName' => $last_name,
                'adr_city' => $city,
                'adr_address' => $address,
                'adr_mobile' => $mobile,
                'lat' => $lat,
                'lng' => $lng
            ]);

            Address::where('adr_id', '=', $new_address->adr_id)->update([
                'adr_isDefault' => $request->isDefault
            ]);

            if (count($user->addresses) == 1) {
                $new_address->adr_isDefault = 1;
                $new_address->save();
            }

            if ($request->isDefault == 1) {
                Address::where([
                    ['user_id', '=', Auth::user()->id],
                    ['adr_isDefault', '=', 1],
                    ['adr_id', '<>', $new_address->adr_id],
                ])
                    ->update(['adr_isDefault' => -1]);
            }


            /*  if ($request->isDefault == 1) {
                  Address::select('*')->update([
                      'adr_isDefault' => -1
                  ]);
                  Address::where('adr_id', '=', $new_address->adr_id)->update([
                      'adr_isDefault' => 1
                  ]);
              }*/

            /* $new_address->city_id = $new_address->adr_city;
              $new_address->city = getTranslation($new_address->adr_city, lang(), 'city')['trn_text'];
  */
            $new_address = $this->addCityDetails($new_address);
            return $this->responseJson(true, 'success', $new_address);
        }
    }

    public function addNewAddress(Request $request)
    {
        $user = $request->user();

        $rules = [
            //  'first_name' => 'required',
            //'last_name' => 'required',
            'city' => 'required|exists:cities,cit_id',
            'address' => 'required',
            'mobile' => 'required|numeric',
            'lat' => ['numeric', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['numeric', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
        ];


        $validate = Validator::make($request->all(), $rules);
        $attributeNames = array(
            'mobile' => trans('users.mobile'),
        );
        $validate->setAttributeNames($attributeNames);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            // dd($user->id);

            $addressCouunt = Address::where('user_id', $user->id)->where('adr_isDeleted', '=', '-1')->get();
            // dd(count($addressCouunt));
            if (count($addressCouunt) == 0)
                $adr_isDefault = 1;
            elseif (count($addressCouunt) > 0 && $request->has('isDefault') && $request->isDefault == 1) {
                Address::where('user_id', '=', Auth::user()->id)->update(['adr_isDefault' => -1]);
                $adr_isDefault = 1;
            } elseif (count($addressCouunt) > 0) {
                $address = Address::where('user_id', '=', Auth::user()->id)
                    ->where('adr_isDefault', '=', 1)->where('adr_isDeleted', '=', '-1')->get();
                if (count($address) > 0)
                    $adr_isDefault = -1;
                else
                    $adr_isDefault = 1;
            } else
                if ($request->isDefault == null)
                    $adr_isDefault = -1;
                else
                    $adr_isDefault = $request->isDefault;

            $user_id = $user->id;
            $first_name = $request->first_name;
            if (empty($request->last_name)) {
                $last_name = "";
            } else {
                $last_name = $request->last_name;
            }

            $city = $request->city;
            $address = $request->address;
            $mobile = $request->mobile;
            $lat = $request->lat;
            $lng = $request->lng;

            $new_address = new Address();
            $new_address->user_id = $user_id;
            $new_address->adr_firstName = $first_name;
            $new_address->adr_lastName = $last_name;
            $new_address->adr_city = $city;
            $new_address->adr_address = $address;
            $new_address->adr_mobile = $mobile;
            $new_address->lat = $lat;
            $new_address->lng = $lng;
            $new_address->adr_isDefault = $adr_isDefault;
            $new_address->save();

            /* Address::where('adr_id', '=', $new_address->adr_id)->update([
                 'adr_isDefault' => $request->isDefault
             ]);

             if (count($user->addresses) == 1) {
                 $new_address->adr_isDefault = 1;
                 $new_address->save();
             }

             if ($request->isDefault == 1) {
                 Address::where([
                     ['user_id', '=', Auth::user()->id],
                     ['adr_isDefault', '=', 1],
                     ['adr_id', '<>', $new_address->adr_id],
                 ])
                     ->update(['adr_isDefault' => -1]);
             }

 */
            /*  if ($request->isDefault == 1) {
                  Address::select('*')->update([
                      'adr_isDefault' => -1
                  ]);
                  Address::where('adr_id', '=', $new_address->adr_id)->update([
                      'adr_isDefault' => 1
                  ]);
              }*/

            /* $new_address->city_id = $new_address->adr_city;
              $new_address->city = getTranslation($new_address->adr_city, lang(), 'city')['trn_text'];
  */
            $new_address = $this->addCityDetails($new_address);
            return $this->responseJson(true, 'success', $new_address);
        }
    }

    public function getAddresses(Request $request)
    {

        $user = Auth::user(); //$request->user();
        $address_user = $user->addresses->where('adr_isDeleted', '=', -1);
        $address = $address_user->map(function ($value) {
            $value = $this->addCityDetails($value);
            return $value;
        })->values();
        return $this->responseJson(true, 'success', $address);

    }

    /*  public function deleteAddress(Request $request)
      {

          $id = $request->address_id;
          $address = Address::find($id);
          $userId=$address->user_id;
      //    dd($userId);
          if ($address) {
              $is_default = $address->adr_isDefault;
              $address->delete();
              $default_address = NULL;

              //  dd(auth()->user()->addresses()->count());
              if ($is_default && auth()->user()->addresses()->count() > 0) {
                  $default_address = auth()->user()->addresses()->orderBy('adr_id', 'asc')->first();
                  $default_address->adr_isDefault = 1;
                  $default_address->save();

              }
              $address->adr_isDeleted = 1;
              $address->save();

              if($default_address instanceof Address){
                  $default_address = $this->addCityDetails($default_address);
              }
              return $this->responseJson(true, 'success', $default_address);
          } else {
              $msg = trans('users.error_address');
              return $this->responseJson2(false, $msg, null);
          }
      }
  */
    public function deleteAddress(Request $request)
    {
        $user = $request->user();
        $id = $request->address_id;
        $address = Address::find($id);
        if ($address) {
            $is_default = $address->adr_isDefault;
            if ($is_default == -1) {
                $address->adr_isDeleted = 1;

                $address->save();
                return $this->responseJson(true, 'success', $address);
            } else {
                $address->adr_isDeleted = 1;
                $address->adr_isDefault = -1;
                $address->save();
                //dd($user->id);
                $default_address = Address::where('user_id', $user->id)
                    ->where('adr_isDeleted', '=', -1)->orderBy('adr_id', 'asc')->first();
                if ($default_address) {
                    $default_address->adr_isDefault = 1;
                    $default_address->save();
                    return $this->responseJson(true, 'success', $default_address);
                } else
                    return $this->responseJson(true, 'success', null);
            }


        } else {
            $msg = trans('users.error_address');
            return $this->responseJson2(false, $msg, null);
        }
    }

    public function setAsDefaultAddress(Request $request)
    {
        $id = $request->address_id;
        $address = Address::find($id);
        if ($address) {
            Address::select('*')->update([
                'adr_isDefault' => -1
            ]);
            Address::where('adr_id', '=', $address->adr_id)->update([
                'adr_isDefault' => 1
            ]);

            return $this->responseJson(true, 'success', null);
        } else {
            $msg = trans('users.error_address');
            return $this->responseJson2(false, $msg, null);
        }
    }

    public function updateCardInfo(Request $request)
    {
        $user = $request->user();

        $rules = [
            'cardNo' => 'required|numeric',
            'expireMonth' => 'required|integer|between:1,12',
            'expireYear' => 'required',
            'ccv' => 'required|numeric',
            'cardHolderName' => 'required',
        ];


        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            $user->cardNo = $request->cardNo;
            $user->expireMonth = $request->expireMonth;
            $user->expireYear = $request->expireYear;
            $user->ccv = $request->ccv;
            $user->cardHolderName = $request->cardHolderName;
            $user->save();
            return $this->responseJson(true, 'success', $user);
        }


    }

    public function forgetPasswordDriver(Request $request)
    {
        $rules = [
            'email' => 'required|email|exists:users'
        ];
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {

            $email = $request->email;
            $user = User::where('email', $email)->first();

            $subject = "Forget Password for " . $email;

            $message = "The user :" . $user->name . " forget his/her password.";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: <' . $email . '>' . "\r\n";

            if (@mail('hafizyounis@gmail.com', $subject, $message, $headers)) {
                $msg = trans('success');
                return $this->responseJson2(true, trans('api.success'), $msg);
            } else {
                $msg = trans('error');
                return $this->responseJson2(false, $msg, null);
            }
        }

    }

    public function changeMobileDriver(Request $request)
    {
        $user = auth()->user();
        // dd($user);

        $rules = [
            'new_mobile' => 'required|numeric|unique:users,mobile',
        ];


        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            $new_mobile = $request->new_mobile;
            $subject = "Change Mobile No. for :" . $user->name;

            $message = "The user :" . $user->name . " request change his/her mobile number to : " . $new_mobile;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: <' . $user->email . '>' . "\r\n";

            if (@mail('hafizyounis@gmail.com', $subject, $message, $headers)) {
                $msg = trans('success');
                return $this->responseJson2(true, trans('api.success'), $msg);
            } else {
                $msg = trans('error');
                return $this->responseJson2(false, $msg, null);
            }
        }


    }

    public function show(Request $request)
    {

        $user = Auth::user();
        $user->generateToken();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'type' => $user->type,
            'image' => (!empty($user->image)) ? $this->fullPath($user->image) : '',
            'apiToken' => $user->api_token,
            'isVerified' => $user->isVerified,
            'typeSocialMedia'=>$user->typeSocialMedia,
            'verficationCode' => $user->verficationCode,
            'lat' => $user->lat,
            'lng' => $user->lng,
            'city_id' => $user->city,
            'city_name' => $user->getCityName(),
            'address' => $user->address,

        ];

        return responseJson(true, 'success', $data);

    }

    public function getResetToken(Request $request)
    {

        $rules = [
            'email' => 'required|email|exists:users'
        ];
        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            /*$messages = $validate->errors();
            return $this->responseJson(false, 'error', $this->validatorErrorMsg($rules, $messages));
       */

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {

            $resetToken = str_random(150);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $resetToken,
                'created_at' => Carbon::now()
            ]);

            /*   Mail::to($request->email)
                    ->send(new ResetPassword($request->email, $resetToken));
    */

            $view = (string)\View::make('auth.passwords.resetPassword', ['email' => $request->email, 'token' => $resetToken]);
            $subject = "Password reset";

            $message = $view;
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: <Mango>' . "\r\n";

            if (@mail($request->email, $subject, $message, $headers)) {
                $msg = trans('users.reset_password');
                return $this->responseJson2(true, trans('api.success'), $msg);
            } else {
                $msg = trans('users.email_send_error');
                return $this->responseJson2(false, $msg, null);
            }


        }
    }

    public function reset(Request $request, $token)
    {


        $data = [];
        $validate = Validator::make($request->all(), [
            'new_password' => 'required',
            'password_confirmation' => 'required|same:new_password'
        ]);

        $tokenReset = DB::table('password_resets')
            ->where('token', $token)->exists();

        if ($validate->fails()) {
            return $this->responseJson(false, 'error', $validate->errors());
        } else {

            if (!$tokenReset) {
                return $this->responseJson(false, 'error', null);
            } else {
                $email = DB::table('password_resets')
                    ->where('token', $token)
                    ->orderBy('created_at', 'DESC')
                    ->first()->email;


                $password = bcrypt($request->new_password);
                $user = User::where('email', $email)->first();
                // $user = User::find($user->id);


                $user->update([
                    'user_pass' => $password
                ]);
                $user->generateToken();
                DB::table('password_resets')->where('email', $email)->update([
                    'token' => null,
                ]);
                //    $user->image = $this->fullPath($user->image);
                return $this->responseJson(true, 'success', $user);
            }

        }

    }
//logout
    public function logout(Request $request,$user_id = null)
    {
        $user_id = auth()->user()->id;

        if (!isset($user_id)) {
//            $user_id = auth()->user()->id;
            $user = User::find($user_id);
            if(isset($user)){
                $user->api_token = null;
                $user->fcmToken = null;
                $user->save();}

            $value = \request()->bearerToken();
            $id = (new Parser())->parse($value)->getHeader('jti');
            $token = DB::table('oauth_access_tokens')
                ->where('id', '=', $id)
                ->update(['revoked' => true]);
        } else {

            $token = DB::table('oauth_access_tokens')
                ->where('user_id', '=', $user_id)
                ->update(['revoked' => true]);
            $user = User::find($user_id);
            if(isset($user)){
                $user->api_token = null;
                $user->fcmToken = null;
                $user->save();}
        }

        if ($token)
            return responseJson(true,'logout' , null, 200);
        return responseJson(false, 'not logout', null, 422);
    }
//    public function logout(Request $request)
//    {
//        $user = $request->user();
//
//        if ($user) {
//            $user->api_token = null;
//            $user->fcmToken = null;
//            $user->save();
//            return $this->responseJson(true, 'success', null);
//        } else {
//            return $this->responseJson(true, 'not_found', null, 'user');
//        }
//
//
//    }

    public function username()
    {
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = ['email' => trans('auth.failed')];

        if ($request->expectsJson()) {
            //   return response()->json($errors, 422);
            return $this->responseJson2(false, trans('auth.failed'), null);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function refreshFcmToken(Request $request)
    {
        $user = $request->user();
        $user->fcmToken = $request->fcmToken;
        $user->save();
        return $this->responseJson(true, 'success', $user);

    }

    public function changePassword(Request $request)
    {

        // check if the old password is correct


        $rules = [
            'old_password' => 'required',
            'new_password' => 'required',
            'new_password_confirmation' => 'same:new_password',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else {
            $user = $request->user();
            if (Hash::check($request->old_password, $user->user_pass)) {
                $user->update(['user_pass' => Hash::make($request->new_password)]);
                return $this->responseJson(true, 'success', $user);
            } else {
                return $this->responseJson2(false, 'errorOldPassword', null, 'ar', null);
            }
        }


    }


    public function userDetails($user)
    {
        $address = Address::where('user_id', '=', $user->id)
            ->where('adr_isDefault', '=', 1)
            ->first();
        $user['image'] = $this->fullPath($user->image);
        $user->idSocialMedia = $user->idSocialMedia;
        $user->typeSocialMedia = (int)$user->typeSocialMedia;
        $user->deviceType = (int)$user->deviceType;
        $user->lat = (float)$user->lat;
        $user->lng = (float)$user->lng;
        $user->city_id = $user->city_id;
        $user->city_name = $user->city_name;
        $user->city = getTranslation($user->city, lang(), city_trans_type())['trn_text'];
        if ($address) {
            $user->address = $this->addCityDetails($address);
        } else {
            $user->address = null;
        }


        return $user;
    }

    public function addCityDetails($address)
    {
        $address->city_id = (int)$address->adr_city;
        $address->city = getTranslation($address->adr_city, lang(), 'city')['trn_text'];
        return $address;
    }
}
