<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('from_user');
            $table->bigInteger('reminder_id')->unsigned();
            $table->foreign('reminder_id')->references('id')->on('reminders')->onDelete('cascade');
            $table->string('note', 1000);
            $table->dateTime('moment');
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminders_comments');
    }
}
