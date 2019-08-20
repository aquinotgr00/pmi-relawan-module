<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVolunteerIdToUnitVolunteer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unit_volunteers', function (Blueprint $table) {
            $table->unsignedInteger('volunteer_id')->after('membership_id');
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
            $table->dropColumn('volunteer_id');
        });
    }
}
