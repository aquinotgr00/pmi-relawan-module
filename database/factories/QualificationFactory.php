<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use BajakLautMalaka\PmiRelawan\Qualification;
use Faker\Generator as Faker;

$factory->define(Qualification::class, function (Faker $faker) {
    return [
        'description'=>$faker->paragraph(),
        'category'=>$faker->randomElement(array_keys(config('volunteer.qualification.category'))),
        'volunteer_id'=>null
    ];
});
