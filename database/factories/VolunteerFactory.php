<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Volunteer;
use Faker\Generator as Faker;

$factory->define(Volunteer::class, function (Faker $faker) {
    return [
        'id_user' => $faker->unique()->numberBetween($min = 1, $max = 50),
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'cpf' => $faker->unique()->numberBetween($min = 1000000000, $max = 9999999999),
        'rg' => $faker->unique()->numberBetween($min = 100000000, $max = 999999999),
        'birth' => $faker->date($format = 'Y-m-d'),
        'gender' => $faker->numberBetween($min = 0, $max = 2)
    ];
});
