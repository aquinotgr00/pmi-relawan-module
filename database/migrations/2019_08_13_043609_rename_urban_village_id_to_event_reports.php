<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameUrbanVillageIdToEventReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_reports', function (Blueprint $table) {
            $table->renameColumn('urban_village_id', 'village_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_reports', function (Blueprint $table) {
            $table->renameColumn('village_id', 'urban_village_id');
        });
    }
}
