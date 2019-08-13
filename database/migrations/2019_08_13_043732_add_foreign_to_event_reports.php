<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToEventReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_reports', function (Blueprint $table) {
            $table->bigInteger('volunteer_id')->unsigned()->change();
        });
        
        Schema::table('event_reports', function (Blueprint $table) {
            $table->foreign('volunteer_id')->references('id')->on('volunteers');
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
            $table->dropForeign('event_reports_volunteer_id_foreign');
            
        });
    }
}
