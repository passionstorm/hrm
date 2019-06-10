<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostProject extends FormRequest
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
            'c_name' => 'required',
            'budget' => 'required',
            'deadline' => 'required|date|after:today',
        ];
    }

    public function messages(){
        return [
            'name.required'=>'Project name cannot be empty',
            'c_name.required'=>'Customer name cannot be empty',
            'budget.required'=>'Budget cannot be empty',
            'deadline.required'=>'Deadline cannot be empty',
        ];
    }
}
