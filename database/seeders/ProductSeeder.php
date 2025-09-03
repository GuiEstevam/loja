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
                'price' => 89.99,
                'stock' => 10,
                'free_shipping' => true,
                'rating' => 4.8,
                'rating_count' => 127,
                'installments' => 6,
                'installment_value' => 14.99,
                'is_new' => false,
                'is_sale' => false,
            ],
            [
                'name' => 'Adidas Ultraboost',
                'brand' => 'Adidas',
                'categories' => ['Masculino', 'Esportivo'],
                'colors' => ['Preto', 'Azul'],
                'sizes' => ['39', '40', '41'],
                'image' => 'product_2.jpg',
                'price' => 129.99,
                'stock' => 8,
                'free_shipping' => true,
                'rating' => 4.9,
                'rating_count' => 89,
                'installments' => 10,
                'installment_value' => 12.99,
                'is_new' => true,
                'is_sale' => false,
            ],
            [
                'name' => 'Puma RS-X',
                'brand' => 'Puma',
                'categories' => ['Feminino', 'Casual'],
                'colors' => ['Branco', 'Vermelho'],
                'sizes' => ['36', '37', '38'],
                'image' => 'product_3.jpg',
                'price' => 69.99,
                'stock' => 12,
                'free_shipping' => false,
                'rating' => 4.6,
                'rating_count' => 203,
                'installments' => 5,
                'installment_value' => 13.99,
                'is_new' => false,
                'is_sale' => true,
                'sale_price' => 49.99,
            ],
            [
                'name' => 'New Balance 574',
                'brand' => 'New Balance',
                'categories' => ['Masculino', 'Casual'],
                'colors' => ['Azul', 'Verde'],
                'sizes' => ['42', '43', '44'],
                'image' => 'product_4.jpg',
                'price' => 79.99,
                'stock' => 6,
                'free_shipping' => true,
                'rating' => 4.7,
                'rating_count' => 156,
                'installments' => 8,
                'installment_value' => 9.99,
                'is_new' => false,
                'is_sale' => false,
            ],
            [
                'name' => 'Fila Disruptor',
                'brand' => 'Fila',
                'categories' => ['Feminino', 'Casual'],
                'colors' => ['Branco', 'Preto'],
                'sizes' => ['36', '37', '38'],
                'image' => 'product_5.jpg',
                'price' => 64.99,
                'stock' => 9,
                'free_shipping' => false,
                'rating' => 4.5,
                'rating_count' => 78,
                'installments' => 4,
                'installment_value' => 16.24,
                'is_new' => true,
                'is_sale' => true,
                'sale_price' => 44.99,
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
                'free_shipping' => $data['free_shipping'] ?? false,
                'rating' => $data['rating'] ?? 0.0,
                'rating_count' => $data['rating_count'] ?? 0,
                'installments' => $data['installments'] ?? 1,
                'installment_value' => $data['installment_value'] ?? null,
                'is_new' => $data['is_new'] ?? false,
                'is_sale' => $data['is_sale'] ?? false,
                'sale_price' => $data['sale_price'] ?? null,
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
