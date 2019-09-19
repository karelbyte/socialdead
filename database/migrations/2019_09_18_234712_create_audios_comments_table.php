<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiosCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audios_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('from_user');
            $table->bigInteger('audio_id')->unsigned();
            $table->foreign('audio_id')->references('id')->on('audios')->onDelete('cascade');
            $table->string('note', 1000);
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
        Schema::dropIfExists('audios_comments');
    }
}
