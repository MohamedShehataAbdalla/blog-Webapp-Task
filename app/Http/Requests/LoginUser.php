<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUser extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
    public function messages()
    {
        return [
            'required'  => 'This field is required',
            'string'  =>   'This field must be a string',
            'email'  =>   'Enter valid email',
            'email.max'  =>   'The text must not exceed 255 characters',
            'password.min'  =>   'Password must not be less than 8 characters',
            'email.unique'  =>   'This email is already registered',

        ];
    }
}
