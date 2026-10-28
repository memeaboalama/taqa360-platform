<?php

use App\Application\Admin\Controllers\Merchant\MerchantController;
use App\Application\Admin\Controllers\Merchant\PackageController;
use App\Application\Admin\Controllers\Merchant\MerchantOrdersController;
use App\Application\Admin\Controllers\ProviderController;
use App\Application\Admin\Controllers\ServicesController;
use App\Application\Admin\Controllers\OrderController;
use App\Application\Admin\Controllers\User\UserController;
use App\Domain\MarketPlace\Models\MerchantOrder;
use Application\Admin\Controllers\AdminController;
use App\Application\Admin\Controllers\Merchant\MerchantCouponController;
use App\Application\Admin\Controllers\Merchant\MerchantCashBackController;
use Application\Admin\Controllers\CashBackController;
use Application\Admin\Controllers\Catalog\CatalogController;
use Application\Admin\Controllers\FilesController;
use Application\Admin\Controllers\RoleController;
use Application\Admin\Controllers\NationalityController;
use Application\Admin\Controllers\Brands\BrandController;
use Application\Admin\Controllers\Brands\ModelController;
use Application\Admin\Controllers\Towing\CityController as TowingCityController;
use Application\Admin\Controllers\Towing\LineController;
use Application\Admin\Controllers\CityController;
use Application\Admin\Controllers\CountryController;
use Application\Admin\Controllers\RegionController;
use Application\Admin\Controllers\DistrictController;
use Application\Admin\Controllers\AuthController;
use Application\Admin\Controllers\PasswordController;
use Application\Admin\Controllers\ContactUsController;
use Application\Admin\Controllers\EmployeesController;
use Application\Admin\Controllers\Category\MainCategoryController;
use Application\Admin\Controllers\Category\MerchantCategoryController;
use Application\Admin\Controllers\Category\ItemController;
use Application\Admin\Controllers\Category\ProviderCategoryController;
use Application\Admin\Controllers\Category\MarketplaceCategoryController;
use Application\Admin\Controllers\BannerController;
use Application\Admin\Controllers\BannerSpacesController;
use Application\Admin\Controllers\AdsContentController;
use Application\Admin\Controllers\AdPageController;
use Application\Admin\Controllers\AdPlacementController;
use Application\Admin\Controllers\AdBannerController;
use Application\Admin\Controllers\Catalog\CategoryController;
use Application\Admin\Controllers\SpecificationController;
use Application\Admin\Controllers\CouponController;
use Application\Admin\Controllers\OfferController;
use Application\Admin\Controllers\DriverController;
use Application\Admin\Controllers\HistoryDriverController;
use Application\Admin\Controllers\DriverRegisterController;
use Application\Admin\Controllers\SalesAgentController;
use Application\Admin\Controllers\NotificationController;
use Application\Admin\Controllers\TermsController;
use Application\Admin\Controllers\PoliciesController;
use Application\Admin\Controllers\GeneralSettingController;
use Application\Admin\Controllers\RefundPaymentController;
use Application\Admin\Controllers\TypeServicesController;
use Application\Admin\Controllers\Company\CompanyController;
use Application\Admin\Controllers\Educator\EducatorController;
use Application\Admin\Controllers\Finance\Reports\Orders\MerchantOrderReportController;
use Application\Admin\Controllers\Finance\Reports\Orders\DriverOrderReportController;
use Application\Admin\Controllers\Finance\Reports\Driver\InvoiceDeliveryController;

use Application\Admin\Controllers\WalletController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Pusher\Pusher;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;


/*
|--------------------------------------------------------------------------
| Web Routes - Merchant
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () { //...
    Route::middleware('checkAdmin')->prefix('admin')->as('admin:')->group(function () {
        Route::get('/home', [AdminController::class, 'index'])->name('home');

        Route::prefix('merchants')->as('merchant.')->group(function () {
            Route::get('/', [MerchantController::class, 'index']);
            Route::get('/pending', [MerchantController::class, 'getPending']);
            Route::get('/tracking', [MerchantController::class, 'trackMerchantBranches']);
            Route::get('/menu/changes', [MerchantController::class, 'getMenuChanges']);
            Route::get('/pending/items/merchant/{id}', [MerchantController::class, 'getPendingItems']);
            Route::get('/pending/{id}', [MerchantController::class, 'showPending'])->whereNumber('id')->name('pending.show');

            Route::prefix('orders')->as('orders.')->group(function () {
                Route::get('/', [OrderController::class, 'index']);
                Route::get('/get/{status?}', [OrderController::class, 'index']);
                Route::get('/show/{id}', [OrderController::class, 'showOrder'])->name('showOrder');
                Route::get('/loadPDF/{id}', [OrderController::class, 'showInvoice'])->name('pdf');
            });

            Route::get('/{id}', [MerchantController::class, 'show'])->whereNumber('id')->name('show');
            Route::get('/{merchant_id}/orders/{id}', [MerchantController::class, 'show_orders'])->name('orders.show');
        });

        /*
        Route::prefix('finance')->as('finance.')->group(function () {
            Route::prefix('reports')->as('reports.')->group(function () {
                Route::prefix('orders')->as('orders.')->group(function () {
                    Route::prefix('merchant')->as('merchant.')->group(function () {
                        Route::get('/', [MerchantOrderReportController::class, 'index'])->name('index');
                    });

                    Route::prefix('driver')->as('driver.')->group(function () {
                        Route::get('/', [DriverOrderReportController::class, 'index'])->name('index');
                    });
                });

                Route::prefix('drivers')->as('drivers.')->group(function () {
                    Route::prefix('invoice')->as('invoice.')->group(function () {
                        Route::get('/delivery', [InvoiceDeliveryController::class, 'index'])->name('delivery');
                        Route::get('delivery/loadPDF/{id}', [InvoiceDeliveryController::class, 'show'])->name('pdf');
                    });
                });
            });
        });
        */

        Route::prefix('drivers')->as('drivers.')->group(function () {
            Route::get('/', [DriverController::class, 'index']);
            Route::get('/history', [HistoryDriverController::class, 'history']);
            Route::get('show/{id}', [DriverController::class, 'show'])->name('show');
            Route::get('showDriver/{id}', [DriverRegisterController::class, 'showDriver'])->name('showDriver');
            Route::get('/tracking', [DriverController::class, 'trackDrivers']);
            Route::get('/pending', [DriverController::class, 'getPending']);
            Route::get('/register', [DriverRegisterController::class, 'register']);
        });

        Route::prefix('educators')->as('educators.')->group(function () {
            Route::get('/', [EducatorController::class, 'index']);
            Route::get('show/{id}', [EducatorController::class, 'show'])->name('show');
            Route::get('showEducator/{id}', [DriverRegisterController::class, 'showDriver'])->name('showDriver');
            Route::get('/pending', [EducatorController::class, 'getPending']);
        });


        Route::prefix('salesagents')->as('salesagents.')->group(function () {
            Route::get('/', [SalesAgentController::class, 'index']);
            Route::get('show/{id}', [SalesAgentController::class, 'show'])->name('show');
            Route::get('/pending', [SalesAgentController::class, 'getPending']);
        });

        Route::prefix('companies')->as('companies.')->group(function () {
            Route::get('/', [CompanyController::class, 'index']);
            Route::get('/{id}', [CompanyController::class, 'show']);
        });

        Route::prefix('employees')->as('employees.')->group(function () {
            Route::get('/', [EmployeesController::class, 'index'])->name('all');
        });

        // Banner & Space
        Route::prefix('banner-spaces')->as('banner-spaces.')->group(function () {
            Route::get('/', [BannerSpacesController::class, 'index'])->name('index');
            Route::get('/page/{page}', [BannerSpacesController::class, 'show'])->name('show');
        });

        Route::prefix('ads-content')->as('ads-content.')->group(function () {
            Route::get('/', [AdsContentController::class, 'index'])->name('index');
            Route::get('/placement/{placement}', [AdsContentController::class, 'manage'])->name('manage');
        });


        Route::prefix('roles')->as('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::get('/update/{id}', [RoleController::class, 'update'])->name('update');
        });

        Route::prefix('packages')->as('packages.')->group(function () {
            Route::get('/', [PackageController::class, 'index'])->name('all');
            Route::get('/{id}', [PackageController::class, 'update'])->name('update');
        });

        Route::prefix('services')->as('services.')->group(function () {
            Route::get('/', [ServicesController::class, 'index'])->name('all');
            Route::get('/{id}', [ServicesController::class, 'showMenu'])->name('showMenu');
        });
        Route::prefix('typeservices')->as('typeservices.')->group(function () {
            Route::get('/', [TypeServicesController::class, 'index'])->name('create');


        });

        Route::prefix('customers')->as('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::get('/{user_id}/marketplace-orders/{id}', [UserController::class, 'show_orders'])
                ->name('orders.show');
        });

        Route::prefix('terms')->as('terms.')->group(function () {
            Route::get('/', [TermsController::class, 'update'])->name('update');
        });

        Route::prefix('policies')->as('policies.')->group(function () {
            Route::get('/', [PoliciesController::class, 'update'])->name('update');
        });

        Route::prefix('nationalities')->as('nationalities.')->group(function () {
            Route::get('/', [NationalityController::class, 'index'])->name('index');
        });


        Route::prefix('countries')->as('countries.')->group(function () {
            Route::get('/', [CountryController::class, 'index'])->name('index');
        });

        Route::prefix('cities')->as('cities.')->group(function () {
            Route::get('/', [CityController::class, 'index'])->name('index');
        });

        Route::prefix('regions')->as('regions.')->group(function () {
            Route::get('/', [RegionController::class, 'index'])->name('index');
        });

        Route::prefix('districts')->as('districts.')->group(function () {
            Route::get('/', [DistrictController::class, 'index'])->name('index');
        });

        Route::prefix('brands')->as('brands.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::get('/models', [ModelController::class, 'index'])->name('models.index');
        });

        Route::prefix('messages')->as('messages.')->group(function () {
            Route::get('/', [ContactUsController::class, 'index'])->name('index');
        });

        Route::prefix('general-settings')->as('settings.')->group(function () {
            Route::get('/', [GeneralSettingController::class, 'index'])->name('index');
        });

        Route::prefix('towing/cities')->as('towing.cities.')->group(function () {
            Route::get('/', [TowingCityController::class, 'index'])->name('index');
            Route::get('/lines', [LineController::class, 'index'])->name('lines.index');
        });

        Route::prefix('categories')->as('categories.')->group(function () {
            Route::get('/main', [MainCategoryController::class, 'index'])->name('main');
            Route::get('/marketplace', [MarketplaceCategoryController::class, 'index'])->name('marketplace');
            Route::get('/merchant', [MerchantCategoryController::class, 'index'])->name('merchant');
            Route::get('/provider', [ProviderCategoryController::class, 'index'])->name('provider');
            Route::get('/items/{category_id}', [ItemController::class, 'index'])->name('items');
        });

        Route::prefix('catalogs')->as('catalogs.')->group(function () {
            Route::get('/categories', [CatalogController::class, 'getCategoriesPage'])->name('categories');
            Route::get('/subcategories', [CatalogController::class, 'getSubcategoriesPage'])->name('subcategories');
            Route::get('/chaildCategories', [CatalogController::class, 'getChaildCategoriesPage'])->name('chaildCategories');
        });

        Route::prefix('vars')->as('vars.')->group(function () {
            Route::get('/banners', [BannerController::class, 'index'])->name('banners');
        });
        Route::prefix('vars')->as('vars.')->group(function () {
            Route::get('/specifications', [SpecificationController::class, 'index'])->name('specifications');
            Route::view('/brands', 'admin::livewire.catalog.brand.index')->name('brands.index');
            Route::view('/car-types', 'admin::livewire.catalog.car-type.index')->name('car-types');
        });


        // Ad Management
        Route::prefix('ad-management')->as('ad-management.')->group(function () {
            Route::prefix('pages')->as('pages.')->group(function () {
                Route::get('/', [AdPageController::class, 'index'])->name('index');
            });

            Route::prefix('placements')->as('placements.')->group(function () {
                Route::get('/{pageId}', [AdPlacementController::class, 'index'])->name('index');
                Route::get('/{placementId}/show', [AdPlacementController::class, 'show'])->name('show');
            });

            Route::prefix('banners')->as('banners.')->group(function () {
                Route::get('/{placementId}', [AdBannerController::class, 'index'])->name('index');
            });
        });



        Route::prefix('notification')->as('notification.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
        });
        Route::prefix('offer')->as('offer.')->group(function () {
            Route::get('/', [OfferController::class, 'index'])->name('all');
        });
        Route::prefix('coupons')->as('coupons.')->group(function () {
            Route::get('/', [CouponController::class, 'index'])->name('all');
            Route::get('/{id}', [CouponController::class, 'selectmerchants'])->name('selectmerchants');

        });
        Route::prefix('merchantCoupons')->as('merchantCoupons.')->group(function () {
            Route::get('/', [MerchantCouponController::class, 'index'])->name('all');
        });
        Route::prefix('cashBack')->as('cashBack.')->group(function () {
            Route::get('/', [CashBackController::class, 'index'])->name('all');
            Route::get('/{id}', [CashBackController::class, 'selectmerchants'])->name('selectmerchants');

        });
        Route::prefix('merchantsCashBack')->as('merchantsCashBack.')->group(function () {
            Route::get('/', [MerchantCashBackController::class, 'index'])->name('all');
        });
        Route::prefix('refundPayment')->as('refundPayment.')->group(function () {
            Route::get('/', [RefundPaymentController::class, 'index'])->name('all');
        });
        Route::prefix('wallet')->as('wallet.')->group(function () {
            Route::get('/coupon', [WalletController::class, 'walletCoupons'])->name('coupon.all');
            Route::get('/users', [WalletController::class, 'users'])->name('users.all');
            Route::get('/{id}', [WalletController::class, 'selectmerchants'])->name('selectmerchants');
            Route::get('/users/history/{id}', [WalletController::class, 'history'])->name('user.history');


        });
    });


    Route::get('admin/login', [AuthController::class, 'index'])->name('admin.login');

    Route::get('admin/forget-password', [PasswordController::class, 'forgetPassword'])
        ->name('admin.forgetPassword');

    Route::get('admin/verify-otp', [PasswordController::class, 'verifyOTP'])
        ->name('admin.verifyOTP');

    Route::get('admin/reset-password', [PasswordController::class, 'resetPassword'])
        ->name('admin.resetPassword');


});
Route::get('push-test',function (){
    $json = file_get_contents('data.json');


//converting json object to php associative array
    $data = json_decode($json, true);

//    foreach ($data as $datum){
//       $merchant= \Domain\MarketPlace\Models\Merchant::create([
//            'company_name'=>[
//                'en'=>$datum["name"],
//                'ar'=>$datum["name_ar"],
//            ],
//            'email'=>$datum["email"],
//            'phone'=>$datum["mobileNumber"],
//            'dialcode'=>$datum["countryCode"]??"966",
//            'country_name'=>$datum["country"]??"Saudi Arabia (‫المملكة العربية السعودية‬)",
//            'country_code'=>'sa',
//            'country_id'=>1,
////            'city_id'=>'',
////            'distract_id'=>'',
//            'status'=>'active',
//            'password'=>\Illuminate\Support\Facades\Hash::make('123456'),
//
//        ]);
//    }



});

// Route::get('/today-image-urls',[FilesController::class,'getTodayImageUrls']);
// Route::get('/all-image-urls',[FilesController::class,'getAllImageUrls']);

Route::post('/pusher/auth', function (\Illuminate\Http\Request $request) {
    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
            //            'useTLS' => true,
        ]
    );

    $presenceData = ['user_id' => 1]; // Modify this as per your requirements
    $auth = $pusher->authorizeChannel($channelName, $socketId);

    return response($auth);
});
