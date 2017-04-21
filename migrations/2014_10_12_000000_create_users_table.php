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
            $table->string('name');
            $table->string('nickname',100)->nullable();
            $table->string('email')->unique();
            $table->string('avatar')->default('default.jpg');
            $table->date('birthday')->nullable();
            $table->text('info')->nullable();
            $table->date('started_work');
            $table->string('position');
            $table->integer('department_id')->unsigned();
            $table->string('password');
            $table->char('api_token', 60)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
    }
}
