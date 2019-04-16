<?php

use Faker\Generator as Faker;

$factory->define(App\Settings::class, function (Faker $faker) {
    return [
        'user_id' => 0,
        'slack_url' => null
    ];
});
