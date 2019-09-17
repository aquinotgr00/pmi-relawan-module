<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use BajakLautMalaka\PmiRelawan\EventParticipant;
use BajakLautMalaka\PmiRelawan\EventReport;
use BajakLautMalaka\PmiRelawan\EventActivity;

class EventReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('event_reports')->insert(
        //     [
        //         'title'=>'Diskusi Umum',
        //         'description'=>'Diskusi umum Palang Merah Indonesia - DKI Jakarta',
        //         'image'=>'',
        //         'approved'=>true,
        //         'created_at'=>'2038-01-18 23:59:59'
        //     ]
        // );

        factory(EventReport::class, 5)->create()->each(function ($rsvp) {
			if ($rsvp->approved) {
                $rsvp->participants()->saveMany(
					factory(EventParticipant::class, rand(0,3))
					->make(['event_report_id'=>$rsvp->id])
				);
				$rsvp->participants()->activities()->saveMany(
					factory(EventActivity::class)
					->make(['event_report_id' => $rsvp->id])
				);
                $archived = (bool)random_int(0, 1);
				if ($archived) {
                    $rsvp->update(['archived'=>$rsvp->id]);
					$rsvp->delete();
				}
            }
            if($rsvp->approved===false) {
                $rsvp->update(['archived'=>$rsvp->id]);
            }
		});
    }
}
