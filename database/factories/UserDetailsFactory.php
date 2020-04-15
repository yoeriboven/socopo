<?php

use Faker\Generator as Faker;

$factory->define(App\UserDetails::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->streetAddress,
        'postal' => $faker->postcode,
        'city' => $faker->city,
        'country' => 'US'
    ];
});
