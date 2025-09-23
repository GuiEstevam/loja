<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id());
        })->with('product')->paginate(10);

        return view('shop.order_items.index', compact('orderItems'));
    }

    public function show(OrderItem $orderItem)
    {
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('shop.order_items.show', compact('orderItem'));
    }
}
