<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Work;
use Faker\Generator as Faker;

$factory->define(Work::class, function (Faker $faker) {
    return [
        'id_institution' => $faker->numberBetween($min = 1, $max = 50),
        'name'=> $faker->realText($maxNbChars = 50),
        'content' => $faker->text($maxNbChars = 200),
        'work_date' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 year', $timezone = null)
    ];
});
