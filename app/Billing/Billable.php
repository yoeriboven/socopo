<?php

namespace App\Billing;

use Mpociot\VatCalculator\Facades\VatCalculator;

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

    /**
     * Checks whether the user is a business based on whether the vat_id is valid
     *
     * @return bool
     */
    public function isBusiness()
    {
        return VatCalculator::isValidVATNumber($this->details->vat_id);
    }

    /**
     * Get the tax percentage to apply to the subscription.
     *
     * @return int
     */
    public function getTaxPercent()
    {
        return VatCalculator::getTaxRateForCountry($this->details->country, $this->isBusiness()) * 100;
    }

    /**
     * Get the tax percentage to apply to the subscription for Cashier > 6.0.
     *
     * @return int
     */
    public function taxPercentage()
    {
        return $this->getTaxPercent();
    }
}
