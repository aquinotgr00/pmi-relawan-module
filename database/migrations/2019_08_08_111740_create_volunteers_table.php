<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('image')->nullable();
            $table->string('dob')->nullable();
            $table->string('birthplace')->nullable();
            $table->enum('gender', ['male ', 'female'])->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
            $table->longText('address')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('subdivision')->nullable();
            $table->string('postal_code')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volunteers');
    }
}
