<?php

use Faker\Generator as Faker;

$factory->define(App\Billing\Subscription::class, function (Faker $faker) {
    return [
        'user_id' => '0',
        'name' => 'Pro',
        'stripe_id' => 'sub_Eeiaoeqk',
        'stripe_plan' => 'plan_Eiaqeyb',
        'quantity' => 1
    ];
});
