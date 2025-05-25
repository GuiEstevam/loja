<?php

// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            'Masculino',
            'Feminino',
            'Infantil',
            'Esportivo',
            'Casual'
        ];
        foreach ($categorias as $cat) {
            Category::firstOrCreate([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'active' => true,
            ]);
        }
    }
}
