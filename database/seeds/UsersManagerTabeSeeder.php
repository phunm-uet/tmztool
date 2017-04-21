<?php

use Illuminate\Database\Seeder;

class UsersManagerTabeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('user_manager')->truncate();
        DB::table('user_manager')->insert(array(
        	array('user_id' => 1 ,'department_id' => 1),
        	array('user_id' => 3 ,'department_id' => 2),
        	array('user_id' => 5 ,'department_id' => 3),
        	array('user_id' => 6 ,'department_id' => 4),
        	array('user_id' => 7 ,'department_id' => 5),
        ));
    }
}
