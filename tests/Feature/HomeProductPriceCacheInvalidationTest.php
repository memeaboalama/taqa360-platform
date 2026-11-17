<?php

namespace Tests\Feature;

use Domain\MarketPlace\Models\Item;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeProductPriceCacheInvalidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');

        Schema::create('items', function (Blueprint $table): void {
            $table->id();
            $table->json('title')->nullable();
            $table->decimal('default_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('is_on_sale')->default(false);
            $table->timestamp('sale_expires_at')->nullable();
            $table->string('status')->nullable();
            $table->integer('stock')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('items');

        parent::tearDown();
    }

    public function test_price_changes_invalidate_cached_home_data_for_each_storefront_locale(): void
    {
        Cache::shouldReceive('forget')
            ->once()
            ->with('home_page_data_storefront_v4_ar');
        Cache::shouldReceive('forget')
            ->once()
            ->with('home_page_data_storefront_v4_en');

        $item = Item::create(['default_price' => 100]);
        $item->default_price = 120;
        $item->save();
    }
}
