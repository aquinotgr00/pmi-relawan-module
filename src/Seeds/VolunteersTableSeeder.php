<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

use Illuminate\Database\Seeder;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\Qualification;

class VolunteersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Volunteer::class, 200)->create()->each(function($volunteer) {
            $volunteer->qualifications()->saveMany(factory(Qualification::class, rand(1,5))->make());
        });
    }
}
