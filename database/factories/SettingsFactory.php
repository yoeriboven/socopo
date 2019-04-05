<?php

use Faker\Generator as Faker;

$factory->define(App\Settings::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\User')->create()
    ];
});
