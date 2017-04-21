<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call(DepartmentTableSeeder::class);
        $this->call(FieldTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(NichesTableSeeder::class);
        $this->call(PlatformsTableSeeder::class);
        $this->call(SourcesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UsersManagerTabeSeeder::class);
        $this->call(TypeProductsSeeder::class);
        $this->call(PageSeeder::class);
    }
}
