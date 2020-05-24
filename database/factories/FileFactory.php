<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {

    return [
        'type' => $faker->randomElement(['image', 'video']),
        'category_id' =>function(){
            return App\Models\Category::inRandomOrder()->first()->id;
        },
        'challenge_id' => function(){
            return App\Models\Challenge::inRandomOrder()->first()->id;
        },
        'url' => $faker->imageUrl(),

    ];
});
