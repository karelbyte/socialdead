<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('user_uid');
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->date('moment');
            $table->string('title', 120)->nullable();
            $table->string('subtitle', 120)->nullable();
            $table->mediumText('note')->nullable();
            $table->string('nameto', 120)->nullable();
            $table->string('category', 120)->nullable();
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('type_id');
            $table->bigInteger('item_id');
            $table->unsignedTinyInteger('recurrent');
            $table->unsignedTinyInteger('extend');
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
        Schema::dropIfExists('reminders');
    }
}
