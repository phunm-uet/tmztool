<?php

use Illuminate\Database\Seeder;

class TypeProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('type_products')->truncate();
    	DB::table('type_products')->insert(array(
    		array("id" => 1,"product_type" => "Dây chuyền"),
    		array("id" => 2,"product_type" => "T-Shirt"),
    		array("id" => 3,"product_type" => "Mug")
    	));
    }
}
