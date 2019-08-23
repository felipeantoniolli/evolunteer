<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    $volunteers = [
        'id_user' => $faker->numberBetween($min = 1, $max = 50),
        'id_volunteer' => null,
        'id_institution' => $faker->numberBetween($min = 1, $max = 50),
        'note' => $faker->numberBetween($min = 1, $max = 5),
        'message' => $faker->word
    ];

    $institutions = [
        'id_user' => $faker->numberBetween($min = 51, $max = 100),
        'id_volunteer' => $faker->numberBetween($min = 1, $max = 50),
        'id_institution' => null,
        'note' => $faker->numberBetween($min = 1, $max = 5),
        'message' => $faker->word
    ];

    return array_merge($volunteers, $institutions);
});
