<?php

use Illuminate\Database\Migrations\Migration;
use BajakLautMalaka\PmiRelawan\Membership;

class InsertDefaultMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Membership::create(['name'=>'Pengurus']);
        Membership::create(['name'=>'Tenaga Sukarela']);

        $korpsSukarela = Membership::create(['name'=>'Korps Sukarela']);
        Membership::create(['name'=>'Perti', 'parent_id'=>$korpsSukarela->id]);
        Membership::create(['name'=>'Markas', 'parent_id'=>$korpsSukarela->id]);

        $pmr = Membership::create(['name'=>'Palang Merah Remaja']);
        Membership::create(['name'=>'Mula', 'parent_id'=>$pmr->id]);
        Membership::create(['name'=>'Madya', 'parent_id'=>$pmr->id]);
        Membership::create(['name'=>'Wira', 'parent_id'=>$pmr->id]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
