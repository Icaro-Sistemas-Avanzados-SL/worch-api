<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'email_verified_at' => $faker->date('Y-m-d H:i:s'),
        'password' => $faker->password,
        'remember_token' => Str::random(10),

    ];
});
