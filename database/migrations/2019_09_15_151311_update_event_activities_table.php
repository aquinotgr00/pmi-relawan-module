<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEventActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('volunteer_id')->nullable()->change();
            $table->unsignedBigInteger('admin_id')->nullable();
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
            $table->unsignedInteger('volunteer_id');
            $table->dropColumn('admin_id');
        });
    }
}
