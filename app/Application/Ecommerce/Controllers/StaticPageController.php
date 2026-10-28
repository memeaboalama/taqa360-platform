<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\MarketPlace\Models\Favorite;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StaticPageController extends Controller
{
    public function loginPage()
    {
        if (Auth::check()) {
            return redirect()->route('account.index');
        }

        return view('ecommerce::pages.auth.login');
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
                'remember' => ['sometimes', 'boolean'],
                'redirect' => ['nullable', 'string'],
            ]);

            if (!Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password'],
            ], $request->boolean('remember'))) {
                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->with('error', __('ecommerce::ecommerce.auth.validation.credentials_do_not_match'));
            }

            $user = Auth::user();

            // ---------------------------------------------------------------------------
            // [DISABLED - email verification, comment-only] The block below logged the user
            // out and forced them to /verify whenever email_verified_at was null, which
            // blocked sign-in for any account that had not confirmed its email. This
            // handler also ignored config('auth.email_verification.enabled'), so it kept
            // blocking even when the switch was off.
            // Kept commented (not deleted) so it can be restored later: uncomment the
            // block below to re-enforce verification on this login route.
            // NOTE: the password / credential check (Auth::attempt above) is untouched.
            // ---------------------------------------------------------------------------
            // if (!$user->email_verified_at) {
            //     Auth::guard('web')->logout();
            //     session()->put('ecommerce_user_id_for_verification', $user->id);
            //     session()->put('ecommerce_user_email_for_verification', $user->email);
            //     return redirect()->route('verify')
            //         ->with('error', __('ecommerce::ecommerce.auth.validation.email_not_verified'));
            // }

            $request->session()->regenerate();

            $redirectTo = $validated['redirect'] ?? session('url.intended', route('home'));

            return redirect()->to($redirectTo)
                ->with('success', __('ecommerce::ecommerce.auth.alerts.login_success'));
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->only('email', 'remember'));
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->with('error', __('ecommerce::ecommerce.auth.alerts.login_error'));
        }
    }

    public function checkoutPage()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login', ['redirect' => url('/checkout')]);
        }

        $user = Auth::user();
        $cart = $user->webCart()->with('cartItems.item')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('home');
        }

        $addresses = $user->addresses;
        $cities = City::where('status', 'active')->get();
        $countries = Country::where('status', 'active')->get();

        return view('ecommerce::pages.checkout.checkout', compact('user', 'cart', 'addresses', 'cities', 'countries'));
    }

    public function wishlistPage()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        $user = Auth::user();
        $favorites = Favorite::with(['item' => function ($query) {
            $query->with(['files']);
        }])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('ecommerce::pages.wishlist', compact('favorites'));
    }

    public function productPage($item_id)
    {
        $product = Item::with([
            'merchant',
            'mainCategory',
            'subCategory',
            'childCategory',
            'files',
        ])
            ->where('id', $item_id)
            ->where('status', 'active')
            ->first();

        if (!$product) {
            abort(404, 'Product not found');
        }

        $relatedProducts = Item::with(['merchant'])
            ->where('status', 'active')
            ->where('main_category_id', $product->main_category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $isFavorite = Auth::check() && Favorite::where('user_id', Auth::id())
            ->where('item_id', $product->id)
            ->exists();

        $relatedFavoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->whereIn('item_id', $relatedProducts->pluck('id'))->pluck('item_id')->flip()->all()
            : [];

        return view('ecommerce::pages.product.product-details-static', compact('product', 'relatedProducts', 'isFavorite', 'relatedFavoriteIds'));
    }
}
