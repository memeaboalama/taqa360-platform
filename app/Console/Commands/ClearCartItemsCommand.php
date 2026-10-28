<?php

namespace App\Console\Commands;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\Driver\Jobs\SearchForDriverJob;
use App\Domain\MarketPlace\Models\Cart;
use App\Domain\MarketPlace\Models\CartItem;
use App\Domain\MarketPlace\Models\MerchantChoice;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Models\MerchantOrderItem;
use App\Support\Enum\StatusEnum;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\CartChoice;
use Domain\MarketPlace\Models\TempOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Log;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ClearCartItemsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cart-items-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all cart items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        echo Carbon::now()->subMinutes(5)->format('Y-m-d H:i:s') . " now";
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        CartChoice::truncate();
//        CartItem::truncate();
//        Cart::truncate();
//        MerchantChoice::truncate();
//        MerchantOrderItem::truncate();
//        MerchantOrder::truncate();
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
//        echo "cleared" . "\n";


    }
}
