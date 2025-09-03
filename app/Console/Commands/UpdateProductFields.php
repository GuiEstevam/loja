<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class UpdateProductFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza os campos modernos dos produtos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Atualizando campos dos produtos...');

        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());

        foreach ($products as $product) {
            // Calcular parcelas automaticamente (10x sem juros)
            $installments = 10;
            $installment_value = round($product->price / $installments, 2);

            // Definir alguns produtos como novos ou em promoção
            $is_new = rand(0, 1) == 1;
            $is_sale = rand(0, 1) == 1;
            $sale_price = $is_sale ? round($product->price * 0.8, 2) : null;

            // Rating aleatório entre 4.0 e 5.0
            $rating = round(4.0 + (rand(0, 10) / 10), 1);
            $rating_count = rand(50, 300);

            // Frete grátis para produtos acima de €50
            $free_shipping = $product->price >= 50;

            $product->update([
                'free_shipping' => $free_shipping,
                'rating' => $rating,
                'rating_count' => $rating_count,
                'installments' => $installments,
                'installment_value' => $installment_value,
                'is_new' => $is_new,
                'is_sale' => $is_sale,
                'sale_price' => $sale_price,
                'sale_ends_at' => $is_sale ? now()->addDays(30) : null,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Produtos atualizados com sucesso!');

        return Command::SUCCESS;
    }
}
