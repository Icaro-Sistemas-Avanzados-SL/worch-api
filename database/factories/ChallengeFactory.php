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
            return App\User::inRandomOrder()->first()->id;
        },
        'title' => $faker->word,
        'description' => $faker->text,
        'difficulty' => rand(1, 10),
        'lat' => $faker->latitude,
        'lng' => $faker->longitude,
        'time' => $faker->time(),
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
