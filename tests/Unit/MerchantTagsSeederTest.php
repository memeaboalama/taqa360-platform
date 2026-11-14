<?php

namespace Tests\Unit;

use App\Domain\AppBase\Models\Category\MainCategory;
use App\Support\Enum\MainCategoryType;
use App\Support\Enum\StatusEnum;
use Database\Seeders\Merchant\MerchantTagsSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MerchantTagsSeederTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'merchant_tag_seeder_test', 'database.connections.merchant_tag_seeder_test' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]]);
        DB::purge('merchant_tag_seeder_test');

        Schema::connection('merchant_tag_seeder_test')->create('main_categories', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('type');
            $table->string('status');
            $table->timestamps();
        });
        Schema::connection('merchant_tag_seeder_test')->create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->text('main_categories')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function test_default_store_tags_are_bilingual_assigned_and_idempotent(): void
    {
        MainCategory::create([
            'title' => ['ar' => 'تصنيف خدمة', 'en' => 'Service category'],
            'type' => MainCategoryType::SERVICE->value,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        MainCategory::create([
            'title' => ['ar' => 'Category3', 'en' => 'Category3'],
            'type' => MainCategoryType::MARKET->value,
            'status' => StatusEnum::ACTIVE->value,
        ]);
        DB::table('merchants')->insert([
            ['id' => 6, 'email' => 'demo.solar@demo.local', 'main_categories' => '99'],
            ['id' => 7, 'email' => 'demo.wind@demo.local', 'main_categories' => null],
            ['id' => 8, 'email' => 'demo.safety@demo.local', 'main_categories' => null],
            ['id' => 9, 'email' => 'other@demo.local', 'main_categories' => null],
        ]);

        $seeder = new MerchantTagsSeeder();
        $seeder->run();

        $tags = MainCategory::query()->where('type', MainCategoryType::MARKET->value)->get();
        $this->assertCount(10, $tags);
        $this->assertSame(
            'Renewable Energy',
            $tags->firstWhere('id', 2)->getTranslation('title', 'en')
        );
        $this->assertSame('Service category', MainCategory::find(1)->getTranslation('title', 'en'));

        $tagIdsByTitle = $tags->mapWithKeys(fn ($tag) => [$tag->getTranslation('title', 'en') => (int) $tag->id]);
        $solarIds = array_map('intval', explode(',', DB::table('merchants')->where('id', 6)->value('main_categories')));
        $windIds = array_map('intval', explode(',', DB::table('merchants')->where('id', 7)->value('main_categories')));
        $safetyIds = array_map('intval', explode(',', DB::table('merchants')->where('id', 8)->value('main_categories')));

        $this->assertContains(99, $solarIds);
        $this->assertContains($tagIdsByTitle['Solar Energy'], $solarIds);
        $this->assertContains($tagIdsByTitle['Wind Energy'], $windIds);
        $this->assertContains($tagIdsByTitle['Safety Equipment'], $safetyIds);
        $this->assertNull(DB::table('merchants')->where('id', 9)->value('main_categories'));

        $seeder->run();

        $this->assertSame(10, MainCategory::query()->where('type', MainCategoryType::MARKET->value)->count());
        $this->assertCount(
            count($solarIds),
            array_unique(array_map('intval', explode(',', DB::table('merchants')->where('id', 6)->value('main_categories'))))
        );
    }
}
