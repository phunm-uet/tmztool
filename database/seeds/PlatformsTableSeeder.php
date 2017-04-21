<?php

use Illuminate\Database\Seeder;

class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("platforms")->truncate();
        DB::table('platforms')->insert(array(
        	array('name' => 'Sunfrog',"url" => "https://sunfrog.com"),
        	array('name' => 'Teespring','url' => 'https://teespring.com'),
        	array('name' => 'Amazon','url' => 'https://amazon.com'),
        ));
    }
}
