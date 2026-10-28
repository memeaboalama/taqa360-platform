<?php

namespace App\Application\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Domain\MarketPlace\Models\Favorite;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $user = Auth::user();

        $favorites = Favorite::with(['item' => function($query) {
                $query->with(['files']); 
            }])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('ecommerce::pages.wishlist-legacy', compact('favorites'));
    }
}
