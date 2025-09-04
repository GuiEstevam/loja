<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class AddSkuToExistingProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::whereNull('sku')->orWhere('sku', '')->get();

        foreach ($products as $product) {
            // Gerar SKU baseado no nome do produto
            $baseSku = Str::upper(Str::slug($product->name, ''));
            $sku = $baseSku . '-' . Str::random(4);

            $product->update(['sku' => $sku]);

            $this->command->info("SKU adicionado ao produto {$product->name}: {$sku}");
        }

        $this->command->info("Total de produtos atualizados: " . $products->count());
    }
}
