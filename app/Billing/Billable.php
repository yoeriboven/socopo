<?php

namespace App\Billing;

trait Billable
{
    use \Laravel\Cashier\Billable {
        \Laravel\Cashier\Billable::subscription as parentSubscription;
    }

    /**
     * Gets the subscriptions and returns whether at least one is valid
     *
     * @return bool
     */
    public function isSubscribed()
    {
        return !! $this->subscriptions->filter(function ($subscription) {
            return $subscription->valid();
        })->count();
    }

    /**
     * Get a subscription instance (by name).
     *
     * @param  string  $subscription
     * @return \Laravel\Cashier\Subscription|null
     */
    public function subscription($subscription = null)
    {
        // Return the first found subscription
        if (is_null($subscription)) {
            return $this->subscriptions->sortByDesc(function ($value) {
                return $value->created_at->getTimestamp();
            })->first();
        }

        // Return the first found subscription with the $subscription name
        return $this->parentSubscription($subscription);
    }

    /**
    * Get all of the subscriptions for the Stripe model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->orderBy('created_at', 'desc');
    }

    /**
     * Begin creating a new subscription.
     *
     * @param  string  $subscription
     * @param  string  $plan
     * @return \Laravel\Cashier\SubscriptionBuilder
     */
    public function newSubscription($subscription, $plan)
    {
        return new SubscriptionBuilder($this, $subscription, $plan);
    }
}
