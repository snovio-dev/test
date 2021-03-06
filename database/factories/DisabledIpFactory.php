<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DisabledIp;
use Faker\Generator as Faker;

$factory->define(DisabledIp::class, function (Faker $faker) {
    return [
        'ip' => $faker->ipv4,
    ];
});
