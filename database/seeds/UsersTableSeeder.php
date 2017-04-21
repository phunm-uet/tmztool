<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert(array(
        	// admin
        	array('name' => 'Nguyen Van A', 'email' => 'a@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '1','password' => bcrypt('a'),"api_token" => str_random(60)),
        	// Nhan vien idea thuong
        	array('name' => 'Nguyen Van B', 'email' => 'b@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '2','password' => bcrypt('b'),"api_token" => str_random(60)),
        	// Idea Leader
        	array('name' => 'Nguyen Van B1', 'email' => 'b1@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '2','password' => bcrypt('b1'),"api_token" => str_random(60)),
        	// Design thuong
        	array('name' => 'Nguyen Van C', 'email' => 'c@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '3','password' => bcrypt('c'),"api_token" => str_random(60)),
        	// Design leader
        	array('name' => 'Nguyen Van C1', 'email' => 'c1@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '3','password' => bcrypt('c1'),"api_token" => str_random(60)),
        	//Upload
        	array('name' => 'Nguyen Van D', 'email' => 'd@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '4','password' => bcrypt('d'),"api_token" => str_random(60)),
        	// Marketing
            array('name' => 'Nguyen Van E', 'email' => 'e@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '5','password' => bcrypt('e'),"api_token" => str_random(60)),
        	array('name' => 'Nguyen Van F', 'email' => 'f@gmail.com','started_work' => '2016-01-01','position'=>'FullTime','department_id' => '5','password' => bcrypt('f'),"api_token" => str_random(60))
        ));
    }
}
