<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmemberIdToUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('unit_volunteers', 'sub_member_id')) { } else {
            Schema::table('unit_volunteers', function (Blueprint $table) {
                $table->unsignedInteger('sub_member_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       if (Schema::hasColumn('unit_volunteers', 'sub_member_id')) {
            Schema::table('unit_volunteers', function (Blueprint $table) {
                $table->dropColumn('sub_member_id');
            });
        }
    }
}
