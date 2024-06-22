<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserInfoRequest extends FormRequest
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
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->id,'id')
            ],
            'mobile' => [
                'required',
                'numeric',
                Rule::unique('users')->ignore($this->id,'id')
            ],


        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'البريد الإلكتروني مسجل مسبقا',
            'name.required' => 'الرجاء كتابة الاسم',
        ];
    }
}
