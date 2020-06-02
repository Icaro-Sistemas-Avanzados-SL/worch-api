<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {

    return [
        'message' => $faker->word,
        'is_seen' => $faker->word,
        'deleted_from_sender' => $faker->word,
        'deleted_from_receiver' => $faker->word,
        'user_id' => $faker->word,
        'conversation_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
