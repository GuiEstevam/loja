<?php

// database/seeders/SizeSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run()
    {
        foreach (range(36, 44) as $tam) {
            Size::firstOrCreate(['name' => (string)$tam, 'active' => true]);
        }
    }
}
