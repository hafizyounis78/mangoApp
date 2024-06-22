<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddLookupRequest extends FormRequest
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
            'lookup_title' => 'required',
            'lookup_parent' =>'required|not_in:-1'
        ];
    }

    public function messages()
    {
        return [
          'lookup_title.required' => 'اسم الثابت مطلوب',
            'lookup_parent.required' => 'التصنيف الرئيسي مطلوب',
            'lookup_parent.not_in' => 'اختر التصنيف'
        ];
    }
}
