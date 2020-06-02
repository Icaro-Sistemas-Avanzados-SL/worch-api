<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Notification;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {

    return [
        'message' => $faker->word,
        'read' => $faker->word,
        'notification_user_id' => $faker->word,
        'notificated_id' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
