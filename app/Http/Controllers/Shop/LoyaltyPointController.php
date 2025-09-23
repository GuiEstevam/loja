<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use Illuminate\Support\Facades\Auth;

class LoyaltyPointController extends Controller
{
    public function index()
    {
        $loyaltyPoint = LoyaltyPoint::where('user_id', Auth::id())->first();

        return view('shop.loyalty_points.index', compact('loyaltyPoint'));
    }
}
