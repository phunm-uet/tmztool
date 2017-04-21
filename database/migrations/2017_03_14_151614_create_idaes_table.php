<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdaesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ideas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('audience');
            $table->string('link');
            $table->text('reason')->nullable();
            $table->text('deploy_description');
            $table->integer('staff_id')->unsigned();
            $table->integer('niche_id')->unsigned();
            $table->integer('niche_id_2')->unsigned()->nullable();
            $table->integer('field_id')->unsigned();
            $table->integer('idea_leader_id')->unsigned()->nullable();
            $table->date('date_approve')->nullable();
            $table->integer('result_approve')->default(0);
            $table->text('reason_approve')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('num_design')->default(1);
            $table->integer('source_id')->unsigned();
            // Source level 2
            $table->integer('source_id_2')->unsigned()->nullable();
            // Source level 3
            $table->integer('source_id_3')->unsigned()->nullable();
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->foreign('niche_id')->references('id')->on('niches')->onDelete('cascade');
            $table->foreign('niche_id_2')->references('id')->on('niches')->onDelete('cascade');
            $table->foreign('source_id')->references('id')->on('sources')->onDelete('cascade');
            $table->foreign('source_id_2')->references('id')->on('sources')->onDelete('cascade');
            $table->foreign('source_id_3')->references('id')->on('sources')->onDelete('cascade');
            $table->foreign('idea_leader_id')->references('user_id')->on('user_manager')->onDelete('cascade');
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ideas');
    }
}
