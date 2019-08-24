<?php
namespace BajakLautMalaka\PmiRelawan\Seeds;

use Illuminate\Database\Seeder;
use BajakLautMalaka\PmiRelawan\Volunteer;
use BajakLautMalaka\PmiRelawan\Qualification;
use App\User;

class VolunteersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 200)->create()->each(function($user) {
            $volunteer = factory(Volunteer::class)->create();
            $volunteer->qualifications()->saveMany(factory(Qualification::class, rand(1,5))->make());
            $user->volunteer()->save($volunteer);
        });
    }
}
