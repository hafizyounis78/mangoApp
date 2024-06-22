<?php

namespace App\Http\Controllers\Api;

use App\FollowingSeller;
use App\Instruction;
use App\Mail\ResetPassword;
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

class InstructionController extends Controller
{

    public function instructions()
    {

        $lang = getLang(app()->getLocale());
        return $this->responseJson(true, 'success',  $this->getAllInstruction($lang->lng_id));
    }

    public function getAllInstruction($lang)
    {
        $instructions = Instruction::select('id', 'image', 'orderBy')
            ->where('isActive', '=', 1)
            ->orderBy('orderBy', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $instructions = $instructions->map(function ($value) use ($lang) {
            $trn = getTranslation($value->id, $lang, instruction_trans_type());
            $value->image = $this->fullPath($value->image);
            $value->title = $trn->trn_text;
            $value->description = $trn->trn_desc;
            return $value;
        });
        return $instructions;
    }

}
