<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublicationTimeColumnToSchedulePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_posts', function (Blueprint $table) {
            $table->timestamp('publication_time', 0)->nullable();
            $table->dropUnique([
                'date',
                'time',
                'group_id',
            ]);
            $table->dropColumn([
                'date',
                'time'
            ]);
            $table->unique([
                'publication_time',
                'group_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_posts', function (Blueprint $table) {
            $table->dropColumn('publication_time');
            $table->date('date');
            $table->timeTz('time');
            $table->unique([
                'date',
                'time',
                'group_id',
            ]);
            $table->dropUnique([
                'publication_time',
                'group_id',
            ]);
        });
    }
}