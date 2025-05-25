<?php

// database/seeders/ColorSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run()
    {
        $cores = [
            ['name' => 'Branco', 'hex_code' => '#FFFFFF'],
            ['name' => 'Preto', 'hex_code' => '#000000'],
            ['name' => 'Azul', 'hex_code' => '#1E40AF'],
            ['name' => 'Vermelho', 'hex_code' => '#DC2626'],
            ['name' => 'Verde', 'hex_code' => '#16A34A'],
        ];
        foreach ($cores as $cor) {
            Color::firstOrCreate($cor + ['active' => true]);
        }
    }
}
