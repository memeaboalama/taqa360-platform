<?php

namespace Tests\Feature;

use App\Domain\AppBase\Models\AdBanner;
use App\Support\Enum\FileTypesEnum;
use App\Support\Services\ImagesServices\ImageService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdBannerRenderingTest extends TestCase
{
    public function test_no_action_has_no_destination(): void
    {
        $banner = new AdBanner();
        $banner->click_action = [
            'type' => 'none',
            'url' => 'https://example.com/old-destination',
        ];

        $this->assertNull($banner->click_url);
    }

    public function test_selected_actions_resolve_to_their_saved_destinations(): void
    {
        app()->setLocale('en');

        $url = new AdBanner();
        $url->click_action = ['type' => 'url', 'url' => 'https://example.com/campaign'];

        $product = new AdBanner();
        $product->click_action = ['type' => 'product', 'id' => 42];

        $merchant = new AdBanner();
        $merchant->click_action = ['type' => 'merchant', 'id' => 17];

        $categoryQuery = 'main_category=2&sub_category=8&child_category=11';
        $category = new AdBanner();
        $category->click_action = ['type' => 'category', 'id' => 11, 'url' => "https://store.example/en/categories?{$categoryQuery}"];

        $this->assertSame('https://example.com/campaign', $url->click_url);
        $this->assertSame('/en/product/42', parse_url($product->click_url, PHP_URL_PATH));
        $this->assertSame('/en/ecommerce/merchant/17', parse_url($merchant->click_url, PHP_URL_PATH));
        $this->assertSame('/en/categories', parse_url($category->click_url, PHP_URL_PATH));
        $this->assertSame($categoryQuery, parse_url($category->click_url, PHP_URL_QUERY));

        app()->setLocale('ar');
        $this->assertSame('/ar/categories', parse_url($category->click_url, PHP_URL_PATH));
    }

    public function test_device_image_urls_use_the_matching_saved_image(): void
    {
        Storage::fake('fileDisk');
        Storage::disk('fileDisk')->put('ads/desktop.webp', 'desktop');
        Storage::disk('fileDisk')->put('ads/tablet.webp', 'tablet');
        Storage::disk('fileDisk')->put('ads/mobile.webp', 'mobile');

        $banner = new AdBanner();
        $banner->content = [
            'images' => [
                'desktop' => 'ads/desktop.webp',
                'tablet' => 'ads/tablet.webp',
                'mobile' => 'ads/mobile.webp',
            ],
        ];

        $disk = Storage::disk('fileDisk');
        $this->assertSame($disk->url('ads/desktop.webp'), $banner->getImageUrl('desktop'));
        $this->assertSame($disk->url('ads/tablet.webp'), $banner->getImageUrl('tablet'));
        $this->assertSame($disk->url('ads/mobile.webp'), $banner->getImageUrl('mobile'));
    }

    public function test_titles_and_subtitles_are_localized(): void
    {
        $banner = new AdBanner();
        $banner->title = ['en' => 'English title', 'ar' => 'عنوان عربي'];
        $banner->subtitle = ['en' => 'English description', 'ar' => 'وصف عربي'];

        app()->setLocale('ar');
        $this->assertSame('عنوان عربي', $banner->localized_title);
        $this->assertSame('وصف عربي', $banner->localized_subtitle);

        app()->setLocale('en');
        $this->assertSame('English title', $banner->localized_title);
        $this->assertSame('English description', $banner->localized_subtitle);
    }

    public function test_banner_upload_rejects_non_image_files(): void
    {
        $file = UploadedFile::fake()->create('payload.json', 10, 'application/json');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not a valid image');

        app(ImageService::class)->storeImage(
            $file,
            'upload/ads/banners',
            FileTypesEnum::BANNER
        );
    }
}
