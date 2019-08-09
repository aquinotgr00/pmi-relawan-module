<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedAreaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        array_map(function($area) {
            $path = __DIR__."/json/$area.json";
            $json = json_decode(file_get_contents($path), true);
            DB::table($area)->insert($json['rows']);
        },[
            'provinces',
            'cities',
            'subdistricts',
            'villages'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('TRUNCATE TABLE villages');
        DB::statement('TRUNCATE TABLE subdistricts');
        DB::statement('TRUNCATE TABLE cities');
        DB::statement('TRUNCATE TABLE provinces');
    }
}
