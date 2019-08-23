<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BajakLautMalaka\PmiRelawan\City;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use Faker\Generator as Faker;

$factory->define(UnitVolunteer::class, function (Faker $faker) {
    return [
        'city_id'=>City::all()->random()->id,
        'membership_id'=>Membership::all()->random()->id,
        'name'=>$faker->sentence(3)
    ];
});
