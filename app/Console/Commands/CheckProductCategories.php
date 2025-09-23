<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CheckProductCategories extends Command
{
    protected $signature = 'products:check-categories';
    protected $description = 'Verificar relacionamento entre produtos e categorias';

    public function handle()
    {
        $this->info('Verificando relacionamento entre produtos e categorias...');

        $products = Product::with('categories')->get();

        $this->table(
            ['Produto', 'Categorias', 'Total Categorias'],
            $products->map(function ($product) {
                return [
                    $product->name,
                    $product->categories->pluck('name')->join(', '),
                    $product->categories->count()
                ];
            })->toArray()
        );

        $this->info('Total de produtos: ' . $products->count());
        $this->info('Total de relacionamentos: ' . $products->sum(function ($p) {
            return $p->categories->count();
        }));

        return Command::SUCCESS;
    }
}
