<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required',
            'password_confirmation' => 'required|same:new_password'
        ];
    }

    public function messages()
    {
        return [
          'old_password.required' => 'The old password is required'  ,
            'new_password.required' => 'please write new password',
            'password_confirmation.required' => 'please confirm new password',
            'password_confirmation.same' => 'The old and new password is not identical'
        ];
    }
}
