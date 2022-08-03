<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'required'  => 'This field is required',
            'string'  =>   'This field must be a string',
            'email'  =>   'Enter valid email',

            'first_name.max'  =>   'The text must not exceed 255 characters',
            'last_name.max'  =>   'The text must not exceed 255 characters',
            'email.max'  =>   'The text must not exceed 255 characters',
            'password.min'  =>   'Password must not be less than 8 characters',

            'email.unique'  =>   'This email is already registered',
            'password_confirmation.same'  =>   'Password does not match',

        ];
    }
}
