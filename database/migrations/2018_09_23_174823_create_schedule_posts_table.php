<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->date('date');
            $table->timeTz('time');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->unique([
                'date',
                'time',
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
        Schema::dropIfExists('schedule_posts');
    }
}
