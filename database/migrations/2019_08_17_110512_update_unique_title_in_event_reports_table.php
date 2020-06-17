<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUniqueTitleInEventReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('archived')->nullable()->change();
        });

        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('event_reports');

        if(array_key_exists('event_reports_title_unique', $indexes)) {
            Schema::table('event_reports', function (Blueprint $table) {
                $table->dropUnique('event_reports_title_unique');
            });
        }
        
        if(!array_key_exists('event_reports_title_approved_deleted_at_unique', $indexes)) {
            Schema::table('event_reports', function (Blueprint $table) {
                $table->unique(['title','approved','archived']);
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
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('event_reports');
        
        if(array_key_exists('event_reports_title_approved_deleted_at_unique', $indexes)) {
            Schema::table('event_reports', function (Blueprint $table) {
                $table->dropUnique('event_reports_title_approved_archived_unique');
            });
        }
        
    }
}
