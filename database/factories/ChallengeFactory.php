<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Challenge;
use Faker\Generator as Faker;

$factory->define(Challenge::class, function (Faker $faker) {

    return [
        'category_id' => $faker->word,
        'user_id' => $faker->word,
        'title' => $faker->word,
        'description' => $faker->text,
        'difficulty' => $faker->randomDigitNotNull,
        'lat' => $faker->randomDigitNotNull,
        'lng' => $faker->randomDigitNotNull,
        'time' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
