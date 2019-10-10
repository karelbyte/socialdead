<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapsulesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsules_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('capsule_id')->unsigned();
            $table->foreign('capsule_id')->references('id')->on('capsules')->onDelete('cascade');
            $table->tinyInteger('type');
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->string('doc', 150);
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
        Schema::dropIfExists('capsules_details');
    }
}
