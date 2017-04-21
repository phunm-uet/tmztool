<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('page_managers')->truncate();
    	DB::table('page_managers')->insert(array(
    		array("id" => 1,"page_id" => "890441891061330","page_name" => "Snowmobile Club"),
    		array("id" => 2,"page_id" => "2","page_name" => "T-Shirt"),
    		array("id" => 3,"page_id" => "3","page_name" => "Mug")
    	));
    }
}
