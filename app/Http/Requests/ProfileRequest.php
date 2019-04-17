<?php

namespace App\Http\Requests;

use App\Rules\ValidUsername;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [ 'required', 'min:3', 'max:30', new ValidUsername ]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => 'A username is required',
            'username.min' => 'A username must be at least 3 characters.',
            'username.max' => 'A username can not be greater than 30 characters.',
        ];
    }
}
