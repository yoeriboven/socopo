<?php

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Returns the rules to validate this model
     *
     * @return array
     */
    public static function getValidationRules()
    {
        return [
            'name' => 'required|min:3|max:50',
            'vat_id' => 'nullable|vat_number',
            'address' => 'required|min:5|max:100',
            'postal' => 'required|max:15',
            'city' => 'required|max:50',
            'country' => ['required', Rule::in(array_keys(config('countries')))],
        ];
    }
}
