<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceVolunteerPicIdWithModeratorIdInEventReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('event_reports', 'participant_pic_id')) {
            Schema::table('event_reports', function (Blueprint $table) {
                $table->renameColumn('participant_pic_id', 'moderator_id');
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
        
    }
}
