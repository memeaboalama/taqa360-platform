<?php

namespace Tests\Unit;

use App\Domain\MarketPlace\Actions\Dashboard\Profile\UpdateBasicInfoAction;
use App\Domain\MarketPlace\Filters\MerchantBranchFilter;
use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Support\Services\ImagesServices\ImageService;
use Domain\MarketPlace\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class MerchantTagFilterModel extends Model
{
    protected $table = 'merchants';
    public $timestamps = false;
}

class MerchantTagBranchFilterModel extends Model
{
    protected $table = 'merchant_branches';
    public $timestamps = false;
}

class MerchantTagsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'merchant_tags_test', 'database.connections.merchant_tags_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('merchant_tags_test');

        Schema::connection('merchant_tags_test')->create('merchants', function (Blueprint $table) {
            $table->id();
            $table->text('main_categories')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::connection('merchant_tags_test')->create('merchant_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
        });
        Schema::connection('merchant_tags_test')->create('market_place_category_merchants', function (Blueprint $table) {
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('market_category_id');
        });
    }

    public function test_tag_filter_matches_exact_ids_for_merchants_and_their_branches(): void
    {
        DB::table('merchants')->insert([
            ['id' => 1, 'main_categories' => '1'],
            ['id' => 2, 'main_categories' => '10'],
            ['id' => 3, 'main_categories' => '2,1'],
            ['id' => 4, 'main_categories' => '2,10'],
        ]);
        DB::table('merchant_branches')->insert([
            ['id' => 11, 'merchant_id' => 1],
            ['id' => 12, 'merchant_id' => 2],
            ['id' => 13, 'merchant_id' => 3],
            ['id' => 14, 'merchant_id' => 4],
        ]);

        $merchantIds = (new MerchantFilter(['main_category' => [1]]))
            ->apply((new MerchantTagFilterModel())->newQuery())
            ->orderBy('id')
            ->pluck('id')
            ->all();
        $branchIds = (new MerchantBranchFilter(['main_category' => [1]]))
            ->apply((new MerchantTagBranchFilterModel())->newQuery()->join(
                'merchants',
                'merchants.id',
                '=',
                'merchant_branches.merchant_id'
            ))
            ->orderBy('merchant_branches.id')
            ->pluck('merchant_branches.id')
            ->all();

        $this->assertSame([1, 3], $merchantIds);
        $this->assertSame([11, 13], $branchIds);

        $multipleTagMerchantIds = (new MerchantFilter(['main_category' => [1, 2]]))
            ->apply((new MerchantTagFilterModel())->newQuery())
            ->orderBy('id')
            ->pluck('id')
            ->all();
        $unfilteredMerchantIds = (new MerchantFilter(['main_category' => []]))
            ->apply((new MerchantTagFilterModel())->newQuery())
            ->orderBy('id')
            ->pluck('id')
            ->all();

        $this->assertSame([1, 3, 4], $multipleTagMerchantIds);
        $this->assertSame([1, 2, 3, 4], $unfilteredMerchantIds);
    }

    public function test_updating_empty_tag_selection_clears_saved_tags(): void
    {
        DB::table('merchants')->insert([
            'id' => 1,
            'main_categories' => '1,2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $action = new UpdateBasicInfoAction(
            new MerchantRepository(new Merchant()),
            Mockery::mock(ImageService::class)
        );

        $action->handle(1, ['main_category_ids' => [3, 10], 'marketplace_category_id' => 5]);
        $this->assertSame('3,10', DB::table('merchants')->where('id', 1)->value('main_categories'));

        $action->handle(1, ['main_category_ids' => [], 'marketplace_category_id' => 5]);
        $this->assertNull(DB::table('merchants')->where('id', 1)->value('main_categories'));
    }
}
