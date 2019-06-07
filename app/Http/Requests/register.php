<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class register extends FormRequest
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
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'rpassword' => 'required|same:password',
            'role' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'salary' => 'required',
            'avatar' => 'image'
        ];
    }
}
