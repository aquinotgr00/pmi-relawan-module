<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BajakLautMalaka\PmiRelawan\EventParticipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\EventActivity;
use Faker\Generator as Faker;

$factory->define(EventActivity::class, function (Faker $faker, $params) {
    $volunteerId = EventReport::find($params['event_report_id'])->participants->random()->volunteer->id;
    $isAdmin = rand(0, 1);
    return [
      'event_report_id' => $params['event_report_id'],
      'volunteer_id' => $isAdmin ? null:$volunteerId,
      'comment' => $faker->sentence,
      'admin_id' => $isAdmin ? 1:null
    ];
});