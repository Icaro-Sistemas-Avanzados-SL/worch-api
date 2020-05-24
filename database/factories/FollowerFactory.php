<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Follower;
use Faker\Generator as Faker;

$factory->define(Follower::class, function (Faker $faker) {

    return [
        'follower_id' => function(){
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'followed_id' => function(){
            return App\Models\User::inRandomOrder()->first()->id;
        },

    ];
});
