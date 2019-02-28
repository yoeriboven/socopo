<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $profile_id = factory('App\Profile')->create()->id;
    $date = \Carbon\Carbon::now()->subMinutes(21);

    // Use either video or image as the type
    $type = ['video', 'image'][rand(0, 1)];

    return [
        'profile_id' => $profile_id,
        'ig_post_id' => $faker->randomNumber(),
        'caption' => $faker->sentence(),
        'type' => $type,
        'link' => $faker->imageUrl(),
        'posted_at' => $date
    ];
});
