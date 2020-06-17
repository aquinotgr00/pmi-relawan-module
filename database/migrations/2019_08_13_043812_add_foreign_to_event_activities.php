<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToEventActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_activities', function (Blueprint $table) {
            $table->bigInteger('event_report_id')->unsigned()->change();
        });
        
        Schema::table('event_activities', function (Blueprint $table) {
            $table->foreign('event_report_id')->references('id')->on('event_reports');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_activities', function (Blueprint $table) {
            $table->dropForeign('event_activities_event_report_id_foreign');
        });
    }
}
