<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Model\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'user' => $faker->unique()->name,
        'telephone' => $faker->numberBetween($min = 000000001, $max = 999999999),
        'type' => $faker->numberBetween($min = 1, $max = 2),
        'cep' => $faker->numberBetween($min = 0000001, $max = 9999999),
        'street' => $faker->streetName,
        'number' => $faker->numberBetween($min = 1, $max = 100),
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'complement' => null,
        'reference' => null,
        'active' => 1,
        'secondary_telephone' => null,
        'secondary_email' => null,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
