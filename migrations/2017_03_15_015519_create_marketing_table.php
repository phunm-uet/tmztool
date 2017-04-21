<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->integer('desgin_upload_id')->unsigned();
            // $table->integer('status');
            $table->text('link_ads')->nullable();
            $table->timestamps('last_modify');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('desgin_upload_id')->references('id')->on('design_upload')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketing');
    }
}
