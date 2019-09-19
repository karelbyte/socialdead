<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('from_user');
            $table->bigInteger('photo_id')->unsigned();
            $table->foreign('photo_id')->references('id')->on('photos')->onDelete('cascade');
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
        Schema::dropIfExists('photos_comments');
    }
}
