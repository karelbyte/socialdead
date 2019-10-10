<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapsulesConstablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsules_constables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_uid');
            $table->bigInteger('capsule_id')->unsigned();
            $table->foreign('capsule_id')->references('id')->on('capsules')->onDelete('cascade');
            $table->char('key', 5);
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
        Schema::dropIfExists('capsules_constables');
    }
}
