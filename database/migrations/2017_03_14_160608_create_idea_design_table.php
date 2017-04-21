<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdeaDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idea_design', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idea_id')->unsigned();
            $table->integer('staff_design_id')->unsigned()->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->dateTime('task_start')->nullable();
            $table->timestamps();
            $table->foreign('idea_id')->references('id')->on('ideas')->onDelete('cascade');
            $table->foreign('staff_design_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('idea_design');
    }
}
