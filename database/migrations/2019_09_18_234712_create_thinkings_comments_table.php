<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThinkingsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thinkings_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('from_user');
            $table->bigInteger('thinking_id')->unsigned();
            $table->foreign('thinking_id')->references('id')->on('thinkings')->onDelete('cascade');
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
        Schema::dropIfExists('thinkings_comments');
    }
}
