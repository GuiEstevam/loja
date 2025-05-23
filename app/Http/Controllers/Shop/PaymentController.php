<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id());
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('shop.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        if ($payment->order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('shop.payments.show', compact('payment'));
    }
}
