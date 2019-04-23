<?php

namespace Tests\Traits;

trait InteractsWithStripe
{
    public function getStripeToken()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        return \Stripe\Token::create([
                    'card' => [
                        'number' => '4242424242424242',
                        'exp_month' => 1,
                        'exp_year' => 2025,
                        'cvc' => 123
                    ]
                ])->id;
    }
}
