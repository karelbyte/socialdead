<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubRemindersUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_reminders_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sub_reminder_id')->unsigned();
            $table->foreign('sub_reminder_id')->references('id')->on('sub_reminders')->onDelete('cascade');
            $table->uuid('to_user_uid');
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
        Schema::dropIfExists('sub_reminders_users');
    }
}
