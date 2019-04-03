<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:50',
            'address' => 'required|min:5|max:100',
            'postal' => 'required|max:15',
            'city' => 'required|max:50',
            'country' => ['required', Rule::in(array_keys(config('countries')))]
        ];
    }
}
