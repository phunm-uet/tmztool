<?php

use Illuminate\Database\Seeder;

class SourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sources')->truncate();
        DB::table('sources')->insert(array(
        	array('name' => 'Google','level' => 1),
        	array('name' => 'Hai','level' => 1),
        	array('name' => 'Social Network','level' => 1),
        	array('name' => 'Forum','level' => 1),
        	array('name' => 'Website','level' => 1),
        	array('name' => 'Google Search','level' => 2),
        	array('name' => 'Google Trend','level' => 2),
        	array('name' => 'Instagram','level' => 2),
        	array('name' => 'Pinterest','level' => 2),
        	array('name' => 'Facebook','level' => 2),
        	array('name' => 'Facebook doi thu','level' => 3),
        	array('name' => 'Facebook Group','level' => 3),
        ));        
    }
}
