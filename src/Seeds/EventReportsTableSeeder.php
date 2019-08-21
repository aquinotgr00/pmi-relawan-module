<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

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
    	
        $event_reports =  factory(EventReport::class, 200)->create()->each(function ($u) {
			if ($u->approved) {
				if ($u->id % 2 == 0) {
					\BajakLautMalaka\PmiRelawan\EventReport::find($u->id)->update(['archived'=>$u->id]);
					\BajakLautMalaka\PmiRelawan\EventReport::find($u->id)->delete();
				}
			}
		});
    }
}
