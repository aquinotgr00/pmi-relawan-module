<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToUnitVolunteers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_volunteers', function (Blueprint $table) {
            $table->bigInteger('city_id')->unsigned()->change();
            $table->bigInteger('membership_id')->unsigned()->change();
        });
        
        Schema::table('unit_volunteers', function (Blueprint $table) {
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('membership_id')->references('id')->on('memberships');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unit_volunteers', function (Blueprint $table) {
            $table->dropForeign('unit_volunteers_city_id_foreign');
            $table->dropForeign('unit_volunteers_membership_id_foreign');
        });
    }
}
