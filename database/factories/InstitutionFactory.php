<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Institution;
use Faker\Generator as Faker;

$factory->define(Institution::class, function (Faker $faker) {
    return [
        'id_user' => $faker->unique()->numberBetween($min = 50, $max = 100),
        'reason' => $faker->catchPhrase,
        'fantasy' => $faker->company,
        'cpf' => null,
        'cnpj' => $faker->unique()->numberBetween($min = 10000000000000, $max = 99999999999999)
    ];
});
