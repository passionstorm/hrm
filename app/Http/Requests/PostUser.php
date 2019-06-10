<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUser extends FormRequest
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
            'email' => 'required',
            'retype_password' => 'same:password',
            'name' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

}
