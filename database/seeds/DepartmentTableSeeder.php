<?php

use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('department')->truncate();
        DB::table('department')->insert(array(
        	array('name' => 'admin','slug' => 'admin'),
        	array('name' => 'idea','slug' => 'idea'),
        	array('name' => 'design','slug' => 'design'),
        	array('name' => 'upload','slug' => 'upload'),
        	array('name' => 'marketing','slug' => 'marketing'),
        ));
    }
}
