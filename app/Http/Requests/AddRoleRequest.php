<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddRoleRequest extends FormRequest
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
            'display_name'=> 'required',
            'description'=> 'required',
            'permission' => 'required'
        ];
    }

    public function messages()
    {
        return [
          'permission.required' => 'الرجاء اختيار صلاحية',
            'display_name.required' => 'الاسم للعرض مطلوب'
        ];
    }
}
