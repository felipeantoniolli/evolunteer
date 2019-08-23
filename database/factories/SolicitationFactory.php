<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Solicitation;
use Faker\Generator as Faker;

$factory->define(Solicitation::class, function (Faker $faker) {
    return [
        'id_volunteer' => $faker->numberBetween($min = 1, $max = 50),
        'id_institution' => $faker->numberBetween($min = 1, $max = 50),
        'message' => $faker->word,
        'approved' => $faker->numberBetween($min = 0, $max = 2),
        'justification' =>$faker->word
    ];
});
