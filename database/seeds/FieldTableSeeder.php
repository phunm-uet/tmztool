<?php

use Illuminate\Database\Seeder;

class FieldTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fields')->truncate();
        DB::table('fields')->insert(array(
        	array('name' => 'TradeMark'),
        	array('name' => 'Evergreen'),
        	array('name' => 'Trend'),
        	array('name' => 'Season')
        ));        
    }
}
