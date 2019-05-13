<?php

namespace App\Http\Controllers;

use App\Billing\Subscription;
use Illuminate\Support\Carbon;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeWebhookController extends CashierController
{
    /**
     * Handle customer subscription updated.
     *
     * This method handles the current_period_end.
     * The parent method is called and handles the rest.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $data = $payload['data']['object'];

            $user->subscriptions->filter(function (Subscription $subscription) use ($data) {
                return $subscription->stripe_id === $data['id'];
            })->each(function (Subscription $subscription) use ($data) {
                // Current Period End
                if (isset($data['current_period_end'])) {
                    $subscription->current_period_end = Carbon::createFromTimestamp($data['current_period_end']);
                }

                $subscription->save();
            });
        }

        return parent::handleCustomerSubscriptionUpdated($payload);
    }
}
