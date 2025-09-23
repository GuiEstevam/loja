<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    // Lista cupons ativos para o cliente (exemplo)
    public function index()
    {
        $discounts = Discount::where('active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            })
            ->paginate(10);

        return view('shop.discounts.index', compact('discounts'));
    }

    // Mostra detalhes do cupom
    public function show(Discount $discount)
    {
        if (!$discount->active) {
            abort(404);
        }

        return view('shop.discounts.show', compact('discount'));
    }
}
