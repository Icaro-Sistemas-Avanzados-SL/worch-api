<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Favourite;
use Faker\Generator as Faker;

$factory->define(Favourite::class, function (Faker $faker) {

    return [
        'user_id' => function(){
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'challenge_id' => function(){
            return App\Models\Challenge::inRandomOrder()->first()->id;
        },

    ];
});
