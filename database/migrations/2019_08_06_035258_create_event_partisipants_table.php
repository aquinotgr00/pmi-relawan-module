<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPartisipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_partisipants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('event_report_id');
            $table->unsignedInteger('volunteer_id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->boolean('request_join')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_partisipants');
    }
}
