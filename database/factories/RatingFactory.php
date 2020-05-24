<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {

    return [
        'user_id' => function(){
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'challenge_id' => function(){
            return App\Models\Challenge::inRandomOrder()->first()->id;
        },
        'comment' => $faker->text,
        'rate' => rand(1, 10)
    ];
});
