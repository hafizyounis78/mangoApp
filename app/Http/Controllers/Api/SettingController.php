<?php

namespace App\Http\Controllers\Api;

use App\FollowingSeller;
use App\Instruction;
use App\Mail\ResetPassword;
use App\Setting;
use App\Translation;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

class SettingController extends Controller
{
    public function setting(Request $request)
    {
        $setting = Setting::find(1);
		$data = [
			'app_link' => $setting->set_app_link,
			'phone' => $setting->set_phone,
			'email' => $setting->set_email,
			'about_us' => (lang() == 1)? $setting->set_about_us_en : $setting->set_about_us_ar,
		];
        return $this->responseJson(true, 'success', $data);
    }
    public function aboutUs(Request $request)
    {
        if(lang() == 1) {
            $aboutUs = "set_about_us_en";
        }else {
            $aboutUs = "set_about_us_ar";
        }
        $setting = Setting::select($aboutUs)->first();
        return $this->responseJson(true, 'success', $setting);
    }

}