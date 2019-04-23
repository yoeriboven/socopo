<?php

namespace App\Billing;

use Laravel\Cashier\Subscription as BaseSubscription;

class Subscription extends BaseSubscription
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at', 'ends_at',
        'created_at', 'updated_at',
        'current_period_end'
    ];
}
