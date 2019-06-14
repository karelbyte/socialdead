<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('from_user');
            $table->uuid('to_user');
            $table->foreign('to_user')->references('uid')->on('users')->onDelete('cascade');
            $table->tinyInteger('type_id')->unsigned();
            $table->tinyInteger('status_id')->unsigned();
            $table->dateTime('moment');
            $table->string('note', 120)->nullable();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
