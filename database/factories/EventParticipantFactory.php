<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BajakLautMalaka\PmiRelawan\EventParticipant;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiAdmin\Admin;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Log;

$factory->define(EventParticipant::class, function (Faker $faker, $params) {
    static $combo = [];

    $volunteer_id = Volunteer::verified()->get()->random()->id;
    if(array_key_exists($params['event_report_id'], $combo)) {
        while (in_array($volunteer_id, $combo[$params['event_report_id']])) {
            $volunteer_id = Volunteer::verified()->get()->random()->id;
        }
    }
    $combo[$params['event_report_id']][] = $volunteer_id;
    $approved = $faker->optional()->boolean();
    return [
        'volunteer_id'=>$volunteer_id,
        'approved'=>$approved,
        'admin_id'=>is_null($approved)?null:Admin::active()->get()->random()->id
    ];
    
});
