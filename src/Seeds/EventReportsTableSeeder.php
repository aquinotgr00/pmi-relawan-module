<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

use BajakLautMalaka\PmiRelawan\EventParticipant;
use Illuminate\Database\Seeder;
use BajakLautMalaka\PmiRelawan\EventReport;

class EventReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(EventReport::class, 200)->create()->each(function ($rsvp) {
			if ($rsvp->approved) {
                $rsvp->participants()->saveMany(
                    factory(EventParticipant::class, rand(0,15))->make(['event_report_id'=>$rsvp->id])
                );
                $archived = (bool)random_int(0, 1);
				if ($archived) {
                    $rsvp->update(['archived'=>$rsvp->id]);
					$rsvp->delete();
				}
			}
		});
    }
}
