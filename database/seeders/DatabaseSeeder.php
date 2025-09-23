<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
