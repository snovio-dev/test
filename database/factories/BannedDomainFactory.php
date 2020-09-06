<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BannedDomain;
use Faker\Generator as Faker;

$factory->define(BannedDomain::class, function (Faker $faker) {
    return [
        'domain' => $faker->domainName,
    ];
});
