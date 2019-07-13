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
            $table->increments('id');
            $table->bigInteger('talent_type_id')->unsigned()->nullable();
            $table->string('name', 45);
            $table->string('surname');
            $table->date('birthday');
            $table->string('gender');
            $table->bigInteger('phone');
             $table->string('email',191)->unique();
            $table->timestamp('email_verified_at', 6)->nullable();
            $table->string('status');
            $table->string('administrator');
            $table->string('profile_photo');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('talent_type_id')->references('id')->on('talent_types');
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
