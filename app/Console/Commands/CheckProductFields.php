<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProductFields extends Command
{
    protected $signature = 'products:check-fields';
    protected $description = 'Verifica os campos dos produtos';

    public function handle()
    {
        $this->info('Verificando campos dos produtos...');

        $products = Product::all();

        foreach ($products as $product) {
            $this->line(sprintf(
                '%s - €%.2f - Rating: %.1f (%d) - New: %s - Sale: %s - Shipping: %s - Installments: %dx €%.2f',
                $product->name,
                $product->price,
                $product->rating,
                $product->rating_count,
                $product->is_new ? 'Yes' : 'No',
                $product->is_sale ? 'Yes' : 'No',
                $product->free_shipping ? 'Free' : 'Paid',
                $product->installments,
                $product->installment_value
            ));
        }

        return Command::SUCCESS;
    }
}
