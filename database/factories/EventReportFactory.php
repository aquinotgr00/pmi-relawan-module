<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BajakLautMalaka\PmiAdmin\Admin;
use BajakLautMalaka\PmiRelawan\Village;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\EventReport;
use Faker\Generator as Faker;

$factory->define(EventReport::class, function (Faker $faker) {
    $rsvpIsCreatedByAdmin = $faker->boolean;
    $randomVillage = $faker->optional()->randomElement(Village::all());
    $approved = $rsvpIsCreatedByAdmin?true:$faker->optional()->boolean;
    return [
        'volunteer_id'=>$rsvpIsCreatedByAdmin?null:Volunteer::verified()->get()->random()->id,
        'admin_id'=>$rsvpIsCreatedByAdmin?Admin::active()->get()->random()->id:null,
        'village_id'=> $randomVillage?$randomVillage->id:null,
        'participant_pic_id'=> Volunteer::verified()->get()->random()->id,
        'title'=> $faker->sentence,
        'description'=> $faker->paragraph(),
        'image'=>$faker->imageUrl(640,480),
        'approved'=> $approved,
        'emergency'=> $faker->boolean,
        'reason_rejection'=> $approved===false?$faker->sentence:NULL 
    ];
});
