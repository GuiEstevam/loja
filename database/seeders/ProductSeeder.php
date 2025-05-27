<?php

// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $produtos = [
            [
                'name' => 'Nike Air Max 90',
                'brand' => 'Nike',
                'categories' => ['Masculino', 'Esportivo'],
                'colors' => ['Branco', 'Preto'],
                'sizes' => ['40', '41', '42'],
                'image' => 'product_1.jpg',
                'price' => 699.90,
                'stock' => 10,
            ],
            [
                'name' => 'Adidas Ultraboost',
                'brand' => 'Adidas',
                'categories' => ['Masculino', 'Esportivo'],
                'colors' => ['Preto', 'Azul'],
                'sizes' => ['39', '40', '41'],
                'image' => 'product_2.jpg',
                'price' => 799.90,
                'stock' => 8,
            ],
            [
                'name' => 'Puma RS-X',
                'brand' => 'Puma',
                'categories' => ['Feminino', 'Casual'],
                'colors' => ['Branco', 'Vermelho'],
                'sizes' => ['36', '37', '38'],
                'image' => 'product_3.jpg',
                'price' => 549.90,
                'stock' => 12,
            ],
            [
                'name' => 'New Balance 574',
                'brand' => 'New Balance',
                'categories' => ['Masculino', 'Casual'],
                'colors' => ['Azul', 'Verde'],
                'sizes' => ['42', '43', '44'],
                'image' => 'product_4.jpg',
                'price' => 599.90,
                'stock' => 6,
            ],
            [
                'name' => 'Fila Disruptor',
                'brand' => 'Fila',
                'categories' => ['Feminino', 'Casual'],
                'colors' => ['Branco', 'Preto'],
                'sizes' => ['36', '37', '38'],
                'image' => 'product_5.jpg',
                'price' => 499.90,
                'stock' => 9,
            ],
        ];

        foreach ($produtos as $i => $data) {
            $brand = Brand::where('name', $data['brand'])->first();
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . ($i + 1),
                'description' => 'Tênis ' . $data['name'] . ' - conforto, estilo e performance.',
                'price' => $data['price'],
                'stock' => $data['stock'],
                'image' => $data['image'],
                'active' => true,
                'brand_id' => $brand ? $brand->id : null,
                'sku' => strtoupper(Str::random(8)),
                'featured' => $i < 2,
            ]);

            // Relacionamentos
            $categoryIds = Category::whereIn('name', $data['categories'])->pluck('id');
            $colorIds = Color::whereIn('name', $data['colors'])->pluck('id');
            $sizeIds = Size::whereIn('name', $data['sizes'])->pluck('id');

            $product->categories()->sync($categoryIds);
            $product->colors()->sync($colorIds);
            $product->sizes()->sync($sizeIds);
        }
    }
}
