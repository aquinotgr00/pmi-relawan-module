<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qualifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description');
            $table->tinyInteger('category');
            $table->unsignedBigInteger('volunteer_id');
            $table->timestamps();
        });
        
        Schema::table('qualifications', function (Blueprint $table) {
            $table->foreign('volunteer_id')
                    ->references('id')->on('volunteers')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qualifications');
    }
}
