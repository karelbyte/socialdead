<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_uid');
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->dateTime('moment');
            $table->string('title', 120)->nullable();
            $table->string('subtitle', 120)->nullable();
            $table->tinyInteger('rating')->unsigned()->default(0);
            $table->string('url', 120)->nullable();
            $table->tinyInteger('status_id')->unsigned()->default(0);
            $table->tinyInteger('in_history')->unsigned()->default(0);
            $table->bigInteger('history_id')->unsigned()->default(0);
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
        Schema::dropIfExists('photos');
    }
}
