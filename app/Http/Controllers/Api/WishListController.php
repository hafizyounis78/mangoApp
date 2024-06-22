<?php

namespace App\Http\Controllers\Api;

use App\FollowingSeller;
use App\Instruction;
use App\Mail\ResetPassword;
use App\Product;
use App\Translation;
use App\WishList;
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

class WishListController extends Controller
{
    public $productCon;

    public function __construct()
    {
        $this->productCon = new ProductController();
    }

    public function wishList(Request $request)
    {
        $user = $request->user();
        $rules = [
            'product_id' => 'required|exists:products,prd_id',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else if (!$this->ifInWithList($user->id, $request->product_id)) {
            WishList::create([
                'user_id' => $user->id,
                'prd_id' => $request->product_id
            ]);

            return $this->responseJson(true, 'add_to_withList', null);

        } else {
            $wishList = WishList::where('user_id', '=', $user->id)
                ->where('prd_id', '=', $request->product_id);

            $wishList->delete();
            return $this->responseJson(true, 'delete_to_withList', null);
        }
    }

    public function addTOWishList(Request $request)
    {
        $user = $request->user();

        $rules = [
            'product_id' => 'required|exists:products,prd_id',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else if ($this->ifInWithList($user->id, $request->product_id)) {
            return $this->responseJson(false, 'already_inWishList', null);
        } else {
            WishList::create([
                'user_id' => $user->id,
                'prd_id' => $request->product_id
            ]);

            return $this->responseJson(true, 'add_to_withList', null);
        }
    }

    public function deleteFromWishList(Request $request)
    {
        $user = $request->user();

        $rules = [
            'product_id' => 'required|exists:products,prd_id',
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            $messages = $validate->errors();
            $errors = $this->validatorErrorMsg($rules, $messages);
            return $this->responseJson2(false, $errors, null);
        } else if (!$this->ifInWithList($user->id, $request->product_id)) {
            return $this->responseJson(false, 'not_inWishList', null);
        } else {
            $wishList = WishList::where('user_id', '=', $user->id)
                ->where('prd_id', '=', $request->product_id);

            $wishList->delete();
            return $this->responseJson(true, 'delete_to_withList', null);
        }
    }

    public function getWishList(Request $request)
    {
        $user = $request->user();
    
        $wishList_user = WishList::where('user_id', '=', $user->id)->pluck('prd_id')->toArray();
        $getProduct = Product::whereIn('prd_id', $wishList_user)->get();
        $getProduct = $this->productCon->productLessDetails($getProduct);
        return $this->responseJson(true, 'success', $getProduct);

    }

    public function ifInWithList($user_id, $product_id)
    {
        $wishList = WishList::where([
			['user_id', '=', $user_id],
			['prd_id', '=', $product_id],
		]);

        if ($wishList->exists()) {
            return true;
        } else {
            return false;
        }
    }
}
