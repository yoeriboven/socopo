<?php

use Faker\Generator as Faker;

$factory->define(App\UserDetails::class, function (Faker $faker) {
    return [
        'business' => true,
        'country' => 'US',
        'vat_id' => '',
        'name' => $faker->company,
        'street' => $faker->streetAddress,
        'postal' => $faker->postcode,
        'city' => $faker->city
    ];
});
