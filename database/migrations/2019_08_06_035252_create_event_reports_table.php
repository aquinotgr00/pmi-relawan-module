<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('volunteer_id');
            $table->unsignedInteger('admin_id')->nullable();
            $table->unsignedInteger('urban_village_id')->nullable();
            $table->unsignedInteger('participant_pic_id')->nullable();
            $table->string('title')->unique();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('location')->nullable();
            $table->string('image');
            $table->string('image_file_name')->nullable();
            $table->boolean('approved')->nullable();
            $table->boolean('archived')->default(0);
            $table->boolean('emergency')->default(0);
            $table->string('reason_rejection')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('event_reports');
    }
}
