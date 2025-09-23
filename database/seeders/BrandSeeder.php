<?php

// database/seeders/BrandSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $marcas = ['Nike', 'Adidas', 'Puma', 'New Balance', 'Asics', 'Fila', 'Reebok'];
        foreach ($marcas as $marca) {
            Brand::firstOrCreate([
                'name' => $marca,
                'slug' => Str::slug($marca),
                'active' => true,
            ]);
        }
    }
}
