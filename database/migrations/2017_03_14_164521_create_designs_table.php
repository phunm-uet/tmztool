<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idea_design_id')->unsigned();
            $table->dateTime('start_design')->nullable();
            $table->dateTime('finish_design')->nullable();
            $table->string('link_PNG')->nullable();
            $table->string('link_banner')->nullable();
            $table->string('link_banner_retarget')->nullable();
            $table->integer('num_return')->default(0);
            $table->string('progress')->nullable();
            $table->integer('status_approve')->default(0);
            $table->text('reason_approve')->nullable();
            $table->text('link_psd')->nullable();
            $table->text('link_draft')->nullable();
            $table->string('name_design')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('idea_design_id')->references('id')->on('idea_design')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designs');
    }
}
