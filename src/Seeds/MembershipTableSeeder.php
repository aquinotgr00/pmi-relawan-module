<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

use Illuminate\Database\Seeder;
use BajakLautMalaka\PmiRelawan\Membership;

class MembershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $memberships =  factory(Membership::class, 10)->create()->each(function ($u) {
			if ($u->id % 2 == 0) {
				\BajakLautMalaka\PmiRelawan\Membership::find($u->id)->update(['parent_id'=>$u->id]);
			}
		});
    }
}
