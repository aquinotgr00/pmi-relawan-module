<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BajakLautMalaka\PmiAdmin\Admin;
use BajakLautMalaka\PmiRelawan\Village;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\EventReport;
use Faker\Generator as Faker;

$factory->define(EventReport::class, function (Faker $faker) {
    $number_of_active_admins 	= Admin::active()->count();
    $number_of_villages 		= Village::all()->count();
    $volunteer                  = Volunteer::all()->random();
    return [
        'volunteer_id' => $volunteer->id,
        'admin_id'=>$faker->numberBetween(2,$number_of_active_admins),
        'village_id'=>$faker->numberBetween(2,$number_of_villages),
        'moderator_id'=>$volunteer->id,
        'title'=> $faker->sentence,
        'description'=> $faker->paragraph(),
        'location'=>$faker->address,
        'image'=>$faker->imageUrl(640,480),
        'image_file_name'=>$faker->imageUrl(640,480),
        'approved'=>$faker->boolean,
        'archived'=>$faker->boolean,
        'emergency'=>$faker->boolean,
        'reason_rejection'=> $faker->sentence
    ];
});
