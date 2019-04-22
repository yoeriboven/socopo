<?php

namespace App\Billing;

use Illuminate\Support\Carbon;
use Laravel\Cashier\Subscription as BaseSubscription;

class Subscription extends BaseSubscription
{
    public function getRenewDateAttribute()
    {
        $subscription = $this->asStripeSubscription();

        return Carbon::createFromTimeStamp($subscription->current_period_end)->format('F jS, Y');
    }
}
