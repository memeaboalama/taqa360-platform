<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\AppBase\Actions\Home\FetchHomeDataAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\MarketPlace\Models\Favorite;
use App\Domain\MarketPlace\Models\MerchantOrderItem;
use App\Domain\MarketPlace\Models\ProductReview;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Application\Marketplace\Enums\MerchantOrderStatuses;

class StaticPageController extends Controller
{
    public function termsPage(
        FetchGeneralSettingsAction $fetchGeneralSettingsAction,
        FetchHomeDataAction $fetchHomeDataAction
    ) {
        return $this->legalPage('terms', $fetchGeneralSettingsAction, $fetchHomeDataAction);
    }

    public function privacyPage(
        FetchGeneralSettingsAction $fetchGeneralSettingsAction,
        FetchHomeDataAction $fetchHomeDataAction
    ) {
        return $this->legalPage('privacy', $fetchGeneralSettingsAction, $fetchHomeDataAction);
    }

    public function contactPage(FetchHomeDataAction $fetchHomeDataAction)
    {
        $footerData = $fetchHomeDataAction->getStoreFooterData() ?? [];
        $currentLocale = app()->getLocale();
        $isRTL = in_array($currentLocale, ['ar', 'he'], true);
        $pageTitle = __('ecommerce::ecommerce.contact_us');

        return view('ecommerce::pages.contact', compact(
            'footerData',
            'currentLocale',
            'isRTL',
            'pageTitle'
        ));
    }

    private function legalPage(
        string $page,
        FetchGeneralSettingsAction $fetchGeneralSettingsAction,
        FetchHomeDataAction $fetchHomeDataAction
    ) {
        $legalContent = $fetchGeneralSettingsAction->handle([
            'terms_conditions_ar',
            'terms_conditions_en',
            'policies_ar',
            'policies_en',
        ]);
        $contentKey = $page === 'terms' ? 'terms_conditions' : 'policies';
        $currentLocale = app()->getLocale();
        $isRTL = in_array($currentLocale, ['ar', 'he'], true);
        $content = data_get($legalContent, $contentKey . '_' . $currentLocale)
            ?? data_get($legalContent, $contentKey . '_en')
            ?? data_get($legalContent, $contentKey . '_ar')
            ?? '';
        $pageTitle = $page === 'terms'
            ? __('ecommerce::ecommerce.terms_conditions')
            : __('ecommerce::ecommerce.privacy_policy');
        $footerData = $fetchHomeDataAction->getStoreFooterData() ?? [];

        return view('ecommerce::pages.legal-content', compact(
            'content',
            'legalContent',
            'footerData',
            'currentLocale',
            'isRTL',
            'pageTitle'
        ));
    }

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

            if (config('auth.email_verification.enabled', true) && !$user->email_verified_at) {
                Auth::guard('web')->logout();
                session()->put('ecommerce_user_id_for_verification', $user->id);
                session()->put('ecommerce_user_email_for_verification', $user->email);

                return redirect()->route('verify')
                    ->with('error', __('ecommerce::ecommerce.auth.validation.email_not_verified'));
            }

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
        $citiesByCountry = $cities->groupBy('country_id')->map(function ($countryCities) {
            return $countryCities->map(function (City $city) {
                return ['id' => $city->id, 'title' => $city->title];
            })->values();
        });
        $countries = Country::where('status', 'active')->get();

        return view('ecommerce::pages.checkout.checkout', compact('user', 'cart', 'addresses', 'cities', 'citiesByCountry', 'countries'));
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
            ->whereHas('item', fn ($query) => $query->where('status', 'active')->availableForStorefront())
            ->latest()
            ->paginate(12);

        return view('ecommerce::pages.wishlist', compact('favorites'));
    }

    public function productPage($item_id)
    {
        $product = Item::with([
            'merchant.brand',
            'mainCategory',
            'subCategory',
            'childCategory',
            'catalogSpecifications.properties' => fn ($query) => $query
                ->where('specification_properties.status', 'active'),
            'files',
            'activeItemPrices.type_service',
            'reviews' => fn ($query) => $query->approved()->with('user')->latest(),
        ])
            ->withReviewSummary()
            ->where('id', $item_id)
            ->where('status', 'active')
            ->availableForStorefront()
            ->first();

        if (!$product) {
            abort(404, 'Product not found');
        }

        $relatedProducts = Item::with(['merchant'])
            ->withReviewSummary()
            ->where('status', 'active')
            ->availableForStorefront()
            ->where('main_category_id', $product->main_category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        $hasCompletedPurchase = Auth::check() && MerchantOrderItem::query()
            ->where('item_id', $product->id)
            ->whereHas('order', fn ($query) => $query
                ->where('user_id', Auth::id())
                ->where('status', MerchantOrderStatuses::PREVIOUS->value))
            ->exists();
        $existingReview = Auth::check() ? ProductReview::query()
            ->where('item_id', $product->id)
            ->where('user_id', Auth::id())
            ->first() : null;
        $canReview = $hasCompletedPurchase && $existingReview === null;

        $isFavorite = Auth::check() && Favorite::where('user_id', Auth::id())
            ->where('item_id', $product->id)
            ->exists();

        $relatedFavoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->whereIn('item_id', $relatedProducts->pluck('id'))->pluck('item_id')->flip()->all()
            : [];

        return view('ecommerce::pages.product.product-details-static', compact(
            'product',
            'relatedProducts',
            'isFavorite',
            'relatedFavoriteIds',
            'canReview',
            'hasCompletedPurchase',
            'existingReview'
        ));
    }
}
