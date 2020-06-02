<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Conversation;
use Faker\Generator as Faker;

$factory->define(Conversation::class, function (Faker $faker) {

    return [
        'guest' => $faker->word,
        'host' => $faker->word,
        'status' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
