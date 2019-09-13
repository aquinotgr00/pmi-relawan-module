<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use BajakLautMalaka\PmiRelawan\EventParticipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\EventActivity;
use Faker\Generator as Faker;

$factory->define(EventActivity::class, function (Faker $faker, $params) {
    return [
      'event_report_id' => $params['event_report_id'],
      'volunteer_id' => $params['volunteer_id'],
      'comment' => $faker->sentence
    ];
});