<?php

use Faker\Generator as Faker;
use Laravel\Cashier\Subscription;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'user_id' => '0',
        'name' => 'Pro',
        'stripe_id' => 'sub_Eeiaoeqk',
        'stripe_plan' => 'plan_Eiaqeyb',
        'quantity' => 1
    ];
});
