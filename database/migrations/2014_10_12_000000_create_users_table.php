<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('uid')->unique();
            $table->string('full_names');
            $table->string('email')->unique();
            $table->string('phone', 15)->nullable();
            $table->string('address')->nullable();
            $table->string('nif', 15)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            //   user information
            $table->string('avatar')->nullable();
            $table->date('birthdate');
            $table->tinyInteger('sex_id')->unsigned();
            $table->foreign('sex_id')->references('id')->on('users_sex');
            $table->string('occupation', 50)->nullable();
            $table->tinyInteger('civil_status_id')->unsigned()->nullable();
            $table->string('birthplace')->nullable();
            $table->string('country')->nullable();
            //--- other user information
            $table->string('who_you_are', 500)->nullable();
            $table->string('website')->nullable();
            $table->string('facebook', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->tinyInteger('religion_id')->unsigned()->nullable();
            $table->tinyInteger('politics_id')->unsigned()->nullable();
            //----------
            $table->tinyInteger('status_id')->unsigned()->default(1);
            $table->foreign('status_id')->references('id')->on('users_status');
            $table->rememberToken();
            $table->timestamps();
            $table->primary('uid');
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
        Schema::dropIfExists('users');
    }
}
