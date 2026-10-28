<?php

use App\Application\Api\Controllers\User\Auth\AuthController;
use App\Domain\Provider\Models\Company;
use App\Http\Controllers\BackupFilesController;
use App\Http\Controllers\ElmController;
use App\Http\Controllers\RegoionsController;
use App\Http\Controllers\StorageFileController;
use App\Websocket\MyCustomWebSocketHandler;
use Application\Ecommerce\Controllers\EcommerceController;
use Application\Marketplace\Controllers\MerchantController;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


WebSocketsRouter::webSocket('/app/{appkey}', MyCustomWebSocketHandler::class);


Route::get('/companyPass', function () {
    Company::first()->update(['password' => Hash::make('password')]);
});

Route::get('/user/{user?}', function () {
    Auth::guard('web')->loginUsingId(request('user', 1), false);
    return view('user-echo-test');
});


Route::get('/debug-login', function () {
    Auth::guard('provider')->loginUsingId(1, false);
    return view('echo-test');
})->name('debug.login');


// Route::get('/', function () {
//     return redirect('/admin/login');
// });

Route::get('/', [EcommerceController::class, 'index'])->name('home');

Route::get('/storage/{path}', [StorageFileController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.file');

// Every store route lives inside a locale-prefixed group (see
// app/Application/Ecommerce/routes/web.php), so a bare "/merchants" used to 404.
// Send it to the same page in the visitor's current locale instead of erroring.
Route::get('/merchants', function () {
    return redirect()->route('merchants.index');
})->name('merchants.locale-less');

Route::get('regions', [RegoionsController::class, 'createRegionsAndCities'])->name('regions');
Route::get('carstypes', [RegoionsController::class, 'createCarsTyeps'])->name('carstypes');

Route::get('send', [ElmController::class, 'send']);

Route::get('/today-image-urls', [BackupFilesController::class, 'getTodayImageUrls']);
Route::get('/all-image-urls', [BackupFilesController::class, 'getAllImageUrls']);

Route::get('/test', function () {
    return file_get_contents(public_path('dist/index.html'));
});

Route::get('/huloolcom', function () {
    return file_get_contents(public_path('huloolcom/index.html'));
});

Route::get('language/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'Cache cleared successfully';
});
//Route::post('/file', function (\Illuminate\Http\Request $request) {
//   $data= (New \App\Support\Services\ImagesServices\ImageService())
//        ->storeImage($request->file,"upload/file",\App\Support\Enum\FileTypesEnum::LOGO);
//
//   \Domain\MarketPlace\Models\Merchant::first()->files()->create($data);
//
//   dd($data);
//
//})->name('file');

