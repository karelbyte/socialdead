<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapsulesEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsules_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('capsule_id')->unsigned();
            $table->foreign('capsule_id')->references('id')->on('capsules')->onDelete('cascade');
            $table->string('email', 191);
            $table->string('token', 50);
            $table->smallInteger('status_id');
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
        Schema::dropIfExists('capsules_emails');
    }
}
