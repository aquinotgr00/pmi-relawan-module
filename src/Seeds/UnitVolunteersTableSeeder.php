<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

use BajakLautMalaka\PmiRelawan\UnitVolunteer;
use Illuminate\Database\Seeder;

class UnitVolunteersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(UnitVolunteer::class, 25)->create();
    }
}
