<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('history_id');
            $table->foreign('history_id')->references('id')->on('histories')->onDelete('cascade');
            $table->tinyInteger('type');
            $table->string('item', 120);
            $table->string('note', 350);
            $table->tinyInteger('status_id')->unsigned();
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
        Schema::dropIfExists('history_details');
    }
}
