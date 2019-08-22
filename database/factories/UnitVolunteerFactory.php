<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use BajakLautMalaka\PmiRelawan\Membership;
use BajakLautMalaka\PmiRelawan\City;
use Faker\Generator as Faker;

$factory->define(UnitVolunteer::class, function (Faker $faker) {
	$membership = Membership::all()->random();
	$city 		= City::all()->random();
    return [
        'membership_id' => $membership->id,
        'city_id' => $city->id ,
        'name' => $faker->sentence
    ];
});
