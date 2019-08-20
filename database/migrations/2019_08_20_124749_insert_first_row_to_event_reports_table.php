<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class InsertFirstRowToEventReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('event_reports')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('event_reports')->insert(
            [
                'title'=>'Diskusi Umum',
                'description'=>'Diskusi umum Palang Merah Indonesia - DKI Jakarta',
                'image'=>'',
                'approved'=>true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
