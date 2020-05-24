<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Challenge;
use Faker\Generator as Faker;

$factory->define(Challenge::class, function (Faker $faker) {

    return [
        'category_id' =>function(){
            return App\Models\Category::inRandomOrder()->first()->id;
        },
        'user_id' => function(){
            return App\Models\User::inRandomOrder()->first()->id;
        },
        'parent_id' =>function(){
            return App\Models\Challenge::inRandomOrder()->first()->id;
        },
        'title' => $faker->word,
        'description' => $faker->text,
        'difficulty' => rand(1, 10),
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'time' => $faker->time(),
        'address' => $faker->address,
        'slug' => $faker->slug
    ];
});
