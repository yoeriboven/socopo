<?php

namespace App\Billing;

use Laravel\Cashier\SubscriptionBuilder as BaseSubscriptionBuilder;

class SubscriptionBuilder extends BaseSubscriptionBuilder
{
    /**
     * Create a new Stripe subscription.
     *
     * Note: we added the storing of current_period_end
     * the rest of the method comes from Laravel\Cashier\SubscriptionBuilder
     *
     * @param  string|null  $token
     * @param  array  $options
     * @return \Laravel\Cashier\Subscription
     */
    public function create($token = null, array $options = [])
    {
        $customer = $this->getStripeCustomer($token, $options);

        $subscription = $customer->subscriptions->create($this->buildPayload());

        if ($this->skipTrial) {
            $trialEndsAt = null;
        } else {
            $trialEndsAt = $this->trialExpires;
        }

        return $this->owner->subscriptions()->create([
            'name' => $this->name,
            'stripe_id' => $subscription->id,
            'stripe_plan' => $this->plan,
            'quantity' => $this->quantity,
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => null,
            'current_period_end' => $subscription->current_period_end
        ]);
    }
}
