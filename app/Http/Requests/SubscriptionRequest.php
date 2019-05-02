<?php

namespace App\Http\Requests;

use App\UserDetails;
use Illuminate\Validation\Rule;
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
        $subscription_rules = [
            'stripeToken' => 'required',
            'plan' => ['required', Rule::in(app('plans')->pluck('id'))]
        ];

        return array_merge($subscription_rules, UserDetails::getValidationRules());
    }
}
