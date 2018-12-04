<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentSchedulePostPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment_schedule_post', function (Blueprint $table) {
            $table->integer('attachment_id')->unsigned()->index();
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade');
            $table->integer('schedule_post_id')->unsigned()->index();
            $table->foreign('schedule_post_id')->references('id')->on('schedule_posts')->onDelete('cascade');
            $table->primary(['attachment_id', 'schedule_post_id']);
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
        Schema::drop('attachment_schedule_post');
    }
}
