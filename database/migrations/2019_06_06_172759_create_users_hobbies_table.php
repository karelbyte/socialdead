<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersHobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_hobbies', function (Blueprint $table) {
            $table->uuid('user_uid');
            $table->foreign('user_uid')->references('uid')->on('users')->onDelete('cascade');
            $table->string('hobby', 550)->nullable();
            $table->string('music', 550)->nullable();
            $table->string('tv', 550)->nullable();
            $table->string('movies', 550)->nullable();
            $table->string('games', 550)->nullable();
            $table->string('writers', 550)->nullable();
            $table->string('others', 550)->nullable();
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
        Schema::dropIfExists('users_hobbies');
    }
}
