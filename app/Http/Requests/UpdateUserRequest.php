<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'required|min:3',
            'email' => [
                'required',
                //  'email',
                Rule::unique('users')->ignore($this->user, 'id'),
            ],
           /* 'mobile' => [
                'required',
                'numeric',
                Rule::unique('users')->ignore($this->id, 'id')
            ],*/
            'mobile' => "required|numeric|unique:users,mobile,".$this->user,
            'password' => 'nullable',
            'user_type' => 'required'

            //  'role'=> 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'The email or phone field is required.'
        ];
    }
}
