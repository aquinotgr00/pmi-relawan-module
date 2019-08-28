<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\Subdistrict;
use BajakLautMalaka\PmiRelawan\City;
use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use BajakLautMalaka\PmiRelawan\Village;
use Faker\Generator as Faker;

$factory->define(Volunteer::class, function (Faker $faker) {
    $city           = City::all()->random();
    $subdistrict    = Subdistrict::where('city_id',$city->id)->get()->random();
    $village        = Village::where('subdistrict_id',$subdistrict->id)->get()->random();
    return [
        'phone'=>$faker->e164PhoneNumber,
        'image'=>$faker->imageUrl(),
        'dob'=>$faker->date,
        'birthplace'=>City::all()->random()->name,
        'gender'=>$faker->randomElement(['male','female']),
        'religion'=>$faker->randomElement(config('volunteer.religion')),
        'blood_type'=>$faker->randomElement(config('volunteer.bloodType')),
        'address'=>$faker->streetAddress,
        'province'=>'DKI Jakarta',
        'city'=>$city->name,
        'subdistrict'=>$subdistrict->name,
        'subdivision'=>$village->name,
        'postal_code'=>$faker->postcode,
        'verified'=>$faker->boolean,
        'unit_id'=>UnitVolunteer::all()->random()->id
    ];
});
