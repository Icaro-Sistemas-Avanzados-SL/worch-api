<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\File;
use Faker\Generator as Faker;

$factory->define(File::class, function (Faker $faker) {

    return [
        'type' => $faker->word,
        'challenge_id' => $faker->word,
        'category_id' => $faker->word,
        'url' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
