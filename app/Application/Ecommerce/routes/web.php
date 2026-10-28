<?php

use App\Application\Ecommerce\Controllers\AccountController;
use App\Application\Ecommerce\Controllers\AuthController;
use App\Application\Ecommerce\Controllers\CartController;
use App\Application\Ecommerce\Controllers\CategoryController;
use App\Application\Ecommerce\Controllers\CheckoutController;
use App\Application\Ecommerce\Controllers\FilterController;
use App\Application\Ecommerce\Controllers\MerchantController;
use App\Application\Ecommerce\Controllers\OfferController;
use App\Application\Ecommerce\Controllers\ProductController;
use App\Application\Ecommerce\Controllers\WishlistController;
use Application\Ecommerce\Controllers\EcommerceController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Providers\RouteServiceProvider;






/*
|--------------------------------------------------------------------------
| Web Routes - Ecommerce
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() ?: LaravelLocalization::getCurrentLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localeCookieRedirect']
    ],
    function () {

        Route::get('/ecommerce/index', [EcommerceController::class, 'index']);

        Route::get('/', [EcommerceController::class, 'staticIndex'])->name('ecommerce.static');
        Route::get('/ecommerce-static', function () {
            return redirect()->route('ecommerce.static');
        });
        Route::get('/checkout', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'checkoutPage'])->name('checkout');
        Route::post('/login', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'login'])->name('auth.login.post');


        Route::middleware(['auth:web'])->group(function() {
             Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
             Route::get('/checkout/legacy', [CheckoutController::class, 'index'])->name('checkout.index');
             Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
             Route::post('/service-requests/store', [\App\Application\Educational\Controllers\ServiceRequestWebController::class, 'store'])->name('service-requests.store');
        });

        Route::controller(EcommerceController::class)->group(function () {

            Route::get('/home', 'index')->name('ecommerce.home');
            Route::get('/index', 'index')->name('ecommerce.index');

            Route::get('/search','search')->name('search');
            Route::get('/filter/category/{categoryId?}', 'filterByCategory')->name('products.by-category');
            Route::get('/filter/live', 'liveFilter')->name('products.live-filter');

            Route::get('/load-more', 'loadMoreProducts')->name('products.load-more');
            Route::get('/quick-view/{id}', 'quickView')->name('quick-view');

            Route::get('/search-suggestions', 'searchSuggestions')->name('search.suggestions');
            Route::get('/featured-products', 'featuredProducts')->name('products.featured');
            Route::get('/new-arrivals', 'getNewArrivals')->name('products.new-arrivals');
            Route::get('/best-sellers', 'getBestSellers')->name('products.best-sellers');

            Route::post('/save-filters', 'saveFilterState')->name('filters.save');
            Route::get('/filter', 'filterProducts')->name('filters.get');
            Route::get('/home/tabs/{placementId}/{groupKey}', [FilterController::class, 'filterTabs'])->name('home.tab.filter');
        });

    Route::get('/merchants', [MerchantController::class, 'staticIndex'])->name('merchants.index');

        Route::prefix('ecommerce/merchant')->controller(MerchantController::class)->group(function () {
            Route::get('/{merchant}', 'show')->name('merchant.show');
        });
        Route::get('/merchant/{merchant}/filter', [MerchantController::class, 'filter'])->name('merchant.filter');
        Route::get('/products', [ProductController::class, 'fetch'])->name('products.fetch');
        Route::get('/products/{item_id}', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'productPage'])->name('product.show');
        Route::get('/categories/{category_id}/products', [ProductController::class, 'showItemsByCategory'])->name('products.by-category');


        Route::get('/categories', [CategoryController::class, 'staticIndex'])->name('categories.index');
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/search', [CategoryController::class, 'search'])->name('categories.search');
        Route::get('/filter', [CategoryController::class, 'filter'])->name('categories.filter');
        Route::get('/search/suggestions', [CategoryController::class, 'searchSuggestions'])->name('search.suggestions');

        Route::get('/special-offers', [OfferController::class, 'index'])->name('offers.index');
        Route::get('/offers', function () {
            return redirect()->route('offers.index');
        });

        Route::get('/product/{item_id}', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'productPage'])->name('product.show');
        Route::get('/quick-view/{id}', [ProductController::class, 'quickView'])->name('quick-view');


        Route::prefix('checkout')->controller(CheckoutController::class)->middleware('auth')->group(function () {
            Route::get('/legacy', 'index')->name('checkout.index');
            Route::post('/apply-coupon', 'applyCoupon')->name('checkout.apply-coupon');
            Route::post('/remove-coupon', 'removeCoupon')->name('checkout.remove-coupon');
            Route::post('/store', 'store')->name('checkout.store');
        });

        Route::prefix('cart')->controller(CartController::class)->group(function(){

    Route::get('', 'staticIndex')->name('cart.index');
            Route::post('/add', 'addItem')->name('cart.add');
            Route::post('/remove', 'removeItem')->name('cart.remove');
            Route::post('/clear', 'clearItems')->name('cart.clear');
        Route::post('/switch-to-store', 'switchToStore')->name('cart.switch-to-store');
            Route::post('/update-qty', 'updateQty')->name('cart.update-qty');

            Route::get('/header', 'getCartHeader')->name('cart.header');
            Route::get('/sidebar', 'getCartSidebar')->name('cart.sidebar');
        });



        Route::get('/wishlist', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'wishlistPage'])->name('wishlist.index');
        Route::get('/product-page/{item_id}', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'productPage'])->name('product.static');
        Route::post('/add-to-wishlist', [ProductController::class, 'addToWishlist'])->name('wishlist.add');
        Route::post('/add-review', [ProductController::class, 'addReview'])->name('reviews.store');

        Route::get('/api/products', [ProductController::class, 'fetch'])->name('products.fetch');
        Route::get('/api/categories/{category_id}/products', [ProductController::class, 'showItemsByCategory'])->name('products.by-category');
        Route::get('/api/related-products/{productId}', [ProductController::class, 'relatedProducts'])->name('products.related');

        Route::middleware('auth')->group(function () {
            Route::controller(AccountController::class)->prefix('account')->name('account.')->group(function () {
                Route::get('/', 'staticIndex')->name('index');
                Route::get('/profile', 'profile')->name('profile');
                Route::get('/profile/edit', 'editProfile')->name('edit-profile');
                Route::post('/profile/update', 'updateProfile')->name('update-profile');
                Route::get('/orders', 'orders')->name('orders');
                Route::get('/orders/{id}', 'order')->name('order');
                Route::post('/orders/{id}/cancel', 'cancelOrder')->name('order.cancel');
                Route::get('/service-requests', 'serviceRequests')->name('service_requests');
                Route::get('/service-requests/{id}', 'serviceRequest')->name('service_request');
                Route::get('/addresses', 'addresses')->name('addresses');
                Route::post('/addresses/store', 'storeAddress')->name('store_address');
                Route::get('/addresses/create', 'createAddress')->name('create_address');
                Route::get('/addresses/{id}/edit', 'editAddress')->name('edit_address');
                Route::put('/addresses/{id}', 'updateAddress')->name('update_address');
                Route::delete('/addresses/{id}', 'deleteAddress')->name('delete_address');
            });

            Route::controller(\App\Application\Ecommerce\Controllers\WalletController::class)->prefix('wallet')->name('account.wallet.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/deposit', 'deposit')->name('deposit');
                Route::post('/deposit', 'processDeposit')->name('deposit.process');
                Route::post('/apply-coupon', 'applyCoupon')->name('coupon.apply');
                Route::get('/withdraw', 'withdraw')->name('withdraw');
                Route::post('/withdraw', 'processWithdraw')->name('withdraw.process');
            });
    }
);


        Route::get('login', [\App\Application\Ecommerce\Controllers\StaticPageController::class, 'loginPage'])->name('auth.login');
        Route::get('register', [AuthController::class, 'getRegisterPage'])->name('auth.register');
    Route::get('verify', [AuthController::class, 'getVerifyPage'])->name('verify');
    Route::post('login-legacy', [AuthController::class, 'login'])->name('login.post');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
    Route::post('verify', [AuthController::class, 'verify'])->name('verify.post');
    Route::post('resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('forgot-password', [AuthController::class, 'getForgotPasswordPage'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

});



