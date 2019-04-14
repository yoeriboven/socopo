<?php

namespace App\Http\Requests;

use App\UserDetails;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return UserDetails::getValidationRules();
    }
}
