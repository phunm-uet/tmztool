<?php

use Illuminate\Database\Seeder;

class NichesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('niches')->truncate();
        DB::table('niches')->insert(array(
        	array('name' => 'Dog','level' => 1),
        	array('name' => 'Cat','level' => 1),
        	array('name' => 'Horse','level' => 1),
        	array('name' => 'Veteran','level' => 1),
        	array('name' => 'NFL','level' => 2),
        	array('name' => 'NCCA','level' => 2),
        ));
    }
}
