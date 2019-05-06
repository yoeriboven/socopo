<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $date = \Carbon\Carbon::now()->subMinutes(21);

    // Use either video or image as the type
    $type = ['GraphImage', 'GraphVideo'][rand(0, 1)];

    return [
        'profile_id' => function () {
            factory('App\Profile')->create()->id;
        },
        'ig_post_id' => $faker->randomNumber(),
        'caption' => $faker->sentence(),
        'type' => $type,
        'post_url' => $faker->url,
        'posted_at' => $date
    ];
});
