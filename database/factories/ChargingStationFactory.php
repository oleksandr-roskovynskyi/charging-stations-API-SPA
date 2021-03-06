<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ChargingStation;
use Faker\Generator as Faker;
use Faker\Provider\uk_UA\Address;
use Faker\Provider\uk_UA\Company;

$factory->define(ChargingStation::class, function (Faker $faker) {
    $faker->addProvider(new Address($faker));
    $faker->addProvider(new Company($faker));

    return [
        'name' => $faker->companyPrefix . ' ' . $faker->companyName . '-' . $faker->companySuffix,
        'city' => $faker->city,  //24
        'open_from' => $faker->time($format = 'H:i'),
        'open_to' => $faker->time($format = 'H:i'),
        'latitude' => $faker->latitude($min = 44, $max = 50),
        'longitude' => $faker->longitude($min = 22, $max = 39),
    ];
});
