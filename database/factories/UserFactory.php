<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    $gender = $faker->randomElement(['male', 'female']);
    return [
        'name' => $faker->name($gender),
        'email' => $faker->safeEmail,
        'email_verified_at' => $faker->date('Y-m-d H:i:s'),
        'password' => bcrypt('secret'),
        'bio' => $faker->realText(),
        'phone' => $faker->phoneNumber,
        'birthdate' => $faker->date(),
        'gender' => $gender,
        'instagram' => $faker->word,
        'facebook' => $faker->word,
        'avatar' => $faker->imageUrl(),
        'remember_token' => Str::random(10),
    ];
});
